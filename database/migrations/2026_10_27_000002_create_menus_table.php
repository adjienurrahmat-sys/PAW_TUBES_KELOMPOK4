<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $t) {
            $t->id();
            $t->string('nama');
            $t->string('kategori');
            $t->text('deskripsi')->nullable();
            $t->unsignedInteger('harga');
            $t->unsignedInteger('stok')->default(0); // 0 = sold out
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('menus'); }
};
