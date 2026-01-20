<?php
require_once __DIR__ . '/../models/PartsModel.php';

class HomeController
{
    public function index()
    {
        $model = new PartsModel();
        $products = $model->getAllDiscounted();
        require_once __DIR__ . '/../views/home.php';
    }
}
