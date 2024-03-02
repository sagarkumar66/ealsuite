<?php $this->load->view('layout/header.php'); ?>
<div class="container content">
    <a href="<?php echo base_url() ?>add/<?= $page_name ?>" class="btn btn-primary btn-sm mb-3">Add <?= ucwords($page_name); ?></a>
    <table class="table table-primary table-striped" id="list_table">
        <thead>
            <tr>
                <?php foreach ($header as $value) {
                    echo "<th>".$value."</th>";
                } ?>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<?php $this->load->view('layout/footer.php'); ?>

<script>
    $(document).ready(function(){
        let sort_by = '<?= $sort_by ?>';
        let sort_type = '<?= $sort_type ?>';
        let dont_sort = <?= json_encode($dont_sort) ?>;
        let table_settings = {
            'processing' : true,
            'pageLength' : 10,
            'ajax': {
                url: '<?php echo base_url();?>api/list/<?= $page_name ?>',
                type: 'POST',
                data: {} 
            }
        };
        if(sort_by != ''){
            table_settings.order = [[sort_by, sort_type]];
        }
        if(dont_sort.length != 0){
            table_settings.columnDefs = [{'targets': dont_sort, 'orderable': false}];
        }
        console.log(table_settings);
        $('#list_table').DataTable(table_settings);
    });
</script>
    