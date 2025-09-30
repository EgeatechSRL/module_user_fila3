<?php

declare(strict_types=1);

namespace Modules\User\Filament\Resources\BaseProfileResource\Pages;

use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Arr;
use Webmozart\Assert\Assert;
use Modules\Xot\Datas\XotData;
use Filament\Actions\CreateAction;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Columns\Layout\Stack;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Database\Eloquent\Collection;
use Modules\Xot\Filament\Pages\XotBaseListRecords;
use Modules\User\Filament\Resources\BaseProfileResource;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Modules\User\Filament\Actions\Profile\ChangeProfilePasswordAction;

class ListProfiles extends XotBaseListRecords
{
    protected static string $resource = BaseProfileResource::class;
    protected static bool $canChangePassword = true;
    protected static bool $canToggleActive = true;

    protected function shouldShowChangePasswordAction(): bool
    {
        return static::$canChangePassword;
    }

    protected function shouldShowToggleActiveAction(): bool
    {
        return static::$canToggleActive;
    }

    public function getModelLabel(): string
    {
        return static::trans('navigation.name');
    }

    public function getPluralModelLabel(): string
    {
        return static::trans('navigation.plural');
    }

    public function getGridTableColumns(): array
    {
        return [
            Stack::make([
                'user_name' => TextColumn::make('user.name')
                    ->sortable()
                    // ->searchable()
                    ->default(
                        function ($record) {
                            $user = $record->user;
                            $user_class = XotData::make()->getUserClass();
                            if (null === $user) {
                                /** @var \Modules\Xot\Contracts\UserContract */
                                $user = XotData::make()->getUserByEmail($record->email);
                            }
                            if (null === $user) {
                                $data = $record->toArray();
                                $user_data = Arr::except($data, ['id']);
                                /** @var \Modules\Xot\Contracts\UserContract */
                                $user = $user_class::create($user_data);
                            }
                            $record->update(['user_id' => $user->id]);

                            return $user->name;
                        }
                    ),
                'first_name' => TextColumn::make('first_name')
                    ->sortable()
                    ->searchable(),
                'last_name' => TextColumn::make('last_name')
                    ->sortable()
                    ->searchable(),
                'email' => TextColumn::make('email')
                    ->sortable()
                    ->searchable(),
                'is_active' => IconColumn::make('is_active')
                    ->boolean(),
                'photo' => SpatieMediaLibraryImageColumn::make('photo')
                    ->collection('profile'),
            ]),
        ];
    }

    public function getListTableColumns(): array
    {
        return [
            'user_name' => TextColumn::make('user.name')
                ->sortable()
                // ->searchable()
                ->default(
                    function ($record) {
                        $user = $record->user;
                        $user_class = XotData::make()->getUserClass();
                        if (null === $user) {
                            if (null == $record->email) {
                                $record->update(['email' => fake()->email()]);
                            }
                            try {
                                /** @var \Modules\Xot\Contracts\UserContract */
                                $user = XotData::make()->getUserByEmail($record->email);
                            } catch (\Exception $e) {
                                // $record->delete();

                                return '--';
                            }
                        }
                        if (null === $user) {
                            $data = $record->toArray();
                            $user_data = Arr::except($data, ['id']);
                            /** @var \Modules\Xot\Contracts\UserContract */
                            $user = $user_class::create($user_data);
                        }
                        $record->update(['user_id' => $user->id]);

                        return $user->name;
                    }
                ),
            'first_name' => TextColumn::make('first_name')
                ->sortable()
                ->searchable(),
            'last_name' => TextColumn::make('last_name')
                ->sortable()
                ->searchable(),
            'email' => TextColumn::make('email')
                ->sortable()
                ->searchable(),
            'is_active' => IconColumn::make('is_active')
                ->boolean(),
            'photo' => SpatieMediaLibraryImageColumn::make('photo')
                ->collection('profile'),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            ChangeProfilePasswordAction::make()
            ->visible($this->shouldShowChangePasswordAction()),
            ...parent::getTableActions(),
        ];
    }

    protected function getTableBulkActions(): array
    {
        return [
            // Tables\Actions\BulkActionGroup::make([
            //    Tables\Actions\DeleteBulkAction::make(),
            /*
                Tables\Actions\BulkAction::make('refresh-profiles')
                    ->requiresConfirmation()
                    ->action(function (Collection $records) {
                        $users = User::all();

                        foreach ($users as $user) {
                            Profile::firstOrCreate(
                                ['user_id' => $user->id, 'email' => $user->email],
                                ['credits' => 1000]
                            );
                        }
                    }),
                */
            // ]),
            Tables\Actions\DeleteBulkAction::make()
            ->visible($this->shouldShowDeleteAction()),
            BulkAction::make('bulk_activate')
                ->action(
                    function (Collection $collection) {
                        $collection
                            ->chunk(20)
                            ->each
                            ->each(
                                function ($user): void {
                                    Assert::isInstanceOf($user, Model::class, '['.__LINE__.']['.class_basename($this).']');
                                    $user->update(['is_active' => true]);
                                }
                            );
                    }
                )
                ->visible($this->shouldShowToggleActiveAction()),

            BulkAction::make('bulk_inactivate')
                ->action(
                    function (Collection $collection) {
                        $collection
                            ->chunk(20)
                            ->each
                            ->each(
                                function ($user): void {
                                    Assert::isInstanceOf($user, Model::class, '['.__LINE__.']['.class_basename($this).']');
                                    $user->update(['is_active' => true]);
                                }
                            );
                    }
                )
                ->visible($this->shouldShowToggleActiveAction()),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            TernaryFilter::make('is_active')
                ->placeholder(static::trans('filters.is_active.all'))
                ->trueLabel(static::trans('filters.is_active.active'))
                ->falseLabel(static::trans('filters.is_active.inactive'))
                ->queries(
                    true: static fn (Builder $query) => $query->where('is_active', '=', true),
                    false: static fn (Builder $query) => $query->where('is_active', '=', false),
                )
                ->label(static::trans('fields.is_active')),
        ];
    }

    public function table(Table $table): Table
    {
        $table = parent::table($table);

        return $table
            ->filtersLayout(FiltersLayout::AboveContentCollapsible)
            ->filtersFormColumns(1)
            ->paginated([10, 25, 50]);
    }
}
