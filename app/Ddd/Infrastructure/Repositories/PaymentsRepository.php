<?php

namespace App\Ddd\Infrastructure\Repositories;

use App\Ddd\Domain\Entities\Payment;
use App\Ddd\Domain\Repositories\PaymentsRepositoryInterface;
use App\Ddd\Domain\ValueObjects\Amount;
use App\Ddd\Domain\ValueObjects\Id;
use App\Ddd\Domain\ValueObjects\Status;
use App\Ddd\Domain\ValueObjects\Uid;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use  Illuminate\Support\Carbon;
use App\Exceptions\PaymentNotFoundException;

class PaymentsRepository implements PaymentsRepositoryInterface
{
    /**
     * @return Collection<int, Payment>
     */
    public function fetchAll(): Collection
    {
        $result = DB::select('select * from payments order by id desc');
        $arr = [];
        foreach ($result as $item) {
            $arr[] = new Payment(
                new Uid($item->uid), 
                new Id($item->order_id), 
                Status::from($item->status),
                new Amount($item->amount),
                new Id($item->id), 
                Carbon::parse($item->confirmed_at),
                Carbon::parse($item->created_at),
                Carbon::parse($item->updated_at)
            );
        }
        $payments = collect($arr);
        return $payments;
    }

    public function add(Payment $payment): void
    {
        $params = [
            $payment->getUid()->toString(), 
            $payment->getOrderId()->toInt(), 
            $payment->getStatus()->value, 
            $payment->getAmount()->toInt(), 
            now(), 
            now()
        ];
        DB::insert('insert into payments(uid, order_id, status, amount, created_at, updated_at) values (?, ?, ?, ?, ?, ?)', $params);
    }

    public function save(Payment $payment): void
    {
        if ($payment->getStatus() == 'succeeded') {
            $params = [$payment->getStatus()->value, now(), $payment->getUid()->toString()];
            DB::update('update payments set status = ?, confirmed_at = ? where uid = ?', $params);
        } else {
            $params = [$payment->getStatus()->value, $payment->getUid()->toString()];
            DB::update('update payments set status = ? where uid = ?', $params);
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
            new Uid($row->uid), 
            new Id($row->order_id), 
            Status::from($row->status),
            new Amount($row->amount),
            new Id($row->id), 
            Carbon::parse($row->confirmed_at),
            Carbon::parse($row->created_at),
            Carbon::parse($row->updated_at)
        );

        return $payment;
    }
}