<?php

return [
    'layout' => 'layouts.mainViewTemplate',                         //ссылка на главный шаблон приложения

    'ISS_ROUTE_PREFIX' =>                                           //префикс маршрутов модуля
        env("ISS_ROUTE_PREFIX",'/iss'),
    'ISS_ADMIN_ROUTE_PREFIX' =>                                    //префикс маршрутов модуля (административный интерфейс)
        env("ISS_ADMIN_ROUTE_PREFIX", '/admin'),

    'ISS_MAIL_FROM_ADDRESS' => 'ivanovTesting8823@yandex.ru',      //адрес почты отправки писем ИОС

    'ISS_COMMANDS' => [                                            //команды ИОС
        'cache' => [                                                   //
            'actionHotStart' => 'start',                               //прогрев кэша
            'actionClear' => 'clear',                                  //очистка кэша
        ],
    ],


    'ALLOWED_EDUCATION_MATERIAL_TYPES' => [                         //разрашенные типы обучающих материалов
        'mp4', 'avi', 'pdf', 'txt', 'docx'
    ],

    'ALLOWED_EDUCATION_TEXT_MATERIAL_TYPES' => [                    //разрашенные ТИПЫ ФАЙЛОВ для текстовых обучающих материалов
        'pdf', 'txt', 'docx'
    ],

    'ALLOWED_EDUCATION_VIDEO_MATERIAL_TYPES' => [                  //разрашенные ТИПЫ ФАЙЛОВ для видео обучающих материалов
        'mp4', 'avi'
    ],

    'CONFIG_DATA_FROM_MAIN_APP' => [                               //настройки для загрузки данных из основного прил.
        'organization' => [                                             //таблица откуда берем название организации
            'tableName' => 'users',                                     //название таблицы
            'fieldOrganizationName' => 'organization',                  //имя поля в котором хранится назв-е организации
            'fieldCodeName' => 'id',                                    //имя поля с кодом организации в Users (по умолчанию organization_code)
        ],
        'fio' => [                                                      //таблица откуда берем ФИО сотрудника
            'tableName' => 'users',                                     //название таблицы
            'fieldName' => 'name',                                      //имя поля в котором хранится Имя
            'fieldSecondName' => 'second_name',                         //имя поля в котором хранится Отчество
            'fieldLastName' => 'last_name',                             //имя поля в котором хранится Фамилия
            'fieldCodeName' => 'id',                                     //имя поля с кодом сотрудника в Users (по умолчанию employee_fio_code)
        ],
        'contact' => [
            'tableName' => 'users',                                     //название таблицы
            'fieldEmail' => 'email',                                    //имя поля в котором хранится email
            'fieldCodeName' => 'id',                                    //имя поля с кодом контактов в Users (по умолчанию contact_code)
        ]
    ],

    'ROLE_ADMIN' => 'admin',                                       //название роли администратора ИОС
    'ROLE_MANAGER' => 'manager',                                   //название роли менеджера ИОС
    'ROLE_EMPLOYEE' => 'employee',                                 //название роли сотрудника ИОС

    'EXAM_STATUS' => [                                             //названия статусов экзамена (которые ставит препод-ль)
        'passed' => 'passed',                                          //название отметки экзамен сдан
        'failed' => 'failed',                                          //название отметки экзамен провален
    ],

    'EXAM_CHECK_TYPE' => [                                        //названия типов проверки экзамена
        'manual' => 'manual',                                          //вручную преподавателем
        'auto' => 'auto',                                              //автоматически сервисом
    ],

    'EXAM_ERRORS_ALLOWED_PERCENT' => 30,                         //допустимый процент ошибок в экзамене (для авто проверки)

    'REAL_ROUTE_POINT_STATE' => [                                 //название статусов реальной точки обучающего маршрута
        'passed'  => 'passed',                                          //пройдена
        'wait'    => 'wait',                                            //ожидает сдачи экзамена
        'expired' => 'expired',                                         //сдача экзамена просрочена
    ],

    'ISS_USER_ACTION' => [                                        //интерфейс администратора, действия над пользователями
        'create' => 'create',
        'edit' => 'edit'
    ],

    'ISS_USER_PARAMETERS' => [
        'defaultAvatar' => 'defaultUserAvatar.png',
    ],

    'ISS_REF_ROUTE_POINT_ACTION' => [                            //интерфейс администратора, действия над справочной точкой маршрута
        'create' => 'create',
        'edit' => 'edit'
    ],
];
