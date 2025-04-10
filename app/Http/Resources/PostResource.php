<?php

namespace App\Http\Resources;

use App\Http\Resources\User\UserResource;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $positionArr = explode(',', $this->image_position);
        $positionStr = $this->image_position;
        if ($this->image_position !== null && $this->image_position !== '') {
//            dd($this->image_position);
            $image = Image::whereIn('id', $positionArr)->orderByRaw("FIELD (id, $positionStr) ASC")->get();
        } else {
            $image = Image::whereIn('id', $positionArr)->get();
        }


        return [
            'id' => $this->id,
            'image_position' => $this->image_position,
            'image_preview' => $this->image_preview,
            'title' => $this->title,
            'vin' => $this->vin,
            'brand' => $this->brand,
            'model' => $this->model,
            'year_release' => $this->year_release,
            'color_id' => $this->color_id,
            'mileage' => $this->mileage,
            'fuel' => $this->fuel,
            'drive_id' => $this->driver_id,
            'body_type_id' => $this->body_type_id,
            'transmission_id' => $this->transmission_id,
            'engine_capacity' => $this->engine_capacity,
            'horsepower' => $this->horsepower,
            'price' => $this->price,
            'up_price' => $this->up_price,
            'description' => $this->description,
            'user_id' => $this->user_id,
            'is_published' => $this->is_published,

//            'images' => ImageResource::collection($this->images),
            'images' => ImageResource::collection($image),
//            'images' => Image::whereIn('id', $positionArr)->get(),

            'bets' => BetResource::collection($this->bets),
            'user' => UserResource::make($this->user),
//            'userWhenLoaded' => UserResource::make($this->whenLoaded('user')),
        ];
    }
}
