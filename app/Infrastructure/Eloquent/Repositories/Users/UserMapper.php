<?php

declare(strict_types=1);

namespace App\Infrastructure\Eloquent\Repositories\Users;

use App\Domain\User\Entities\User as DomainUser;
use App\Models\User as EloquentUser;

class UserMapper
{
    public static function toModel(DomainUser $user): EloquentUser
    {
        $model = EloquentUser::find($user->getId()) ?? new EloquentUser();

        if ($user->getId()) {
            $model->{$model->getColumnName('id')} = $user->getId();
        }

        $model->{$model->getColumnName('name')} = $user->getName();
        $model->{$model->getColumnName('email')} = $user->getEmail();
        $model->{$model->getColumnName('password')} = $user->getPassword();
        $model->{$model->getColumnName('subscribed_news')} = $user->getSubscribedNews();

        return $model;
    }

    public static function toEntity(EloquentUser $model): DomainUser
    {
        return new DomainUser(
            $model->{$model->getColumnName('id')},
            $model->{$model->getColumnName('name')},
            $model->{$model->getColumnName('email')},

            (string)($model->{$model->getColumnName('password')} ?? ''),
            $model->{$model->getColumnName('created_at')}
                ? $model->{$model->getColumnName('created_at')}->toDateTimeImmutable()
                : null,
            $model->{$model->getColumnName('updated_at')}
                ? $model->{$model->getColumnName('updated_at')}->toDateTimeImmutable()
                : null,
            $model->{$model->getColumnName('email_verified_at')}
                ? $model->{$model->getColumnName('email_verified_at')}->toDateTimeImmutable()
                : null,
            $model->{$model->getColumnName('subscribed_news')} ?? false
        );
    }
}

