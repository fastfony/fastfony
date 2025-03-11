<?php

declare(strict_types=1);

namespace App\Tests\Unit\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class CrudTest extends TestCase
{
    public function testGetEntityFqcn(): void
    {
        $finder = new Finder();
        $finder->files()->in(__DIR__.'/../../../../src/Controller')->name('*.php');

        foreach ($finder as $file) {
            $className = $this->getClassNameFromFile($file);
            if (is_subclass_of($className, AbstractCrudController::class)) {
                $this->assertNotEmpty($className::getEntityFqcn(), \sprintf('The method getEntityFqcn in %s should not return an empty value.', $className));
            }
        }
    }

    private function getClassNameFromFile(SplFileInfo $file): string
    {
        $namespace = 'App\\Controller';
        $relativePath = $file->getRelativePath();
        if ($relativePath) {
            $namespace .= '\\'.str_replace('/', '\\', $relativePath);
        }

        return $namespace.'\\'.$file->getBasename('.php');
    }
}
