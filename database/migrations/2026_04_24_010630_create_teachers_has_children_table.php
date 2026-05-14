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
        Schema::create('teachers_has_children', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('teacher_id');
            $table->foreign('teacher_id')
                ->references('id')
                ->on('users');
            $table->uuid('child_id');
            $table->foreign('child_id')
                ->references('id')
                ->on('children');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers_has_children');
    }
};
