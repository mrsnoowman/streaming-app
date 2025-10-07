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
        Schema::create('vms', function (Blueprint $table) {
            $table->id();
            $table->string('lokasi');
            $table->boolean('status')->default(1);
            $table->timestamp('lastupdate');
            $table->text('http_link');
            $table->string('ip');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vms');
    }
};
