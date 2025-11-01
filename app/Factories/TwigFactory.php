<?php

declare(strict_types=1);

namespace App\Factories;

use App\ViewFunctions\ViewFunction;
use DI\Attribute\Inject;
use Invoker\CallableResolver;
use Slim\Views\Twig;
use Twig\Extension\CoreExtension;
use Twig\Extra\Html\HtmlExtension;
use Twig\Loader\FilesystemLoader;
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

    #[Inject('view_functions')]
    private array $viewFunctions;

    public function __construct(
        private CallableResolver $callableResolver,
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

        foreach ($this->viewFunctions as $function) {
            /** @var ViewFunction&callable $function */
            $function = $this->callableResolver->resolve($function);

            $twig->getEnvironment()->addFunction(
                new TwigFunction($function->name, $function)
            );
        }

        return $twig;
    }
}
