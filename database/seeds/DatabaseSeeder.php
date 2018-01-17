<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // Create default users
        $this->call(UserTableSeeder::class);
        $this->command->info('User table seeded!');

        // Create default cards
        $this->call(CardTableSeeder::class);
        $this->command->info('Card table seeded!');

        Model::reguard();
    }
}
