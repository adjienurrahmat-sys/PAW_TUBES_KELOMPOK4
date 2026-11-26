<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reservasis', function (Blueprint $t) {
            $t->id();
            $t->string('kode')->unique();
            $t->string('nama');
            $t->unsignedTinyInteger('jumlah_tamu');
            $t->date('tanggal');
            $t->string('jam', 5);
            $t->foreignId('meja_id')->constrained('mejas');
            $t->string('metode_bayar')->nullable();
            $t->text('catatan')->nullable();
            $t->string('sumber')->default('online'); // online (pelanggan) | staf (walk-in / WhatsApp)
            $t->unsignedBigInteger('total')->default(0);
            $t->string('status')->default('menunggu_pembayaran');
            $t->timestamps();
        });
        Schema::create('reservasi_items', function (Blueprint $t) {
            $t->id();
            $t->foreignId('reservasi_id')->constrained()->cascadeOnDelete();
            $t->foreignId('menu_id')->constrained('menus');
            $t->unsignedInteger('jumlah');
            $t->unsignedInteger('harga');
            $t->unsignedBigInteger('subtotal');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('reservasi_items');
        Schema::dropIfExists('reservasis');
    }
};
