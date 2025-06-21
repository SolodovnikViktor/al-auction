<?php

namespace App\Http\Resources;

use App\Models\Photo;
use App\Models\PhotoPosition;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LotIndexResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        if ($positionStr = PhotoPosition::where('lot_id', $this->id)->whereNot('position', '')->first()) {
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
            'is_published' => $this->is_published,
            'photos' => PhotoResource::collection($photos),
            'bets' => BetResource::collection($this->bets),
//            'userWhenLoaded' => UserResource::make($this->whenLoaded('user')),
        ];
    }
}
