<?php

use Illuminate\Database\DBAL\TimestampType;
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
        Schema::table('m_barang', function (Blueprint $table) {
            $table->string('barang_id',20);
            $table->string('kategori_id',20);
            $table->string('barang_kode',10)->unique();
            $table->string('barang_nama',100);
            $table->string('harga_beli',11);
            $table->string('harga_jual',11);


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('m_barang', function (Blueprint $table) {
            //
        });
    }
};
