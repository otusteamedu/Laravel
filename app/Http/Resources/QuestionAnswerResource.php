<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionAnswerResource extends JsonResource
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
            'question_id' => $this->question_id,
            'answer_id' => $this->id,
            'order_number' => $this->order_number,
            'name' => $this->name,
            'comment' => $this->comment,
            'self_text' => '',
            'self' => $this->self,
            'selected' => $this->selected,
            'icon' => $this->icon,
            'excluded_order_numbers' => $this->excluded_order_numbers,
            'end_text' => $this->end_text
        ];
    }
}
