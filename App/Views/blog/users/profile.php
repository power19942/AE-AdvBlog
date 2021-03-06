<div class="page box">
    <div class="centered-content">
        <div class="box-body">
            <h3 class="text-center" style="">Manage your profile</h3>
            <image src="<?php echo $img; ?>" class="img-circle img-bordered"
                   style="width: 100px; height: 100px; margin-left: 46%" alt="<?php echo $user->name . '\'s photo'; ?>">
            <form action="<?php echo $action; ?>" class="form-modal form" method="POST" enctype="multipart/form-data">
                <div class="form-group col-sm-6">
                    <label for="username">Username</label>
                    <input type="text" name="name" class="form-control" id="username" placeholder="Username" value="<?php echo $user->name; ?>">
                </div>
                <div class="form-group col-sm-6">
                    <label for="email">Email</label>
                    <input type="email" id="email" class="form-control"
                           name="email" placeholder="someone@example.com"
                           value="<?php echo $user->email; ?>">
                </div>
                <div class="form-group col-sm-12" title="You can write about yourself at maximum characters of 140">
                    <label for="bio">Bio</label>
                    <textarea id="bio" class="form-control" name="bio" style="resize: vertical"><?php echo $bio; ?></textarea>
                </div>
                <div class="form-group col-sm-6">
                    <label for="img">Change profile photo</label>
                    <input type="file" id="img" name="img" class="form-control">
                </div>
                <div class="clearfix"></div>
                <h3 class="text-center">Change password</h3>
                <div class="form-group col-sm-12">
                    <label for="old-pass">Old Password</label>
                    <input type="password" id="old-pass" class="form-control" name="old_pass" placeholder="Old Password">
                </div>
                <div class="form-group col-sm-12">
                    <label for="pass">New Password</label>
                    <input type="password" id="pass" class="form-control" name="pass" placeholder="Choose a password">
                </div>
                <div class="form-group col-sm-12">
                    <label for="re-pass">Confirm New Password</label>
                    <input type="password" id="re-pass" class="form-control" name="re-pass" placeholder="Re-Type password">
                </div>
                <div class="clearfix"></div>
                <div id="form-results"></div>
                <div class="clearfix"></div>
                <div class="col-md-offset-4 col-md-4 col-sm-offset-3 col-sm-6 col-xs-offset-2 col-xs-8">
                    <button class="button submit-btn form-control">Submit</button>
                </div>
                <div class="clearfix"></div>
                <br>
            </form>
        </div>
    </div>
</div>