<?php
    require_once "db.php";

    class User {
        private $id;
        private $email;
        private $username;
        private $password;
        private $confirmPass;

        private $db;

        public function __construct($email, $username, $password) {
            $this->email = $email;
            $this->username = $username;
            $this->password = $password;

            $this->db = new DB();
        }

        public function getId(){
            return $this->id;
        }
        public function getEmail(){
            return $this->email;
        }
        public function getUseraname(){
            return $this->username;
        }
        public function getPassword(){
            return $this->password;
        }
        public function getConfirmPass(){
            return $this->confirmPass;
        }

        public function exists(){
            $sql = "SELECT * FROM users WHERE email=:email";
            $preparedStmt = DB::getConnection()->prepare($sql);
            try{
                $preparedStmt->execute(["email" => $this->email]);
                //$query = ["successfullyExecuted" => true];
                //return $query;
            }catch(PDOException $e){
                //echo $e->getMessage();
                $query = ["successfullyExecuted" => false, "errMessage" => $e->getMessage()];
                return $query;
            }
            $user_assoc = $preparedStmt->fetch(PDO::FETCH_ASSOC);
            if($user_assoc){
                $query = ["successfullyExecuted" => true, "userExists" => true];
                return $query;
            }
            else{
                $query = ["successfullyExecuted" => true, "userExists" => false];
                return $query;
            }
        }

    }

?>