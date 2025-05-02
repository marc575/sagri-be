<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name, // ou full_name, selon ton modèle
            'email' => $this->email,
            // Ajoute d'autres champs si tu veux : phone, role, etc.
        ];
    }
}
