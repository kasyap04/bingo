const table = document.getElementById('table') ;

$(".players-cont").css({
    "left" : "-" + ($(".players-cont").width() - 35) + "px" 
}) ;

// document.body.addEventListener("click", () => {
// }) ;


const TIME_VAL = {
    GAME_REQUEST: null,
    REQUEST_SHOW_TIME: null,
    CREATE_TEAM_TIMEOUT: null,
    CREATE_TEAM_INTERVAL: null,
    TEAMEXIT_OR_START: null,
    TIMER: null
} ;

const GLOBALS = {
    count_down: 5,
    timer_start: false,
    im_ready: false
} ;


// ********************************** Check if game is started or teammate exited ********************************************

checkExitOrStart = () => {
    $.ajax({
        cache: false,
        type: "POST",
        url: "",
        data: {s: 12},
        success: (r) => {
            console.log(r) ;
            if(r){
                try{
                    let data = JSON.parse(resJson(r))[0] ;
                    console.log(data) ;
                    if(data.active){
                        if(data.start.includes('me')){
                            $(".teammate-cont article:first-child").css({
                                "color": "#00a30e",
                                "font-weight": "bold"
                            }) ;
                            $(".startBtn-inner button").html("CANCEL") ;

                            if( !GLOBALS.im_ready ){
                                GLOBALS.im_ready = true ;
                            }

                        } else{
                            $(".teammate-cont article:first-child").css({
                                "color": "#808080",
                                "font-weight": "normal"
                            }) ;
                            $(".startBtn-inner button").html("READY") ;
                            
                            if( GLOBALS.im_ready ){
                                GLOBALS.im_ready = false ;
                            }
                        }
                        if(data.start.includes("teammate")){
                            $(".teammate-cont article:last-child").css({
                                "color": "#00a30e",
                                "font-weight": "bold"
                            }) ;
                        } else{
                            $(".teammate-cont article:last-child").css({
                                "color": "#808080",
                                "font-weight": "normal"
                            }) ;
                        }

                        if(data.start.includes('me') && data.start.includes("teammate")){
                            if(!GLOBALS.timer_start){
                                GLOBALS.timer_start = true ;
                                document.getElementById('timer').innerText = GLOBALS.count_down ;
                                $(".startBtn-inner span").css({
                                    "visibility": "visible"
                                }) ;
                                TIME_VAL.TIMER = setInterval(() => {
                                    document.getElementById('timer').innerText -- ;

                                    if(document.getElementById('timer').innerText <= 0){
                                        clearInterval(TIME_VAL.TIMER) ;
                                        location.href = "/start" ;
                                        console.log("TIMER STOPPED") ;
                                        clearInterval(TIME_VAL.TEAMEXIT_OR_START) ;
                                    }

                                }, 1000) ;
                            }
                        } else {
                            clearInterval(TIME_VAL.TIMER) ;
                            $(".startBtn-inner span").css({
                                "visibility": "hidden"
                            }) ;
                            GLOBALS.timer_start = false ;
                        }

                    } else {
                        $(".teammate-cont").hide() ;
                        GLOBALS.im_ready = false ;
                        GLOBALS.timer_start = false ;
                        clearInterval(TIME_VAL.TEAMEXIT_OR_START) ;
                        TIME_VAL.TEAMEXIT_OR_START = null ;
                        $(".teammate-cont").attr("data-team", "f") ;
                        $(".startBtn-inner button").html("READY") ;
                        $(".startBtn-inner span").css({
                            "visibility": "hidden"
                        }) ;

                        startCheckingRequest() ;
                    }
                } catch(e){
                    console.log(e) ;
                    err() ;
                }
            }
        },
        error: (jqXHR, exception, responseText) => {
            loadingAnim(0) ;
            console.log(jqXHR, exception, responseText) ;
            err() ;
        }
    }) ;
}



