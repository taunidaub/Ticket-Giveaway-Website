<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		
		//$this->session->sess_destroy();
		//$this->output->enable_profiler(TRUE);
		
		if($this->session->userdata("LoggedIn")==false){
			$this->load->view('login');
		}else{	
			$this->load->view('main_page');	
		}
	}
	
	public function login()
	{
		if($_REQUEST['EID'] == '' || $_REQUEST['Password'] == ''){ echo '0'; }
		if(LDAP_authUser($_REQUEST['EID'],$_REQUEST['Password']) != 0){
			$this->session->set_userdata('LoggedIn',true);		
			$this->session->set_userdata('EID',$_REQUEST['EID']);
			$this->session->set_userdata('Password',$_REQUEST['Password']);
			$this->session->set_userdata('Location',getTown($_REQUEST['EID'],$_REQUEST['Password']));
			$this->session->set_userdata('Email',getEmail($_REQUEST['EID'],$_REQUEST['Password']));
			$this->session->set_userdata('Phone',getPhone($_REQUEST['EID'],$_REQUEST['Password']));
			$this->session->set_userdata('Department',getDepartment($_REQUEST['EID'],$_REQUEST['Password']));
			$this->session->set_userdata('Name',getRealName($_REQUEST['EID'],$_REQUEST['Password']));
			
			$sql= 'select * from users where eid = "'.$_REQUEST['EID'].'"';
			$query = $this->db->query($sql);
			if ($query->num_rows() == 1)
				$this->session->set_userdata('Admin',$query->row()->email );
				
			echo "1";
			
		}else{
			$this->session->set_userdata('LoggedIn',false);
			echo "0";
		}

	}
	
	public function logout()
	{
		
		$this->session->set_userdata('LoggedIn',false);
		$this->session->unset_userdata('Admin');
		$this->session->unset_userdata('EID');
		$this->session->unset_userdata('Password');
		
		header("location:http://tickets.alb.domain.com");

	}

	public function giveaway($g)
	{
		$date=date('Y-m-d H:i:s');
		$sql= "select * from ".$this->config->item('ma')." where entry_deadline>='".$date."' and id='".$g."'";//
		$query = $this->db->query($sql);
		if ($query->num_rows() == 1){
			$data['gid']=$g;
			$data['table']=$this->config->item('mu');
			$this->load->view('entry_form',$data);	
		}
		else {
			$data['error']="The giveaway you are looking for has ended.";
			$this->load->view('main_page', $data);
		}	
		
	}
	public function admin_giveaway($g)
	{
		if($g==0){
			$data['table']=$this->config->item('ma');
			$data['gid']='';
			$this->load->view('giveaway_form',$data);	
		}
		else{
			$date=date('Y-m-d H:i:s');
			$sql= "select * from ".$this->config->item('ma')." where id='".$g."'";//".$date."
			$query = $this->db->query($sql);
			if ($query->num_rows() == 1){
				$data['gid']=$g;
				$data['table']=$this->config->item('ma');
				$this->load->view('giveaway_form',$data);	
			}
		}	
	}

	public function select_winners($g)
	{
		if($g==0){
			$data['table']=$this->config->item('ma');
			$this->load->view('select_winners',$data);	
		}
		else{
			$sql1="select * from ".$this->config->item('mu')." where entry_giveaway_id='".$g."' and status = 'entered'";//".$date."
			$query1 = $this->db->query($sql1);
			if ($query1->num_rows() >= 1){
				$data['gid']=$g;
				$data['table']=$this->config->item('mu');
				$this->load->view('select_winners',$data);
			}	
		}	
	}
	
	public function verify_winners($g)
	{
		if($g==0){
			$data['table']=$this->config->item('ma');
			$this->load->view('verify_winners',$data);	
		}
		else{
			$sql2="select * from ".$this->config->item('mu')." where entry_giveaway_id='".$g."' and status = 'selected'";//".$date."
			$query2 = $this->db->query($sql2);
			if ($query2->num_rows() >= 1){
				$data['gid']=$g;
				$data['table']=$this->config->item('mu');
				$this->load->view('verify_winners',$data);
			}
			else{
				$sql3="select * from ".$this->config->item('mu')." where entry_giveaway_id='".$g."' and status = 'verified'";//".$date."
				$query3 = $this->db->query($sql3);
				if ($query3->num_rows() >= 1){
					$data['gid']=$g;
					$data['table']=$this->config->item('mu');
					$this->load->view('email_winners',$data);
				}
				else{
					$sql4="select * from ".$this->config->item('mu')." where entry_giveaway_id='".$g."' and status = 'emailed'";//".$date."
					$query4 = $this->db->query($sql4);
					if ($query4->num_rows() >= 1){
						$data['gid']=$g;
						$data['table']=$this->config->item('mu');
						$this->load->view('confirm_winners',$data);
					}
						else{
						$sql4="select * from ".$this->config->item('mu')." where entry_giveaway_id='".$g."' and status = 'confirmed'";//".$date."
						$query4 = $this->db->query($sql4);
						if ($query4->num_rows() >= 1){
							$data['gid']=0;
							$data['table']=$this->config->item('ma');
							$this->load->view('view_winners',$data);
						}
						else{		
							$data['gid']=$g;
							$data['table']=$this->config->item('mu');
							$this->load->view('select_winners',$data);
						}
					}
				}
			}
		}
	}

	public function eidlookupbyemail($email)
	{
		$ds = LDAPConnect('LdapUser',$Password);
		$Perms = 3;
		$dn = 'OU=Company Divisions,DC=corp,DC=domain,DC=com';					// Set the base DN
		$justthese = array("cn", "displayname","department","division");	

		$lookfor='(mail='.$email.'*)';
		$filter='(&(objectCategory=*)(objectClass=*) '.$lookfor.' (cn=E*))';			// Create the filter (more Detal = faster)
		$sr=ldap_search($ds, $dn, $filter,$justthese);
		$info = @ldap_get_entries($ds, $sr);
			for($x=0;$x < count($info)-1;$x++){
				$arr[$x]['eid']=$info[$x]['cn'][0];
				$arr[$x]['name']=$info[$x]['displayname'][0];
				$arr[$x]['department']=$info[$x]['department'][0];
				$arr[$x]['division']=$info[$x]['division'][0];
			}
			echo json_encode($arr);
		
	}
	
  //*************************************\\
 //     	EDIT FUNCTION		  \\
