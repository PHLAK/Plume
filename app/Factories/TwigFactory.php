<?php

declare(strict_types=1);

namespace App\Factories;

use App\Filters\ViewFilter;
use App\Functions\ViewFunction;
use DI\Attribute\Inject;
use DI\Container;
use Slim\Views\Twig;
use Twig\Extension\CoreExtension;
use Twig\Extra\Html\HtmlExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFilter;
use Twig\TwigFunction;

class TwigFactory
{
    #[Inject('views_path')]
    private string $viewsPath;

    #[Inject('icons_path')]
    private string $iconsPath;

    #[Inject('view_cache')]
    private string $viewCache;

    #[Inject('date_format')]
    private string $dateFormat;

    #[Inject('timezone')]
    private string $timezone;

    /** @var list<ViewFilter> */
    #[Inject('view_filters')]
    private array $viewFilters;

    /** @var list<ViewFunction> */
    #[Inject('view_functions')]
    private array $viewFunctions;

    public function __construct(
        private Container $container,
    ) {}

    public function __invoke(): Twig
    {
        $twig = new Twig(new FilesystemLoader([$this->viewsPath, $this->iconsPath]), [
            'cache' => strtolower($this->viewCache) === 'false' ? false : $this->viewCache,
        ]);

        /** @var CoreExtension $core */
        $core = $twig->getEnvironment()->getExtension(CoreExtension::class);

        $core->setDateFormat($this->dateFormat, '%d days');
        $core->setTimezone($this->timezone);

        $twig->addExtension(new HtmlExtension);

        foreach ($this->viewFilters as $class) {
            /** @var ViewFilter $filter */
            $filter = $this->container->get($class);

            $twig->getEnvironment()->addFilter(
                new TwigFilter($filter->name, $filter)
            );
        }

        foreach ($this->viewFunctions as $class) {
            /** @var ViewFunction $function */
            $function = $this->container->get($class);

            $twig->getEnvironment()->addFunction(
                new TwigFunction($function->name, $function)
            );
        }

        return $twig;
    }
}
