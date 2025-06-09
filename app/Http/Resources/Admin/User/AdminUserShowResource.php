<?php

namespace App\Http\Resources\Admin\User;

use App\Http\Resources\Admin\Post\AdminPostIndexResource;
use App\Http\Resources\Main\MainBetResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminUserShowResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'role' => $this->Role,
            'name' => $this->name,
            'surname' => $this->surname,
            'patronymic' => $this->patronymic,
            'phone' => $this->phone,
            'phone_verified_at' => $this->phone_verified_at,
            'email' => $this->email,
            'count_bets' => $this->count_bets,
            'address' => $this->address,
            'created_at' => $this->created_at->format('d.m.y'),
            'bets' => MainBetResource::collection($this->CountBets),
            'posts' => AdminPostIndexResource::collection($this->Posts),
        ];
    }
}
