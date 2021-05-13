<?php

namespace GhostZero\Tmi\Commander;

use GhostZero\Tmi\Events\Event;

class CommandOrigins
{
    public Event $event;
    public array $arguments;
    public array $options;

    public function __construct(Event $event, array $arguments, array $options)
    {
        $this->event = $event;
        $this->arguments = $arguments;
        $this->options = $options;
    }
}