openInPlayerCont = item => {
    $(".player-body").hide() ;
    $(".player-inner-head section").removeClass("player-body-selected") ;
    $(`.player-inner-head section:nth-child(${item})`).addClass("player-body-selected") ;

    if(item == 1){
        $(".friend-cont").show() ;
    } else if(item == 2){
        $(".addFriend-cont").show() ;
        document.getElementById('searchFriend').focus() ;
    } else if(item == 3){
        $(".request-cont").show() ;
    } else if(item == 4){
        $(".chat-cont").show() ;
    }
} ;

togglePlayerCont = t => {
    let isOpen = t.getAttribute('data-open') === "true" ;
    if(isOpen){
        $(".players-cont").animate({
            "left" : "-" + ($(".players-cont").width() - 35) + "px" 
        }, 200) ;

        $(".player-cont-right").css("background", "none")
        $(t).css("transform", "rotate(0deg)") ;
        t.setAttribute('data-open', 'false') ;
    } else {
        $(".players-cont").animate({
            "left" : "0px" 
        }, 200) ;

        openInPlayerCont(1) ;
        $(".player-cont-right").css("background-color", "#d4e9ec")
        $(t).css("transform", "rotate(180deg)") ;

        t.setAttribute('data-open', 'true') ;
    }
} ;


// *************************** Menu *********************************************

toggleMenuList = () => {
    let menuOpenStatus = $(".menu-cont").attr("data-menuOpen") === 'true';
    if(menuOpenStatus) {
        closeMenuList() ;
    } else {
        $(".menu-cont").animate({
            "right": "0px"
        }, 200) ;
        $(".menu-cont").attr("data-menuOpen", "true") ;
    }
}

closeMenuList = () => {
    $(".menu-cont").animate({
        "right": "-150px"
    }, 200) ;
    $(".menu-cont").attr("data-menuOpen", "false") ;
}


makeTwoDig = () => {
    for(let i = 0; i < 25; i++){
        
        if(table.children[i].children[0].value.length == 1){
            table.children[i].children[0].value = "0" + table.children[i].children[0].value ;
        }
    }
} ;


// ********************** Check for duplicate number & empty cell *************************
checkTable = () => {
    
    $(".table-cont input").css({
        "color": "black"
    }) ;

    makeTwoDig() ;

    let uniq = true, empty = true ;
    
    let num = [] ;
    for(let i = 0, j = 1; i < 25; i++, j++){
        let cell = table.children[i].children[0] ;
        
        if(j%2 == 1 ){
            $(table.children[i]).css({
                "background-color": "rgba(24, 160, 179, 0.267)"
            }) ;
        } else
        $(table.children[i]).css({
            "background-color": "rgba(224, 108, 108, 0.247)"
        }) ;
        

        if(!num.includes(cell.value)){
            num.push(cell.value) ;
        } else {
            $(table.children[i]).css({
                "background-color": "red",
                "color":"white"
            }) ;
            uniq = false ;
            break ;
        }

        if(cell.value == "" || isNaN(cell.value) || cell.value > 25 || cell.value <= 0){
            $(table.children[i]).css({
                "background-color": "red",
                "color":"white"
            }) ;
            empty = false ;
        }
    }

    res = uniq && empty ;
    if(res){
        return num ;
    } else
    return res ;
} ;


// ************************** shuffle ************************

document.getElementById('randumBtn').addEventListener("click", () => {
    let num = [] ;
    
    while(num.length < 25){
        var random = Math.floor( Math.random()*50 ) ;
        if(random > 0 && random <= 25){
            if(!num.includes(random)){
                num.push(random) ;
            }
        }

        for(let i = 0; i < 25; i++){
            table.children[i].children[0].value = num[i] < 10 ? "0"+num[i] : num[i] ;
        }

    }
}) ;

// ******************** Teammate cont ***************************
closeTeammate = () => {
    loadingAnim(1) ;
    $.ajax({
        cache: false,
        type: "POST",
        url: "",
        data: {s: 10},
        success: (r) => {
            loadingAnim(0) ;
            console.log(r) ;
            try{
                let data = JSON.parse(resJson(r))[0] ;
                if(data == 's'){
                    $(".teammate-cont").hide() ;
                    clearInterval(TIME_VAL.TIMER) ;
                    $(".startBtn-inner span").css({
                        "visibility": "hidden"
                    }) ;
                    
                } else if(data == 'e'){
                    err() ;
                } else if(data == 'l'){
                    login() ;
                }
            } catch(e){
                console.log(e) ;
                err() ;
            }
        },
        error: (jqXHR, exception, responseText) => {
            loadingAnim(0) ;
            console.log(jqXHR, exception, responseText) ;
            err() ;
        }
    }) ;
} ;

