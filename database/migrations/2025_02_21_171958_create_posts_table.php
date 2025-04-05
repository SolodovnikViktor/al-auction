<?php

use App\Models\Image;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('image_position')->nullable();
            $table->string('image_preview')->nullable();
            $table->string('title');
            $table->string('vin');
            $table->string('brand');
            $table->string('model');
            $table->smallInteger('year_release');
            $table->foreignId('color_id');
            $table->unsignedMediumInteger('mileage'); // пробег
            $table->string('fuel'); // топливо
            $table->foreignId('drive_id'); // привод
            $table->foreignId('body_type_id'); // тип кузова
            $table->foreignId('transmission_id'); // тип коробки
            $table->float('engine_capacity'); // объём
//            от 0 до 65535
            $table->unsignedSmallInteger('horsepower'); // лошади
            $table->unsignedMediumInteger('price');//0 до 16 777 215
            $table->unsignedMediumInteger('up_price')->nullable();
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
