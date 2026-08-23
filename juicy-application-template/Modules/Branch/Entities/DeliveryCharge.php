<?php

namespace Modules\Branch\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;

class DeliveryCharge extends Model
{
    use HasFactory,LogsActivity;

    protected static $logName  = 'DeliveryCharge';
    protected static $logAttributes = ['*'];
    protected static $ignoreChangedAttributes = ['updated_at'];
    protected static $logOnlyDirty = true;

    protected $fillable = ['branch_id','distance','price'];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
