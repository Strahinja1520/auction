<?php
namespace App\Models;



class AuctionModel
{
    private $db;

    public function __construct(\App\Core\DatabaseConnection &$dbc)
    {
        $this->db = $dbc->getConnection();
    }

    public function getAllAuctions()
    {
        $sql = "SELECT * FROM auction";
        $prep = $this->db->prepare($sql);
        $res = $prep->execute();
        $auctions = [];
        if($res){
            $auctions = $prep->fetchAll(\PDO::FETCH_OBJ);
        }

        return $auctions;
    }

    public function getAllInactiveAuctions(): array
    {
        $sql = "SELECT * FROM auction WHERE is_active = false";
        $prep = $this->db->prepare($sql);
        $res = $prep->execute();
        $auctions = [];
        if($res){
            $auctions = $prep->fetchAll(\PDO::FETCH_OBJ);
        }

        return $auctions;
    }

    public function getAuctionById(int $auctionId)
    {
        $sql = "SELECT * FROM auction WHERE auction_id = ?";
        $prep = $this->db->prepare($sql);
        $res = $prep->execute([$auctionId]);
        $auction = NULL;
        if($res){
            $auction = $prep->fetch(\PDO::FETCH_OBJ);
        }

        return $auction;
    }

    public function getAuctionByName(int $auctionName)
    {
        $sql = "SELECT * FROM category WHERE title = ?";
        $prep = $this->db->prepare($sql);
        $res = $prep->execute([$auctionName]);
        $auction = NULL;
        if($res){
            $auction = $prep->fetch(\PDO::FETCH_OBJ);
        }

        return $auction;
    }

    public function getAllAuctionsByCategoryId(int $categoryId): array
    {
        $sql = "SELECT * FROM auction WHERE category_id = ? AND is_active = true";
        $prep = $this->db->prepare($sql);
        $res = $prep->execute([$categoryId]);
        $auctions = [];
        if($res){
            $auctions = $prep->fetchAll(\PDO::FETCH_OBJ);
        }

        return $auctions;
    }

    public function getAllAuctionsByUserId(int $userId): array
    {
        $sql = "SELECT * FROM auction WHERE user_id = ? AND is_active = true";
        $prep = $this->db->prepare($sql);
        $res = $prep->execute([$userId]);
        $auctions = [];
        if($res){
            $auctions = $prep->fetchAll(\PDO::FETCH_OBJ);
        }

        return $auctions;
    }
    public function getAllInactiveAuctionsByUserId(int $userId): array
    {
        $sql = "SELECT * FROM auction WHERE user_id = ? AND is_active = false";
        $prep = $this->db->prepare($sql);
        $res = $prep->execute([$userId]);
        $auctions = [];
        if($res){
            $auctions = $prep->fetchAll(\PDO::FETCH_OBJ);
        }

        return $auctions;
    }
    public function getWinnerOffer(){
        $sql = "SELECT * FROM offer ORDER BY price DESC LIMIT 1";
        $prep = $this->db->prepare($sql);
        $res = $prep->execute();
        $auction = null;
        if($res){
            $auction = $prep->fetch(\PDO::FETCH_OBJ);
        }

        return $auction;
    }
    public function insertAuction(string $title, string $description, float $price, int $categoryId, int $userId, string $imgPath, int $expire_number): bool
    {
        $sql = "INSERT INTO auction (title, description, starting_price, category_id, user_id, img_path, expire_number) VALUES (?,?,?,?,?,?,?)";
        $prep = $this->db->prepare($sql);
        $res = $prep->execute([$title, $description, $price, $categoryId, $userId, $imgPath, $expire_number]);

        return $res;
    }

    public function editAuction(string $title, string $description, float $price, int $auctionId): bool
    {
        $sql = "UPDATE auction SET title = ?, description = ?, starting_price = ? WHERE auction_id = ?";
        $prep = $this->db->prepare($sql);
        $res = $prep->execute([$title, $description, $price, $auctionId]);

        return $res;
    }

    public function getAllAuctionsForDelete($date){
        $sql = "SELECT * FROM auction WHERE expire_date < '$date'";
        $prep = $this->db->prepare($sql);
        $res = $prep->execute();
        $auctions = [];
        if($res){
            $auctions = $prep->fetchAll(\PDO::FETCH_OBJ);
        }

        return $auctions;

    }

    public function editExpireDate($date, $auctionId): bool
    {
        $sql = "UPDATE auction SET expire_date = ? WHERE auction_id = ?";
        $prep = $this->db->prepare($sql);
        $res = $prep->execute([$date, $auctionId]);
        return $res;
    }

    public function deactivateAuction(int $id): bool
    {
        $sql = "UPDATE auction SET is_active = '0' WHERE auction_id = ?";
        $prep = $this->db->prepare($sql);
        $res = $prep->execute([$id]);

        return $res;
    }

    public function deleteAuction(int $id): bool{
        $sql = "DELETE FROM auction WHERE auction_id = ?";
        $prep = $this->db->prepare($sql);
        $res = $prep->execute([$id]);

        return $res;
    }

    public function getAllBySearch(string $string)
    {
        $sql = "SELECT auction.*, user.username FROM auction LEFT JOIN user ON user.user_id = auction.user_id WHERE title LIKE ? AND is_active = true";
        $string = "%" . $string . "%";
        $prep = $this->db->prepare($sql);
        $res = $prep->execute([$string]);

        $auctions = [];
        if($res){
            $auctions = $prep->fetchAll(\PDO::FETCH_OBJ);
        }

        return $auctions;
    }
}