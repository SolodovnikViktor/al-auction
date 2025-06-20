<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('photo_positions', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('lot_id')->nullable();
            $table->string('position');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('photo_positions');
    }
};
