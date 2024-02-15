<?php $this->load->view('layout/header.php'); ?>
<div class="container mt-5">
    <a href="<?php echo base_url() ?>Customer/add" class="btn btn-primary btn-sm mb-3">Add Customer</a>
    <table class="table table-primary table-striped" id="customer_table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Address</th>
                <th>Edit</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<?php $this->load->view('layout/footer.php'); ?>

<script>
    $(document).ready(function(){
        $('#customer_table').DataTable({
            'processing' : true,
            'pageLength' : 10,
            'ajax': {
                url: '<?php echo base_url();?>api/list_data',
                type: 'POST',
                data: {'list' : 'customer'} 
            },
            'columnDefs': [
                {
                    'targets': [4],
                    'orderable': false
                }
            ]
        });
    });
</script>
    