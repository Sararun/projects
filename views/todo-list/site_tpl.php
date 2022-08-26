<div class="col-md-12">

    <?php // заранее определённая переменная, чтобы интераператор не ругался
    /** @var array $taskList */ ?>
    <?php if (!empty($taskList)): ?>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">title</th>
                <th scope="col">description</th>
                <th scope="col">created_at</th>
                <th scope="col">deadline</th>
                <th scope="col">executed</th>
                <th scope="col">Handle</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($taskList as $value): ?>
                <tr>
                    <th scope="row"><?php echo $value['id']; ?></th>
                    <td><?php echo $value['title']; ?></td>
                    <td><?php echo $value['description']; ?></td>
                    <td><?php echo $value['created_at']; ?></td>
                    <td><?php echo $value['deadline']; ?></td>
                    <td>
                        <input id="is_active" name="is_active" type="checkbox" class="form-check-input"
                                <?php if (!empty($value['executed'])): ?>
                                    checked<?php else: ?> disabled <?php
                                endif;?>>
                    </td>
                    <td>

                        <a class="btn btn-primary" href="/update?id=<?php echo $value['id']; ?>" role="button">Edit</a>
                        <a class="btn btn-danger" href="/delete?id=<?php echo $value['id']; ?>" role="button">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <h1>Add to task</h1>
    <form action="/store" method="post">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['_csrf']; ?>">
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input id="title" name="title" type="text" class="form-control">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" name="description" class="form-control" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label for="deadline" class="form-label">Title</label>
            <input id="deadline" name="deadline" type="datetime-local" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>