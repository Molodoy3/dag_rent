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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string("login");
            $table->string("password");
            //$table->foreignId("platform_id")->references("id")->on("platforms")->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamp("busy")->nullable();
            $table->string("email")->nullable();
            $table->string("passwordEmail")->nullable();
            $table->integer("price")->nullable();
            $table->foreignId("user_id")->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string("comment")->nullable();
            $table->integer("status")->nullable();
            $table->softDeletes('deleted_at', precision: 0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        //Schema::dropIfExists('accounts');
        Schema::dropIfExists('accounts');
    }
};
