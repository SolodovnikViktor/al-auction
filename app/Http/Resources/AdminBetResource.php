<?php

namespace App\Http\Resources;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminBetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => new UserResource($this->user),
//            'posts' => LotIndexResource::collection($this->posts),
            'down_bet' => $this->down_bet,
            'up_bet' => $this->up_bet
        ];
    }
}
