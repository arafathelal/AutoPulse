<?php
require_once __DIR__ . '/../models/PartsModel.php';

class ProductController
{
    public function index()
    {
        $model = new PartsModel();
        $products = $model->getAll();
        require_once __DIR__ . '/../views/products.php';
    }
}