<?php
namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory {
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
        return [
            'user_id'     => 1,
            'name'        => $this->faker->firstName,
            'subText'     => $this->faker->numberBetween( .5, 20 ),
            'price'       => $this->faker->numberBetween( 200, 3000 ),
            'description' => $this->faker->paragraph( 20 ),
            'image'       => $this->faker->imageUrl( 640, 480 ),
        ];
    }
}