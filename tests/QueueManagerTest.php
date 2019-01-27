<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Intersect\Queue\QueueManager;

class QueueManagerTest extends TestCase {

    public function test_run_singleProcessor()
    {
        $queueManager = new QueueManager();

        $testProcessor = new TestProcessor();
        $queueManager->registerProcessor($testProcessor);

        $this->assertFalse($testProcessor->wasRun);

        $queueManager->run();

        $this->assertTrue($testProcessor->wasRun);
    }

    public function test_registerProcessor_duplicateCheck()
    {
        $queueManager = new QueueManager();

        $testProcessor = new TestProcessor();
        $queueManager->registerProcessor($testProcessor);
        $queueManager->registerProcessor($testProcessor);

        $this->assertTrue(count($queueManager->getRegisteredProcessors()) == 1);
    }

    public function test_run_multipleProcessors()
    {
        $queueManager = new QueueManager();

        $testProcessor = new TestProcessor();
        $testProcessor2 = new TestProcessor();
        $queueManager->registerProcessor($testProcessor, 'p1');
        $queueManager->registerProcessor($testProcessor2, 'p2');

        $this->assertFalse($testProcessor->wasRun);
        $this->assertFalse($testProcessor2->wasRun);

        $queueManager->run();

        $this->assertTrue($testProcessor->wasRun);
        $this->assertTrue($testProcessor2->wasRun);
    }

}