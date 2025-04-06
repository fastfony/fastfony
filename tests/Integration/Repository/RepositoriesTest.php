<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception\NotNullConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

use function Symfony\Component\String\u;

class RepositoriesTest extends KernelTestCase
{
    /**
     * @dataProvider getEntities
     */
    public function testSaveFindAndRemoveMethods(ClassMetadata $classMetadata): void
    {
        $entity = $classMetadata->newInstance();
        /** @var ServiceEntityRepository $repository */
        $repository = static::getContainer()->get(EntityManagerInterface::class)
            ->getRepository($entity::class);

        try {
            if (method_exists($repository, 'save')) {
                $repository->save($entity);
                $repository->find($entity->getId());
                $this->assertSame($entity, $repository->find($entity->getId()));

                /* @phpstan-ignore method.notFound */
                $repository->remove($entity);

                return;
            }

            $this->expectNotToPerformAssertions();
        } catch (NotNullConstraintViolationException) {
            // Do nothing, it's normal
            $this->expectNotToPerformAssertions();
        }
    }

    /**
     * @return array<array<int, ClassMetadata>>
     */
    public static function getEntities(): array
    {
        $metadatas = static::getContainer()->get(EntityManagerInterface::class)
            ->getMetadataFactory()
            ->getAllMetadata()
        ;

        return array_map(function (ClassMetadata $classMetadata) {
            return [$classMetadata];
        }, array_filter($metadatas, function (ClassMetadata $className) {
            return u($className->getName())->startsWith('App\Entity');
        }));
    }
}
