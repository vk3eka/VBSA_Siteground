<?php
// Show PHP errors

error_reporting(0);

require_once 'classes/user.php';

$objUser = new User();

// GET
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    try {
        if ($id != null) {
            if ($objUser->delete($id)) {
                $objUser->redirect('index.php?deleted');
            }
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
} 
?>
<!doctype html>
<html lang="en">
    <head>
        <!-- Head metas, css, and title -->
        <?php require_once 'includes/head.php'; ?>
    </head>
    <body>
        <!-- Header banner -->
        <?php require_once 'includes/header.php'; ?>
        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar menu -->
                <?php require_once 'includes/sidebar.php'; ?>
                <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
                    <h1 style="margin-top: 10px">DataTable</h1>

                    <?php 
                        if (isset($_GET['updated'])) {
                            echo '<div class="alert alert-info alert-dismissible fade show" role="alert">
                                    <strong>User!</strong> Updated with success.
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>';
                        } else if (isset($_GET['deleted'])) {
                            echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    <strong>User!</strong> Deleted with success.
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>';
                        } else if (isset($_GET['inserted'])) {
                            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>User!</strong> Inserted with success.
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>';
                        } else if (isset($_GET['error'])) {
                            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>DB Error!</strong> Something goes wrong during the database transaction. Try again!
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>';
                        } 
                    ?>

                    <div class="table-responsive">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Full Name</th>
                                    <th>E-mail</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <?php
                                $query = "SELECT * FROM crud_users";
                                $stmt = $objUser->runQuery($query);
                                $stmt->execute();
                            ?>
    					    <tbody>
                            <?php if ($stmt->rowCount() > 0) {
                                while ($rowUser = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
							<tr>
								<td><?php print($rowUser['id']); ?></td>
								<td>
                                    <a href="form.php?edit_id=<?php print($rowUser['id']); ?>">
										<?php print($rowUser['name']); ?>
									</a>
                                </td>
								<td><?php print($rowUser['email']); ?></td>
								<td>
                                    <a class="confirmation" href="index.php?delete_id=<?php print($rowUser['id']); ?>">
                                        <span data-feather="trash"></span>
                                    </a>
                                </td>
							</tr>
							<?php }} else { ?>
							<tr>
								<td colspan="4">No record found...</td>
							</tr>
							<?php } ?>
                        </table>
                    </div>
                </main>
            </div>
        </div>
        <!-- Footer scripts, and functions -->
        <?php require_once 'includes/footer.php'; ?>

        <!-- Custom scripts -->
        <script>
            // JQuery confirmation
            $('.confirmation').on('click', function () {
                return confirm('Are you sure you want do delete this user?');
            });
        </script>
    </body>
</html>
