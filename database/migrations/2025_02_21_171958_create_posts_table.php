<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id');
            $table->foreignId('model_id');
            $table->string('vin');
            $table->smallInteger('year_release');
            $table->foreignId('color_id');
            $table->unsignedMediumInteger('mileage'); // пробег
            $table->foreignId('fuel_id'); // топливо
            $table->foreignId('wheel_id'); // руль
            $table->foreignId('drive_id'); // привод
            $table->foreignId('body_type_id'); // тип кузова
            $table->foreignId('transmission_id'); // тип коробки
            $table->float('engine_capacity'); // объём
            $table->unsignedSmallInteger('horsepower'); // лошади от 0 до 65535
            $table->unsignedMediumInteger('price');//0 до 16 777 215
            $table->text('description'); // описание
            $table->foreignId('user_id')->index();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
