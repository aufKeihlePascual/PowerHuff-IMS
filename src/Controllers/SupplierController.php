<?php

namespace App\Controllers;

class SupplierController extends BaseController
{

    protected $userModel, $adminModel, $supplierModel;

    public function __construct()
    {
        $this->userModel = new \App\Models\User();
        $this->adminModel = new \App\Models\Admin();
        $this->supplierModel = new \App\Models\Supplier();
    }

    

    

}
