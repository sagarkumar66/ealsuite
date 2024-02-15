<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ealsuite</title>
    <link rel="icon" type="image/png" sizes="32x32" href="https://ealsuite.com/assets/img/ealsuite/favicons/favicon-32x32.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/styles.css">
</head>

<body>
    <nav class="navbar fixed-top navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="https://ealsuite.com/"><img src="https://ealsuite.com/assets/img/ealsuite/ealsuite_logo.svg" alt="company-logo"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?php echo $page_name == 'customer' ? 'active' : ''; ?>" href="<?php echo base_url() ?>Customer">Customer</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $page_name == 'invoice' ? 'active' : ''; ?>" href="<?php echo base_url() ?>Invoice">Invoice</a>
                    </li>
                    <?php if(isset($_SESSION['userName'])){ ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $page_name == 'logout' ? 'active' : ''; ?>" href="<?php echo base_url() ?>Home/logout">Logout</a>
                    </li>
                    <?php } ?>
                </ul>
                <span class="navbar-text">
                    <?php echo isset($_SESSION['userName']) ? 'Hello '.$_SESSION['userName'] : '' ?>
                </span>
            </div>
        </div>
    </nav>