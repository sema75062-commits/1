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
        Schema::create('centers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title', 50);
            $table->string('address', 150);
            $table->string('phone', 20);
            $table->string('email', 100)->unique()->nullable();
            $table->uuid('admin_id');
            $table->foreign('admin_id')
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
        Schema::dropIfExists('centers');
    }
};
