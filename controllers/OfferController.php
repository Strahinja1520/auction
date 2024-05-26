<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\AuctionModel;
use App\Models\OfferModel;

class OfferController extends Controller
{
    public function create(): void
    {
        session_start();
        if(isset($_POST["new-price"])){
            $offerPrice = $_POST["new-price"];
        }
        if(isset($_POST["auction_id"])){
            $auction_id = $_POST["auction_id"];
        }
        $user_id = $_SESSION["user_id"];

        // ! Mehanizam za brisanje aukcija koje su istekle: 
        $auctionModel = new AuctionModel($this->getConnection());

        $trenutniDatum = date('Y-m-d H:i:s');

        $auctionsForDelete = $auctionModel->getAllAuctionsForDelete($trenutniDatum);

        foreach ($auctionsForDelete as $auction) {
            $auctionModel->deactivateAuction($auction->auction_id);
        }
        // ! Kraj mehanizma



        $auctionModel = new AuctionModel($this->getConnection());


        $auction = $auctionModel->getAuctionById($auction_id);

        $inactiveAuctions = $auctionModel->getAllInactiveAuctions();

        foreach ($inactiveAuctions as $inactive) {
            if($inactive->auction_id === $auction->auction_id){
                $this->set("message", "Nema vise licitacija, aukcija je gotova!");
                return;
            }
            
        }

        $offerModel = new OfferModel($this->getConnection());
        $currentAuctionPrice = $offerModel->getLastOfferPrice($auction);

        $currentAuctionPrice = $currentAuctionPrice + ($currentAuctionPrice * 0.07);
        if($offerPrice < $currentAuctionPrice){
            $this->set("message", "Ponuda mora biti barem 7% veca od prethodne!");
            return;
        }

        if ($user_id == $auction->user_id) {
            $this->set('message', 'Ne mozete napraviti ponudu za svoju aukciju!');
            return;
        }

        $currentDateTime = date("Y-m-d H:i:s");
        $futureDateTime = date("Y-m-d H:i:s", strtotime($currentDateTime . ' + ' . $auction->expire_number . ' days'));
        
        $res1 = $auctionModel->editExpireDate($futureDateTime, $auction_id);

        $res2 = $offerModel->insertOffer($auction_id, $user_id, $offerPrice);

        if($res1 && $res2){
            header("Location: " . \Configuration::BASE . "auction/" . $auction_id);
            return;
        }else{
            echo "DOSLO JE DO GRESKE";
        }
        
    }
}