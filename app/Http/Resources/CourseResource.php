<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        $searchName = $request->input('search_name');
        $searchPackageId = $request->input('search_package_id');

        $data = [
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            'price'       => $this->price,
            'package_id'  => $this->package_id,
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
            'deleted_at'  => $this->deleted_at,
        ];

        // Use filter request, by name or by package_id
        if ($searchName && stripos($this->name, $searchName) !== false || 
            stripos($this->package_id, $searchPackageId) !== false) {
                
            return $data;
        }

        return $data;
    }
}
