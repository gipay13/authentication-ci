<div class="card card-outline card-primary">
    <div class="card-header text-center">
        <a href="<?= base_url('passwordreset') ?>" class="h1">Password Reset</a>
    </div>
    <div class="card-body">
        <p class="login-box-msg">You forgot your password? Here you can easily retrieve a new password.</p>
        <?= $this->session->flashdata('message') ?>
        <form action="<?= base_url('auth/passwordreset') ?>" method="post">
            <div class="input-group">
                <input type="text" name="email" class="form-control" placeholder="Email" value="<?= set_value('email') ?>">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                    </div>
                </div>
            </div>
            <div class="mb-4">
                <?= form_error('email', '<small class="text-danger"> <i class="fas fa-times"></i> ', '</small>') ?>
            </div>
            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-block">Request new password</button>
                </div>
            </div>
        </form>
        <p class="mt-3 mb-1">
            <a href="<?= base_url() ?>">Sign In</a>
        </p>
    </div>
</div>