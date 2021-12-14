<div class="card card-outline card-primary">
    <div class="card-header text-center">
        <a href="<?= base_url('register') ?>" class="h1">ACI Sign Up</a>
    </div>
    <div class="card-body">
        <p class="login-box-msg">Register new account</p>
        <form action="<?= base_url('register') ?>" method="post">
			<div class="input-group">
                <input type="text" name="name" class="form-control" placeholder="Full Name" value="<?= set_value('name') ?>">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-user"></span>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <?= form_error('name', '<small class="text-danger"> <i class="fas fa-times"></i> ', '</small>') ?>
            </div>

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
            <div class="mb-3">
                <?= form_error('password', '<small class="text-danger"> <i class="fas fa-times"></i> ', '</small>') ?>
            </div>
			<div class="input-group">
                <input type="password" name="repassword" id="repassword" class="form-control" placeholder="Retype Password">
                <div class="input-group-append">
                    <div class="input-group-text" style="margin: 0; padding-left: 11px; padding-right: 11px;"	>
                        <a href="#" class="text-dark" id="icon-click-repassword"><i class="fas fa-eye" id="icon-repassword"></i></a>
                    </div>
                </div>
            </div>
			<div class="mb-4">
                <?= form_error('repassword', '<small class="text-danger"> <i class="fas fa-times"></i> ', '</small>') ?>
            </div>
            <div class="row">
                <div class="col-4">
                    <button type="submit" class="btn btn-primary btn-block">Sign Up</button>
                </div>
            </div>
        </form>
        <p class="mt-3">
            <a href="<?= base_url() ?>" class="text-center">I Already Have Account</a>
        </p>
    </div>
</div>
