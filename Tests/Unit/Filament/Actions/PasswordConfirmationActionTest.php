<?php

declare(strict_types=1);

namespace Modules\User\Tests\Unit\Filament\Actions;

use Modules\User\Filament\Actions\PasswordConfirmationAction;
use Tests\TestCase;

/**
 * Class PasswordConfirmationActionTest.
 *
 * @covers \Modules\User\Filament\Actions\PasswordConfirmationAction
 */
final class PasswordConfirmationActionTest extends TestCase
{
    private PasswordConfirmationAction $passwordConfirmationAction;

    protected function setUp(): void
    {
        parent::setUp();

        /* @todo Correctly instantiate tested object to use it. */
        $this->passwordConfirmationAction = new PasswordConfirmationAction;
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->passwordConfirmationAction);
    }

    public function testCall(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }
}