<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PhotoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->randomDigitNotNull(),
            'albumId' => $this->faker->randomDigitNotNull(),
            'title' => $this->faker->sentence(),
            'url' => $this->faker->imageUrl(),
            'thumbnailUrl' => $this->faker->imageUrl()
        ];
    }
}
