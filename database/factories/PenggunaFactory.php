<?php
namespace Database\Factories;
use App\Models\Pengguna;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
class PenggunaFactory extends Factory
{
    protected $model = Pengguna::class;
    protected static ?string $password;
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'username' => fake()->unique()->userName(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => []);
    }
}
