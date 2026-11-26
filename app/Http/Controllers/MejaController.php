<?php
namespace App\Http\Controllers;

use App\Models\Meja;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MejaController extends Controller
{
    public function index() { return view('admin.meja', ['data' => Meja::orderBy('nomor')->get()]); }

    public function store(Request $r)
    {
        Meja::create($r->validate([
            'nomor' => 'required|string|max:10|unique:mejas,nomor',
            'kapasitas' => 'required|integer|min:1|max:30', 'lantai' => 'required|integer|min:1|max:5',
            'area' => 'nullable|in:Ruang Tamu,Meja Makan,Teras,Galeri', 'pos_x' => 'nullable|numeric|between:0,100', 'pos_y' => 'nullable|numeric|between:0,100',
        ]));
        return back()->with('ok', 'Meja ditambahkan.');
    }

    public function update(Request $r, Meja $meja)
    {
        $meja->update($r->validate([
            'nomor' => ['required', 'string', 'max:10', Rule::unique('mejas', 'nomor')->ignore($meja->id)],
            'kapasitas' => 'required|integer|min:1|max:30', 'lantai' => 'required|integer|min:1|max:5',
            'area' => 'nullable|in:Ruang Tamu,Meja Makan,Teras,Galeri', 'pos_x' => 'nullable|numeric|between:0,100', 'pos_y' => 'nullable|numeric|between:0,100',
        ]));
        return back()->with('ok', 'Meja diperbarui.');
    }

    public function destroy(Meja $meja)
    {
        if ($meja->reservasis()->exists()) {
            return back()->withErrors(['meja' => 'Meja sudah punya reservasi, tidak bisa dihapus.']);
        }
        $meja->delete();
        return back()->with('ok', 'Meja dihapus.');
    }
}
