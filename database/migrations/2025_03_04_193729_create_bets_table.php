<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('post_id');
            $table->unsignedMediumInteger('down_price');
            $table->unsignedMediumInteger('up_price');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bets');
    }
};
