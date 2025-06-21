<?php

namespace App\Http\Resources;

use App\Http\Resources\User\UserResource;
use App\Models\Photo;
use App\Models\PhotoPosition;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminLotShowResource extends JsonResource
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
            'color' => $this->color->title,
            'mileage' => $this->mileage,
            'fuel' => $this->fuel->title,
            'wheel' => $this->wheel->title,
            'drive' => $this->drive->title,
            'body_type' => $this->body_type->title,
            'transmission' => $this->transmission->title,
            'engine_capacity' => $this->engine_capacity,
            'horsepower' => $this->horsepower,
            'price' => $this->price,
            'description' => $this->description,
            'is_published' => $this->is_published,
            'photos' => PhotoResource::collection($photos),
            'bets' => AdminBetResource::collection($this->bets),
            'user' => UserResource::make($this->user),
        ];
    }
}
