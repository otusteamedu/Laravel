<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Response;

//главная страница модуля
Route::get('/iss', function () {return view('iss::issMainPage');})->middleware('web')->name('iss');

//ВРЕМЕННЫЙ маршрут на стр пользователя, пока нет авторизации
Route::get(
    '/iss/user/{id}',
    function ($id) {
        return view(
            'iss::issUserPage',
            [
                'userRole' => 'manager',
                'userParameters' => [
                    'Organization' => 'Test firm',
                    'Name' => 'Ivan',
                    'SecondName' => 'Ivanov',
                    'LastName' => 'Ivanovich',
                ],
                'educationChains' => [
                    [
                        'readyPercent' =>15,
                        'nodes' => [
                            '1' => ['pass' => 'pass', 'examDate' => '12-01-2020'],
                            '2' => ['pass' => 'expired', 'examDate' => '12-01-2021'],
                            '3' => ['pass' => 'wait', 'examDate' => '12-01-2022'],
                            '4' => ['pass' => 'wait', 'examDate' => '12-01-2023'],
                            '5' => ['pass' => 'wait', 'examDate' => '12-01-2024']
                        ],
                        'routeName' => 'First testing education route',
                        'chainId' => 123454234,
                    ],
                    [
                        'readyPercent' => 27,
                        'nodes' => [
                            '1' => ['pass' => 'pass', 'examDate' => '01-01-2023'],
                            '2' => ['pass' => 'pass', 'examDate' => '02-01-2023'],
                            '3' => ['pass' => 'wait', 'examDate' => '03-01-2023'],
                            '4' => ['pass' => 'wait', 'examDate' => '04-01-2023'],
                            '5' => ['pass' => 'wait', 'examDate' => '05-01-2023']
                        ],
                        'routeName' => 'Route 2',
                        'chainId' => 77854234,
                    ],
                ],
                'diagrams' => [/*
                    'organization1' => [
                        'json' => '{
                        "employee1": [{"route1": "27"}, {"route2": "17"}, {"route3": "7"}],
                        "employee2": [{"route1": "83"}, {"route2": "57"}]
                        }',
                        'diagramName' => 'Education routes chart',
                    ],*/
                    'organization2' => [
                        'json' => '{
                        "employee1": [{"route1": "7"}, {"route2": "68"}, {"route3": "83"}],
                        "employee2": [{"route1": "11"}, {"route2": "17"}],
                        "employee3": [{"route1": "90"}, {"route3": "95"}]
                        }',
                        'diagramName' => 'Education routes chart',
                    ],
                ],
            ]
        );
    }
)->middleware('web')->name('issUser');

//ВРЕМЕННЫЙ маршрут на страницу точки маршрута обучения (пока нет контроллеров)
Route::get(
    '/iss/educationChainNode/{chainId}/{nodeId}',
    function($chainId, $nodeId) {
        //echo 'chain id ='.$chainId.'  node id ='.$nodeId;

        $nodeData = [
            'routeName' => 'Example route 1',
            'nodeName' => 'Example Node1',
            'examResult' => 'passed',
            'examDate' => '12-12-12',
            'questions' => ['question1' => 'How many items can has employee?', 'question2' => 'What employee can do Someth.?'],
            'userId' => 12345
        ];

        return view('iss::issNodePage', ['nodeData' => $nodeData]);
    }
)->middleware('web')->name('issEducationChainNode');
