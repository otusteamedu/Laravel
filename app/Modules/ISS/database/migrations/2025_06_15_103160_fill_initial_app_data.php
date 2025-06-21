<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::insert('insert into user_roles(id, name) values (1, \'employee\'), (2, \'manager\'), (3, \'admin\');');
        DB::insert('insert into education_material_types(id, name)
                          values(1, \'mp4\'), (2, \'avi\'), (3, \'txt\'), (4, \'pdf\'), (5, \'docx\');');
        DB::insert('insert into exam_question_types(name) values(\'simple\'), (\'complicated\');');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::delete('delete from user_roles');
        DB::delete(' delete from education_material_types');
        DB::delete(' delete from exam_question_types');
    }
};
