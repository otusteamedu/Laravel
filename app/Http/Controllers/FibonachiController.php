<?php

namespace App\Http\Controllers;

use App\Exceptions\Fibonachi\FibonachiExaption;
use App\Exceptions\Fibonachi\NotAdminException;
use App\Models\User;
use Illuminate\Http\Response;
use App\Response\WebResponse;
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
                throw new NotAdminException();
            }
            if ((int) $number < 1 || (int) $number > 100 || !is_int($number)) {
                throw new FibonachiExaption();
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
        } catch (NotAdminException $e) {
            $response = new WebResponse(false, null, $e->getMessage(), [], 403);
            Log::error(__METHOD__ . var_export($response, true));
        } catch (FibonachiExaption $e) {
            $response = new WebResponse(false, null, $e->getMessage(), [], 400);
            Log::error(__METHOD__ . var_export($response, true));
        } catch (Throwable $e) {
            $response = new WebResponse(false, null, $e->getMessage(), [], 500);
            Log::error(__METHOD__ . var_export($response, true));
        } finally {
            return response()->json($response , $response->statusCode);
        }
    }
}
