<?php

return [
    'layout' => 'layouts.mainViewTemplate',                         //ссылка на главный шаблон приложения
    'ALLOWED_EDUCATION_MATERIAL_TYPES' => ['video', 'pdf', 'text'], //разрашенные типы обучающих материалов
    'ALLOWED_EDUCATION_TEXT_MATERIAL_TYPES' => ['pdf', 'text'],     //разрашенные типы для текстовых обучающих материалов
    'CONFIG_DATA_FROM_MAIN_APP' => [                                //настройки для загрузки данных из основного прил.
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
        ]
    ],
    'ROLE_ADMIN' => 'admin',
    'ROLE_MANAGER' => 'manager',
    'ROLE_EMPLOYEE' => 'employee',
];
