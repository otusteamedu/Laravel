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
            create materialized view persons as  
                select
                    p.id,
                    p.identifier,
                    p.surname,
                    p.name,
                    p.middle_name,
                    p.active 
                from
                    core.persons p       
        ';
        DB::statement($query);
        $query = "comment on materialized view persons is 'Материализованное представление объекта «Персона»'";
        DB::statement($query);
        $fields = [
            'id' => '1',
            'identifier' =>  '2',
            'surname' =>  '3',
            'name' =>  '4',
            'middle_name' =>  '5',
            'active' => '7',
        ];
        foreach($fields as $fieldName => $comment){
            $query = "comment on column persons.$fieldName is '$comment'";
            DB::statement($query);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $query = 'drop materialized view persons';
        DB::statement($query);
    }
};
