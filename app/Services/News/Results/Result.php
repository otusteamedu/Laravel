<?php
declare(strict_types=1);

namespace App\Services\News\Results;

final readonly class Result
{
    /**
     * @param NewsDTO[] $results
     */
    public function __construct(
        public array $results
    )
    {
    }
}
