<?php

namespace GhostZero\Tmi\Commander;

use GetOpt\GetOpt;
use GhostZero\Tmi\Events\Event;
use GhostZero\Tmi\Events\EventHandler;
use GhostZero\Tmi\Events\Irc\PrivmsgEvent;

class Commander
{
    protected EventHandler $eventHandler;

    protected string $event;

    /**
     * @var CommandExecutor[]
     */
    protected array $executors = [];

    public function __construct(EventHandler $eventHandler, string $event)
    {
        $this->eventHandler = $eventHandler;
        $this->event = $event;

        $eventHandler->addHandler($event, fn(Event $e) => $this->commandHandler($e));
    }

    protected function commandHandler(Event $event)
    {
        /** @noinspection PhpPossiblePolymorphicInvocationInspection */
        $command = ($data = explode(' ', $event->message))[0];

        if ($executor = $this->executors[$command] ?? null) {
            $options = new GetOpt(array_map(fn(Option $x) => $x->asGetOptOption(), $executor->getOptions()));
            $options->process($arguments = array_slice($data, 1));
            $executor->handle(new CommandOrigins($event, $arguments, $options->getOptions()));
        }
    }

    public static function register(EventHandler $eventHandler, array $commands): Commander
    {
        $commander = new self($eventHandler, PrivmsgEvent::class);

        foreach ($commands as $command => $executor) {
            $commander->registerCommand($command, $executor);
        }

        return $commander;
    }

    public function registerCommand(string $string, CommandExecutor $executor): void
    {
        $this->executors[$string] = $executor;
    }

    public function getCommands(): array
    {
        return $this->executors;
    }
}