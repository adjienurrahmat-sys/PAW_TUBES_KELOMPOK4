<?php
namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Reservasi;

class DashboardController extends Controller
{
    public function index()
    {
        $hariIni = now()->toDateString();
        $sah = ['diterima', 'diproses', 'siap', 'selesai'];
        $grafik = [];
        for ($i = 6; $i >= 0; $i--) {
            $tgl = now()->subDays($i)->toDateString();
            $grafik[now()->subDays($i)->format('d/m')] = Reservasi::whereDate('tanggal', $tgl)->whereIn('status', $sah)->count();
        }
        return view('admin.dashboard', [
            'jumlahHariIni' => Reservasi::whereDate('tanggal', $hariIni)->where('status', '!=', 'dibatalkan')->count(),
            'omzetHariIni' => Reservasi::whereDate('tanggal', $hariIni)->whereIn('status', $sah)->sum('total'),
            'menungguBayar' => Reservasi::where('status', 'menunggu_pembayaran')->count(),
            'stokMenipis' => Menu::where('stok', '<=', 5)->orderBy('stok')->get(),
            'grafik' => $grafik,
        ]);
    }
}
