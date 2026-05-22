<?php

namespace App\Traits;

use App\Models\Club;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToClub
{
    protected static function bootBelongsToClub()
    {
        static::creating(function ($model) {
            if (auth()->check() && ! $model->club_id) {
                $model->club_id = auth()->user()->club_id;
            }
        });

        static::addGlobalScope('club', function (Builder $builder) {
            if (auth()->check()) {
                $user = auth()->user();
                if (!$user->is_super_admin) {
                    $builder->where($builder->getModel()->getTable() . '.club_id', $user->club_id);
                }
            }
        });
    }

    public function club()
    {
        return $this->belongsTo(Club::class);
    }
}
