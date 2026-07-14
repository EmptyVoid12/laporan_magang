<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gangguans', function (Blueprint $table) {
            $table->timestamp('submitted_for_verification_at')->nullable()->after('foto');
            $table->timestamp('verified_at')->nullable()->after('submitted_for_verification_at');
            $table->foreignId('verified_by')->nullable()->after('verified_at')->constrained('users')->nullOnDelete();
            $table->text('verification_notes')->nullable()->after('verified_by');
        });
    }

    public function down(): void
    {
        Schema::table('gangguans', function (Blueprint $table) {
            $table->dropConstrainedForeignId('verified_by');
            $table->dropColumn([
                'submitted_for_verification_at',
                'verified_at',
                'verification_notes',
            ]);
        });
    }
};
