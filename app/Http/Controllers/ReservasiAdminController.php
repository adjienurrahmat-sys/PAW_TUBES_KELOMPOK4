<?php
namespace App\Http\Controllers;

use App\Models\Meja;
use App\Models\Menu;
use App\Models\Reservasi;
use App\Services\ReservasiService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ReservasiAdminController extends Controller
{
    public function index(Request $r)
    {
        $q = Reservasi::with('meja', 'items.menu');
        if ($r->filled('q')) {
            $q->where(fn ($w) => $w->where('kode', 'like', "%{$r->q}%")->orWhere('nama', 'like', "%{$r->q}%"));
        }
        if ($r->filled('status')) $q->where('status', $r->status);
        if ($r->filled('tanggal')) $q->whereDate('tanggal', $r->tanggal);
        $sort = in_array($r->sort, ['tanggal', 'total', 'created_at'], true) ? $r->sort : 'tanggal';
        $q->orderBy($sort, $r->dir === 'asc' ? 'asc' : 'desc');
        return view('admin.reservasi', ['data' => $q->paginate(10)->withQueryString()]);
    }

    public function status(Request $r, Reservasi $reservasi)
    {
        $d = $r->validate(['status' => ['required', Rule::in(array_keys(Reservasi::STATUS))]]);
        if ($d['status'] === 'dibatalkan' && $reservasi->status !== 'dibatalkan') {
            foreach ($reservasi->items as $it) $it->menu?->increment('stok', $it->jumlah); // kembalikan stok
        }
        $reservasi->update(['status' => $d['status']]);
        return back()->with('ok', "Status {$reservasi->kode} diperbarui.");
    }

    // Staf mencatat pelanggan yang datang langsung atau memesan lewat WhatsApp
    public function baru()
    {
        return view('admin.reservasi_baru', [
            'mejas' => Meja::orderBy('lantai')->orderByRaw('CAST(nomor AS UNSIGNED)')->get(),
            'menus' => Menu::orderBy('id')->get(),
        ]);
    }

    public function simpan(Request $r)
    {
        $d = $r->validate([
            'nama' => 'required|string|max:100',
            'jumlah_tamu' => 'required|integer|min:1|max:20',
            'tanggal' => 'required|date|after_or_equal:today',
            'jam' => 'required|date_format:H:i|after_or_equal:08:00|before_or_equal:22:00',
            'meja_id' => 'required|exists:mejas,id',
            'items' => 'array', 'items.*' => 'nullable|integer|min:0',
            'catatan' => 'nullable|string|max:255',
            'metode_bayar' => 'required|in:Tunai,QRIS,Transfer Bank,E-wallet',
            'status' => ['required', Rule::in(['diterima', 'diproses', 'siap', 'selesai'])],
        ]);

        $items = collect($d['items'] ?? [])->filter(fn ($q) => (int) $q > 0);
        if ($items->isEmpty()) {
            return back()->withInput()->withErrors(['items' => 'Pilih minimal satu menu.']);
        }
        if (!in_array((int) $d['meja_id'], ReservasiService::mejaBisa($d['tanggal'], $d['jam'], $d['jumlah_tamu']), true)) {
            return back()->withInput()->withErrors(['meja_id' => 'Meja tidak tersedia pada jam itu atau kapasitasnya kurang.']);
        }

        try {
            $res = ReservasiService::buat($d, $items, 'staf', $d['metode_bayar'], $d['status']);
        } catch (ValidationException $e) {
            return back()->withInput()->withErrors($e->errors());
        }
        return redirect()->route('admin.reservasi')->with('ok', "Reservasi {$res->kode} berhasil dicatat.");
    }
}
