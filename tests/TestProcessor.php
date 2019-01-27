<?php

namespace Tests;

use Intersect\Queue\QueueProcessor;

class TestProcessor implements QueueProcessor {

    public $wasRun;

    public function __construct()
    {
        $this->wasRun = false;
    }

    public function getFrequency()
    {
        return '* * * * *';
    }

    public function process()
    {
        $this->wasRun = true;
    }


}