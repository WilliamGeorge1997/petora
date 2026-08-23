<?php

namespace Modules\Branch\DTO;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class BranchQrSettingDto
{
    public bool $isQrImageEnabled;
    public string $dotType;
    public string $dotColor;
    public string $dotColorType;
    public ?string $dotColor2;
    public string $dotGradientType;
    public int $dotGradientRotation;
    public string $bgColor;
    public string $bgColorType;
    public ?string $bgColor2;
    public string $bgGradientType;
    public int $bgGradientRotation;
    public string $cornerSquareType;
    public string $cornerSquareColor;
    public string $cornerSquareColorType;
    public ?string $cornerSquareColor2;
    public string $cornerSquareGradientType;
    public int $cornerSquareGradientRotation;
    public string $cornerDotType;
    public string $cornerDotColor;
    public string $cornerDotColorType;
    public ?string $cornerDotColor2;
    public string $cornerDotGradientType;
    public int $cornerDotGradientRotation;
    public int $width;
    public int $height;
    public int $margin;
    public bool $hideBackgroundDots;
    public float $imageSize;
    public int $imageMargin;
    public bool $useBranchImg;
    public int $typeNumber;
    public string $mode;
    public string $errorCorrectionLevel;
    public ?UploadedFile $qrImage;

    public function __construct(Request $request)
    {
        $this->isQrImageEnabled = $request->boolean('is_qr_image_enabled');
        $this->dotType = $request->input('dot_type', 'square');
        $this->dotColor = $request->input('dot_color', '#000000');
        $this->dotColorType = $request->input('dot_color_type', 'single');
        $this->dotColor2 = $request->input('dot_color_2');
        $this->dotGradientType = $request->input('dot_gradient_type', 'linear');
        $this->dotGradientRotation = (int) $request->input('dot_gradient_rotation', 0);
        $this->bgColor = $request->input('bg_color', '#ffffff');
        $this->bgColorType = $request->input('bg_color_type', 'single');
        $this->bgColor2 = $request->input('bg_color_2');
        $this->bgGradientType = $request->input('bg_gradient_type', 'linear');
        $this->bgGradientRotation = (int) $request->input('bg_gradient_rotation', 0);
        $this->cornerSquareType = $request->input('corner_square_type', 'square');
        $this->cornerSquareColor = $request->input('corner_square_color', '#000000');
        $this->cornerSquareColorType = $request->input('corner_square_color_type', 'single');
        $this->cornerSquareColor2 = $request->input('corner_square_color_2');
        $this->cornerSquareGradientType = $request->input('corner_square_gradient_type', 'linear');
        $this->cornerSquareGradientRotation = (int) $request->input('corner_square_gradient_rotation', 0);
        $this->cornerDotType = $request->input('corner_dot_type', 'square');
        $this->cornerDotColor = $request->input('corner_dot_color', '#000000');
        $this->cornerDotColorType = $request->input('corner_dot_color_type', 'single');
        $this->cornerDotColor2 = $request->input('corner_dot_color_2');
        $this->cornerDotGradientType = $request->input('corner_dot_gradient_type', 'linear');
        $this->cornerDotGradientRotation = (int) $request->input('corner_dot_gradient_rotation', 0);
        $this->width = (int) $request->input('width', 300);
        $this->height = (int) $request->input('height', 300);
        $this->margin = (int) $request->input('margin', 0);
        $this->hideBackgroundDots = $request->boolean('hide_background_dots');
        $this->imageSize = (float) $request->input('image_size', 0.40);
        $this->imageMargin = (int) $request->input('image_margin', 3);
        $this->useBranchImg = $request->boolean('use_branch_img');
        $this->typeNumber = (int) $request->input('type_number', 0);
        $this->mode = $request->input('mode', 'Byte');
        $this->errorCorrectionLevel = $request->input('error_correction_level', 'H');
        $this->qrImage = $request->file('qr_image');
    }

    public function toArray(): array
    {
        return [
            'is_qr_image_enabled' => $this->isQrImageEnabled,
            'dot_type' => $this->dotType,
            'dot_color' => $this->dotColor,
            'dot_color_type' => $this->dotColorType,
            'dot_color_2' => $this->dotColor2,
            'dot_gradient_type' => $this->dotGradientType,
            'dot_gradient_rotation' => $this->dotGradientRotation,
            'bg_color' => $this->bgColor,
            'bg_color_type' => $this->bgColorType,
            'bg_color_2' => $this->bgColor2,
            'bg_gradient_type' => $this->bgGradientType,
            'bg_gradient_rotation' => $this->bgGradientRotation,
            'corner_square_type' => $this->cornerSquareType,
            'corner_square_color' => $this->cornerSquareColor,
            'corner_square_color_type' => $this->cornerSquareColorType,
            'corner_square_color_2' => $this->cornerSquareColor2,
            'corner_square_gradient_type' => $this->cornerSquareGradientType,
            'corner_square_gradient_rotation' => $this->cornerSquareGradientRotation,
            'corner_dot_type' => $this->cornerDotType,
            'corner_dot_color' => $this->cornerDotColor,
            'corner_dot_color_type' => $this->cornerDotColorType,
            'corner_dot_color_2' => $this->cornerDotColor2,
            'corner_dot_gradient_type' => $this->cornerDotGradientType,
            'corner_dot_gradient_rotation' => $this->cornerDotGradientRotation,
            'width' => $this->width,
            'height' => $this->height,
            'margin' => $this->margin,
            'hide_background_dots' => $this->hideBackgroundDots,
            'image_size' => $this->imageSize,
            'image_margin' => $this->imageMargin,
            'use_branch_img' => $this->useBranchImg,
            'type_number' => $this->typeNumber,
            'mode' => $this->mode,
            'error_correction_level' => $this->errorCorrectionLevel,
        ];
    }
}
