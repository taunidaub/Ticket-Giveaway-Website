<?php //Enter PHP
include("../../phpincludes/LDAP.class.php");	
include("../../phpincludes/db.php");
conn2('tickets');

$sql1="select * from entries where entry_giveaway_id > '450'";
$result1=mysql_query($sql1);
while($row1=mysql_fetch_assoc($result1)){
	$eid=getOthersEID($row1[entry_email]);
	if($row1[status]=="winner")
		$status='confirmed';
	else 
		$status=$row1[status];
		
	$insert1='insert into new_entries
	(`eid`,`entry_name`,`entry_email`,`entry_phone`,`entry_location`,`entry_department`,`entry_tickets_num`,`entry_giveaway_id`,`entry_id`,`status`) 
	values 
	("'.$eid.'","'.$row1[entry_name].'","'.$row1[entry_email].'","'.$row1[entry_phone].'","'.$row1[entry_location].'","'.$row1[entry_department].'","'.$row1[entry_tickets_num].'","'.$row1[entry_giveaway_id].'","'.$row1[entry_id].'","'.$status.'")';
	//echo $insert1."<br>";
	mysql_query($insert1) or die(mysql_error());
}