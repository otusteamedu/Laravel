<?php

namespace App\Modules\ISS\tests\Unit\issServices;

//use PHPUnit\Framework\TestCase;
use App\Modules\ISS\tests\TestCase;
use PHPUnit\Framework\Attributes\Group;
use Mockery\MockInterface;
use App\Modules\ISS\src\Services\issUser\IssUserRepoInterface;
use App\Modules\ISS\src\Services\issUser\createIssUserWebToken\CreateIssUserWebToken;
use App\Modules\ISS\src\Services\issUser\createIssUserWebToken\InputDTO as createTokenInputDTO;
use App\Modules\ISS\src\Services\issUser\createIssUserWebToken\OutputDTO as createTokenOutputDTO;
use App\Modules\ISS\src\Services\issUser\deleteIssUserWebToken\DeleteIssUserWebToken;
use App\Modules\ISS\src\Services\issUser\deleteIssUserWebToken\InputDTO as deleteTokenInputDTO;
use App\Modules\ISS\src\Services\issUser\deleteIssUserWebToken\OutputDTO as deleteTokenOutputDTO;
use App\Modules\ISS\src\Services\issUser\fetchIssUserWebToken\FetchIssUserWebToken;
use App\Modules\ISS\src\Services\issUser\fetchIssUserWebToken\InputDTO as fetchTokenInputDTO;
use App\Modules\ISS\src\Services\issUser\fetchIssUserWebToken\OutputDTO as fetchTokenOutputDTO;
use App\Modules\ISS\src\Services\issUser\getAllUsers\GetAllUsers;
use App\Modules\ISS\src\Services\issUser\getAllUsers\InputDTO as getAllUsersInputDTO;
use App\Modules\ISS\src\Services\issUser\getAllUsers\OutputDTO as getAllUsersOutputDTO;
use App\Modules\ISS\src\Services\issUser\getAllUsers\SingleUserDTO as allUsersSingleUserDTO;
use App\Modules\ISS\src\Services\issUser\getUserData\GetUserData;
use App\Modules\ISS\src\Services\issUser\getUserData\InputDTO as getOneUserInputDTO;
use App\Modules\ISS\src\Services\issUser\getUserData\OutputDTO as getOneUserOutputDTO;
use App\Modules\ISS\src\Services\issUser\getUsersRelatedToManager\GetUsersRelatedToManager;
use App\Modules\ISS\src\Services\issUser\getUsersRelatedToManager\InputDTO as getSomeUsersInputDTO;
use App\Modules\ISS\src\Services\issUser\getUsersRelatedToManager\OutputDTO as getSomeUsersOutputDTO;
use App\Modules\ISS\src\Services\issUser\getUsersRelatedToManager\SingleUserDTO as getSomeUsersSingleUserDTO;
use App\Modules\ISS\src\Services\issUser\loadUserDataFromMainApp\LoadUserDataFromMainApp;
use App\Modules\ISS\src\Services\issUser\loadUserDataFromMainApp\InputDTO as loadInputDTO;
use App\Modules\ISS\src\Services\issUser\loadUserDataFromMainApp\OutputDTO as loadOutputDTO;
use App\Modules\ISS\src\Services\issUser\loadUserDataFromMainApp\OrganizationDTO;
use App\Modules\ISS\src\Services\issUser\loadUserDataFromMainApp\FioDTO;
use App\Modules\ISS\src\Services\issUser\loadUserDataFromMainApp\ContactDTO;
use App\Modules\ISS\src\Services\issUser\IssUser;
use App\Modules\ISS\src\Services\issUser\deleteIssUser\DeleteIssUser;
use App\Modules\ISS\src\Services\issUser\deleteIssUser\InputDTO as deleteIssUserInputDTO;
use App\Modules\ISS\src\Services\issUser\deleteIssUser\OutputDTO as deleteIssUserOutputDTO;

class IssUserTest extends TestCase
{
    private $orgDTO;
    private $fioDTO;
    private $contactDTO;
    private $setOrganization;
    private $setFio;
    private $setContact;

