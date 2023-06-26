<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    public function subscriptions()
    {
        return $this->hasMany(PlanSubscription::class);
    }

    public function isFree(): bool
    {
        return $this->price <= 0;
    }

    //-- check if plan is active
    public function isActive(): bool
    {
        return $this->price <= 0;
    }

    //-- check if plan has trial
    public function hasTrial(): bool
    {
        return $this->trial_period && $this->trial_interval;
    }

    //-- activate plan
    public function activate()
    {
        return $this->update(['is_active' => true]);
    }

    //-- deactivate plan
    public function deactivate()
    {
        return $this->update(['is_active' => false]);
    }
}
