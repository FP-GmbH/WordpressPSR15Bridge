<?php
/**
 * Created by PhpStorm.
 * User: bastian_charlet
 * Date: 15.12.2017
 * Time: 10:49
 */

namespace Joyce\WordpressMiddleware\test\Action;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Joyce\WordpressMiddleware\Action\WordpressAction;
use Joyce\WordpressMiddleware\Service\WordpressBridgeService;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template\TemplateRendererInterface;

class WordpressActionTest extends TestCase
{

    public function testProcess()
    {
        $wpOutput = 'hallo i bims. 1 wordpress.';

        $template = $this->prophesize(TemplateRendererInterface::class);
        $template
            ->render(WordpressAction::DEFAULT_TEMPLATE, [WordpressAction::FRONTEND_KEY_WORDPRESS => $wpOutput])
            ->shouldBeCalled()
            ->willReturn('<html>');
        /** @var TemplateRendererInterface $templateMock */
        $templateMock = $template->reveal();

        $wordpressBridge = $this->prophesize(WordpressBridgeService::class);
        $wordpressBridge->getStdOutString()->shouldBeCalled()->willReturn($wpOutput);
        $wordpressBridgeMock = $wordpressBridge->reveal();

        $action = new WordpressAction($templateMock, $wordpressBridgeMock);

        /** @var ServerRequestInterface $request */
        $request = $this->prophesize(ServerRequestInterface::class)->reveal();
        /** @var DelegateInterface $delegate */
        $delegate = $this->prophesize(DelegateInterface::class)->reveal();

        $this->assertInstanceOf(HtmlResponse::class, $action->process($request, $delegate));
    }
}