//									       \\	
	public function edit($table,$id)
	{
		$date=date('Y-m-d');
		if($table== $this->config->item('mu')){
			$data= array('entry_name'=>$_REQUEST['entry_name'],'entry_email'=>$_REQUEST['entry_email'], 'entry_phone'=>$_REQUEST['entry_phone'], 'entry_location'=>$_REQUEST['entry_location'], 'entry_department'=>$_REQUEST['entry_department'], 'entry_tickets_num'=>$_REQUEST['entry_tickets_num'], 'entry_giveaway_id'=>$_REQUEST['entry_giveaway_id'], 'EID'=>$this->session->userdata('EID'), 'status'=>$_REQUEST['status']);
		$this->db->where('entry_id', $id);
		}
		if($table== $this->config->item('ma')){
			$et_temp=explode(" ",$_REQUEST['event_time']);
			if(@$et_temp[2]=="PM"){
				$et_time=explode(":",$et_temp[1]);
				if($et_time[0]<12)
					$et_time[0]=$et_time[0]+12;
			
				$final_et=$et_temp[0]." ".$et_time[0].":".$et_time[1].":".$et_time[2];
			}
			else 
				$final_et=$et_temp[0]." ".$et_temp[1];
			
			$tmp_et=explode("-",$et_temp[0]);
			$nice_et=$tmp_et[1]."/".$tmp_et[2]."/".$tmp_et[0]." at " .$et_temp[1]." ".$et_temp[2];
			
			
				
			$ed_temp=explode(" ",$_REQUEST['entry_deadline']);
			if(@$ed_temp[2]=="PM"){
				$ed_time=explode(":",$ed_temp[1]);
				if($ed_time[0]<12)
					$ed_time[0]=$ed_time[0]+12;
					
				$final_ed=$ed_temp[0]." ".$ed_time[0].":".$ed_time[1].":".$ed_time[2];
			}
			else 
				$final_ed=$ed_temp[0]." ".$ed_temp[1];
			
			$tmp_ed=explode("-",$ed_temp[0]);
			$nice_ed=$tmp_ed[1]."/".$tmp_ed[2]."/".$tmp_ed[0]." at " .$ed_temp[1]." ".$ed_temp[2];
			
			$dd_temp=explode(" ",$_REQUEST['draw_deadline']);
			if(@$dd_temp[2]=="PM"){
				$dd_time=explode(":",$dd_temp[1]);
				if($dd_time[0]<12)
					$dd_time[0]=$dd_time[0]+12;
				
				$final_dd=$dd_temp[0]." ".$dd_time[0].":".$dd_time[1].":".$dd_time[2];
			}
			else 
				$final_dd=$dd_temp[0]." ".$dd_temp[1];
				
			$tmp_dd=explode("-",$dd_temp[0]);
			$nice_dd=$tmp_dd[1]."/".$tmp_dd[2]."/".$tmp_dd[0]." at " .$dd_temp[1]." ".$dd_temp[2];
			
			$data=array('department'=>$_REQUEST['department'],'giveaway_name'=>$_REQUEST['giveaway_name'], 'description'=>$_REQUEST['description'], 'location'=>$_REQUEST['location'], 'entry_deadline'=>$final_ed, 'draw_deadline'=>$final_dd, 'restriction'=>$_REQUEST['restriction'], 'event_title'=>$_REQUEST['giveaway_name'], 'event_time'=>$final_et,'event_description'=>$_REQUEST['description'],'web_link'=>$_REQUEST['web_link'],'max_tickets'=>$_REQUEST['max_tickets'],'active'=>'1');
		
			$sql2='select id from '.$this->config->item('ma').' where giveaway_name= "'.$_REQUEST['giveaway_name'].'" and event_time="'.$final_et.'" and event_description="'.$_REQUEST['description'].'"';
			$query2 = $this->db->query($sql2);
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: ticket_giveaways@domain.com[do_not_reply@domain.com]' . "\r\n" .
						'Reply-To: do_not_reply@domain.com[do_not_reply@domain.com]' . "\r\n" .
						'X-Mailer: PHP/' . phpversion();
		
			$subject = $_REQUEST['giveaway_name']. ' Edit';
			//echo $email[0];
			
			$to=$this->session->userdata("Email");
			foreach ($query2->result() as $row2)
			{	
				$body='
				<p>The '.$_REQUEST['department'].' department has a limited number of tickets to give away for '.$_REQUEST['giveaway_name'].' on '.$nice_et.' at '.$_REQUEST['location'].'. If you are interested in tickets, please complete the form  <a href="http://tickets.alb.domain.com/index.php/main/giveaway/'.$row2->id.'">here</a> by '.$nice_ed.' The drawing will take place after '.$nice_dd.'	</p>
				<p>If you receive text only email or the link is not working, please copy and paste this link into the address bar of your web browser. http://tickets.alb.domain.com/index.php/main/giveaway/'.$row2->id.'</p>
				<p>For more information on this event you can visit <a href="'.$_REQUEST['web_link'].'">'.$_REQUEST['web_link'].'</a>.</p>
				';
			}
			mail($to, $subject, $body, $headers);

		$this->db->where('id', $id);
		}
		
		$this->db->update($table, $data); 
		echo $this->db->affected_rows();
	
	}
	
	public function insert_sql($table)
	{
		$date=date('Y-m-d');
		if($table== $this->config->item('mu')){
			$data= array('entry_name'=>$_REQUEST['entry_name'],'entry_email'=>$_REQUEST['entry_email'], 'entry_phone'=>$_REQUEST['entry_phone'], 'entry_location'=>$_REQUEST['entry_location'], 'entry_department'=>$_REQUEST['entry_department'], 'entry_tickets_num'=>$_REQUEST['entry_tickets_num'], 'entry_giveaway_id'=>$_REQUEST['entry_giveaway_id'], 'EID'=>$this->session->userdata('EID'), 'status'=>"entered");

		}
		if($table== $this->config->item('ma')){
			$data=array('department'=>$_REQUEST['department'],'giveaway_name'=>$_REQUEST['giveaway_name'], 'description'=>$_REQUEST['description'], 'location'=>$_REQUEST['location'], 'entry_deadline'=>$_REQUEST['entry_deadline'], 'draw_deadline'=>$_REQUEST['draw_deadline'], 'restriction'=>$_REQUEST['restriction'], 'event_title'=>$_REQUEST['giveaway_name'], 'event_time'=>$_REQUEST['event_time'],'event_description'=>$_REQUEST['description'],'web_link'=>$_REQUEST['web_link'],'max_tickets'=>$_REQUEST['max_tickets'],'active'=>'1');
		
		}
		
		$str= $this->db->insert_string($table, $data); 
		$query = $this->db->query($str);
		echo $this->db->affected_rows();
	}
	
	public function insert_mailer($table)
	{
		$date=date('Y-m-d');
		if($table== $this->config->item('mu')){
			$data= array('entry_name'=>$_REQUEST['entry_name'],'entry_email'=>$_REQUEST['entry_email'], 'entry_phone'=>$_REQUEST['entry_phone'], 'entry_location'=>$_REQUEST['entry_location'], 'entry_department'=>$_REQUEST['entry_department'], 'entry_tickets_num'=>$_REQUEST['entry_tickets_num'], 'entry_giveaway_id'=>$_REQUEST['entry_giveaway_id'], 'EID'=>$this->session->userdata('EID'), 'status'=>"entered");
			$str= $this->db->insert_string($table, $data); 
			$query = @$this->db->query($str);
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: ticket_giveaways@domain.com[do_not_reply@domain.com]' . "\r\n" .
						'Reply-To: do_not_reply@domain.com[do_not_reply@domain.com]' . "\r\n" .
						'X-Mailer: PHP/' . phpversion();
			$sql2="select * from ". $this->config->item('ma')." where id='".$_REQUEST['entry_giveaway_id']."'";
			$query2 = $this->db->query($sql2);
			foreach ($query2->result() as $row2){
				$subject = $row2->giveaway_name.' Entry Confirmation';
				//echo $email[0];
				$temp=explode(" ", $row2->draw_deadline);
				$temp1=explode("-",$temp[0]);
				$temp2=explode(":",$temp[1]);
				if($temp2[0]>12){
					$time="PM";
					$temp2[0]=$temp2[0]-12;
				}
				else if($temp2[0]==12)
					$time="PM";
				else
					$time="AM";
				$nicedrawingdate=$temp1[1]."/".$temp1[2]."/".$temp1[0]." at " .$temp2[0].":".$temp2[1]." ".$time;
				$to=$_REQUEST['entry_email'];//
				
				$body='
				<html>
				<head>
					<title>Entry Confirmation</title>
				</head>
				<body>
				<p>'.$_REQUEST['entry_name'].'.</p>
				<p>
				This is confirmation for your entry into the '.$row2->event_title.' giveaway.</p>The drawing will take place after '.$nicedrawingdate.'
				<p>For more information on this event, you can visit <a href="'.$row2->web_link.'">'.$row2->web_link.'</a>.</p>
				';
				$body .='		
				</body>
				</html>';
			}
			
			mail($to, $subject, $body, $headers);

		}
		if($table== $this->config->item('ma')){
		$et_temp=explode(" ",$_REQUEST['event_time']);
			if($et_temp[2]=="PM"){
				$et_time=explode(":",$et_temp[1]);
				if($et_time[0]<12)
					$et_time[0]=$et_time[0]+12;
			
				$final_et=$et_temp[0]." ".$et_time[0].":".$et_time[1].":".$et_time[2];
			}
			else 
				$final_et=$et_temp[0]." ".$et_temp[1];
			
			$tmp_et=explode("-",$et_temp[0]);
			$nice_et=$tmp_et[1]."/".$tmp_et[2]."/".$tmp_et[0]." at " .$et_temp[1]." ".$et_temp[2];
			
			
				
			$ed_temp=explode(" ",$_REQUEST['entry_deadline']);
			if($ed_temp[2]=="PM"){
				$ed_time=explode(":",$ed_temp[1]);
				if($ed_time[0]<12)
					$ed_time[0]=$ed_time[0]+12;
					
				$final_ed=$ed_temp[0]." ".$ed_time[0].":".$ed_time[1].":".$ed_time[2];
			}
			else 
				$final_ed=$ed_temp[0]." ".$ed_temp[1];
			
			$tmp_ed=explode("-",$ed_temp[0]);
			$nice_ed=$tmp_ed[1]."/".$tmp_ed[2]."/".$tmp_ed[0]." at " .$ed_temp[1]." ".$ed_temp[2];
			
			$dd_temp=explode(" ",$_REQUEST['draw_deadline']);
			if($dd_temp[2]=="PM"){
				$dd_time=explode(":",$dd_temp[1]);
				if($dd_time[0]<12)
					$dd_time[0]=$dd_time[0]+12;
				
				$final_dd=$dd_temp[0]." ".$dd_time[0].":".$dd_time[1].":".$dd_time[2];
			}
			else 
				$final_dd=$dd_temp[0]." ".$dd_temp[1];
				
			$tmp_dd=explode("-",$dd_temp[0]);
			$nice_dd=$tmp_dd[1]."/".$tmp_dd[2]."/".$tmp_dd[0]." at " .$dd_temp[1]." ".$dd_temp[2];
			
			$data=array('department'=>$_REQUEST['department'],'giveaway_name'=>$_REQUEST['giveaway_name'], 'description'=>$_REQUEST['description'], 'location'=>$_REQUEST['location'], 'entry_deadline'=>$final_ed, 'draw_deadline'=>$final_dd, 'restriction'=>$_REQUEST['restriction'], 'event_title'=>$_REQUEST['giveaway_name'], 'event_time'=>$final_et,'event_description'=>$_REQUEST['description'],'web_link'=>$_REQUEST['web_link'],'max_tickets'=>$_REQUEST['max_tickets'],'active'=>'1');
		
			$str= $this->db->insert_string($table, $data); 
			$query = $this->db->query($str);
			$sql2='select id from '.$this->config->item('ma').' where giveaway_name= "'.$_REQUEST['giveaway_name'].'" and event_time="'.$final_et.'" and event_description="'.$_REQUEST['description'].'"';
			$query2 = $this->db->query($sql2);
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: ticket_giveaways@domain.com[do_not_reply@domain.com]' . "\r\n" .
						'Reply-To: do_not_reply@domain.com[do_not_reply@domain.com]' . "\r\n" .
						'X-Mailer: PHP/' . phpversion();
		
			$subject = 'Ticket Giveaway Creation';
			//echo $email[0];
			
			$to=$this->session->userdata("Email");
			foreach ($query2->result() as $row2)
			{	
				$body='
				<p>The '.$_REQUEST['department'].' department has a limited number of tickets to give away for '.$_REQUEST['giveaway_name'].' on '.$nice_et.' at '.$_REQUEST['location'].'. If you are interested in tickets, please complete the form  <a href="http://tickets.alb.domain.com/index.php/main/giveaway/'.$row2->id.'">here</a> by '.$nice_ed.' The drawing will take place after '.$nice_dd.'	</p>
				<p>If you receive text only email or the link is not working, please copy and paste this link into the address bar of your web browser. http://tickets.alb.domain.com/index.php/main/giveaway/'.$row2->id.'</p>
				<p>For more information on this event you can visit <a href="'.$_REQUEST['web_link'].'">'.$_REQUEST['web_link'].'</a>.</p>
				';
			}
				mail($to, $subject, $body, $headers);
		
		}
		echo "1";
	}
	
	
	public function send_mail()
	{
		mail($this->session->userdata("To"), $this->session->userdata("Subject"), $this->session->userdata("Body"), $this->session->userdata("Headers"));
		header('location:http://tickets.alb.domain.com/index.php/');
	}
	
	public function get_upload($id)
	{
		$data['ID']=$id;
		$this->load->view('upload_form',$data);	
	}
		
	public function encrypt($text) 
	{ 
		return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, SALT, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)))); 
	} 
	
	public function decrypt($text) 
	{ 
		return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, SALT, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))); 
	} 

	public function upload_document()
	{
		$date_added = date("Y-m-d");
		$this->load->library('ftp');
	}
	
	public function pick_winners($id)
	{	
		$maxw=$_REQUEST['max_winners'];
		for($x=0;$x<$maxw;$x++)
		{
			$table='new_entries';
			$sql1= "SELECT entry_id FROM $table WHERE entry_giveaway_id='$id' and status='entered'";
			//echo $sql1;
			$query1 = $this->db->query($sql1);
			$total=@$query1->num_rows();
			$current=rand(0,$total);
			//echo ($current);
			$winners[$x]= @$query1->row($current);
			$update_id=$winners[$x]->entry_id;
			$data=array('status'=>"selected");
			$this->db->where('entry_id', $update_id);
			$this->db->update($table, $data);		
		}
		
		if (count($winners)==$_REQUEST['max_winners'])
			echo $id;
			
		else
			echo '0';
		
	}
	
	public function send_winner_email($gid)
	{
		$sql="select * from ".$this->config->item('ma')." where id ='".$gid."'";
		$query = $this->db->query($sql);
		foreach ($query->result() as $row){
			$admin_to=$this->session->userdata("Email");
			$admin_body="Here are the most recently emailed winners for ".$row->giveaway_name.":".'<br>';
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: ticket_giveaways@domain.com[do_not_reply@domain.com]' . "\r\n" .
						'Reply-To: do_not_reply@domain.com[do_not_reply@domain.com]' . "\r\n" .
						'X-Mailer: PHP/' . phpversion();
			$admin_subject=$row->giveaway_name.' Winner List ';
			$subject = $row->giveaway_name.' Giveaway ';
			//echo $email[0];
			foreach(@$_REQUEST[email] as $entry_id){
				$sql2="select * from ".$this->config->item('mu')." where entry_giveaway_id ='".$row->id."' and entry_id='".$entry_id."'";
				$query2 = $this->db->query($sql2);
				$r=0;
				
				foreach ($query2->result() as $row2){
					$to=$row2->entry_email;
					$admin_body.=$row2->entry_name." - ".$row2->entry_email.'<br>';
					$body='
					<html>
					<head>
						<title>Ticket Giveaway</title>
					</head>
					<body>
					<p>Congratulations, '.$row2->entry_name.'.</p>
					<p>
					You have been selected to win tickets for '.$row->event_title.' on '.$row->event_time.' at '.$row->location.'.</p>
					<p>For more information on this event, you can visit <a href="'.$row->web_link.'">'.$row->web_link.'</a>.</p>
					';
					if(@$_REQUEST[additional_message]!="Please add any additional comments to the email here.")
					$body .=@$_REQUEST[additional_message];
					$body .='		
					</body>
					</html>';
					
					$data= array('status'=>'emailed');
					$this->db->where('entry_id', $row2->entry_id);
					$this->db->update($this->config->item('mu'), $data); 
					
					mail($to, $subject, $body, $headers);
					
					}//foreach ($query2->result() as $row2){
				}//foreach($_REQUEST[email] as $entry_id){
				$sql2="select * from ".$this->config->item('mu')." where entry_giveaway_id ='".$row->id."' and status='selected'";
				$query2 = $this->db->query($sql2);
				foreach ($query2->result() as $row2){
					$data= array('status'=>'entered');
					$this->db->where('entry_id', $row2->entry_id);
					$this->db->update($this->config->item('mu'), $data); 
				}
				
				mail($admin_to, $admin_subject, $admin_body, $headers);
			}//foreach ($query->result() as $row){	
		
			echo '1';
	}
		
	public function verify_email($gid)
	{
		$sql="select * from ".$this->config->item('ma')." where id ='".$gid."'";
		$query = $this->db->query($sql);
		foreach ($query->result() as $row){
			$admin_to=$this->session->userdata("Email");
			$admin_body="Here are the most recently selected winners for ".$row->giveaway_name.":".'<br>';
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: ticket_giveaways@domain.com' . "\r\n" .
						'Reply-To: do_not_reply@domain.com' . "\r\n" .
						'X-Mailer: PHP/' . phpversion();
			$admin_subject="Please verify the ".$row->giveaway_name.' winner list ';
			
			$sql2="select * from ".$this->config->item('mu')." where entry_giveaway_id ='".$row->id."' and status='selected'";
			$query2 = $this->db->query($sql2);
		
			foreach ($query2->result() as $row2){
				$admin_body.=$row2->entry_name." - Email: ".$row2->entry_email."&nbsp;&nbsp; EID: ".$row2->eid.'<br>';
			}			
			mail($admin_to, $admin_subject, $admin_body, $headers);
			
			$headers2  = 'MIME-Version: 1.0' . "\r\n";
			$headers2 .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers2 .= 'From:'.$this->session->userdata("Email") . "\r\n" .
						'Reply-To:'.$this->session->userdata("Email") . "\r\n" .
						'X-Mailer: PHP/' . phpversion();
						
			if(@$_REQUEST['add_email']!='')
				mail($_REQUEST['add_email'], $admin_subject, $admin_body, $headers2);
				
			echo '1';
		}//foreach ($query->result() as $row){	
	}
	
	
	public function repick_winners($gid)
	{
		$sql="select * from ".$this->config->item('ma')." where id ='".$gid."'";
		$query = $this->db->query($sql);
		$count=0;
		$winners=array();
		foreach ($query->result() as $row){
			foreach(@$_REQUEST['verify'] as $entry_id){
				if ($entry_id!=''){
					$temp=explode(":",$entry_id);
					$id=$temp[1];
					$status=$temp[0];
					$data= array('status'=>$status);
					$this->db->where('entry_id', $id);
					$this->db->update($this->config->item('mu'), $data); 
					if($status=='declined')
						$count++;
				}
			}	
		}//foreach ($query->result() as $row){	

		while(count(@$winners)<$count)
		{
			$table='new_entries';
			$sql1= "SELECT entry_id FROM $table WHERE entry_giveaway_id='".$gid."' and status='entered'";
			//echo $sql1;
			$query1 = $this->db->query($sql1);
			$total=@$query1->num_rows();
			$current=rand(0,$total);
			//echo ($current);
			$winners[$current]= @$query1->row($current);
			$update_id=$winners[$current]->entry_id;
			$data=array('status'=>"selected");
			$this->db->where('entry_id', $update_id);
			$this->db->update($table, $data);	
				
		}
		
		if (count($winners)==$count)
			echo '1';
	}
	
	public function verify_all($gid)
	{
		$sql="select * from ".$this->config->item('mu')." where entry_giveaway_id ='".$gid."' and status='selected'";
		$query = $this->db->query($sql);
		foreach ($query->result() as $row){
			$data= array('status'=>'verified');
			$this->db->where('entry_id', $row->entry_id);
			$this->db->update($this->config->item('mu'), $data); 	
		}
		echo '1';
	
	}

	public function confirm($gid)
	{
		$sql="select * from ".$this->config->item('ma')." where id ='".$gid."'";
		$query = $this->db->query($sql);
		$count=0;
		$winners=array();
		foreach ($query->result() as $row){
			foreach(@$_REQUEST[confirm] as $entry_id){
				if ($entry_id!=''){
					$temp=explode(":",$entry_id);
					$id=$temp[1];
					$status=$temp[0];
					$data= array('status'=>$status);
					$this->db->where('entry_id', $id);
					$this->db->update($this->config->item('mu'), $data); 
					if($status=='declined')
						$count++;
				}
			}	
		}//foreach ($query->result() as $row){	

		while(count(@$winners)<$count)
		{
			$table=$this->config->item('mu');
			$sql1= "SELECT entry_id FROM $table WHERE entry_giveaway_id='".$gid."' and status='entered'";
			//echo $sql1;
			$query1 = $this->db->query($sql1);
			$total=@$query1->num_rows();
			$current=rand(0,$total);
			//echo ($current);
			$winners[$current]= @$query1->row($current);
			$update_id=$winners[$current]->entry_id;
			$data=array('status'=>"selected");
			$this->db->where('entry_id', $update_id);
			$this->db->update($table, $data);	
				
		}
		
		if (count($winners)==$count)
			echo '1';
	}
}	
/* End of file main.php */
/* Location: ./application/controllers/main.php */