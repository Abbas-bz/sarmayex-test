<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class TaskFilter
{
    public static function handle(Builder $builder)
    {
        if(Auth::user()->isAdministrator() && request()->has('userId')){
            $builder->whereHas('users', function (Builder $query) {
                $query->where('user_id', request()->get('userId'));
            });
        }
    }
}
