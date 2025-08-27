<?php

namespace ISS\App\Presentation\Http\Controllers\AdminInterface;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use ISS\App\Application\Services\IssUser\GetAllUsers\GetAllUsers;
use ISS\App\Application\Services\IssUser\GetAllUsers\InputDTO as getAllUsersInputDTO;
use ISS\App\Application\Services\IssUser\GetUserData\GetUserData;
use ISS\App\Application\Services\IssUser\GetUserData\InputDTO as getUserDataInputDTO;
use ISS\App\Application\Services\AppServices\IssUser\DeleteIssUser\DeleteIssUser;
use ISS\App\Application\Services\AppServices\IssUser\DeleteIssUser\InputDTO as deleteIssUserInputDTO;
use ISS\App\Application\Services\IssUser\UpdateIssUser\UpdateIssUser;
use ISS\App\Application\Services\IssUser\UpdateIssUser\InputDTO as updateIssUserInputDTO;
use ISS\App\Application\Services\IssUser\CreateIssUser\CreateIssUser;
use ISS\App\Application\Services\IssUser\CreateIssUser\InputDTO as createIssUserInputDTO;

/**
 * @var array $userParametersLabels заголовки для колонок таблиц параметров пользователя
 * (порядок параметров пользователя в методах контроллера должен соответствовать порядку этих заголовков)
 * @var array $errorMessages сообщения об ошибках валидации
 * @var array $validationRules правила валидации для запросов создания и редактирования пользователей ИОС
 */

class MainIssUserManageController
{
    private array $userParametersLabels;
    private array $validationRules;
    private array $errorMessages;

    public function __construct()
    {
        $this->userParametersLabels = [
            'issUserId' => __('iss::issAdminUserCRUDInterface.issUserId'),
            'avatar' => __('iss::issAdminUserCRUDInterface.avatar'),
            'mainAppUserId' => __('iss::issAdminUserCRUDInterface.mainAppUserId'),
            'userRole' => __('iss::issAdminUserCRUDInterface.userRole'),
            'organization' => __('iss::issAdminUserCRUDInterface.organization'),
            'lastName' => __('iss::issAdminUserCRUDInterface.lastName'),
            'firstName' => __('iss::issAdminUserCRUDInterface.firstName'),
            'secondName' => __('iss::issAdminUserCRUDInterface.secondName'),
            'email' => __('iss::issAdminUserCRUDInterface.email'),
        ];

        $this->validationRules = [
            //'issUserId' => 'nullable|integer',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'mainAppUserId' => 'required|integer',
            'userRole' => 'required|string|in:' . config('iss.ROLE_ADMIN') . ',' . config('iss.ROLE_MANAGER') . ',' . config('iss.ROLE_EMPLOYEE'),
            'organization' => 'nullable|string',
            'lastName' => 'nullable|string',
            'firstName' => 'nullable|string',
            'secondName' => 'nullable|string',
            'email' => 'nullable|email',
        ];

        $this->errorMessages = [
            //'issUserId.integer' => __('iss::issAdminUserCRUDInterface.validationErrors.mustBeInteger'),
            'avatar.image' => __('iss::issAdminUserCRUDInterface.validationErrors.avatarImage'),
            'avatar.mimes' => __('iss::issAdminUserCRUDInterface.validationErrors.avatarTypes'),
            'mainAppUserId.required' => __('iss::issAdminUserCRUDInterface.validationErrors.mainAppUserIdRequired'),
            'mainAppUserId.integer' => __('iss::issAdminUserCRUDInterface.validationErrors.mustBeInteger'),
            'userRole.required' => __('iss::issAdminUserCRUDInterface.validationErrors.userRoleRequired'),
            'userRole.string' => __('iss::issAdminUserCRUDInterface.validationErrors.mustBeString'),
            'userRole.in' => __('iss::issAdminUserCRUDInterface.validationErrors.userRoleAccepted', ['values' => config('iss.ROLE_ADMIN') . ',' . config('iss.ROLE_MANAGER') . ',' . config('iss.ROLE_EMPLOYEE')]),
            'organization.string' => __('iss::issAdminUserCRUDInterface.validationErrors.mustBeString'),
            'lastName.string' => __('iss::issAdminUserCRUDInterface.validationErrors.mustBeString'),
            'firstName.string' => __('iss::issAdminUserCRUDInterface.validationErrors.mustBeString'),
            'secondName.string' => __('iss::issAdminUserCRUDInterface.validationErrors.mustBeString'),
            'email.email' => __('iss::issAdminUserCRUDInterface.validationErrors.emailFormatWrong'),
        ];

    }

