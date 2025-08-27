<?php

namespace ISS\App\Presentation\Http\Controllers;


use Illuminate\View\View;
use Illuminate\Auth\AuthManager;
use Illuminate\Support\Facades\Cache;
use ISS\App\Application\Services\IssUser\IssUser;
use ISS\App\Application\Services\IssUser\GetUserData\GetUserData;
use ISS\App\Application\Services\IssUser\GetUserData\InputDTO as userDataIssDTO;
use ISS\App\Application\Services\IssUser\LoadUserDataFromMainApp\LoadUserDataFromMainApp;
use ISS\App\Application\Services\IssUser\LoadUserDataFromMainApp\InputDTO as userDataMainAppDTO;
use ISS\App\Application\Services\IssUser\LoadUserDataFromMainApp\FioDTO;
use ISS\App\Application\Services\IssUser\LoadUserDataFromMainApp\OrganizationDTO;
use ISS\App\Application\Services\IssUser\LoadUserDataFromMainApp\ContactDTO;
use ISS\App\Application\Services\IssUser\GetAllUsers\GetAllUsers;
use ISS\App\Application\Services\IssUser\GetAllUsers\InputDTO as allUsersDataDTO;
use ISS\App\Application\Services\IssUser\GetUsersRelatedToManager\GetUsersRelatedToManager;
use ISS\App\Application\Services\IssUser\GetUsersRelatedToManager\InputDTO as someUsersDataDTO;
use ISS\App\Application\Services\IssUser\CreateIssUserWebToken\CreateIssUserWebToken;
use ISS\App\Application\Services\IssUser\CreateIssUserWebToken\InputDTO as setTokenDelDTO;
use ISS\App\Application\Services\IssUser\DeleteIssUserWebToken\DeleteIssUserWebToken;
use ISS\App\Application\Services\IssUser\DeleteIssUserWebToken\InputDTO as delTokenDelDTO;

/**
 * Контроллер главной страницы ИОС
 * содержит:
 * - метод для отображения страницы
 */

class IssStartPageController extends Controller
{
   /**
    * Отображение начальной страницы ИОС
    * @param AuthManager              $auth
    * @param GetUserData              $getUserData
    * @param GetAllUsers              $getAllUsers
    * @param GetUsersRelatedToManager $getUsersRelatedToManager
    * @param LoadUserDataFromMainApp  $loadUserDataFromMainApp
    * @param CreateIssUserWebToken    $createIssUserWebToken
    * @return View
    */
   public function index(
       AuthManager              $auth,
       GetUserData              $getUserData,
       GetAllUsers              $getAllUsers,
       GetUsersRelatedToManager $getUsersRelatedToManager,
       LoadUserDataFromMainApp  $loadUserDataFromMainApp,
       CreateIssUserWebToken    $createIssUserWebToken,
   ): View
   {
       //авторизация в ИОС
       if (!session()->has('issUser') || is_null(session('issUser')->issUserId)) {
           //первичный вход в ИОС
           request()->session()->remove('issUser');
           $issUser = new IssUser();

           //находим пользователя ИОС по коду пользователя из основного приложения
           $issUserData = $getUserData(new userDataIssDTO(fieldName: 'user_id', fieldValue: $auth->user()->id));

           //создание в сессии объекта для авторизованного пользователя ИОС
           if ($issUserData) {
               $token = $createIssUserWebToken(new setTokenDelDTO(issUserId: $issUserData->id));

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
                       $getAllUsers(
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
                       $getUsersRelatedToManager(
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
                   $operResult = $loadUserDataFromMainApp(
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
                           contact: new ContactDTO(
                               tableName: config('iss.CONFIG_DATA_FROM_MAIN_APP.contact.tableName'),
                               fieldEmail: config('iss.CONFIG_DATA_FROM_MAIN_APP.contact.fieldEmail'),
                               fieldCodeName: config('iss.CONFIG_DATA_FROM_MAIN_APP.contact.fieldCodeName'),
                           ),
                           issUserId: $issUserId
                       )
                   );

                   if ($operResult->result != 'ok') {
                       //запиль в лог
                   }
               }


           }

           Cache::tags(['userData'])->flush();
       }
       //авторизован в ИОС (никаких действий не требуется)

       return view('iss::issMainPage', ['issUser' => session()->get('issUser')]);
   }

   public function issExit(DeleteIssUserWebToken $deleteIssUserWebToken)
   {
       if (!is_null(session('issUser')->issUserId) && !is_null(session('issUser')->webToken)) {
           $deleteIssUserWebToken(new delTokenDelDTO(issUserId: session('issUser')->issUserId));
       }

       session()->remove('issUser');
       Cache::tags(['userData'])->flush();

       return redirect()->route('main');
   }
}
