'use-strict'

resJson = v => v.substring( v.indexOf("sta[")+3, v.indexOf("]end")+1 ) ; 


makeLineMsg = (msg, color, time) => {
    $(".linemsg-cont").css({
        "color": color,
        "border-color": color,
        "box-shadow": "0px 0px 7px 1px "+ color
    }) ;

    $(".linemsg-cont").html(msg) ;

    $(".linemsg-cont").animate({
        "bottom": "20px",
    }, 200) ;

    setTimeout(() => {
        $(".linemsg-cont").animate({
            "bottom": "-100px",
        }, 200) ;
    }, time) ;
}

var dla ;  // delay loading anim
loadingAnim = m => { // m =>   1 for open,   0 for close
    
    if(m == 1){
        dla = setTimeout(() => { 
            $(".loading-outer").css("display", "flex") ; 
        }, 500) ;
    } else if(m == 0){
        $(".loading-outer").css("display", "none") ;
        clearTimeout(dla) ;
    }
}


err = () => makeLineMsg("Something wend wrong. Please try again later", "red", 3500) ;

function login(){
    location.href = "/login" ;
}