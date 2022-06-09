const table = document.getElementById('table') ;

$(".players-cont").css({
    "left" : "-" + ($(".players-cont").width() - 35) + "px" 
}) ;

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

openMenuList = () => {
    console.log(0) ;
}


// ************************** Game Request **************************************
closeGameRequest = () => {
    $(".gameRequest-cont").animate({
        "left" : "200%"
    }) ;
} ;

showGameRequest = () => {
    $(".gameRequest-cont").animate({
        "left" : "100%"
    }) ;
} ;


// ********************** Accept / reject game request ******************************

acceptGameRequest = () => {
    closeGameRequest() ;
} ;

rejectGameRequest = () => {
    closeGameRequest() ;
} ;

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

        if(cell.value == ""){
            $(table.children[i]).css({
                "background-color": "red",
                "color":"white"
            }) ;
            empty = false ;
        }
    }

    return uniq && empty ;
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
    $(".teammate-cont").hide() ;
} ;

showTeammate = () => {
    document.getElementById('teammateName').innerText = "teammate name"
    $(".teammate-cont").show() ;
} ;


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
            console.log(jqXHR, exception, responseText) ;
            err() ;
        }
    }) ;
}