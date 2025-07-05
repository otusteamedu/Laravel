<?php

namespace App\Ddd\Infrastructure\Repositories;

use App\Ddd\Domain\Entities\Payment;
use App\Ddd\Domain\Repositories\PaymentsRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use  Illuminate\Support\Carbon;
use App\Dto\Payment\StoreDto;

class PaymentsRepository implements PaymentsRepositoryInterface
{
    public function fetchAll(): Collection
    {
        $result = DB::select('select * from payments order by id desc');
        $arr = [];
        foreach ($result as $item) {
            $arr[] = new Payment(
                $item->id, 
                $item->uid, 
                $item->order_id, 
                $item->status,
                $item->amount,
                $item->confirmed_at,
                Carbon::parse($item->created_at),
                Carbon::parse($item->updated_at)
            );
        }
        $payments = collect($arr);
        return $payments;
    }

    public function add(StoreDto $dto): void
    {
        $params = [$dto->uid, $dto->orderId, $dto->status, $dto->amount, now(), now()];
        DB::insert('insert into payments(uid, order_id, status, amount, created_at, updated_at) values (?, ?, ?, ?, ?, ?)', $params);
    }
}