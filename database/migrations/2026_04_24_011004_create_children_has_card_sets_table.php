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
        Schema::create('children_has_card_sets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('child_id');
            $table->foreign('child_id')
                ->references('id')
                ->on('children');
            $table->uuid('card_set_id');
            $table->foreign('card_set_id')
                ->references('id')
                ->on('card_sets');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('children_has_card_sets');
    }
};