showTeammate = (teammateName) => {
    document.getElementById('teammateName').innerText = teammateName ;
    $(".teammate-cont").css("display", "flex") ;
    $(".teammate-cont article").css({
        "color": "#808080",
        "font-weight": "normal"
    }) ;
    $(".teammate-cont").attr("data-team", "t") ;
} ;

checkIfTeamCreated = () => {
    console.log('if') ;
    $.ajax({
        cache: false,
        type: "POST",
        url: "",
        data: {s: 11},
        success: (r) => {
            console.log(r) ;
            if(r){
                try{
                    let data = JSON.parse(resJson(r)) ;
                    if(data[0] == 's'){
                        showTeammate(data[1]) ;
                        clearInterval(TIME_VAL.CREATE_TEAM_INTERVAL) ;
                        clearTimeout(TIME_VAL.CREATE_TEAM_TIMEOUT) ;
                    } else if(data[0] == 'e'){
                        err() ;
                    }
                } catch(e){
                    console.log(e) ;
                    err() ;
                }
            }
        },
        error: (jqXHR, exception, responseText) => {
            loadingAnim(0) ;
            console.log(jqXHR, exception, responseText) ;
            err() ;
        }
    }) ;
}


createAddFriendTemplet = (data, condition) => {
    $(".addFriend-friendList-cont").html('') ;
    console.log(data, condition) ;
    let addBtn = '' ;
    
    
    if(condition == 'add'){
        addBtn = `<span class="material-icons" onclick="sendFriendRequest(${data.u_id})">add</span>` ; ;
    }
    let str = `<section class="player-list"><article onclick="openProfile(1)">${data.name}</article>
    <article>
    ${addBtn}
    </article></section>` ;
    $(".addFriend-friendList-cont").html(str) ;
}


document.getElementById('searchFriedBtn').addEventListener("click", () => {
    let username = document.getElementById('searchFriend').value ;

    if(username){
        loadingAnim(1) ;
        $.ajax({
            cache: false,
            type: "POST",
            url: "",
            data: {s: 1, u: username},
            success: (r) => {
                loadingAnim(0) ;
                console.log(r) ;
                try{
                    let data = JSON.parse(resJson(r)) ;

                    if(data == 'nff'){
                        $(".addFriend-friendList-cont").html('<article class="player-empty-msg">No result found</article>') ;
                    } else {
                        if(data[0] == 'add'){
                            createAddFriendTemplet(data[1], 'add') ;
                        } else if(data[0] == 'fr'){
                            makeLineMsg(`${username} is already send you a friend request`, `black`, 3500) ;
                            createAddFriendTemplet(data[1], null) ;
                        } else if(data[0] == 'yf'){
                            createAddFriendTemplet(data[1], null) ;
                        } else if(data[0] == 'l'){
                            login() ;
                        }
                    }
                } catch(e){
                    console.log(e) ;
                    err() ;
                }
            },
            error: (jqXHR, exception, responseText) => {
                console.log(jqXHR, exception, responseText) ;
                err() ;
            }
        }) ;
    }
}) ;

sendFriendRequest = id => {
    loadingAnim(1) ;
    $.ajax({
        cache: false,
        type: "POST",
        url: "",
        data: {s: 2, id: id},
        success: (r) => {
            loadingAnim(0) ;
            console.log(r) ;
            try{
                let data = JSON.parse(resJson(r))[0] ;
                if(data == 's'){
                    makeLineMsg("Friend request send", "green", 2500) ;
                } else if(data == 'ar'){
                    makeLineMsg("You already send friend request to this player", "orange", 2500) ;
                } else if(data == 'af'){
                    makeLineMsg("Player already in your friendlist", "red", 2500) ;
                } else if(data == 'e'){
                    err() ;
                } else if(data == "l"){
                    login() ;
                }
            } catch(e){
                console.log(e) ;
                err() ;
            }
        },
        error: (jqXHR, exception, responseText) => {
            console.log(jqXHR, exception, responseText) ;
            err() ;
        }
    }) ;
}


