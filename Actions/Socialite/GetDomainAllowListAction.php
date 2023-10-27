<?php
/**
 * @see https://github.com/DutchCodingCompany/filament-socialite
 */

declare(strict_types=1);

namespace Modules\User\Actions\Socialite;

use Exception;
use Spatie\QueueableAction\QueueableAction;

class GetDomainAllowListAction
{
    use QueueableAction;

    /**
     * Execute the action.
     */
    public function execute(): string|array
    {
        $res = config('filament-socialite.domain_allowlist');
        if (\is_string($res)) {
            return $res;
        }
        if (\is_array($res)) {
            return $res;
        }
        throw new Exception('WIP');
    }
}
