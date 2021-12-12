<div class="center">
    <div class="panel ">
        <div class="panel-body"><h3>Login</h3>
            <div class="alert alert-danger alert-dismissible fade" role="alert"> 
                    <button class="close" data-dismiss="alert"></button>
                    <span class="message"></span>
                </div>
            <form id="form_login" method="post" action="?">
                <div class="form-group">
                    <label class="sr-only">Username or Email</label>
                    <input id="user_name" name="user_name" type="text" placeholder="Email" class="form-control">
                </div>
                <div class="form-group m-b-5">
                    <label class="sr-only">Password</label>
                    <input id="user_pass" name="user_pass" type="password" placeholder="Password" class="form-control">
                </div>
<!--                <div class="form-group form-inline m-b-10 ">
                    <div class="form-check">
                        <label>
                            <input type="checkbox"><small> Remember me</small>
                        </label>
                    </div>
                </div>-->
                <div class="form-group">
                    <button class="btn" type="button" onclick="submit_form()">Login</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    var base_url = "<?=base_url();?>";
    function submit_form(){ 
        $('.alert').addClass('hide');
        var username = $('#user_name').val();
        var password = $('#user_pass').val();
        if(username !== '' && password !== ''){
            $.ajax({
                type : "POST",
                url  : base_url+"login/auth",
                data : $('#form_login').serialize(),
                datatype: "json",
                success: function(data){ 
                    console.log(data);
                          if (data['success']) {
                              if (data['login']) {
                                  $('.alert').removeClass('fade');
                                  $('.message').html('User sedang login.');
                              } else {
                                  
                                  window.location = base_url + 'main';
                              }
                          } else if (!data['success']) {
                              $("#attempt").val(data['attempt']);
                              $('.alert').removeClass('fade');
                              $('.message').html('Username dan password salah.');

                          }
//                      }
                }
            });
        }else{                
            $('.alert').removeClass('fade');
            $('.message').html('Mohon isi semua data.');
        }
    }
</script>