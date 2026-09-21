<?php

namespace Modules\Common\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Setting extends Model
{
    use HasFactory, HasTranslations;

    public array $translatable = ['display'];

    protected $fillable = ['key', 'display', 'value', 'type'];

    public function getValueAttribute(?string $value): ?string
    {
        if ($value != null && $value != '' && $this->type == 'file') {
            return asset('storage/uploads/setting/'.$value);
        }

        return $value;
    }
}
