<?php

declare(strict_types=1);

namespace App\Tests\Unit\Form\Type;

use App\Form\Type\JsonFormType;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class JsonFormTypeTest extends TypeTestCase
{
    private ValidatorInterface|MockObject $validator;

    protected function setUp(): void
    {
        $this->validator = Validation::createValidator();

        parent::setUp();
    }

    protected function getExtensions(): array
    {
        $type = new JsonFormType($this->validator);

        return [
            new PreloadedExtension([$type], []),
        ];
    }

    public function testSubmitValidData(): void
    {
        $formData = '{"key": "value"}';

        $form = $this->factory->create(JsonFormType::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals(
            json_decode($formData, true),
            $form->getData(),
        );

        $view = $form->createView();
        $this->assertSame($formData, $view->vars['data']);
    }

    public function testSubmitFalseData(): void
    {
        $formData = '{"key""value"}';

        $form = $this->factory->create(JsonFormType::class);

        $form->submit($formData);

        $this->assertFalse($form->isSynchronized());
        $this->assertEquals(
            json_decode($formData, true),
            $form->getData(),
        );
    }

    public function testSubmitNullData(): void
    {
        $formData = null;

        $form = $this->factory->create(JsonFormType::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertNull($form->getData());
    }

    public function testCreateValidData(): void
    {
        $formData = '{"key""value"}';
        $form = $this->factory->create(JsonFormType::class, $formData);
        $view = $form->createView();
        $this->assertSame(
            json_encode($formData, \JSON_PRETTY_PRINT),
            $view->vars['data'],
        );
    }
}
