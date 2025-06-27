Стратегия кэширования:

Кэшируются данные
1) контроллер:   IssAdminController
   TTL:          60*60
   прогрев:      да
   данные:       $diagramsData
   тэги:         ['diagram', 'adminDiagrams']
   ключ:         adminDiagrams
   инвалидация:  в IssCheckExamController (checkExam, setExamManualCheckResult) перед отправкой ответа
   инвалидация:  в /НЕ РЕАЛИЗУЕТСЯ В РАМКАХ ОБУЧЕНИЯ в ОТУС
                   контроллер админ интерфейса,
                   подключение и отключение пользователя ИОС от обучающих маршрутов/ перед отправкой ответа ajax



2) контроллер:   IssUserPageController
   TTL:          60*60
   прогрев:      да
   данные:       $diagramsData
   тэги:         ['diagram', 'managerDiagram']
   ключ:         'managerDiagram_' . $issUserId
   инвалидация:  в IssCheckExamController (checkExam, setExamManualCheckResult) перед отправкой ответа
   инвалидация:  в IssAuthUser для Администратора (чтобы мог смотреть страницы других пользователей корректно)
   инвалидация:  в /НЕ РЕАЛИЗУЕТСЯ В РАМКАХ ОБУЧЕНИЯ в ОТУС
                   контроллер админ интерфейса,
                   подключение и отключение пользователя ИОС от обучающих маршрутов/ перед отправкой ответа ajax

3) контроллер:   IssUserPageController
   TTL:          60*60
   прогрев:      да
   данные:       $issUserRoutes
   тэги:         ['userData', 'userDataRoutes']
   ключ:         'userDataRoutes_' . $issUserId
   инвалидация:  в IssCheckExamController (checkExam, setExamManualCheckResult) перед отправкой ответа
   инвалидация:  в /НЕ РЕАЛИЗУЕТСЯ В РАМКАХ ОБУЧЕНИЯ в ОТУС
                    контроллер админ интерфейса,
                    инструмент составления обучающих маршрутов
                    подключение и отключение пользователя ИОС от обучающих маршрутов/ перед отправкой ответа ajax

4) контроллер:   IssUserPageController
   TTL:          60*60
   прогрев:      да
   данные:       $issUserParameters
   тэги:         ['userData', 'userDataMain']
   ключ:         'userDataMain_' . $issUserId
   инвалидация:  в IssStartPageController в блоке "авторизация ИОС" (после загрузки данных из основного приложения)
   инвалидация:  в IssStartPageController в функции выхода из ИОС
   инвалидация:  в /НЕ РЕАЛИЗУЕТСЯ В РАМКАХ ОБУЧЕНИЯ в ОТУС
                    контроллер админ интерфейса,
                    обновление данных пользователя ИОС
                    удаление пользователя ИОС/ перед отправкой ответа ajax



5) контроллер:   IssRoutePointController
   TTL:          60*5
   прогрев:      нет
   данные:       $pointMainData
   тэги:         ['pointData', 'mainPointData']
   ключ:         'mainPointData_' . $issUserId . '_' . $pointId
   инвалидация:  IssCheckExamController (checkExam, setExamManualCheckResult)
   инвалидация:  в /НЕ РЕАЛИЗУЕТСЯ В РАМКАХ ОБУЧЕНИЯ в ОТУС
                    контроллер админ интерфейса,
                    инструмент составления обучающих маршрутов/ перед отправкой ответа ajax

6) контроллер:   IssRoutePointController
   TTL:          60*60
   прогрев:      нет
   данные:       $isComplicated
   тэги:         ['pointData', 'pointExam']
   ключ:         'pointExam_' . $pointId
   инвалидация:  в /НЕ РЕАЛИЗУЕТСЯ В РАМКАХ ОБУЧЕНИЯ в ОТУС
                    контроллер админ интерфейса,
                    инструмент составления обучающих маршрутов/ перед отправкой ответа ajax

7) контроллер:   IssRoutePointController
   TTL:          60*60
   прогрев:      нет
   данные:       $examQuestionsWithAnswers
   тэги:         ['pointData', 'pointExam', 'questionsWithAnswers']
   ключ:         'questionsWithAnswers_' . $pointId
   инвалидация:  в /НЕ РЕАЛИЗУЕТСЯ В РАМКАХ ОБУЧЕНИЯ в ОТУС
                    контроллер админ интерфейса,
                    инструмент составления обучающих маршрутов/ перед отправкой ответа ajax

8) контроллер:   IssRoutePointController
   TTL:          60*60
   прогрев:      нет
   данные:       $educationMaterials
   тэги:         ['pointData', 'pointMaterials']
   ключ:         'pointMaterials_' . $pointId
   инвалидация:  в /НЕ РЕАЛИЗУЕТСЯ В РАМКАХ ОБУЧЕНИЯ в ОТУС
                    контроллер админ интерфейса,
                    инструмент составления обучающих маршрутов/ перед отправкой ответа ajax