    /**
     * Показать всех пользователей ИОС.
     * маршрут GET iss/admin/MainIssUserManage
     *
     * @param GetAllUsers $getAllUsers сервис, для извлечения данных всех пользователей
     */
    public function index(GetAllUsers $getAllUsers)
    {
        if (Session::has('actionSuccess')) {
            $success = Session::get('actionSuccess');
        } else {
            $success = false;
        }

        $issUsers = $getAllUsers(new getAllUsersInputDTO())->users;

        $i = 0; $userParameters = [];
        foreach ($issUsers as $issUser) {
            $userParameters[$i]['issUserId'] = $issUser->id;
            $userParameters[$i]['avatar'] = 'issPublic/' . $issUser->avatarFilePath;
            $userParameters[$i]['mainAppUserId'] = $issUser->userId;
            $userParameters[$i]['userRole'] = $issUser->roleName;
            $userParameters[$i]['organization'] = $issUser->organization;
            $userParameters[$i]['lastName'] = $issUser->lastName;
            $userParameters[$i]['firstName'] = $issUser->name;
            $userParameters[$i]['secondName'] = $issUser->secondName;
            $userParameters[$i]['email'] = $issUser->email;
            $i++;
        }
        unset($i);

        return view(
            'iss::adminInterface.issUserCRUD.issUserList',
            ['labels' => $this->userParametersLabels, 'userParameters' => $userParameters, 'success' => $success]
        );
    }

    /**
     * Показать данные пользователя ИОС
     * маршрут GET iss/admin/MainIssUserManage/{MainIssUserManage}
     */
    public function show(int $issUserId)
    {
        //not used
    }

    /**
     * Отобразить форму для создания пользователя ИОС
     * маршрут GET iss/admin/MainIssUserManage/create
     */
    public function create()
    {
        if (Session::has('actionSuccess')) {
            $success = Session::get('actionSuccess');
        } else {
            $success = false;
        }

        $userParameters = [
            'issUserId' => null,
            'avatar' => null,
            'mainAppUserId' => null,
            'userRole' => null,
            'organization' => null,
            'lastName' => null,
            'firstName' => null,
            'secondName' => null,
            'email' => null
        ];
        return view(
            'iss::adminInterface.issUserCRUD.issUserCreateOrEdit',
            [
                'action' => config('iss.ISS_USER_ACTION.create'),
                'labels' => $this->userParametersLabels,
                'userParameters' => $userParameters,
                'success' => $success
            ]
        );
    }

    /**
     * Создать пользователя ИОС
     * маршрут POST iss/admin/MainIssUserManage
     * @param CreateIssUser $createIssUser сервис создания нового пользователя ИОС
     */
    public function store(Request $request, CreateIssUser $createIssUser)
    {
        $validator = Validator::make($request->input(), $this->validationRules, $this->errorMessages);
        try {
            $validated = $validator->validated();
        } catch (\Error | \Exception $e) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $newIssUser = $createIssUser(new createIssUserInputDTO(
            avatarFile: $request->file('avatar'),
            userId: $validated['mainAppUserId'],//$request->input('mainAppUserId'),
            roleName: $validated['userRole'],//$request->input('userRole'),
            organization: $validated['organization'],//$request->input('organization'),
            name: $validated['firstName'],//$request->input('firstName'),
            secondName: $validated['secondName'],//$request->input('secondName'),
            lastName: $validated['lastName'],//$request->input('lastName'),
            email: $validated['email'],//$request->input('email')
        ));

        if ($newIssUser->result === true) {
            Session::flash('actionSuccess', __('iss::issAdminUserCRUDInterface.createSuccess'));
            return redirect()->back();
        } else {
            return redirect()->back()->withErrors(['actionError' => __('iss::issAdminUserCRUDInterface.createError')]);
        }
    }

