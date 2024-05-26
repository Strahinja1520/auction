<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\AuctionModel;
use App\Models\CategoryModel;
use App\Models\OfferModel;
use App\Models\UserModel;

class AuctionController extends Controller
{
    public function show($id): void
    {
        session_start();

        if(!isset($_SESSION['username'])){
            header("Location: " . \Configuration::BASE . "user/login");
            return;
        }

        $auctionModel = new AuctionModel($this->getConnection());

        $auction = $auctionModel->getAuctionById($id);

        if(!$auction){
            header("Location: /dir2");
            exit;
        }

        $this->set("auction", $auction);



        $lastOfferPrice = $this->getLastOfferPrice($id);

        if(!$lastOfferPrice){
            $lastOfferPrice = "-";
        }

        $this->set("lastOfferPrice", $lastOfferPrice);

        $offerModel = new OfferModel($this->getConnection());
        $lastFiveOffers = $offerModel->getLastFiveOffer($auction->auction_id);


        $this->set("offers", $lastFiveOffers);

        $userModel = new UserModel($this->getConnection());
        $user = $userModel->getUserById($auction->user_id);

        $this->set("user", $user);

        
        
        $ath = "Logout";
        if(isset($_SESSION['username'])){
            $ath = "Login";
        }

        $this->set("logging", $ath);

    }


    private function getLastOfferPrice($auctionId) 
    {
        $offerModel = new OfferModel($this->getConnection());
        $offers = $offerModel->getAllOffersByAuctionId($auctionId);

        $lastPrice = 0;

        foreach ($offers as $offer) {
            if($lastPrice < $offer->price ){
                $lastPrice = $offer->price;
            }
        }

        return $lastPrice;

    }

