<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('temporary_files', function (Blueprint $table) {
            $table->id();
            $table->string('folder');
            $table->string('path');
            $table->string('name');
            $table->string('user_id');
            $table->string('size');
            $table->timestamps();
        });

        Schema::create('temporary_reorder', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('position');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('temporary_files');
        Schema::dropIfExists('temporary_reorder');
    }
};
