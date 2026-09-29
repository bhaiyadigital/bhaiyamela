<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class MergeDatabase extends Command
{
    protected $signature = 'db:merge {file}';
    protected $description = 'Merge SQL file into current database';

    public function handle()
    {
        $file = $this->argument('file');

        if (!File::exists($file)) {
            $this->error("SQL file not found!");
            return;
        }

        $sql = File::get($file);

        try {

            DB::unprepared($sql);

            $this->info("Database merged successfully!");

        } catch (\Exception $e) {

            $this->error("Error: " . $e->getMessage());

        }
    }
}