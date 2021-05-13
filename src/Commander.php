<?php

namespace GhostZero\Tmi\Commander;

use GetOpt\GetOpt;
use GhostZero\Tmi\Events\Event;
use GhostZero\Tmi\Events\EventHandler;

class Commander
{
    /**
     * @var CommandExecutor[]
     */
    private array $executors = [];

    public function __construct(EventHandler $eventHandler, string $event)
    {
        $eventHandler->addHandler($event, function (Event $event) {
            /** @noinspection PhpPossiblePolymorphicInvocationInspection */
            $command = ($data = explode(' ', $event->message))[0];

            if ($executor = $this->executors[$command] ?? null) {
                $options = new GetOpt(array_map(fn(Option $x) => $x->asGetOptOption(), $executor->getOptions()));
                $options->process($arguments = array_slice($data, 1));
                $executor->handle(new CommandOrigins($event, $arguments, $options->getOptions()));
            }
        });
    }

    public function registerCommand(string $string, CommandExecutor $executor)
    {
        $this->executors[$string] = $executor;
    }
}