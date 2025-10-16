<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'deadline' => $this->faker->dateTimeBetween('now', '+3 months')->format('Y-m-d'),
        ];
    }

    /**
     * Create a project with tasks
     */
    public function withTasks(int $count = 3): static
    {
        return $this->has(\App\Models\Task::factory()->count($count));
    }

    /**
     * Create a completed project (all tasks done)
     */
    public function completed(): static
    {
        return $this->withTasks(5)->afterCreating(function ($project) {
            $project->tasks()->update(['status' => 'done']);
        });
    }

    /**
     * Create a project with overdue deadline
     */
    public function overdue(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'deadline' => $this->faker->dateTimeBetween('-1 month', '-1 day')->format('Y-m-d'),
            ];
        });
    }
}
