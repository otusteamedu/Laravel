<?php

namespace App\Modules\ISS\tests\Unit\issOrmModels;

//use PHPUnit\Framework\TestCase;
use App\Modules\ISS\tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use App\Modules\ISS\src\Models\EducationMaterialType;
use App\Modules\ISS\src\Models\EducationMaterial;
use App\Modules\ISS\src\Models\EducationRoute;
use App\Modules\ISS\src\Models\EducationRoutePoint;
use App\Modules\ISS\src\Models\ExamAnswer;
use App\Modules\ISS\src\Models\ExamQuestion;
use App\Modules\ISS\src\Models\ExamQuestionType;
use App\Modules\ISS\src\Models\RealEducationRoutePoint;
use App\Modules\ISS\src\Models\RealEducationRoutesOfUser;
use App\Modules\ISS\src\Models\UserData;
use App\Modules\ISS\src\Models\UserRole;

class IssOrmModelTest extends TestCase
{
    use DatabaseTruncation;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }


    /**
     * Проверка что все ОРМ модели создаются
     */
    public function test_check_orm_model_creation()
    {
        $educationMaterialType = EducationMaterialType::factory()->create();
        $this->assertModelExists($educationMaterialType, 'Model EducationMaterialType not created!');

        $educationRoutePoint = EducationRoutePoint::factory()->create();
        $this->assertModelExists($educationRoutePoint, 'Model EducationRoutePoint not created!');

        $educationMaterial = EducationMaterial::factory()->create();
        $this->assertModelExists($educationMaterial, 'Model EducationMaterial not created!');

        $examQuestionType = ExamQuestionType::factory()->create();
        $this->assertModelExists($examQuestionType, 'Model ExamQuestionType not created!');

        $examQuestion = ExamQuestion::factory()->create();
        $this->assertModelExists($examQuestion, 'Model ExamQuestion not created!');

        $examAnswer = ExamAnswer::factory()->create();
        $this->assertModelExists($examAnswer, 'Model ExamAnswer not created!');

        $educationRoute = EducationRoute::factory()->create();
        $this->assertModelExists($educationRoute, 'Model EducationRoute not created!');

        $userRole = UserRole::factory()->create();
        $this->assertModelExists($userRole, 'Model UserRole not created!');

        $userData = UserData::factory()->create();
        $this->assertModelExists($userData, 'Model UserData not created!');

        $realEducationRoutePoint = RealEducationRoutePoint::factory()->create();
        $this->assertModelExists($realEducationRoutePoint, 'Model RealEducationRoutePoint not created!');

        $realEducationRoutesOfUser = RealEducationRoutesOfUser::factory()->create();
        $this->assertModelExists($realEducationRoutesOfUser, 'Model RealEducationRoutesOfUser not created!');
    }

    /**
     * Проверка что все связи ОРМ моделей работают
     */
    public function test_check_orm_model_relations()
    {
        $educationMaterialType = EducationMaterialType::factory()->create();
        $educationRoutePoint = EducationRoutePoint::factory()->create();

        // MODEL EducationMaterial
        $educationMaterial = EducationMaterial::factory()->create(
            [
                'material_type_id' => $educationMaterialType->id,
                'point_id' => $educationRoutePoint->id,
            ]
        );

        // Связь EducationMaterial <-> EducationMaterialType
        $this->assertSame(
            $educationMaterialType->name,
            $educationMaterial->educationMaterialType()->first()->name,
            'Relation EducationMaterial -> EducationMaterialType not created!'
        );
        $this->assertEquals(
            1,
            $educationMaterialType->educationMaterial()->count(),
            'Relation EducationMaterialType <- EducationMaterial not created!'
        );

        // Связь EducationMaterial <-> EducationRoutePoint
        $this->assertSame(
            $educationRoutePoint->name,
            $educationMaterial->educationRoutePoint()->first()->name,
            'Relation EducationMaterial -> EducationRoutePoint not created!'
        );
        $this->assertEquals(
            1,
            $educationRoutePoint->educationMaterial()->count(),
            'Relation EducationMaterialType <- EducationRoutePoint not created!'
        );
        //____________________________________________________________________



        $examQuestionType = ExamQuestionType::factory()->create();

        // MODEL ExamQuestion
        $examQuestion = ExamQuestion::factory()->create(
            [
                'point_id' => $educationRoutePoint->id,
                'question_type_id' => $examQuestionType->id
            ]
        );

        // Связь ExamQuestion <-> EducationRoutePoint
        $this->assertSame(
            $educationRoutePoint->name,
            $examQuestion->educationRoutePoint()->first()->name,
            'Relation ExamQuestion -> EducationRoutePoint not created!'
        );
        $this->assertEquals(
            1,
            $educationRoutePoint->examQuestion()->count(),
            'Relation ExamQuestion <- EducationRoutePoint not created!'
        );

        // Связь ExamQuestion <-> ExamQuestionType
        $this->assertSame(
            $examQuestionType->name,
            $examQuestion->examQuestionType()->first()->name,
            'Relation ExamQuestion -> ExamQuestionType not created!'
        );
        $this->assertEquals(
            1,
            $examQuestionType->examQuestion()->count(),
            'Relation ExamQuestion <- ExamQuestionType not created!'
        );
        //____________________________________________________________________

        // MODEL ExamAnswer
        $examAnswer = ExamAnswer::factory()->create(
            [
                'question_id' => $examQuestion->id
            ]
        );

        // Связь ExamAnswer <-> ExamQuestion
        $this->assertSame(
            $examQuestion->short_question_name,
            $examAnswer->examQuestion()->first()->short_question_name,
            'Relation ExamAnswer -> ExamQuestion not created!'
        );
        $this->assertEquals(
            1,
            $examQuestion->examAnswer()->count(),
            'Relation ExamAnswer <- ExamQuestion not created!'
        );
        //____________________________________________________________________


        $educationRoute = EducationRoute::factory()->create();

        // MODEL RealEducationRoutePoint
        $realEducationRoutePoint = RealEducationRoutePoint::factory()->create(
            [
                'route_id' => $educationRoute->id,
                'route_point_id' => $educationRoutePoint->id
            ]
        );

        // Связь RealEducationRoutePoint <-> EducationRoute
        $this->assertSame(
            $educationRoute->name,
            $realEducationRoutePoint->educationRoute()->first()->name,
            'Relation RealEducationRoutePoint -> EducationRoute not created!'
        );
        $this->assertEquals(
            1,
            $educationRoute->realEducationRoutePoint()->count(),
            'Relation RealEducationRoutePoint <- EducationRoute not created!'
        );

        // Связь RealEducationRoutePoint <-> EducationRoutePoint
        $this->assertSame(
            $educationRoutePoint->name,
            $realEducationRoutePoint->educationRoutePoint()->first()->name,
            'Relation RealEducationRoutePoint -> EducationRoutePoint not created!'
        );
        $this->assertEquals(
            1,
            $educationRoutePoint->realEducationRoutePoint()->count(),
            'Relation RealEducationRoutePoint <- EducationRoutePoint not created!'
        );
        //____________________________________________________________________


        $userRole = UserRole::factory()->create();

        // MODEL UserData
        $userData = UserData::factory()->create(
            [
                'role_id' => $userRole->id
            ]
        );

        // Связь UserData <-> UserRole
        $this->assertSame(
            $userRole->name,
            $userData->userRole()->first()->name,
            'Relation UserData -> UserRole not created!'
        );
        $this->assertEquals(
            1,
            $userRole->userData()->count(),
            'Relation UserData <- UserRole not created!'
        );
        //____________________________________________________________________


        // MODEL RealEducationRoutesOfUser
        $realEducationRoutesOfUser = RealEducationRoutesOfUser::factory()->create(
            [
                'route_id' => $educationRoute->id,
                'last_pass_point_id' => $realEducationRoutePoint->id,
                'user_data_id' => $userData->id
            ]
        );

        // Связь RealEducationRoutesOfUser <-> UserData
        $this->assertSame(
            $userData->name,
            $realEducationRoutesOfUser->userData()->first()->name,
            'Relation RealEducationRoutesOfUser -> UserData not created!'
        );
        $this->assertEquals(
            1,
            $userData->realEducationRoutesOfUser()->count(),
            'Relation RealEducationRoutesOfUser <- UserData not created!'
        );

         // Связь RealEducationRoutesOfUser <-> EducationRoute
        $this->assertSame(
            $educationRoute->name,
            $realEducationRoutesOfUser->educationRoute()->first()->name,
            'Relation RealEducationRoutesOfUser -> EducationRoute not created!'
        );
        $this->assertEquals(
            1,
            $educationRoute->realEducationRoutesOfUser()->count(),
            'Relation RealEducationRoutesOfUser <- EducationRoute not created!'
        );

         // Связь RealEducationRoutesOfUser <-> RealEducationRoutePoint
        $this->assertSame(
            $realEducationRoutePoint->position,
            $realEducationRoutesOfUser->lastPassedPoint()->first()->position,
            'Relation RealEducationRoutesOfUser -> RealEducationRoutePoint not created!'
        );
        $this->assertEquals(
            1,
            $realEducationRoutePoint->realEducationRoutesOfUser()->count(),
            'Relation RealEducationRoutesOfUser <- RealEducationRoutePoint not created!'
        );
    }
}
