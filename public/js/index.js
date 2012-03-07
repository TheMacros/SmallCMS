var login = new Object();
$(document).ready(function(){
    $('#login').click(function(){
        if ($('#auth-form').is(':visible')){
            login.formClose();
        }else{
            login.formShow();
        }
        return false;
    });
    $('#logincancel').click(function(){
       login.formClose(); 
    });
    $('#loginsubmit').click(function(){
        var email = $('#email').val();
        var pass = $('#password').val();
        login.auth(email, pass);
        return false;
    })
});
login.formShow = function(){
    $('#auth-form').show('slide', {'direction': 'up'}, function(){
        $('#email').focus();
    });
}
login.formClose = function(){
    $('#auth-form').hide('slide', {'direction': 'up'});
    $('#auth-form li.alert').remove();
}

login.auth = function(email, pass){
    $('#auth-form li.alert').remove();
    $('#auth-form .loading').fadeIn();
    $.post(
        '/login',
        {
            'email' : email,
            'password': pass
        }, function(data){
            $('#auth-form .loading').fadeOut('fast');
            if (data.status == 'ok'){
                location = '/';
            }else{
                var out = '<li class="alert alert-error"><a class="close" data-dismiss="alert">×</a>' + data.message + '</li>';
                $('#auth-form .form-actions.submit').before(out);
                $('#email').focus();
            }
        }, 'json'
    )
}