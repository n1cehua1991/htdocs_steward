$(function(){

    $('#login_name').focus();

    $('.img-code').click(function(){
        var yzm_src = '/kit/captcha?code=' + Math.ceil(Math.random()*10000);
        $(this).attr('src', yzm_src);
    });

    $('button').click(function(){
        login();
    });

    $(document).keypress(function(e) {
        if(e.which == 13) {
            login();
        }
    });

});

function login() {

    var login_name = $.trim($('#login_name').val());
    var password = $.trim($('#password').val());
    var yzm = $.trim($('.yzm-input').val());

    if ( E.isEmpty(login_name) ) {
        layer.alert('请输入用户名',{icon:2});
        $('#login_name').focus();
        return false;
    }

    if ( E.isEmpty(password) ) {
        layer.alert('请输入密码',{icon:2});
        $('#password').focus();
        return false;
    } else if(!E.isPwd(password)) {
        layer.alert('请输入正确的密码',{icon:2});
        $('#password').focus();
        return false;
    }

    if ( E.isEmpty(yzm) ) {
        layer.alert('请输入正确的验证码',{icon:2});
        $('.yzm-input').focus();
        return false;
    }

    var layer_index = layer.load();
    $.ajax({
        type: 'GET',
        url:'/ajax/login',
        data:{
            mobile : login_name,
            password : password,
            yzm : yzm
        },
        dataType: 'json',
        success: function(obj) {
            layer.close(layer_index);
            if (obj.code == 200) {
                self.location = '/admin';
            }else{
                layer.alert(obj.message,{icon:2},function(){
                    $('#login_name').focus();
                    layer.closeAll();
                });
            }
        }
    });

}