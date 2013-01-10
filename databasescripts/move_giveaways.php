<?php //Enter PHP
include("../../phpincludes/LDAP.class.php");	
include("../../phpincludes/db.php");
conn2('tickets');

$sql1="select * from giveaways where id > '450'";
$result1=mysql_query($sql1);
while($row1=mysql_fetch_assoc($result1)){
	$insert1='insert into new_giveaways(`department`,`giveaway_name`,`description`,`location`,`entry_deadline`,`draw_deadline`,`restriction`,`event_title`,`event_time`,`event_description`,`web_link`,`max_tickets`,`active`,`id`) values ("'.$row1[department].'","'.$row1[giveaway_name].'","'.$row1[description].'","'.$row1[location].'","'.$row1[entry_deadline].'","'.$row1[draw_deadline].'","","'.$row1[event_title].'","'.$row1[event_time].'","'.$row1[event_description].'","'.$row1[web_link].'","'.$row1[max_tickets].'","'.$row1[active].'","'.$row1[id].'")';
	//echo $insert1."<br>";
	mysql_query($insert1) or die(mysql_error());
}