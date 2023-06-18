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
        Schema::create('apoteks', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->boolean('rujukan');
            $table->string('rumah_sakit')->nullable();
            $table->json('obat');
            $table->json('harga_satuan');
            $table->integer('total_harga');
            $table->string('apoteker');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apoteks');
    }
};