    /**
     * Отобразить форму для редактирования пользователя ИОС
     * маршрут GET iss/admin/MainIssUserManage/{MainIssUserManage}/edit
     * @param GetUserData $getUserData сервис для извлечения данных одного пользователя
     */
    public function edit(GetUserData $getUserData, int $issUserId)
    {
        if (Session::has('actionSuccess')) {
            $success = Session::get('actionSuccess');
        } else {
            $success = false;
        }

        $issUser = $getUserData(new getUserDataInputDTO(fieldName: 'id', fieldValue: $issUserId));

        $userParameters['issUserId'] = $issUser->id;
        $userParameters['avatar'] = null; //'issPublic/' . $issUser->avatarFilePath;
        $userParameters['mainAppUserId'] = $issUser->userId;
        $userParameters['userRole'] = $issUser->roleName;
        $userParameters['organization'] = $issUser->organization;
        $userParameters['lastName'] = $issUser->lastName;
        $userParameters['firstName'] = $issUser->name;
        $userParameters['secondName'] = $issUser->secondName;
        $userParameters['email'] = $issUser->email;

        return view(
            'iss::adminInterface.issUserCRUD.issUserCreateOrEdit',
            [
                'action' => config('iss.ISS_USER_ACTION.edit'),
                'labels' => $this->userParametersLabels,
                'userParameters' => $userParameters,
                'success' => $success
            ]
        );
    }

    /**
     * Обновить данные пользователя ИОС
     * маршрут PUT|PATCH iss/admin/MainIssUserManage/{MainIssUserManage}
     * @param UpdateIssUser $updateIssUser
     */
    public function update(Request $request, int $issUserId, UpdateIssUser $updateIssUser)
    {
        $validator = Validator::make($request->input(), $this->validationRules, $this->errorMessages);
        try {
            $validated = $validator->validated();
        } catch (\Error | \Exception $e) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $result = $updateIssUser(new updateIssUserInputDTO(
                id: $issUserId,
                avatarFile: $request->file('avatar'),
                userId: $validated['mainAppUserId'],//$request->input('mainAppUserId'),
                roleName: $validated['userRole'],//$request->input('userRole'),
                organization: $validated['organization'],//$request->input('organization'),
                name: $validated['firstName'],//$request->input('firstName'),
                secondName: $validated['secondName'],//$request->input('secondName'),
                lastName: $validated['lastName'],//$request->input('lastName'),
                email: $validated['email'],//$request->input('email')
        ));

        if ($result->result === true) {
            Session::flash('actionSuccess', __('iss::issAdminUserCRUDInterface.updatedSuccess'));
            return redirect()->back();
        } else {
            return redirect()->back()->withErrors(['actionError' => __('iss::issAdminUserCRUDInterface.updateError')]);
        }
    }

    /**
     * Удалить пользователя ИОС
     * маршрут DELETE iss/admin/MainIssUserManage/{MainIssUserManage}
     * @param DeleteIssUser $deleteIssUser сервис удаления пользователя ИОС
     */
    public function destroy(DeleteIssUser $deleteIssUser, int $issUserId)
    {
        $result = $deleteIssUser(new deleteIssUserInputDTO($issUserId));

        if ($result->result === true) {
            Session::flash('actionSuccess', __('iss::issAdminUserCRUDInterface.deleteSuccess'));
            return redirect()->back();
        } else {
            return redirect()->back()->withErrors(['actionError' => __('iss::issAdminUserCRUDInterface.deleteError')]);
        }
    }
}
