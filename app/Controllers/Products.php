<?php

namespace App\Controllers;

use App\Models\Product_model;
use App\Models\Category_model;


class Products extends BaseController
{
    protected $productModel;
    protected $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new Category_model();
        $this->productModel = new Product_model();
    }

    public function index()
    {
        $products = $this->productModel->findAll();
        return $this->response->setJSON($products);
    }

    public function edit($id)
    {
        $product = $this->productModel->getProduct($id);
        return $this->response->setJSON($product);
    }

    public function update($id)
    {
        $data = $this->request->getJSON();
        $result = $this->productModel->updateProduct($id, (array) $data);

        if ($result) {
            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to update product.']);
        }
    }

    public function delete($id)
    {
        $result = $this->productModel->deleteProduct($id);
        return $this->response->setJSON(['success' => $result]);
    }

    public function create()
    {
        $data = $this->request->getJSON();
    
        $productModel = new \App\Models\Product_model();
        $insertedId = $productModel->createProduct([
            'name' => $data->name,
            'description' => $data->description,
            'price' => $data->price,
            'category_id' => $data->category
        ]);
    
        if ($insertedId) {
            return $this->response->setJSON(['message' => 'Product created successfully', 'inserted_id' => $insertedId]);
        } else {
            return $this->response->setJSON(['error' => 'Failed to create product']);
        }
    }

    public function categories()
    {
        $categories = $this->categoryModel->getAllCategories();
        return $this->response->setJSON($categories);
    }
}
