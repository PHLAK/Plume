<?php

declare(strict_types=1);

namespace App;

use App\Data\Page;
use App\Exceptions\NotFoundException;
use App\Helpers\Str;
use DI\Attribute\Inject;
use Illuminate\Support\Collection;
use League\CommonMark\ConverterInterface;

class Pages
{
    #[Inject('pages_path')]
    private string $pagesPath;

    public function __construct(
        private ConverterInterface $converter,
    ) {}

    /** @return Collection<string, Page> */
    public function all(): Collection
    {
        $paths = new Collection(glob($this->pagesPath . '/*.md') ?: []);

        return $paths->mapWithKeys(function (string $path): array {
            [$slug] = Str::extract(sprintf('#^%s/(?<slug>.+).md$#', preg_quote($this->pagesPath, '#')), $path);

            $content = $this->converter->convert(file_get_contents($path));

            return [$slug => Data\Page::fromRenderedContent($content)];
        })->sortBy('weight');
    }

    /** @throws NotFoundException */
    public function get(string $slug): Page
    {
        $pagePath = sprintf('%s/%s.md', $this->pagesPath, $slug);

        if (! is_readable($pagePath)) {
            throw new NotFoundException;
        }

        if (($contents = file_get_contents($pagePath)) === false) {
            throw new NotFoundException;
        }

        return Page::fromRenderedContent($this->converter->convert($contents));
    }
}
