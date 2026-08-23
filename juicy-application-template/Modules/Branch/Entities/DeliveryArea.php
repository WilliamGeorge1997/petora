<?php

namespace Modules\Branch\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Branch\Entities\Branch;
use Spatie\Activitylog\Traits\LogsActivity;

class DeliveryArea extends Model
{
    use HasFactory, LogsActivity;

    protected static $logName  = 'DeliveryArea';
    protected static $logAttributes = ['*'];
    protected static $ignoreChangedAttributes = ['updated_at'];
    protected static $logOnlyDirty = true;

    protected $fillable = ['branch_id', 'title', 'price'];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
