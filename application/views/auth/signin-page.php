<div class="card card-outline card-primary">
    <div class="card-header text-center">
        <a href="<?= base_url() ?>" class="h1">ACI Sign In</a>
    </div>
    <div class="card-body">
        <p class="login-box-msg">Sign in to start your journey</p>
		<?= $this->session->flashdata('message') ?>
		<form action="<?= base_url('') ?>" method="post">
			<div class="input-group">
				<input type="text" name="email" class="form-control" placeholder="Email" value="<?= set_value('email') ?>">
				<div class="input-group-append">
					<div class="input-group-text">
						<span class="fas fa-envelope"></span>
					</div>
				</div>
			</div>
			<div class="mb-3">
                <?= form_error('email', '<small class="text-danger"> <i class="fas fa-times"></i> ', '</small>') ?>
            </div>

			<div class="input-group">
				<input type="password" name="password" id="password" class="form-control" placeholder="Password">
				<div class="input-group-append">
					<div class="input-group-text" style="margin: 0; padding-left: 11px; padding-right: 11px;">
						<a href="#" class="text-dark" id="icon-click-password"><i class="fas fa-eye" id="icon-password"></i></a>
					</div>
				</div>
			</div>
			<div class="mb-4">
                <?= form_error('password', '<small class="text-danger"> <i class="fas fa-times"></i> ', '</small>') ?>
            </div>

			<div class="row mb-4">
				<div class="col-4">
					<button type="submit" class="btn btn-primary btn-block">Sign In</button>
				</div>
			</div>
		</form>
		<p class="mb-1">
			<a href="<?= base_url('passwordreset') ?>">I forgot my password</a>
		</p>
		<p class="mb-0">Dont Have Any Account? 
			<a href="<?= base_url('register') ?>" class="text-center">Click Here</a>
		</p>
    </div>
</div>
