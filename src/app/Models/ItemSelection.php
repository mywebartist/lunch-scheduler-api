<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ItemSelection
 *
 * @property int $id
 * @property int $user_id
 * @property int $schedule_id
 * @property int $item_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\ItemSelectionFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemSelection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ItemSelection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ItemSelection query()
 * @method static \Illuminate\Database\Eloquent\Builder|ItemSelection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemSelection whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemSelection whereItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemSelection whereScheduleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemSelection whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemSelection whereUserId($value)
 * @mixin \Eloquent
 */
class ItemSelection extends Model
{
    use HasFactory;
}
