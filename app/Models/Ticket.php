<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


class Ticket extends Model
{
    use HasFactory,SoftDeletes;

    public function ticketCategory(): BelongsTo
    {
        return $this->belongsTo(TicketCategory::class);
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class)->modelType(Ticket::class, false);
    }

    public function parentTicket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'ticket_parent_id')->whereNull('ticket_parent_id')->whereNot('id', $this->id);
    }

    public function childTickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'ticket_parent_id', 'id');
    }
}
