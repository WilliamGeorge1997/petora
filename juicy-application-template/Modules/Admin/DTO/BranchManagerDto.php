<?php

namespace Modules\Admin\DTO;

class BranchManagerDto
{
    public $name;
    public $email;
    public $password;
    public $phone;
    public $is_active;
    public $role;

    public function __construct($request)
    {

        $this->name = $request->get('manager_name');
        $this->phone = $request->get('manager_phone');
        $this->email = $request->get('manager_email');
        if ($request->get('manager_password')) $this->password =  bcrypt($request->get('manager_password'));
        $this->role = 2;
        $this->is_active = isset($request['manager_is_active']) ? 1 : 0;
    }

    public function dataFromRequest()
    {
        $data =  json_decode(json_encode($this), true);
        if ($data['password'] == null) unset($data['password']);
        return $data;
    }
}
