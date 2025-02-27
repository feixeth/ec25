<?php

namespace Database\Factories;

use App\Models\Reports;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reports>
 */
class ReportsFactory extends Factory
{
    /**
     * Le nom du modèle correspondant à cette factory.
     *
     * @var string
     */
    protected $model = Reports::class;

    /**
     * Définit l'état par défaut du modèle.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'reported_entity_id' => $this->faker->randomNumber(5),
            'reported_entity_type' => $this->faker->randomElement([
                'App\\Models\\Post', 
                'App\\Models\\Comment', 
                'App\\Models\\User'
            ]),
            'report_content' => $this->faker->paragraph(),
            'created_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }

    /**
     * Indique que le rapport n'a pas d'entité spécifique (rapport général).
     *
     * @return static
     */
    public function general(): static
    {
        return $this->state(fn (array $attributes) => [
            'reported_entity_id' => null,
            'reported_entity_type' => null,
        ]);
    }

    /**
     * Indique que le rapport concerne un post.
     *
     * @param int|null $postId
     * @return static
     */
    public function forPost(?int $postId = null): static
    {
        return $this->state(fn (array $attributes) => [
            'reported_entity_id' => $postId ?? $this->faker->randomNumber(5),
            'reported_entity_type' => 'App\\Models\\Post',
        ]);
    }

    /**
     * Indique que le rapport concerne un commentaire.
     *
     * @param int|null $commentId
     * @return static
     */
    public function forComment(?int $commentId = null): static
    {
        return $this->state(fn (array $attributes) => [
            'reported_entity_id' => $commentId ?? $this->faker->randomNumber(5),
            'reported_entity_type' => 'App\\Models\\Comment',
        ]);
    }

    /**
     * Indique que le rapport concerne un utilisateur.
     *
     * @param int|null $userId
     * @return static
     */
    public function forUser(?int $userId = null): static
    {
        return $this->state(fn (array $attributes) => [
            'reported_entity_id' => $userId ?? $this->faker->randomNumber(5),
            'reported_entity_type' => 'App\\Models\\User',
        ]);
    }
}