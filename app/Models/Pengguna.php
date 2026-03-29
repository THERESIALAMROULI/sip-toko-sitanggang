<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Schema;

class Pengguna extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\PenggunaFactory> */
    use HasFactory, Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'nama',
        'role',
        'status',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles, true);
    }

    public function getNameAttribute($value): ?string
    {
        return $value ?? $this->attributes['nama'] ?? null;
    }

    public function setNameAttribute($value): void
    {
        $this->attributes['name'] = $value;

        if (Schema::hasColumn($this->getTable(), 'nama')) {
            $this->attributes['nama'] = $value;
        }
    }

    public function stokHistories(): HasMany
    {
        return $this->hasMany(RiwayatStok::class, 'user_id');
    }
}
