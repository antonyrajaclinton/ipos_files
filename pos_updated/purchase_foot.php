<?php
include_once 'db/connect_db.php';
session_start();
if ($_SESSION['username'] == "") {
    header('location:index.php');
} else {
    if ($_SESSION['role'] == "Admin") {
        include_once 'inc/header_all.php';
    } else {
        include_once 'inc/header_all_operator.php';
    }
}
$appuser = $_SESSION['username'];
date_default_timezone_set("Asia/Kolkata");
$pdate = date("Y-m-d");
$time = date("H:i:s");
//$exdate =date('Y-m-d', strtotime("+30 days"));
//reprint
if (isset($_POST['re_print']))
//if(!empty($_POST('re_print')))
{
    echo '<script type="text/javascript">
                    jQuery(function validation(){
                    swal("Warning", "Printer Must Be included", "warning", {
                    button: "Continue",
                        });
                    });
                    </script>';
}
//print
if (isset($_POST['add_print'])) {
    $code = $_POST['product_code'];
    $productdesc = $_POST['product_desc'];
    $sproductdesc = $_POST['sproduct_desc'];
    $eancode = $_POST['eancode'];
    $stock = $_POST['stockp'];
    $min_stock = $_POST['min_stock'];
    $uom = $_POST['uom'];
    $gst = $_POST['gst'];
    $mrp = $_POST['mrp'];
    $category = $_POST['category'];
    $purchase = $_POST['purchase_price'];
    $sell = $_POST['sell_price'];
    $exdays = $_POST['exdays'];
    $exdate = date('Y-m-d', strtotime("+" . $exdays . " days"));
    if (isset($_POST['product_code'])) {
        $result1 = mysqli_query($conn, "UPDATE `tbl_product` SET `purchase_price`='$purchase',`sell_price`='$sell',`stock`= `stock`+'$stock',`min_stock`='$min_stock',`MRP`='$mrp',`exdays`='$exdays',`prodd`='$pdate',`expdate`='$exdate',`appuser`='$appuser' WHERE `product_code`='$code'") or die(mysqli_error($conn));
        $result = mysqli_query($conn, "INSERT INTO `tbl_purchase`(`product_code`, `product_name`, `product_category`, `purchase_price`, `sell_price`, `stock`, `min_stock`, `product_unit`, `sdescription`, `gst`, `Ean_code`, `MRP`, `exdays`, `prodd`,`expdate`, `appuser`) VALUES ('$code','$productdesc','$category','$purchase','$sell','$stock','$min_stock','$uom','$sproductdesc','$gst','$eancode','$mrp','$exdays','$pdate','$exdate','$appuser')") or die(mysqli_error($conn));
        if ($result) {
            echo '<script type="text/javascript">
                          jQuery(function validation(){
                          swal("Success", "Purchase Added, Printer Must be included", "success", {
                          button: "Continue",
                              });
                          });
                          </script>';
        }
    }
}
if (isset($_POST['add_pur'])) {
    $product_code = $_POST['product_code'];
    // $s_product_name = $_POST['s_product_name'];
    // $p_size_no = $_POST['p_size_no'];
    $quantity_product = $_POST['quantity_product'];
    // $pur_price = $_POST['pur_price'];
    // $sell_price = $_POST['sell_price'];
    // $purchase = $_POST['purchase_price'];
    // $gstvalue = $_POST['gstvalue'];

    // $dis_count = $_POST['dis_count'];
    // $gst_price = ($gst / 100) * $sell;
    // $discount_price = ($dis_count / 100) * $sell;
    // $mrp = $gst_price + $sell + $discount_price;
    // if (isset($_POST['product_code'])) {
    //     $result1 = mysqli_query($conn, "UPDATE `tbl_product` SET `purchase_price`='$purchase',`sell_price`='$sell',`stock`= `stock`+'$stock',`min_stock`='$min_stock',`MRP`='$mrp',`prodd`='$pdate',`appuser`='$appuser' WHERE `product_code`='$code'") or die(mysqli_error($conn));
    //     $cod = $code . " ".date("Hi");
    //     $result = mysqli_query($conn, "INSERT INTO `tbl_purchase`(`product_code`, `product_name`, `product_category`, `purchase_price`, `sell_price`, `stock`, `min_stock`, `product_unit`, `sdescription`, `gst`, `Ean_code`, `MRP`, `prodd`, `appuser`,`p_discount`,`p_gst_value`,`p_dis_value`) VALUES ('$cod','$productdesc','$category','$purchase','$sell','$stock','$min_stock','$uom','$sproductdesc','$gst','$eancode','$mrp','$pdate','$appuser','$dis_count','$gst_price','$discount_price')") or die(mysqli_error($conn));
    //     if ($result) {
    //         echo '<script type="text/javascript"> alert("Purchase Added Successfully"); </script>';
    //     }
    // }
    if (isset($_POST['product_id'])) {
        $pid_incr = 0;
        $product_ids = $_POST['product_id'];
        foreach ($product_ids as $product_id) {
            $quantity = $quantity_product[$pid_incr];
            $product_pur_price_new = $_POST['pur_price'][$pid_incr];
            $product_sell_price_new = $_POST['sell_price'][$pid_incr];
            if ($quantity != "" &&  $quantity != 0 && $product_pur_price_new != '' && $product_sell_price_new != '') {
                mysqli_query($conn, "UPDATE `tbl_product` SET `stock`='$quantity' where `product_id`='$product_id' ") or die(mysqli_error($conn));
                $purchase_sql = "SELECT `product_id` FROM `tbl_purchase` ORDER BY product_id DESC";
                $purchase_sql_row = mysqli_fetch_assoc(mysqli_query($conn, $purchase_sql));
                $pur_incr_id = 1;
                if (isset($purchase_sql_row['product_id'])) {
                    $pur_incr_id = $purchase_sql_row['product_id'] + 1;
                }
                $product_code_new = $product_code[$pid_incr] . " " . $pur_incr_id;
                $product_sql = "SELECT * FROM `tbl_product` where `product_id`='$product_id'";
                $product_sql_row = mysqli_fetch_assoc(mysqli_query($conn, $product_sql));

                $product_sdescription_new = $_POST['s_product_name'][$pid_incr];
                $product_gst_new = $_POST['gstvalue'][$pid_incr];
                $dis_count = $product_sql_row['p_discount'];
                if ($dis_count != "" && $product_sell_price_new != 0) {
                    $gst_price = ($gst / 100) * $product_sell_price_new;
                    $discount_price = ($dis_count / 100) * $product_sell_price_new;
                    $mrp = $gst_price + $product_sell_price_new + $discount_price;
                } else {
                    $mrp = 0;
                    $gst_price = 0;
                    $discount_price = 0;
                }


                mysqli_query($conn, "INSERT INTO `tbl_purchase` (`product_code`, `product_name`, `product_category`, `purchase_price`, `sell_price`, `stock`, `min_stock`, `product_unit`, `sdescription`, `gst`, `Ean_code`, `MRP`, `prodd`, `appuser`,`p_discount`,`p_gst_value`,`p_dis_value`) VALUES ('$product_code_new','" . $product_sql_row['product_name'] . "','" . $product_sql_row['product_category'] . "','$product_pur_price_new','$product_sell_price_new','$quantity','" . $product_sql_row['min_stock'] . "','" . $product_sql_row['product_unit'] . "','$product_sdescription_new','$product_gst_new','" . $product_sql_row['Ean_code'] . "','" . $product_sql_row['MRP'] . "','$pdate','" . $product_sql_row['product_unit'] . "','$dis_count','$gst_price','$discount_price')") or die(mysqli_error($conn));
            }
            $pid_incr++;
        }
        if ($pid_incr != 0) {
            echo '<script type="text/javascript">
            alert("Purchase Added Successfully");
            window.location.replace("' . $base_url . 'purchase_list.php");
            </script>';
        }
    }
}

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Product Purchase
        </h1>
        <hr>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Enter New Purchase</h3>
            </div>
            <input type="hidden" class="form-control" name="eancode" required id="ean" readonly>
            <input type="hidden" class="form-control" name="category" required id="cat" readonly>
            <input type="hidden" class="form-control" name="gst" required id="gst" readonly>
            <input type="hidden" class="form-control" name="uom" required id="uom" readonly>
            <input type="hidden" class="form-control" name="product_desc" id="desc" required readonly>
            <form action="" method="POST" name="form_product" enctype="multipart/form-data" autocomplete="off" id="f" onkeydown="return event.key != 'Enter';">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Product code</label><br>
                                <span class="text-muted">*Make sure the product code matches</span>
                                <input type="text" class="form-control" name="product_code" autofocus required placeholder="Product Code" id="product_code">
                            </div>
                        </div>
                    </div>
                    <table class="table table-border">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Product Code</th>
                                <th>Short desc</th>
                                <th>Size</th>
                                <th>Quantity</th>
                                <th>Purchase price</th>
                                <th>Selling price</th>
                                <th>Gst%</th>
                            </tr>
                        </thead>
                        <tbody id="mypurchase">
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    <input type="submit" class="btn btn-primary" name="add_pur" value="Save purchase" id="addpur">
                </div>
            </form>
        </div>
    </section>
    <!-- /.content -->
</div>

<script src="foot.js"></script>
<?php
include_once 'inc/footer_all.php';
?>