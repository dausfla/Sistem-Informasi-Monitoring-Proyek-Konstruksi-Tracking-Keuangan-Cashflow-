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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('category', ['rbk_land', 'non_rbk_land'])->default('rbk_land');
            $table->enum('status', ['planning', 'on_progress', 'completed', 'on_hold'])->default('planning');
            $table->decimal('planned_progress', 5, 2)->default(0.00);
            $table->decimal('actual_progress', 5, 2)->default(0.00);
            $table->decimal('total_contract_value', 15, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
