<?php

declare(strict_types=1);

namespace App;

use App\Data\Page;
use App\Exceptions\NotFoundException;
use DI\Attribute\Inject;
use GlobIterator;
use Illuminate\Support\LazyCollection;
use League\CommonMark\ConverterInterface;
use SplFileInfo;

class Pages
{
    #[Inject('pages_path')]
    private string $pagesPath;

    #[Inject(ConverterInterface::class)]
    private ConverterInterface $converter;

    /** @throws NotFoundException */
    public function get(string $slug): Page
    {
        $page = new SplFileInfo(sprintf('%s/%s.md', $this->pagesPath, $slug));

        if (! $page->isReadable()) {
            throw new NotFoundException;
        }

        if (($contents = file_get_contents($page->getRealPath())) === false) {
            throw new NotFoundException;
        }

        return Page::fromRenderedContent($this->converter->convert($contents));
    }

    /** @return LazyCollection<string, Page> */
    public function all(): LazyCollection
    {
        /** @var GlobIterator<int, SplFileInfo> $posts */
        $pages = new GlobIterator($this->pagesPath . '/*.md');

        return  new LazyCollection(function () use ($pages) {
            foreach ($pages as $page) {
                $slug = $page->getBasename('.md');

                yield $slug => $this->get($slug);
            }
        })->sortBy('weight');
    }
}
