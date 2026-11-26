<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservasiItem extends Model
{
    public $timestamps = false;
    protected $fillable = ['reservasi_id', 'menu_id', 'jumlah', 'harga', 'subtotal'];
    public function menu() { return $this->belongsTo(Menu::class); }
}
