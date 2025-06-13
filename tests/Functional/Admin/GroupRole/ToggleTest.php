<?php

declare(strict_types=1);

namespace App\Tests\Functional\Admin\GroupRole;

use App\Entity\User\Group;
use App\Entity\User\Role;
use App\Repository\User\GroupRepository;
use App\Repository\User\RoleRepository;
use App\Repository\User\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ToggleTest extends WebTestCase
{
    private const TEST_GROUP_NAME = 'Test Group';
    private const TEST_ROLE_NAME = 'ROLE_TEST';

    public function testToggleRoleInGroupAsAdmin(): void
    {
        $user = static::getContainer()->get(UserRepository::class)
            ->findOneBy(['enabled' => true]);

        // We create a group and a role for testing
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);

        $group = new Group();
        $group->setName(self::TEST_GROUP_NAME);
        $entityManager->persist($group);

        $role = new Role();
        $role->setName(self::TEST_ROLE_NAME);
        $role->setDescription('Test Role');
        $entityManager->persist($role);

        $entityManager->flush();

        self::ensureKernelShutdown();

        $client = static::createClient();
        $client->loginUser($user);

        $groupId = $group->getId();
        $roleId = $role->getId();

        // Test adding the role to the group
        $client->request('POST', '/admin/group_role/toggle', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'group_id' => $groupId,
            'role_id' => $roleId,
        ]));

        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());
        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertTrue($responseData['checked']);

        // We check that the role has been added to the group
        $groupRepository = static::getContainer()->get(GroupRepository::class);
        $roleRepository = static::getContainer()->get(RoleRepository::class);
        $group = $groupRepository->findOneBy(['name' => self::TEST_GROUP_NAME]);
        $role = $roleRepository->findOneBy(['name' => self::TEST_ROLE_NAME]);
        $this->assertTrue($group->getRoles()->contains($role));

        // Test removing the role from the group
        $client->request(
            'POST',
            '/admin/group_role/toggle',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode([
                'group_id' => $groupId,
                'role_id' => $roleId,
            ],
            ));

        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());
        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertFalse($responseData['checked']);

        // We reload the group and check that the role has been removed
        $group = $groupRepository->findOneBy(['name' => self::TEST_GROUP_NAME]);
        $role = $roleRepository->findOneBy(['name' => self::TEST_ROLE_NAME]);
        $this->assertFalse($group->getRoles()->contains($role));
    }

    public function tearDown(): void
    {
        // We remove the test group and role
        $groupRepository = static::getContainer()->get(GroupRepository::class);
        $roleRepository = static::getContainer()->get(RoleRepository::class);
        $group = $groupRepository->findOneBy(['name' => 'self::TEST_GROUP_NAME']);
        $role = $roleRepository->findOneBy(['name' => self::TEST_ROLE_NAME]);
        if ($group) {
            $groupRepository->remove($group);
        }
        if ($role) {
            $roleRepository->remove($role);
        }

        parent::tearDown();
    }
}
