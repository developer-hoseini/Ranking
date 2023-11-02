<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;


class Profile extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'user_id',
        'fname',
        'lname',
        'bio',
        'state_id',
        'birth_date',
        'gender',
        'mobile',
        'code_melli',
        'en_fullname',
        'bank_account',
        'account_holder_name',
        'show_mobile',
        'likes',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this?->fname.' '.$this?->lname
        );
    }
}