removePlaterList = t => {
    $(t).parent().parent().remove() ;
    if(document.getElementsByClassName('request-cont')[0].children.length == 0){
        document.getElementsByClassName('request-cont')[0].innerHTML = '<article class="player-empty-msg">You have no friend request</article>' ;
    }
}



rejectOrAcceptFriend = (id, context, t) => {
    let swift = '', msg = '', color = '' ;
    if(context == 'accept'){
        swift = 3 ;
        msg = "Firend request accepted" ;
        color = "green" ;
        
    } else {
        swift = 4 ;
        msg = "Firend request rejected" ;
        color = "black" ;
    }

    loadingAnim(1) ;
    $.ajax({
        cache: false,
        type: "POST",
        url: "",
        data: {s: swift, id: id},
        success: (r) => {
            loadingAnim(0) ;
            console.log(r) ;
            try{
                let data = JSON.parse(resJson(r))[0] ;
                if(data == 's'){
                    makeLineMsg(msg, color, 2500) ;
                    removePlaterList(t) ;
                } else if(data == "e"){
                    err() ;
                } else if(data == "l"){
                    login() ;
                }
            } catch(e){
                console.log(e) ;
                err() ;
            }
        },
        error: (jqXHR, exception, responseText) => {
            loadingAnim(0) ;
            console.log(jqXHR, exception, responseText) ;
            err() ;
        }
    }) ;
}


//  ***********************************LOGOUT************************************

openLogoutCont = () => {
    $(".logout-outer").show() ;
    $(".logut-cont").show() ;
}

closeLogoutCont = () => {
    $(".logut-cont").hide () ;
    $(".logout-outer").hide() ;
    closeMenuList() ;
}

logout = () => {
    loadingAnim(1) ;
    $.ajax({
        cache: false,
        type: "POST",
        url: "",
        data: {s: 5},
        success: (r) => {
            loadingAnim(0) ;
            console.log(r) ;
            try{
                let data = JSON.parse(resJson(r))[0] ;
                if(data == 's' || data == "l"){
                    makeLineMsg("Login out", "green", 2500) ;
                    location.reload() ;
                } else if(data == "e"){
                    err() ;
                }
            } catch(e){
                console.log(e) ;
                err() ;
            }
        },
        error: (jqXHR, exception, responseText) => {
            loadingAnim(0) ;
            console.log(jqXHR, exception, responseText) ;
            err() ;
        }
    }) ;
}



inviteToGame = id => {
    if(id){
        loadingAnim(1) ;
        $.ajax({
            cache: false,
            type: "POST",
            url: "",
            data: {s: 6, id: id},
            success: (r) => {
                loadingAnim(0) ;
                console.log(r) ;
                try{
                    let data = JSON.parse(resJson(r))[0] ;
                    if(data == 's'){
                        makeLineMsg("Request send", "green", 1000) ;
                        TIME_VAL.CREATE_TEAM_INTERVAL = setInterval(checkIfTeamCreated, 1500) ;
                        TIME_VAL.CREATE_TEAM_TIMEOUT = setTimeout(() => {
                            clearInterval(TIME_VAL.CREATE_TEAM_INTERVAL) 
                        }, 11000) ;
                    } else if(data == "e"){
                        err() ;
                    }
                } catch(e){
                    console.log(e) ;
                    err() ;
                }
            },
            error: (jqXHR, exception, responseText) => {
                loadingAnim(0) ;
                console.log(jqXHR, exception, responseText) ;
                err() ;
            }
        }) ;
    } else
    err() ;
}
// {"status":"s","id":"2","name":"ridhik"}

// ************************** Game Request **************************************
closeGameRequest = () => {
    clearTimeout(TIME_VAL.REQUEST_SHOW_TIME) ;
    $(".gameRequest-cont").animate({
        "left" : "200%"
    }) ;
} ;

