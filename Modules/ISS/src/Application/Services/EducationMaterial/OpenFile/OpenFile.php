<?php

namespace ISS\App\Application\Services\EducationMaterial\OpenFile;

use Illuminate\Support\Facades\Storage;
use ISS\App\Application\Services\EducationMaterial\OpenFile\InputDTO;
use ISS\App\Application\Services\EducationMaterial\OpenFile\OutputDTO;

class OpenFile
{
    /**
     * Открыть на странице файл учебных материалов
     * @param InputDTO $inputData
     * @return ?OutputDTO
     */
    public function __invoke(InputDTO $inputData): ?OutputDTO
    {
        //каждый тип файлов учебных материалов находится в отдельной папке на диске iss,
        //имя папки совпадает с именем типа файлов
        try {
            return new OutputDTO(fileStream: Storage::disk('iss')
                ->response(
                    '/private/' . $inputData->fileType . '/' . $inputData->fileName,
                    $inputData->fileType . '_instruction' . '.' . $inputData->fileType
                )
            );
        } catch (\Error | \Exception $e) {
            return null;
        }
    }
}
