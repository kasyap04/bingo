<?php
include "base.php" ;

class Profile extends Bingo{

    public function __construct(){
        parent::__construct() ;
        $this->userId() ;

        if($this->userId == 'no'){
            header("Location:/login") ;
        }
    }

    public function getWonTotalGame($id){
        $qry = mysqli_query($this->conn, "SELECT t_game AS total, w_game AS win FROM user WHERE u_id = $id") ;
        return mysqli_fetch_assoc($qry) ;
    }

    public function timeCalculation($from_date, $to_date){
        $from_date = strtotime($from_date) ;
        $to_date = strtotime($to_date) ;
        return date("i:s", $to_date - $from_date)."s" ;
    }

    public function dateToString($date){
        $date = strtotime($date) ;
        $date = date("M d, Y", $date) ;

        return $date ;
    }

    public function getFromGameRecord($team_id, $id){
        $qry = mysqli_query($this->conn, "SELECT winner, `exit`, started, ended FROM record WHERE t_id = $team_id") ;
        $res = mysqli_fetch_assoc($qry) ;

        $count = mysqli_num_rows($qry) ;
        // print_r($res) ;
        // echo "getFromGameRecord\n" ;
        $result = [] ;
        
        if($count  != 0){
            if($count > 1){
                $result['status'] = 'Draw' ;
            } else {
                if($res['exit'] != NULL){
                    if($res['exit'] == $id){
                        $result['status'] = 'You exit' ;
                    } else
                    $result['status'] = 'Teammate exit' ;
                } else {
                    if($res['winner'] == $id){
                        $result['status'] = 'Win' ;
                    } else
                    $result['status'] = 'Lose' ;
                }
            }
            $result['time'] = $this->timeCalculation($res['started'], $res['ended']) ;
            $result['date'] = $this->dateToString($res['started']) ;
    
            return $result ;
        } else 
        return FALSE ;

    }

    public function getGameSummery($id){
        $team_qry = mysqli_query($this->conn, "SELECT t_id, player1, player2 FROM team WHERE player1 = $id OR player2 = $id ORDER BY t_id DESC") ;
        $result = [] ;
        if(mysqli_num_rows($team_qry) > 0){
            while($teams = mysqli_fetch_assoc($team_qry)){
                // print_r($teams) ; 
                $team_id = $teams['t_id'] ;
                $teammate_id = $teams['player1'] == $id ? $teams['player2'] : $teams['player1'] ;
                $teammate_name = $this->getNameById($teammate_id) ;
                $from_team = $this->getFromGameRecord($team_id, $id) ;
                if(!$from_team){
                    break ;
                    return FALSE ;
                }
                array_push($result, [
                    'teammate' =>$teammate_name,
                    'status' => $from_team['status'],
                    'time' => $from_team['time'],
                    'date' => $from_team['date'] 
                ]) ;
            }
            return $result ;
        } else
        return FALSE ;

    } 

}