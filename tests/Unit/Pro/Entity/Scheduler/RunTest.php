<?php

declare(strict_types=1);

namespace App\Tests\Unit\Pro\Entity\Scheduler;

use App\Pro\Entity\Scheduler\Run;
use PHPUnit\Framework\TestCase;

class RunTest extends TestCase
{
    public function testGetRunDateFormatted(): void
    {
        $run = new Run();
        $run->setRunDate('1633024800'); // Unix timestamp for 2021-10-01 00:00:00

        $expectedDate = new \DateTime('@1633024800');
        $this->assertEquals($expectedDate, $run->getRunDateFormatted());
    }

    public function testGetInputObject(): void
    {
        $run = new Run();
        $inputObject = (object) ['key' => 'value'];
        $run->setInput(serialize($inputObject));

        $this->assertEquals($inputObject, $run->getInputObject());
    }

    public function testHasFailureOutput(): void
    {
        $run = new Run();
        $this->assertFalse($run->hasFailureOutput());

        $run->setFailureOutput(serialize(['error' => 'Something went wrong']));
        $this->assertTrue($run->hasFailureOutput());
    }

    public function testGetFailureOutputObject(): void
    {
        $run = new Run();
        $failureOutputArray = ['error' => 'Something went wrong'];
        $run->setFailureOutput(serialize($failureOutputArray));

        $this->assertEquals($failureOutputArray, $run->getFailureOutputObject());
    }

    public function testGetFailureOutputObjectReturnsNullWhenNoFailureOutput(): void
    {
        $run = new Run();

        $this->assertNull($run->getFailureOutputObject());
    }
}
