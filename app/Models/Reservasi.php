<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Reservasi extends Model
{
    public const STATUS = [
        'menunggu_pembayaran' => 'Menunggu pembayaran',
        'diterima' => 'Diterima (Received)',
        'diproses' => 'Diproses (Preparing)',
        'siap' => 'Siap (Ready)',
        'selesai' => 'Selesai',
        'dibatalkan' => 'Dibatalkan',
    ];

    protected $fillable = ['kode', 'nama', 'jumlah_tamu', 'tanggal', 'jam', 'meja_id', 'metode_bayar', 'catatan', 'sumber', 'total', 'status'];
    protected $casts = ['tanggal' => 'date'];

    public function meja() { return $this->belongsTo(Meja::class); }
    public function items() { return $this->hasMany(ReservasiItem::class); }

    public static function buatKode(): string
    {
        do { $kode = 'RSV-' . strtoupper(Str::random(5)); }
        while (static::where('kode', $kode)->exists());
        return $kode;
    }
}
