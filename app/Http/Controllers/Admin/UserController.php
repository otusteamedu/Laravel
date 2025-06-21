<?php

namespace App\Http\Controllers\Admin;

use App\Dto\Admin\User\StoreDto;
use App\Dto\Admin\User\UpdateDto;
use App\Exceptions\UserNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\StoreRequest;
use App\Http\Requests\Admin\User\UpdateRequest;
use App\Models\User;
use App\Services\UsersService;
use App\Services\RolesService;
use Hash;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function __construct(
        private UsersService $service,
        private RolesService $rolesService
    )
    {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $sort = $request->get('sort', 'id');
        $allowedSorts = config('custom.usersSorts');
        $sort = in_array($sort, $allowedSorts) ? $sort : 'id';

        $direction = $request->get('direction', 'asc');
        $allowedDirections= config('custom.usersDirections');
        $direction = in_array($direction, $allowedDirections) ? $direction: 'asc';

        $page = (int) ($request->get('page')?? 1);

        $users = $this->service->getList($sort, $direction, $page);
        return view('admin.users.index', compact('users', 'direction'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $roles = $this->rolesService->getAll();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $dto = new StoreDto(
            $data['name'], 
            $data['email'],
            Hash::make($data['password']),
            $data['role_id'],
        );
        
        $this->service->add($dto);

        return redirect()->route('admin.users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $userId): View
    {
        Gate::authorize('viewAny', User::class);

        try {
            $user = $this->service->getById($userId);
        } catch (UserNotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        $data = [
            'userId' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'emailVerified' => !empty($user->email_verified_at) ? 'Да' : 'Нет',
            'role' => $user->role->getTitle(),
            'createdAt' => $user->created_at->format('d.m.Y H:i'),
            'updatedAt' => $user->updated_at->format('d.m.Y H:i'),
        ];

        return view('admin.users.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $userId): View
    {
        Gate::authorize('update', User::class);

        try {
            $user = $this->service->getById($userId);
        } catch (UserNotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        $roles = $this->rolesService->getAll();

        $data = [
            'userId' => $userId,
            'name' => $user->name,
            'email' => $user->email,
            'role_id' => $user->role_id,
            'roles' => $roles,
        ];

        return view('admin.users.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, int $userId): RedirectResponse
    {
        Gate::authorize('update', User::class);

        $data = $request->validated();
        $dto = new UpdateDto(
            $userId, 
            $data['name'], 
            $data['email'],
            $data['role_id']
        );

        try {
            $this->service->update($dto);
        } catch (UserNotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        return redirect()->route('admin.users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $userId): RedirectResponse
    {
        Gate::authorize('delete', User::class);

        try {
            $this->service->delete($userId);
        } catch (UserNotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['Невозможно выполнить удаление']);
        }

        return redirect()->route('admin.users.index');
    }

    public function export() 
    {
        return Excel::download(new UsersExport($this->service), 'users.xlsx');
    }
}
