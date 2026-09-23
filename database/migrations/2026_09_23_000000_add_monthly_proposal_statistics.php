<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings_vices', function (Blueprint $table) {
            $table->json('monthly_stats_recipients')->nullable();
        });

        Schema::create('monthly_proposal_stat_deliveries', function (Blueprint $table) {
            $table->id();
            $table->date('month');
            $table->string('email');
            $table->timestamp('sent_at')->nullable();
            $table->unique(['month', 'email']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monthly_proposal_stat_deliveries');
        Schema::table('settings_vices', function (Blueprint $table) {
            $table->dropColumn('monthly_stats_recipients');
        });
    }
};
