<?php

namespace App\Http\Resources\Main;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MainBetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
//            'user' => MainUserResource::collection($this->user),
//            'posts' => AdminPostIndexResource::collection($this->posts),
            'down_bet' => $this->down_bet,
            'up_bet' => $this->up_bet
        ];
    }
}
