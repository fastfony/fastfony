<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    use SaveAndRemoveMethodTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function countEnabled(): int
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->where('u.enabled = true')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function createSuperAdmin(string $email): User
    {
        $user = $this->create($email);
        $user->setRoles(['ROLE_SUPER_ADMIN']);

        $this->save($user);

        return $user;
    }

    public function create(string $email): User
    {
        $user = new User();
        $user->setEmail($email);

        $this->save($user);

        return $user;
    }
}
