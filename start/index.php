
<?php
include "../static/php/start.php" ;

if(!isset($_SESSION['bingo_started'])){
    $_SESSION['bingo_started'] = date("Y-m-d H:i:s") ;
}

$game = new Game() ;


if(($_SERVER['REQUEST_METHOD'] === 'POST')){
    $swift = $_POST['s'] ;
    if(!empty($swift)){
        switch($swift){

            case 1:
                $num = $game->sanitize($_POST['n']) ;
                $res = $game->selectedNumbers($num) ;
                echo $game->out($res) ;
            break ;

            case 2:
                $result = $game->getMyBingo() ;
                $res = json_encode($result, true) ;
                echo "sta[".$res."]end" ;
                // if($result['win'] != FALSE){
                //     $game->exitGame(TRUE) ;                  
                // }
            break ;

            case 3:
                $exit = $game->exitGame() ;
                echo $game->out($exit) ;
            break ;

        }
    } else
        echo $game->out("e") ;
    
    exit() ;
} else {
    
$game->checkTeamActive() ;


// print_r($game->getMyBingo() );

if($game->userId == 'no'){
    header("Location:/login") ;
}

$MY_NAME = $game->getMyName() ;

$teammate_name = $game->getTeammate()['name']  ;

}


?>

<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $MY_NAME ;  ?>@Bingo</title>
        <meta charset='UTF-8'>
        <meta name='theme-color' content='#f8f8ff'>
        <meta http-equiv='Cache-Control' content='no-cache, must-revalidate'>
        <meta http-equiv='Pragma' content='no-cache'>
        <meta http-equiv='Expires' content='0'>
        <meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no'>
        <script src='/static/js/jquery.js'></script>
        <link href="/static/font/font.css" rel='stylesheet'>
        <link href="/static/icon/icon.css" rel='stylesheet'>
        <link href="/static/css/base.css" rel='stylesheet'>
        <link href="/static/css/start.css" rel='stylesheet'>
    </head>
    <body>
        <header class="body-header">
            <section> <h2>BINGO</h2> </section>
            <section>
                <div class="menu-outer">
                <span class="material-icons" onclick="toggleExitGameCont(1)">logout</span>
                    <!-- <div class="menu-icon"></div> -->
                </div>
            </section>
        </header>

        <div class="teammate-cont" >
            <article>
                <span class="textOverDots" id="myName"><?php echo $MY_NAME ; ?></span>
            </article>
            <article>
                <span id="teammateName" class="textOverDots"><?php echo $teammate_name ;?></span>
            </article>
        </div>

        <div class="table-canvas">
            <div class="table-canvas-bingo-cont">
                <article>B</article>
                <article>I</article>
                <article>N</article>
                <article>G</article>
                <article>O</article>
            </div>
            <main class="table-cont" id="table">
            <?php

            if($game->myNums){
                for($i = 0; $i < 5; $i++){
                    for($j = 0; $j < 5; $j++){
                        echo "<div> <input type='text' value='".$game->myNums[$i][$j]."' maxlength='2' readonly> </div>\n" ;
                    }
                }
            }

            ?>
            </main>
        </div>

        <div class="winner-cont-outer">
            <div class="winner-cont">
                <div class="winner-cont-top">
                    <span class="material-icons handshake">handshake</span>
                </div>
                <div class="winner-cont-bottom">
                    <article>YOU</article>
                    <article>BOTH WIN</article>
                </div>
            </div>
            <div class="game-exit-cont">
                <button class="gameOver-back-button" onclick="exitGame()"><span class="material-icons">arrow_back</span> Back</button>
                <article>Game will exit in <label id="exitTimer">4</label>s</article>
            </div>
        </div>

        <div class="linemsg-cont">This is a message</div>

        <div class="exitGamme-confirm-outer">
            <div class="exitGamme-confirm-cont">
                <article>Are you sure you want to exit the game?</article>
                <section>
                    <button onclick="exitGame()">Yes, im leaving</button>
                    <button onclick="toggleExitGameCont(0)">Not now</button>
                </section>
            </div>
        </div>

        <div class="teammateExit-cont">
            <article> <b>Teammate exit!</b> </article>
            <article>Game will exit in <label id="gameExitTimer">5</label>s</article>
        </div>

        <div class='loading-outer'>
            <div class='loadingCont'>
                <div class="loading-icon" id="loadingIcon">
                    <div class='clock1'>
                        <div class='big-one'></div>
                    </div>
                    <div class='clock2'>
                        <div class='small-one'></div>
                    </div>
                    <label>Loading..</label>
                </div>
            </div>
        </div>
        <script src="/static/js/base.js"></script>
        <script src="/static/js/start.js"></script>
    </body>
        <!--  
    ____  ___    ____  ____  ____  ______
   / __ \/   |  / __ \/ __ \/ __ \/_  __/
  / /_/ / /| | / /_/ / /_/ / / / / / /   
 / ____/ ___ |/ _, _/ _, _/ /_/ / / /    
/_/   /_/  |_/_/ |_/_/ |_|\____/ /_/                      
 
-->
</html>