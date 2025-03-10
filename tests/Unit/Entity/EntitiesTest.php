<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

use function Symfony\Component\String\u;

/*
 * Thanks to Julien Sambon for this share
 */
class EntitiesTest extends KernelTestCase
{
    /**
     * @dataProvider getEntities
     */
    public function testGettersAndSetters(ClassMetadata $classMetadata): void
    {
        $entity = $classMetadata->newInstance();
        $entityMock = $this->createMock($classMetadata->getName());

        // An entity must have the ORM\Entity attribute
        $reflectionClass = new \ReflectionClass($classMetadata->getName());
        $attributes = $reflectionClass->getAttributes(Entity::class);
        self::assertNotEmpty($attributes);

        $properties = [];
        $reflectionProperties = array_filter(
            $classMetadata->getReflectionProperties(),
            function (\ReflectionProperty $property) use ($classMetadata) {
                return $property->class === $classMetadata->getName();
            }
        );

        foreach ($reflectionProperties as $reflectionProperty) {
            $fieldName = ucfirst($reflectionProperty->getName());

            foreach (['get', 'is'] as $getter) {
                $getterMethod = $getter.$fieldName;
                if (method_exists($entity, $getterMethod)) {
                    $properties[$reflectionProperty->getName()]['getter'] = $getterMethod;
                    break;
                }
            }

            $setterMethod = 'set'.$fieldName;
            if (method_exists($entity, $setterMethod)) {
                $properties[$reflectionProperty->getName()]['setter'] = $setterMethod;
            }
        }

        foreach ($properties as $property) {
            if (
                !isset($property['getter'])
                || !isset($property['setter'])
                || method_exists($this, 'test'.$classMetadata->getReflectionClass()->getShortName().ucfirst($property['setter']))
                || method_exists($this, 'test'.$classMetadata->getReflectionClass()->getShortName().ucfirst($property['getter']))
            ) {
                continue;
            }

            $getter = $classMetadata->getReflectionClass()->getMethod($property['getter']);
            $setter = $classMetadata->getReflectionClass()->getMethod($property['setter']);

            // If return type is not the same as the setter param type then it's specific and we don't test it here
            if ($getter->getReturnType()?->getName() !== $setter->getParameters()[0]->getType()?->getName()) {
                continue;
            }

            if (u($getter->getReturnType()->getName())->containsAny('DateTime')) {
                // The mock does not handle datetime automatically
                $value = new \DateTime();
            } else {
                $value = $entityMock->{$property['getter']}();
            }

            $entity->{$property['setter']}($value);
            self::assertEquals($value, $entity->{$property['getter']}());
        }
    }

    /**
     * @return array<int, ClassMetadata[]>
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

    /*
     * Tests Getter / Setter spéciaux
     */
    public function testGetRoles(): void
    {
        self::assertEquals((new User())->getRoles(), ['ROLE_USER']);
    }
}
