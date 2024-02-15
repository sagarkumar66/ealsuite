<?php $this->load->view('layout/header.php'); ?>

<div class="container content p-3">
    <div class="row">
        <div class="col-lg-3"></div>
        <div class="col-lg-6">
            <h4 class="text-center">Add Invoice</h4>
            <form id="add_invoice_form" class="mt-3">
                <div id="message" role="alert"></div>
                <div class="form-group mb-3">
                    <label for="customer">Customer</label>
                    <select name="customer" id="customer" class="form-control" autocomplete="off" required>
                        <option value=""> -- Select a Customer -- </option>
                        <?php foreach ($customers as $key => $value) {
                            echo "<option value='".$value['id']."'>".$value['name']."</option>";
                        }?>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label for="date">Invoice Date</label>
                    <input type="date" name="date" id="date" class="form-control" autocomplete="off">
                </div>
                <div class="form-group mb-3">
                    <label for="amount">Invoice Amount</label>
                    <input type="number" name="amount" id="amount" placeholder="Enter amount" class="form-control" min='0' autocomplete="off">
                </div>
                <button type="button" class="btn btn-primary" id="add_btn">Add</button>
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
        $('#add_btn').click(() => {
            if($('#add_invoice_form').valid()){
                $.ajax({
                    'url': '<?php echo base_url() ?>api/add_data',
                    'type': "POST",
                    'dataType': 'json',
                    'data': $('#add_invoice_form').serialize()+'&add=invoice',
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
    