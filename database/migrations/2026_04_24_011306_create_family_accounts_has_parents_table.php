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
        Schema::create('family_accounts_has_parents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('family_account_id');
            $table->foreign('family_account_id')
                ->references('id')
                ->on('family_accounts');
            $table->uuid('parent_id');
            $table->foreign('parent_id')
                ->references('id')
                ->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_accounts_has_parents');
    }
};
