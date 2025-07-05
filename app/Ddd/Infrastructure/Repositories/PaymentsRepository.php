<?php

namespace App\Ddd\Infrastructure\Repositories;

use App\Ddd\Domain\Entities\Payment;
use App\Ddd\Domain\Repositories\PaymentsRepositoryInterface;
use App\Dto\Payment\UpdateDto;
use App\Exceptions\PaymentAmountNotCorrectException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use  Illuminate\Support\Carbon;
use App\Dto\Payment\StoreDto;
use App\Exceptions\PaymentNotFoundException;

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

    public function save(UpdateDto $dto): void
    {
        $payment = $this->fetchByUid($dto->uid);

        if ($payment->getAmount() != $dto->amount) {
            throw new PaymentAmountNotCorrectException();
        }

        if ($dto->status == 'succeeded') {
            DB::update('update payments set status = ?, confirmed_at = ? where uid = ?', [$dto->status, now(), $dto->uid]);
        } else {
            DB::update('update payments set status = ? where uid = ?', [$dto->status, $dto->uid]);
        }
    }

    public function fetchByUid(string $uid): Payment
    {
        $result = DB::select('select * from payments where uid = ? limit 1', [$uid]);
        $row = $result[0] ?? '';

        if (!$row) {
            throw new PaymentNotFoundException();
        }

        $payment = new Payment(
            $row->id, 
            $row->uid, 
            $row->order_id, 
            $row->status,
            $row->amount,
            $row->confirmed_at,
            Carbon::parse($row->created_at),
            Carbon::parse($row->updated_at)
        );

        return $payment;
    }
}