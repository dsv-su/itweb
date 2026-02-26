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
        Schema::create('project_proposals', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('name');
            $table->integer('created');

            $table->string('status_stage1');
            $table->string('status_stage2');
            $table->string('status_stage3');

            $table->json('pp')->nullable();
            $table->json('files')->nullable();
            $table->boolean('reminder')->default(true);
            $table->string('last_reminder_type')->nullable();
            $table->timestamp('last_reminder_sent_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_proposals');
    }
};
