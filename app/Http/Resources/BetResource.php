<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
//            'user' => UserResource::collection($this->user),
//            'posts' => LotIndexResource::collection($this->posts),
            'down_bet' => $this->down_bet,
            'up_bet' => $this->up_bet
        ];
    }
}
