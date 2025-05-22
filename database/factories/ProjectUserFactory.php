<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\ProjectRoleEnum;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProjectUser>
 */
class ProjectUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $projects = Project::all();
        $users = User::all();
        $project_user = ProjectUser::select(DB::raw("CONCAT(project_id, '-', user_id) as user_project"))->pluck('user_project')->toArray();

        $project_users = [];
        foreach ($projects as $project) {
            foreach ($users as $user) {
                $tmp = $project->id . "-" . $user->id;
                if (!in_array($tmp, $project_user)) {
                    array_push($project_users, $tmp);
                }
            }
        }

        if (!empty($project_users)) {
            $project_and_user = fake()->randomElement($project_users);
            $project_and_user = explode('-', $project_and_user);

            $project_id = $project_and_user[0];
            $user_id    = $project_and_user[1];
        } else {
            $project_id = Project::factory()->create()->id;
            $user_id    = User::factory()->create()->id;
        }

        return [
            'project_id' => $project_id,
            'user_id'    => $user_id,
            'roles'      => [fake()->randomElement([ProjectRoleEnum::ADMIN, ProjectRoleEnum::MEMBER])],
            'invited_at' => now(),
            'joined_at'  => now(),
            'left_at'    => fake()->randomElement([null, now()]),
        ];
    }
}
