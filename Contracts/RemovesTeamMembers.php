<?php

declare(strict_types=1);

namespace Modules\User\Contracts;

use Modules\Xot\Contracts\UserContract;

/**
 * ---.
 */
interface RemovesTeamMembers
{
    public function remove(UserContract $user, TeamContract $teamContract, UserContract $teamMember): void;
}