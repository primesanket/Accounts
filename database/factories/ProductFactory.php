<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'product_no' => $this->faker->unique()->numerify('PROD-####'),
            'product_name' => $this->faker->word,
            'company_name' => $this->faker->company,
            'gst' => $this->faker->numberBetween(5, 28), // Assuming GST is a percentage
            'hsn_sac' => $this->faker->numerify('#######'), // 7-digit number
            'opening_stock' => $this->faker->numberBetween(1, 100),
            'remark' => $this->faker->sentence,
        ];
    }
}
