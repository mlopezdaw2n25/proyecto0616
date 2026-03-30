<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Bluemmb\Faker\PicsumPhotosProvider;
use Faker\Factory as FakerFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = FakerFactory::create();
        $faker->addProvider(new PicsumPhotosProvider($faker));
        return [
            'name' => fake()->text(100),
            'slug' => fake()->slug(),
            'extract' => fake()->text(100),
            'body' => fake()->paragraph(),
            'url' => $faker->imageUrl(640, 480, true),
            'status' => fake()->numberBetween(1, 2),
            'category_id' => Category::inRandomOrder()->first(),
            'user_id' => User::inRandomOrder()->first(),
        ];
    }
}
