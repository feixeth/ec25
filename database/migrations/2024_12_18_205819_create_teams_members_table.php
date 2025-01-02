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
        Schema::create('teams_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained('users','id')
                  ->onDelete('cascade');
            $table->foreignId('team_id')
                  ->constrained('teams','id')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams_members');
    }
};
