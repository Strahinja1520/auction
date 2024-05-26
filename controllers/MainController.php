<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\UserModel;
use App\Models\AuctionModel;


class MainController extends \App\Core\Controller
{
   public function home()
    {
        session_start();

        $auctionModel = new AuctionModel($this->getConnection());

        $trenutniDatum = date('Y-m-d H:i:s');

        $auctionsForDelete = $auctionModel->getAllAuctionsForDelete($trenutniDatum);

        foreach ($auctionsForDelete as $auction) {
            $auctionModel->deactivateAuction($auction->auction_id);
        }

        $categoryModel = new CategoryModel($this->getConnection());

        $categories = $categoryModel->getSixCategories();

        $this->set('categories', $categories);

        $ath = "Logout";
        if(isset($_SESSION['username'])){
            $ath = "Login";
        }

        $this->set("logging", $ath);
    }

    public function getRegister(): void
    {
        session_start();
        $ath = "Logout";
        if(isset($_SESSION['username'])){
            $ath = "Login";
        }

        $this->set("logging", $ath);
    }
    public function postRegister(): void
    {
        $email = filter_input(INPUT_POST, "reg_email", FILTER_SANITIZE_EMAIL);
        $forename = filter_input(INPUT_POST, "reg_forename", FILTER_SANITIZE_SPECIAL_CHARS);
        $lastname = filter_input(INPUT_POST, "reg_lastname", FILTER_SANITIZE_SPECIAL_CHARS);
        $phone = filter_input(INPUT_POST, "reg_phone", FILTER_SANITIZE_SPECIAL_CHARS);
        $username = filter_input(INPUT_POST, "reg_username", FILTER_SANITIZE_SPECIAL_CHARS);
        $password1 = filter_input(INPUT_POST, "reg_password_1", FILTER_SANITIZE_SPECIAL_CHARS);
        $password2 = filter_input(INPUT_POST, "reg_password_2", FILTER_SANITIZE_SPECIAL_CHARS);

        $phone = "+381 " . $phone;

        $is_admin = 0;
        $is_moderator = 0;


        $userModel = new UserModel($this->getConnection());


        $user = $userModel->getUserByEmail($email);
        if($user){
            $this->set("message", "Korisnik sa ovim emailom vec postoji!");
            return;
        }


        $user = $userModel->getUserByName($username);
        if($user){
            $this->set("message", "Korisnik sa ovim username-om vec postoji!");
            return;
        }


        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->set("message", "Email nije u dobrom formatu!");
            return;
        }

        if (!preg_match("/^[A-Z][a-z]{2,32}$/", $forename)) {
            $this->set("message", "Ime mora poceti velikim slovom i ne sme imati solova ili specijalne znakove!");
            return;
        }

        if (!preg_match("/^[A-Z][a-z]{2,32}$/", $lastname)) {
            $this->set("message", "Prezime mora poceti velikim slovom i ne sme imati solova ili specijalne znakove!");
            return;
        }

        if (!preg_match("/^[A-Za-z0-9]{6,32}$/", $username)) {
            $this->set("message", "Username mora imati bar 6 karaktera i nisu dozvoljeni specijalni znaci!");
            return;
        }

        if(!preg_match("/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/", $password1)){
            $this->set("message", "Password nije dobar! Morate imati mala slova, velika slova, brojke i specijalne karaktere!");
            return;
        }


        if($password1 !== $password2){
            $this->set("message", "Lozinke se ne poklapaju!");
            return;
        }


        $passwordHash = password_hash($password1, PASSWORD_DEFAULT);



        if(!$userModel->insertUser($forename, $lastname, $phone, $email, $username, $passwordHash, $is_admin, $is_moderator)){
            $this->set("message", "Doslo je do greske pri registraciji!");
            return;
        }

