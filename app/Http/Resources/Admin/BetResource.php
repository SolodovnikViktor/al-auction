<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BetResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => UserResource::collection($this->user),
            'posts' => PostIndexResource::collection($this->posts),
            'down_bet' => $this->down_bet,
            'up_bet' => $this->up_bet
        ];
    }
}
