<?php

namespace App\Interfaces\Http\Controllers;

use App\Application\Exceptions\NotValidItemServiceException;
use App\Application\Exceptions\NotAdminServiceException;
use App\Infrastructure\EloquentModels\User;
use Illuminate\Http\Response;
use App\Interfaces\Response\WebResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Support\Facades\Log;
use Throwable;

class FibonachiController extends Controller
{
    public function index(): Response
    {
        try {
            $response = new WebResponse(true);
        } catch (Throwable $e) {
            $response = new WebResponse(false, null, $e->getMessage(), [], 500);
            Log::error(__METHOD__ . var_export($response, true));
        } finally {
            return response()->view('fibonachi.index.index', compact('response'), $response->statusCode);
        }
    }

    public function calculate(int $number, GateContract $gate): JsonResponse
    {
        try {
            if (!$gate->allows('calculate', User::class)) {
                throw new NotAdminServiceException();
            }
            if ((int) $number < 1 || (int) $number > 100 || !is_int($number)) {
                throw new NotValidItemServiceException('Число должно быть от 1 до 100.');
            }
            if ($number === 1) {
                $result = [0];
            } else {
                $result = [0, 1];
                for ($i = 2; $i <= $number; $i++) {
                    $result[$i] = $result[$i - 2] + $result[$i - 1];
                }
            }
            $response = new WebResponse(true, $result, 'Успешно');
        } catch (Throwable $th) {
            $response = new WebResponse(false, null, $th->getMessage(), [], $th->getCode());
            Log::error(__METHOD__ . var_export($response, true));
        } finally {
            return response()->json($response , $response->statusCode);
        }
    }
}
