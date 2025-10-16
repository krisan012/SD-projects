<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = \App\Models\User::where('email', 'admin@example.com')->first();
        $user = \App\Models\User::where('email', 'user@example.com')->first();

        if ($admin) {
            \App\Models\Project::factory()
                ->count(3)
                ->withTasks(5)
                ->create(['user_id' => $admin->id]);

            \App\Models\Project::factory()
                ->completed()
                ->create(['user_id' => $admin->id]);
        }

        if ($user) {
            \App\Models\Project::factory()
                ->count(2)
                ->withTasks(3)
                ->create(['user_id' => $user->id]);

            \App\Models\Project::factory()
                ->overdue()
                ->withTasks(4)
                ->create(['user_id' => $user->id]);
        }

        $this->command->info('✅ Sample projects created successfully.');
    }
}
