<?php
/**
 * Created by PhpStorm.
 * User: bastian_charlet
 * Date: 20.11.2017
 * Time: 17:30
 */

namespace Joyce\WordpressMiddleware\Action;


use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use InvalidArgumentException;
use Joyce\WordpressMiddleware\Service\WordpressBridgeService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template\TemplateRendererInterface;

class WordpressAction implements MiddlewareInterface
{
    public const DEFAULT_TEMPLATE = 'joyce::wordpress-blank';
    public const FRONTEND_KEY_WORDPRESS = 'wordpress_string';
    /** @var TemplateRendererInterface */
    private $template;

    private $wordpressBridgeService;

    public function __construct(TemplateRendererInterface $template, WordpressBridgeService $wordpressBridgeService)
    {
        $this->template = $template;
        $this->wordpressBridgeService = $wordpressBridgeService;
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * to the next middleware component to create the response.
     *
     * @param ServerRequestInterface $request
     * @param DelegateInterface $delegate
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        $wordpress_output = $this->wordpressBridgeService->getStdOutString();
        $rendered = $this->template->render(
            self::DEFAULT_TEMPLATE,
            [self::FRONTEND_KEY_WORDPRESS => $wordpress_output]
        );

        try {
            return new HtmlResponse($rendered);
        } catch (InvalidArgumentException $exception) {
            //todo: log or do something else.
            return $delegate->process($request);
        }
    }
}