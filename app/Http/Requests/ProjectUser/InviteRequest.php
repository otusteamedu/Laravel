<?php

namespace App\Http\Requests\ProjectUser;

use Closure;
use Illuminate\Auth\AuthManager;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Services\Repositories\UserRepositoryInterface;
use App\Services\Repositories\ProjectRepositoryInterface;

class InviteRequest extends FormRequest
{
    private bool $user = false;
    private bool $member = false;

    public function __construct(
        private ProjectRepositoryInterface $projectRepository,
        private UserRepositoryInterface $userRepository
    ) {}
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(
        AuthManager $auth,
        ProjectRepositoryInterface $projectRepository,
        UserRepositoryInterface $userRepository
    ): bool {
        $this->projectRepository = $projectRepository;
        $this->userRepository = $userRepository;

        return $auth->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $member = $this->member;
        $user   = $this->user;

        return [
            'project_id' => ['required', 'exists:projects,id'],
            'email'      => [
                'required',
                'email:rfc',
                function (string $attribute, mixed $value, Closure $fail) use ($user, $member) {
                    if ($user === false) {
                        $fail("Пользователь должен подтвердить саой email, прежде чем его можно будет приглашать для участия в проектах");
                    } elseif ($member === true) {
                        $fail("Пользователь c email $value уже является участником проекта");
                    }
                }
            ],
            'user_id' => ['required', 'exists:users,id'],
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'project_id.required' => 'Проект не существует',
            'project_id.exists'   => 'Проект не существует',
            'email.required'      => 'Пожалуйста, укажите email.',
            'email.email'         => 'Поле :input должно содержать корректный email адрес',
        ];
    }

    protected function prepareForValidation(): void
    {
        $projectId = request()->route('projectId');
        $email     = request()->input('email');

        $this->merge(['project_id' => $projectId]);

        if ($user = $this->userRepository->findByEmail($email, true)) {
            $this->user = true;

            $this->merge(['user_id' => $user->userId]);
        }

        if ($user && $this->projectRepository->findUser($projectId, $user->userId)) {
            $this->member = true;
        }
    }

    protected function failedValidation(Validator $validator)
    {
        debugbar()->info($validator->errors());
        throw new HttpResponseException(redirect()
            ->back()
            ->withInput()
            ->withErrors($validator->errors())
            ->with('error', 'Ошибка валидации формы'));
    }
}
