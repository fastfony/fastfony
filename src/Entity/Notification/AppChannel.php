<?php

declare(strict_types=1);

namespace App\Entity\Notification;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\User\User;
use App\Repository\Notification\AppChannelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: AppChannelRepository::class)]
#[ApiResource(mercure: true)]
class AppChannel
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    /** @phpstan-ignore-next-line */
    private ?Uuid $id = null;

    #[ORM\OneToOne(inversedBy: 'appChannel', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * @var Collection<int, AppMessage>
     */
    #[ORM\OneToMany(targetEntity: AppMessage::class, mappedBy: 'appChannel', orphanRemoval: true)]
    #[ORM\OrderBy(['createdAt' => 'DESC'])]
    private Collection $messages;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
    }

    public function __toString(): string
    {
        return (string) $this->getUser()?->getUserIdentifier();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, AppMessage>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(AppMessage $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setAppChannel($this);
        }

        return $this;
    }

    public function removeMessage(AppMessage $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getAppChannel() === $this) {
                $message->setAppChannel(null);
            }
        }

        return $this;
    }
}
