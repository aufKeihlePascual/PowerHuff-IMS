<?php

namespace App\Controllers;

class SupplierController extends BaseController
{
    protected $supplierModel;

    public function __construct()
    {
        $this->supplierModel = new \App\Models\Supplier();
    }

    public function showSupplierManagement()
    {
        // Fetch all suppliers
        $suppliers = $this->supplierModel->getAllSuppliers();
        $data = [
            'title' => 'Suppliers',
            'suppliers' => $suppliers,
            'username' => $_SESSION['username'],

        ];
        return $this->render('supplier-management', $data);
        exit;
    }

    public function createSupplier()
    {
        // Get the data from the form (assuming data is sent via POST)
        $data = [
            'supplier_name' => $_POST['supplier_name'],
            'product_categoryID' => $_POST['product_categoryID'],
            'contact_number' => $_POST['contact_number'],
            'email' => $_POST['email'],
            'address' => $_POST['address'],
            'created_on' => date('Y-m-d H:i:s')
        ];

        if ($this->supplierModel->createSupplier($data)) {
            header('Location: /dashboard/suppliers');
        } else {
            echo "Error creating supplier.";
        }
    }

    public function updateSupplier($id)
    {
        // Update supplier details
        $data = [
            'supplier_name' => $_POST['supplier_name'],
            'product_categoryID' => $_POST['product_categoryID'],
            'contact_number' => $_POST['contact_number'],
            'email' => $_POST['email'],
            'address' => $_POST['address'],
        ];

        if ($this->supplierModel->updateSupplier($id, $data)) {
            header('Location: /dashboard/suppliers');
        } else {
            echo "Error updating supplier.";
        }
    }

    public function deleteSupplier($id)
    {
        if ($this->supplierModel->deleteSupplier($id)) {
            header('Location: /dashboard/suppliers');
        } else {
            echo "Error deleting supplier.";
        }
    }
}
