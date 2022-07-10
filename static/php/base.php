<?php

class Bingo{
    protected $conn ;

    private $HOST       = 'localhost' ;
    private $USER       = 'root' ;
    private $PASSWORD   = '' ;
    private $DB         = 'bingo' ;

    public $userId = "no" ;

    function __construct(){
        $this->conn = $conn = mysqli_connect($this->HOST, $this->USER, $this->PASSWORD, $this->DB) ;
        if(mysqli_connect_error()){
            die("ERROR:Could not connect") ;
        }
    }

    public function sanitize($value){
        return mysqli_real_escape_string($this->conn, $value) ;
    }

    public function userId(){
        session_start() ;
        $id = "no" ;
        if(isset($_SESSION['login'])){
            $id = $_SESSION['login'] ;
        } elseif(isset($_COOKIE['login'])){
            $id = $_COOKIE['login'] ;
        }   

        $this->userId = $id ;

        // return $userId ;
    }

    public function gotoLogin($check){
        if($check == "php"){
            header("Location:/login") ;
        } else
        echo $this->out("l") ;
    }


    public function out($msg){
        return "sta[\"$msg\"]end";
    }

    public function getTeammate(){
        if($this->userId == 'no'){
            return False ;
        } else {
            $qry = mysqli_query($this->conn, "SELECT user.u_id, user.name FROM user JOIN team ON user.u_id = team.player2 OR user.u_id = team.player1 WHERE (team.player1 = $this->userId OR team.player2 = $this->userId) AND user.u_id != $this->userId AND team.active = 1") ;
            if(mysqli_num_rows($qry) == 1){
                return mysqli_fetch_assoc($qry) ;
            } else
            return False ;
        }
    }

    // public function __destruct(){
    //     mysqli_close($this->conn) ;
    // }
}

$bingo = new Bingo();
