<?php

use App\Console\Commands\ProjectInitialize;

class FakeCommandComponents
{
    public array $messages = [];

    public function info(string $message): void
    {
        $this->messages[] = $message;
    }
}

class FakeProjectInitializeCommand extends ProjectInitialize
{
    public array $calls = [];

    public FakeCommandComponents $fakeComponents;

    public bool $fresh = false;

    public function __construct()
    {
        parent::__construct();

        $this->fakeComponents = new FakeCommandComponents();
        $this->components = $this->fakeComponents;
    }

    public function option($key = null)
    {
        return $key === 'fresh' ? $this->fresh : parent::option($key);
    }

    public function call($command, array $arguments = [])
    {
        $this->calls[] = [$command, $arguments];

        return self::SUCCESS;
    }
}

it('uses safe migrate flow by default', function () {
    $command = new FakeProjectInitializeCommand();

    $command->handle();

    expect($command->fakeComponents->messages)->toContain(
        'Menjalankan inisialisasi aman tanpa menghapus data yang sudah ada.'
    );

    expect($command->calls)->toBe([
        ['migrate', ['--force' => true]],
        ['shield:generate', ['--all' => true, '--panel' => 'admin']],
        ['db:seed', ['--force' => true]],
        ['filament:optimize-clear', []],
        ['optimize:clear', []],
    ]);
});

it('only resets database when fresh option is explicitly enabled', function () {
    $command = new FakeProjectInitializeCommand();
    $command->fresh = true;

    $command->handle();

    expect($command->fakeComponents->messages)->toContain(
        'Mode fresh aktif: database akan di-reset sebelum inisialisasi.'
    );

    expect($command->calls[0])->toBe(['migrate:fresh', ['--force' => true]]);
});
