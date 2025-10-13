<?php

declare(strict_types=1);

namespace App;

use App\Data\Page;
use App\Exceptions\PageNotFoundException;
use App\Helpers\Str;
use Illuminate\Support\Collection;
use League\CommonMark\ConverterInterface;

class Pages
{
    public function __construct(
        private Config $config,
        private ConverterInterface $converter,
    ) {}

    /** @return Collection<string, Page> */
    public function all(): Collection
    {
        $paths = new Collection(glob($this->config->string('pages_path') . '/*.md') ?: []);

        return $paths->mapWithKeys(function (string $path): array {
            [$slug] = Str::extract(sprintf('#^%s/(?<slug>.+).md$#', preg_quote($this->config->string('pages_path'), '#')), $path);

            $content = $this->converter->convert(file_get_contents($path));

            return [$slug => Data\Page::fromRenderedContent($content)];
        })->sortBy('weight');
    }

    /** @throws PageNotFoundException */
    public function get(string $slug): Page
    {
        $pagePath = sprintf('%s/%s.md', $this->config->string('pages_path'), $slug);

        if (! is_readable($pagePath)) {
            throw new PageNotFoundException;
        }

        if (($contents = file_get_contents($pagePath)) === false) {
            throw new PageNotFoundException;
        }

        return Page::fromRenderedContent($this->converter->convert($contents));
    }
}