        $this->set("message", "Napravljen je novi nalog! Sada mozete da se prijavite.");
        
    }

    public function getLogin()
    {
    }
    public function postLogin()
    {
        session_start();
        $username = filter_input(INPUT_POST, "login_username", FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, "login_password", FILTER_SANITIZE_SPECIAL_CHARS);

        $userModel = new UserModel($this->getConnection());
        
        $user = $userModel->getUserByName($username);
        if(!$user){
            $this->set("message", "Korisnik sa ovim korisnickim imenom ne postoji!");
            return;
        }

        if(!password_verify($password, $user->password_hash)){
            $this->set("message", "Lozinka nije dobra!");
            return;
        }

        $_SESSION['username'] = $user->username;
        $_SESSION['email'] = $user->email;
        $_SESSION['user_id'] = $user->user_id;

        if($user->is_admin == 1){
            $_SESSION["is_admin"] = true;
            header("Location: " . \Configuration::BASE . "admin-dashboard");
            return;
        }

        if($user->is_moderator == 1){
            $_SESSION["is_moderator"] = true;
            header("Location: " . \Configuration::BASE . "moderator-dashboard");
            return;
        }

        header("Location: " . \Configuration::BASE . "user/profile");

    }

    public function logout()
    {
        session_start();
        session_destroy();
        header("Location: " . \Configuration::BASE . "user/login");
    }

    public function admin(){

        session_start();

        if(!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin'){
            header("Location: " . \Configuration::BASE);
            return;
        }

        $userModel = new UserModel($this->getConnection());

        $moderators = $userModel->getModerators();

        $this->set("moderators", $moderators);
        
    }

    public function adminUsers()
    {
        session_start();

        if(!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin'){
            header("Location: " . \Configuration::BASE);
            return;
        }

        $userModel = new UserModel($this->getConnection());
        $users = $userModel->getAllUsers();

        $this->set("users", $users);

    }

    public function adminCategories()
    {
        session_start();

        if(!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin'){
            header("Location: " . \Configuration::BASE);
            return;
        }

        $userModel = new CategoryModel($this->getConnection());
        $categories = $userModel->getAllCategory();

        $this->set("categories", $categories);

    }


    public function moderatorUsers()
    {
        session_start();

        if(!$_SESSION["is_moderator"]){
            header("Location: " . \Configuration::BASE);
            return;
        }

        $userModel = new UserModel($this->getConnection());
        $users = $userModel->getAllUsers();

        $this->set("users", $users);

    }


    
    public function getCreateModerator(){

    }

    public function postCreateModerator(){
        $email = filter_input(INPUT_POST, "reg_email", FILTER_SANITIZE_EMAIL);
        $forename = filter_input(INPUT_POST, "reg_forename", FILTER_SANITIZE_SPECIAL_CHARS);
        $lastname = filter_input(INPUT_POST, "reg_lastname", FILTER_SANITIZE_SPECIAL_CHARS);
        $phone = filter_input(INPUT_POST, "reg_phone", FILTER_SANITIZE_SPECIAL_CHARS);
        $username = filter_input(INPUT_POST, "reg_username", FILTER_SANITIZE_SPECIAL_CHARS);
        $password1 = filter_input(INPUT_POST, "reg_password_1", FILTER_SANITIZE_SPECIAL_CHARS);
        $password2 = filter_input(INPUT_POST, "reg_password_2", FILTER_SANITIZE_SPECIAL_CHARS);

        $phone = "+381 " . $phone;

        $is_admin = 0;
        $is_moderator = 1;

        $userModel = new UserModel($this->getConnection());


        $user = $userModel->getUserByEmail($email);
        if($user){
            $this->set("message", "Korisnik sa ovim emailom vec postoji!");
            return;
        }


        $user = $userModel->getUserByName($username);
        if($user){
            $this->set("message", "Korisnik sa ovim username-om vec postoji!");
            return;
        }


        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->set("message", "Email nije u dobrom formatu!");
            return;
        }

        if (!preg_match("/^[A-Z][a-z]{2,32}$/", $forename)) {
            $this->set("message", "Ime mora poceti velikim slovom i ne sme imati solova ili specijalne znakove!");
            return;
        }

        if (!preg_match("/^[A-Z][a-z]{2,32}$/", $lastname)) {
            $this->set("message", "Prezime mora poceti velikim slovom i ne sme imati solova ili specijalne znakove!");
            return;
        }

        if (!preg_match("/^[A-Za-z0-9]{6,32}$/", $username)) {
            $this->set("message", "Username mora imati bar 6 karaktera i nisu dozvoljeni specijalni znaci!");
            return;
        }

        if(!preg_match("/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/", $password1)){
            $this->set("message", "Password nije dobar! Morate imati mala slova, velika slova, brojke i specijalne karaktere!");
            return;
        }


        if($password1 !== $password2){
            $this->set("message", "Lozinke se ne poklapaju!");
            return;
        }


        $passwordHash = password_hash($password1, PASSWORD_DEFAULT);



        if(!$userModel->insertUser($forename, $lastname, $phone, $email, $username, $passwordHash, $is_admin, $is_moderator)){
            $this->set("message", "Doslo je do greske pri registraciji!");
            return;
        }

        $this->set("message", "Uspesno kreiran moderator");
    }

    public function getCreateCategory(){

    }

    public function postCreateCategory(){

        $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);
        
        $categoryModel = new CategoryModel($this->getConnection());

        $user = $categoryModel->getCategoryByName($name);
        if($user){
            $this->set("message", "Kategorija sa ovim nazivom vec postoji!");
            return;
        }
        if(isset($_FILES["image"])){
            $img_name = $_FILES['image']['name'];
            $img_size = $_FILES['image']['size'];
            $img_type = $_FILES['image']['type'];

            $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
            $img_ex = strtolower($img_ex);

            $allowed_ex = array("jpg", "jpeg", "png");

            if(in_array($img_ex, $allowed_ex)){
                $new_image_name = uniqid($name, true) . "." . $img_ex;
                $upload_path ="C:/xampp/htdocs/dir2/assets/img/uploads/" . $new_image_name;
                if(move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)){



                    $res = $categoryModel->insertCategory($name, $new_image_name);
                    if($res){
                        $this->set("message", "Uspesno ste kreirali kategoriju!");
                    }
                    
                }
            }
        } 
    }

    public function pageNotFound(){
        
    }
}