<?php

namespace App\Http\Resources\Admin\User;

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
            'count_bets' => $this->count_bets,
            'created_at' => $this->created_at->format('d.m.y'),
        ];
    }
}
