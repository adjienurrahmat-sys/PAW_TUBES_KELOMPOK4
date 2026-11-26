<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index() { return view('admin.pengguna', ['data' => User::orderBy('name')->get()]); }

    public function store(Request $r)
    {
        // Akun yang dibuat Pemilik selalu berperan Staf
        User::create($r->validate([
            'name' => 'required|string|max:100', 'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]) + ['role' => 'staf']);
        return back()->with('ok', 'Akun Staf ditambahkan.');
    }

    public function update(Request $r, User $user)
    {
        $d = $r->validate([
            'name' => 'required|string|max:100',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable|string|min:6',
        ]);
        if (empty($d['password'])) unset($d['password']);
        $user->update($d);
        return back()->with('ok', 'Pengguna diperbarui.');
    }

    public function destroy(Request $r, User $user)
    {
        if ($user->role === 'pemilik') {
            return back()->withErrors(['user' => 'Akun Pemilik tidak bisa dihapus.']);
        }
        if ($user->id === $r->user()->id) {
            return back()->withErrors(['user' => 'Tidak bisa menghapus akun sendiri.']);
        }
        $user->delete();
        return back()->with('ok', 'Pengguna dihapus.');
    }
}
