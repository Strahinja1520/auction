<?php
namespace App\Models;



class OfferModel
{
    private $db;

    public function __construct(\App\Core\DatabaseConnection &$dbc)
    {
        $this->db = $dbc->getConnection();
    }

    public function getAllOffers()
    {
        $sql = "SELECT * FROM offer";
        $prep = $this->db->prepare($sql);
        $res = $prep->execute();
        $offers = [];
        if($res){
            $offers = $prep->fetchAll(\PDO::FETCH_OBJ);
        }

        return $offers;
    }

    public function getOfferById(int $offerId)
    {
        $sql = "SELECT * FROM offer WHERE offer_id = ?";
        $prep = $this->db->prepare($sql);
        $res = $prep->execute([$offerId]);
        $offer = NULL;
        if($res){
            $offer = $prep->fetch(\PDO::FETCH_OBJ);
        }

        return $offer;
    }

    public function getOfferByName(int $offerName)
    {
        $sql = "SELECT * FROM category WHERE name = ?";
        $prep = $this->db->prepare($sql);
        $res = $prep->execute([$offerName]);
        $offer = NULL;
        if($res){
            $offer = $prep->fetch(\PDO::FETCH_OBJ);
        }

        return $offer;
    }

    public function getAllOffersByAuctionId(int $auctionId): array
    {
        $sql = "SELECT * FROM offer WHERE auction_id = ? ORDER BY creation_date ASC";
        $prep = $this->db->prepare($sql);
        $res = $prep->execute([$auctionId]);
        $offers = [];
        if($res){
            $offers = $prep->fetchAll(\PDO::FETCH_OBJ);
        }

        return $offers;
    }

    public function getLastByAuctionId(int $auctionId) {
        $sql = 'SELECT * FROM `offer` WHERE `auction_id` = ? ORDER BY `creation_date` DESC LIMIT 1;';
        $prep = $this->db->prepare($sql);

        if (!$prep) {
            return null;
        }

        $res = $prep->execute([ $auctionId ]);
        if (!$res) {
            return null;
        }

        return $prep->fetch(\PDO::FETCH_OBJ);
    }

    public function getLastByUserId(int $userId) {
        $sql = 'SELECT * FROM `offer` WHERE `user_id` = ? ORDER BY `price` DESC LIMIT 1;';
        $prep = $this->db->prepare($sql);
        $res = $prep->execute([$userId]);
        $offer = NULL;
        if($res){
            $offer = $prep->fetch(\PDO::FETCH_OBJ);
        }

        return $offer;
    }

    public function getLastOfferPrice($auction) {
        $lastOffer = $this->getLastByAuctionId($auction->auction_id);

        if (!$lastOffer) {
            return $auction->starting_price;
        }

        return $lastOffer->price;
    }

    public function insertOffer(int $auctionId, int $userId, float $price): bool
    {
        $sql = "INSERT INTO offer (auction_id, user_id, price) VALUES (?,?,?)";
        $prep = $this->db->prepare($sql);
        $res = $prep->execute([ $auctionId, $userId, $price ]);
        return $res;
    }

    public function getLastFiveOffer(int $auction_id)
    {
        $sql = 'SELECT offer.*, user.username FROM offer LEFT JOIN user ON offer.user_id = user.user_id WHERE auction_id = ? ORDER BY creation_date DESC LIMIT 4;';
        $prep = $this->db->prepare($sql);

        if (!$prep) {
            return null;
        }

        $res = $prep->execute([$auction_id]);
        if (!$res) {
            return null;
        }

        return $prep->fetchAll(\PDO::FETCH_OBJ);
    }
}