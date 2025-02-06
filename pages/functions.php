<?php
function checkUserAccess($db) {
    if (!isset($_SESSION['MEMBER_ID'])) {
        die('Access Denied: No session found.');
    }

    $query = 'SELECT ID, t.TYPE 
              FROM users u 
              JOIN type t ON t.TYPE_ID = u.TYPE_ID 
              WHERE ID = ?';

    $stmt = $db->prepare($query);
    $stmt->bind_param('i', $_SESSION['MEMBER_ID']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if ($row['TYPE'] == 'User  ') {
            redirectWithMessage(
                'Restricted Page! You will be redirected to POS',
                'pos.php'
            );
        }
    }
}



function getSearchQuery() {
    global $db;
    $search_query = '';
    if (isset($_POST['search'])) {
        $search_query = mysqli_real_escape_string($db, $_POST['search']);
    }
    return $search_query;
}

function fetchCategories($db, $search_query) {
    $sql = "SELECT CATEGORY_ID, CNAME 
            FROM category 
            WHERE CNAME LIKE ? 
            ORDER BY CNAME ASC";
    
    $stmt = $db->prepare($sql);
    $like_query = '%' . $search_query . '%'; // Prepare the LIKE query
    $stmt->bind_param('s', $like_query); // 's' indicates the type is string
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result) {
        die("Bad SQL: $sql");
    }
    return $result;
}





// Function to fetch categories as a select dropdown
function getCategories($db) {
    $sql = "SELECT DISTINCT CNAME, CATEGORY_ID FROM category ORDER BY CNAME ASC";
    $result = mysqli_query($db, $sql) or die("Bad SQL: $sql");

    $options = "<select class='form-control' name='category' required>
            <option disabled selected hidden>Select Category</option>";
    while ($row = mysqli_fetch_assoc($result)) {
        $options .= "<option value='" . $row['CATEGORY_ID'] . "'>" . $row['CNAME'] . "</option>";
    }
    $options .= "</select>";

    return $options;
}

// Function to fetch suppliers as a select dropdown
function getSuppliers($db) {
    $sql2 = "SELECT DISTINCT SUPPLIER_ID, COMPANY_NAME FROM supplier ORDER BY COMPANY_NAME ASC";
    $result2 = mysqli_query($db, $sql2) or die("Bad SQL: $sql2");

    $sup = "<select class='form-control' name='supplier' required>
            <option disabled selected hidden>Select Supplier</option>";
    while ($row = mysqli_fetch_assoc($result2)) {
        $sup .= "<option value='" . $row['SUPPLIER_ID'] . "'>" . $row['COMPANY_NAME'] . "</option>";
    }
    $sup .= "</select>";

    return $sup;
}

// Function to fetch product details and render the table rows
function getProducts($db) {
    $query = 'SELECT 
                p.PRODUCT_ID, 
                p.PRODUCT_CODE, 
                p.NAME, 
                p.PRICE, 
                c.CNAME, 
                s.COMPANY_NAME 
              FROM product p 
              JOIN category c ON p.CATEGORY_ID = c.CATEGORY_ID 
              JOIN supplier s ON p.SUPPLIER_ID = s.SUPPLIER_ID 
              GROUP BY p.PRODUCT_CODE';
    $result = mysqli_query($db, $query) or die(mysqli_error($db));

    $rows = '';
    while ($row = mysqli_fetch_assoc($result)) {
        $rows .= '<tr>';
        $rows .= '<td>' . $row['PRODUCT_CODE'] . '</td>'; // Product Code
        $rows .= '<td>' . $row['NAME'] . '</td>';         // Product Name
        $rows .= '<td>' . $row['PRICE'] . '</td>';        // Price
        $rows .= '<td>' . $row['CNAME'] . '</td>';        // Category Name
        $rows .= '<td>' . $row['COMPANY_NAME'] . '</td>'; // Company Name (Supplier)
        $rows .= '<td align="right">
                    <div class="btn-group">
                        <a type="button" class="btn btn-primary bg-gradient-primary" href="pro_searchfrm1.php?action=edit&id=' . $row['PRODUCT_ID'] . '">
                            <i class="fas fa-fw fa-list-alt"></i> Details
                        </a>
                        <div class="btn-group">
                            <a type="button" class="btn btn-primary bg-gradient-primary dropdown no-arrow " data-toggle="dropdown" style="color:white;">
                                ... <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu text-center" role="menu">
                                <li>
                                    <a type="button" class="btn btn-warning bg-gradient-warning btn-block" style="border-radius: 0px;" href="pro_edit.php?action=edit&id=' . $row['PRODUCT_ID'] . '">
                                        <i class="fas fa-fw fa-edit"></i> Edit
                                    </a>
                                </li>
                                <li>
                                    <a type="button" class="btn btn-danger bg-gradient-danger btn-block" style="border-radius: 0px;" 
                                       href="pro_del.php?id=' . $row['PRODUCT_ID'] . '" 
                                       onclick="return confirm(\'Are you sure you want to delete this product?\');">
                                        <i class="fas fa-fw fa-trash"></i> Delete
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                  </td>';
        $rows .= '</tr>';
    }

    return $rows;
}

// Function to fetch product details based on the product ID
function getProductDetails($db, $productId) {
    $query = 'SELECT PRODUCT_ID, PRODUCT_CODE, NAME, DESCRIPTION, QTY_STOCK, PRICE, c.CNAME 
              FROM product p 
              JOIN category c ON p.CATEGORY_ID = c.CATEGORY_ID 
              WHERE PRODUCT_ID = ?';
    
    $stmt = $db->prepare($query);
    $stmt->bind_param('i', $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_assoc();
}

?>