<?php

include "static/php/home.php" ;

$home = new Home() ;



$MY_NAME = $home->getMyName() ;


if(($_SERVER['REQUEST_METHOD'] === 'POST')){
    if($home->userId == 'no'){
        echo $home->out('l') ;
    } else {
        $swift = $_POST['s'] ;

        if(!empty($swift)){
            switch($swift){
                case 1:
                    $username = $_POST['u'] ;
                    if(!empty($username)){
                        $username = $home->sanitize($username ) ;
                        $check_friend = $home->checkAlreadyMyFriend($username) ;
                        $result_status = '' ;
                        if($check_friend == 'friend_request'){
                            $result_status = 'fr' ; // friend request
                        } elseif($check_friend == 'friend'){
                            $result_status = 'yf' ; // your friend
                        } elseif($check_friend == 'add'){
                            $result_status = 'add' ;
                        }

                        $friend = $home->getMyFriend($username) ;
                        if(is_array($friend)){
                            echo "sta[\"$result_status\", ".json_encode($friend)."]end" ;
                        } else
                        echo $home->out("nff") ; // no friend found
                    } else
                    echo $home->out("unf") ; // username not found
                break ;

                case 2:
                    $id = $_POST['id'] ;
                    if(!empty($id)){
                        $id = $home->sanitize($id) ;
                        $res = $home->sendFriendRequest($id) ;
                        echo  $home->out($res) ;
                    } else
                    echo $home->out("e") ;
                break ;

                case 3:
                    $id = $_POST['id'] ;
                    if(!empty($id)){
                        $id = $home->sanitize($id) ;
                        $home->acceptFriendRequest($id) ;
                    }
                break ;

                case 4:
                    $id = $_POST['id'] ;
                    if(!empty($id)){
                        $id = $home->sanitize($id) ;
                        $home->rejectFriendRequest($id) ;
                    }
                break ;
            }
        } else
        echo $home->out("e") ;

        exit() ;
    }
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $MY_NAME ;  ?> @ Bingo</title>
        <meta charset='UTF-8'>
        <meta name='theme-color' content='#66be66'>
        <meta http-equiv='Cache-Control' content='no-cache, must-revalidate'>
        <meta http-equiv='Pragma' content='no-cache'>
        <meta http-equiv='Expires' content='0'>
        <meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no'>
        <script src='static/js/jquery.js'></script>
        <link href="static/font/font.css" rel='stylesheet'>
        <link href="static/icon/icon.css" rel='stylesheet'>
        <link href="static/css/base.css" rel='stylesheet'>
        <link href="static/css/home.css" rel='stylesheet'>
    </head>
    <body>
        <header class="body-header">
            <section> <h2>BINGO</h2> </section>
            <section>
                <div class="menu-outer" onclick="openMenuList()">
                    <div class="menu-icon"></div>
                </div>
            </section>
        </header>

        <header class="user-header">
            <section class="username-cont">
                <span class="material-icons">account_circle</span>
                <span><?php echo $MY_NAME  ;  ?></span>
            </section>
            <section class="startBtn-cont">
                <section class="startBtn-inner">
                    <button>READY</button> <br>
                    <span>Game starts in <label>10</label>s</span>
                </section>
            </section>
        </header>

        <div class="teammate-cont">
            <article>
                <span class="textOverDots"><?php echo $MY_NAME  ;  ?></span>
            </article>
            <article>
                <span id="teammateName" class="textOverDots">teammate name</span>
                <span onclick="closeTeammate()" class="material-icons remove-teammate">close</span>
            </article>
        </div>

        <main class="table-cont" id="table">
            <?php
            for($cell = 1; $cell <= 25; $cell++){
                $val = $cell ;
                if($cell < 10){
                    $val = "0$cell" ;
                }
                echo "<div> <input type='text' value='$val' maxlength='2'> </div>\n" ;
            }
            ?>
            <!-- <div> <input type="text" value="01" maxlength="2"> </div>
            <div> <input type="text" value="02" maxlength="2"> </div>
            <div> <input type="text" value="03" maxlength="2"> </div>
            <div> <input type="text" value="04" maxlength="2"> </div>
            <div> <input type="text" value="05" maxlength="2"> </div>
            <div> <input type="text" value="06" maxlength="2"> </div>
            <div> <input type="text" value="07" maxlength="2"> </div>
            <div> <input type="text" value="08" maxlength="2"> </div>
            <div> <input type="text" value="09" maxlength="2"> </div>
            <div> <input type="text" value="10" maxlength="2"> </div>
            <div> <input type="text" value="11" maxlength="2"> </div>
            <div> <input type="text" value="12" maxlength="2"> </div>
            <div> <input type="text" value="13" maxlength="2"> </div>
            <div> <input type="text" value="14" maxlength="2"> </div>
            <div> <input type="text" value="15" maxlength="2"> </div>
            <div> <input type="text" value="16" maxlength="2"> </div>
            <div> <input type="text" value="17" maxlength="2"> </div>
            <div> <input type="text" value="18" maxlength="2"> </div>
            <div> <input type="text" value="19" maxlength="2"> </div>
            <div> <input type="text" value="20" maxlength="2"> </div>
            <div> <input type="text" value="21" maxlength="2"> </div>
            <div> <input type="text" value="22" maxlength="2"> </div>
            <div> <input type="text" value="23" maxlength="2"> </div>
            <div> <input type="text" value="24" maxlength="2"> </div>
            <div> <input type="text" value="25" maxlength="2"> </div> -->
        </main>
        <button id="randumBtn">Create randum numbers</button>

        <div class="players-cont">
            <div class="player-cont-left">
                <div class="player-inner-head">
                    <section onclick="openInPlayerCont(1)">Friends</section>
                    <section onclick="openInPlayerCont(2)" style="white-space: nowrap ;">Add friend  </section>
                    <section onclick="openInPlayerCont(3)">Request</section>
                    <section onclick="openInPlayerCont(4)">Chat</section>
                </div>
                <div class="player-inner-body">

                    <section class="friend-cont player-body">
                        
                    <?php
                        $myFriends = $home->getMyFriendList() ;
                        if($myFriends){
                            foreach($myFriends as $friend){
                                echo '<section class="player-list">
                                    <article onclick="openProfile('.$friend['id'].')">'.$friend['name'].'</article>
                                    <article>
                                        <span class="material-icons" onclick="inviteToGame('.$friend['id'].')">add</span>
                                    </article>
                                </section>' ;
                            }
                        } else
                        echo '<article class="player-empty-msg">Your friend list is empty</article>' ;
                    ?>
                    </section>

                    <section class="addFriend-cont  player-body">
                        <section class="addFriend-search-cont">
                            <input type="search" id="searchFriend" placeholder="Search friend" autocomplete="off">
                            <button id="searchFriedBtn">Search</button>
                        </section>
                        <section class="addFriend-friendList-cont">
                            
                        </section>
                    </section>

                    <section class="request-cont  player-body">
                        
                    <?php

                        $friend_requests = $home->getFriendRequests() ;
                        if($friend_requests){
                            foreach($friend_requests as $friend){
                                echo '<section class="player-list">
                                <article onclick="openProfile('.$friend['id'].')">'.$friend['name'].'</article>
                                <article>
                                    <span class="material-icons" onclick="rejectOrAcceptFriend('.$friend['id'].', \'reject\', this)">close</span>
                                    <span class="material-icons" onclick="rejectOrAcceptFriend('.$friend['id'].', \'accept\', this)">done</span>
                                </article>
                            </section>' ;
                            }
                        } else
                        echo '<article class="player-empty-msg">You have no friend request</article>' ;

                    ?>
                    </section>

                    <section class="chat-cont  player-body">
                        <!-- <article class="player-empty-msg">Please find a teammate to start a chat</article> -->
                        <section class="chat-msg-cont">
                            <article class="msg-box msg-right"> <article class="msg">This is message</article> </article>
                            <article class="msg-box msg-left"> <article class="msg">This is message  sdfasdas das dasdasd</article> </article>
                        </section>
                        <section class="chat-type-cont">
                            <input type="text" placeholder="Type message here">
                            <button>Send</button>
                        </section>
                    </section>

                </div>
            </div>
            <div class="player-cont-right">
                <span class="material-icons" onclick="togglePlayerCont(this)" data-open="false">arrow_forward_ios</span>
            </div>
        </div>

        <div class="menu-cont">
            <article>Send feedback</article>
            <article>logout</article>
        </div>

        <div class="logout-outer outer">
            <div class="logut-cont">
                <article>Are you sure you want to logout?</article>
                <section>
                    <button>Now now</button>
                    <button>Yes</button>
                </section>
            </div>
        </div>

        <div class="gameRequest-cont">
            <section>
                <b>Vishnu</b> send a request to play together
            </section>
            <section>
                <span onclick="rejectGameRequest()" class="material-icons">close</span>
                <span onclick="acceptGameRequest()" class="material-icons">done</span>
            </section>
        </div>

        <div class="linemsg-cont">This is a message</div>

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

        <script src="static/js/base.js"></script>
        <script src="static/js/home.js"></script>
    </body>
     <!--  
    ____  ___    ____  ____  ____  ______
   / __ \/   |  / __ \/ __ \/ __ \/_  __/
  / /_/ / /| | / /_/ / /_/ / / / / / /   
 / ____/ ___ |/ _, _/ _, _/ /_/ / / /    
/_/   /_/  |_/_/ |_/_/ |_|\____/ /_/                      
 
-->
</html>