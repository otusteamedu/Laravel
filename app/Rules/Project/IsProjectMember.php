<?php

namespace App\Rules\Project;

use Closure;
use App\Domain\Repositories\Project\ValueObject\ProjectRoleEnum;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Infrastructure\Repositories\Eloquent\ProjectRepository;


class IsProjectMember implements DataAwareRule, ValidationRule
{
    /**
     * Все данные находятся на проверке.
     *
     * @var array<string, mixed>
     */
    protected $data = [];

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $projectRepository = new ProjectRepository();

        $isMember = $projectRepository->userHasRole(
            $this->data['projectId'],
            $value,
            [ProjectRoleEnum::ADMIN, ProjectRoleEnum::MEMBER]
        );

        if ($isMember === false) {
            $fail(':attribute не является участником проекта');
        }
    }

    /**
     * Установите данные для проверки.
     *
     * @param  array<string, mixed>  $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }
}
