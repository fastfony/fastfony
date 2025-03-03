<?php

declare(strict_types=1);

namespace App\Controller\Admin\Parameter;

use App\Entity\Parameter\ParameterCategory;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ParameterCategoryCrud extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ParameterCategory::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->showEntityActionsInlined()
            ->setEntityLabelInPlural('Parameter categories')
        ;
    }
}
