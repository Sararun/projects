<div class="col-md-12">
    <?php /** @var array $taskList */?>
    <?php if (!empty($taskList)): ?>
        <br>
        <div class="accordion" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                        Filter
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <form action="/" method="get" class="row g-3">
                            <input type="hidden" name="filter" value="1">
                            <div class="col-md-4">
                                <label for="title" class="form-label">Title</label>
                                <input id="title" name="title" value="" type="text" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label for="description" class="form-label">Description</label>
                                <input id="description" name="description" value="" type="text" class="form-control">
                            </div>

                            <div class="col-md-4">
                                <label for="executed" class="form-label">Status</label>
                                <select id="executed" name="executed" class="form-select">
                                    <option></option>
                                    <option value="1">Yes</option>
                                    <option value="2">No</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="date_from" class="form-label">Date from</label>
                                <input id="date_from" name="date_from" type="date" class="form-control">
                            </div>

                            <div class="col-md-2">
                                <label for="date_to" class="form-label">Date to</label>
                                <input id="date_to" name="date_to" type="date" class="form-control">
                            </div>
                            <div class="col-12 text-end">
                                <a href="/" class="btn btn-secondary" role="button">Reset</a>
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

        <hr>
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Executor</th>
                    <th scope="col">Title</th>
                    <th scope="col">Description</th>
                    <th scope="col">Created_at</th>
                    <th scope="col">Deadline</th>
                    <th scope="col">Lead time</th>
                    <th scope="col">Status</th>
                    <th scope="col">Handle</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($taskList as $value): ?>
                    <?php $isDeadline = (strtotime(date('Y-m-d H:i:s')) > strtotime($value['deadline']))
                        && ($value['executed'] != 1); ?>
                    <tr id="tr<?php echo $value['id']; ?>"
                        class="<?php if ($value['executed']): ?>table-success<?php endif; ?>
                        <?php if ($isDeadline && ($value['executed'] != 1)): ?>table-danger<?php endif; ?>">
                        <th scope="row"><?php echo $value['id']; ?></th>
                        <td>
                            <?php echo $value['username']; ?> <?php var_dump($isDeadline);?>
                            <?php echo $value['executed']; ?>
                        </td>
                        <td>
                            <a href="/edit?id=<?php echo $value['id']; ?>">
                                <?php echo $value['title']; ?>
                            </a>
                        </td>
                        <td><?php echo $value['description']; ?></td>
                        <td><?php echo $value['created_at']; ?></td>
                        <td><?php echo $value['deadline']; ?></td>
                        <td id="td<?php echo $value['id']; ?>"><?php echo $value['lead_time']; ?></td>
                        <td>
                            <?php if (!$isDeadline): ?>
                                <div class="form-check">
                                    <input data-id="<?php echo $value['id']; ?>"
                                           value="<?php echo $value['executed'] ?>"
                                           class="form-check-input check_status check<?php echo $value['id']; ?>"
                                           type="checkbox"
                                           <?php if ($value['executed']): ?>checked<?php endif; ?>
                                           <?php if ($value['executed'] && ($_SESSION['user']['role'] == 2)): ?>disabled<?php endif; ?>>
                                </div>
                            <?php else: ?>
                                просрочено
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($isDeadline && $_SESSION['user']['role'] == 1): ?>
                                <a class="btn btn-primary" href="/edit?id=<?php echo $value['id']; ?>" role="button">Edit</a>
                            <?php elseif (!$isDeadline): ?>
                                <a class="btn btn-primary" href="/edit?id=<?php echo $value['id']; ?>" role="button">Edit</a>
                            <?php endif; ?>

                            <?php if ($_SESSION['user']['role'] == 1): ?>
                                <a class="btn btn-danger" href="/delete?id=<?php echo $value['id']; ?>" role="button">Delete</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-end">
                <?php echo $paginator; ?>
            </ul>
        </nav>

    <?php else: ?>
        <?php if (!empty($_GET['search'])): ?>
            <div class="alert alert-danger" role="alert">
                По вашему запросу <b>"<?php echo $_GET['search']; ?>"</b> ничего не найдено.
            </div>
            <a class="btn btn-secondary" href="/" role="button">Сброс</a>
        <?php else: ?>
            <p>
                Заданий не найдено.
            </p>
        <?php endif ?>
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
            <textarea id="description" name="description" class="form-control"
                      rows="3"><?php if (!empty($_SESSION['data']['description'])): ?><?php echo $_SESSION['data']['description']; ?><?php endif; ?></textarea>
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
