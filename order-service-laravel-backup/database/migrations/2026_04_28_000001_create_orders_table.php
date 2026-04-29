<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('kode_order')->unique();
            $table->string('nama_pelanggan');
            $table->string('no_telepon');
            $table->text('alamat');
            $table->unsignedBigInteger('motor_id');
            $table->string('motor_nama');
            $table->string('motor_merk');
            $table->decimal('harga', 15, 2);
            $table->integer('jumlah')->default(1);
            $table->decimal('total', 15, 2);
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
