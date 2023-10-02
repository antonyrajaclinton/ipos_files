<?php
include_once'db/connect_db.php';
if(isset($_POST["pcode"]))
{
	$msg="";
	$pcode =$_POST['pcode'];
	//SELECT `product_id`, `product_code`, `product_name`, `product_category`, `purchase_price`, `sell_price`, `stock`, `min_stock`, `product_unit`, `sdescription`, `gst`, `Ean_code`, `MRP` FROM `tbl_product` WHERE 1
	$sql = "SELECT * FROM `tbl_product` WHERE  `Ean_code`='$pcode'  Order BY `product_id` asc";
	// echo $sql;exit;
	$res = mysqli_query($conn, $sql);
	$data=array();
	$html= "";
	if ($res->num_rows > 0)
	{
		$s=1;
		while ($row1 = mysqli_fetch_array($res)) {
			$html.='<tr>
            <td align="right"><label >'.$s.'</label></td>                  
                  <td><input type="hidden" class="form-control product_id" name="product_id[]" value="'.$row1["product_id"].'"><input  class="form-control product_code" name="product_code[]" readonly value="'.$row1["product_code"].'" style="width:100px;"></td>
                  <td><input  class="form-control s_product_name" name="s_product_name[]" ></td>                 
                  <td><input  class="form-control p_size_no" name="p_size_no[]" readonly value="'.$row1["p_size_no"].'" style="width:75px;"></td>                  
                  <td><input type="number" class="form-control quantity_product" name="quantity_product[]" style="width:75px;" value="0" min="0"></td>                      
                  <td><input  type="number" min="1" class="form-control pur_price" name="pur_price[]"></td>
                  <td><input type="number" min="1" class="form-control sell_price" name="sell_price[]"></td>				  
                  <td><input style="width:50px;" type="text" class="form-control gstvalue" name="gstvalue[]" readonly  value="'.$row1["gst"].'"></td> 
                  </tr>';
                  $s++;
		}
		$data['msg']= "Product";		
		$data['html']= $html;		
	}
	else
	{
		$data['msg']= "Product code invalid";
	}
	echo json_encode($data);	    
}
