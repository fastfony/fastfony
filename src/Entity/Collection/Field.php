<?php

declare(strict_types=1);

namespace App\Entity\Collection;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Entity\BlameableEntity;
use App\Entity\CommonProperties;
use App\Repository\Collection\FieldRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\UniqueConstraint(
    name: 'UNIQ_FIELD_NAME_BY_RECORD_COLLECTION',
    fields: ['name', 'recordCollection'],
)]
#[UniqueEntity(fields: ['name', 'recordCollection'])]
#[ApiResource(
    operations: [
        new Get(
            openapi: false,
            security: 'is_granted("ROLE_ADMIN")',
        ),
    ]
)]
#[ORM\Entity(repositoryClass: FieldRepository::class)]
class Field
{
    use BlameableEntity;
    use CommonProperties\Required\AutoGeneratedId;
    use CommonProperties\Required\Name;
    use TimestampableEntity;

    #[Groups([
        'record_collection:read',
        'record_collection:write',
        'record:list',
    ])]
    #[ORM\Column(length: 255)]
    private ?string $type = null;

    /**
     * @var array <string, mixed>
     */
    #[Groups([
        'record_collection:read',
    ])]
    #[ORM\Column(type: Types::JSON)]
    private array $parameters = [];

    #[Groups([
        'record_collection:read',
        'record_collection:write',
    ])]
    #[ORM\Column]
    private bool $hidden = false;

    #[Groups([
        'record_collection:read',
        'record_collection:write',
    ])]
    #[ORM\Column]
    private bool $nonempty = false;

    #[Groups([
        'record_collection:read',
        'record_collection:write',
    ])]
    #[ORM\Column]
    private bool $presentable = true;

    #[ORM\ManyToOne(inversedBy: 'fields')]
    #[ORM\JoinColumn(nullable: false)]
    private ?RecordCollection $recordCollection = null;

    #[Groups([
        'record_collection:read',
    ])]
    public function getId(): ?int
    {
        return $this->id;
    }

    #[Groups([
        'record_collection:read',
        'record:list',
    ])]
    public function getName(): ?string
    {
        return $this->name;
    }

    #[Groups([
        'record_collection:write',
    ])]
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param array<string, mixed> $parameters
     */
    public function setParameters(array $parameters): static
    {
        $this->parameters = $parameters;

        return $this;
    }

    public function isHidden(): ?bool
    {
        return $this->hidden;
    }

    public function setHidden(bool $hidden): static
    {
        $this->hidden = $hidden;

        return $this;
    }

    public function isNonempty(): ?bool
    {
        return $this->nonempty;
    }

    public function setNonempty(bool $nonempty): static
    {
        $this->nonempty = $nonempty;

        return $this;
    }

    public function isPresentable(): ?bool
    {
        return $this->presentable;
    }

    public function setPresentable(bool $presentable): static
    {
        $this->presentable = $presentable;

        return $this;
    }

    public function getRecordCollection(): ?RecordCollection
    {
        return $this->recordCollection;
    }

    public function setRecordCollection(?RecordCollection $recordCollection): static
    {
        $this->recordCollection = $recordCollection;

        return $this;
    }
}
