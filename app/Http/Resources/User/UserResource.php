<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Admin\BetResource;
use App\Http\Resources\Admin\PostIndexResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'role' => $this->role,
            'name' => $this->name,
            'surname' => $this->surname,
            'patronymic' => $this->patronymic,
            'phone' => $this->phone,
            'phone_verified_at' => $this->phone_verified_at,
            'email' => $this->email,
            'address' => $this->address,

            'bet' => BetResource::collection($this->bets),
            'posts' => PostIndexResource::collection($this->posts),
        ];
    }
}
