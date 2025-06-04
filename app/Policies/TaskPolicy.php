<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Все авторизованные пользователи могут видеть список задач
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Task $task): bool
    {
        // Админ может видеть любые задачи, создатель и исполнитель - свои задачи
        return $user->isAdmin() 
            || $user->id === $task->creator_id 
            || $user->id === $task->executor_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Все авторизованные пользователи могут создавать задачи
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Task $task): bool
    {
        // Админ может редактировать любые задачи, создатель и исполнитель - свои задачи
        return $user->isAdmin() 
            || $user->id === $task->creator_id 
            || $user->id === $task->executor_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Task $task): bool
    {
        return $user->isAdmin(); 
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Task $task): bool
    {
        // Только админ может восстанавливать задачи
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Task $task): bool
    {
        // Только админ может окончательно удалять задачи
        return $user->isAdmin();
    }
}
