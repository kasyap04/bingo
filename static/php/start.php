<?php
include "base.php" ;

class Game extends Bingo{

    public $myNums ;
    public $teammateNums ;
    public $teamId ;
    public $isTeamActive = TRUE ;

    public function __construct(){
        parent::__construct() ;
        $this->userId() ;

        if($this->userId == 'no'){
            header("Location:/login") ;
        }

        $qry = mysqli_query($this->conn, "SELECT * FROM team WHERE (player1 = $this->userId OR player2 = $this->userId) AND active=1;") ;
        if(mysqli_num_rows($qry) > 0){
            $res = mysqli_fetch_assoc($qry) ;
            $this->teamId = $res['t_id'] ;
            if($res['player1'] == $this->userId){
                $this->myNums = json_decode($res['num_p1'], TRUE) ;
                $this->teammateNums = json_decode($res['num_p2'], TRUE) ;
            } else {
                $this->myNums = json_decode($res['num_p2'], TRUE) ;
                $this->teammateNums = json_decode($res['num_p1'], TRUE) ;
            }            
        } else
        $this->isTeamActive = FALSE ;

        date_default_timezone_set('Asia/Calcutta'); 
    }

    public function checkTeamActive(){
        if(!$this->isTeamActive){
            header("Location:/") ;
        }
    }

    public function insertIntoRecord($user_id, $status){
        $end = date("Y-m-d H:i:s") ;
        $start = $_SESSION['bingo_started'] ;

        mysqli_query($this->conn, "INSERT INTO record VALUES (NULL, $this->teamId, $user_id, '$status', '$start', '$end')") ;

        mysqli_query($tis->conn, "UPDATE team SET num_p1 = NULL AND num_p2 = NULL WHERE t_id = $this->teamId") ;

        // $checkTeam = mysqli_query($this->conn, "SELECT t_id FROM record WHERE t_id = $this->teamId") ;
        // if(mysqli_num_rows($checkTeam) == 0){
        //     mysqli_query($this->conn, "INSERT INTO record (t_id, started) VALUES ($this->teamId, '$date')")  ;
        //     $this->totalGamePlayed($this->userId) ;
        // }

        
    }

    // public function updateRecord($user_id, $status){
    //     $date = date("Y-m-d H:i:s") ;
    //     mysqli_query($this->conn, "UPDATE record SET  ended = '$date' WHERE t_id = $this->teamId") ;
    // }

    // public function insertRecordAll(){
    //     $ended = date("Y-m-d H:i:s") ;
    //     $teamateId = $this->getTeammate()['u_id'] ;
    //     $game_start_time_qry = mysqli_query($this->conn, "SELECT started FROM record WHERE t_id = $this->teamId") ; 
    //     $game_start_time = mysqli_fetch_assoc($game_start_time_qry)['started'] ;
    //     mysqli_query($this->conn, "INSERT INTO record (t_id, winner, started, ended) VALUES($this->teamId, $teamateId, '$game_start_time', '$ended')") ;
    // }

    // public function totalGamePlayed($id){
    //     mysqli_query($this->conn, "UPDATE user SET t_game = t_game + 1 WHERE u_id = $id") ;
    // }

    // public function updateWonGames($id){
    //     mysqli_query($this->conn, "UPDATE user SET w_game = w_game + 1 WHERE u_id = $id") ;
    // }

    public function checkBingo($total_numbers, $playerNum){
        $count = 0 ;
        
        // $total_numbers = ['23', '19', '12', '06', '11', '25', '07', '10', '15', '13', '24', '08', '20', '16', '01'] ;

        for($i = 0; $i < 5; $i++){
            $row = 0 ;
            for($j = 0; $j < 5;  $j++){
                if(in_array($playerNum[$i][$j], $total_numbers)){
                    $row ++ ;
                }
                if($row == 5){
                    $count ++ ;
                }
            }
        }

        for($i = 0; $i < 5; $i++){
            $col = 0 ;
            for($j = 0; $j < 5;  $j++){
                if(in_array($playerNum[$j][$i], $total_numbers)){
                    $col ++ ;
                    // echo $playerNum[$j][$i]."\n" ;
                }
                if($col == 5){
                    $count ++ ;
                }
            }
        }

        for($i = 0, $j = 0; $i < 5; $i++, $j++){
            $diagonal1[] = $playerNum[$i][$j] ;
        }

        for($i = 0, $j = 4; $i < 5; $i++, $j--){
            $diagonal2[] = $playerNum[$i][$j] ;
        }

        $dia1 = 0 ;
        for($i = 0; $i < 5; $i++){
            if(in_array($diagonal1[$i], $total_numbers)){
                $dia1 ++ ;
                // echo $diagonal1[$i]."\n" ;
            }
            if($dia1 == 5){
                $count ++ ;
            }
        }

        $dia2 = 0 ;
        for($i = 0; $i < 5; $i++){
            if(in_array($diagonal2[$i], $total_numbers)){
                $dia2 ++ ;
                // echo $diagonal1[$i]."\n" ;
            }
            if($dia2 == 5){
                $count ++ ;
            }
        }            

        return $count ;
    }

