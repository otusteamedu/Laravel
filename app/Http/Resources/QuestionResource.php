<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\QuestionAnswerResource;

class QuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'poll_id' => $this->poll_id,
            'order_number' => $this->order_number,
            'name' => $this->name,
            'required' => $this->required,
            'description' => $this->description,
            'type_id' => $this->type_id,
            'comment' => $this->comment,
            'min_answer_count' => $this->min_answer_count,
            'max_answer_count' => $this->max_answer_count,
            'icon' => $this->icon,
            'answers' => QuestionAnswerResource::collection($this->answers)
        ];
    }
}
