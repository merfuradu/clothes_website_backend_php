<?php

namespace App\Models;

use CodeIgniter\Model;

class Category_model extends Model
{
    protected $table = 'categories'; 
    protected $primaryKey = 'id'; 


    public function getAllCategories()
    {
        return $this->findAll();
    }
}