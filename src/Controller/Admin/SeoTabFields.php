<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

trait SeoTabFields
{
    /**
     * @return array<FieldInterface>
     */
    public function getSeoTabFields(): array
    {
        return [
            FormField::addTab('SEO'),
            FormField::addColumn(6),
            TextField::new('slug'),
            FormField::addFieldset('Meta tags'),
            TextField::new('metaTitle')
                ->hideOnIndex(),
            TextareaField::new('metaDescription')
                ->hideOnIndex(),
            FormField::addColumn(6),
            FormField::addFieldset('Meta robots')
                ->setHelp('help.meta_robots'),
            BooleanField::new('noindex')
                ->setHelp('help.noindex')
                ->hideOnIndex(),
            BooleanField::new('nofollow')
                ->setHelp('help.nofollow')
                ->hideOnIndex(),
            BooleanField::new('noarchive')
                ->setHelp('help.noarchive')
                ->hideOnIndex(),
            BooleanField::new('nosnippet')
                ->setHelp('help.nosnippet')
                ->hideOnIndex(),
            BooleanField::new('notranslate')
                ->setHelp('help.notranslate')
                ->hideOnIndex(),
            BooleanField::new('noimageindex')
                ->setHelp('help.noimageindex')
                ->hideOnIndex(),
        ];
    }
}
