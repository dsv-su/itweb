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
        Schema::table('settings_vices', function (Blueprint $table) {
            $table->json('grant_notification_recipients')->nullable()->after('ekonomi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings_vices', function (Blueprint $table) {
            $table->dropColumn('grant_notification_recipients');
        });
    }
};
