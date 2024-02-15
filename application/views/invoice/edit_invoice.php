<?php $this->load->view('layout/header.php'); ?>

<div class="container content p-3">
    <div class="row">
        <div class="col-lg-3"></div>
        <div class="col-lg-6">
            <h4 class="text-center">Edit Invoice</h4>
            <form id="edit_invoice_form" class="mt-3">
                <div id="message" role="alert"></div>
                <div class="form-group mb-3">
                    <label for="customer">Customer</label>
                    <select name="customer" id="customer" class="form-control" autocomplete="off" required>
                        <option value=""> -- Select a Customer -- </option>
                        <?php foreach ($customers as $key => $value) {
                            echo "<option value='".$value['id']."'".($invoice['customer_id']==$value['id']?'selected':'').">".$value['name']."</option>";
                        }?>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label for="date">Invoice Date</label>
                    <input type="date" name="date" id="date" class="form-control" autocomplete="off" value="<?php echo $invoice['date'] ?>">
                </div>
                <div class="form-group mb-3">
                    <label for="amount">Invoice Amount</label>
                    <input type="number" name="amount" id="amount" placeholder="Enter amount" class="form-control" min='0' autocomplete="off" value="<?php echo $invoice['amount'] ?>">
                </div>
                <div class="form-group mb-3">
                    <label for="status">Invoice Status</label>
                    <select name="status" id="status" class="form-control" autocomplete="off" required>
                        <option value=""> -- Select Status -- </option>
                        <option value="1" <?php echo ($invoice['status']==1?'selected':'') ?>> Unpaid </option>
                        <option value="2" <?php echo ($invoice['status']==2?'selected':'') ?>> Paid </option>
                        <option value="3" <?php echo ($invoice['status']==3?'selected':'') ?>> Cancelled </option>
                    </select>
                </div>
                <button type="button" class="btn btn-primary" id="update_btn">Update</button>
            </form>
        </div>
        <div class="col-lg-3"></div>
    </div>
</div>

<?php $this->load->view('layout/footer.php'); ?>

<script>

    function todayDate() {
        var today = new Date(); // get the current date
        var dd = today.getDate(); //get the day from today.
        var mm = today.getMonth()+1; //get the month from today +1 because january is 0!
        var yyyy = today.getFullYear(); //get the year from today

        //if day is below 10, add a zero before (ex: 9 -> 09)
        if(dd<10) {
            dd='0'+dd
        }

        //like the day, do the same to month (3->03)
        if(mm<10) {
            mm='0'+mm
        }

        //finally join yyyy mm and dd with a "-" between then
        return yyyy+'-'+mm+'-'+dd;
    }

    $(document).ready(function(){
        $('#date').attr('max', todayDate());
        $('#update_btn').click(() => {
            if($('#edit_invoice_form').valid()){
                $.ajax({
                    'url': '<?php echo base_url() ?>api/edit_data',
                    'type': "POST",
                    'dataType': 'json',
                    'data': $('#edit_invoice_form').serialize()+'&edit=invoice&id='+<?php echo $this->uri->segment(3); ?>,
                    'success': function (response) {
                        $('#message').html(response.msg);
                        if(response.status){
                            $('#message').attr("class","alert alert-primary");
                            window.location.href = '<?php echo base_url()?>Invoice';
                        } else {
                            $('#message').attr("class","alert alert-danger");
                        }
                    }
                })
            }
        });
    });
</script>
    