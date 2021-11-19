<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Dashboard</h1>
            </div>
            <div class="col-sm-6">
               
            </div>
        </div>
      </div>
</section>

<section class="content">
    <?= $this->session->flashdata('message') ?>
    <div class="card card-primary card-outline">
        <div class="card-body box-profile">
            <div class="">
                <img class="profile-user-img img-fluid" src="<?= base_url('uploads/image_profile/'.$user->image)?>" alt="User profile picture">
            </div>
            <h3 class="profile-username text-bold"><?= $user->name ?></h3>
            <p class="text-muted">Member</p>
            <div class="row">
                <div class="col-md-6 mb-3">Gender</div>
                <div class="col-md-6 mb-3"><?= $user->gender == null ? 'Not Set' : ($user->gender == 'M' ? 'Male' : 'Female') ?></div>
                <div class="col-md-6 mb-3">Address</div>
                <div class="col-md-6 mb-3"><?= $user->address == null ? 'Not Set' : $user->address ?></div>
                <div class="col-md-6 mb-3">Email</div>
                <div class="col-md-6 mb-3"><?= $user->email ?></div>
            </div>
            <a href="<?= base_url('profile') ?>" class="btn btn-primary btn-block"><b>Update Profile</b></a>
        </div>
    </div>
</section>