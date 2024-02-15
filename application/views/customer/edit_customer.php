<?php $this->load->view('layout/header.php'); ?>

<div class="container p-3 mt-3">
    <div class="row">
        <div class="col-lg-3"></div>
        <div class="col-lg-6">
            <h4 class="text-center">Edit Customer</h4>
            <form id="edit_customer_form" class="mt-3">
                <div id="message" role="alert"></div>
                <div class="form-group mb-3">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" placeholder="Enter name" class="form-control" autocomplete="off" required value="<?php echo $customer['name'] ?>">
                </div>
                <div class="form-group mb-3">
                    <label for="phone">Phone No</label>
                    <input type="number" name="phone" id="phone" placeholder="Enter phone" class="form-control" title="Please enter a 10-digit phone number"  maxlength="10" minlength="10" autocomplete="off" value="<?php echo $customer['phone'] ?>">
                </div>
                <div class="form-group mb-3">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="Enter email" class="form-control" autocomplete="off" value="<?php echo $customer['email'] ?>">
                </div>
                <div class="form-group mb-3">
                    <label for="address">Address</label>
                    <textarea name="address" id="address" placeholder="Enter address" class="form-control" autocomplete="off" maxlength="100"><?php echo $customer['address'] ?></textarea>
                </div>
                <button type="button" class="btn btn-primary" id="update_btn">Update</button>
            </form>
        </div>
        <div class="col-lg-3"></div>
    </div>
</div>

<?php $this->load->view('layout/footer.php'); ?>

<script>

    $(document).ready(function(){
        $('#update_btn').click(() => {
            if($('#edit_customer_form').valid()){
                $.ajax({
                    'url': '<?php echo base_url() ?>api/edit_data',
                    'type': "POST",
                    'dataType': 'json',
                    'data': $('#edit_customer_form').serialize()+'&edit=customer&id='+<?php echo $this->uri->segment(3); ?>,
                    'success': function (response) {
                        $('#message').html(response.msg);
                        if(response.status){
                            $('#message').attr("class","alert alert-primary");
                            window.location.href = '<?php echo base_url()?>Customer';
                        } else {
                            $('#message').attr("class","alert alert-danger");
                        }
                    }
                })
            }
        });
    });
</script>
    