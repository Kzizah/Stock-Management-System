<?php
// Define categories
$categories = [
    'keyboard' => 0,
    'Mouse' => 1,
    'Headset' => 6,
    'CPU' => 7,
    'Monitor' => 2,
    'Motherboard' => 3,
    'Processor' => 4,
    'Power Supply' => 5,
    'others' => 9,
    'reme' => 14,
];
?>

<!-- Tab panes -->
<div class="tab-content">
    <?php foreach ($categories as $categoryName => $categoryId): ?>
        <div class="tab-pane fade in mt-2" id="<?php echo strtolower(str_replace(' ', '', $categoryName)); ?>">
            <div class="row">
                <?php
                $query = 'SELECT * FROM product WHERE CATEGORY_ID=? GROUP BY PRODUCT_CODE ORDER BY PRODUCT_CODE ASC';
                $stmt = $db->prepare($query);
                $stmt->bind_param('i', $categoryId);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result && $result->num_rows > 0):
                    while ($product = $result->fetch_assoc()):
                ?>
                        <div class="col-sm-4 col-md-2">
                            <form method="post" action="pos.php?action=add&id=<?php echo $product['PRODUCT_ID']; ?>">
                                <div class="products">
                                    <h6 class="text-info"><?php echo htmlspecialchars($product['NAME']); ?></h6>
                                    <h6>Price: <?php echo htmlspecialchars($product['PRICE']); ?></h6>
                                    <h6>Quantity: <?php echo htmlspecialchars($product['QTY_STOCK']); ?></h6>
                                    <input type="text" name="quantity" class="form-control" value="1" />
                                    <input type="hidden" name="name" value="<?php echo htmlspecialchars($product['NAME']); ?>" />
                                    <input type="hidden" name="price" value="<?php echo htmlspecialchars($product['PRICE']); ?>" />
                                    <input type="submit" name="addpos" style="margin-top:5px;" class="btn btn-info" value="Add" />
                                </div>
                            </form>
                        </div>
                <?php
                    endwhile;
                else:
                    echo '<p class="text-warning">No products available in this category.</p>';
                endif;
                ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
