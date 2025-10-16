<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_id' => \App\Models\Project::factory(),
            'title' => $this->faker->sentence(4),
            'status' => $this->faker->randomElement(['todo', 'in_progress', 'done']),
            'due_date' => $this->faker->optional(0.7)->dateTimeBetween('now', '+2 months')?->format('Y-m-d'),
        ];
    }

    /**
     * Create a todo task
     */
    public function todo(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'todo',
            ];
        });
    }

    /**
     * Create an in-progress task
     */
    public function inProgress(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'in_progress',
            ];
        });
    }

    /**
     * Create a completed task
     */
    public function done(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'done',
            ];
        });
    }

    /**
     * Create a task with due date
     */
    public function withDueDate(string $date = null): static
    {
        return $this->state(function (array $attributes) use ($date) {
            return [
                'due_date' => $date ?? $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            ];
        });
    }

    /**
     * Create an overdue task
     */
    public function overdue(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'due_date' => $this->faker->dateTimeBetween('-1 month', '-1 day')->format('Y-m-d'),
                'status' => $this->faker->randomElement(['todo', 'in_progress']),
            ];
        });
    }
}
