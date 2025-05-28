<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Admin\BetResource;
use App\Http\Resources\Admin\PostIndexResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminUserIndexResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'role' => $this->role,
            'name' => $this->name,
            'surname' => $this->surname,
            'phone' => $this->phone,
            'email' => $this->email,
            'created_at' => $this->created_at->format('d.m.y'),

            'bet' => BetResource::collection($this->bets),
            'posts' => PostIndexResource::collection($this->posts),
        ];
    }
}
