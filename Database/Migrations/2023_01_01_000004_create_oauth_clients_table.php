<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\User\Models\User;
use Modules\Xot\Database\Migrations\XotBaseMigration;

class CreateOauthClientsTable extends XotBaseMigration
{
    public function up(): void
    {
        $this->tableCreate(
            static function (Blueprint $table): void {
                // $table->bigIncrements('id');
                $table->uuid('id')->primary();
                // $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->foreignIdFor(User::class, 'user_id')->nullable()->index();
                $table->string('name');
                $table->string('secret', 100)->nullable();
                $table->string('provider')->nullable();
                $table->text('redirect');
                $table->boolean('personal_access_client');
                $table->boolean('password_client');
                $table->boolean('revoked');
                $table->timestamps();
            }
        );

        // -- UPDATE --
        $this->tableUpdate(
            function (Blueprint $table): void {
                if ($this->getColumnType('id') !== 'string') {
                    $table->uuid('id')->change();  // is  just primary
                }

                $this->updateUser($table);
            }
        );
    }
}
