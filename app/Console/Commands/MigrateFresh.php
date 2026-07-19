<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MigrateFresh extends Command
{

    protected $signature = 'app:migrate-fresh';

    protected $description = 'Command description';

    public function handle()
    {
        $this->info('Running migrate:fresh command...');
        $this->call('migrate:fresh', ['--seed' => true]);
        $this->call('passport:keys', ['--force' => true]);
        $this->call('passport:client',['--personal' => true]);
        $this->info('Migrate fresh completed.');
    }
}
