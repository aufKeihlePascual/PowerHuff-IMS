<?php

namespace App\Controllers;

class SupplierController extends BaseController
{
    protected $userModel, $supplierModel;

    public function __construct()
    {
        $this->supplierModel = new \App\Models\Supplier();
        $this->userModel = new \App\Models\User();
    }

    public function showSupplierManagement()
    {
        if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
            header('Location: /login');
            exit;
        }
        
        $suppliers = $this->supplierModel->getSuppliers();
        $role = $_SESSION['role'];

        $data = [
            'title' => 'Supplier Management',
            'suppliers' => $suppliers,
            'username' => $_SESSION['username'],
            
            'role' => $role,
            'isAdmin' => $role === 'Admin',
            'isInventoryManager' => $role === 'Inventory_Manager',
            'isProcurementManager' => $role === 'Procurement_Manager',
            'canAccessLinks' => in_array($role, ['Admin', 'Inventory_Manager']),

        ];
        return $this->render('supplier-management', $data);
        exit;
    }

    public function createSupplier()
    {
        $categoryModel = new \App\Models\Category();
        $categories = $categoryModel->getAllCategories();
        
        $role = $_SESSION['role'];

        $data = [
            'supplier_name' => $_POST['supplier_name'],
            'product_categoryID' => $_POST['product_categoryID'],
            'contact_number' => $_POST['contact_number'],
            'email' => $_POST['email'],
            'address' => $_POST['address'],
            'created_on' => date('Y-m-d H:i:s'),
            
            'role' => $role,
            'isAdmin' => $role === 'Admin',
            'isInventoryManager' => $role === 'Inventory_Manager',
            'isProcurementManager' => $role === 'Procurement_Manager',
            'canAccessLinks' => in_array($role, ['Admin', 'Inventory_Manager']),
        ];

        if ($this->supplierModel->createSupplier($data)) {
            header('Location: /dashboard/suppliers');
        } else {
            echo "Error creating supplier.";
        }
    }

    public function updateSupplier($id)
    {
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
        $categoryModel = new \App\Models\Category();
        $categories = $categoryModel->getAllCategories();
        $role = $_SESSION['role'];

        $data = [
            'title' => 'Create Supplier',
            'username' => $_SESSION['username'],
            'categories' => $categories,
            
            'role' => $role,
            'isAdmin' => $role === 'Admin',
            'isInventoryManager' => $role === 'Inventory_Manager',
            'isProcurementManager' => $role === 'Procurement_Manager',
            'canAccessLinks' => in_array($role, ['Admin', 'Inventory_Manager']),
        ];

        return $this->render('add-supplier', $data);
    }

    public function showEditUserPage($user_id)
    {
        $user = $this->userModel->findByUserID($user_id);

        if (!$user) {
            echo 'User not found.';
            exit;
        }

        $role = $_SESSION['role'];

        $data = [
            'title' => 'Edit User',
            'user_id' => $user['user_id'],
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'username' => $user['username'],
            'selectedAdmin' => $user['role'] === 'Admin' ? 'selected' : '',
            'selectedInventoryManager' => $user['role'] === 'Inventory_Manager' ? 'selected' : '',
            'selectedProcurementManager' => $user['role'] === 'Procurement_Manager' ? 'selected' : '',
            
            'role' => $role,
            'isAdmin' => $role === 'Admin',
            'isInventoryManager' => $role === 'Inventory_Manager',
            'isProcurementManager' => $role === 'Procurement_Manager',
            'canAccessLinks' => in_array($role, ['Admin', 'Inventory_Manager']),
        ];

        return $this->render('edit-user-page', $data);
    }

}
