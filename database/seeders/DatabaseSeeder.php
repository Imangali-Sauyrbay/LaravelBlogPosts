<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if($this->command->confirm('Should database be refreshed?', true)) {
            $this->command->call('migrate:refresh');
            $this->command->newLine(2);
            $this->command->info('DB was refreshed!');
            $this->command->newLine(2);
        }

        Cache::tags(['blogpost'])->flush();

        $this->call([
            AuthorsTableSeeder::class,
            BlogpostsTableSeeder::class,
            CommentsTableSeeder::class
        ]);
    }
}
