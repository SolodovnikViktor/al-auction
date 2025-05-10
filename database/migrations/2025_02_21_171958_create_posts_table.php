<?php

use App\Models\Photo;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('image_preview')->nullable();
            $table->string('title')->nullable();
            $table->string('vin');
            $table->foreignId('brand_id');
            $table->foreignId('model_id');
            $table->smallInteger('year_release');
            $table->foreignId('color_id');
            $table->unsignedMediumInteger('mileage'); // пробег
            $table->string('fuel'); // топливо
            $table->foreignId('drive_id'); // привод
            $table->foreignId('body_type_id'); // тип кузова
            $table->foreignId('transmission_id'); // тип коробки
            $table->float('engine_capacity'); // объём
            $table->unsignedSmallInteger('horsepower'); // лошади от 0 до 65535
            $table->unsignedMediumInteger('price');//0 до 16 777 215
            $table->unsignedMediumInteger('up_price')->nullable();
            $table->text('description'); // описание
            $table->foreignId('user_id')->index();
            $table->boolean('is_published')->default(true);
            $table->unsignedSmallInteger('count_bets')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
