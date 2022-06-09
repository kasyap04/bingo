<?php

include "base.php" ;

class Login extends Bingo{

    public function checkUsernameExist($username){
        $res = mysqli_query($this->conn, "SELECT u_id FROM user WHERE name = '$username' ") ;
        return mysqli_num_rows($res) == 0 ;
    }

    public function login($username, $password){
        $username = $this->sanitize($username) ;
        $password = md5($this->sanitize($password)) ;
        if(!empty($username) && !empty($password)){
            if($this->checkUsernameExist($username)){
                if(mysqli_query($this->conn, "INSERT INTO user (name, password) VALUES ('$username', '$password')")){
                    $userId = mysqli_insert_id($this->conn) ;
                    $_SESSION['login'] = $userId ;
                    setcookie('login', $userId, time() + (86400 * 30), "/");
                    echo $this->out("s") ;
                }
            } else
            echo $this->out("ue") ; // username already exist
        } else
        $this->out("ef") ;  // empty fields
    }


}