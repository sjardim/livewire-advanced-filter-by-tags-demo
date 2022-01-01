<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class VideoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        //Choose your language
        //Note that catchPhrase() will only return text in US English (en_US)
        $faker = \Faker\Factory::create('pt_BR');
        
        return [
            'title' => $this->faker->catchPhrase(). ' – '. $faker->name(),
            'created_at' => $faker->dateTimeThisMonth(),
            'updated_at' => $faker->dateTimeThisMonth(),
        ];
    }
}
