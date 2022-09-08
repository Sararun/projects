<div class="col-md-12">
    <?php /** @var array $item */?>
    <h1>Update task</h1>
    <hr>
    <form action="/update?id=<?php echo $item['id']; ?>" method="post">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['_csrf']; ?>">
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input id="title" name="title"
                <?php if (empty($_SESSION['data']['title'])): ?>
                    value="<?php echo $item['title']; ?>"
                <?php else: ?>
                    value="<?php echo $_SESSION['data']['title']; ?>"
                <?php endif; ?>
                   type="text" class="form-control">
        </div>
        <?php if (!empty($_SESSION['user']) && ($_SESSION['user']['role'] == 1)): ?>
            <div class="mb-3">
                <label for="title" class="form-label">Users</label>
                <select name="user_id" class="form-select" aria-label="Default select example">
                    <?php /** @var array $users */?>
                    <?php foreach ($users as $value): ?>
                        <option value="<?php echo $value['id']; ?>">
                            <?php echo $value['username']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php endif; ?>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" name="description" class="form-control" rows="3"><?php if (empty($_SESSION['data']['description'])): ?><?php echo $item['description']; ?><?php else: ?><?php echo $_SESSION['data']['description']; ?><?php endif; ?></textarea>
        </div>
        <div class="mb-3">
            <label for="created_at" class="form-label">Created</label>
            <input id="created_at" name="created_at" value="<?php echo date('Y-m-d H:i:s', strtotime($item['created_at'])); ?>"
                   type="text" class="form-control" disabled>
        </div>
        <div class="mb-3">
            <label for="deadline" class="form-label">Title</label>
            <input id="deadline" name="deadline" value="<?php echo date('Y-m-d H:i:s', strtotime($item['deadline'])); ?>"
                   type="datetime-local" class="form-control">
        </div>
        <input type="hidden" name="mode" value="updated">
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
<?php unset($_SESSION['data']); ?>