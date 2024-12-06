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

    public function showAddSupplierPage()
    {
        $supplier = $this->supplierModel->findBySupplierID($supplier_id);
        $supplier = $this->supplierModel->findBySupplierID($supplier_id);
        
        $data = [
            'title' => 'Add Supplier',
            //'supplier_id' => $supplier['supplier_id'],
            //'supplier_name' => $supplier['supplier_name'],

            'username' => $_SESSION['username'],
        ];
        return $this->render('add-supplier', $data);  // Render the add-supplier view
    }

    public function showEditUserPage($user_id)
    {
        $user = $this->userModel->findByUserID($user_id);

        if (!$user) {
            echo 'User not found.';
            exit;
        }

        $data = [
            'title' => 'Edit User',
            'user_id' => $user['user_id'],
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'username' => $user['username'],
            'selectedAdmin' => $user['role'] === 'Admin' ? 'selected' : '',
            'selectedInventoryManager' => $user['role'] === 'Inventory_Manager' ? 'selected' : '',
            'selectedProcurementManager' => $user['role'] === 'Procurement_Manager' ? 'selected' : '',
        ];

        return $this->render('edit-user-page', $data);
    }

}
