<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategoris', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->unsignedInteger('harga');
            $table->unsignedInteger('stok')->default(0);
            $table->foreignId('kategori_id')->constrained('kategoris')->cascadeOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('penjualans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kasir_id')->constrained('users')->cascadeOnDelete();
            $table->date('tanggal');
            $table->unsignedInteger('total');
            $table->unsignedInteger('bayar');
            $table->unsignedInteger('kembalian');
            $table->timestamps();
        });

        Schema::create('penjualan_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penjualan_id')->constrained('penjualans')->cascadeOnDelete();
            $table->foreignId('barang_id')->constrained('barangs')->cascadeOnDelete();
            $table->unsignedInteger('qty');
            $table->unsignedInteger('harga');
            $table->unsignedInteger('subtotal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjualan_details');
        Schema::dropIfExists('penjualans');
        Schema::dropIfExists('barangs');
        Schema::dropIfExists('kategoris');
    }
};