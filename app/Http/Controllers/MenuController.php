<?php
namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    private array $rules = [
        'nama' => 'required|string|max:100', 'kategori' => 'required|string|max:50',
        'harga' => 'required|integer|min:0', 'stok' => 'required|integer|min:0',
    ];

    public function index(Request $r)
    {
        $q = Menu::query();
        if ($r->filled('q')) $q->where('nama', 'like', "%{$r->q}%");
        if ($r->status === 'habis') $q->where('stok', 0);
        if ($r->status === 'tersedia') $q->where('stok', '>', 0);
        if ($r->status === 'menipis') $q->whereBetween('stok', [1, 5]);
        $sort = in_array($r->sort, ['nama', 'harga', 'stok'], true) ? $r->sort : 'nama';
        return view('admin.menu', ['data' => $q->orderBy($sort, $r->dir === 'desc' ? 'desc' : 'asc')->get()]);
    }

    public function store(Request $r)
    {
        Menu::create($r->validate($this->rules));
        return back()->with('ok', 'Menu ditambahkan.');
    }

    public function update(Request $r, Menu $menu)
    {
        $menu->update($r->validate($this->rules));
        return back()->with('ok', 'Menu diperbarui.');
    }

    public function stok(Request $r, Menu $menu) // dipakai Staf: hanya ubah stok / ketersediaan
    {
        $menu->update($r->validate(['stok' => 'required|integer|min:0']));
        return back()->with('ok', 'Stok diperbarui.');
    }

    public function soldOut(Menu $menu) // Staf dan Pemilik: tandai menu habis (stok 0)
    {
        $menu->update(['stok' => 0]);
        return back()->with('ok', "{$menu->nama} ditandai sold out.");
    }

    public function destroy(Menu $menu)
    {
        if ($menu->id && \App\Models\ReservasiItem::where('menu_id', $menu->id)->exists()) {
            return back()->withErrors(['menu' => 'Menu sudah dipakai di reservasi, tidak bisa dihapus.']);
        }
        $menu->delete();
        return back()->with('ok', 'Menu dihapus.');
    }
}
