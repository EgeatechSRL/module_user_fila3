<?php

declare(strict_types=1);

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Modules\User\Models\ModelHasRole.
 *
 * @property string                          $id
 * @property string                          $uuid
 * @property string                          $role_id
 * @property string                          $model_type
 * @property string                          $model_id
 * @property string|null                     $team_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null                     $updated_by
 * @property string|null                     $created_by
 *
 * @method static \Modules\User\Database\Factories\ModelHasRoleFactory factory($count = null, $state = [])
 * @method static Builder|ModelHasRole                                 newModelQuery()
 * @method static Builder|ModelHasRole                                 newQuery()
 * @method static Builder|ModelHasRole                                 query()
 * @method static Builder|ModelHasRole                                 whereCreatedAt($value)
 * @method static Builder|ModelHasRole                                 whereCreatedBy($value)
 * @method static Builder|ModelHasRole                                 whereId($value)
 * @method static Builder|ModelHasRole                                 whereModelId($value)
 * @method static Builder|ModelHasRole                                 whereModelType($value)
 * @method static Builder|ModelHasRole                                 whereRoleId($value)
 * @method static Builder|ModelHasRole                                 whereTeamId($value)
 * @method static Builder|ModelHasRole                                 whereUpdatedAt($value)
 * @method static Builder|ModelHasRole                                 whereUpdatedBy($value)
 * @method static Builder|ModelHasRole                                 whereUuid($value)
 *
 * @mixin \Eloquent
 */
class ModelHasRole extends BaseMorphPivot
{
    use HasUuids;

    public $incrementing = false;

    // use Traits\UuidTrait;
    /**
     * @var array<int, string>
     */
    protected $fillable = ['id', 'role_id', 'model_type', 'model_id', 'team_id'];

    protected $connection = 'user';

    protected $casts = [
        'id' => 'string',
    ];

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    /**
     * Create a new pivot model from raw values returned from a query.
     *
     * @param  array  $attributes
     * @param  string  $table
     * @param  bool  $exists
     * @return static
     *
     * @throws \Exception
     */
    public static function fromRawAttributes(Model $parent, $attributes, $table, $exists = false)
    {
        // https://laracasts.com/discuss/channels/eloquent/generating-custom-id-uuid-for-many-to-many-relationship-pivot-table
        // https://www.appsloveworld.com/php/394/laravel-eloquent-uuid-in-a-pivot-table
        dddx('a');
        if (! $exists && ! \array_key_exists('id', $attributes)) {
            $attributes['id'] = Str::uuid(); // Uuid::generate()->string;
        }

        return parent::fromRawAttributes($parent, $attributes, $table, $exists);
    }
}
