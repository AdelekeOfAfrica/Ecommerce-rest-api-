<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\subCategory>
 */
class subCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subcategory_name = $this->faker->unique()->words($nb=2, $asText=true);
        $slug = Str::slug($subcategory_name);
        return [
            //
            'category_id'=>$this->faker->numberBetween(1,5),
            'name' => $subcategory_name,
            'slug' => $slug
        ];
    }
}
