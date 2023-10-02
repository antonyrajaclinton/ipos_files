<?php
include_once 'db/connect_db.php';
//include_once'db/mssql.php';
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

//error_reporting(0);

//$id = $_GET['id'];
$id = isset($_GET['id']) ? $_GET['id'] : '';
//ITEM_MASTEMP
if ($id != "") {
    $mssqldelete = "DELETE FROM tbl_product WHERE product_id = '$id'";
    $mssqlquery = mysqli_query($conn, $mssqldelete);
    //$delete = $pdo->prepare("DELETE FROM tbl_product WHERE product_id=".$id);
    //if($delete->execute())
    if ($mssqlquery) {
        echo '<script type="text/javascript">
            jQuery(function validation(){
            swal("Info", "Product Has Been Deleted", "info", {
            button: "Continue",
                });
            });
            dataTable.ajax.reload();
            </script>';
    }
}
if (isset($_POST['action']) && $_POST['action'] == 'update') {
    $post = $_POST;
    $bill_id = $post['bill_id'];
    $mrp = $post['mrp'];
    $sell = $post['sell'];
    $pur = $post['pur'];
    $ean = $post['ean'];
    $cat = $post['cat'];
    $st = $post['st'];


    if ($bill_id != '') {
        //SELECT `product_id`, `product_code`, `product_name`, `product_category`, `purchase_price`, `sell_price`, `stock`, `min_stock`, `product_unit`, `sdescription`, `gst`, `Ean_code`, `MRP` FROM `tbl_product` WHERE 1  purchase price , selling price , MRP ,Eancode,category
        $uqry = "UPDATE `tbl_product` SET `sell_price`='$sell',`MRP`='$mrp',`purchase_price`='$pur',`stock`='$st',`product_category`='$cat',`Ean_code`='$ean' WHERE `product_id`='$bill_id'";
        $mssqlup = mysqli_query($conn, $uqry);
        if ($mssqlup) {
            echo '<script type="text/javascript">
                jQuery(function validation(){
                swal("Info", "Product Has Been Updated", "info", {
                button: "Continue",
                    });
                });
                dataTable.ajax.reload();
                </script>';
        }
    }
}
?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">List of Products</h3>
                <a href="add_product_foot.php" class="btn btn-success btn-sm pull-right">Add Product</a>
            </div>
            <div class="box-body">
                <div style="overflow-x:auto;">
                    <table class="table table-striped" style="width:100%" id="myProduct">
                        <thead>
                            <tr>
                                <!-- Code, Product name , Eancode, Capital price, selling price, mrp and category -->
                                <!-- <th>No</th> -->
                                <th>Code</th>
                                <th>Product</th>
                                <th>Ean code</th>
                                <th>Capital price</th>
                                <th>Selling price</th>
                                <th>M.R.P</th>
                                <th>Category</th>
                                <th>Stock</th>

                                <th>Option</th>
                            </tr>

                        </thead>
                        <tbody>
                           
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <div id="update" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- BEGIN FORM-->
                <form action="" method="post" class="form-horizontal" id="form_sample_1">
                    <!-- <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Edit</h4>
                </div> -->
                    <div class="modal-body">
                        <div class="form-body">
                            <input type="hidden" class="form-control bill_id" name="bill_id" value="forward">
                            <div class="form-group form-md-line-input">
                                <label class="col-md-4 control-label" for="form_control_1">MRP
                                </label>
                                <div class="col-md-6">
                                    <input type="number" class="form-control mrp" name="mrp">
                                </div>
                            </div>
                            <div class="form-group form-md-line-input">
                                <label class="col-md-4 control-label" for="form_control_1"> Selling Price
                                </label>
                                <div class="col-md-6">
                                    <input type="number" id="sell" class="form-control sell" name="sell">
                                </div>
                            </div>
                            <div class="form-group form-md-line-input">
                                <label class="col-md-4 control-label" for="form_control_1"> Purchase Price
                                </label>
                                <div class="col-md-6">
                                    <input type="number" id="pur" class="form-control pur" name="pur">
                                </div>
                            </div>
                            <div class="form-group form-md-line-input">
                                <label class="col-md-4 control-label" for="form_control_1"> Ean code
                                </label>
                                <div class="col-md-6">
                                    <input type="text" id="ean" class="form-control ean" name="ean">
                                </div>
                            </div>
                            <div class="form-group form-md-line-input">
                                <label class="col-md-4 control-label" for="form_control_1"> Category
                                </label>
                                <div class="col-md-6">
                                    <input type="text" id="cat" class="form-control cat" name="cat">
                                </div>
                            </div>
                            <div class="form-group form-md-line-input">
                                <label class="col-md-4 control-label" for="form_control_1"> Stock
                                </label>
                                <div class="col-md-6">
                                    <input type="number" id="st" class="form-control st" name="st">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-danger">Close</button>
                        <button type="submit" name="action" value="update" class="btn btn-success">Update</button>
                    </div>
                </form>
                <!-- END FORM-->
            </div>
        </div>
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
    $(document).ready(function() {
        //$('#myProduct').DataTable();
        // $('#myProduct').DataTable({
        //     dom: 'lBfrtip',
        //     buttons: [
        //         'excel', 'csv', 'copy', 'print'
        //     ],
        //     "lengthMenu": [
        //         [10, 25, 50, -1],
        //         [10, 25, 50, "All"]
        //     ]
        // });

        $('#myProduct').dataTable({
            "serverSide": true,
            "ajax": {
                'type': 'POST',
                'url': '<?php echo $base_url; ?>datatable_ajax.php',
                'data': {
                    session_role: "<?php echo $_SESSION['role']; ?>",
                    page_status: 'product_list',
                },
            }
        });

        $(document).on('click', '.up', function() {
            $('.bill_id,.mrp,.sell').val("");
            $('.bill_id').val($(this).attr('data-id'));
            $('.mrp').val($(this).attr('data-col1'));
            $('.sell').val($(this).attr('data-col'));
            $('.pur').val($(this).attr('data-pur'));
            $('.ean').val($(this).attr('data-ean'));
            $('.cat').val($(this).attr('data-cat'));
            $('.st').val($(this).attr('data-stock'));

        });
    });
</script>

<?php
include_once 'inc/footer_all.php';
?>