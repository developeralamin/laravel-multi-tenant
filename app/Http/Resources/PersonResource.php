<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonResource extends JsonResource
{
   /**
     * Transform the resource into an array.
     *
     * @param Request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id'      => $this->id,
            'name'    => $this->name,
            'address' => $this->address,
            'photo'   => $this->getPhoto($this->photo),
            'thumb'   => $this->getPhoto($this->thumb),
        ];
    }


    /**
     * @param $photo
     *
     * @return string|null
     */
    public function getPhoto($photo): ?string
    {
        if ($photo) {
            return Storage::url($photo);
        }

        return null;
    }
}
