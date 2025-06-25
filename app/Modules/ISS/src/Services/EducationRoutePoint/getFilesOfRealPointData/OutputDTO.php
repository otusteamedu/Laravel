<?php

declare(strict_types=1);

namespace App\Modules\ISS\src\Services\EducationRoutePoint\getFilesOfRealPointData;

/**
 * @var array<string,array[][]> $materials массив учебных материалов
 *      [
 *          'тип материала' => [
 *                                 ['title' =>'example1', 'file_path' => 'example\file\path\1'],
 *                                 ['title' =>'example1', 'file_path' => 'example\file\path\1'],
 *                             ],
 *          'тип материала' => [],
 *      ]
 */

class OutputDTO
{
    public function __construct(
        public array $materials
    )
    {
    }
}
