<?php

declare(strict_types=1);

namespace App\Functions;

use App\Data\Page;
use App\Pages as PagesRepository;
use DI\Attribute\Inject;
use Illuminate\Support\Collection;

class Pages extends ViewFunction
{
    public string $name = 'pages';

    #[Inject(PagesRepository::class)]
    private PagesRepository $pages;

    public function __invoke(): Collection
    {
        return $this->pages->all()->mapWithKeys(
            fn (Page $page, string $slug) => [$slug => $page->link]
        );
    }
}
