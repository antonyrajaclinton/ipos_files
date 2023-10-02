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
if (isset($_GET['id'])) {

    $id = $_GET['id'];
    $sqlnew1 = "DELETE FROM tbl_purchase WHERE product_id ='$id'";
    $retvalnew1 = mysqli_query($conn, $sqlnew1) or die(mysqli_error($conn));


    if ($retvalnew2) {
        echo '<script type="text/javascript">
        jQuery(function validation(){
            swal("Info", "Transaction has been deleted", "info", {
                button: "Continue",
                });
                });
                </script>';
    }
}
//0210
if (isset($_GET['print'])) {
    $id = $_GET['print'];
    $sqlfet = "SELECT * FROM tbl_purchase WHERE product_id ='$id'";
    $resultft = mysqli_query($conn, $sqlfet) or die(mysqli_error($conn));
    $rowft = mysqli_fetch_array($resultft);
    $pro = $rowft['product_code'];
    $qty = $rowft['stock'];
    $desc = $rowft['product_name'];
    $pri = $rowft['sell_price'];
    $short = $rowft['sdescription'];
    $c1 = $qty / 3;
    $c = ceil($c1);
    //echo $c;
    $myfile = fopen("barcode.prn", "w") or die("Unable to open file!");
    $txt = 'SIZE 104.2 mm, 21.6 mm
    GAP 3 mm, 0 mm
    DIRECTION 0,0
    REFERENCE 0,0
    OFFSET 0 mm
    SET PEEL OFF
    SET CUTTER OFF
    SET PARTIAL_CUTTER OFF
    SET TEAR ON
    CLS
    DMATRIX 797,120,797,120,x4,r180,12,12,"' . $pro . '"
    CODEPAGE 1252
    TEXT 797,52,"0",180,8,7,"' . $pro . '"
    DMATRIX 486,120,486,120,x4,r180,12,12,"' . $pro . '"
    TEXT 479,52,"0",180,8,7,"' . $pro . '"
    DMATRIX 202,120,202,120,x4,r180,12,12,"' . $pro . '"
    TEXT 203,53,"0",180,8,7,"' . $pro . '"
    TEXT 723,108,"0",180,7,9,"' . $short . '"
    TEXT 405,108,"0",180,7,9,"' . $short . '"
    TEXT 129,108,"0",180,7,9,"' . $short . '"
    PRINT 1,1
    ';

    fwrite($myfile, $txt);
    fclose($myfile);
    for ($i = 1; $i <= $c; $i++) {
        shell_exec('C:\xampp\htdocs\POS\Barcode1.bat');
    }
}

