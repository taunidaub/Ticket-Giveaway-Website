<?php 
if($this->session->userdata("LoggedIn")==false){
	?>
	<div class="post">
	<h2 class="title" id="title">Welcome to the ticket giveaway website.</h2>
	<div class="entry" id="entry"> 
	This website is used to randomly distribute items and tickets to events offered by various departments. 
	Log in to see the contests that you are eligible to participate in.
	</div>
<?php 
}
if($this->session->userdata("LoggedIn")==true){
$sql="select * from ".$this->config->item('ma')." where entry_deadline>='2012-10-31'";//".$date."
$query = $this->db->query($sql);
if($query->num_rows()>0){
echo '<h2 class="title" id="title">Current Active Giveaways:</h2>
		<div class="entry" id="entry">';

$eligible='';
$registered='';
foreach ($query->result() as $row)
{
	$sql2="select * from ".$this->config->item('mu')." where entry_giveaway_id=".$row->id." and ".$this->config->item('mu').".eid != '".$this->session->userdata('EID')."'";
	$query2 = $this->db->query($sql2);
	if ($query2->num_rows() == 0)	
		$eligible.= '<a href="javascript:enter_giveaway('.$row->id.')">'.$row->giveaway_name.'</a><br>&nbsp;&nbsp;-&nbsp;'.$row->description.'<br>';
	else
		$registered.= '<a href="javascript:enter_giveaway('.$row->id.')">'.$row->giveaway_name.'</a><br>&nbsp;&nbsp;-&nbsp;'.$row->description.'<br>';
}			
if($eligible!=''){
	echo "<h3 class='title'>Contests you can sign up for:</h3>";
	echo $eligible;
	}
if($registered!=''){
	echo "<h3 class='title'>Contests you have already signed up for:</h3>";
	echo $registered;
	}
} 
else
echo "<h3 class='title'>There are currently no active contests for you to enter.</h3>";

?>

<? } ?>
</div>
</div>