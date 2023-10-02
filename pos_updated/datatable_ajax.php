<?php
require_once 'vendor/autoload.php';

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\MySQL;

$config = [
	'host'     => 'localhost',
	'port'     => '3306',
	'username' => 'root',
	'password' => '',
	'database' => 'ipos'
];
$GLOBALS['sess_role'] = $_POST['session_role'];
$GLOBALS['page_status'] = $_POST['page_status'];
$GLOBALS['i'] = 0;
$dt = new Datatables(new MySQL($config));
if ($GLOBALS['page_status'] == "product_list") {
	$dt->query('SELECT `product_code`,`product_name`,`Ean_code`,`purchase_price`,`sell_price`,`MRP`,`product_category`,`stock`,`product_id`  FROM `tbl_product` ORDER BY `product_code` ASC');

	$dt->edit('purchase_price', function ($data) {
		return "INR " . number_format($data['purchase_price'], 2, '.', '');
	});
	$dt->edit('sell_price', function ($data) {
		return "INR " . number_format($data['sell_price'], 2, '.', '');
	});
	$dt->edit('MRP', function ($data) {
		return "INR " . number_format($data['MRP'], 2, '.', '');
	});
	$dt->edit('product_id', function ($data) {
		$button = "";
		if ($GLOBALS['sess_role'] == "Admin") {
			$button = '<a href="product.php?id=' . $data['product_id'] . '"  class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>';
		}
		$button .= '<a data-toggle="modal" href="#update" data-id="' . $data['product_id'] . '" data-col="' . $data['sell_price'] . '" data-col1="' . $data['MRP'] . '" data-pur="' . $data['purchase_price'] . '" data-ean="' . $data['Ean_code'] . '" data-cat="' . $data['product_category'] . '" data-stock="' . $data['stock'] . '" class="btn btn-info btn-sm up"><i class="fa fa-edit"></i></a>';
		return  $button;
	});
}



if ($GLOBALS['page_status'] == "purchase_list") {
	$dt->query('SELECT `min_stock`,`product_code`,`product_name`,`purchase_price`,`sell_price`,`stock`,`prodd`,`product_id` FROM `tbl_purchase` WHERE prodd BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE()');

	$dt->edit('min_stock', function ($data) {
		return $GLOBALS['i']++;
	});
	$dt->edit('product_id', function ($data) {
		$button = "";
		if ($GLOBALS['sess_role'] == "Admin") {
			$button = '<a href="purchase_list.php?id=' . $data['product_id'] . '" onclick="return confirm("Delete Transaction?")" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>';
		}
		$button .= '<a href="purchase_list.php?print=' . $data['product_id'] . '" class="btn btn-info btn-sm"><i class="fa fa-print"></i></a>';
		return  $button;
	});
}

echo $dt->generate();
