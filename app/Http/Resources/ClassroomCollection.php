<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ClassroomCollection extends ResourceCollection
{

    public $collects = ClassroomResource::class;
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {

        $data = $this->collection->map(function($model){
            return [
                'id' => $model->id,
                'name' => $model->name,
                'code' => $model->code,
                'cover_image' => $model->cover_image_url,
                'meats' => [
                    'section'=>$model->section,
                    'room'=>$model->room,
                    'subject'=>$model->subject,
                    'students_count' =>$model->students_count,
                    'theme' => $model->theme,
                ],
                'user' => [
                    'name' => $model->user?->name,
                ],
            ];
        });

        return [
            'data' => $data,
            'links' => [
                'self' => 'link-value',
            ],
        ];
    }
}
