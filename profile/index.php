<?php
include "../static/php/profile.php" ;

$profile = new Profile() ;

$userId = $_GET['id'] ?? $profile->userId ;

$userId = $profile->sanitize($userId) ;

// $profile->getFromGameRecord(26) ;
$summery = $profile->getGameSummery($userId) ;


$getWonTotalGame = $profile->getWonTotalGame($userId) ;
$WON_GAMES = $getWonTotalGame['win'] ?? 0 ;
$TOTAL_GAMES = $getWonTotalGame['total'] ?? 0 ;
$LOSE_GAMES = 0; 

if($summery){
    foreach($summery as $row){
        if($row['status'] == 'Lose'){
            $LOSE_GAMES ++ ;
        }
    }
}




?>
<!DOCTYPE html>
<html>
    <head>
        <title>Profile | <?php echo $profile->getNameById($userId) ?>@BINGO</title>
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
        <link href="/static/css/profile.css" rel='stylesheet'>
    </head>
    <body>
        <header class="body-header">
            <section> <h2>Profile</h2> </section>
            <section>
                <div class="menu-outer" onclick="toggleMenuList()">
                <!-- <span class="material-icons" onclick="toggleExitGameCont(1)">logout</span> -->
                    <!-- <div class="menu-icon"></div> -->
                </div>
            </section>
        </header>

        <main class="profile-main-cont">
            
            <main class="profile-user-cont">
                <div class="user-profile-cont">
                    <span class="material-icons">account_circle</span>
                    <article><?php echo $profile->getNameById($userId) ?></article>
                </div>
                <div class="user-result-cont">
                    <section>
                        <article><?php echo $WON_GAMES ?></article>
                        <article>Won</article>
                    </section>
                    <section>
                        <article><?php echo $TOTAL_GAMES ?></article>
                        <article>Total</article>
                    </section>
                    <section>
                        <article><?php echo $LOSE_GAMES ?></article>
                        <article>Failed</article>
                    </section>
                </div>
            </main>  <!--- profile-user-cont  -->
    
    
            <main class="profile-summery-cont">
                <article class="profile-summery-heder">Summery</article>
                <div class="profile-summery-div">
                    <section class="summery-heading-cont">
                        <article class="summery-teammate">Teammate</article>
                        <article class="summery-status">Status</article>
                        <article class="summery-duration">Duration</article>
                        <article class="summery-date">Date</article>
                    </section>
                <?php
                if($summery){
                    foreach($summery as $row){

                        $css = '' ;
                        if($row['status'] == 'You exit' || $row['status'] == 'Teammate exit' || $row['status'] == 'Lose'){
                            $css = 'status-lose' ;
                        } elseif($row['status'] == 'Win'){
                            $css = 'status-win' ;
                        } elseif($row['status'] == 'Draw'){
                            $css = 'status-draw' ;
                        }

                        echo '<section class="summery-data-cont">
                        <article class="summery-teammate">'.$row['teammate'].'</article>
                        <article class="summery-status '.$css.'">'.$row['status'].'</article>
                        <article class="summery-duration">'.$row['time'].'</article>
                        <article class="summery-date">'.$row['date'].'</article>
                    </section>' ;
                    }
                } else
                echo '<article class="summery-doData">No data found</article> ' ;
                ?>
                </div>
            </main>  <!---profile-summery-cont  -->

        </main>

    </body>
<!--  
    ____  ___    ____  ____  ____  ______
   / __ \/   |  / __ \/ __ \/ __ \/_  __/
  / /_/ / /| | / /_/ / /_/ / / / / / /   
 / ____/ ___ |/ _, _/ _, _/ /_/ / / /    
/_/   /_/  |_/_/ |_/_/ |_|\____/ /_/                      
 
-->
</html>