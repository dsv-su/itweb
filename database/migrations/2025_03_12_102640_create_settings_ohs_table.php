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
        Schema::create('settings_ohs', function (Blueprint $table) {
            $table->id();
            $table->boolean('form_enable')->default(true);
            $table->integer('oh_max')->default(60);
            $table->integer('oh_vinnova')->default(100);
            $table->integer('oh_eu')->default(25);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings_ohs');
    }
};
