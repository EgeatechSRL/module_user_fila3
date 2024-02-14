<?php

declare(strict_types=1);

namespace Modules\User\Console\Commands;

use Illuminate\Console\Command;
use Modules\Xot\Datas\XotData;

class ShowTenantListCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:tenant-list';

    /**
     * The console command description.
     */
    protected string $description = 'Visualizza lista tenant';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $modelClass = XotData::make()->getTenantClass();

        $map = static function ($row) {
            return $row->toArray();
        };

        $rows = $modelClass::get()->map($map);

        if (\count($rows) > 0) {
            $headers = array_keys($rows[0]);

            $this->newLine();
            $this->table($headers, $rows);
            $this->newLine();
        } else {
            $this->newLine();
            $this->warn('⚡ No Tenants ['.$modelClass.']');
            $this->newLine();
        }
    }
}