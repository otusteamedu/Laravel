<?php

namespace Tests\Feature\Area;

use App\Application\Services\Area\AreaDTO;
use App\Interfaces\Response\WebResponse;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

#[Group('feature_area_controller')]

class AreaControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected string $indexUrl;
    protected string $createUrl;
    protected string $storeUrl;
    protected string $editUrlBase;
    protected string $updateUrlBase;
    protected string $destroyUrlBase;

    public function setUp(): void
    {
        $this->setUpTheTestEnvironment();

        $this->indexUrl = route('area.index');
        $this->createUrl = route('area.create');
        $this->storeUrl = route('area.store');
        $this->editUrlBase = 'area.edit';
        $this->updateUrlBase = 'area.update';
        $this->destroyUrlBase = 'area.destroy';
    }

    #[Test()]
    #[TestWith(['area.index.index', 200])]
    public function index_returns_successful_response(
        string $expectedView,
        int $expectedStatus,
    ): void {
        $response = $this->get($this->indexUrl);

        $response->assertStatus($expectedStatus);
        $response->assertViewIs($expectedView);

        $response->assertViewHas('response', function ($resp) {
            return $resp instanceof WebResponse
                && $resp->success === true
                && !empty($resp->data)
                && collect($resp->data)->first() instanceof AreaDTO;
        });
    }

    #[Test()]
    #[TestWith(['area.create.create', 200])]
    public function create_returns_successful_response(
        string $expectedView,
        int $expectedStatus,
    ): void {
        $response = $this->get($this->createUrl);

        $response->assertStatus($expectedStatus);
        $response->assertViewIs($expectedView);

        $response->assertViewHas('response', function ($resp) {
            return $resp instanceof WebResponse
                && $resp->success === true
                && empty($resp->data);
        });
    }

    #[Test]
    #[TestWith(['Новая зона', 201, true, 'Запись добавлена'])]
    #[TestWith([null, 422, false, 'Поле "Название территории" обязательно для заполнения.'])]
    public function store_returns_response(
        ?string $name,
        int $expectedStatus,
        bool $expectedSuccess,
        string $expectedMessage
    ): void {
        $this->withHeaders([
            'Accept-Language' => 'ru',
        ]);
        $response = $this->postJson($this->storeUrl, [
            'name-area' => $name,
        ]);
        $response->assertStatus($expectedStatus);
        $response->assertJson([
            'success' => $expectedSuccess,
            'message' => $expectedMessage,
            'status_code' => $expectedStatus,
        ]);
        $response->assertJsonStructure([
            'success',
            'data',
            'message',
            'errors',
            'status_code',
        ]);
    }

    #[Test]
    #[TestWith(['area.edit.edit', 200, true, 1, 'Успешно'])]
    #[TestWith(['area.edit.edit', 404, false, 9999, 'Запись не найдена для редактирования'])]
    public function edit_returns_expected_response(
        string $expectedView,
        int $expectedStatus,
        bool $expectedSuccess,
        int $id,
        string $expectedMessage,
    ): void {
        $response = $this->get(route($this->editUrlBase, $id));
        $response->assertStatus($expectedStatus);
        $response->assertViewIs($expectedView);
        if ($expectedStatus === 200) {
            $response->assertViewHas('response', function ($resp) use (
                $expectedSuccess,
                $expectedStatus,
                $expectedMessage,
                ) {
                return $resp instanceof WebResponse
                    && $resp->success === $expectedSuccess
                    && $resp->statusCode === $expectedStatus
                    && $resp->message === $expectedMessage
                    && $resp->data instanceof AreaDTO;
            });
        } elseif ($expectedStatus === 400) {
            $response->assertViewHas('response', function ($resp) use (
                $expectedSuccess,
                $expectedStatus,
                $expectedMessage,
                ) {
                return $resp instanceof WebResponse
                    && $resp->success === $expectedSuccess
                    && $resp->statusCode === $expectedStatus
                    && $resp->message === $expectedMessage;
            });
        }
    }

    #[Test]
    #[TestWith(['Обновлённая зона', 201, true, 'Запись успешно сохранена', 1])]
    #[TestWith([null, 422, false, 'Поле "Название территории" обязательно для заполнения.', 1])]
    #[TestWith(['Любое имя', 404, false, 'Запись не найдена для редактирования', 999999])]
    public function update_returns_expected_response(
        ?string $name,
        int $expectedStatus,
        bool $expectedSuccess,
        string $expectedMessage,
        int $id,
    ): void {
        $response = $this->putJson(route($this->updateUrlBase, $id), [
            'name-area' => $name,
        ]);
        $response->assertStatus($expectedStatus);
        $response->assertJson([
            'success' => $expectedSuccess,
            'message' => $expectedMessage,
            'status_code' => $expectedStatus,
        ]);
        $response->assertJsonStructure([
            'success',
            'data',
            'message',
            'errors',
            'status_code',
        ]);
    }

    #[Test]
    #[TestWith([201, true, 'Запись успешно удалена', 1])]
    #[TestWith([404, false, 'Запись не найдена для удаления', 999999])]
    public function destroy_returns_expected_response(
        int $expectedStatus,
        bool $expectedSuccess,
        string $expectedMessage,
        int $id,
    ): void {
        $response = $this->deleteJson(route($this->destroyUrlBase, $id));
        $response->assertStatus($expectedStatus);
        $response->assertJson([
            'success' => $expectedSuccess,
            'message' => $expectedMessage,
            'status_code' => $expectedStatus,
        ]);
        $response->assertJsonStructure([
            'success',
            'data',
            'message',
            'errors',
            'status_code',
        ]);
    }
}
