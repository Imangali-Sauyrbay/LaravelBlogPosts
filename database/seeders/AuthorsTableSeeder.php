<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AuthorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $authorsCount = max((int) $this->command->ask('How many authors should be added?', 20), 0);

        Author::factory($authorsCount)->create([
            'role_id' => Role::where('name', 'user')->first()->id
        ]);

        Author::factory()->create([
            'email' => 'admin@admin.com',
            'role_id' => Role::where('name', 'admin')->first()->id
        ]);
    }
}
