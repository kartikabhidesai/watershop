<?php
include("../include/config.inc.php");
include("session.php");

$generalFunction = new generalfunction();
$converter  = new encryption();
$dbfunction = new dbfunctions();


/* Database connection end */
$data_ids = $_REQUEST['data_ids'];
$data_id_array = explode(",", $data_ids); 
if($_REQUEST['action']=='active')
{
	$field = 'captain_active=1';
}
elseif($_REQUEST['action']=='deactive')
{
	$field = 'captain_active=0';
}
else
{
	$field = 'is_deleted=1';
}
	if(!empty($data_id_array)) {
		foreach($data_id_array as $id) {
			$sql = "UPDATE tbl_users SET $field ";
			$sql.=" WHERE user_id = '".$converter->decode($id)."'";
			$query=mysqli_query($dbConn, $sql) or die("captain_action.php: delete employees");
		}
	}
?>