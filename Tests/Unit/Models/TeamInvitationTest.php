<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\User\Models;

use Modules\User\Models\TeamInvitation;
use Tests\TestCase;

/**
 * Class TeamInvitationTest.
 *
 * @covers \Modules\User\Models\TeamInvitation
 */
final class TeamInvitationTest extends TestCase
{
    private TeamInvitation $teamInvitation;

    protected function setUp(): void
    {
        parent::setUp();

        /* @todo Correctly instantiate tested object to use it. */
        $this->teamInvitation = new TeamInvitation;
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->teamInvitation);
    }

    public function testTeam(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }
}