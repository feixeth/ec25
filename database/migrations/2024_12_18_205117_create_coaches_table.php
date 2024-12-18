<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up():void
    {
        Schema::create('coaches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users', 'id') // ref la colonne `id` dans la table `users`
                ->onDelete('cascade');
            $table->foreignId('game_id')
                  ->constrained('games', 'id')
                  ->onDelete('cascade');
            $table->enum('status', ['Available', 'Not available', 'N/A'])
                  ->default('N/A');
            $table->string('achievement')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down():void
    {
        Schema::dropIfExists('coaches');
    }
};
