<?php

namespace App\Services\Domain\Methods\Essence;

class NewsService implements EssenceInterfaceService{

    public function model(){
        return 'News';
    }  

    public function property(){
        return [
            'id'=>'id',
            'name'=>'name',
            'text'=>'text',
            'photo'=>'photo'
        ];
    }
}