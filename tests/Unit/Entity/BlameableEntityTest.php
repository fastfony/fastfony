<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\BlameableEntity;
use App\Entity\User\User;
use PHPUnit\Framework\TestCase;

class BlameableEntityTest extends TestCase
{
    private object $entity;

    protected function setUp(): void
    {
        // Créer une classe anonyme utilisant le trait pour le tester
        $this->entity = new class {
            use BlameableEntity;
        };
    }

    public function testCreatedByGetterAndSetter(): void
    {
        $user = $this->createMock(User::class);

        $this->assertNull($this->entity->getCreatedBy());

        $result = $this->entity->setCreatedBy($user);

        $this->assertSame($this->entity, $result);
        $this->assertSame($user, $this->entity->getCreatedBy());
    }

    public function testUpdatedByGetterAndSetter(): void
    {
        $user = $this->createMock(User::class);

        $this->assertNull($this->entity->getUpdatedBy());

        $result = $this->entity->setUpdatedBy($user);

        $this->assertSame($this->entity, $result);
        $this->assertSame($user, $this->entity->getUpdatedBy());
    }
}
