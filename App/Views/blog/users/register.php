<!-- Register Page -->
<div id="register-page" class="page box">
    <!-- Centered Content -->
    <div class="centered-content">
        <h1 class="heading">Create New Account</h1>
        <!-- Form -->
        <form action="<?php echo url('/register/submit'); ?>" class="form">
            <div class="form-group">
                <label for="first_name" class="col-xs-1">Name</label>
                <div class="col-sm-12 col-xs-12">
                    <input type="text" name="name" id="first_name" placeholder="First Name" class="input placeholder form-control" />
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-xs-1">Email</label>
                <div class="col-sm-12 col-xs-12">
                    <input type="email" name="email" id="email" placeholder="Email Address" class="input placeholder form-control" />
                </div>
            </div>
            <div class="form-group">
                <label for="password" class="col-xs-1">Password</label>
                <div class="col-sm-12 col-xs-12">
                    <input type="password" name="pass" id="password" placeholder="Password" class="input form-control" />
                </div>
            </div>
            <div class="form-group">
                <label for="confirm_password" class="col-xs-1">Confirm Password</label>
                <div class="col-sm-12 col-xs-12">
                    <input type="password" name="re-pass" id="confirm_password" placeholder="Confirm Password" class="input form-control" />
                </div>
            </div>
            <div class="clearfix"></div>
            <div id="form-results"></div>
            <div class="form-group">
                <div class="col-sm-offset-0 col-sm-12 col-xs-12">
                    <button class="button bold submit-btn btn-block form-control">Sign Up</button>
                </div>
            </div>
            <div class="form-group text-center">
                Already have an account ? <a href="<?php echo url('/login'); ?>">Login here</a>
            </div>
        </form>
        <!--/ Form -->
    </div>
    <!--/ Centered Content -->
</div>
<!--/ Register Page -->