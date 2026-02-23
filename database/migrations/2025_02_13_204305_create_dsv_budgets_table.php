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
        Schema::create('dsv_budgets', function (Blueprint $table) {
            $table->id();

            $table->json('research_area');
            $table->unsignedBigInteger('preapproved_total');
            $table->unsignedBigInteger('sent_total')->default(0);
            $table->unsignedBigInteger('granted_total')->default(0);

            $table->decimal('budget_dsv_total_sek', 15, 2);
            $table->decimal('budget_dsv_total_eur', 15, 2);
            $table->decimal('budget_dsv_total_usd', 15, 2);

            $table->decimal('budget_project_total_sek', 15, 2);
            $table->decimal('budget_project_total_eur', 15, 2);
            $table->decimal('budget_project_total_usd', 15, 2);

            // phd years (counts)
            $table->unsignedSmallInteger('phd_total');

            $table->decimal('cost_total_sek', 15, 2);
            $table->decimal('cost_total_eur', 15, 2);
            $table->decimal('cost_total_usd', 15, 2);

            $table->decimal('granted_total_sek', 15, 2);
            $table->decimal('granted_total_eur', 15, 2);
            $table->decimal('granted_total_usd', 15, 2);

            // phd years promised
            $table->unsignedSmallInteger('phd_promised');

            $table->decimal('cofinanced_total_sek', 15, 2);
            $table->decimal('cofinanced_total_eur', 15, 2);
            $table->decimal('cofinanced_total_usd', 15, 2);

            $table->json('funding_org')->nullable();

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dsv_budgets');
    }
};
