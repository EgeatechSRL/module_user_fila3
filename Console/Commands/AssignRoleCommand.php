<?php

declare(strict_types=1);

namespace Modules\User\Console\Commands;

use Illuminate\Console\Command;

use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\text;

use Modules\User\Models\Role;
use Modules\User\Models\User;
use Symfony\Component\Console\Input\InputOption;
use Webmozart\Assert\Assert;

class AssignRoleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'user:assign-role';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign a module to user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $email = text('email ?');
        Assert::notNull($user = User::firstWhere(['email' => $email]));
        $opts = Role::all()
            ->pluck('name', 'name')
            // ->toArray()
        ;

        $rows = multiselect(
            label: 'What roles',
            options: $opts,
            required: true,
            scroll: 10,
            // validate: function (array $values) {
            //  return ! \in_array(\count($values), [1, 2], true)
            //    ? 'A maximum of two'
            //  : null;
            // }
        );

        foreach ($rows as $row) {
            $role = Role::firstOrCreate(['name' => $row]);
            $user->assignRole($role);
        }
        $this->info(implode(', ', $rows).' assigned to '.$email);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     *               protected function getArguments()
     *               {
     *               return [
     *               ['example', InputArgument::REQUIRED, 'An example argument.'],
     *               ];
     *               }
     */
    /**
     * Get the console command options.
     */
    protected function getOptions(): array
    {
        return [
            ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }
}
