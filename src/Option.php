<?php

namespace GhostZero\Tmi\Commander;

use GetOpt\Option as GetOptOption;

class Option
{
    const NO_ARGUMENT = ':noArg';
    const REQUIRED_ARGUMENT = ':requiredArg';
    const OPTIONAL_ARGUMENT = ':optionalArg';
    const MULTIPLE_ARGUMENT = ':multipleArg';

    private GetOptOption $option;

    /**
     * Creates a new option.
     *
     * @param string $short The option's short name (one of [a-zA-Z0-9?!§$%#]) or null for long-only options
     * @param string|null $long The option's long name (a string of 1+ letter/digit/_/- characters, starting with a letter
     *                        or digit) or null for short-only options
     * @param string $mode Whether the option can/must have an argument (optional, defaults to no argument)
     */
    public function __construct(string $short, string $long = null, string $mode = self::NO_ARGUMENT)
    {
        $this->option = new GetOptOption($short, $long, $mode);
    }

    /**
     * Fluent interface for constructor so options can be added during construction
     *
     * @param string $short
     * @param string|null $long
     * @param string $mode
     * @return self
     * @see self::__construct()
     */
    public static function create(string $short, string $long = null, string $mode = self::NO_ARGUMENT): self
    {
        return new static($short, $long, $mode);
    }

    /**
     * @return GetOptOption
     * @internal
     */
    public function asGetOptOption(): GetOptOption
    {
        return $this->option;
    }
}