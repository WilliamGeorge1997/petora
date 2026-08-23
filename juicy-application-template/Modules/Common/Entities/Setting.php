<?php

namespace Modules\Common\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;

    protected $table = "settings";
    protected $fillable = ['value'];


    public function getValueAttribute($value)
    {
        if ($value != null && $value != '' && $this->type == 'file') {
            return asset('uploads/setting/' . $value);
        }
        return $value;
    }
}
