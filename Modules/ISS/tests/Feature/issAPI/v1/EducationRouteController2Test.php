<?php

namespace App\Modules\ISS\tests\Feature\issAPI\v1;

//use PHPUnit\Framework\TestCase;
use App\Modules\ISS\tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\HttpFoundation\Response;
use Mockery\MockInterface;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Illuminate\Auth\AuthManager;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\DataProvider;
use App\Modules\ISS\src\Models\EducationRoute;
use App\Modules\ISS\src\Models\RealEducationRoutesOfUser;
use App\Modules\ISS\src\Models\UserData;
use App\Models\User;

class EducationRouteController2Test extends TestCase
{
    use DatabaseTruncation;

    private string $token;

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('passport:client --env=testing --silent');
        Artisan::call('passport:client --personal --silent');

        $dataToRegister = [
            'email' => 'testingOauth@mail.com',
            'password' => 'testingOauth@mail.com',
            'name' => 'testingOauth',
            'second_name' => 'testingOauth',
            'last_name' => 'testingOauth',
            'organization' => 'testingOauth',
            'user_role' => 'emp'
        ];
        $response = $this->postJson('http://localhost/api/oauth/register', $dataToRegister);
        $this->token = $response->json()['token'];

    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->token);
    }

    /**
     * Проверка что index выдает данные если пользователь авторизован в Passport апи
     */
    #[Group(name: "oauth_ref_route_index")]
    public function test_index_success()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('http://localhost/iss/api/v1/issEducationRoute');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(['data' => []]);
    }

    /**
     * Проверка что index выдает ошибку если пользователь не авторизован в Passport апи
     */
    #[Group(name: "oauth_ref_route_index")]
    public function test_index_unauthorized()
    {
        $response = $this->getJson('http://localhost/iss/api/v1/issEducationRoute');

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJson(['message' => 'Unauthenticated.']);
    }



    /**
     * Проверка что store создает справочный маршрут если пользователь авторизован в Passport апи
     * и задал параметры маршрута правильно
     */
    #[Group(name: "oauth_ref_route_store")]
    #[TestWith(['testRefRoute1'])]
    #[TestWith([1234])]
    public function test_store_success($routeName)
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('http://localhost/iss/api/v1/issEducationRoute', ['name' => $routeName]);

        $response->assertStatus(Response::HTTP_CREATED);
    }

    /**
     * Проверка что store выдает ошибку если пользователь авторизован в Passport апи
     * но задал параметры маршрута не правильно
     */
    #[Group(name: "oauth_ref_route_store")]
    #[TestWith([null])]
    public function test_store_param_error($routeName)
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('http://localhost/iss/api/v1/issEducationRoute', ['name' => $routeName]);

        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Проверка что store выдает ошибку если пользователь не авторизован в Passport апи
     */
    #[Group(name: "oauth_ref_route_store")]
    #[TestWith(['test unauthorized'])]
    public function test_store_unauthorized($routeName)
    {
        $response = $this->postJson('http://localhost/iss/api/v1/issEducationRoute', ['name' => $routeName]);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }



    public static function refRoutesToShow(): array
    {
        return [['refR1'], ['refR2']];
    }
    /**
     * Проверка что показывает выбранную запись справочного маршрута
     * если пользователь авторизован в Passport api
     */
    #[Group(name: "oauth_ref_route_show")]
    #[DataProvider('refRoutesToShow')]
    public function test_show_success()//$routeName)
    {
        //создаем справочный маршрут
        $responseCreated = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('http://localhost/iss/api/v1/issEducationRoute', ['name' => 'trt']);

        //показываем его
        $responseShow =  $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('http://localhost/iss/api/v1/issEducationRoute/' . $responseCreated['id']);

        $responseShow->assertJson([
            'data' => [
                'routeMarker' => 'iss',
                'id' => $responseCreated->json()['id'],
                'name' => $responseCreated->json()['name'],
                'createDate' => $responseCreated->json()['created_at'],
                'updateDate' => $responseCreated->json()['updated_at'],
                'deleteDate' => null
            ],
            'meta' => ['ISS module api v1']
        ]);
    }

    /**
     * Проверка что не показывает выбранную запись справочного маршрута
     * если пользователь не авторизован в Passport api
     */
    #[Group(name: "oauth_ref_route_show")]
    public function test_show_unauthorized()
    {
        //создаем справочный маршрут
        $educationRouteToShow = EducationRoute::factory()->create();

        //показываем его
        $response =  $this->withHeader('Authorization', '')
            ->getJson('http://localhost/iss/api/v1/issEducationRoute/' . $educationRouteToShow->id);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Проверка что выдает правильный ответ если запись справочного маршрута не найдена
     * если пользователь авторизован в Passport api
     */
    #[Group(name: "oauth_ref_route_show")]
    public function test_show_not_found()
    {
        //создаем справочный маршрут
        $educationRouteToShow = EducationRoute::factory()->create();
        $id = $educationRouteToShow->id + 12345678;

        //показываем не существующий маршрут
        $response =  $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('http://localhost/iss/api/v1/issEducationRoute/' . $id);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([]);
    }



    /**
     * Проверка что выдает правильный ответ если запись справочного маршрута успешно обновлена
     * и пользователь авторизован в Passport api
     */
    #[Group(name: "oauth_ref_route_update")]
    public function test_update_success()
    {
        //создаем пользователя и ставим его авторизованым
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        //создаем тестовый маршрут
        $route = EducationRoute::factory()->create();

        //обновляем существующий маршрут
        $response =  $this->putJson('http://localhost/iss/api/v1/issEducationRoute/' . $route->id, ['name' => 'trt']);

        $response->assertStatus(Response::HTTP_OK);
        $this->assertSame('trt', $route->refresh()->name);
    }

    /**
     * Проверка что выдает правильный ответ если запись справочного маршрута не была найдена
     * и пользователь авторизован в Passport api
     */
    #[Group(name: "oauth_ref_route_update")]
    public function test_update_not_found()
    {
        //создаем пользователя и ставим его авторизованым
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        //обновляем не существующий маршрут
        $response =  $this->putJson('http://localhost/iss/api/v1/issEducationRoute/1');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(['error' => 'Call to a member function update() on null', '0' => 500]);
    }

    /**
     * Проверка что выдает правильный ответ если при обновлении записи справочного маршрута возникла ошибка
     * и пользователь авторизован в Passport api
     */
    #[Group(name: "oauth_ref_route_update")]
    public function test_update_error()
    {
        //создаем пользователя и ставим его авторизованым
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        //создаем тестовый маршрут
        $route = EducationRoute::factory()->create();

        //обновляем существующий маршрут
        $response =  $this->putJson('http://localhost/iss/api/v1/issEducationRoute/1', ['name' => null]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(['error' , '0']);
    }

    /**
     * Проверка что выдает ошибку 401 если при обновлении записи справочного маршрута
     * пользователь не авторизован в Passport api
     */
    #[Group(name: "oauth_ref_route_update")]
    public function test_update_unauthorized()
    {
        //создаем тестовый маршрут
        $route = EducationRoute::factory()->create();

        //обновляем существующий маршрут
        $response =  $this->putJson('http://localhost/iss/api/v1/issEducationRoute/1', ['name' => null]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }



    /**
     * Проверка что справочный маршрут успешно удален
     * если пользователь авторизован в Passport API
     */
    #[Group(name: "oauth_ref_route_delete")]
    public function test_delete_success()
    {
        //создаем пользователя и ставим его авторизованым
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        //создаем тестовый маршрут
        $route = EducationRoute::factory()->create();

        $response =  $this->deleteJson('http://localhost/iss/api/v1/issEducationRoute/' . $route->id);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseCount('education_routes', 0);
    }

    /**
     * Проверка что выдает правильный ответ при попытке удалить справочный маршрут
     * если пользователь не авторизован в Passport API
     */
    #[Group(name: "oauth_ref_route_delete")]
    public function test_delete_unauthorized()
    {
        //создаем тестовый маршрут
        $route = EducationRoute::factory()->create();

        $response =  $this->deleteJson('http://localhost/iss/api/v1/issEducationRoute/' . $route->id);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->assertDatabaseCount('education_routes', 1);
    }

    /**
     * Проверка что возвращается правильный ответ если справочный маршрут не найден
     * если пользователь авторизован в Passport API
     */
    #[Group(name: "oauth_ref_route_delete")]
    public function test_delete_not_found()
    {
        //создаем пользователя и ставим его авторизованым
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        //создаем тестовый маршрут
        $route = EducationRoute::factory()->create();
        $id = $route->id + 12345678;

        $response =  $this->deleteJson('http://localhost/iss/api/v1/issEducationRoute/' . $id);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(['message' , '0']);
        $this->assertDatabaseCount('education_routes', 1);
    }

    /**
     * Проверка что возвращается правильный ответ если справочный маршрут не удалось удалить (на него есть ссылки внешних ключей)
     * если пользователь авторизован в Passport API
     */
    #[Group(name: "oauth_ref_route_delete")]
    public function test_delete_error()
    {
        //создаем пользователя и ставим его авторизованым
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        //создаем пользователя ИОС
        $issUser = UserData::factory()->create();

        //создаем тестовый маршрут
        $route = EducationRoute::factory()->create();
        $id = $route->id;

        //Создаем реальный маршрут пользователя и привязываем его к справочному маршруту
        $realUserRoute = RealEducationRoutesOfUser::create(['route_id' => $id, 'user_data_id' => $issUser->id]);

        $response =  $this->deleteJson('http://localhost/iss/api/v1/issEducationRoute/' . $id);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(['error' , '0']);
        $this->assertDatabaseCount('education_routes', 1);
    }
}
