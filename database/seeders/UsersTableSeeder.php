<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      $userCount = max((int)$this->command->ask('Enter user count:', 20), 1);
      User::factory()->testuser()->create();
      User::factory()->count($userCount)->create();

    }
}
