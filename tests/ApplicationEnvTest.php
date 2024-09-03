<?php

namespace Jasny\Tests;

use Jasny\ApplicationEnv;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ApplicationEnvTest extends TestCase
{
    protected ApplicationEnv $env;

    public function setUp(): void
    {
        $this->env = new ApplicationEnv("dev.testers.john");
    }

    public function testToString(): void
    {
        $this->assertEquals("dev.testers.john", (string)$this->env);
    }

    public function testIs(): void
    {
        $this->assertTrue($this->env->is('dev'));
        $this->assertTrue($this->env->is('dev.testers'));
        $this->assertTrue($this->env->is('dev.testers.john'));

        $this->assertFalse($this->env->is('prod'));
        $this->assertFalse($this->env->is('staging.testers'));
        $this->assertFalse($this->env->is('dev.testers.john.temp'));
        $this->assertFalse($this->env->is('dev.test'));
    }

    public static function levelProvider(): array
    {
        return [
            [1, null, ['dev', 'dev.testers', 'dev.testers.john']],
            [1, 3, ['dev', 'dev.testers', 'dev.testers.john']],
            [1, 10, ['dev', 'dev.testers', 'dev.testers.john']],
            [1, 1, ['dev']],
            [1, 2, ['dev', 'dev.testers']],
            [2, null, ['dev.testers', 'dev.testers.john']],
            [0, null, ['', 'dev', 'dev.testers', 'dev.testers.john']],
            [0, 0, ['']],
            [0, 1, ['', 'dev']]
        ];
    }

    #[DataProvider('levelProvider')]
    public function testGetLevels(int $from, ?int $to, array $expected): void
    {
        $levels = $this->env->getLevels($from, $to);

        $this->assertEquals($expected, $levels);
    }

    public function testGetLevelsCallback(): void
    {
        $expected = [
            'settings.yml',
            'settings.dev.yml',
            'settings.dev.testers.yml',
            'settings.dev.testers.john.yml'
        ];

        $levels = $this->env->getLevels(0, null, fn($env) => $env === '' ? "settings.yml" : "settings.$env.yml");

        $this->assertSame($expected, $levels);
    }
}
