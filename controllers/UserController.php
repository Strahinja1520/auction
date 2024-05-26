<?php 

namespace App\Controllers;

use App\Core\Controller;
use App\Models\AuctionModel;
use App\Models\UserModel;
use App\Models\OfferModel;

class UserController extends Controller
{
    public function profile()
    {
        session_start();

        // ! Mehanizam za brisanje aukcija koje su istekle: 
        $auctionModel = new AuctionModel($this->getConnection());

        $trenutniDatum = date('Y-m-d H:i:s');

        $auctionsForDelete = $auctionModel->getAllAuctionsForDelete($trenutniDatum);

        foreach ($auctionsForDelete as $auction) {
            $auctionModel->deactivateAuction($auction->auction_id);
        }
        // ! Kraj mehanizma

        $ath = "Login";
        if(!isset($_SESSION['username'])){
            $ath = "Logout";
            header("Location: " . \Configuration::BASE . "user/login");
            return;
        }
        $this->set("logging", $ath);

        $userModel = new UserModel($this->getConnection());
        $user = $userModel->getUserById($_SESSION['user_id']);

        if(!$user){
            header("Location: " . \Configuration::BASE);
            exit;
        }
        // $user->phone = substr($user->phone, 5);
        $this->set("user", $user);

        $auctionModel = new AuctionModel($this->getConnection());
        
        $auctions = $auctionModel->getAllAuctionsByUserId($user->user_id);
        $this->set("auctions", $auctions);

        $inactive_auctions = $auctionModel->getAllInactiveAuctionsByUserId($user->user_id);
        $this->set("inactive", $inactive_auctions);

        $winnerId = $auctionModel->getWinnerOffer();

        if($winnerId){
            $winner = $userModel->getUserById($winnerId->user_id);

            $this->set("winner", $winner);
        }

        
    }

    public function edit(): void
    {
        session_start();

        $ath = "Login";
        if(!isset($_SESSION['username'])){
            $ath = "Logout";
            header("Location: " . \Configuration::BASE . "user/login");
            return;
        }
        $this->set("logging", $ath);

        $email = filter_input(INPUT_POST, "reg_email", FILTER_SANITIZE_EMAIL);
        $forename = filter_input(INPUT_POST, "reg_forename", FILTER_SANITIZE_SPECIAL_CHARS);
        $lastname = filter_input(INPUT_POST, "reg_lastname", FILTER_SANITIZE_SPECIAL_CHARS);
        $phone = filter_input(INPUT_POST, "reg_phone", FILTER_SANITIZE_SPECIAL_CHARS);
        $username = filter_input(INPUT_POST, "reg_username", FILTER_SANITIZE_SPECIAL_CHARS);

        $phone = "+381 " . $phone;

        $userModel = new UserModel($this->getConnection());
        $user = $userModel->getUserById($_SESSION['user_id']);
        $res = $userModel->editUser($user->user_id, $email, $forename, $lastname, $phone, $username);
        if($res){
            $this->set("message", "Uspesno ste editovali profil podatke");
        }else{
            echo "Doslo je do greske prilikom update-a.";
        }
    }

    public function deleteFromAdmin(int $id): void{
        $userModel = new UserModel($this->getConnection());
        $user = $userModel->getUserById($id);
        $res = $userModel->deleteUser($user->user_id);

        if($res){
            $this->set("message", "Uspesno ste obrisali korisnika");
        }else{
            echo "Doslo je do greske prilikom brisanja";
        }
    }
    public function deleteFromModerator(int $id): void{
        $userModel = new UserModel($this->getConnection());
        $user = $userModel->getUserById($id);
        $res = $userModel->deleteUser($user->user_id);

        if($res){
            $this->set("message", "Uspesno ste obrisali korisnika");
        }else{
            echo "Doslo je do greske prilikom brisanja";
        }
    }

    public function userAuctions($id): void{
        $userModel = new UserModel($this->getConnection());
        $user = $userModel->getUserById($id);
        if(!$user){
            header("Location: " . \Configuration::BASE . "admin-dashboard/users");
        }

        $this->set("user", $user);

        $auctionModel = new AuctionModel($this->getConnection());
        $offerModel = new OfferModel($this->getConnection());
        
        $activeAuctions = $auctionModel->getAllAuctionsByUserId($id);
        $this->set("active", $activeAuctions);

        

        $inactiveAuctions = $auctionModel->getAllInactiveAuctionsByUserId($id);

        $this->set("inactive", $inactiveAuctions);


        foreach($activeAuctions as $inactive){
            $offerPrice = $offerModel->getLastOfferPrice($inactive);
            $this->set("offerPriceActive", $offerPrice);
        }

        foreach($inactiveAuctions as $inactive){
            $offerPrice = $offerModel->getLastOfferPrice($inactive);
            $this->set("offerPriceInactive", $offerPrice);
        }

        

        
    }
    public function modUserAuctions($id): void{
        $userModel = new UserModel($this->getConnection());
        $user = $userModel->getUserById($id);
        if(!$user){
            header("Location: " . \Configuration::BASE . "admin-dashboard/users");
        }

        $this->set("user", $user);

        $auctionModel = new AuctionModel($this->getConnection());
        $offerModel = new OfferModel($this->getConnection());
        
        $activeAuctions = $auctionModel->getAllAuctionsByUserId($id);
        $this->set("active", $activeAuctions);

        

        $inactiveAuctions = $auctionModel->getAllInactiveAuctionsByUserId($id);

        $this->set("inactive", $inactiveAuctions);


        foreach($activeAuctions as $inactive){
            $offerPrice = $offerModel->getLastOfferPrice($inactive);
            $this->set("offerPriceActive", $offerPrice);
        }

        foreach($inactiveAuctions as $inactive){
            $offerPrice = $offerModel->getLastOfferPrice($inactive);
            $this->set("offerPriceInactive", $offerPrice);
        }

        

        
    }
}