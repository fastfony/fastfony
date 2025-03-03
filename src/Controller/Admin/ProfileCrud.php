<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\User\Profile;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProfileCrud extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Profile::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('firstName'),
            TextField::new('lastName'),
            TelephoneField::new('phoneNumber'),
        ];
    }
}
