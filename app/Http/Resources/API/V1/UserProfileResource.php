<?php

namespace App\Http\Resources\API\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
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
            'full_name' => $this->full_name,
            'job_title' => $this->job_title,
            'email' => $this->email,
            'phone' => $this->phone,
            'website' => $this->website,
            'address' => $this->address,
            'summary' => $this->summary,
            'education' => $this->education,
            'experience' => $this->experience,
            'certifications' => $this->certifications,
            'languages' => $this->languages,
            'skills' => $this->skills,
            'projects' => $this->projects,
            'awards' => $this->awards,
            'social_links' => $this->social_links,
            'interests' => $this->interests
        ];
    }
}
