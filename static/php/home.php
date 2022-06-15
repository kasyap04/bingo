<?php

include "base.php" ;




class Home extends Bingo{

    public function __construct(){
        parent::__construct() ;
        $this->userId() ;

        if($this->userId == 'no'){
            header("Location:/login") ;
        }

        date_default_timezone_set("Asia/Kolkata") ;
    }

    public function getMyName(){
        $id = $this->userId ;
        $res = mysqli_query($this->conn, "SELECT name FROM user WHERE u_id = $id") ;
        return mysqli_fetch_array($res)['name'] ;
    }

    public function checkUserExist($id){
        $qry = mysqli_query($this->conn, "SELECT u_id FROM user WHERE u_id = $id") ;
        return mysqli_num_rows($qry) > 0 ;
    }

    public function checkAlreadyMyFriend($name) {
        $qry = mysqli_query($this->conn, "SELECT COUNT(friends.user2) AS count, friends.status FROM friends JOIN (SELECT user.u_id FROM user WHERE user.name = '$name') AS u ON u.u_id = friends.user2") ;
        $result = mysqli_fetch_array($qry) ;
        $ret = '' ;
        if($result['count'] > 0){
            if($result['status'] == 0){
                $ret = 'friend_request' ;
            } else
            $ret = 'friend' ;
        } else
        $ret = 'add' ;

        return $ret ;
    }

    public function getMyFriend($name) {
        $myname = $this->getMyName() ;
        $qry = mysqli_query($this->conn, "SELECT u_id, name FROM user WHERE name = '$name' AND name != '$myname'") ;
        if(mysqli_num_rows($qry) == 1){
            return mysqli_fetch_assoc($qry) ;
        } else
        return 'no' ;
    }

    public function getFriendRequests(){
        $qry = mysqli_query($this->conn, "SELECT friends.user2 AS id, user.name FROM friends JOIN user ON user.u_id = friends.user2 WHERE friends.user1 = $this->userId AND friends.status = 0") ;
        $result = [] ;
        while($row = mysqli_fetch_assoc($qry)){
            $result[] = $row ;
        }
        return $result ;
    }

    public function getMyFriendList(){
        $qry = mysqli_query($this->conn, "SELECT friends.user2 AS id, user.name FROM friends LEFT JOIN user ON friends.user2 = user.u_id WHERE friends.user1 = $this->userId AND friends.status = 1") ;
        $result = [] ;
        while($row = mysqli_fetch_assoc($qry)){
            $result[] = $row ;
        }

        return $result ;
    }

    public function sendFriendRequest($id){
        if($this->checkUserExist($id) ){
            
            $checkAlreadyRequested = mysqli_query($this->conn, "SELECT status FROM friends WHERE user2 = $id AND user1 = $this->userId") ;
            if(mysqli_num_rows($checkAlreadyRequested) > 0){
                $status = mysqli_fetch_assoc($checkAlreadyRequested)['status'] ;
                if($status == 0){
                    return "ar" ; // already requested
                } else
                return "af" ;  // alreqady friend
            } else {
                if(mysqli_query($this->conn, "INSERT INTO friends (user1, user2, status) VALUES ($this->userId, $id, 0)")){
                    return 's' ;
                } else
                return 'e' ;
            }

        } else
        return "unf" ;  // user not found
    }

    public function acceptFriendRequest($id){
        if(mysqli_query($this->conn, "UPDATE friends SET status = 1 WHERE user1 = $this->userId AND user2 = $id")){
            echo $this->out('s') ;
        } else
        echo $this->out('e') ;
        // echo $this->out('s') ;
    }

    public function rejectFriendRequest($id){
        if(mysqli_query($this->conn, "DELETE FROM friends WHERE user1 = $this->userId AND user2 = $id")){
            echo $this->out('s') ;
        } else
        echo $this->out('e') ;
        // echo $this->out("s") ;
    }

    public function sendPlayRequest($teammateId){
        $teammateId = $this->sanitize($teammateId) ;
        $time = date("Y-m-d H:i:s") ;
        if(mysqli_query($this->conn, "UPDATE user SET request_id = $this->userId, last_request = '$time' WHERE u_id = $teammateId")){
            return "s" ;
        } else
        return "e" ;
    }

    public function checkUserAlreadyMatching(){
        $qry = mysqli_query($this->conn, "SELECT COUNT(*) AS count FROM team WHERE player1 = $this->userId OR player2 = $this->userId") ;
        return mysqli_fetch_assoc($qry)['count'] == 0 ;
    }

    public function checkPlayRequest(){

        if($this->checkUserAlreadyMatching()){
            $timeNow = date("Y-m-d H:i:s") ;
            $newDate = date("Y-m-d H:i:s", strtotime($timeNow."-10 seconds")) ;
            echo "$timeNow\n$newDate" ;
            $qry = mysqli_query($this->conn, "SELECT COUNT(u2.u_id) AS count, u2.name, u2.u_id FROM user u2 JOIN user u1 ON u1.request_id = u2.u_id WHERE u1.last_request >= '$newDate' AND u1.u_id = $this->userId") ;
            $res = mysqli_fetch_assoc($qry) ;
            // echo $this->userId ;
            print_r($res) ;
        } else
        return 'pm' ;  // player matched

        // $timeNow = date("Y-m-d H:i:s") ;

        // $newDate = date("Y-m-d H:i:s", strtotime($timeNow."-10 seconds")) ;
        // echo "$timeNow\n$newDate" ;

        // $qry = mysqli_query($this->conn, "SELECT ") ;
        // echo $this->getTimeDifference(1, 2) ;
    }

    // SELECT COUNT(u2.u_id) AS count, u2.name, u2.u_id FROM user u1 JOIN user u2 ON u1.u_id = u2.u_id WHERE u1.last_request >= '2022-06-13 21:26:20' AND u1.u_id = 1;

}

