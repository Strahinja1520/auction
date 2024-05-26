<?php
namespace App\Models;



class CategoryModel
{
    private $db;

    public function __construct(\App\Core\DatabaseConnection &$dbc)
    {
        $this->db = $dbc->getConnection();
    }

    public function getAllCategory()
    {
        $sql = "SELECT * FROM category";
        $prep = $this->db->prepare($sql);
        $res = $prep->execute();
        $categories = [];
        if($res){
            $categories = $prep->fetchAll(\PDO::FETCH_OBJ);
        }

        return $categories;
    }

    public function getSixCategories()
    {
        $sql = "SELECT * FROM category LIMIT 6";
        $prep = $this->db->prepare($sql);
        $res = $prep->execute();
        $categories = [];
        if($res){
            $categories = $prep->fetchAll(\PDO::FETCH_OBJ);
        }

        return $categories;
    }

    public function getCategoryById(int $categoryId)
    {
        $sql = "SELECT * FROM category WHERE category_id = ?";
        $prep = $this->db->prepare($sql);
        $res = $prep->execute([$categoryId]);
        $category = NULL;
        if($res){
            $category = $prep->fetch(\PDO::FETCH_OBJ);
        }

        return $category;
    }

    public function getCategoryByName(string $categoryName)
    {
        $sql = "SELECT * FROM category WHERE name = ?";
        $prep = $this->db->prepare($sql);
        $res = $prep->execute([$categoryName]);
        $category = NULL;
        if($res){
            $category = $prep->fetch(\PDO::FETCH_OBJ);
        }

        return $category;
    }
    public function deleteCategory(int $id){
        $sql = "DELETE FROM category WHERE category_id = ?";
        $prep = $this->db->prepare($sql);
        $res = $prep->execute([$id]);
        return $res;
    }

    public function insertCategory(string $name, string $imgPath): bool
    {
        $sql = "INSERT INTO category (name, img_path) VALUES (?,?)";
        $prep = $this->db->prepare($sql);
        $res = $prep->execute([$name, $imgPath]);

        return $res;
    }

}