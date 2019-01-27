<?php

namespace Intersect\Queue;

use Cron\CronExpression;

/**
 * Class QueueManager
 * @package Intersect\Queue
 */
class QueueManager {

    private $registeredProcessors = [];
    private $runningProcessors = [];
    private $scheduledProcessors = [];

    public function __construct() {}

    /**
     * @param QueueProcessor $processor
     * @param null $identifier
     */
    public function registerProcessor(QueueProcessor $processor, $identifier = null)
    {
        $identifier = (!is_null($identifier) ? $identifier : $this->generateProcessorIdentifier($processor));

        if (array_key_exists($identifier, $this->registeredProcessors))
        {
            return;
        }

        $this->registeredProcessors[$identifier] = $processor;
    }

    public function run()
    {
        $this->scheduleProcessors();
        $this->processScheduledProcessors();
    }

    /**
     * @return array
     */
    public function getRegisteredProcessors()
    {
        return $this->registeredProcessors;
    }

    /**
     * @return array
     */
    public function getScheduledProcessors()
    {
        return $this->scheduledProcessors;
    }

    /**
     * @return array
     */
    public function getRunningProcessors()
    {
        return $this->runningProcessors;
    }

    /**
     * @param QueueProcessor $processor
     * @return string
     */
    private function generateProcessorIdentifier(QueueProcessor $processor)
    {
        return get_class($processor);
    }

    private function scheduleProcessors()
    {
        /** @var QueueProcessor $processor */
        foreach ($this->registeredProcessors as $identifier => $processor)
        {
            if ($this->isSchedulable($processor, $identifier))
            {
                $this->scheduledProcessors[$identifier] = $processor;
            }
        }
    }

    /**
     * @param $identifier
     * @return bool
     */
    private function isRunning($identifier)
    {
        return array_key_exists($identifier, $this->runningProcessors);
    }

    private function isSchedulable(QueueProcessor $processor, $identifier)
    {
        if ($this->isRunning($identifier))
        {
            return false;
        }

        if (!CronExpression::factory($processor->getFrequency())->isDue())
        {
            return false;
        }

        return true;
    }

    private function processScheduledProcessors()
    {
        /** @var QueueProcessor $processor */
        foreach ($this->scheduledProcessors as $identifier => $processor)
        {
            $this->runningProcessors[$identifier] = true;

            $processor->process();

            unset($this->runningProcessors[$identifier]);
        }
    }

}