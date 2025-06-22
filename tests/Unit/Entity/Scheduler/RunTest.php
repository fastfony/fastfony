<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity\Scheduler;

use App\Entity\Scheduler\Run;
use PHPUnit\Framework\TestCase;

final class RunTest extends TestCase
{
    private Run $run;

    protected function setUp(): void
    {
        $this->run = new Run();
    }

    public function testGetRunDateFormatted(): void
    {
        $this->run->setRunDate('1633024800'); // Unix timestamp for 2021-10-01 00:00:00

        $expectedDate = new \DateTime('@1633024800');
        $this->assertEquals($expectedDate, $this->run->getRunDateFormatted());
    }

    public function testGetInputObject(): void
    {
        $inputObject = (object) ['key' => 'value'];
        $this->run->setInput(serialize($inputObject));

        $this->assertEquals($inputObject, $this->run->getInputObject());
    }

    public function testHasFailureOutput(): void
    {
        $this->assertFalse($this->run->hasFailureOutput());

        $this->run->setFailureOutput(serialize(['error' => 'Something went wrong']));
        $this->assertTrue($this->run->hasFailureOutput());
    }

    public function testGetFailureOutputObject(): void
    {
        $failureOutputArray = ['error' => 'Something went wrong'];
        $this->run->setFailureOutput(serialize($failureOutputArray));

        $this->assertEquals($failureOutputArray, $this->run->getFailureOutputObject());
    }

    public function testGetFailureOutputObjectReturnsNullWhenNoFailureOutput(): void
    {
        $this->assertNull($this->run->getFailureOutputObject());
    }

    public function testGetSetMessageContextId(): void
    {
        $id = 'context-123456';
        $result = $this->run->setMessageContextId($id);

        $this->assertSame($id, $this->run->getMessageContextId());
        $this->assertSame($this->run, $result, 'La méthode doit retourner $this');
    }

    public function testGetSetRunDate(): void
    {
        $timestamp = (string) time();
        $result = $this->run->setRunDate($timestamp);

        $this->assertSame($timestamp, $this->run->getRunDate());
        $this->assertSame($this->run, $result, 'La méthode doit retourner $this');
    }

    public function testGetSetTrigger(): void
    {
        $trigger = 'cron';
        $result = $this->run->setTrigger($trigger);

        $this->assertSame($trigger, $this->run->getTrigger());
        $this->assertSame($this->run, $result, 'La méthode doit retourner $this');
    }

    public function testGetSetScheduler(): void
    {
        $scheduler = 'daily-report';
        $result = $this->run->setScheduler($scheduler);

        $this->assertSame($scheduler, $this->run->getScheduler());
        $this->assertSame($this->run, $result, 'La méthode doit retourner $this');
    }

    public function testGetSetInput(): void
    {
        $input = 'a:1:{s:5:"param";s:5:"value";}'; // Représentation sérialisée d'un tableau
        $result = $this->run->setInput($input);

        $this->assertSame($input, $this->run->getInput());
        $this->assertSame($this->run, $result, 'La méthode doit retourner $this');
    }

    public function testGetSetFailureOutput(): void
    {
        $error = 'Error message';
        $result = $this->run->setFailureOutput($error);

        $this->assertSame($error, $this->run->getFailureOutput());
        $this->assertSame($this->run, $result, 'La méthode doit retourner $this');
    }

    public function testIsSetTerminated(): void
    {
        $this->assertFalse($this->run->isTerminated());

        $result = $this->run->setTerminated(true);

        $this->assertTrue($this->run->isTerminated());
        $this->assertSame($this->run, $result, 'La méthode doit retourner $this');
    }
}
