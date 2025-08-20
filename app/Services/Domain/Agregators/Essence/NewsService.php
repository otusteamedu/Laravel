<?php

namespace App\Services\Domain\Agregators\Essence;

class NewsService implements EssenceInterfaceService{

    public function model(){
        return 'News';
    }  

    public function property(){
        return [
            'ids'=>'id',
            'title'=>'name',
            'description'=>'text',
            'photo'=>'photo'
        ];
    }
}