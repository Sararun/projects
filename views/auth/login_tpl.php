<div class="col-md-6">
    <h4 class="mb-4">Login</h4>
    <form action="/login" method="post">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['_csrf']; ?>">
        <input type="hidden" name="mode" value="login">
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" name="email" value="" type="text" class="form-control">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input id="password" name="password" value="" type="text" class="form-control">
        </div>
        <div class="mb-3 form-check">
            <input id="check_me" name="check_me" type="checkbox" class="form-check-input">
            <label class="form-check-label" for="check_me">Check me out</label>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
<div class="col-md-6"></div>
