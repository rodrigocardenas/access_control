<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'accessLog' => $this->accessLogs->map(function ($value) {
                return [
                    'id' => $value->id, 
                    'building_id' => $value->building_id, 
                    'building_name' => $value->building->name, 
                    'block' => $value->block,
                    'date' => $value->date,
                    'type_name' => $value->type_name
                ];
            }),
        ];
    }
}
