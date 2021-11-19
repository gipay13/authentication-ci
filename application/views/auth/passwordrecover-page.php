<div class="card card-outline card-primary">
    <div class="card-header text-center">
        <a href="<?= base_url('passwordrecover') ?>" class="h1">Recover Password</a>
    </div>
    <div class="card-body">
        <p class="login-box-msg"><?= $this->session->userdata('password_reset') ?></p>
        <form action="<?= base_url('passwordrecover?email='.$email.'&token='.$token) ?>" method="post">
            <div class="input-group">
                <input type="password" name="password" class="form-control" placeholder="Password">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <?= form_error('password', '<small class="text-danger"> <i class="fas fa-times"></i> ', '</small>') ?>
            </div>
            <div class="input-group">
                <input type="password" name="repassword" class="form-control" placeholder="Confirm Password">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
            </div>
            <div class="mb-4">
                <?= form_error('repassword', '<small class="text-danger"> <i class="fas fa-times"></i> ', '</small>') ?>
            </div>
            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-block">Change password</button>
                </div>
            </div>
        </form>
        <p class="mt-3 mb-1">
            <a href="<?= base_url() ?>">Login</a>
        </p>
    </div>
</div>