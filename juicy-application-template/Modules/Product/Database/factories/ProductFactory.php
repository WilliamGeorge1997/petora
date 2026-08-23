<?php
namespace Modules\Product\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Product\Entities\Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => ['en' => $this->faker->word,'ar' => $this->faker->word],
            'description' => ['en' =>$this->faker->text,'ar' => $this->faker->text],
            'price' => $this->faker->numberBetween(50,100),
            'new_price' => $this->faker->numberBetween(0,50),
            'category_id' => 1,
        ];
    }
}

