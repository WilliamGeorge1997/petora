<?php

namespace Modules\Branch\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Branch\Entities\Branch;

class BranchQrSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'dot_type',
        'dot_color',
        'dot_color_type',
        'dot_color_2',
        'dot_gradient_type',
        'dot_gradient_rotation',
        'bg_color',
        'bg_color_type',
        'bg_color_2',
        'bg_gradient_type',
        'bg_gradient_rotation',
        'corner_square_type',
        'corner_square_color',
        'corner_square_color_type',
        'corner_square_color_2',
        'corner_square_gradient_type',
        'corner_square_gradient_rotation',
        'corner_dot_type',
        'corner_dot_color',
        'corner_dot_color_type',
        'corner_dot_color_2',
        'corner_dot_gradient_type',
        'corner_dot_gradient_rotation',
        'width',
        'height',
        'margin',
        'qr_image',
        'is_qr_image_enabled',
        'hide_background_dots',
        'image_size',
        'image_margin',
        'use_branch_img',
        'type_number',
        'mode',
        'error_correction_level',
    ];

    protected function casts(): array
    {
        return [
            'hide_background_dots' => 'boolean',
            'is_qr_image_enabled' => 'boolean',
            'use_branch_img' => 'boolean',
            'image_size' => 'float',
            'width' => 'integer',
            'height' => 'integer',
            'margin' => 'integer',
            'image_margin' => 'integer',
            'type_number' => 'integer',
            'dot_gradient_rotation' => 'integer',
            'bg_gradient_rotation' => 'integer',
            'corner_square_gradient_rotation' => 'integer',
            'corner_dot_gradient_rotation' => 'integer',
        ];
    }

    public function getQrImageAttribute($value)
    {
        if ($value != null && $value != '') {
            return asset('uploads/branch/setting/' . $value);
        }
        return $value;
    }
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
