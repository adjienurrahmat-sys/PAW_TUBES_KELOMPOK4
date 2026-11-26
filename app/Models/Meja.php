<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meja extends Model
{
    protected $fillable = ['nomor', 'kapasitas', 'lantai', 'area', 'pos_x', 'pos_y', 'lebar', 'tinggi'];
    public function reservasis() { return $this->hasMany(Reservasi::class); }
}
