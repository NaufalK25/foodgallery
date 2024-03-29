<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>

<link rel="stylesheet" href="<?= base_url(); ?>assets/css/auth/login.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/templates/navbar.css">
</head>
<body>
    
<?php
    $this->load->view('templates/navbar');
?>

<div class="login-kotak-luar">
    <div class="login-kotak">
        <form action="login" method="POST">
            <!-- Logo -->
            <div class="text-center mb-3">
                <img src="<?= base_url(); ?>assets/img/fg.png" alt="logo" width="180">
            </div>
            <?= $this->session->flashdata('alert'); ?>
            <!-- Username -->
            <div class="form-floating mb-3">
                <input type="text" class="form-control border-dark login-input" id="username" name="username" placeholder="Username" value="<?= set_value('username') ?>" autofocus>
                <label for="username"><i class="fas fa-user"></i> Username</label>
                <?= form_error('username', '<small class="login-error">', '</small>') ?>
            </div>
            <!-- Password -->
            <div class="form-floating mb-3">
                <input type="password" class="form-control border-dark login-input" id="password" name="password" placeholder="Password" autocomplete="off">
                <label for="password"><i class="fas fa-key"></i> Password</label>
                <?= form_error('password', '<small class="login-error">', '</small>') ?>
            </div>
            <div class="text-center mb-3">
                <button type="submit" class="btn btn-outline-primary login-button">Login</button>
            </div>
            <p>Belum Punya Akun? <a href="<?= base_url(); ?>register" class="login-link">Regis Disini</a></p>
        </form>
    </div>
</div>
