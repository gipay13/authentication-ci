<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Profile</h1>
            </div>
            <div class="col-sm-6">
               
            </div>
        </div>
      </div>
</section>

<section class="content">
    <?= form_open_multipart('users/profile'); ?>  
        <div class="card">
            <div class="card-body">
			    <div class="form-group">
				    <label for="image">Image</label>
                    <div class="row">
                        <div class="col-md-3 col-sm-12">
                            <img src="<?= base_url('uploads/image_profile/'.$user->image) ?>" alt="" class="img-thumbnail">
                        </div>
                        <div class="col-md-9 col-sm-12">
                            <input type="file" name="image" class="form-control" id="image">
                        </div>
                    </div>
			    </div>
                <div class="form-group">
				    <label for="name">Name</label>
				    <input type="text" name="name" class="form-control" id="name" value="<?= $user->name ?>">
                    <?= form_error('name', '<small class="text-danger"> <i class="fas fa-times"></i> ', '</small>') ?>
			    </div>
                <div class="form-group">
				    <label for="gender">Gender</label>
				    <select name="gender" id="gender" class="form-control">
                        <option value="">--Select--</option>
                        <option value="M" <?= $user->gender == 'M' ? 'selected' : null ?>>Male</option>
                        <option value="F" <?= $user->gender == 'F' ? 'selected' : null ?>>Female</option>
                    </select>
                    <?= form_error('gender', '<small class="text-danger"> <i class="fas fa-times"></i> ', '</small>') ?>
			    </div>
                <div class="form-group">
				    <label for="address">Address</label>
                    <textarea name="address" id="address" cols="30" rows="5" class="form-control"><?= $user->address ?></textarea>
                    <?= form_error('address', '<small class="text-danger"> <i class="fas fa-times"></i> ', '</small>') ?>
                </div>
                <div class="form-group">
				    <label for="email">Email</label>
				    <input type="email" name="email" class="form-control" id="email" value="<?= $user->email ?>" readonly>
			    </div>
            </div>					
			<div class="card-footer">
				<button type="submit" class="btn btn-primary">Update</button>
                <a href="<?= base_url('dashboard') ?>" class="btn btn-danger">Cancel</a>
			</div>
        </div>
    <?= form_close(); ?>
</section>