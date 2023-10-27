<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\TicketCategory
 *
 * @property int $id
 * @property string $name
 * @property string|null $label
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|TicketCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketCategory onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketCategory whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketCategory whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketCategory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketCategory withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketCategory withoutTrashed()
 *
 * @mixin \Eloquent
 */
class TicketCategory extends Model
{
    use HasFactory,SoftDeletes;
}
