<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\User\Profile;
use App\Handler\FeatureFlag;
use App\Handler\Features;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProfileCrud extends AbstractCrudController
{
    public function __construct(
        private readonly FeatureFlag $featureFlag,
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return Profile::class;
    }

    /**
     * @return iterable<FieldInterface>
     */
    public function configureFields(string $pageName): iterable
    {
        if (!$this->featureFlag->isEnabled(Features::USERS_MANAGEMENT->value)) {
            throw new NotFoundHttpException();
        }

        return [
            FormField::addColumn(8),
            TextField::new('firstName'),
            TextField::new('lastName'),
            TelephoneField::new('phoneNumber'),
        ];
    }
}
