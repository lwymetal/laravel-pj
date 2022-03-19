<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

      if ($this->command->confirm('Refresh DB?')) {
        $this->command->call('migrate:refresh');
        $this->command->info('DB refreshed');
      }

      $this->call([
        UsersTableSeeder::class,
        BlogPostsTableSeeder::class,
        CommentsTableSeeder::class
      ]);



    }

}
