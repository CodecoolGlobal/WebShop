<?php

require_once "../utils/utils.php";


class User{
    private $id;
    private $name;
    private $isAdmin;


    public function __construct(){
        if (isset($_SESSION['userID'])) {
            $this->id = $_SESSION['userID'];
            $this->name = $_SESSION['username'];
            $this->isAdmin=$_SESSION['isAdmin'];
        }
    }

    public function checkIfLoggedIn():void{

        if (!isset($this->id))
            header("Location: ../shop/login.php");
    }
    public function checkIfLoggedInAsAdmin():void{

        if (!isset($this->id)||!($this->isAdmin))
            header("Location: ../admin/login.php");
    }

    public function getID():int{
        return $this->id;
    }

    public function getName():string{
        return $this->name;
    }



    public function login(string $email, string $password):void{
        $connection=getConnection();
        $query="SELECT * FROM users WHERE email=:email AND password=:password AND deleted=0";
        $statement=$connection->prepare($query);
        $data=["email"=>$email, "password"=>md5($password)];
        $statement->execute($data);
        $resultset=$statement->fetch();

        if ($resultset){
            var_dump($resultset);
            $this->id=$resultset["id"];
            $_SESSION['userID']=$this->id;
            $this->name=$resultset["name"];
            $_SESSION['username']=$this->name;
            $this->isAdmin=($resultset["isadmin"]==0)?FALSE:TRUE;
            if ($this->isAdmin)
                header("Location: ../admin/dashboard.php");
            else
                header("Location: ../shop/dashboard.php");
        } else
            header("Location: ../shop/login.php");
    }

    public function logout():void{
        if (isset($this->id)){
            unset($this->id);
            unset($this->name);
            unset($_SESSION["userID"]);
            unset($_SESSION["username"]);

        }

        header("Location: ../shop/login.php");

    }





}






?>









