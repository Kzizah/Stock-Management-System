<?php
include'../includes/connection.php';

#count the number of product categories

$query = "select * from category";
$result = mysqli_query($db,$query) or die(mysqli_error($db));

$rows = mysqli_num_rows($result);
$count = 0;
while($count < $rows){
	$res = mysqli_fetch_assoc($result);
	$cat_id = $res['CATEGORY_ID'];

	$query2 = "select * from product where CATEGORY_ID='$cat_id' group by CATEGORY_ID";

    $result2 = mysqli_query($db, $query2);
    $rows2 = mysqli_num_rows($result2);
    $count2 = 0;
    while($count2 < $rows2){
    	$res2 = mysqli_fetch_assoc($result2);
		$pro_id = $res2['PRODUCT_ID'];

		echo "
							<!-- Tab panes -->
							<div class='tab-content'>
								<div class='tab-pane fade in mt-2'>
						        <div class='row'>
						            <div class='col-sm-4 col-md-2'>
						                <form method='post' action='pos.php?action=add&id=".$pro_id."'>
						                    <div class='products'>
						                        <h6 class='text-info'>".$res2['NAME']."</h6>
						                        <h6>$ ".$res2['PRICE']."</h6>
						                        <input type='text' name='quantity' class='form-control' value='1' />
						                        <input type='hidden' name='name' value='".$res2['NAME']."' />
						                        <input type='hidden' name='price' value='".$res2['PRICE']."' />
						                        <input type='submit' name='addpos' style='margin-top:5px;' class='btn btn-info' value='Add' />
						                    </div>
						                </form>
						            </div>
						        </div>
						      </div>
					      </div>
					      </div>
                        <!-- /.panel-body -->
                      </div>
                    </div>
                  </div>";


		$count2 = $count2 + 1;
    }


	$count = $count + 1;
}

?>