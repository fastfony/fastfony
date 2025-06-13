<?php

declare(strict_types=1);

namespace App\Pro\Entity\Collection;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Entity\BlameableEntity;
use App\Entity\CommonProperties;
use App\Pro\Repository\Collection\RecordRepository;
use App\Pro\State\PublishedRecordProvider;
use App\Pro\State\RecordProcessor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/internal/records/{id}',
            security: "is_granted('ROLE_ADMIN')",
        ),
        new Get(
            uriTemplate: '/public/records/{id}',
            normalizationContext: ['groups' => ['public:record:list']],
            security: "is_granted('PUBLIC_ACCESS')",
            provider: PublishedRecordProvider::class,
        ),
        new GetCollection(
            uriTemplate: '/public/record_collections/{collectionId}/records',
            uriVariables: [
                'collectionId' => new Link(
                    fromProperty: 'records',
                    fromClass: RecordCollection::class,
                ),
            ],
            normalizationContext: ['groups' => ['public:record:list']],
            security: 'is_granted("PUBLIC_ACCESS")',
            provider: PublishedRecordProvider::class,
        ),
        new Post(
            uriTemplate: '/internal/records',
            security: "is_granted('ROLE_ADMIN')",
            processor: RecordProcessor::class,
        ),
        new Put(
            uriTemplate: '/internal/records/{id}',
            security: "is_granted('ROLE_ADMIN')",
            processor: RecordProcessor::class,
        ),
        new Patch(
            uriTemplate: '/internal/records/{id}',
            security: "is_granted('ROLE_ADMIN')",
        ),
        new Delete(
            uriTemplate: '/internal/records/{id}',
            security: "is_granted('ROLE_ADMIN')",
        ),
    ],
    normalizationContext: ['groups' => ['record:read']],
    denormalizationContext: ['groups' => ['record:write']],
)]
#[ORM\Entity(repositoryClass: RecordRepository::class)]
class Record
{
    use BlameableEntity;
    use CommonProperties\Required\Published;
    use TimestampableEntity;

    #[Groups([
        'record:read',
        'record:list',
        'public:record:list',
        'public:page:read',
    ])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    private Uuid $id;

    #[Groups([
        'record:write',
    ])]
    #[ORM\ManyToOne(inversedBy: 'records')]
    #[ORM\JoinColumn(nullable: false)]
    private ?RecordCollection $collection = null;

    /**
     * @var Collection<int, RecordFieldValue>
     */
    #[Groups([
        'record:write',
    ])]
    #[ORM\OneToMany(
        targetEntity: RecordFieldValue::class,
        mappedBy: 'record',
        orphanRemoval: true
    )]
    private Collection $fields;

    public function __construct()
    {
        $this->id = Uuid::v7();
        $this->fields = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    #[Groups([
        'record:list',
    ])]
    public function isPublished(): bool
    {
        return $this->published;
    }

    #[Groups([
        'record:write',
    ])]
    public function setPublished(bool $published): static
    {
        $this->published = $published;

        return $this;
    }

    public function getCollection(): ?RecordCollection
    {
        return $this->collection;
    }

    public function setCollection(?RecordCollection $collection): static
    {
        $this->collection = $collection;

        return $this;
    }

    /**
     * @return Collection<int, RecordFieldValue>
     */
    public function getFields(): Collection
    {
        return $this->fields;
    }

    public function setFields(Collection $fields): static
    {
        $this->fields = $fields;

        return $this;
    }

    public function findField(Field $field): ?RecordFieldValue
    {
        foreach ($this->fields as $recordFieldValue) {
            if ($recordFieldValue->getField() === $field) {
                return $recordFieldValue;
            }
        }

        return null;
    }

    /**
     * @return array<string, mixed>
     */
    #[SerializedName('fields')]
    #[Groups([
        'record:list',
    ])]
    public function getArrayFields(): array
    {
        $fields = [];
        foreach ($this->fields as $field) {
            $fields[$field->getField()->getName()] = $field->getValue();
        }

        return $fields;
    }

    /**
     * @return array<string, mixed>
     */
    #[SerializedName('fields')]
    #[Groups([
        'public:record:list',
        'public:page:read',
    ])]
    public function getPresentableArrayFields(): array
    {
        $fields = [];
        foreach ($this->fields as $field) {
            if ($field->getField()->isPresentable()) {
                $fields[$field->getField()->getName()] = $field->getValue();
            }
        }

        return $fields;
    }

    public function resetFields(): void
    {
        $this->fields = new ArrayCollection();
    }

    public function addField(RecordFieldValue $field): static
    {
        if (!$this->fields->contains($field)) {
            $this->fields->add($field);
            $field->setRecord($this);
        }

        return $this;
    }

    public function removeField(RecordFieldValue $field): static
    {
        if ($this->fields->removeElement($field)) {
            // set the owning side to null (unless already changed)
            if ($field->getRecord() === $this) {
                $field->setRecord(null);
            }
        }

        return $this;
    }
}
