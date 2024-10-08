<?php

declare(strict_types=1);

namespace Jasny;

/**
 * Logic around APPLICATION_ENV environment variable
 * Supports sub-environments separated by a dot.
 */
class ApplicationEnv
{
    protected string $env;

    /**
     * ApplicationEnv constructor.
     */
    public function __construct(string $env)
    {
        $this->env = $env;
    }

    /**
     * Cast object to a string
     */
    public function __toString(): string
    {
        return $this->env;
    }


    /**
     * Check if environment matches or is a parent.
     */
    public function is(string $env): bool
    {
        return $this->env === $env || str_starts_with($this->env, "$env.");
    }

    /**
     * Traverse through each level of the application env.
     *
     * @return array<mixed>
     */
    public function getLevels(int $from = 1, ?int $to = null, ?callable $callback = null): array
    {
        $parts = explode('.', $this->env);
        $n = isset($to) ? min(count($parts), $to) : count($parts);

        $levels = [];

        for ($i = $from; $i <= $n; $i++) {
            $level = join('.', array_slice($parts, 0, $i));
            $levels[] = isset($callback) ? $callback($level) : $level;
        }

        return $levels;
    }
}
