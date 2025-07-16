<?php

namespace Database\Factories;

use App\Enums\TaskStatusEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;


class TaskFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title'       => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'due_date'    => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'user_id'     => User::factory(),
            'parent_id'   => null,
            'status'      => TaskStatusEnum::Pending->value,
        ];
    }
}
