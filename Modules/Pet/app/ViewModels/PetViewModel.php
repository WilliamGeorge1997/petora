<?php

namespace Modules\Pet\ViewModels;

use Modules\Client\Models\Client;
use Modules\Client\Services\ClientService;
use Modules\Pet\Services\PetTypeService;

class PetViewModel
{
    public function clients()
    {
        return (new ClientService(new Client))->active(columns: ['id', 'name', 'phone']);
    }

    public function petTypes()
    {
        return (new PetTypeService)->active(columns: ['id', 'title']);
    }
}
