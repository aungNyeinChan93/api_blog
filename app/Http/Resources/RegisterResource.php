<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegisterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'registerToken' => $this->createToken('register', ['auth:register'])->plainTextToken,
            'time' => $this->created_at->diffForHumans(),
            'created_at' => Carbon::now()->format('d-m-Y h-m-s'),
        ];
    }
}
