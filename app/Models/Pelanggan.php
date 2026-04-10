<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Pelanggan extends Model
{
    use HasFactory;
    protected $table = 'pelanggans';
    protected $fillable = [
        'name',
        'phone',
        'address',
    ];
    public function getNameAttribute(): ?string
    {
        return $this->attributes['nama'] ?? null;
    }
    public function setNameAttribute(?string $value): void
    {
        $this->attributes['nama'] = $value;
    }
    public function getPhoneAttribute(): ?string
    {
        return $this->attributes['telp'] ?? null;
    }
    public function setPhoneAttribute(?string $value): void
    {
        $this->attributes['telp'] = $value;
    }
    public function getAddressAttribute(): ?string
    {
        return $this->attributes['alamat'] ?? null;
    }
    public function setAddressAttribute(?string $value): void
    {
        $this->attributes['alamat'] = $value;
    }
}
