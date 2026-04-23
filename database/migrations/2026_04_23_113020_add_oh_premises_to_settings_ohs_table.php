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
        Schema::table('settings_ohs', function (Blueprint $table) {
            $table->integer('oh_premises')->default(8)->after('oh_eu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings_ohs', function (Blueprint $table) {
            $table->dropColumn('oh_premises');
        });
    }
};
