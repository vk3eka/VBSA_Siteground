<?php

$filter_title = '';
if((isset($_POST['filter_select'])) && ($_POST['filter_select'] == 'Filter'))
{
	//echo("Filter Array " . $_POST['filter_array'] . "<br>");
	if(($_POST['filter_array'] == 'all, ') || ($_POST['filter_array'] == 'all'))
	{
		echo("Query for all members<br>");
		//$query_memb = "Select MemberID, FirstName, LastName, MobilePhone, Email, ReceiveEmail, ReceiveSMS, paid_memb, LifeMember, Gender, Junior, referee, coach_id, ccc_player, affiliate_player, curr_memb, totplayed_curr+totplaybill_curr AS Current, totplayed_curr AS CSnooker, totplaybill_curr AS CBilliards, memb_by, memb_occupation, active_coach FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id WHERE ((paid_memb=20 AND YEAR(paid_date)=YEAR(NOW())) OR LifeMember=1 OR Junior!='na' OR totplayed_curr+totplaybill_curr>0 OR ccc_player=1 OR referee=1 OR active_coach=1 OR Gender != 'Male' OR affiliate_player = 1) AND (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1) AND curr_memb = 0 AND (ReceiveEmail = 1 AND Email != '')"; 
		$filter_title = 'all';
	}
	elseif($_POST['filter_array'] == '')
	{
	//	echo("Query id array is empty<br>");
		//$query_memb = "Select MemberID, FirstName, LastName, MobilePhone, Email, ReceiveEmail, ReceiveSMS, paid_memb, LifeMember, Gender, Junior, referee, coach_id, ccc_player, affiliate_player, curr_memb, totplayed_curr+totplaybill_curr AS Current, totplayed_curr AS CSnooker, totplaybill_curr AS CBilliards, memb_by, memb_occupation, active_coach FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id WHERE MemberID = 1";
	}
	elseif((isset($_POST['filter_array'])) && ($_POST['filter_array'] != ''))
	{
		$filter_elements = explode(", ", $_POST['filter_array']);
		$filterby = '';
		$filter_text = '';
		$x = 0;
		foreach($filter_elements as $elements)
		{
			$filter_element_title = '';
			if($x == 0)
			{
				$and_or = ' AND ';
			}
			else
			{
				$and_or = ' OR ';
			}
			switch ($elements) {
				case 'gender':
					$filterby = $and_or . " Gender != 'Male' ";
					$filter_element_title = 'gender';
					break;
				case 'junior':
					$filterby = $and_or . " Junior != 'na' ";
					$filter_element_title = 'junior';
					break;
				case 'life':
					$filterby = $and_or . " LifeMember = 1 ";
					$filter_element_title = 'life';
					break;
				case 'referee':
					$filterby = $and_or . " referee = 1 ";
					$filter_element_title = 'referee';
					break;
				case 'coach':
					$filterby = $and_or . " active_coach = 1 ";
					$filter_element_title = 'coach';
					break;
				case 'ccc':
					$filterby = $and_or . " ccc_player = 1 ";
					$filter_element_title = 'ccc';
					break;
				case 'paid':
					$filterby = $and_or . " (paid_memb = 20 AND YEAR(paid_date) = YEAR(NOW())) ";
					$filter_element_title = 'paid';
					break;
				case 'affiliate':
					$filterby = $and_or . " affiliate_player = 1 ";
					$filter_element_title = 'affiliate';
					break;
				default:
					$filterby = "";
					$filter_element_title = '';
					break;
			}
			$filter_text = $filter_text . " " . $filterby;
			$filter_title = $filter_title . ", " . $filter_element_title;
			$x++;
		}
		echo("Query for filtered " . $filter_text . " members<br>");
		//$query_memb = "Select MemberID, FirstName, LastName, MobilePhone, Email, ReceiveEmail, ReceiveSMS, paid_memb, LifeMember, Gender, Junior, referee, coach_id, ccc_player, affiliate_player, curr_memb, totplayed_curr+totplaybill_curr AS Current, totplayed_curr AS CSnooker, totplaybill_curr AS CBilliards, memb_by, memb_occupation, active_coach FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id WHERE (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1) AND curr_memb = 0 AND (ReceiveEmail = 1 AND Email != '') " . $filter_text;

		//$query_memb = "Select FirstName, LastName, Email FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id WHERE (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1) AND curr_memb = 0 AND (ReceiveEmail = 1 AND Email != '') " . $filter_text;

	}
	else
	{
		$_POST['filter_array'] = '';
		echo("Query if nothing selected 1<br>");
		//$query_memb = "Select MemberID, FirstName, LastName, MobilePhone, Email, ReceiveEmail, ReceiveSMS, paid_memb, LifeMember, Gender, Junior, referee, coach_id, ccc_player, affiliate_player, curr_memb, totplayed_curr+totplaybill_curr AS Current, totplayed_curr AS CSnooker, totplaybill_curr AS CBilliards, memb_by, memb_occupation, active_coach FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id WHERE MemberID = 1";
	}
}
else
{
	echo("Query if nothing selected 2<br>");
	//$query_memb = "Select MemberID, FirstName, LastName, MobilePhone, Email, ReceiveEmail, ReceiveSMS, paid_memb, LifeMember, Gender, Junior, referee, coach_id, ccc_player, affiliate_player, curr_memb, totplayed_curr+totplaybill_curr AS Current, totplayed_curr AS CSnooker, totplaybill_curr AS CBilliards, memb_by, memb_occupation, active_coach FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id WHERE MemberID = 1";
}

?>