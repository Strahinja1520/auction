<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\AuctionModel;
use App\Models\CategoryModel;
use App\Models\UserModel;

class CategoryController extends Controller
{
    public function show($id): void
    {
        session_start();

        $categoryModel = new CategoryModel($this->getConnection());

        $category = $categoryModel->getCategoryById($id);

        if(!$category){
            header("Location: /dir2");
            exit;
        }


        $this->set("category", $category);


        $auctionModel = new AuctionModel($this->getConnection());
        
        $auctionsInCategory = $auctionModel->getAllAuctionsByCategoryId($id);
        $this->set("auctionsInCategory", $auctionsInCategory);


        $userModel = new UserModel($this->getConnection());

        foreach ($auctionsInCategory as $auction) {
            $user = $userModel->getUserById($auction->user_id);

            $this->set("username", $user->username);
        }

        $ath = "Logout";
        if(isset($_SESSION['username'])){
            $ath = "Login";
        }

        $this->set("logging", $ath);

    }

    public function showAll(): void
    {
        session_start();
        
        $categoryModel = new CategoryModel($this->getConnection());

        $categories = $categoryModel->getAllCategory();

        $this->set('categories', $categories);

        $ath = "Logout";
        if(isset($_SESSION['username'])){
            $ath = "Login";
        }

        $this->set("logging", $ath);
    }

    public function deleteFromAdmin(int $id): void{
        $categoryModel = new CategoryModel($this->getConnection());
        $category = $categoryModel->getCategoryById($id);
        $res = $categoryModel->deleteCategory($category->category_id);

        if($res){
            $this->set("message", "Uspesno ste obrisali korisnika");
        }else{
            echo "Doslo je do greske prilikom brisanja";
        }
    }


}

