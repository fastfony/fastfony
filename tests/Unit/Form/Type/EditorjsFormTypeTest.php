<?php

declare(strict_types=1);

namespace App\Tests\Unit\Form\Type;

use App\Form\Type\EditorjsFormType;
use Symfony\Component\Form\Test\TypeTestCase;

class EditorjsFormTypeTest extends TypeTestCase
{
    public function testSubmitValidData(): void
    {
        $formData = json_encode(['blocks' => [['type' => 'paragraph', 'data' => ['text' => 'Hello world']]]]);

        $form = $this->factory->create(EditorjsFormType::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals(
            json_decode($formData, true),
            $form->getData(),
        );
    }
}
