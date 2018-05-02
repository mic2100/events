# Events Library
[![Build Status](https://travis-ci.org/mic2100/events.png?branch=master)](https://travis-ci.org/mic2100/events)
## Instructions
### Events
Events can either extend the `AbstractEvent` class or implement the `EventInterface`
Some simple examples
```php
class SampleEventOne extends AbstractEvent
{
    protected $handle = 'sample-event-one';

    public function handle(array $params = null) : bool
    {
        return true;
    }
}
class SampleEventTwo extends AbstractEvent
{
    protected $handle = 'sample-event-two';

    public function handle(array $params = null) : bool
    {
        return true;
    }
}
```

### Dispatching
When you have created some events you can add them to the dispatcher.
With this you can trigger events using the handle or using a wildcard to trigger multiple events.

```php
$dispatcher = new Dispatcher;
$dispatcher->addEvent(new SampleEventOne);
$dispatcher->addEvent(new SampleEventTwo);
$dispatcher->addEvent(new SampleEventTwo, 'custom-handle-one');

$dispatcher->trigger('custom-handle-one'); //triggers one event
$dispatcher->trigger('sample-event*'); //triggers two events
```
