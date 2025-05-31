<?php
namespace App\Services\Tasks;

use App\DTO\Tasks\TaskDTO;
use App\Models\Task;
use App\Repositories\Tasks\TaskRepositoryInterface;
use App\Repositories\Categories\CategoryRepositoryInterface;
use App\Repositories\Users\UserRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class TasksService
{
    protected TaskRepositoryInterface $taskRepository;
    protected CategoryRepositoryInterface $categoryRepository;
    protected UserRepositoryInterface $userRepository;

    /**
     * TasksService constructor.
     *
     * @param TaskRepositoryInterface $taskRepository
     * @param CategoryRepositoryInterface $categoryRepository
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        TaskRepositoryInterface $taskRepository,
        CategoryRepositoryInterface $categoryRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->taskRepository = $taskRepository;
        $this->categoryRepository = $categoryRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Получить список задач с пагинацией
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(int $perPage = 10): LengthAwarePaginator
    {
        return $this->taskRepository->getAllWithRelations($perPage);
    }

    /**
     * Получить данные для формы создания задачи
     *
     * @return array
     */
    public function getFormData(): array
    {
        return [
            'users' => $this->userRepository->all(),
            'categories' => $this->categoryRepository->all(),
            'priorities' => \App\Models\Priority::all(),
        ];
    }

    /**
     * Создать новую задачу
     *
     * @param TaskDTO $taskDTO
     * @return Task
     */
    public function create(TaskDTO $taskDTO): Task
    {
        return $this->taskRepository->create($taskDTO->toArray());
    }

    /**
     * Создать задачу из данных запроса
     *
     * @param array $requestData
     * @return Task
     */
    public function createFromRequest(array $requestData): Task
    {
        $taskDTO = TaskDTO::fromRequest($requestData);
        return $this->create($taskDTO);
    }

    /**
     * Обновить существующую задачу
     *
     * @param Task $task
     * @param TaskDTO $taskDTO
     * @return bool
     */
    public function update(Task $task, TaskDTO $taskDTO): bool
    {
        return $this->taskRepository->updateByModel($task, $taskDTO->toArray());
    }

    /**
     * Обновить задачу из данных запроса
     *
     * @param Task $task
     * @param array $requestData
     * @return bool
     */
    public function updateFromRequest(Task $task, array $requestData): bool
    {
        $taskDTO = TaskDTO::fromRequest($requestData);
        return $this->update($task, $taskDTO);
    }

    /**
     * Удалить задачу
     *
     * @param Task $task
     * @return bool
     */
    public function delete(Task $task): bool
    {
        return $this->taskRepository->deleteByModel($task);
    }

    /**
     * Получить DTO из модели задачи
     * 
     * @param Task $task
     * @return TaskDTO
     */
    public function getDTO(Task $task): TaskDTO
    {
        return TaskDTO::fromModel($task);
    }

    /**
     * Получить правила валидации для задачи
     *
     * @return array
     */
    public function getValidationRules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'executor_id' => ['required', 'exists:users,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'priority_id' => ['required', 'exists:priorities,id'],
            'due_date' => ['nullable', 'date'],
            'status' => ['required', 'in:новая,в работе,выполнена,отменена'],
        ];
    }
}
