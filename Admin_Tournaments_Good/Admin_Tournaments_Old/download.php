<?PHP
    require_once('../Connections/connvbsa.php');
	$id = $_GET['id'];
	$fp = fopen("csv/$id.csv",'w');
	$headers = array("ID","VBSA ID","VBSA Tourn ID","First Name","Surname","Paid","How paid","Confirmed"."Email","MobilePhone","Seed",'Wildcard','Ranked','Junior Group','Entered on');
	fputcsv($fp,$headers);
	
	
	mysql_select_db($database_connvbsa, $connvbsa);
	$query_players_confirmed = sprintf("SELECT members.MemberID, members.LastName, members.FirstName, tourn_entry.tourn_memb_id, tourn_entry.tournament_number, tourn_entry.amount_entry, tourn_entry.entered_by, tourn_entry.how_paid, tourn_entry.entry_confirmed, tourn_entry.seed, tourn_entry.junior_cat, tourn_entry.tourn_date_ent, members.Email, members.MobilePhone FROM tourn_entry, members WHERE tournament_number = %s AND members.MemberID=tourn_memb_id ORDER BY tourn_entry.junior_cat, members.LastName", $id);
$players_confirmed = mysql_query($query_players_confirmed, $connvbsa) or die(mysql_error());

	$i=0;
	$cur_year = date("Y");
   while ($row_players_confirmed = mysql_fetch_assoc($players_confirmed)){
          $array = array();
		  $i++;
		  $array = array($i,$row_players_confirmed['tourn_memb_id'],$id,$row_players_confirmed['FirstName'],$row_players_confirmed['LastName'],$row_players_confirmed['amount_entry'],$row_players_confirmed['how_paid'],$row_players_confirmed['entry_confirmed'],$row_players_confirmed['Email'],$row_players_confirmed['MobilePhone'],$row_players_confirmed['seed'],$row_players_confirmed['Wildcard'],$row_players_confirmed['Ranking'],$row_players_confirmed['junior_cat'],date('m/d/Y',strtotime($row_players_confirmed['tourn_date_ent'])));
		  $this_year = date('Y',strtotime($row_players_confirmed['tourn_date_ent']));
		  if($this_year!=$cur_year)continue;
		  fputcsv($fp,$array);
   }
		  
		  $file = 'csv/'.$id.'.csv';

if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    ob_clean();
    flush();
    readfile($file);
    exit;
}

?>