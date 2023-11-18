<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * App\Models\Ticket
 *
 * @property int $id
 * @property string $subject
 * @property string|null $content
 * @property int|null $ticket_parent_id
 * @property int|null $ticket_category_id
 * @property int|null $status_id
 * @property int $created_by_user_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Ticket> $childTickets
 * @property-read int|null $child_tickets_count
 * @property-read \App\Models\User $createdByUser
 * @property-read Ticket|null $parentTicket
 * @property-read \App\Models\Status|null $status
 * @property-read \App\Models\TicketCategory|null $ticketCategory
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket query()
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereCreatedByUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereTicketCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereTicketParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Ticket extends Model implements HasMedia
{
    use HasFactory,SoftDeletes;
    use InteractsWithMedia;

    public function ticketCategory(): BelongsTo
    {
        return $this->belongsTo(TicketCategory::class);
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function ticketStatus(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'status_id')->modelType(Ticket::class, false);
    }

    public function parentTicket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'ticket_parent_id')->whereNull('ticket_parent_id')->whereNot('id', $this->id);
    }

    public function childTickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'ticket_parent_id', 'id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('files')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/jpg']);
    }

    public function scopeAuthCreatedScope(Builder $builder): Builder
    {
        return $builder->whereHas('createdByUser', fn ($q) => $q->authScope());
    }
}
