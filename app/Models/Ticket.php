<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Ticket extends Model implements HasMedia
{
    use InteractsWithMedia;
    use SoftDeletes;

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
