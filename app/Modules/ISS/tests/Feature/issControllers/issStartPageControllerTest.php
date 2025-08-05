<?php

namespace App\Modules\ISS\tests\Feature\issControllers;

//use PHPUnit\Framework\TestCase;
use App\Modules\ISS\tests\TestCase;
use Mockery\MockInterface;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use App\Modules\ISS\src\Http\Controllers\IssStartPageController;
use Illuminate\Auth\AuthManager;
use App\Modules\ISS\src\Services\issUser\getUserData\GetUserData;
use App\Modules\ISS\src\Services\issUser\getUserData\InputDTO as userDataIssDTO;
use App\Modules\ISS\src\Services\issUser\getAllUsers\GetAllUsers;
use App\Modules\ISS\src\Services\issUser\getAllUsers\InputDTO as allUsersDataDTO;
use App\Modules\ISS\src\Services\issUser\getUsersRelatedToManager\GetUsersRelatedToManager;
use App\Modules\ISS\src\Services\issUser\getUsersRelatedToManager\InputDTO as someUsersDataDTO;
use App\Modules\ISS\src\Services\issUser\loadUserDataFromMainApp\LoadUserDataFromMainApp;
use App\Modules\ISS\src\Services\issUser\loadUserDataFromMainApp\InputDTO as userDataMainAppDTO;
use App\Modules\ISS\src\Services\issUser\loadUserDataFromMainApp\FioDTO;
use App\Modules\ISS\src\Services\issUser\loadUserDataFromMainApp\OrganizationDTO;
use App\Modules\ISS\src\Services\issUser\createIssUserWebToken\CreateIssUserWebToken;
use App\Modules\ISS\src\Services\issUser\createIssUserWebToken\InputDTO as setTokenDelDTO;
use App\Models\User;

class issStartPageControllerTest extends TestCase
{
    use DatabaseTruncation;

    private User $userNotRegisteredInISS;
    private User $userRegisteredInISS;

    public function setUp(): void
    {
        parent::setUp();

        $this->userNotRegisteredInISS = User::factory()->create(['password' => 'test12345678']);
        $this->userRegisteredInISS = User::factory()->create(['password' => 'test12345']);
    }

    public function tearDown(): void
    {
        parent::tearDown();

        unset($this->userNotRegisteredInISS);
        unset($this->userRegisteredInISS);
    }

    /**
     * Проверка что пользователь, авторизованный в основном приложении, но не зарегистрированный в ИОС,
     * может зайти на главную страницу ИОС
     */
    public function test_authorized_in_main_app_but_iss_unregistered_user_can_enter_index()
    {
        $this->markTestSkipped('out of date must be remade with accordance to controller new changes');

        //глушим реальные сервисы, которые получает контроллер
        $this->mock(AuthManager::class, function (MockInterface $mock): void {
            $mock->shouldReceive('user')->andReturn($this->userNotRegisteredInISS);
        });

        $this->mock(GetUserData::class, function (MockInterface $mock): void {
            //$mock->shouldReceive('getUserData')->andReturn(null);
            $mock->shouldReceive('invoke')->andReturn(null);
        });

        $this->mock(GetAllUsers::class, function (MockInterface $mock): void {
            //$mock->shouldReceive('getAllUsers')->andReturn([]);
            $mock->shouldReceive('invoke')->andReturn([]);
        });

        $this->mock(GetUsersRelatedToManager::class, function (MockInterface $mock): void {
            //$mock->shouldReceive('getUsersRelatedToManager')->andReturn([]);
            $mock->shouldReceive('invoke')->andReturn([]);
        });

        $this->mock(LoadUserDataFromMainApp::class, function (MockInterface $mock): void {
            //$mock->shouldReceive('loadUserDataFromMainApp')->andReturn('fake user not loads');
            $mock->shouldReceive('invoke')->andReturn('fake user not loads');
        });

        $this->mock(CreateIssUserWebToken::class, function (MockInterface $mock): void {
            //$mock->shouldReceive('createIssUserWebToken')->andReturn(null);
            $mock->shouldReceive('invoke')->andReturn(null);
        });

        $response = $this->actingAs($this->userNotRegisteredInISS)->get(route('iss'));
        $response->assertStatus(
            200,
            'User authorized in main application, but unregistered in ISS must can come to start page!'
        );
    }

    /**
     * Проверка что пользователь, авторизованный в основном приложении, и зарегистрированный в ИОС,
     * может зайти на главную страницу ИОС,
     * при этом выполняется вход в ИОС (авторизация в ИОС)
     * при этом открывается доступ к ссылкам на аккаунт ИОС и страницу административного интерфейса
     */
    public function test_authorized_in_main_and_iss_registered_user_can_enter_index()
    {
        $this->markTestIncomplete();
        //глушим реальные сервисы, которые получает контроллер
        $this->mock(AuthManager::class, function (MockInterface $mock): void {
            $mock->shouldReceive('user')->andReturn($this->userRegisteredInISS);
        });

        $this->mock(GetUserData::class, function (MockInterface $mock): void {
            $mock->shouldReceive('getUserData')
                //->with(new userDataIssDTO(fieldName: 'user_id', fieldValue: $this->userRegisteredInISS->id))
                ->andReturn(null);
        });

        $this->mock(GetAllUsers::class, function (MockInterface $mock): void {
            $mock->shouldReceive('getAllUsers')
                //->with(new allUsersDataDTO(returnedFields: ['id']))
                ->andReturn([]);
        });

        $this->mock(GetUsersRelatedToManager::class, function (MockInterface $mock): void {
            $mock->shouldReceive('getUsersRelatedToManager')
                //->with(new someUsersDataDTO(currentUser: session('issUser'), returnedFields: ['id']))
                ->andReturn([]);
        });

        $this->mock(LoadUserDataFromMainApp::class, function (MockInterface $mock): void {
            $mock->shouldReceive('loadUserDataFromMainApp')
                ->andReturn('fake user not loads');
        });

        $this->mock(CreateIssUserWebToken::class, function (MockInterface $mock): void {
            $mock->shouldReceive('createIssUserWebToken')
                ->andReturn(null);
        });

        $response = $this->actingAs($this->userRegisteredInISS)->get(route('iss'));
        $response->assertStatus(
            200,
            'User authorized in main application, and unregistered in ISS must can come to start page!'
        );
        //проверки
        //в сессии должен появиться объект IssUser
        //должны извлечся id пользователей ИОС в зависимостр от роли текущего пользователя (вызов заглушек сервисов)
        //в данные пользователя должны обновиться (должен вызваться сервис обновления, сам сервис глушим)
        //
        //
    }

}
