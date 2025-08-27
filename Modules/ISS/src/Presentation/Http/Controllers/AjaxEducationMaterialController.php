<?php

namespace ISS\App\Presentation\Http\Controllers;

use Illuminate\Http\Request;
use ISS\App\Application\Services\EducationMaterial\DownloadFile\DownloadFile;
use ISS\App\Application\Services\EducationMaterial\DownloadFile\InputDTO as downloadFileDTO;
use ISS\App\Application\Services\EducationMaterial\OpenFile\OpenFile;
use ISS\App\Application\Services\EducationMaterial\OpenFile\InputDTO as openFileDTO;

/**
 * Контроллер работы с учебными материалами ИОС
 * содержит:
 * - метод для загрузки клиенту файла учебного материала
 * - метод для отображения файла учебного материала на странице
 */

class AjaxEducationMaterialController extends Controller
{
    private DownloadFile $downloadFile;
    private OpenFile $openFile;

    public function __construct(
        DownloadFile $downloadFile,
        OpenFile $openFile
    )
    {
        $this->downloadFile = $downloadFile;
        $this->openFile = $openFile;
    }

    /**
     * Передает клиенту файл учебного материала для сохранения
     * //@param Request $request
     *              тип файла для учебного материала
     *                  $request->fileType
     *              название файла учебного материала с расширением
     *                  $request->fileName
     * //@param string $fileType тип файла
     * //@param string $fileName имя файла с расширением
     * Удивительно что $request->all(); дает array{пустой}, но $request->fileType и $request->fileType дают нужные значения
     */
    public function download(Request $request)
    {
        if (in_array($request->fileType, config('iss.ALLOWED_EDUCATION_MATERIAL_TYPES')) && $request->fileName !== 'null') {
            if ($result = ($this->downloadFile)(new downloadFileDTO(fileType: $request->fileType, fileName: $request->fileName))) {
                return $result->fileStream;
            } else {
                return null;
            }
        } else {
            abort(404);
        }
    }

    /**
     * Открывает на странице файл учебного материала
     * @param Request $request
     *              тип файла для учебного материала
     *                  $request->fileType
     *              название файла учебного материала с расширением
     *                  $request->fileName
     */
    public function open(Request $request)
    {
        if (in_array($request->fileType, config('iss.ALLOWED_EDUCATION_MATERIAL_TYPES')) &&
            $request->fileName !== 'null'
        ) {
            if ($result = ($this->openFile)(new openFileDTO(fileType: $request->fileType, fileName: $request->fileName))) {
                return $result->fileStream;
            } else {
                return null;
            }
        } else {
            abort(404);
        }
    }
}