if (isset($_POST['action']) && $_POST['action'] == 'update') {
    $post = $_POST;
    $bill_id = $post['bill_id'];
    $d = $post['d'];
    $date = date("d-m-Y", strtotime($d));

    if ($bill_id != '') {
        $sqlfet = "SELECT * FROM tbl_purchase WHERE product_id ='$bill_id'";
        $resultft = mysqli_query($conn, $sqlfet) or die(mysqli_error($conn));
        $rowft = mysqli_fetch_array($resultft);
        $pro = $rowft['product_code'];
        $qty = $rowft['stock'];
        $desc = $rowft['product_name'];
        $pri = $rowft['sell_price'];
        $c1 = $qty / 4;
        $c = ceil($c1);
        //SELECT `product_id`, `product_code`, `product_name`, `product_category`, `purchase_price`, `sell_price`, `stock`, `min_stock`, `product_unit`, `sdescription`, `gst`, `Ean_code`, `MRP`, `exdays`, `prodd`, `expdate`, `appuser` FROM `tbl_purchase` WHERE 1


        $myfile1 = fopen("barcodepkd.prn", "w") or die("Unable to open file!");

        $txt1 = 'SPEED 3.0
            DENSITY 11
            SET CUTTER OFF
            SET PEEL OFF
            DIRECTION 0
            SIZE 3.920,0.980
            GAP 0.120,0.00
            OFFSET 0.220
            CLS
            TEXT 18,12,"1",0,1,2,"GSR CITY SHOPPIE"
            TEXT 10,37,"1",0,1,2,"' . $desc . '"
            BARCODE 10,65,"128M",59,0,0,2,2,"' . $pro . '"
            TEXT 10,148,"2",0,1,2,"Rs.' . $pri . '"
            TEXT 71,128,"2",0,1,1,"' . $pro . '"
            TEXT 40,180,"1",0,1,1,"Pkd ' . $date . '"
            TEXT 217,12,"1",0,1,2,"GSR CITY SHOPPIE"
            TEXT 209,37,"1",0,1,2,"' . $desc . '"
            BARCODE 210,65,"128M",59,0,0,2,2,"' . $pro . '"
            TEXT 210,148,"2",0,1,2,"Rs.' . $pri . '"
            TEXT 271,128,"2",0,1,1,"' . $pro . '"
            TEXT 240,185,"1",0,1,1,"Pkd ' . $date . '"
            TEXT 416,12,"1",0,1,2,"GSR CITY SHOPPIE"
            TEXT 409,37,"1",0,1,2,"' . $desc . '"
            BARCODE 410,65,"128M",59,0,0,2,2,"' . $pro . '"
            TEXT 410,148,"2",0,1,2,"Rs.' . $pri . '"
            TEXT 471,128,"2",0,1,1,"' . $pro . '"
            TEXT 440,185,"1",0,1,1,"Pkd ' . $date . '"
            TEXT 615,12,"1",0,1,2,"GSR CITY SHOPPIE"
            TEXT 609,37,"1",0,1,2,"' . $desc . '"
            BARCODE 610,65,"128M",59,0,0,2,2,"' . $pro . '"
            TEXT 610,148,"2",0,1,2,"Rs.' . $pri . '"
            TEXT 671,128,"2",0,1,1,"' . $pro . '"
            TEXT 640,185,"1",0,1,1,"Pkd ' . $date . '"
            PRINT 1, 1
            ';
        fwrite($myfile1, $txt1);
        fclose($myfile1);
        for ($i = 1; $i <= $c; $i++) {
            shell_exec('C:\xampp\htdocs\POS\Barcodepkd.bat');
        }
    }
}


?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->


    <!-- Main content -->
    <section class="content container-fluid">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">List of Transactions (last 30 days)</h3>
                <a href="purchase_foot.php" class="btn btn-success btn-sm pull-right">Add Purcahse</a>
            </div>
            <div class="box-body">
                <div style="overflow-x:auto;" class="responsive">
                    <table class="table table-striped" stye="width:100%" id="myOrder">
                        <thead>
                            <tr>
                                <th style="width:20px;">No</th>
                                <th style="width:100px;">Code</th>
                                <th style="width:100px;">Name</th>
                                <th style="width:50px;">Purchase price</th>
                                <th style="width:50px;">Sell price</th>
                                <th style="width:100px;">Qty</th>
                                <th style="width:50px;">Date</th>
                                <th style="width:50px;">Action</th>
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
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title">Print</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-body" id="data_append">

                            <input type="hidden" class="form-control bill_id" name="bill_id" value="forward">
                            <div class="form-group form-md-line-input">
                                <label class="col-md-4 control-label" for="form_control_1">Pack Date
                                </label>
                                <div class="col-md-6">
                                    <input type="date" class="form-control d" name="d">
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-danger">Close</button>
                        <button type="submit" name="action" value="update" class="btn btn-success">Print</button>
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
        //$('#myOrder').DataTable();
        // $('#myOrder').DataTable({  //old
        //     dom: 'lBfrtip',
        //     buttons: [
        //         'excel', 'csv', 'copy', 'print'
        //     ],
        //     "lengthMenu": [
        //         [10, 25, 50, -1],
        //         [10, 25, 50, "All"]
        //     ]
        // });
        $(document).on('click', '.up', function() {
            // $('.bill_id,.mrp,.sell').val("");
            // $('.bill_id').val($(this).attr('data-id'));
            // $('.mrp').val($(this).attr('data-ean'));
            // $('.sell').val($(this).attr('data-col'));
            var ean = $(this).attr('data-ean');
            $.ajax({
                url: "purchasecheck.php",
                method: 'POST',
                data: {
                    pcode: pcode
                },
                success: function(data) {}
            });

        });


        $('#myOrder').dataTable({
            "serverSide": true,
            "ajax": {
                'type': 'POST',
                'url': '<?php echo $base_url; ?>datatable_ajax.php',
                'data': {
                    session_role: "<?php echo $_SESSION['role']; ?>",
                    page_status: 'purchase_list',
                },
            }
        });

    });
</script>

<?php
include_once 'inc/footer_all.php';
?>