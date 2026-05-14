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
        Schema::create('centers_has_children', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('center_id');
            $table->foreign('center_id')
                ->references('id')
                ->on('centers');
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
        Schema::dropIfExists('centers_has_children');
    }
};
