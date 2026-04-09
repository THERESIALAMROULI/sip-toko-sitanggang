<?php

// Menentukan namespace agar class berada pada lokasi yang tepat dalam aplikasi.
namespace App\Models;

// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Database\Eloquent\Factories\HasFactory;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Database\Eloquent\Relations\HasMany;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Foundation\Auth\User as Authenticatable;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Notifications\Notifiable;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Support\Facades\Schema;

// Mendefinisikan class sebagai wadah logika pada file ini.
class Pengguna extends Authenticatable
// Membuka blok kode.
{
    /** @use HasFactory<\Database\Factories\PenggunaFactory> */
    // Mengimpor class atau helper yang dibutuhkan pada file ini.
    use HasFactory, Notifiable;

    // Mendefinisikan properti yang akan dipakai pada class ini.
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    // Mendefinisikan properti yang akan dipakai pada class ini.
    protected $fillable = [
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'name',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'email',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'username',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'nama',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'role',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'status',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'password',
    // Menandai bagian dari struktur array yang digunakan pada proses ini.
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    // Mendefinisikan properti yang akan dipakai pada class ini.
    protected $hidden = [
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'password',
        // Baris ini merupakan bagian dari logika proses pada file ini.
        'remember_token',
    // Menandai bagian dari struktur array yang digunakan pada proses ini.
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    // Mendefinisikan method casts untuk menjalankan proses tertentu.
    protected function casts(): array
    // Membuka blok kode.
    {
        // Mengembalikan hasil proses dari method ini.
        return [
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'email_verified_at' => 'datetime',
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'password' => 'hashed',
        // Menandai bagian dari struktur array yang digunakan pada proses ini.
        ];
    // Menutup blok kode.
    }

    // Mendefinisikan method hasRole untuk menjalankan proses tertentu.
    public function hasRole(string $role): bool
    // Membuka blok kode.
    {
        // Mengembalikan hasil proses dari method ini.
        return $this->role === $role;
    // Menutup blok kode.
    }

    // Mendefinisikan method hasAnyRole untuk menjalankan proses tertentu.
    public function hasAnyRole(array $roles): bool
    // Membuka blok kode.
    {
        // Mengembalikan hasil proses dari method ini.
        return in_array($this->role, $roles, true);
    // Menutup blok kode.
    }

    // Mendefinisikan method getNameAttribute untuk menjalankan proses tertentu.
    public function getNameAttribute($value): ?string
    // Membuka blok kode.
    {
        // Mengembalikan hasil proses dari method ini.
        return $value ?? $this->attributes['nama'] ?? null;
    // Menutup blok kode.
    }

    // Mendefinisikan method setNameAttribute untuk menjalankan proses tertentu.
    public function setNameAttribute($value): void
    // Membuka blok kode.
    {
        // Baris ini merupakan bagian dari logika proses pada file ini.
        $this->attributes['name'] = $value;

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (Schema::hasColumn($this->getTable(), 'nama')) {
            // Baris ini merupakan bagian dari logika proses pada file ini.
            $this->attributes['nama'] = $value;
        // Menutup blok kode.
        }
    // Menutup blok kode.
    }

    // Mendefinisikan method stokHistories untuk menjalankan proses tertentu.
    public function stokHistories(): HasMany
    // Membuka blok kode.
    {
        // Mengembalikan hasil proses dari method ini.
        return $this->hasMany(RiwayatStok::class, 'user_id');
    // Menutup blok kode.
    }
// Menutup blok kode.
}
