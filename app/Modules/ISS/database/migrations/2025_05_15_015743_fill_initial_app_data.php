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
        DB::insert('insert into user_roles(name) values (\'employee\'), (\'manager\'), (\'admin\');');
        DB::insert('insert into education_material_types(name) values(\'video\'), (\'text\'), (\'pdf\');');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::delete('delete from user_roles');
        DB::delete(' delete from education_material_types');
    }
};
