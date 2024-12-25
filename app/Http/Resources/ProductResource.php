<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'description' => $this->description,
            'stock' => $this->stock,
            'price' => $this->price,
            'image' => $this->image ? Storage::url($this->image) : 'https://placehold.co/600?text=No+Image',
            'barcode' => $this->barcode
        ];
    }
}
