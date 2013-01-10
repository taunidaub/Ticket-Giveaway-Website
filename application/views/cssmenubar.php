<?php 
$date=date('Y-m-d H:i:s');
?>
<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('base_url_noIndex')?>css/csshorizontalmenu.css" />
<script type="text/javascript" src="<?php echo $this->config->item('base_url_noIndex')?>js/csshorizontalmenu.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item('base_url_noIndex')?>js/main.js"></script>

<div class="horizontalcssmenu">
<ul id="cssmenu1">
<? if($this->session->userdata("LoggedIn")==false){ ?>
<li>Login:<form id="myForm1" action="<?php echo $this->config->item('base_url') ?>main/login"><input type="text"  name="EID" class="required" title="EID"  /> <input type="password" name="Password" class="required" title="Password" /><input name="submit" id="submit" type="submit" value="Log In" onClick="javascript:login()"/></form><div id="myResult1" style="visibility:hidden;"></div></li>
<? }
else{
	echo '<li><a href="javascript:logout()">Logout</a></li>';
	?>
	
	
	<?php if($this->session->userdata("Admin")!=null){ ?> 
	
	<li><a href="javascript:select_winners('0');">Past Giveaways</a>
			<?php 
			$month=date('Y-m-d',mktime(0,0,0,date('m'),date('d'),date('Y'))-2678400);
			$sql="select giveaway_name, id from ".$this->config->item('ma')." where draw_deadline<='".$date."' and event_time>='".$month."'";//".$date."
			$query = $this->db->query($sql);
			if ($query->num_rows() > 0)
				echo "<ul>";
			foreach ($query->result() as $row)
			{
				$sql2="select entry_id from ".$this->config->item('mu')." where entry_giveaway_id ='".$row->id."' and status='confirmed'";
			   	$query2 = $this->db->query($sql2);
				if ($query2->num_rows() >0)
			   		echo '<li><a href="javascript:reselect_winners('.$row->id.')">'.$row->giveaway_name.'  ('.$query2->num_rows().' confirmed)</a></li>';
					
			   	$sql2="select entry_id from ".$this->config->item('mu')." where entry_giveaway_id ='".$row->id."' and status='emailed'";
			   	$query2 = $this->db->query($sql2);
				if ($query2->num_rows() >0)
			   		echo '<li><a href="javascript:reselect_winners('.$row->id.')">'.$row->giveaway_name.' ('.$query2->num_rows().' emailed)</a></li>';
					
				$sql2="select entry_id from ".$this->config->item('mu')." where entry_giveaway_id ='".$row->id."' and status='verified'";
			   	$query2 = $this->db->query($sql2);
				if ($query2->num_rows() >0)
			   		echo '<li><a href="javascript:reselect_winners('.$row->id.')">'.$row->giveaway_name.' ('.$query2->num_rows().' verified)</a></li>';
			}
			if ($query->num_rows() >0)
				echo "</ul>";			
		 ?>
	</li>
	<li><a href="javascript:reselect_winners('0');">Re-select winners</a>
			<?php 
			$sql="select giveaway_name, draw_deadline, id from ".$this->config->item('ma')." where draw_deadline <='".$date."' and event_time>='".$date."'";//".$date."
			$query = $this->db->query($sql);
			if ($query->num_rows() > 0)
				echo "<ul>";
			foreach ($query->result() as $row)
			{
				$sql2="select entry_id from ".$this->config->item('mu')." where entry_giveaway_id ='".$row->id."' and status='confirmed'";
			   	$query2 = $this->db->query($sql2);
				if ($query2->num_rows() >0)
			   		echo '<li><a href="javascript:reselect_winners('.$row->id.')">'.$row->giveaway_name.' ('.$query2->num_rows().' confirmed)</a></li>';
					
			   	$sql2="select entry_id from ".$this->config->item('mu')." where entry_giveaway_id ='".$row->id."' and status='emailed'";
			   	$query2 = $this->db->query($sql2);
				if ($query2->num_rows() >0)
			   		echo '<li><a href="javascript:reselect_winners('.$row->id.')">'.$row->giveaway_name.' ('.$query2->num_rows().' emailed)</a></li>';
					
				$sql2="select entry_id from ".$this->config->item('mu')." where entry_giveaway_id ='".$row->id."' and status='verified'";
			   	$query2 = $this->db->query($sql2);
				if ($query2->num_rows() >0)
			   		echo '<li><a href="javascript:reselect_winners('.$row->id.')">'.$row->giveaway_name.' ('.$query2->num_rows().' verified)</a></li>';

				$sql2="select entry_id from ".$this->config->item('mu')." where entry_giveaway_id ='".$row->id."' and status='selected'";
			   	$query2 = $this->db->query($sql2);
				if ($query2->num_rows() >0)
			   		echo '<li><a href="javascript:reselect_winners('.$row->id.')">'.$row->giveaway_name.' ('.$query2->num_rows().' selected)</a></li>';
						
				
			}
			if ($query->num_rows() >0)
				echo "</ul>";			
		 ?>
	</li>
	
	<li><a href="javascript:select_winners('0');">Select winners</a>
			<?php 
			$sql="select giveaway_name, draw_deadline, id from ".$this->config->item('ma')." where entry_deadline <='".$date."' and event_time>='".$date."'";//".$date."
			$query = $this->db->query($sql);
			if ($query->num_rows() > 0)
				echo "<ul>";
			foreach ($query->result() as $row)
			{
				$total="select * from ".$this->config->item('mu')." where entry_giveaway_id ='".$row->id."'";
				$query3 = $this->db->query($total);
				$total=$query3->num_rows();
				
				$sql2="select entry_id from ".$this->config->item('mu')." where entry_giveaway_id ='".$row->id."' and status='entered'";
			   	$query2 = $this->db->query($sql2);
				if ($query2->num_rows()==$total)
			   		echo '<li><a href="javascript:select_winners('.$row->id.')">'.$row->giveaway_name.' ('.$query2->num_rows().' entered)</a></li>';
			}
			if ($query->num_rows() >0)
				echo "</ul>";			
		 ?>
	</li>
	<li><a href="javascript:edit_giveaway('0')">Edit a giveaway</a>
			<?php 
			$sql="select giveaway_name, id from ".$this->config->item('ma')." where event_time>='".$date."'";//".$date."
			$query = $this->db->query($sql);
			if ($query->num_rows() > 0)
				echo "<ul>";
			foreach ($query->result() as $row)
			{
			   echo '<li><a href="javascript:edit_giveaway('.$row->id.')">'.$row->giveaway_name.'</a></li>';
			}
			if ($query->num_rows() >0)
				echo "</ul>";			
		 ?>
	</li>	
	<li><a href="javascript:add_giveaway();">Add a giveaway</a></li>
	<?php } ?>
	<li><a href="javascript:gohome();" >Current Active Giveaways</a>    
		<?php 
			$sql="select * from ".$this->config->item('ma')." where entry_deadline>='".$date."'";//".$date."
				$query = $this->db->query($sql);
				if ($query->num_rows() > 0)
				echo "<ul>";
					foreach ($query->result() as $row)
					{
						$sql2="select * from ".$this->config->item('mu')." where entry_giveaway_id=".$row->id." and ".$this->config->item('mu').".entry_email = '".$this->session->userdata('Email')."'";
						$query2 = $this->db->query($sql2);
						if ($query2->num_rows() == 0)	
							echo '<li><a href="javascript:enter_giveaway('.$row->id.')">'.$row->giveaway_name.'</a></li>';
						
					}
			if ($query->num_rows()>0)
				echo "</ul>";	
					
		 ?>
	</li>
<? } ?>
</ul>
<br style="clear: left;" />
</div>

<? 
if($this->session->userdata("LoggedIn")==false){ ?>
<script language="javascript" type="text/javascript">

window.addEvent('domready', function(){

  // The elements used.
  var myForm = document.id('myForm1'),
    myResult = document.id('myResult1');

  // Labels over the inputs.
  myForm.getElements('[type=text], textarea,[type=password]').each(function(el){
    new OverText(el);
  });

  // Validation.
  new Form.Validator.Inline(myForm);

  // Ajax (integrates with the validator).
  //console.log('entering Login AJAX');
  new Form.Request(myForm, myResult, {
    requestOptions: {
      'spinnerTarget': myForm
    },
	onSuccess: function(responseText,responseXML){ 
	 //console.log(responseText.get('html'));
		if(responseText.get('html') === '1'){
			window.location.reload(true);
		}
	}
  });

});

	</script>
<?php } ?>