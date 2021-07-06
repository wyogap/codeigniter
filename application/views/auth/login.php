<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>RAPS Dinas Pendidikan Kabupaten Kebumen</title>
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<link href="<?php echo base_url();?>assets/image/tutwuri.png" rel="shortcut icon">
		<link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/plugins/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/dist/css/adminlte.min.css">

		<link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/plugins/fontawesome/css/all.min.css">

		<script src="<?php echo base_url();?>assets/adminlte/plugins/jquery/jquery.min.js"></script>
		<script src="<?php echo base_url();?>assets/adminlte/plugins/bootstrap/js/bootstrap.min.js"></script>
		<script src="<?php echo base_url();?>assets/formvalidation/form-validator/jquery.form-validator.js"></script>
		
		<script type="text/javascript">
			if (typeof jQuery == 'undefined') {
				document.write(unescape('%3Clink rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" /%3E'));
				document.write(unescape('%3Clink rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.0.5/css/adminlte.min.css" /%3E'));
				document.write(unescape('%3Cscript type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.slim.min.js" %3E%3C/script%3E'));
				document.write(unescape('%3Cscript type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" %3E%3C/script%3E'));
			}
		</script>
		
		<script src='https://www.google.com/recaptcha/api.js' async defer></script>	
	</head>

<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="../../index2.html"><b>RAPS</b></a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Masuk ke aplikasi</p>
	  <?php $this->load->helper('form'); ?>
	  <p class="login-box-msg text-info">
		<div class="row">
			<div class="col-md-12">
				<?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
			</div>
		</div>
		<?php
		$this->load->helper('form');
		$error = $this->session->flashdata('error'); 	
		if($error)
		{
			?>
			<div class="alert alert-danger alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<?php echo $error; ?>                    
			</div>
		<?php }
		$success = $this->session->flashdata('success');
		if($success)
		{
			?>
			<div class="alert alert-success alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<?php echo $success; ?>                    
			</div>
		<?php } ?>
	  </p>

	  <form role="form" enctype="multipart/form-data" id="proses" action="<?php echo site_url();?>/auth/login/" method="post">
        <div class="input-group mb-3">
		  <input type="text" class="form-control" placeholder="Username" id="username" name="username" data-validation="required" minlength="4" maxlength="100">
		  <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
		  <input type="password" class="form-control" placeholder="Password" id="password" name="password" data-validation="required">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
				 
		<?php if($use_captcha == 1) { ?>
		<?php if(strpos(base_url(), 'localhost')) { ?>
			<!-- localhost -->
			<div class="form-group has-feedback">
				<div class="g-recaptcha" data-sitekey="6LdDOOoUAAAAABvtPcoIZ4RHTm545Wb9lgD8j2Ab" style="width=100%"></div>
			</div>
		<?php } else { ?>
			<!-- ppdb.disdik.kebumenkab.go.id -->
			<div class="form-group has-feedback">
				<div class="g-recaptcha" data-sitekey="6LfUN-oUAAAAAAEiaEPyE-S-d3NRbzXZVoNo51-x" style="width=100%"></div>
			</div>
		<?php } ?>
		<?php } ?>

		<div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Masuk</button>
          </div>
        </div>

		<!-- <div class="row">
			<div class="col-12">
			<p class="mb-3">
				<a href="forgot-password.html">Lupa password saya</a>
			</p>
			</div>
	  	</div> -->
      </form>
	</div>
  </div>
</body>

	<script>
			var $messages = $('#error-message-wrapper');
				$.validate({
					modules: 'security',
					errorMessagePosition: $messages,
					scrollToTopOnError: false
				});
		</script>
</html>
