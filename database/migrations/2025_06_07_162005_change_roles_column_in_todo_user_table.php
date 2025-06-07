<?php

use App\Models\TodoRoleEnum;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('todo_user', function (Blueprint $table) {
            $table->string('role')->default('');
        });

        $users = DB::table('todo_user')->select(['id', 'roles'])->get();

        foreach ($users as $user) {
            $roles = json_decode($user->roles);

            if ($roles[0] === 'Постановщик') {
                $roles[0] = 'Наблюдатель';
            }
            DB::table('todo_user')->where('id', $user->id)->update(['role' => TodoRoleEnum::from($roles[0])]);
        }

        Schema::table('todo_user', function (Blueprint $table) {
            $table->dropColumn('roles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('todo_user', function (Blueprint $table) {
            $table->json('roles')->nullable();
        });

        $users = DB::table('todo_user')->select(['id', 'role'])->get();

        foreach ($users as $user) {
            DB::table('todo_user')->where('id', $user->id)->update(['roles' => [TodoRoleEnum::from($user->role)]]);
        }

        Schema::table('todo_user', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
