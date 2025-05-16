<?php
include '../../include/layout/header.php';

$categories = $db->query('SELECT * FROM categories');

$inValidInputTitle = '';
$inValidInputAuthor = '';
$inValidInputBody = '';
$inValidInputImage = '';
if (isset($_POST['addPost'])) {

    if (empty(trim($_POST['title']))) {
        $inValidInputTitle = 'پر کردن عنوان اجباریست.';
    }
    if (empty(trim($_POST['author']))) {
        $inValidInputAuthor = 'پر کردن نویسنده اجباریست.';
    }
    if(empty(trim($_FILES['image']['name']))) {
        $inValidInputImage = 'پر کردن تصویر اجباریست.';
    }
    if (empty(trim($_POST['body']))) {
        $inValidInputBody = 'پر کردن متن اجباریست.';
    }

    if (!empty(trim($_POST['title'])) && !empty(trim($_POST['author'])) && !empty(trim($_FILES['image']['name'])) && !empty(trim($_POST['body']))) {

        $title = $_POST['title'];
        $author = $_POST['author'];
        $body = $_POST['body'];
        $categoryId = $_POST['categoryId'];

        $imageName = time() . '-' . $_FILES['image']['name'];
        $tmpImage = $_FILES['image']['tmp_name'];
        if(move_uploaded_file($tmpImage, "../../../upload/posts/$imageName")) {
            $postInsert = $db->prepare('INSERT INTO posts (title, body, category_id, author, image) VALUES (:title, :body, :category_id, :author, :image)');
            $postInsert->execute(['title' => $title, 'body' => $body, 'category_id' => $categoryId, 'author' => $author, 'image' => $imageName]);

            header('Location:index.php');
            exit();
        } else {
            echo 'Create Error';
        }
    }

}
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Section -->
        <?php include '../../include/layout/sidebar.php' ?>

        <!-- Main Section -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div
                class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="fs-3 fw-bold">ایجاد مقاله</h1>
            </div>

            <!-- Posts -->
            <div class="mt-4">
                <form method="post" class="row g-4" enctype="multipart/form-data">
                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="form-label">عنوان مقاله</label>
                        <input type="text" name="title" class="form-control" />
                        <div class="form-text text-danger"><?= $inValidInputTitle ?></div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="form-label">نویسنده مقاله</label>
                        <input type="text" name="author" class="form-control" />
                        <div class="form-text text-danger"><?= $inValidInputAuthor ?></div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="form-label">دسته بندی مقاله</label>
                        <select name="categoryId" class="form-select">
                            <?php if ($categories->rowCount() > 0): ?>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['id'] ?>"><?= $category['title'] ?></option>
                                <?php endforeach ?>
                            <?php else: ?>
                                <option value="0">دسته بندی وجود ندارد</option>
                            <?php endif ?>
                        </select>
                    </div>

                    <div class="col-12 col-sm-6 col-md-4">
                        <label for="formFile" class="form-label">تصویر مقاله</label>
                        <input name="image" class="form-control" type="file" />
                        <div class="form-text text-danger"><?= $inValidInputImage ?></div>
                    </div>

                    <div class="col-12">
                        <label for="formFile" class="form-label">متن مقاله</label>
                        <textarea
                            name="body"
                            class="form-control"
                            rows="6"></textarea>
                        <div class="form-text text-danger"><?= $inValidInputBody ?></div>
                    </div>

                    <div class="col-12">
                        <button name="addPost" type="submit" class="btn btn-dark">
                            ایجاد
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>

<!-- Footer Section -->
<?php include '../../include/layout/footer.php' ?>