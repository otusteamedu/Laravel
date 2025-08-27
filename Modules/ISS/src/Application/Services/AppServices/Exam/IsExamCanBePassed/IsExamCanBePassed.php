<?php

namespace ISS\App\Application\Services\AppServices\Exam\IsExamCanBePassed;

use ISS\App\Application\Services\AppServices\Exam\IsExamCanBePassed\InputDTO;
use ISS\App\Application\Services\AppServices\Exam\IsExamCanBePassed\OutputDTO;
use ISS\App\Application\Services\RealEducationRoutePoint\GetRealRoutePointById\GetRealRoutePointById;
use ISS\App\Application\Services\RealEducationRoutePoint\GetRealRoutePointById\InputDTO as rerpInputDTO;
use ISS\App\Application\Services\RealEducationRoutesOfUsers\GetRouteOfUserByRefRouteId\GetRouteOfUserByRefRouteId;
use ISS\App\Application\Services\RealEducationRoutesOfUsers\GetRouteOfUserByRefRouteId\InputDTO as reruInputDTO;
use ISS\App\Application\Services\RealEducationRoutePoint\GetFirstRealRoutePointByRouteId\GetFirstRealRoutePointByRouteId;
use ISS\App\Application\Services\RealEducationRoutePoint\GetFirstRealRoutePointByRouteId\InputDTO as firstPointInputDTO;
use ISS\App\Application\Services\RealEducationRoutePoint\GetNextRealRoutePointByPosition\GetNextRealRoutePointByPosition;
use ISS\App\Application\Services\RealEducationRoutePoint\GetNextRealRoutePointByPosition\InputDTO as nextPointInputDTO;

use ISS\App\Domain\Exam\Exam;

class IsExamCanBePassed
{
    private GetRealRoutePointById $getRealRoutePointById;
    private GetRouteOfUserByRefRouteId $getRouteOfUserByRefRouteId;
    private GetFirstRealRoutePointByRouteId $getFirstRealRoutePointByRouteId;
    private GetNextRealRoutePointByPosition $getNextRealRoutePointByPosition;

    public function __construct(
        GetRealRoutePointById $getRealRoutePointById,
        GetRouteOfUserByRefRouteId $getRouteOfUserByRefRouteId,
        GetFirstRealRoutePointByRouteId $getFirstRealRoutePointByRouteId,
        GetNextRealRoutePointByPosition $getNextRealRoutePointByPosition,
    )
    {
        $this->getRealRoutePointById = $getRealRoutePointById;
        $this->getRouteOfUserByRefRouteId = $getRouteOfUserByRefRouteId;
        $this->getFirstRealRoutePointByRouteId = $getFirstRealRoutePointByRouteId;
        $this->getNextRealRoutePointByPosition = $getNextRealRoutePointByPosition;
    }

    /**
     * Проверить разрешено ли сдать экзамен для текущей точки маршрута
     * (если для предыдущей точки маршрута экзамен не сдан то для текущей точки маршрута сдать его нельзя,
     * если пытаемся сдать экзамен для уже пройденной точки маршрута -- также запретит,
     * разрешено сдать экзамен только для точки маршрута следующей за последней сданной точкой данного маршрута)
     * @param InputDTO $inputDTO
     * @return OutputDTO
     */
    public function __invoke(InputDTO $inputData): OutputDTO
    {
        try {
            //получаем id справочного маршрута для данной реальной точки
            $refRouteId = ($this->getRealRoutePointById)(new rerpInputDTO(id: $inputData->realRoutePointId))->routeId;

            //получаем код lpp для реального маршрута этого пользователя по коду справочного маршрута из реальной точки
            $lppId = ($this->getRouteOfUserByRefRouteId)(new reruInputDTO(
                issUserId: $inputData->issUserId,
                refRouteId: $refRouteId
            ))->lastPassPointId;


            if (is_null($lppId)) {
                //если на маршруте не пройдено ни одной точки то получаем код первой точки маршрута
                $validPointId = ($this->getFirstRealRoutePointByRouteId)(new firstPointInputDTO(refRouteId: $refRouteId))
                    ->id;
            } else {
                //если есть пройденные точки, то получаем позицию последней пройденой точки маршрута
                $pllPosition = ($this->getRealRoutePointById)(new rerpInputDTO(id: $lppId))->position;

                //получаем код реальной точки, следующей за последней пройденной точкой маршрута
                $validPointId = ($this->getNextRealRoutePointByPosition)(new nextPointInputDTO(
                    routeId: $refRouteId,
                    position: $pllPosition
                ))->id;
            }

        } catch (\Error | \Exception $e) {
            //запись влог
            return new OutputDTO(grantPassExam: false);
        }

        //если текущая точка является следующей за последней пройденной
        // (или первой точкой маршрута, если еще нет пройденных точек), то ее экзамен можно сдать
        return new OutputDTO(grantPassExam: Exam::isPointCanBePassed($inputData->realRoutePointId, $validPointId));
    }
}
