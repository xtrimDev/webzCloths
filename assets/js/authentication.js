$('document').ready(function() {
    /* handling form validation */
    $('#login').validate({
        rules: {
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
            },
        },
        messages: {
            email:{
                required: "Please enter an email.",
                email: "Please enter a valid email."
            },
            password: "Please enter a password.",
        },
        submitHandler: login
    });

    /* Handling login functionality */
    function login() {
        const data = $("#login").serialize();
        $.ajax({
            type : 'POST',
            url  : 'authentication.php',
            data : data,
            beforeSend: function(){
                $("#error_msg").fadeOut();
                $("#sign_in").html('Wait..');
                $('#sign_in').attr('disabled', 'disabled');
            },
            success : function(response){
                if(response === "success"){
                    $("#sign_in").html('Signing In');
                    setTimeout(' window.location.href = "index.php"; ',2000);
                } else if (response === "102") {
                    $("#sign_in").html('Redirecting');
                    setTimeout(' window.location.href = "auth.php?verify"; ',2000);
                } else {
                    $("#error_msg").fadeIn(1000, function(){
                        $('#sign_in').html('Sign In');
                        $("#error_msg").html('<div class="msg_danger">'+response+'</div>');
                        $("#sign_in").removeAttr('disabled');
                    });
                }
            }
        });
        return false;
    }


    $('#register').validate({
        rules: {
            name: {
                required: true,
            },
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
            },
        },
        messages: {
            name: "Please enter your name",
            email:{
                required: "Please enter an email.",
                email: "Please enter a valid email."
            },
            password: "Please enter a password.",
        },
        submitHandler: registration
    });

    /* Handling login functionality */
    function registration() {
        const data = $("#register").serialize();
        $.ajax({
            type : 'POST',
            url  : 'authentication.php',
            data : data,
            beforeSend: function(){
                $("#error_msg").fadeOut();
                $("#sign_up").html('Wait..');
                $("#sign_up").attr('disabled', 'disabled');
            },
            success : function(response){
                if(response === "success"){
                    $("#sign_up").html('Signing up..');
                    setTimeout(' window.location.href = "auth.php?verify"; ',2000);
                } else {
                    $("#error_msg").fadeIn(1000, function() {
                        $("#sign_up").html('Sign up');
                        $("#error_msg").html('<div class="msg_danger">'+response+'</div>');
                        $("#sign_up").removeAttr('disabled');
                    });
                }
            }
        });
        return false;
    }
});