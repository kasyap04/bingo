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
        $friendId = $this->getMyFriend($name)['u_id'] ;
        $qry = mysqli_query($this->conn, "SELECT COUNT(user2) AS count, status FROM friends WHERE user1 = $this->userId AND user2 = $friendId") ;
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

        for($i = 0; $i < count($result); $i++){
            $friend_id = $result[$i]['id'] ;
            // echo $friend_id ;
            $qry = mysqli_query($this->conn, "SELECT COUNT(t_id) AS count FROM team WHERE (player1 = $friend_id OR player2 = $friend_id) AND active = 1") ;
            if(mysqli_fetch_assoc($qry)['count'] == 0){
                $result[$i]['matching'] = FALSE ;
            } else
            $result[$i]['matching'] = TRUE ;
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
        $qry = mysqli_query($this->conn, "SELECT COUNT(*) AS count FROM team WHERE (player1 = $this->userId OR player2 = $this->userId) AND active = 1") ;
        return mysqli_fetch_assoc($qry)['count'] == 0 ;
    }

    public function checkPlayRequest(){

        if($this->checkUserAlreadyMatching()){
            $timeNow = date("Y-m-d H:i:s") ;
            $newDate = date("Y-m-d H:i:s", strtotime($timeNow."-10 seconds")) ;
            $qry = mysqli_query($this->conn, "SELECT COUNT(u2.u_id) AS count, u2.name, u2.u_id, u1.last_request FROM user u2 JOIN user u1 ON u1.request_id = u2.u_id WHERE u1.last_request >= '$newDate' AND u1.u_id = $this->userId") ;
            $res = mysqli_fetch_assoc($qry) ;
            return $res;
        } else
        return array('count' => 'pm') ;  // player matched

    }

    public function rejectRequest(){
        if(mysqli_query($this->conn, "UPDATE user SET request_id = NULL, last_request = NULL WHERE u_id = $this->userId")){
            return 's' ;
        } else
        return 'e' ;
    }

    // SELECT COUNT(u2.u_id) AS count, u2.name, u2.u_id FROM user u1 JOIN user u2 ON u1.u_id = u2.u_id WHERE u1.last_request >= '2022-06-13 21:26:20' AND u1.u_id = 1;

    public function acceptRequest(){
        $timeNow = date("Y-m-d H:i:s") ;
        $newDate = date("Y-m-d H:i:s", strtotime($timeNow."-10 seconds")) ;
        $qry = mysqli_query($this->conn, "SELECT COUNT(u2.u_id) AS count, u2.name, u2.u_id, u1.last_request FROM user u2 JOIN user u1 ON u1.request_id = u2.u_id WHERE u1.last_request >= '$newDate' AND u1.u_id = $this->userId") ;
        $res = mysqli_fetch_assoc($qry) ;
        return $res ;
    }

    public function createTeam($teammate_id){
        if(mysqli_query($this->conn, "INSERT INTO team (player1, player2) VALUES ($this->userId, $teammate_id)")){
            $this->rejectRequest() ;
            return TRUE ;
        } else
        return FALSE ;
    }

    public function deactivateTeam(){
        if(mysqli_query($this->conn, "UPDATE team SET active = 0 WHERE player1 = $this->userId OR player2 = $this->userId")){
            return TRUE ;
        } else
        return FALSE ;
    }

    public function checkTeamCreated(){
        $qry = mysqli_query($this->conn, "SELECT user.name, user.u_id FROM user JOIN team ON team.player1 = user.u_id OR team.player2 = user.u_id WHERE (team.player1 = $this->userId OR team.player2 = $this->userId) AND active = 1") ;
        if(mysqli_num_rows($qry) > 0){
            while($res = mysqli_fetch_assoc($qry)){
               if($res['u_id'] != $this->userId){
                    $teammate = $res ;
               }
            }
            return $teammate['name'] ;
        }
    }

    public function checkExitOrStart(){
        $qry = mysqli_query($this->conn, "SELECT player1, player2, num_p1 AS num1, num_p2 AS num2 FROM team WHERE (player1 =$this->userId OR player2 = $this->userId) AND active = 1") ;
        $res = array('status' => 'success', 'start' => [], 'active' => TRUE) ;
        if($qry){
            if(mysqli_num_rows($qry) > 0){
                $team = mysqli_fetch_assoc($qry) ;
                if($team['player1'] == $this->userId){
                    if($team['num1']){
                        array_push($res['start'], 'me') ;
                    }
                    if($team['num2']){
                        array_push($res['start'], 'teammate') ;
                    }
                } elseif($team['player2'] == $this->userId){
                    if($team['num2']){
                        array_push($res['start'], 'me') ;
                    }
                    if($team['num1']){
                        array_push($res['start'], 'teammate') ;
                    }
                }
            } else
            $res['active'] = FALSE ;
        } else
        $res['status'] = 'failed' ;

        return $res ;
    }

    public function imReady($table){
        $table = json_decode($table) ;
        $k = 0 ;
        $table2D = [] ;
        for($i = 0; $i < 5; $i++){
            for($j = 0; $j < 5; $j++, $k++){
                $table2D[$i][$j] = $table[$k] ;
            }
        }
        $table = json_encode($table2D) ;

        $qry = "UPDATE team SET num_p1 = CASE WHEN player1 = $this->userId THEN '$table' ELSE num_p1 END, num_p2 = CASE WHEN player2 = $this->userId THEN '$table' ELSE num_p2 END WHERE active = 1" ;
        if(mysqli_query($this->conn, $qry)){
            return 's' ;
        } else
        return 'e' ;
    }

    public function imNotReady(){
        $qry = "UPDATE team SET num_p1 = CASE WHEN player1 = $this->userId THEN NULL ELSE num_p1 END, num_p2 = CASE WHEN player2 = $this->userId THEN NULL ELSE num_p2 END WHERE active = 1;" ;
        if(mysqli_query($this->conn, $qry)){
            return 's' ;
        } else
        return 'e' ;
    }

    public function sendFeedback($feedback){
        if(mysqli_query($this->conn, "UPDATE user SET feedback = '$feedback' WHERE u_id = $this->userId")){
            return 's' ;
        }
        return 'e' ;
    }
}