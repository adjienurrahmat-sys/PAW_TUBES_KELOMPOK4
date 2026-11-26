<?php
namespace App\Http\Controllers;

use App\Models\Reservasi;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    private function query(Request $r)
    {
        $q = Reservasi::with('meja', 'items.menu')->where('status', '!=', 'menunggu_pembayaran');
        if ($r->filled('dari')) $q->whereDate('tanggal', '>=', $r->dari);
        if ($r->filled('sampai')) $q->whereDate('tanggal', '<=', $r->sampai);
        if ($r->filled('status')) $q->where('status', $r->status);
        return $q->orderBy('tanggal', 'desc')->orderBy('jam');
    }

    public function index(Request $r)
    {
        $data = $this->query($r)->get();
        $total = $data->where('status', '!=', 'dibatalkan')->sum('total');
        return view('admin.laporan', compact('data', 'total'));
    }

    public function csv(Request $r)
    {
        $data = $this->query($r)->get();
        return response()->streamDownload(function () use ($data) {
            $f = fopen('php://output', 'w');
            fputcsv($f, ['Kode', 'Tanggal', 'Jam', 'Nama', 'Meja', 'Tamu', 'Metode', 'Status', 'Total']);
            foreach ($data as $x) {
                fputcsv($f, [$x->kode, $x->tanggal->format('Y-m-d'), $x->jam, $x->nama, $x->meja->nomor,
                    $x->jumlah_tamu, $x->metode_bayar, $x->status, $x->total]);
            }
            fclose($f);
        }, 'laporan-petak25.csv', ['Content-Type' => 'text/csv']);
    }
}
