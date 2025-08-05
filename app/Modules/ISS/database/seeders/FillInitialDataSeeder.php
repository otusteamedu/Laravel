<?php

namespace App\Modules\ISS\database\seeders;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Sequence;

use App\Modules\ISS\src\Models\UserRole;
use App\Modules\ISS\src\Models\UserData;
use App\Modules\ISS\src\Models\EducationRoute;
use App\Modules\ISS\src\Models\EducationRoutePoint;
use App\Modules\ISS\src\Models\ExamQuestion;
use App\Modules\ISS\src\Models\ExamAnswer;
use App\Modules\ISS\src\Models\EducationMaterial;
use App\Modules\ISS\src\Models\RealEducationRoutePoint;
use App\Modules\ISS\src\Models\RealEducationRoutesOfUser;
use App\Modules\ISS\src\Models\Teacher;


class FillInitialDataSeeder  extends Seeder
{
    public function run(): void
    {
        //создаем пользователей для 2 организаций
        $dataset = [
            ['id' => 1, 'userId' => 1, 'roleId' => 1, 'avatar' => 'defaultUserAvatar.png', 'org' => 'org1', 'roleName' => 'employee'],
            ['id' => 2, 'userId' => 2, 'roleId' => 1, 'avatar' => 'defaultUserAvatar.png', 'org' => 'org1', 'roleName' => 'employee'],
            ['id' => 3, 'userId' => 3, 'roleId' => 2, 'avatar' => 'TestUserAvatar.jpg', 'org' => 'org1', 'roleName' => 'manager'],
            ['id' => 4, 'userId' => 4, 'roleId' => 3, 'avatar' => 'TestUserAvatar.jpg', 'org' => 'org1', 'roleName' => 'admin'],

            ['id' => 5, 'userId' => 5, 'roleId' => 1, 'avatar' => 'defaultUserAvatar.png', 'org' => 'org2', 'roleName' => 'employee'],
            ['id' => 6, 'userId' => 6, 'roleId' => 2, 'avatar' => 'TestUserAvatar.jpg', 'org' => 'org2', 'roleName' => 'manager'],
            ['id' => 7, 'userId' => 7, 'roleId' => 3, 'avatar' => 'TestUserAvatar.jpg', 'org' => 'org2', 'roleName' => 'admin'],
        ];
        foreach ($dataset as $data) {
            UserData::factory()->create(
                [
                    'user_id' => $data['userId'],
                    'role_id' => $data['roleId'],
                    'user_iss_avatar_path' => $data['avatar'],
                    'organization' => $data['org'],
                    'name' => $data['roleName'] . ' ' . $data['org'],
                    'second_name' => $data['roleName'] . ' ' . $data['org'],
                    'last_name' => $data['roleName'] . ' ' . $data['org'],
                    'email' => $data['roleName'] . '_' . $data['org'] . '_mail',
                ]
            );
        }

        //заполняем справочники маршрутов
        EducationRoute::factory()->create(['name' => 'r1']);
        EducationRoute::factory()->create(['name' => 'r2']);
        EducationRoute::factory()->create(['name' => 'r3']);
        EducationRoute::factory()->create(['name' => 'r4']); //последний будет без точек

        //заполняем справочник точек маршрутов
        EducationRoutePoint::factory(10)->create(); //последняя точка не привязана ни к одному маршруту

        //заполняем справочник обучающих материалов
        for($i = 1; $i < 6; $i++) {
            $type = $i; //fake()->numberBetween(1, 5);
            switch ($type) {
                case 1: $filePath = fake()->randomElement(['mp4-1.mp4', 'mp4-2.mp4']); break;
                case 2: $filePath = 'file not exists'; break;
                case 3: $filePath = fake()->randomElement(['t1.txt', 't2.txt']); break;
                case 4: $filePath = fake()->randomElement(['p1.pdf', 'p2.pdf']); break;
                case 5: $filePath = fake()->randomElement(['doc1.docx']); break;
                default: $filePath = 'file not exists 2'; break;
            }

            EducationMaterial::factory()->create(
                [
                    'point_id' => fake()->numberBetween(1,9),
                    'material_type_id' => $type,
                    'file_path' => $filePath
                ]
            );
        }

        //заполняем справочник экзаменационных вопросов (для каждой точки маршрута 1 простой и 1 сложный вопрос)
        foreach (EducationRoutePoint::all() as $point) {
            //простые
            $questionSimple = ExamQuestion::factory()->create(
                [
                    'short_question_name' => 'simple',
                    'question' => fake()->sentence(),
                    'point_id' => $point->id,
                ]
            );
            ExamAnswer::factory(3)->create(['question_id' => $questionSimple->id]);

            //сложные
            ExamQuestion::factory()->create(
                [
                    'short_question_name' => 'complicated',
                    'question' => fake()->sentence(),
                    'point_id' => $point->id,
                ]
            );
        }

        //создаем точки реальных маршрутов
        $route1 = EducationRoute::where('id', 1)->first();
        $point11 = RealEducationRoutePoint::factory()->create(
            ['route_id' => $route1->id, 'route_point_id' => 1, 'position' => 1,
                'exam_date' => Carbon::parse('01-02-1995')->format('d-m-Y')]
        );
        $point12 = RealEducationRoutePoint::factory()->create(
            ['route_id' => $route1->id, 'route_point_id' => 2, 'position' => 2,
                'exam_date' => Carbon::parse('12-08-2025')->format('d-m-Y')]
        );
        $point13 = RealEducationRoutePoint::factory()->create(
            ['route_id' => $route1->id, 'route_point_id' => 3, 'position' => 3,
                'exam_date' => Carbon::parse('27-09-2025')->format('d-m-Y')]
        );
        $point14 = RealEducationRoutePoint::factory()->create(
            ['route_id' => $route1->id, 'route_point_id' => 4, 'position' => 4,
                'exam_date' => Carbon::parse('27-09-2028')->format('d-m-Y')]
        );

        $route2 = EducationRoute::where('id', 2)->first();
        $point21 = RealEducationRoutePoint::factory()->create(
            ['route_id' => $route2->id, 'route_point_id' => 5, 'position' => 1,
                'exam_date' => Carbon::parse('01-02-2000')->format('d-m-Y')]
        );
        $point22 = RealEducationRoutePoint::factory()->create(
            ['route_id' => $route2->id, 'route_point_id' => 6, 'position' => 2,
                'exam_date' => Carbon::parse('02-11-2025')->format('d-m-Y')]
        );
        $point23 = RealEducationRoutePoint::factory()->create(
            ['route_id' => $route2->id, 'route_point_id' => 7, 'position' => 4,
                'exam_date' => Carbon::parse('30-10-2025')->format('d-m-Y')]
        );

        $route3 = EducationRoute::where('id', 3)->first();
        $point31 = RealEducationRoutePoint::factory()->create(
            ['route_id' => $route3->id, 'route_point_id' => 8, 'position' => 8,
                'exam_date' => Carbon::parse('01-01-2026')->format('d-m-Y')]
        );
        $point32 = RealEducationRoutePoint::factory()->create(
            ['route_id' => $route3->id, 'route_point_id' => 9, 'position' => 30,
                'exam_date' => Carbon::parse('03-01-2026')->format('d-m-Y')]
        );

        $route4 = EducationRoute::where('id', 4)->first();

        //создаем реальные маршруты пользователей
        //фирма 1
        $org1empUser1 = UserData::where('id', 1)->first();
        RealEducationRoutesOfUser::factory()
            ->create(['user_data_id' => $org1empUser1->id, 'route_id' => $route1->id, 'last_pass_point_id' => $point14]);
        RealEducationRoutesOfUser::factory()
            ->create(['user_data_id' => $org1empUser1->id, 'route_id' => $route3->id, 'last_pass_point_id' => null]);

        $org1empUser2 = UserData::where('id', 2)->first();
        RealEducationRoutesOfUser::factory()
            ->create(['user_data_id' => $org1empUser2->id, 'route_id' => $route1->id, 'last_pass_point_id' => $point12]);
        RealEducationRoutesOfUser::factory()
            ->create(['user_data_id' => $org1empUser2->id, 'route_id' => $route3->id, 'last_pass_point_id' => $point31]);

        $org1mngUser = UserData::where('id', 3)->first();
        RealEducationRoutesOfUser::factory()
            ->create(['user_data_id' => $org1mngUser->id, 'route_id' => $route2->id, 'last_pass_point_id' => $point22]);

        $org1adminUser = UserData::where('id', 4)->first();
        RealEducationRoutesOfUser::factory()
            ->create(['user_data_id' => $org1adminUser->id, 'route_id' => $route4->id, 'last_pass_point_id' => null]);

        //фирма 2
        $org2empUser1 = UserData::where('id', 5)->first();
        RealEducationRoutesOfUser::factory()
            ->create(['user_data_id' => $org2empUser1->id, 'route_id' => $route1->id, 'last_pass_point_id' => $point14]);
        RealEducationRoutesOfUser::factory()
            ->create(['user_data_id' => $org2empUser1->id, 'route_id' => $route3->id, 'last_pass_point_id' => null]);

        $org2mngUser = UserData::where('id', 6)->first();
        RealEducationRoutesOfUser::factory()
            ->create(['user_data_id' => $org2mngUser->id, 'route_id' => $route2->id, 'last_pass_point_id' => $point22]);

        $org2adminUser = UserData::where('id', 7)->first();
        RealEducationRoutesOfUser::factory()
            ->create(['user_data_id' => $org2adminUser->id, 'route_id' => $route4->id, 'last_pass_point_id' => null]);

        //создаем преподавателей
        $teacher1 = Teacher::factory()->create(
            ['connected_organization' => 'org1', 'teacher_email' => 'alekseev.a@v2grp.ru']
        );
        $teacher2 = Teacher::factory()->create(
            ['connected_organization' => 'org2', 'teacher_email' => 'alekseev.a@v2grp.ru']
        );
    }
}
