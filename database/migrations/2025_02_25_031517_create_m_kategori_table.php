<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
      Schema::create('m_kategori', function (Blueprint $table) {
    $table->id('kategori_id');  // Ini akan otomatis auto_increment dan jadi primary key
    $table->string('kategori_kode', 10)->unique();  // Tidak perlu auto_increment di sini
    $table->string('kategori_nama', 100);
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_kategori');
    }
};
