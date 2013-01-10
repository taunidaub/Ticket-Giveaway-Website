<!DOCTYPE html>
<html>
<head>
<meta name="keywords" content="" />
<meta name="description" content="" />
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php echo $this->config->item('app_title')?></title>
<link href="<?php echo $this->config->item('base_url_noIndex')?>css/style.css" rel="stylesheet" type="text/css" media="screen" />
<script src="<?php echo $this->config->item('base_url_noIndex')?>js/mootools-core.js"></script>
<script src="<?php echo $this->config->item('base_url_noIndex')?>js/mootools-more.js"></script>
<script src="<?php echo $this->config->item('base_url_noIndex')?>js/csshorizontalmenu.js"></script>
<script src="<?php echo $this->config->item('base_url_noIndex')?>js/main.js"></script>
<script type="text/javascript">
var baseURL = '<?php echo $this->config->item("base_url")?>';
var noIndexURL = '<?php echo $this->config->item("base_url_noIndex")?>';
var user_eid = '<?php echo $this->session->userdata('EID') ?>';

</script>
</head>
<!--end #head -->
<!-- begin #pagecode -->
<?php include ('cssmenubar.php'); ?>
<div id="wrapper">
	<div id="header">
		<div id="logo">
			<h1><?php echo $this->config->item('app_title')?></h1>
		</div>
	</div>
</div>
<div style="clear: both;">&nbsp;</div>
	<!-- begin #page -->
	<div id="page">	
	<!-- begin #content -->
	<div id="content">
		<div id="post" style="height:400px;">
				<?php
				if(@$error!=''){
					echo "<h2 class='error'>".$error."</h2>";
					$error='';
				}
					
				$sql="select * from ".$this->config->item('ma')." where entry_deadline>='".$date."'";//".$date."
				$query = $this->db->query($sql);
				if($query->num_rows()>0){
					echo '<br><h2 class="title" id="title">Current Active Giveaways:</h2>
					<div class="entry" id="entry">';
					
					$eligible='';
					$registered='';
					foreach ($query->result() as $row)
					{
						$sql2="select * from ".$this->config->item('mu')." where entry_giveaway_id=".$row->id." and ".$this->config->item('mu').".entry_email = '".$this->session->userdata('Email')."'";
						//echo $sql2;
						$query2 = $this->db->query($sql2);
						if ($query2->num_rows() == 0)	
							$eligible.= '<a href="javascript:enter_giveaway('.$row->id.')">'.$row->giveaway_name.'</a><br>&nbsp;&nbsp;-&nbsp;'.$row->description.'<br>';
						else
							$registered.= '<a href="javascript:enter_giveaway('.$row->id.')">'.$row->giveaway_name.'</a><br>&nbsp;&nbsp;-&nbsp;'.$row->description.'<br>';
					}			
					if($eligible!=''){
						echo "<br><h3 class='title'>Contests you can sign up for:</h3><br>";
						echo $eligible;
						}
					if($registered!=''){
						echo "<br><h3 class='title'>Contests you have already signed up for:</h3><br>";
						echo $registered;
					}
				
				echo "</div>";
				} 
				else
				echo "<h3 class='title'>There are currently no active contests for you to enter.</h3>";
				?>
		</div>

	</div>
	<!-- end #content -->
	<div style="clear: both;">&nbsp;</div>
<!-- end #page -->
</div>
<!-- begin #footer -->
<div id="footer">
	<p>Copyright (c) <?php echo (date('Y')) ?> <a href="timewarnercable.com">Time Warner Cable</a>. All rights reserved.</p>
</div>
<!-- end #footer -->
</body>
</html>
