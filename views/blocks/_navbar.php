<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <?php if (empty($_SESSION['user'])): ?>
            <a class="navbar-brand" href="/login">DEMO-SITE</a>
        <?php else: ?>
            <a class="navbar-brand" href="/">DEMO-SITE</a>
        <?php endif; ?>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php if (empty($_SESSION['user'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/login">Login</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/">Home</a>
                    </li>
                    <?php if ($_SESSION['user']['role'] == 1): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/register">Add User</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/logout?out=1">Logout</a>
                    </li>
                <?php endif; ?>
            </ul>
            <?php if (!empty($_SESSION['user'])): ?>
                <form method="get" class="d-flex">
                    <input id="search" name="search" class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</nav>
