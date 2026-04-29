<?php

namespace App\Support;

use App\Models\Gangguan;
use App\Models\User;
use App\Notifications\TicketActivityNotification;
use Illuminate\Support\Collection;

class GangguanNotifier
{
    public static function sendToUsers(iterable $users, string $title, string $message, ?Gangguan $gangguan = null): void
    {
        $recipients = collect($users)
            ->filter(fn ($user) => $user instanceof User)
            ->unique('id')
            ->values();

        if ($recipients->isEmpty()) {
            return;
        }

        $recipients->each(function (User $recipient) use ($title, $message, $gangguan): void {
            $recipient->notify(new TicketActivityNotification(
                $title,
                $message,
                $gangguan ? self::urlFor($gangguan, $recipient) : null,
                [
                    'ticket_code' => $gangguan?->kode_tiket,
                    'status' => $gangguan?->workflow_status_label,
                ],
            ));
        });
    }

    public static function notifyOperatorAndTeknisi(Gangguan $gangguan, string $title, string $message): void
    {
        self::sendToUsers(self::recipientsForGangguan($gangguan), $title, $message, $gangguan);
    }

    public static function recipientsForGangguan(Gangguan $gangguan): Collection
    {
        return collect([
            $gangguan->operator,
            $gangguan->teknisi,
        ])->filter();
    }

    public static function urlFor(Gangguan $gangguan, ?User $user = null): ?string
    {
        $user ??= auth()->user();

        if (! $user) {
            return null;
        }

        return match ($user->role) {
            'user' => route('user.gangguan.show', $gangguan),
            'teknisi' => route('teknisi.task', ['ticket' => $gangguan->kode_tiket]),
            default => url('/admin/gangguans'),
        };
    }
}
