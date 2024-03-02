<?php $this->load->view('layout/header.php'); ?>

    <div class="container content login-container">
        <div class="card" style="width: 30vw">
            <div class="card-body">
                <form id="login_form">
                    <div id="message" role="alert"></div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="email" required autocomplete="off" placeholder="sagar@gmail.com">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" required autocomplete="off" placeholder="1234">
                    </div>
                    <button type="button" class="btn btn-primary" id="login_btn">Login</button>
                </form>
            </div>
        </div>
    </div>
        
    <?php $this->load->view('layout/footer.php'); ?>    

    <script>
        $('#login_btn').click(() => {
            if($('#login_form').valid()){
                $.ajax({
                    'url': '<?php echo base_url() ?>Home/login',
                    'type': "POST",
                    'dataType': 'json',
                    'data': {'email' : $('#email').val(), 'password' : $('#password').val()},
                    'success': function (response) {
                        $('#message').html(response.msg);
                        if(response.status){
                            $('#message').attr("class","alert alert-primary");
                            window.location.href = '<?php echo base_url()?>list/customer';
                        } else {
                            $('#message').attr("class","alert alert-danger");
                        }
                    }
                })
            }
        });
    </script>
    
  </body>
</html>