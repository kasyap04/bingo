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

    public function getTimeDifference($start, $end){
        return 'ok' ;
    }

    // public function __destruct(){
    //     mysqli_close($this->conn) ;
    // }
}


$bingo = new Bingo();
