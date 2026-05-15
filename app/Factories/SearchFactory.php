<?php

declare(strict_types=1);

namespace App\Factories;

use DI\Attribute\Inject;
use YetiSearch\YetiSearch;

class SearchFactory
{
    /** @var array<string, mixed> */
    #[Inject('search_config')]
    private array $searchConfig;

    public function __invoke(): YetiSearch
    {
        return new YetiSearch($this->searchConfig);
    }
}
