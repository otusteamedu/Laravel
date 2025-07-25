<?php

namespace App\Ddd\Application\UseCases\Payments\Queries\FetchByUid;

use Illuminate\Support\Carbon;

class Dto
{
    public function __construct(
        public int $id,
        public string $uid,
        public int $order_id,
        public string $status,
        public int $amount,
        public ?Carbon $confirmed_at,
        public ?Carbon $created_at,
    ) 
    {}
}