    public function selectedNumbers($num){
        $qry = mysqli_query($this->conn, "SELECT p_id FROM play WHERE t_id = $this->teamId AND selected = '$num'") ;
        if(mysqli_num_rows($qry) <= 0){
            if(mysqli_query($this->conn, "INSERT INTO play (t_id, selected, selected_by) VALUES($this->teamId, '$num', $this->userId)")){
                return 's' ;
            } else
            return 'e' ;
        } else
        return 'nas' ; // number already selected 
    }

    public function getMyBingo(){
        $result_array = array('status' => FALSE, 'bingo' => [], 'numbers' => [], 'win' => []) ;
        
        if($this->myNums && $this->teammateNums){

            $selected_numbers = mysqli_query($this->conn, "SELECT play.selected FROM play JOIN team ON team.t_id = play.t_id WHERE team.active = 1 AND (team.player1 = $this->userId OR team.player2 = $this->userId)") ;
            
            if($this->isTeamActive){
                $result_array['status'] = TRUE ;
                if(mysqli_num_rows($selected_numbers) > 0){

                    while($res = mysqli_fetch_assoc($selected_numbers)){
                        $total_numbers[] = $res['selected'] ;
                    }
        
                    $my_bingo_val = $this->checkBingo($total_numbers, $this->myNums) ;
                    $teammate_bingo_val = $this->checkBingo($total_numbers, $this->teammateNums) ;

        
                    $result_array['win'] = FALSE ;
                   if($my_bingo_val == 5 && $teammate_bingo_val == 5){
                        $result_array['win'] = 2 ;

                        self.insertIntoRecord($this->userId, 'draw') ;
                        
        
                        // $this->updateRecord($this->userId) ;
                        // $this->insertRecordAll() ;

                        // $this->updateWonGames($this->userId) ;
                    
                        // $this->exitGame(TRUE) ;

        
                   } else {
                        if($my_bingo_val == 5){
                            $result_array['win'] = 'win' ;

                            self.insertIntoRecord($this->userId, 'win') ;
        
                            // $this->updateRecord($this->userId) ;

                            // $this->updateWonGames($this->userId) ;

        
                        } elseif($teammate_bingo_val == 5){
                            $result_array['win'] = 'lose' ;
                            self.insertIntoRecord($this->userId, 'lose') ;
                        }
                   }
                    
                    for($i = 0; $i < count($total_numbers); $i++){
                        if(strlen($total_numbers[$i]) <= 1){
                            $total_numbers[$i] = '0'.$total_numbers[$i] ;
                        }
                    }
        
                    $result_array['bingo'] = $my_bingo_val ;
                    $result_array['numbers'] = $total_numbers ;
        
                    // print_r($result_array) ;
                    
                    // return $result_array ; 
                }

            } 

        }
        
        return $result_array ; 
    }

    // public function createTeamWhileExit(){
    //     $teammateId = $this->getTeammate()['u_id'] ;
    //     echo $teamateId ;
    //     $team_check_qry = mysqli_query($this->conn, "SELECT t_id FROM team WHERE active = 1 AND (player1 = $this->userId OR player2 = $this->userId)") ;
    //     if(mysqli_num_rows($team_check_qry) == ){
    //         mysqli_query($this->conn, "INSERT INTO team (player1, player2) VALUES ($this->userId, $teammateId)") ;
    //     }
    // }

    public function exitGame($flag = ''){
        if($flag){
            $this->createTeamWhileExit() ;
        }
        if(mysqli_query($this->conn, "UPDATE team SET active = 0 WHERE t_id = $this->teamId")){
            $date = date("Y-m-d H:i:s") ;
            mysqli_query($this->conn, "UPDATE record SET exit = $this->userId, ended = '$date' WHERE t_id = $this->teamId") ;
            
            return 's' ;
        } else
        return 'e' ;
    } 

}