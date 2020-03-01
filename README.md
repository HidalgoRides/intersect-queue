# Intersect Queue
**Intersect Queue** is a queue processing system that allows cron-like processors to be defined and ran using a single script entry point.

## Changelog
See `CHANGELOG.md` for all released features/bug fixes


## Installation via Composer
```
composer require hidalgo-rides/intersect-queue
```

## Usage
### Create custom processors
Create your own custom processors to perform any actions you want to perform at reoccurring times
```php
<?php

class SampleProcessor implements \Intersect\Queue\QueueProcessor {
    
    public function getFrequency()
    {
        // every five minutes
        return '*/5 * * * *';
    }

    public function process()
    {
        // do custom things here like query database records to
        // perform actions, update stats, or whatever you want
    }
    
}
```

### Register and run processors with the QueueManager
Create a script to initialize the QueueManager and register all your custom processors
```php
<?php

// initialize QueueManager
$queueManager = new \Intersect\Queue\QueueManager();

// register processor
$queueManager->registerProcessor(new SampleProcessor());

// run QueueManager
$queueManager->run();
```

### Setup cron to invoke QueueManager script every minute
```
* * * * * php /path/to/queue/manager/script.php >> /dev/null 2>&1
```

## License
Intersect Framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).