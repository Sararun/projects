<div class="col-md-12">
    <?php /** @var array $item */?>
    <div class="card">
        <div class="card-body">
            <p>User password: <?php echo $_SESSION['success_register']['username']; ?></p>
            <p>User password: <?php echo $_SESSION['success_register']['email']; ?></p>
            <p>User password: <?php echo $_SESSION['success_register']['password']; ?></p>
        </div>
    </div>
</div>
<?php unset($_SESSION['success_register']); ?>
