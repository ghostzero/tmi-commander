<?php

namespace GhostZero\Tmi\Commander;

abstract class CommandExecutor
{
    public function getOptions(): array
    {
        return [];
    }

    abstract public function handle(CommandOrigins $origins): bool;
}