showGameRequest = (name, id) => {
    document.getElementById('gameRequesterName').innerText = name ;
    document.getElementById('gameRequestActionBtn').innerHTML = `<span onclick="rejectGameRequest()" class="material-icons">close</span>
    <span onclick="acceptGameRequest(${id})" class="material-icons">done</span>` ;

    $(".gameRequest-cont").animate({
        "left" : "100%"
    }) ;
    // clearInterval(TIME_VAL.GAME_REQUEST) ;
    TIME_VAL.REQUEST_SHOW_TIME =  setTimeout(closeGameRequest, 10000) ;
} ;


// ********************** Accept / reject game request ******************************

acceptGameRequest = id => {
    
    loadingAnim(1) ;
    $.ajax({
        cache: false,
        type: "POST",
        url: "",
        data: {s: 9},
        success: (r) => {
            loadingAnim(0) ;
            console.log(r) ;
            try{
                let data = JSON.parse(resJson(r))[0] ;
                if(data.status == 's'){
                    showTeammate(data.name) ;
                    closeGameRequest() ;
                } else if(data == 'e'){
                    err() ;
                }
            } catch(e){
                console.log(e) ;
            }
        },
        error: (jqXHR, exception, responseText) => {
            console.log(jqXHR, exception, responseText) ;
            err() ;
        }
    }) ;
} ;

rejectGameRequest = () => {
    loadingAnim(1) ;
    $.ajax({
        cache: false,
        type: "POST",
        url: "",
        data: {s: 8},
        success: (r) => {
            loadingAnim(0) ;
            console.log(r) ;
            try{
                let data = JSON.parse(resJson(r))[0] ;
                if(data == 's'){
                    closeGameRequest() ;       
                    startCheckingRequest() ;
                    $(".teammate-cont").attr("data-team", "f") ;
                } else if(data == 'e' || data == 'l'){
                    err() ;
                }
            } catch(e){
                console.log(e) ;
                err() ;
            }
        },
        error: (jqXHR, exception, responseText) => {
            console.log(jqXHR, exception, responseText) ;
            err() ;
        }
    }) ;
} ;



checkPlayRequest = () => {
    console.log(0) ;
    $.ajax({
        cache: false,
        type: "POST",
        url: "",
        data: {s: 7},
        success: (r) => {
            console.log($(".teammate-cont").attr("data-team")) ;
            if($(".teammate-cont").attr("data-team") == "t" ){
                clearInterval(TIME_VAL.GAME_REQUEST) ;

                if(TIME_VAL.TEAMEXIT_OR_START == null){
                    TIME_VAL.TEAMEXIT_OR_START = setInterval(checkExitOrStart, 1500) ;
                }
            }
            if(r){

                try{
                    let data = JSON.parse(resJson(r))[0] ;
                    if(data.status == 's'){
                        showGameRequest(data.name, data.id) ;
                    }
                } catch(e){
                    console.log(e) ;
                }
            }
        },
        error: (jqXHR, exception, responseText) => {
            console.log(jqXHR, exception, responseText) ;
        }
    }) ;
}
function startCheckingRequest(){
    TIME_VAL.GAME_REQUEST = setInterval(checkPlayRequest, 1500) ;
}

startCheckingRequest() ;




ready = t => {

    const table = checkTable() ;
    if(table){
    
        let param = {s: 13, t: JSON.stringify(table)} ;
        if(GLOBALS.im_ready){
            param = {s: 14}
        }

        loadingAnim(1) ;
        $.ajax({
            cache: false,
            type: "POST",
            url: "",
            data: param,
            success: (r) => {
                loadingAnim(0) ;
                console.log(r) ;
                try{
                    let data = JSON.parse(resJson(r))[0] ;
                    if(data == 'e'){
                        err() ;
                    }
                } catch(e){
                    err() ;
                    console.log(e) ;
                }
            },
            error: (jqXHR, exception, responseText) => {
                loadingAnim(0) ;
                err() ;
                console.log(jqXHR, exception, responseText) ;
            }
        }) ;
    }
}



