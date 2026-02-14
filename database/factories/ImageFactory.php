<?php

namespace Database\Factories;

use App\Enum\ImagePath;
use App\Enum\ImageType;
use App\Models\Image;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Image>
 */
class ImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(ImageType::cases());

        return [
            'type' => $type, // cast داری
        ];
    }
    public function configure(): static
    {
        return $this->afterMaking(function (Image $image) {
            // اینجا type نهایی مشخص است (بعد از state)
            $type = $image->type instanceof ImageType
                ? $image->type
                : ImageType::from($image->type);

            $image->path = $type->baseDir() . '/' . fake()->uuid() . '.' . $type->extension();
        });
    }

    public function avatar(): static
    {
        return $this->state(fn() => ['type' => ImageType::Avatar]);
    }

    public function cover(): static
    {
        return $this->state(fn() => ['type' => ImageType::Cover]);
    }

    public function regular(): static
    {
        return $this->state(fn() => ['type' => ImageType::Regular]);
    }
}
