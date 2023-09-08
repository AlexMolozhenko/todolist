<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new database based on .env settings';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $databaseName = env('DB_DATABASE', false);

        if (!$databaseName) {
            $this->info('Database name not found in .env');
            return;
        }

        $charset = config('database.connections.mysql.charset', 'utf8mb4');
        $collation = config('database.connections.mysql.collation', 'utf8mb4_unicode_ci');

        config(["database.connections.mysql.database" => null]);

        DB::statement("CREATE DATABASE $databaseName CHARACTER SET $charset COLLATE $collation;");

        config(["database.connections.mysql.database" => $databaseName]);

        $this->info("Database $databaseName created successfully");
//        return Command::SUCCESS;
    }
}
