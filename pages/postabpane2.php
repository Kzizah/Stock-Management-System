<?php
include '../includes/connection.php';

// Fetch categories
$query = "SELECT * FROM category";
$result = mysqli_query($db, $query) or die(mysqli_error($db));

$categories = [];
while($row = mysqli_fetch_assoc($result)) {
    $categories[] = $row;
}

// If a category is clicked, get the category ID from GET
$selectedCategoryId = isset($_GET['category_id']) ? (int)$_GET['category_id'] : null;
?>

<!-- Categories Navigation (Tabs) -->
<ul class="nav nav-tabs">
    <?php foreach ($categories as $category): ?>
        <li class="nav-item">
            <a class="nav-link <?php echo ($selectedCategoryId == $category['CATEGORY_ID']) ? 'active' : ''; ?>" 
               href="?category_id=<?php echo $category['CATEGORY_ID']; ?>">
                <?php echo htmlspecialchars($category['CNAME']); ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>

<!-- Tab Content -->
<div class="tab-content">
    <?php
    if ($selectedCategoryId !== null) {
        // Fetch products for the selected category
        $query2 = "SELECT * FROM product WHERE CATEGORY_ID = $selectedCategoryId";
        $result2 = mysqli_query($db, $query2);

        if ($result2 && mysqli_num_rows($result2) > 0): ?>
            <div class="tab-pane fade show active mt-2">
                <!-- Search Bar -->
                <div class="mb-3">
                    <input type="text" id="productSearch" class="form-control" placeholder="Search for products..." onkeyup="filterProducts()" />
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table id="productTable" class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>Product Name</th>
                                <th>Price</th>
                                <th>Quantity in Stock</th>
                                <th>Quantity to Add</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($product = mysqli_fetch_assoc($result2)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($product['NAME']); ?></td>
                                    <td><?php echo number_format($product['PRICE'], 2); ?></td>
                                    <td><?php echo number_format($product['QTY_STOCK'], 2); ?></td>
                                    <td>
                                        <form method="post" action="pos.php?action=add&id=<?php echo $product['PRODUCT_ID']; ?>">
                                            <input type="text" name="quantity" class="form-control" value="1" />
                                            <input type="hidden" name="name" value="<?php echo htmlspecialchars($product['NAME']); ?>" />
                                            <input type="hidden" name="price" value="<?php echo $product['PRICE']; ?>" />
                                            <input type="hidden" name="QTY_STOCK" value="<?php echo $product['QTY_STOCK']; ?>" />
                                    </td>
                                    <td>
                                            <input type="submit" name="addpos" class="btn btn-info" value="Add" />
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <p class="text-warning">No products available in this category.</p>
        <?php endif;
    }
    ?>
</div>

<!-- JavaScript for Search Bar -->
<script>
    function filterProducts() {
        const input = document.getElementById('productSearch').value.toLowerCase();
        const table = document.getElementById('productTable');
        const rows = table.getElementsByTagName('tr');

        for (let i = 1; i < rows.length; i++) { // Skip the header row
            const cells = rows[i].getElementsByTagName('td');
            let match = false;

            for (let j = 0; j < cells.length; j++) {
                if (cells[j]) {
                    const text = cells[j].textContent || cells[j].innerText;
                    if (text.toLowerCase().includes(input)) {
                        match = true;
                        break;
                    }
                }
            }

            rows[i].style.display = match ? '' : 'none';
        }
    }
</script>