    public function setUp(): void
    {
        parent::setUp();

        $this->orgDTO = new OrganizationDTO(
            tableName: config('iss.CONFIG_DATA_FROM_MAIN_APP.organization.tableName'),
            fieldOrganizationName: config('iss.CONFIG_DATA_FROM_MAIN_APP.organization.fieldOrganizationName'),
            fieldCodeName: config('iss.CONFIG_DATA_FROM_MAIN_APP.organization.fieldCodeName')
        );

        $this->fioDTO = new FioDTO(
            tableName: config('iss.CONFIG_DATA_FROM_MAIN_APP.fio.tableName'),
            fieldName: config('iss.CONFIG_DATA_FROM_MAIN_APP.fio.fieldName'),
            fieldSecondName: config('iss.CONFIG_DATA_FROM_MAIN_APP.fio.fieldSecondName'),
            fieldLastName: config('iss.CONFIG_DATA_FROM_MAIN_APP.fio.fieldLastName'),
            fieldCodeName: config('iss.CONFIG_DATA_FROM_MAIN_APP.fio.fieldCodeName'),
        );

        $this->contactDTO = new ContactDTO(
            tableName: config('iss.CONFIG_DATA_FROM_MAIN_APP.contact.tableName'),
            fieldEmail: config('iss.CONFIG_DATA_FROM_MAIN_APP.contact.fieldEmail'),
            fieldCodeName: config('iss.CONFIG_DATA_FROM_MAIN_APP.contact.fieldCodeName')
        );

        $this->setOrganization = [
            'table_name' => $this->orgDTO->tableName,
            'fields' => [$this->orgDTO->fieldOrganizationName],
            'field_code_name' => $this->orgDTO->fieldCodeName,
            'user_id' => 132435
        ];
        $this->setFio = [
            'table_name' => $this->fioDTO->tableName,
            'fields' => [$this->fioDTO->fieldName, $this->fioDTO->fieldSecondName, $this->fioDTO->fieldLastName],
            'field_code_name' => $this->fioDTO->fieldCodeName,
            'user_id' => 132435
        ];

        $this->setContact = [
            'table_name' => $this->contactDTO->tableName,
            'fields' => [$this->contactDTO->fieldEmail],
            'field_code_name' => $this->contactDTO->fieldCodeName,
            'user_id' => 132435
        ];
    }

    public function tearDown(): void
    {
        parent::tearDown();

        unset($this->orgDTO, $this->fioDTO, $this->setOrganization, $this->setFio);
    }

    /**
     * Проверка что сервис возвращает правильную структуру данных для нового веб токена ИОС
     */
    #[Group(name: "webTokenISS")]
    public function test_create_iss_user_web_token_service()
    {
        //сервис отработал без ошибок
        $fakeRepo = $this->mock(IssUserRepoInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('setWebToken')->once()->andReturn([true]);
        });

        $testedService = new CreateIssUserWebToken($fakeRepo);
        $result = $testedService(new CreateTokenInputDTO(issUserId: 0));

        $this->assertInstanceOf(createTokenOutputDTO::class, $result);
        $this->assertIsString($result->issUserWebToken);

        //сервис выдал ошибку
        $fakeRepo = $this->createMock(IssUserRepoInterface::class);
        $fakeRepo->method('setWebToken')->will($this->throwException(new \Exception()));

