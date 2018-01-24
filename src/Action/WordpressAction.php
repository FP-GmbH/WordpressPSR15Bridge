<?php
/**
 * This file is part of F&P ♡ Creating Communities package "WordpressMiddleware".
 *
 * User: bastian_charlet <bc@fp.de>
 * Date: 20.11.2017
 * Time: 17:30
 */

namespace Fup\WordpressMiddleware\Action;


use Fup\WordpressMiddleware\Exception\InvalidArgumentException;
use Fup\WordpressMiddleware\Service\WordpressBridgeService;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template\TemplateRendererInterface;

class WordpressAction implements MiddlewareInterface
{

    /** @var TemplateRendererInterface */
    private $template;

    private $wordpressBridgeService;
    /**
     * @var string Identifier for Layout to render
     */
    private $templateId = '';
    /**
     * @var string Key to access wordpress content within the template
     */
    private $layoutKey = 'wordpress_string';

    public function __construct(
        TemplateRendererInterface $template,
        WordpressBridgeService $wordpressBridgeService,
        $templateId,
        $layoutKey = null
    )
    {
        $this->template = $template;
        $this->wordpressBridgeService = $wordpressBridgeService;
        $this->setTemplateId($templateId);
        if (null !== $layoutKey) {
            $this->setLayoutKey($layoutKey);
        }
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
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $wordpress_output = $this->wordpressBridgeService->getStdOutString();
        $rendered = $this->template->render(
            $this->templateId,
            [$this->layoutKey => $wordpress_output]
        );

        try {
            return new HtmlResponse($rendered);
        } catch (\InvalidArgumentException $exception) {
            //todo: log or do something else.
            return $delegate->process($request);
        }
    }

    /**
     * @param string $templateId
     * @throws \Fup\WordpressMiddleware\Exception\InvalidArgumentException
     */
    public function setTemplateId($templateId)
    {
        if (!is_string($templateId)) {
            throw new InvalidArgumentException('Template id must be string');
        }
        $this->templateId = $templateId;
    }

    /**
     * @param string $layoutKey
     * @throws \Fup\WordpressMiddleware\Exception\InvalidArgumentException
     */
    public function setLayoutKey($layoutKey)
    {
        if (!is_string($layoutKey)) {
            throw new InvalidArgumentException('Layout key must be of type string');
        }
        $this->layoutKey = $layoutKey;
    }
}