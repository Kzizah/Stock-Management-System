<?php
include '../includes/connection.php';
include '../includes/sidebar.php';

function checkUserAccess($db) {
    $query = 'SELECT ID, t.TYPE 
              FROM users u 
              JOIN type t ON t.TYPE_ID = u.TYPE_ID 
              WHERE ID = ?';
    
    $stmt = $db->prepare($query);
    $stmt->bind_param('i', $_SESSION['MEMBER_ID']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if ($row['TYPE'] == 'User ') {
            redirectWithMessage(
                'Restricted Page! You will be redirected to POS',
                'pos.php'
            );
        }
    }
}

function redirectWithMessage($message, $location) {
    echo "<script type='text/javascript'>
            alert('$message');
            window.location = '$location';
          </script>";
    exit;
}

function addCategory($db, $category_name) {
    try {
        $query = "INSERT INTO category (CNAME) VALUES (?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param('s', $category_name);
        
        if ($stmt->execute()) {
            return [
                'success' => true,
                'message' => 'Category added successfully!'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Error adding category: ' . $db->error
            ];
        }
    } catch (Exception $e) {
        return [
            'success' => false,
            'message' => 'Error adding category: ' . $e->getMessage()
        ];
    }
}

function handleFormSubmission($db) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['category_name'])) {
            $result = addCategory($db, $_POST['category_name']);
            
            if ($result['success']) {
                redirectWithMessage($result['message'], 'category.php');
            } else {
                redirectWithMessage($result['message'], '');
            }
        }
    }
}

function renderAddCategoryForm() {
    ?>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h4 class="m-2 font-weight-bold text-primary">Add New Category</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <div class="form-group">
                    <label for="category_name">Category Name</label>
                    <input type="text" 
                           class="form-control" 
                           id="category_name" 
                           name="category_name" 
                           required>
                </div>
                <button type="submit" class="btn btn-primary">Add Category</button>
            </form>
        </div>
    </div>
    <?php
}

// Main execution
checkUserAccess($db);
handleFormSubmission($db);
renderAddCategoryForm();

include '../includes/footer.php';
?>