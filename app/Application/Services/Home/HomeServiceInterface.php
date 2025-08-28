<?php

namespace App\Application\Services\Home;

interface HomeServiceInterface 
{
    /**
     * @return array <nameModel, array<int, string>>
     * @throws NotFoundServiceException
     * @throws ServiceException
     */
    public function prepairDataForIndex(): array;

    public function getRecipe(array $products, int $portions): array;

    public function getMeasureByProduct(int $productId, string $lang): array;

}
