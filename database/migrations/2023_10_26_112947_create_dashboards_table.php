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
        Schema::create('dashboards', function (Blueprint $table) {
            $table->id();

            $table->uuid('request_id');

            $table->foreignId('workflow_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('name');
            $table->string('state');
            $table->integer('created');
            $table->string('status');
            $table->string('type');

            $table->foreignUuid('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->boolean('multiple_heads')->default(false);

            $table->foreignUuid('manager_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignUuid('fo_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignUuid('head_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignUuid('vice_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->json('unit_heads')->nullable();
            $table->json('unit_head_approved')->nullable();

            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dashboards');
    }
};
