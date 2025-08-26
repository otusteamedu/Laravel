<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\QuestionResource;
use App\Models\Question;

class PollResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    // public function __construct($resource,$withQuestions)
    // {
    //     parent::__construct($resource);
    //     $this->withQuestions = $withQuestions;
    // }

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'start_text' => $this->start_text,
            'end_text' => $this->end_text,
            'icon' => $this->icon,
            'authorized' => $this->authorized,
            'chart_mode' => $this->chart_mode,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'questions' => QuestionResource::collection($this->questions)
            // 'questions' => $this->whenLoaded(Question::class)
            //'results' => $this->pollAnswers
        ];
    }
}
