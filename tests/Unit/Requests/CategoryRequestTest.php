<?php
namespace Tests\Unit\Requests;

use Tests\TestCase;
use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Support\Facades\Validator;

class CategoryRequestTest extends TestCase
{
    public function test_validation_create_category_with_valid_data()
    {
        $request = new CreateCategoryRequest();

        $data = [
            'name' => 'Рабочие задачи',
            'color' => '#ff0000',
            'description' => 'Описание категории'
        ];

        $validator = Validator::make($data, $request->rules());

        $this->assertFalse($validator->fails());
    }

    public function test_validation_without_name()
    {
        $request = new CreateCategoryRequest();

        $data = [
            'name' => '', // пустое название
            'color' => '#ff0000',
            'description' => 'Описание'
        ];

        $validator = Validator::make($data, $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('name'));
    }

    public function test_validation_with_invalid_color()
    {
        $request = new CreateCategoryRequest();

        $data = [
            'name' => 'Категория',
            'color' => 'красный', // неправильный формат
            'description' => 'Описание'
        ];

        $validator = Validator::make($data, $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('color'));
    }

    public function test_validation_with_empty_description()
    {
        $request = new CreateCategoryRequest();

        $data = [
            'name' => 'Категория',
            'color' => '#ff0000',
            'description' => null // пустое описание
        ];

        $validator = Validator::make($data, $request->rules());

        $this->assertFalse($validator->fails());
    }
}
