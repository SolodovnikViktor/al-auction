<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\User\UserResource;
use App\Models\Photo;
use App\Models\PhotoPosition;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        if ($positionStr = PhotoPosition::where('post_id', $this->id)->whereNot('position', '')->first()) {
            $positionArr = explode(',', $positionStr->position);
            $photos = (Photo::whereIn('id', $positionArr)->whereNot('path_min', null)->orderByRaw(
                "FIELD (id, $positionStr->position) ASC"
            )->get() ?: []);
        } else {
            $photos = [];
        }
        return [
            'id' => $this->id,
            'image_preview' => $this->image_preview,
            'title' => $this->title,
            'vin' => $this->vin,
            'brand' => $this->brand,
            'model' => $this->model,
            'year_release' => $this->year_release,
            'color' => $this->color->title,
            'mileage' => $this->mileage,
            'fuel' => $this->fuel,
            'drive' => $this->drive->title,
            'body_type_id' => $this->body_type_id,
            'transmission' => $this->transmission->title,
            'engine_capacity' => $this->engine_capacity,
            'horsepower' => $this->horsepower,
            'price' => $this->price,
            'up_price' => $this->up_price,
            'description' => $this->description,
            'is_published' => $this->is_published,
            'count_bets' => $this->count_bets,
//            'photos' => PhotoResource::collection($this->photos),
            'photos' => PhotoResource::collection($photos),
            'bets' => BetResource::collection($this->bets),
            'user' => UserResource::make($this->user),
//            'userWhenLoaded' => UserResource::make($this->whenLoaded('user')),
        ];
    }
}
