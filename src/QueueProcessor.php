<?php

namespace Intersect\Queue;

interface QueueProcessor {

    public function getFrequency();

    public function process();

}