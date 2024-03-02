<?php $this->load->view('layout/header.php'); ?>
<div class="container content p-3">
    <div class="row">
        <div class="col-lg-3"></div>
        <div class="col-lg-6">
            <h4 class="text-center">Edit <?= ucwords($page_name) ?></h4>
            <form id="edit_module_form" class="mt-3">
                <div id="message" role="alert"></div>
                <?php if(count($fields) > 0){ ?>
                    <?php foreach ($fields as $key => $value) { ?>
                        <div class="form-group mb-3">
                            <label for="<?= $value['id'] ?>"><?= $value['label'] ?></label>
                            <?php if($value['field'] == 'input'){ ?>
                                <input 
                                    type="<?= $value['type'] ?>" 
                                    name="<?= $value['id'] ?>" 
                                    id="<?= $value['id'] ?>" 
                                    placeholder="Enter <?= $value['id'] ?>" 
                                    class="form-control" 
                                    autocomplete="off" 
                                    <?= $value['required']?'required':'' ?>
                                    <?php if(count($value['extra']) > 0){ ?>
                                        <?php foreach ($value['extra'] as $prop => $prop_value) { ?>
                                            <?= $prop.'="'.$prop_value.'"' ?>
                                        <?php } ?>
                                    <?php } ?>
                                >
                            <?php } else if($value['field'] == 'textarea'){ ?>
                                <textarea 
                                    name="<?= $value['id'] ?>" 
                                    id="<?= $value['id'] ?>" 
                                    placeholder="Enter <?= $value['id'] ?>" 
                                    class="form-control" 
                                    autocomplete="off" 
                                    <?= $value['required']?'required':'' ?>
                                    <?php if(count($value['extra']) > 0){ ?>
                                        <?php foreach ($value['extra'] as $prop => $prop_value) { ?>
                                            <?= $prop.'="'.$prop_value.'"' ?>
                                        <?php } ?>
                                    <?php } ?>
                                ></textarea>
                            <?php } else if($value['field'] == 'select'){ ?>
                                <select 
                                    name="<?= $value['id'] ?>" 
                                    id="<?= $value['id'] ?>" 
                                    class="form-control" 
                                    autocomplete="off" 
                                    <?= $value['required']?'required':'' ?>
                                    <?php if(count($value['extra']) > 0){ ?>
                                        <?php foreach ($value['extra'] as $prop => $prop_value) { ?>
                                            <?= $prop.'="'.$prop_value.'"' ?>
                                        <?php } ?>
                                    <?php } ?>
                                >
                                    <option value=""> -- Select -- </option>
                                    <?php if(count($value['options']) > 0){ ?>
                                        <?php foreach ($value['options'] as $option) { ?>
                                            <option value="<?= $option['value'] ?>"><?= $option['label'] ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            <?php } ?>
                        </div>
                    <?php } ?>
                <?php } ?>
                <button type="button" class="btn btn-primary" id="edit_module">Update</button>
            </form>
        </div>
        <div class="col-lg-3"></div>
    </div>
</div>

<?php $this->load->view('layout/footer.php'); ?>

<script>

    $(document).ready(function(){
        const form_values = <?= $values; ?>;
        $.each(form_values, function (i, ele) {
            $('#'+i).val(ele);
        });
        $('#edit_module').click(() => {
            if($('#edit_module_form').valid()){
                $.ajax({
                    'url': '<?= base_url() ?>api/edit/<?= $page_name ?>',
                    'type': "POST",
                    'dataType': 'json',
                    'data': $('#edit_module_form').serialize()+'&id='+<?= $this->uri->segment(3); ?>,
                    'success': function (response) {
                        $('#message').html(response.msg);
                        if(response.status){
                            $('#message').attr("class","alert alert-primary");
                            window.location.href = '<?= base_url().$page_name; ?>'
                        } else {
                            $('#message').attr("class","alert alert-danger");
                        }
                    }
                })
            }
        });
    });
</script>
    