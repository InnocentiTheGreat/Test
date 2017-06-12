<?php

class Event {

    protected $events = [];

    public function listen($name, $handle)
    {
        $this->events[$name][] = $handle;
    }

    public function fire($name)
    {
        if(! array_key_exists($name, $this->events)) {
            return false;
        }

        foreach ($this->events[$name] as $event) {
            $event();
        }

        return true;

    }

}

$event = new Event;

$event->listen('subscribed', function () {
   var_dump('Handling it');
});

$event->listen('subscribed', function () {
    var_dump('Handling it again');
});

$event->fire('subscribed');
