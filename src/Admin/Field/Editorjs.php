<?php

declare(strict_types=1);

namespace App\Admin\Field;

use App\Form\Type\EditorjsFormType;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;

class Editorjs implements FieldInterface
{
    use FieldTrait;

    public static function new(string $propertyName, ?string $label = null): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setFormType(EditorjsFormType::class)
            // required also the admin entry in webpack.config.js
        ;
    }
}
