<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
      
        if (request()->is('admin*')) {
            
            if (auth()->check()) {
                $user = auth()->user();

                if (method_exists($user, 'hasRole') && $user->hasRole('super-admin')) {
                    return;
                }

                $builder->where($model->getTable() . '.company_id', $user->company_id);
            }
        }
    }
}