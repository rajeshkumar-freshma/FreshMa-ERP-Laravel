<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition()
    {
        $name = $this->faker->unique()->word();

        return [
            'name'        => ucfirst($name),
            'slug'        => Str::slug($name),
            'parent_id'   => null,
            'is_featured' => 0,
            'status'      => 1,
        ];
    }

    public function inactive()
    {
        return $this->state(fn () => ['status' => 0]);
    }

    public function child(Category $parent)
    {
        return $this->state(fn () => ['parent_id' => $parent->id]);
    }
}
