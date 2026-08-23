<?php

namespace Modules\Admin\Entities;

use Modules\Branch\Entities\Branch;
use Modules\Order\Entities\History;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Traits\CausesActivity;
use Modules\Notification\Entities\Notification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable implements JWTSubject
{
    use HasFactory, HasRoles, CausesActivity, LogsActivity;

    protected $fillable = ['name', 'email', 'phone', 'image', 'password', 'is_active', 'branch_id'];
    protected $hidden = ['password'];
    protected static $logName  = 'Admin';
    protected static $logAttributes = ['*'];
    protected static $ignoreChangedAttributes = ['updated_at'];
    protected static $logOnlyDirty = true;
    protected static $submitEmptyLogs = false;

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function histories()
    {
        return $this->morphMany(History::class, 'historible');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
