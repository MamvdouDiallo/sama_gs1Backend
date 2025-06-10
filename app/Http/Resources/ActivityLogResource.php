<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ActivityLogResource extends JsonResource
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
            'description' => $this->description,
            'method' => $this->methode,
            'ip' => $this->ip,
            'user_id' => $this->user_id,
            'user_email' => $this->user_email,
            'agent' => $this->agent,
            'user_role' => $this->user_role,
            'url' => $this->url,
            'before' => $this->before,
            'after' => $this->after,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
