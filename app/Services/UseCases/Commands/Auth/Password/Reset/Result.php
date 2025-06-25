<?php

namespace App\Services\UseCases\Commands\Auth\Password\Reset;

final readonly class Result
{
    /**
     * @param string $routeName Имя роута для редиректа 
     * @param string|null $token Токен для формы сброса пароля, если не отправляли письмо
     */
    public function __construct(
        public string $routeName,
        public ?string $token = null,
    ) {}
}
