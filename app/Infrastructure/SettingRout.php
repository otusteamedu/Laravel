<?php
namespace App\Infrastructure;

class SettingRout{
    public function start($request){
        return $this->setting($request);
    }

    private function setting($request){
        return [
            'News'=>[
                'model'=> new \App\Models\News,
                'property'=>['id','name','user_id'],
                'request'=>[
                    'page'=>$request->page ?? 0,
                    'count' => $request->count ?? 10,
                    'id'=>$request->id ?? null
                ],
                'inc'=>'id'
            ]
        ];
    }
}