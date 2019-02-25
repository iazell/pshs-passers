<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Passer extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if($this->total == null){
            return [
                'id' => $this->id,
                'name' => $this->name,
                'camp_eligibility' => $this->camp_eligibility,
                'school' => $this->school,
                'division' => $this->division
            ];
        }else{
            return [
                'school' => $this->school,
                'count' => $this->total
            ];
        }
    }
}
