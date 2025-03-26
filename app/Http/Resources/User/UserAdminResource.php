<?php

namespace App\Http\Resources\User;

use App\Http\Resources\BetResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserAdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
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
            'password' => $this->password,
            'timestamps' => $this->timestamps,

            'bet' => BetResource::collection($this->bets),
        ];
    }
}
