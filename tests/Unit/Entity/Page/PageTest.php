<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity\Page;

use App\Entity\Page\Page;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

final class PageTest extends TestCase
{
    public function testGetId(): void
    {
        $page = (new Page());

        $this->assertNull($page->getId());
    }

    public function testGetMetaRobotsDirectives(): void
    {
        $page = (new Page());

        $this->assertSame(
            'index, follow',
            implode(', ', $page->getMetaRobotsDirectives()),
        );

        $page->setNofollow(true);
        $page->setNoindex(true);

        $this->assertSame(
            'noindex, nofollow',
            implode(', ', $page->getMetaRobotsDirectives()),
        );

        $page->setNoarchive(true);
        $page->setNosnippet(true);
        $page->setNotranslate(true);
        $page->setNoimageindex(true);

        $this->assertSame(
            'noindex, nofollow, noarchive, nosnippet, notranslate, noimageindex',
            implode(', ', $page->getMetaRobotsDirectives()),
        );
    }

    public function testGetHtmlContentWithoutTemplateReturnsContent(): void
    {
        $page = new Page();
        $content = '<p>Test content</p>';
        $page->setContent($content);

        $this->assertSame($content, $page->getHtmlContent());
    }

    public function testGetHtmlContentWithInvalidTemplateReturnNull(): void
    {
        $page = new Page();
        $page->setTemplate('non_existent_template.html.twig');

        $this->assertNull($page->getHtmlContent());
    }

    public function testValidateContentOrTemplateWithBothContentAndTemplate(): void
    {
        $page = new Page();
        $page->setContent('Some content');
        $page->setTemplate('template.html.twig');

        $violationBuilder = $this->createMock(ConstraintViolationBuilderInterface::class);
        $violationBuilder->expects($this->once())
            ->method('atPath')
            ->with('template')
            ->willReturnSelf();
        $violationBuilder->expects($this->once())
            ->method('addViolation');

        $context = $this->createMock(ExecutionContextInterface::class);
        $context->expects($this->once())
            ->method('buildViolation')
            ->with('You cannot enter “prose content” and “template path” at the same time.')
            ->willReturn($violationBuilder);

        $page->validateContentOrTemplate($context);
    }

    public function testValidateContentOrTemplateWithInvalidTemplate(): void
    {
        $page = new Page();
        $page->setTemplate('non_existent_template.html.twig');

        $violationBuilder = $this->createMock(ConstraintViolationBuilderInterface::class);
        $violationBuilder->expects($this->once())
            ->method('atPath')
            ->with('template')
            ->willReturnSelf();
        $violationBuilder->expects($this->once())
            ->method('addViolation');

        $context = $this->createMock(ExecutionContextInterface::class);
        $context->expects($this->once())
            ->method('buildViolation')
            ->with('The template file does not exist.')
            ->willReturn($violationBuilder);

        $page->validateContentOrTemplate($context);
    }

    public function testValidateContentOrTemplateWithValidContentOnly(): void
    {
        $page = new Page();
        $page->setContent('Valid content');

        $context = $this->createMock(ExecutionContextInterface::class);
        $context->expects($this->never())
            ->method('buildViolation');

        $page->validateContentOrTemplate($context);
    }
}
