<?php $this->load->view('layout/header.php'); ?>
<div class="container mt-5">
    <a href="<?php echo base_url() ?>Invoice/add" class="btn btn-primary btn-sm mb-3">Add Invoice</a>
    <table class="table table-primary table-striped" id="invoice_table">
        <thead>
            <tr>
                <th>Customer Name</th>
                <th>Date</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Edit</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<?php $this->load->view('layout/footer.php'); ?>

<script>
    let id;
    $(document).ready(function(){
        $('#invoice_table').DataTable({
            'processing' : true,
            'pageLength' : 10,
            'ajax': {
                url: '<?php echo base_url();?>api/list_data',
                type: 'POST',
                data: {'list' : 'invoice'} 
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
    