<?php
namespace App\Http\Controllers;

use App\Models\Meja;
use App\Models\Menu;
use App\Models\Reservasi;
use App\Services\ReservasiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

// Alur pelanggan (tanpa login):
// landing -> form reservasi -> pilih meja -> pre-order menu -> bayar -> status
class PelangganController extends Controller
{
    public function home() { return view('public.home'); }
    public function form() { return view('public.form'); }

    public function daftarMenu()
    {
        return view('public.daftar-menu', ['menus' => Menu::orderBy('id')->get()]);
    }

    public function cek(Request $r)
    {
        $r->validate(['kode' => 'required|string']);
        return redirect()->route('status', strtoupper(trim($r->kode)));
    }

    private function aturan(): array
    {
        return [
            'nama' => 'required|string|max:100',
            'jumlah_tamu' => 'required|integer|min:1|max:20',
            'tanggal' => 'required|date|after_or_equal:today',
            'jam' => 'required|date_format:H:i|after_or_equal:08:00|before_or_equal:22:00',
        ];
    }

    private function mejaBisa(string $tanggal, string $jam, int $tamu): array
    {
        return ReservasiService::mejaBisa($tanggal, $jam, $tamu);
    }

    // Langkah 2: pilih meja
    public function pilih(Request $r)
    {
        $d = $r->validate($this->aturan());
        return view('public.meja', [
            'd' => $d,
            'semua' => Meja::orderBy('lantai')->orderByRaw('CAST(nomor AS UNSIGNED)')->get(),
            'bisa' => $this->mejaBisa($d['tanggal'], $d['jam'], $d['jumlah_tamu']),
        ]);
    }

    private function tampilMenu(array $d, array $qty = [], ?string $catatan = null)
    {
        $d = array_intersect_key($d, array_flip(['nama', 'jumlah_tamu', 'tanggal', 'jam', 'meja_id']));
        return view('public.menu_po', [
            'd' => $d, 'qty' => $qty, 'catatan' => $catatan,
            'meja' => Meja::findOrFail($d['meja_id']),
            'menus' => Menu::orderBy('id')->get(),
        ]);
    }

    // Langkah 3: pre-order menu
    public function pilihMenu(Request $r)
    {
        $v = Validator::make($r->all(), $this->aturan() + ['meja_id' => 'required|exists:mejas,id'],
            ['meja_id.required' => 'Pilih meja terlebih dahulu.']);
        if ($v->fails()) {
            return redirect()->route('form')->withErrors($v)->withInput($r->only(['nama', 'jumlah_tamu', 'tanggal', 'jam']));
        }
        $d = $v->validated();
        if (!in_array((int) $d['meja_id'], $this->mejaBisa($d['tanggal'], $d['jam'], $d['jumlah_tamu']), true)) {
            return redirect()->route('form')->withErrors(['meja_id' => 'Meja tersebut sudah dipesan pada jam itu. Silakan pilih ulang.'])
                ->withInput($r->only(['nama', 'jumlah_tamu', 'tanggal', 'jam']));
        }
        return $this->tampilMenu($d);
    }

    // Langkah 4: simpan reservasi + pesanan
    public function store(Request $r)
    {
        $d = $r->validate($this->aturan() + [
            'meja_id' => 'required|exists:mejas,id',
            'items' => 'array', 'items.*' => 'nullable|integer|min:0',
            'catatan' => 'nullable|string|max:255',
        ]);

        $items = collect($d['items'] ?? [])->filter(fn ($q) => (int) $q > 0);
        if ($items->isEmpty()) {
            return $this->tampilMenu($d, $r->input('items', []), $d['catatan'] ?? null)
                ->withErrors(['items' => 'Pilih minimal satu menu.']);
        }
        if (!in_array((int) $d['meja_id'], $this->mejaBisa($d['tanggal'], $d['jam'], $d['jumlah_tamu']), true)) {
            return redirect()->route('form')->withErrors(['meja_id' => 'Meja tersebut sudah dipesan pada jam itu. Silakan pilih ulang.']);
        }

        try {
            $reservasi = ReservasiService::buat($d, $items);
        } catch (ValidationException $e) {
            return $this->tampilMenu($d, $r->input('items', []), $d['catatan'] ?? null)->withErrors($e->errors());
        }

        return redirect()->route('bayar', $reservasi->kode);
    }

    public function bayarForm(string $kode)
    {
        return view('public.bayar', ['r' => Reservasi::with('items.menu', 'meja')->where('kode', $kode)->firstOrFail()]);
    }

    public function bayar(Request $r, string $kode)
    {
        $res = Reservasi::where('kode', $kode)->firstOrFail();
        $d = $r->validate(['metode_bayar' => 'required|in:QRIS,Transfer Bank,E-wallet']);
        if ($res->status === 'menunggu_pembayaran') {
            $res->update(['metode_bayar' => $d['metode_bayar'], 'status' => 'diterima']);
        }
        return redirect()->route('status', $res->kode);
    }

    public function status(string $kode)
    {
        return view('public.status', ['r' => Reservasi::with('items.menu', 'meja')->where('kode', $kode)->firstOrFail()]);
    }
}
