<?php

namespace Modules\Common\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['value'];

    public function getValueAttribute($value)
    {
        if ($value != null && $value != '' && $this->type == 'file') {
            return asset('storage/uploads/setting/' . $value);
        }
        return $value;
    }
}
