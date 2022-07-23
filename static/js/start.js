$("#table div input").click(function(){
    if(this.value){
        loadingAnim(1) ;
        $.ajax({
            cache: false,
            type: "POST",
            url: "",
            data: {s: 1, n: this.value},
            success: (r) => {
                loadingAnim(0) ;
                console.log(r) ;
                try{
                    let data = JSON.parse(resJson(r))[0] ;
                    if(data == "e"){
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
}) ;

goToHomePage = () => location.href = "/" ;

// makeLineMsg(`Vishnu is already send you a friend request`, `green`, 3500) ;

const TIME_VAL = {
    CHECK_NUMBERS: null,

} ;


hilightSelectedNumbers = num => {
    const table = document.getElementById('table') ;
    for(let i = 0; i < table.children.length; i++){
        // console.log(table.children[i].children[0].value) ;
        if(num.includes(table.children[i].children[0].value)){
            $(table.children[i]).css("background-color", "#ff7373") ;
            $(table.children[i].children[0]).css("color", "#fff") ;
        }
    }
}

setBingoLevel = level => {
    if(level > 0){
        for(let i = 1; i <= level; i++){
            $(`.table-canvas-bingo-cont article:nth-child(${i})`).css("color", "#22b88b") ;
        }
    }
}

gameExitTimer = () => {
    document.getElementById('exitTimer').innerText = 5 ;
    let exit_game = setInterval(() => {
        if(document.getElementById('exitTimer').innerText <= 0){
            clearInterval(exit_game) ;
            // location.href = '/' ;
        } else
        document.getElementById('exitTimer').innerText -- ;
    }, 1000) ;
}

toggleExitGameCont = mode => {
    if(mode == 1){
        $(".exitGamme-confirm-outer").show() ;
    } else
    $(".exitGamme-confirm-outer").hide() ;

}

setWinner = status => {
    $(".winner-cont-outer").fadeIn() ;
    clearInterval(TIME_VAL.CHECK_NUMBERS) ;
    let icon = $(".winner-cont-top") ,
        win_status = $(".winner-cont-bottom article:nth-child(2)") ;
        
    if(status == 2){
        icon.html('<span class="material-icons handshake">handshake</span>') ;
        win_status.html('BOTH WIN') ;
        $(".winner-cont-bottom").css("background-color", "#2fb9db") ;
    } else if(status == 'win') {
        icon.html('<span class="material-icons emoji_events">emoji_events</span>') ;
        win_status.html('WIN') ;
        $(".winner-cont-bottom").css("background-color", "#16af7c") ;
    } else if(status == 'lose'){
        icon.html('<span class="material-icons thumb_down">thumb_down</span>') ;
        win_status.html('LOSE') ;
        $(".winner-cont-bottom").css("background-color", "#e42626") ;
    }

    document.getElementById('exitTimer').innerText = 5 ;
    let exitTimer = setInterval(() => {
        if(document.getElementById('exitTimer').innerText > 1){
            document.getElementById('exitTimer').innerText -- ;
        } else {
            clearInterval(exitTimer) ;
            goToHomePage() ;
        }
    }, 1000) ;

}


checkNumbers = () => {
    $.ajax({
        cache: false,
        type: "POST",
        url: "",
        data: {s: 2},
        success: (r) => {
            console.log(r) ;
            try{
                let data = JSON.parse(resJson(r))[0] ;
                if(data.status){
                    console.log(data) ;
                    hilightSelectedNumbers(data.numbers) ;
                    setBingoLevel(data.bingo) ;
                    if(data.bingo == 5){
                        if(data.win != false){
                            setWinner(data.win) ;
                        } 
                    }

                } else {
                    clearInterval(TIME_VAL.CHECK_NUMBERS) ;
                    $(".teammateExit-cont").show() ;
                    document.getElementById('gameExitTimer').innerText = 5 ;
                    let exit_timer = setInterval(() => {
                        if(document.getElementById('gameExitTimer').innerText > 1){
                            document.getElementById('gameExitTimer').innerText -- ;
                        } else {
                            clearInterval(exit_timer) ;
                            goToHomePage() ;
                        }
                    }, 1000) ;
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

// TIME_VAL.CHECK_NUMBERS = setInterval(checkNumbers, 1500) ;


exitGame = () => {
    loadingAnim(1) ;
    $.ajax({
        cache: false,
        type: "POST",
        url: "",
        data: {s: 3},
        success: (r) => {
            loadingAnim(0) ;
            console.log(r) ;
            try{
                let data = JSON.parse(resJson(r))[0] ;
                if(data == "s"){
                    goToHomePage() ;
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