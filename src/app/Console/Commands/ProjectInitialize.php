<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ProjectInitialize extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:init {--fresh : Reset database sebelum inisialisasi proyek}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Project Initialization';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $shouldResetDatabase = (bool) $this->option('fresh');

        $this->components->info(
            $shouldResetDatabase
                ? 'Mode fresh aktif: database akan di-reset sebelum inisialisasi.'
                : 'Menjalankan inisialisasi aman tanpa menghapus data yang sudah ada.'
        );

        $this->call($shouldResetDatabase ? 'migrate:fresh' : 'migrate', [
            '--force' => true,
        ]);
        $this->call('shield:generate', [
            '--all' => true,
            '--panel' => 'admin',
        ]);
        $this->call('db:seed', [
            '--force' => true,
        ]);

        $this->call('filament:optimize-clear');
        $this->call('optimize:clear');
    }
}
