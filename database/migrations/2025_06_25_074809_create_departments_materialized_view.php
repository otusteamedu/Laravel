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
        $query = '
            create materialized view departments as  
                select
                    d.id,
                    d.identifier,
                    d.code,
                    d.full_name,
                    d.name,
                    d.short_name,
                    d.active 
                from
                    core.departments d        
        ';
        DB::statement($query);
        $query = "comment on materialized view departments is 'Материализованное представлени объекта «Подразделения»'";
        DB::statement($query);
        $fields = [
            'id' => '1',
            'identifier' =>  '2',
            'code' =>  '3',
            'full_name' =>  '4',
            'name' =>  '5',
            'short_name' => '6', 
            'active' => '7',
        ];
        foreach($fields as $fieldName => $comment){
            $query = "comment on column departments.$fieldName is '$comment'";
            DB::statement($query);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $query = 'drop materialized view departments';
        DB::statement($query);
    }
};