    public function getCreate(): void
    {
        session_start();
        if(!isset($_SESSION['username'])){
            header("Location: " . \Configuration::BASE . "user/login");
            return;
        }else{
            $ath = "Logout";
            if(isset($_SESSION['username'])){
                $ath = "Login";
            }

        $this->set("logging", $ath);
        }
        $categoryModel = new CategoryModel($this->getConnection());
        $categories = $categoryModel->getAllCategory();
        $this->set("categories", $categories);
    }
    public function postCreate(): void
    {
        session_start();

        $ath = "Logout";
            if(isset($_SESSION['username'])){
                $ath = "Login";
            }

        $this->set("logging", $ath);

        $title = filter_input(INPUT_POST, "title", FILTER_SANITIZE_SPECIAL_CHARS);
        $description = nl2br($_POST["description"]);
        $description = preg_replace('#<br\s*/?>#i', "\n", $description);
        $category = filter_input(INPUT_POST, "category", FILTER_SANITIZE_SPECIAL_CHARS);
        $user = $_SESSION["user_id"];
        $price = filter_input(INPUT_POST, "price", FILTER_SANITIZE_SPECIAL_CHARS);
        $expire_number = filter_input(INPUT_POST, "expire_number", FILTER_SANITIZE_SPECIAL_CHARS);

        if(isset($_FILES["image"])){
            $img_name = $_FILES['image']['name'];
            $img_size = $_FILES['image']['size'];
            $img_type = $_FILES['image']['type'];

            $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
            $img_ex = strtolower($img_ex);

            $allowed_ex = array("jpg", "jpeg", "png");

            if(in_array($img_ex, $allowed_ex)){
                $new_image_name = uniqid($title, true) . "." . $img_ex;
                $upload_path ="C:/xampp/htdocs/dir2/assets/img/uploads/" . $new_image_name;
                if(move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)){


                    $auctionModel = new AuctionModel($this->getConnection());

                    $res = $auctionModel->insertAuction($title, $description, $price, $category, $user, $new_image_name, $expire_number);
                    if($res){
                        $this->set("message", "Uspesno ste postavili aukciju!");
                    }
                    
                }
            }
        } 

    }

    public function getEdit(int $id): void
    {

        session_start();

        $ath = "Login";
        if(!isset($_SESSION['username'])){
            $ath = "Logout";
            header("Location: " . \Configuration::BASE . "user/login");
            return;
        }
        $this->set("logging", $ath);

        $auctionModel = new AuctionModel($this->getConnection());
        $auction = $auctionModel->getAuctionById($id);

        $this->set("auction", $auction);
    }

    public function postEdit(int $id): void
    {


        $title = filter_input(INPUT_POST, "title", FILTER_SANITIZE_SPECIAL_CHARS);
        $description = nl2br($_POST["description"]);
        $description = preg_replace('#<br\s*/?>#i', "\n", $description);
        $price = filter_input(INPUT_POST, "price", FILTER_SANITIZE_SPECIAL_CHARS);

        

        $offerModel = new OfferModel($this->getConnection());
        $offers = $offerModel->getAllOffersByAuctionId($id);

        

        $auctionModel = new AuctionModel($this->getConnection());
        $auction = $auctionModel->getAuctionById($id);

        if($price !== $auction->starting_price){
            if($offers){
                $this->set("message", "Pocetnu cenu mozete promeniti samo pre bilo koje ponude.");
                return;
            }else{
                $res = $auctionModel->editAuction($title, $description, $price, $id);
                if($res){
                    $this->set("message", "Uspesno ste editovali aukciju");
                    return;
                }
            }
        }else{
            $res = $auctionModel->editAuction($title, $description, $price, $id);
                if($res){
                    $this->set("message", "Uspesno ste editovali aukciju");
                    return;
                }
        }
        
        
        
    }

    public function delete(int $id): void
    {
        $auctionModel = new AuctionModel($this->getConnection());
        $auction = $auctionModel->getAuctionById($id);
        $res = $auctionModel->deleteAuction($id);

        if($res){
            if(unlink("C:/xampp/htdocs/dir2/assets/img/uploads/" . $auction->img_path)){
                header("Location: " . \Configuration::BASE . "user/profile");
                return;
            }else{
                $this->set("message", "Doslo je do greske prilikom brisanja");
            };
            return;
        }

    }
    public function deleteAdminAction(int $id): void
    {
        $auctionModel = new AuctionModel($this->getConnection());
        $auction = $auctionModel->getAuctionById($id);
        $res = $auctionModel->deleteAuction($id);


            if($res){
                if(unlink("C:/xampp/htdocs/dir2/assets/img/uploads/" . $auction->img_path)){
                    $this->set("message", "Uspesno ste obrisali aukciju");
                }else{
                    $this->set("message", "Doslo je do greske prilikom brisanja");
                }
                
            }else{
                echo "Doslo je do greske prilikom brisanja";
            }

    }
    public function deleteModeratorAction(int $id): void
    {
        $auctionModel = new AuctionModel($this->getConnection());
        $auction = $auctionModel->getAuctionById($id);
        $res = $auctionModel->deleteAuction($id);

        if($res){
            if(unlink("C:/xampp/htdocs/dir2/assets/img/uploads/" . $auction->img_path)){
                $this->set("message", "Uspesno ste obrisali aukciju");
            }else{
                $this->set("message", "Doslo je do greske prilikom brisanja");
            }
            
        }else{
            echo "Doslo je do greske prilikom brisanja";
        }

    }


    public function search(): void
    {
        session_start();

        if(!isset($_SESSION['username'])){
            header("Location: " . \Configuration::BASE . "user/login");
            return;
        }

        $searchString = filter_input(INPUT_POST, "search", FILTER_SANITIZE_SPECIAL_CHARS);
        if(!$searchString){
            header("Location: " . \Configuration::BASE);
            return;
        }
        $searchString = trim($searchString);
        $searchString = preg_replace('/ +/', '/ /', $searchString);

        $ath = "Logout";
        if(isset($_SESSION['username'])){
            $ath = "Login";
        }

        $this->set("logging", $ath);

        $auctionModel = new AuctionModel($this->getConnection());

        


        $auctions = $auctionModel->getAllBySearch($searchString);

        $this->set("auctions", $auctions);
        $this->set("search", $searchString);

    }
}