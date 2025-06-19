<?php

namespace App\Http\Resources\Admin\Post;

use App\Http\Resources\Main\MainBetResource;
use App\Http\Resources\Main\PhotoResource;
use App\Models\Photo;
use App\Models\PhotoPosition;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminPostIndexResource extends JsonResource
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
            'brand' => $this->brand->title,
            'model' => $this->model->title,
            'vin' => $this->vin,
            'year_release' => $this->year_release,
            'mileage' => $this->mileage,
            'drive' => $this->drive->title,
            'transmission' => $this->transmission->title,
            'engine_capacity' => $this->engine_capacity,
            'horsepower' => $this->horsepower,
            'price' => $this->price,
            'description' => $this->description,
            'is_published' => $this->is_published,
            'photos' => PhotoResource::collection($photos),
            'bets' => MainBetResource::collection($this->bets),
//            'userWhenLoaded' => MainUserResource::make($this->whenLoaded('user')),
        ];
    }
}
