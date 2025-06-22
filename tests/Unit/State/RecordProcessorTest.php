<?php

declare(strict_types=1);

namespace App\Tests\Unit\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Collection\Field;
use App\Entity\Collection\Record;
use App\Entity\Collection\RecordCollection;
use App\Entity\Collection\RecordFieldValue;
use App\Repository\Collection\FieldRepository;
use App\Repository\Collection\RecordRepository;
use App\State\RecordProcessor;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class RecordProcessorTest extends TestCase
{
    /**
     * @var MockObject|ProcessorInterface<int, string>|(ProcessorInterface<int, string>&object&MockObject)|(ProcessorInterface<int, string>&MockObject)|(object&MockObject)
     */
    private MockObject|ProcessorInterface $persistProcessor;
    private MockObject|FieldRepository $fieldRepository;
    private MockObject|RecordRepository $recordRepository;
    private RecordProcessor $recordProcessor;
    private Operation $operation;

    protected function setUp(): void
    {
        $this->persistProcessor = $this->createMock(ProcessorInterface::class);
        $this->fieldRepository = $this->createMock(FieldRepository::class);
        $this->recordRepository = $this->createMock(RecordRepository::class);

        $this->recordProcessor = new RecordProcessor(
            $this->persistProcessor,
            $this->fieldRepository,
            $this->recordRepository
        );

        $this->operation = $this->createMock(Operation::class);
    }

    public function testProcessNewRecord(): void
    {
        $collection = new RecordCollection();
        $record = new Record();
        $record->setCollection($collection);

        $field = new Field();
        $field->setName('test_field');
        $field->setRecordCollection($collection);

        $requestData = [
            'fields' => [
                'test_field' => 'test_value',
            ],
        ];

        $request = $this->createMock(Request::class);
        $request->expects($this->once())
            ->method('toArray')
            ->willReturn($requestData);

        $context = ['request' => $request];

        $this->recordRepository->expects($this->once())
            ->method('find')
            ->willReturn(null);

        $this->fieldRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['name' => 'test_field', 'recordCollection' => $collection])
            ->willReturn($field);

        $this->recordRepository->expects($this->once())
            ->method('save')
            ->with($this->callback(function ($recordFieldValue) use ($field, $record) {
                return $recordFieldValue instanceof RecordFieldValue
                    && $recordFieldValue->getField() === $field
                    && $recordFieldValue->getRecord() === $record
                    && 'test_value' === $recordFieldValue->getValue();
            }));

        $this->persistProcessor->expects($this->once())
            ->method('process')
            ->with($record, $this->operation, [], $context)
            ->willReturn($record);

        $result = $this->recordProcessor->process($record, $this->operation, [], $context);

        $this->assertSame($record, $result);
    }

    public function testProcessExistingRecord(): void
    {
        $collection = new RecordCollection();
        $record = new Record();
        $record->setCollection($collection);

        $savedRecord = new Record();

        $field = new Field();
        $field->setName('test_field');
        $field->setRecordCollection($collection);

        $existingFieldValue = new RecordFieldValue();
        $existingFieldValue->setField($field);
        $existingFieldValue->setRecord($savedRecord);
        $existingFieldValue->setValue('old_value');

        $savedRecord->addField($existingFieldValue);

        $requestData = [
            'fields' => [
                'test_field' => 'updated_value',
            ],
        ];

        $request = $this->createMock(Request::class);
        $request->expects($this->once())
            ->method('toArray')
            ->willReturn($requestData);

        $context = ['request' => $request];

        $this->recordRepository->expects($this->once())
            ->method('find')
            ->willReturn($savedRecord);

        $this->fieldRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['name' => 'test_field', 'recordCollection' => $collection])
            ->willReturn($field);

        $this->recordRepository->expects($this->once())
            ->method('save')
            ->with($this->callback(function ($recordFieldValue) {
                return $recordFieldValue instanceof RecordFieldValue
                    && 'updated_value' === $recordFieldValue->getValue();
            }));

        $this->persistProcessor->expects($this->once())
            ->method('process')
            ->with($record, $this->operation, [], $context)
            ->willReturn($record);

        $result = $this->recordProcessor->process($record, $this->operation, [], $context);

        $this->assertSame($record, $result);
    }
}
