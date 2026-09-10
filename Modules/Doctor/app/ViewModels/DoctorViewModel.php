<?php

namespace Modules\Doctor\ViewModels;

use Modules\Clinic\Services\ClinicService;

class DoctorViewModel
{
    public function clinics()
    {
        return (new ClinicService)->active(columns: ['id', 'title']);
    }
}
