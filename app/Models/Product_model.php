<?php

namespace App\Models;

use CodeIgniter\Model;

class Product_model extends Model
{
    protected $table = 'products'; 
    protected $primaryKey = 'id'; 

    protected $validationRules = [
        'name' => 'required',
        'description' => 'required',
        'price' => 'required|numeric',
        'category_id' => 'required|numeric',
    ];



    protected $validationGroups = [
        'insert' => ['name', 'description', 'price', 'category_id'],
        'update' => ['name', 'description', 'price', 'category_id'],
    ];

    protected $allowedFields = ['name', 'description', 'price', 'category_id'];

    public function getProduct($id)
    {
        return $this->find($id);
    }

    public function updateProduct($id, $data)
    {
        return $this->update($id, $data);
    }

    public function deleteProduct($id)
    {
        return $this->delete($id);
    }
    public function createProduct($data)
    {
        return $this->insert($data);
    }
}