        $testedService = new CreateIssUserWebToken($fakeRepo);
        $result = $testedService(new CreateTokenInputDTO(issUserId: 0));
        $this->assertInstanceOf(createTokenOutputDTO::class, $result);
        $this->assertNull($result->issUserWebToken);
    }

    /**
     * Проверка что сервис возвращает правильную структуру данных при удалении веб токена ИОС
     */
    #[Group(name: "webTokenISS")]
    public function test_delete_iss_user_web_token_service()
    {
        //сервис отработал без ошибок
        $fakeRepo = $this->mock(IssUserRepoInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('delWebToken')->once()->andReturn([true]);
        });

        $testedService = new DeleteIssUserWebToken($fakeRepo);
        $result = $testedService(new deleteTokenInputDTO(issUserId: 0));

        $this->assertInstanceOf(deleteTokenOutputDTO::class, $result);
        $this->assertTrue($result->result);

        //сервис выдал ошибку
        $fakeRepo = $this->createMock(IssUserRepoInterface::class);
        $fakeRepo->method('delWebToken')->will($this->throwException(new \Exception()));

        $testedService = new DeleteIssUserWebToken($fakeRepo);
        $result = $testedService(new deleteTokenInputDTO(issUserId: 0));
        $this->assertInstanceOf(deleteTokenOutputDTO::class, $result);
        $this->assertFalse($result->result);
    }

    /**
     * Проверка что сервис возвращает правильную структуру данных при извлечении веб токена ИОС
     */
    #[Group(name: "webTokenISS")]
    public function test_fetch_iss_user_web_token_service()
    {
        //сервис отработал без ошибок
        $fakeRepo = $this->mock(IssUserRepoInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('fetchWebToken')->once()->andReturn(['web_token' => '2wrgdbghg4e5tgnbgq']);
        });

        $testedService = new FetchIssUserWebToken($fakeRepo);
        $result = $testedService(new fetchTokenInputDTO(issUserId: 0));

        $this->assertInstanceOf(fetchTokenOutputDTO::class, $result);
        $this->assertIsString($result->issUserWebToken);

        //сервис выдал ошибку
        $fakeRepo = $this->createMock(IssUserRepoInterface::class);
        $fakeRepo->method('fetchWebToken')->will($this->throwException(new \Exception()));

        $testedService = new FetchIssUserWebToken($fakeRepo);
        $result = $testedService(new fetchTokenInputDTO(issUserId: 0));
        $this->assertInstanceOf(fetchTokenOutputDTO::class, $result);
        $this->assertNull($result->issUserWebToken);
    }

    /**
     * Проверка что сервис возвращает правильную структуру данных
     * при извлечении из базы всех пользователей
     */
    #[Group(name: "getUser(s)")]
    public function test_get_all_users_service()
    {
        //сервис отработал правильно (извлечение всех полей)
        $fakeRepo = $this->mock(IssUserRepoInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('getAllUsersData')->once()->andReturn(
                [
                    [
                        'id' => 1234,
                        'user_iss_avatar_path' => 'path\erg\sg\sdgf',
                        'user_id' => 3456,
                        'role_id' => 345,
                        'user_role' => ['name' => 'admin', 'created_at' => '12-12-12 13:13',
                            'updated_at' => '12-12-12 13:13', 'deleted_at' => '12-12-12 13:13'],
                        'organization' => 'test',
                        'name' => 'test',
                        'second_name' => 'test',
                        'last_name' => 'test',
                        'email' => 'test@test.com',
                        'created_at' => '12-12-12 13:13',
                        'updated_at' => '15-08-23 15:47',
                        'deleted_at' => '12-12-12 13:13'
                    ],
                    [
                        [
                            'id' => null,
                            'user_iss_avatar_path' => null,
                            'user_id' => null,
                            'role_id' => null,
                            'user_role' => null,
                            'organization' => null,
                            'name' => null,
                            'second_name' => null,
                            'last_name' => null,
                            'email' => null,
                            'created_at' => null,
                            'updated_at' => null,
                            'deleted_at' => null
                        ]
                    ]
                ]
            );
        });

        $testedService = new GetAllUsers($fakeRepo);
        $result = $testedService(new GetAllUsersInputDTO());

        $this->assertInstanceOf(getAllUsersOutputDTO::class, $result, 'Wrong result type');
        $this->assertIsArray($result->users, 'Property "users" must be of type array');
        $this->assertCount(2, $result->users, 'Wrong users count');
        foreach ($result->users as $user) {
            $this->assertInstanceOf(
                allUsersSingleUserDTO::class,
                $user,
                'Every array item must be object of SingleUserDTO class'
            );
        }

        //сервис отработал правильно (извлечение отдельных полей)
        $fakeRepo = $this->mock(IssUserRepoInterface::class, function (MockInterface $mock) {
                    $mock->shouldReceive('getAllUsersData')
                        ->with(['returned_fields' => ['id', 'user_iss_avatar_path']])
                        ->once()->andReturn(
                            [
                                [
                                    'id' => 1234,
                                    'user_iss_avatar_path' => 'errwsgsseg',
                                    'user_role' => null
                                ]
                            ]
                        );
        });

        $testedService = new GetAllUsers($fakeRepo);
        $result = $testedService(new GetAllUsersInputDTO(returnedFields: ['id', 'user_iss_avatar_path']));

        $this->assertInstanceOf(getAllUsersOutputDTO::class, $result);
        $this->assertIsArray($result->users);
        foreach ($result->users as $user) {
            foreach (get_object_vars($user) as $key => $value)  {
                if ($key == 'id' || $key == 'user_iss_avatar_path')  {
                    $this->assertNotNull($value, 'Error: property marked in input fields must not be null');
                }
            }
        }

        //в сервисе возникли ошибки
        $fakeRepo = $this->createMock(IssUserRepoInterface::class);
        $fakeRepo->method('getAllUsersData')->will($this->throwException(new \Exception()));

        $testedService = new GetAllUsers($fakeRepo);
        $result = $testedService(new GetAllUsersInputDTO());

        $this->assertInstanceOf(getAllUsersOutputDTO::class, $result);
        $this->assertIsArray($result->users);
        $this->assertEmpty($result->users);
    }


    /**
     * Проверка что сервис возвращает правильную структуру данных
     * при извлечении из базы одного пользоватя
     */
    #[Group(name: "getUser(s)")]
    public function test_get_user_data_service()
    {
        $fakeRepo = $this->mock(IssUserRepoInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('getUserData')
                ->with(['field_name' => 'id', 'field_value' => 0, 'returned_fields' => ['*']])
                ->once()->andReturn(
                    [
                        'id' => 1234,
                        'user_id' => 3456,
                        'role_id' => 345,
                        'user_role' => ['name' => 'admin', 'created_at' => '12-12-12 13:13'],
                        'user_iss_avatar_path' => 'path\erg\sg\sdgf',
                        'organization' => 'test',
                        'name' => 'test',
                        'second_name' => 'test',
                        'last_name' => 'test',
                        'web_token' => null,
                        'created_at' => '12-12-12 13:13',
                        'updated_at' => '12-12-12 13:13',
                        'deleted_at' => '12-12-12 13:13',
                    ]
                );
        });

        $testedService = new getUserData($fakeRepo);
        $result = $testedService(new getOneUserInputDTO(fieldName: 'id', fieldValue: 0));

        $this->assertInstanceOf(getOneUserOutputDTO::class, $result);

        //сервис отработал правильно но ничего не нашел
       $fakeRepo = $this->mock(IssUserRepoInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('getUserData')->once()->andReturn([]);
        });

        $testedService = new getUserData($fakeRepo);
        $result = $testedService(new getOneUserInputDTO(fieldName: 'id', fieldValue: 0));

        $this->assertNull($result, 'If no data found must return null');

        //в сервисе возникли ошибки
        $fakeRepo = $this->createMock(IssUserRepoInterface::class);
        $fakeRepo->method('getUserData')->will($this->throwException(new \Exception()));

        $testedService = new getUserData($fakeRepo);
        $result = $testedService(new getOneUserInputDTO(fieldName: 'id', fieldValue: 0));

        $this->assertNull($result, 'If any errors or exceptions must return null');

    }

    /**
     * Проверка что сервис возвращает правильную структуру данных
     * при извлечении из базы пользователей одной фирмы (для менеждера фирмы)
     */
    #[Group(name: "getUser(s)")]
    public function test_get_users_related_to_manager_service()
    {
        //сервис отработал правильно (извлечение всех полей)
        $fakeRepo = $this->mock(IssUserRepoInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('getManyUsersData')->once()->andReturn(
                [
                    [
                        'id' => 1234,
                        'user_iss_avatar_path' => 'path\erg\sg\sdgf',
                        'user_id' => 3456,
                        'role_id' => 345,
                        'user_role' => ['name' => 'admin', 'created_at' => '12-12-12 13:13',
                            'updated_at' => '12-12-12 13:13', 'deleted_at' => '12-12-12 13:13'],
                        'organization' => 'test',
                        'name' => 'test',
                        'second_name' => 'test',
                        'last_name' => 'test',
                        'created_at' => '12-12-12 13:13',
                        'updated_at' => '15-08-23 15:47',
                        'deleted_at' => '12-12-12 13:13'
                    ],
                    [
                        [
                            'id' => 7,
                            'user_iss_avatar_path' => null,
                            'user_id' => null,
                            'role_id' => null,
                            'user_role' => null,
                            'organization' => null,
                            'name' => null,
                            'second_name' => null,
                            'last_name' => null,
                            'created_at' => null,
                            'updated_at' => null,
                            'deleted_at' => null
                        ]
                    ]
                ]
            );
        });

        $testedService = new GetUsersRelatedToManager($fakeRepo);
        $result = $testedService(
            new getSomeUsersInputDTO(
            currentUser:  new IssUser(issUserRole: config('iss.ROLE_MANAGER'))
            )
        );

        $this->assertInstanceOf(getSomeUsersOutputDTO::class, $result);
        $this->assertIsArray($result->users, 'Property "users" must be of type array');
        $this->assertCount(2, $result->users, 'Wrong users count');
        foreach ($result->users as $user) {
            $this->assertInstanceOf(
                getSomeUsersSingleUserDTO::class,
                $user,
                'Every array item must be object of SingleUserDTO class'
            );
        }

        //сервис отработал правильно (текущий пользователь не менеджер)
        $fakeRepo = $this->mock(IssUserRepoInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('getManyUsersData')->never();
        });

        $testedService = new GetUsersRelatedToManager($fakeRepo);
        $result = $testedService(
            new getSomeUsersInputDTO(
                currentUser:  new IssUser(issUserRole: 'haker')
            )
        );

        $this->assertInstanceOf(getSomeUsersOutputDTO::class, $result);
        $this->assertIsArray($result->users, 'Property users must be of type array');
        $this->assertEmpty($result->users, 'If current user is not manager, array must be empty');

        //в сервисе возникли ошибки
        $fakeRepo = $this->createMock(IssUserRepoInterface::class);
        $fakeRepo->method('getManyUsersData')->will($this->throwException(new \Exception()));

        $testedService = new GetUsersRelatedToManager($fakeRepo);
        $result = $testedService(
            new getSomeUsersInputDTO(
                currentUser:  new IssUser(issUserRole: config('iss.ROLE_MANAGER'))
            )
        );

        $this->assertInstanceOf(getSomeUsersOutputDTO::class, $result);
        $this->assertIsArray($result->users, 'Property users must be of type array');
        $this->assertEmpty($result->users, 'If any errors, array must be empty');
    }

    /**
     * Проверка что сервис возвращает правильную структуру данных
     * при загрузке данных в ИОС из основного приложения
     */
    #[Group(name: "loadFromMainApp")]
    public function test_load_user_data_from_main_app_service()
    {
        //ошибка сервиса (обновляемый пользователь не найден в ИОС)
        $fakeRepo = $this->mock(IssUserRepoInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('getUserData')->once()->andReturn([]);
        });

        $testedService = new LoadUserDataFromMainApp($fakeRepo);
        $result = $testedService(
            new loadInputDTO(organization: $this->orgDTO, fio: $this->fioDTO, issUserId: 0, contact: $this->contactDTO)
        );

        $this->assertSame('iss user not found', $result->result);

        //ошибка сервиса (возникли ошибки при поиске обновляемого пользователя ИОС)
        $fakeRepo = $this->createMock(IssUserRepoInterface::class);
        $fakeRepo->method('getUserData')->will($this->throwException(new \Exception()));

        $testedService = new LoadUserDataFromMainApp($fakeRepo);
        $result = $testedService(
            new loadInputDTO(organization: $this->orgDTO, fio: $this->fioDTO, issUserId: 0, contact: $this->contactDTO)
        );

        $this->assertSame('iss user not found', $result->result);

        //ошибка сервиса (данные ФИО или организации или email не найдены в основном приложении или возникли ошибки при загрузке)
        $fakeRepo = $this->createMock(IssUserRepoInterface::class);
        $fakeRepo->method('getUserData')->willReturn(['user_id' => 132435]);
        $fakeRepo->method('getUserDataFromMainApp')->will($this->throwException(new \Exception()));

        $testedService = new LoadUserDataFromMainApp($fakeRepo);
        $result = $testedService(
            new loadInputDTO(organization: $this->orgDTO, fio: $this->fioDTO, issUserId: 0, contact: $this->contactDTO)
        );

        $this->assertSame('error loading user data from main application', $result->result);

        //ошибка сервиса (возникли ошибки при обновлении данных в ИОС)
        $fakeRepo = $this->createMock(IssUserRepoInterface::class);
        $fakeRepo->method('getUserData')->willReturn(['user_id' => 132435]);
        $map = [
            [$this->setOrganization, ['orgName' =>'o']],
            [$this->setFio, ['uName' =>'t', 'usName' => 'tt', 'ulName' => 'ttt']],
            [$this->setContact, ['my_email' => 'werty@mail.ru']],
        ];
        $fakeRepo->method('getUserDataFromMainApp')->willReturnMap($map);
        $fakeRepo->method('updateIssUserByMainAppData')->will($this->throwException(new \Exception()));

        $testedService = new LoadUserDataFromMainApp($fakeRepo);
        $result = $testedService(
            new loadInputDTO(organization: $this->orgDTO, fio: $this->fioDTO, issUserId: 0, contact: $this->contactDTO)
        );

        $this->assertSame('error updating iss user by main app data', $result->result);

        //сервис отработал правильно
        $fakeRepo = $this->createMock(IssUserRepoInterface::class);
        $fakeRepo->method('getUserData')->willReturn(['user_id' => 132435]);
        $map = [
            [
                $this->setOrganization,
                [$this->orgDTO->fieldOrganizationName =>'o']
            ],
            [
                $this->setFio,
                [$this->fioDTO->fieldName =>'t', $this->fioDTO->fieldSecondName => 'tt', $this->fioDTO->fieldLastName => 'ttt']
            ],
            [
                $this->setContact,
                [$this->contactDTO->fieldEmail => 'werty@mail.ru']
            ]
        ];
        $fakeRepo->method('getUserDataFromMainApp')->willReturnMap($map);
        $fakeRepo->method('updateIssUserByMainAppData')->willReturn(true);

        $testedService = new LoadUserDataFromMainApp($fakeRepo);
        $result = $testedService(
            new loadInputDTO(organization: $this->orgDTO, fio: $this->fioDTO, issUserId: 0, contact: $this->contactDTO)
        );

        $this->assertSame('ok', $result->result);
    }

    /**
     * Проверка что сервис возвращает правильную структуру данных
     * при удалении пользователя ИОС
     */
    #[Group(name: "operationOnIssUser")]
    public function test_delete_iss_user_service()
    {
        //ошибка сервиса не удалось удалить маршруты пользователя
        $fakeRepo= $this->createMock(IssUserRepoInterface::class);
        $fakeRepo->method('deleteEducationRoutesOfIssUser')
            ->will($this->throwException(new \Exception()));
        $fakeRepo->method('deleteIssUser')
            ->willReturn([1]);

        $testedService = new DeleteIssUser($fakeRepo);
        $result = $testedService(
            new deleteIssUserInputDTO(issUserId: 345677)
        );

        $this->assertSame(false, $result->result, 'If routes del make error must be false');

        //ошибка сервиса не удалось удалить пользователя
        $fakeRepo= $this->createMock(IssUserRepoInterface::class);
        $fakeRepo->method('deleteIssUser')
            ->will($this->throwException(new \Exception()));
        $fakeRepo->method('deleteEducationRoutesOfIssUser')
            ->willReturn([2]);

        $testedService = new DeleteIssUser($fakeRepo);
        $result = $testedService(
            new deleteIssUserInputDTO(issUserId: 345677)
        );

        $this->assertSame(false, $result->result, 'If user del make error must be false');

        //сервис отработал правильно, маршрутов у пользователя не было
        $fakeRepo = $this->mock(IssUserRepoInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('deleteEducationRoutesOfIssUser')->once()->andReturn([0]);
            $mock->shouldReceive('deleteIssUser')->once()->andReturn([1]);
        });

        $testedService = new DeleteIssUser($fakeRepo);
        $result = $testedService(
            new deleteIssUserInputDTO(issUserId: 345677)
        );

        $this->assertSame(true, $result->result, 'If user del make error must be false');

        //сервис отработал правильно, удалены маршруты пользователя
        $fakeRepo = $this->mock(IssUserRepoInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('deleteEducationRoutesOfIssUser')->once()->andReturn([5]);
            $mock->shouldReceive('deleteIssUser')->once()->andReturn([1]);
        });

        $testedService = new DeleteIssUser($fakeRepo);
        $result = $testedService(
            new deleteIssUserInputDTO(issUserId: 345677)
        );

        $this->assertSame(true, $result->result, 'If user del make error must be false');

        //сервис отработал правильно, но ни маршрутов ни пользователя не нашел
        $fakeRepo = $this->mock(IssUserRepoInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('deleteEducationRoutesOfIssUser')->once()->andReturn([0]);
            $mock->shouldReceive('deleteIssUser')->once()->andReturn([0]);
        });

        $testedService = new DeleteIssUser($fakeRepo);
        $result = $testedService(
            new deleteIssUserInputDTO(issUserId: 345677)
        );

        $this->assertSame(false, $result->result, 'If user del make error must be false');
    }
}
