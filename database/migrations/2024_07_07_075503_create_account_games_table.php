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
        Schema::create('account_games', function (Blueprint $table) {
            $table->id();
            $table->foreignId("account_id")->references("id")->on("accounts")->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId("game_id")->references("id")->on("games")->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_games');
    }
};
