<div class="col-md-12">
    <?php /** @var array $taskList */?>
    <?php if (!empty($taskList)): ?>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Executor</th>
                <th scope="col">Title</th>
                <th scope="col">Description</th>
                <th scope="col">Created_at</th>
                <th scope="col">Deadline</th>
                <th scope="col">Status</th>
                <th scope="col">Handle</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($taskList as $value): ?>
                <tr id="tr<?php echo $value['id']; ?>" class="<?php if ($value['executed']): ?>table-success<?php endif; ?>">
                    <th scope="row"><?php echo $value['id']; ?></th>
                    <td>
                        <?php echo $value['username']; ?>
                    </td>
                    <td>
                        <a href="/edit?id=<?php echo $value['id']; ?>">
                            <?php echo $value['title']; ?>
                        </a>
                    </td>
                    <td><?php echo $value['description']; ?></td>
                    <td><?php echo $value['created_at']; ?></td>
                    <td><?php echo $value['deadline']; ?></td>
                    <td>
                        <div class="form-check">
                            <input data-id="<?php echo $value['id']; ?>"
                                   value="<?php echo $value['executed'] ?>"
                                   class="form-check-input check_status check<?php echo $value['id']; ?>"
                                   type="checkbox" <?php if ($value['executed']): ?>checked<?php endif; ?>>
                        </div>
                    </td>
                    <td>
                        <a class="btn btn-primary" href="/edit?id=<?php echo $value['id']; ?>" role="button">Edit</a>
                        <a class="btn btn-danger" href="/delete?id=<?php echo $value['id']; ?>" role="button">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-danger" role="alert">
            Позапросу ничего не найдено.
        </div>
        <a class="btn btn-secondary" href="/" role="button">Сброс</a>
    <?php endif; ?>

    <h1>Add to task</h1>
    <form action="/store" method="post">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['_csrf']; ?>">
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input id="title" name="title"
                   value="<?php if (!empty($_SESSION['data']['title'])): ?><?php echo $_SESSION['data']['title']; ?><?php endif; ?>"
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
            <textarea id="description" name="description" class="form-control" rows="3"><?php if (!empty($_SESSION['data']['description'])): ?><?php echo $_SESSION['data']['description']; ?><?php endif; ?></textarea>
        </div>
        <div class="mb-3">
            <label for="deadline" class="form-label">Deadline</label>
            <input id="deadline" name="deadline" type="datetime-local" class="form-control">
        </div>
        <input type="hidden" name="mode" value="create">
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
<?php unset($_SESSION['data']); ?>
