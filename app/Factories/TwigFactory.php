<?php

declare(strict_types=1);

namespace App\Factories;

use App\Config;
use App\ViewFunctions\ViewFunction;
use Invoker\CallableResolver;
use Slim\Views\Twig;
use Twig\Extension\CoreExtension;
use Twig\Extra\Html\HtmlExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class TwigFactory
{
    public function __construct(
        private Config $config,
        private CallableResolver $callableResolver,
    ) {}

    public function __invoke(): Twig
    {
        $twig = new Twig(new FilesystemLoader($this->config->string('views_path')), [
            'cache' => $this->config->get('view_cache'),
        ]);

        /** @var CoreExtension $core */
        $core = $twig->getEnvironment()->getExtension(CoreExtension::class);

        $core->setDateFormat($this->config->string('date_format'), '%d days');
        $core->setTimezone($this->config->string('timezone'));

        $twig->addExtension(new HtmlExtension);

        foreach ($this->config->array('view_functions') as $function) {
            /** @var ViewFunction&callable $function */
            $function = $this->callableResolver->resolve($function);

            $twig->getEnvironment()->addFunction(
                new TwigFunction($function->name, $function)
            );
        }

        return $twig;
    }
}
