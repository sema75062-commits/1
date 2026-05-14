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
        Schema::create('card_sets_has_cards', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('card_set_id');
            $table->foreign('card_set_id')
                ->references('id')
                ->on('card_sets');
            $table->uuid('card_id');
            $table->foreign('card_id')
                ->references('id')
                ->on('cards');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_sets_has_cards');
    }
};
