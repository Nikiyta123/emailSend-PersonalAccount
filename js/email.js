document.addEventListener('DOMContentLoaded', function() {

    function message(data) {
        $("#span-message").text(data);
    }
    
    $(document).on('click','#registraciya', function(){

        let type_m = $('#type_m').val();
        let login = $('#login').val();
        let phone = $('#phone').val();
        let email = $('#email').val();

        let action = 'email';

        $.ajax({
            type: 'POST',
            url: '/ajax.php',
            data: {
                type_m:type_m,
                login:login,
                phone:phone,
                email:email,
                action:action
            }
        }).done(function(data) {
            message(data);
        }).fail(function(data) {

        });


    });

});

