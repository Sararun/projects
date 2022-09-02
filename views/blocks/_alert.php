<div class="row">
    <div class="col-12">
        <?php if (!empty($_SESSION['any'])): ?>
            <div id="hide_error_any" class="alert alert-danger" role="alert">
                <?php foreach ($_SESSION['any'] as $message): ?>
                    <?php echo $message; ?><br>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['error']; ?><br>
            </div>
        <?php endif; ?>

        <?php if (!empty($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['success']; ?><br>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php unset($_SESSION['any'], $_SESSION['success'], $_SESSION['error']); ?>

