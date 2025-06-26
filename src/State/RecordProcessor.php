<?php

declare(strict_types=1);

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Collection\Record;
use App\Entity\Collection\RecordFieldValue;
use App\Repository\Collection\FieldRepository;
use App\Repository\Collection\RecordRepository;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;

/**
 * @implements  ProcessorInterface<mixed, Record>
 */
readonly class RecordProcessor implements ProcessorInterface
{
    /**
     * @param ProcessorInterface<mixed, object> $persistProcessor
     */
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private readonly ProcessorInterface $persistProcessor,
        private readonly FieldRepository $fieldRepository,
        private readonly RecordRepository $recordRepository,
    ) {
    }

    /**
     * @param Record                                                                 $data
     * @param array<string, mixed>                                                   $uriVariables
     * @param array<string, mixed>|array{request?: Request, resource_class?: string} $context
     */
    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = [],
    ): Record {
        $data->resetFields();
        $requestData = $context['request']->toArray();
        $savedRecord = $this->recordRepository->find($data->getId());
        if (null !== $savedRecord) {
            $data->setFields($savedRecord->getFields());
        }

        if ($requestData) {
            foreach ($requestData['fields'] as $name => $value) {
                $field = $this->fieldRepository->findOneBy([
                    'name' => $name,
                    'recordCollection' => $data->getCollection(),
                ]);
                if ($field) {
                    $recordFieldValue = $data->findField($field);
                    if (null === $recordFieldValue) {
                        $recordFieldValue = (new RecordFieldValue())
                            ->setRecord($data)
                            ->setField($field)
                        ;
                    }

                    $recordFieldValue->setValue($value);
                    $this->recordRepository->save($recordFieldValue);
                }
            }
        }

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
