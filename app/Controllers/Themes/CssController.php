<?php

declare(strict_types=1);

namespace App\Controllers\Themes;

use DI\Attribute\Inject;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use SplFileInfo;

class CssController
{
    #[Inject('css_path')]
    private string $cssPath;

    public function __construct(
        private Twig $view,
    ) {}

    public function __invoke(Response $response, string $stylesheet): ResponseInterface
    {
        $file = new SplFileInfo(sprintf('%s/%s', $this->cssPath, $stylesheet));

        if (! $file->isReadable()) {
            return $this->view->render($response->withStatus(404), 'error.twig', [
                'message' => 'File not found',
            ]);
        }

        if ($file->getSize() !== false) {
            $response = $response->withHeader('Content-Length', (string) $file->getSize());
        }

        return $response->withHeader('Content-Type', 'text/css')->withBody(
            (new StreamFactory)->createStreamFromFile($file->getRealPath())
        );
    }
}
