$.ajax({
    cache: false,
    type: "POST",
    url: "login.php",
    data: { s: 2 },
    success : (r) => {
        console.log(r) ;
        try{
            let data = JSON.parse(resJson(r)) ;
            if(data == "rh"){
                location.href = "/"   ;
            } 
        } catch(e){
            console.log(e) ;
        }
    },
    error: (jqXHR, exception, responseText) => {
        console.log(jqXHR, exception, responseText) ;
    }
}) ;

checkUsername = t => {
    t.value = t.value.replace(/[^a-z0-9_]/g,'') ;
} ;

$(".password-toggle").click(function(){
    let show = this.getAttribute("data-show") == "t" ;
    if(show){
        $(this).prev().attr("type", "password") ;
        $(this).attr("data-show", "f") ;
        $(this).html("show") ;
    } else{
        $(this).prev().attr("type", "text") ;
        $(this).attr("data-show", "t") ;
        $(this).html("hide") ;
    }
}) ;

// ******************* SIGNUP ******************************************

document.getElementById('signupBtn').addEventListener("click", () => {
    $(".err").hide() ;
    let username = document.getElementById('signupUsername').value ;
    if(username == ""){
        $(".signupErr1").show() ;
        $(".signupErr1").html("Please choose a username") ;
        return false ;
    }

    let pass1 = document.getElementById('signupPass1').value, 
        pass2 = document.getElementById('signupPass2').value ;

    if(pass1 == ""){
        $(".signupErr2").show() ;
        $(".signupErr2").html("Please choose a password") ;
        return false ;
    }

    if(pass1.length < 8){
        $(".signupErr2").show() ;
        $(".signupErr2").html("Password should be more than 8 charecters") ;
        return false ;
    }

    if(pass1 != pass2){
        $(".signupErr3").show() ;
        $(".signupErr3").html("Password is not matching") ;
        return false ;
    }

    loadingAnim(1) ;
    $.ajax({
        cache: false,
        type: "POST",
        url: "login.php",
        data: { u: username, p: pass2, s: 1 },
        success : (r) => {
            loadingAnim(0) ;
            console.log(r) ;
            try{
                let data = JSON.parse(resJson(r)) ;
                if(data == "s"){
                    makeLineMsg("Login Sucessfull", "green", 2000) ;
                    location.href = "/" ;
                } else if(data == "ue"){
                    makeLineMsg("Username already exist", "red", 2500) ;    
                } else if(data == "rh"){
                    location.href = '/' ;
                } else if(data == "ef"){
                    makeLineMsg("Please fill all fields", "red", 2500) ;
                } else if(data == "e"){
                    err() ;
                }
            } catch(e){
                console.log(e) ;
            }
        },
        error: (jqXHR, exception, responseText) => {
            console.log(jqXHR, exception, responseText) ;
        }
    }) ;

}) ;


// console.log( resJson('sta["s"]end') ) ;


// ******************* LOGIN ******************************************

document.getElementById('loginBtn').addEventListener("click", () => {
    console.log(1) ;
}) ;