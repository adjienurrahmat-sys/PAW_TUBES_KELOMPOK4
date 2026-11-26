<?php
namespace App\Services;

use App\Models\Meja;
use App\Models\Menu;
use App\Models\Reservasi;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ReservasiService
{
    // id meja yang masih bisa dipakai (kapasitas cukup dan belum dipesan pada jam itu)
    public static function mejaBisa(string $tanggal, string $jam, int $tamu): array
    {
        $terpakai = Reservasi::whereDate('tanggal', $tanggal)->where('jam', $jam)
            ->where('status', '!=', 'dibatalkan')->pluck('meja_id');
        return Meja::where('kapasitas', '>=', $tamu)->whereNotIn('id', $terpakai)->pluck('id')->all();
    }

    // Membuat reservasi + pesanan, menghitung total, dan mengurangi stok (satu transaksi database)
    public static function buat(array $d, $items, string $sumber = 'online', ?string $metode = null, string $status = 'menunggu_pembayaran'): Reservasi
    {
        return DB::transaction(function () use ($d, $items, $sumber, $metode, $status) {
            $menus = Menu::whereIn('id', $items->keys())->lockForUpdate()->get()->keyBy('id');
            $total = 0;
            $baris = [];
            foreach ($items as $id => $qty) {
                $m = $menus[$id] ?? null;
                if (!$m || $m->stok < $qty) {
                    throw ValidationException::withMessages(['items' => 'Stok ' . ($m->nama ?? 'menu') . ' tidak cukup.']);
                }
                $sub = $m->harga * (int) $qty;
                $total += $sub;
                $baris[] = ['menu_id' => $m->id, 'jumlah' => (int) $qty, 'harga' => $m->harga, 'subtotal' => $sub];
                $m->decrement('stok', (int) $qty);
            }
            $res = Reservasi::create([
                'kode' => Reservasi::buatKode(),
                'nama' => $d['nama'], 'jumlah_tamu' => $d['jumlah_tamu'],
                'tanggal' => $d['tanggal'], 'jam' => $d['jam'], 'meja_id' => $d['meja_id'],
                'catatan' => $d['catatan'] ?? null, 'sumber' => $sumber,
                'metode_bayar' => $metode, 'status' => $status, 'total' => $total,
            ]);
            $res->items()->createMany($baris);
            return $res;
        });
    }
}
