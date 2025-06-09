<?php

namespace App\Modules\ISS\src\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\AuthManager;
use App\Modules\ISS\src\Services\issUser\IssUser;
use App\Modules\ISS\src\Services\issUser\getUserData\GetUserData;
use App\Modules\ISS\src\Services\issUser\getUserData\InputDTO as userDataIssDTO;
use App\Modules\ISS\src\Services\issUser\loadUserDataFromMainApp\LoadUserDataFromMainApp;
use App\Modules\ISS\src\Services\issUser\loadUserDataFromMainApp\InputDTO as userDataMainAppDTO;
use App\Modules\ISS\src\Services\issUser\loadUserDataFromMainApp\FioDTO;
use App\Modules\ISS\src\Services\issUser\loadUserDataFromMainApp\OrganizationDTO;
use App\Modules\ISS\src\Services\issUser\getAllUsers\GetAllUsers;
use App\Modules\ISS\src\Services\issUser\getAllUsers\InputDTO as allUsersDataDTO;
use App\Modules\ISS\src\Services\issUser\getUsersRelatedToManager\GetUsersRelatedToManager;
use App\Modules\ISS\src\Services\issUser\getUsersRelatedToManager\InputDTO as someUsersDataDTO;
use App\Modules\ISS\src\Services\issUser\createIssUserWebToken\CreateIssUserWebToken;
use App\Modules\ISS\src\Services\issUser\createIssUserWebToken\InputDTO as setTokenDelDTO;
use App\Modules\ISS\src\Services\issUser\deleteIssUserWebToken\DeleteIssUserWebToken;
use App\Modules\ISS\src\Services\issUser\deleteIssUserWebToken\InputDTO as delTokenDelDTO;


class IssStartPageController extends Controller
{
   public function index(
       AuthManager              $auth,
       GetUserData              $getUserData,
       GetAllUsers              $getAllUsers,
       GetUsersRelatedToManager $getUsersRelatedToManager,
       LoadUserDataFromMainApp  $loadUserDataFromMainApp,
       CreateIssUserWebToken    $createIssUserWebToken,
   )
   {
       //авторизация в ИОС
       if (!session()->has('issUser') || is_null(session('issUser')->issUserId)) {
           //первичный вход в ИОС
           request()->session()->remove('issUser');
           $issUser = new IssUser();

           //находим пользователя ИОС по коду пользователя из основного приложения
           $issUserData = $getUserData->getUserData(new userDataIssDTO(fieldName: 'user_id', fieldValue: $auth->user()->id));

           //создание в сессии объекта для авторизованного пользователя ИОС
           if ($issUserData) {
               $token = $createIssUserWebToken->createIssUserWebToken(new setTokenDelDTO(issUserId: $issUserData->id));

               $issUser->issUserId = $issUserData->id;
               $issUser->issUserRole = $issUserData->roleName;
               $issUser->issUserAvatar = $issUserData->avatarFilePath;
               $issUser->organization = $issUserData->organization;
               $issUser->name = $issUserData->name;
               $issUser->secondName = $issUserData->secondName;
               $issUser->lastName = $issUserData->lastName;
               $issUser->webToken = $token->issUserWebToken;
           }
           session()->put(['issUser' => $issUser]);

           //обновление данных пользователя ИОС из основного приложения
           if (isset($issUser->issUserId) && isset($auth->user()->id)) {
               $issUserIds = [];

               //если зашел админ, то найти коды всех пользователей ИОС
               if ($issUser->issUserRole == config('iss.ROLE_ADMIN')) {
                   $issUserIds = array_map(
                       function ($item) {
                           return $item->id;
                       },
                       $getAllUsers->getAllUsers(
                           new allUsersDataDTO(
                               returnedFields: ['id']
                           )
                       )->users
                   );
               }
               //если зашел менеджер, то найти коды сотрудников его фирмы
               if ($issUser->issUserRole == config('iss.ROLE_MANAGER')) {
                   $issUserIds = array_map(
                       function ($item) {
                           return $item->id;
                       },
                       $getUsersRelatedToManager->getUsersRelatedToManager(
                           new someUsersDataDTO(
                               currentUser: $issUser,
                               returnedFields: ['id']
                           )
                       )->users
                   );
               }
               //если зашел простой сотрудник то передает только один код
               if ($issUser->issUserRole == config('iss.ROLE_EMPLOYEE')) {
                   $issUserIds = [$issUser->issUserId];
               }

               //вызов сервиса обновления данных для всех найденных пользователей
               foreach ($issUserIds as $issUserId) {
                   $operResult = $loadUserDataFromMainApp->loadUserDataFromMainApp(
                       new userDataMainAppDTO(
                           organization: new OrganizationDTO(
                               tableName: config('iss.CONFIG_DATA_FROM_MAIN_APP.organization.tableName'),
                               fieldOrganizationName: config('iss.CONFIG_DATA_FROM_MAIN_APP.organization.fieldOrganizationName'),
                               fieldCodeName: config('iss.CONFIG_DATA_FROM_MAIN_APP.organization.fieldCodeName')
                           ),
                           fio: new FioDTO(
                               tableName: config('iss.CONFIG_DATA_FROM_MAIN_APP.fio.tableName'),
                               fieldName: config('iss.CONFIG_DATA_FROM_MAIN_APP.fio.fieldName'),
                               fieldSecondName: config('iss.CONFIG_DATA_FROM_MAIN_APP.fio.fieldSecondName'),
                               fieldLastName: config('iss.CONFIG_DATA_FROM_MAIN_APP.fio.fieldLastName'),
                               fieldCodeName: config('iss.CONFIG_DATA_FROM_MAIN_APP.fio.fieldCodeName'),
                           ),
                           issUserId: $issUserId
                       )
                   );

                   if ($operResult->result != 'ok') {
                       //запиль в лог
                   }
               }


           }
       }
       //авторизован в ИОС (никаких действий не требуется)

       return view('iss::issMainPage', ['issUser' => session()->get('issUser')]);
   }

   public function issExit(DeleteIssUserWebToken $deleteIssUserWebToken)
   {
       if (!is_null(session('issUser')->issUserId) && !is_null(session('issUser')->webToken)) {
           $deleteIssUserWebToken->deleteIssUserWebToken(new delTokenDelDTO(issUserId: session('issUser')->issUserId));
       }

       session()->remove('issUser');

       return redirect()->route('main');
   }
}
