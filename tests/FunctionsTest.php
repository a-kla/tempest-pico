<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

use function TempestPico\Support\Html\composeStr;

// use function TempestPico\Support\toHtml;

/**
 * @internal
 */
class FunctionsTest extends TestCase
{
    #[Test]
    public function composeStr(): void
    {
        $useBorder = true;
        $isAdmin = static fn (): bool => false;

        $this->assertSame(
            'border border-color-blue',
            composeStr([
                'border' => $useBorder,
                // @phpstan-ignore booleanAnd.rightAlwaysFalse
                'border-color-red' => $useBorder && $isAdmin(),
                // @phpstan-ignore booleanNot.alwaysTrue
                'border-color-blue' => $useBorder && ! $isAdmin(),
            ]),
        );

        $isAdmin = static fn (): bool => true;

        $this->assertSame(
            'border border-color-red',
            composeStr([
                'border' => $useBorder,
                // @phpstan-ignore booleanAnd.rightAlwaysTrue
                'border-color-red' => $useBorder && $isAdmin(),
                // @phpstan-ignore booleanNot.alwaysFalse, booleanAnd.alwaysFalse
                'border-color-blue' => $useBorder && ! $isAdmin(),
            ]),
        );

        $useBorder = false;
        $this->assertSame(
            false,
            composeStr([
                'border' => $useBorder,
                // @phpstan-ignore-next-line
                'border-color-red' => $useBorder && $isAdmin(),
                // @phpstan-ignore-next-line
                'border-color-blue' => $useBorder && ! $isAdmin(),
            ]),
        );

        $this->assertSame(
            'border border-solid border-color-blue',
            composeStr([
                'border',
                'border-solid',
                'border-color-blue',
            ]),
        );
    }
}
