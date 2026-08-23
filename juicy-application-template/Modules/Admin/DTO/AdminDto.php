<?php


namespace Modules\Admin\DTO;


class AdminDto
{

    public $name;
    public $email;
    public $password;
    public $phone;
    public $image;
    public $is_active;
    public $role;
    public $branch_id;

    public function __construct($request)
    {

        $this->name = $request->get('name');
        $this->email = $request->get('email');
        $this->role = $request->get('role');
        if ($request->get('password')) $this->password =  bcrypt($request->get('password'));
        $this->phone = $request->get('phone');
        if ($request->hasFile('image')) $this->image   = $request->file('image');
        $this->is_active   = isset($request['is_active']) ? 1 : 0;
        $this->branch_id = $request->get('branch_id');
    }

    public function dataFromRequest()
    {
        $data =  json_decode(json_encode($this), true);
        if ($data['password'] == null) unset($data['password']);
        if ($data['image'] == null) unset($data['image']);
        return $data;
    }

    public function adminDataFromRequest()
    {
        $data =  json_decode(json_encode($this), true);
        $data = array_filter($data);
        $data['role'] = 1;
        return $data;
    }
}
