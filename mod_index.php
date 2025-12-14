<?php require_once('Connections/connvbsa.php'); 
mysql_select_db($database_connvbsa, $connvbsa);

//$url = 'http://172.16.10.32/VBSA_Siteground';
$url = 'https://vbsa.org.au';

$maxRows_page_items = 10;
$pageNum_page_items = 0;
if (isset($_GET['pageNum_page_items'])) {
  $pageNum_page_items = $_GET['pageNum_page_items'];
}
$startRow_page_items = $pageNum_page_items * $maxRows_page_items;


$query_Cal = "Select * FROM calendar WHERE calendar.visible ='Yes' AND calendar.startdate IS NOT NULL AND calendar.startdate >= curdate() ORDER BY calendar.startdate LIMIT 8";
$Cal = mysql_query($query_Cal, $connvbsa) or die(mysql_error());
$row_Cal = mysql_fetch_assoc($Cal);
$totalRows_Cal = mysql_num_rows($Cal);

$query_BBSA = "SELECT GREATEST( MAX( uploaded_on ) , MAX( edited_on ) ) AS MAXDATE FROM BBSA";
$BBSA = mysql_query($query_BBSA, $connvbsa) or die(mysql_error());
$row_BBSA = mysql_fetch_assoc($BBSA);
$totalRows_BBSA = mysql_num_rows($BBSA);

$query_BendBSA = "SELECT GREATEST( MAX( uploaded_on ) , MAX( edited_on ) ) AS MAXDATE FROM BendBSA";
$BendBSA = mysql_query($query_BendBSA, $connvbsa) or die(mysql_error());
$row_BendBSA = mysql_fetch_assoc($BendBSA);
$totalRows_BendBSA = mysql_num_rows($BendBSA);

$query_CC = "SELECT GREATEST( MAX( uploaded_on ) , MAX( edited_on ) ) AS MAXDATE FROM CC";
$CC = mysql_query($query_CC, $connvbsa) or die(mysql_error());
$row_CC = mysql_fetch_assoc($CC);
$totalRows_CC = mysql_num_rows($CC);

$query_DVSA = "SELECT GREATEST( MAX( uploaded_on ) , MAX( edited_on ) ) AS MAXDATE FROM DVSA";
$DVSA = mysql_query($query_DVSA, $connvbsa) or die(mysql_error());
$row_DVSA = mysql_fetch_assoc($DVSA);
$totalRows_DVSA = mysql_num_rows($DVSA);

$query_MSBA = "SELECT GREATEST( MAX( uploaded_on ) , MAX( edited_on ) ) AS MAXDATE FROM MSBA";
$MSBA = mysql_query($query_MSBA, $connvbsa) or die(mysql_error());
$row_MSBA = mysql_fetch_assoc($MSBA);
$totalRows_MSBA = mysql_num_rows($MSBA);

$query_O55 = "SELECT GREATEST( MAX( uploaded_on ) , MAX( edited_on ) ) AS MAXDATE FROM O55";
$O55 = mysql_query($query_O55, $connvbsa) or die(mysql_error());
$row_O55 = mysql_fetch_assoc($O55);
$totalRows_O55 = mysql_num_rows($O55);

$query_RSL = "SELECT GREATEST( MAX( uploaded_on ) , MAX( edited_on ) ) AS MAXDATE FROM RSL";
$RSL = mysql_query($query_RSL, $connvbsa) or die(mysql_error());
$row_RSL = mysql_fetch_assoc($RSL);
$totalRows_RSL = mysql_num_rows($RSL);

$query_SBSA = "SELECT GREATEST( MAX( uploaded_on ) , MAX( edited_on ) ) AS MAXDATE FROM SBSA";
$SBSA = mysql_query($query_SBSA, $connvbsa) or die(mysql_error());
$row_SBSA = mysql_fetch_assoc($SBSA);
$totalRows_SBSA = mysql_num_rows($SBSA);

$query_WSBSA = "SELECT GREATEST( MAX( uploaded_on ) , MAX( edited_on ) ) AS MAXDATE FROM WSBSA";
$WSBSA = mysql_query($query_WSBSA, $connvbsa) or die(mysql_error());
$row_WSBSA = mysql_fetch_assoc($WSBSA);
$totalRows_WSBSA = mysql_num_rows($WSBSA);

$query_VBSAmax = "SELECT  Updated  AS MAXDATE FROM Team_entries WHERE Updated=(SELECT MAX(Updated) FROM Team_entries)";
$VBSAmax = mysql_query($query_VBSAmax, $connvbsa) or die(mysql_error());
$row_VBSAmax = mysql_fetch_assoc($VBSAmax);
$totalRows_VBSAmax = mysql_num_rows($VBSAmax);

$query_page_items = "SELECT webpage_items.ID, webpage_items.Header, webpage_items.`Comment`, webpage_items.`By`, webpage_items.created_on, webpage_items.blocked, webpage_items.img_orientation, webpage_items.item_image,  webpage_items.event_id,  webpage_items.page_help, webpage_items.OrderFP, webpage_items.OrderRef, webpage_items.OrderWomens, webpage_items.OrderHelp, webpage_items.OrderWomens, webpage_items.img_size FROM webpage_items WHERE webpage_items.blocked='No' AND webpage_items.page_front='Y' ORDER BY OrderFP, created_on DESC";
$query_limit_page_items = sprintf("%s LIMIT %d, %d", $query_page_items, $startRow_page_items, $maxRows_page_items);
$page_items = mysql_query($query_limit_page_items, $connvbsa) or die(mysql_error());
$row_page_items = mysql_fetch_assoc($page_items);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Home Page</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
<body>
  
<div class="new_header" style="width: 100%; background-color: black; margin-bottom: 13px;">
<img src="https://vbsa.org.au/ui_assets/Logo-full-lockup_horizontal_invert.svg" style="margin: auto; display: block; padding: 25px; max-width: 600px; width: calc(100% - 50px);">
</div>    

  <!-- Navigation (if needed) -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="<?= $url ?>/index.php">Home</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">VBSA</a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
              <li><a class="dropdown-item" href="<?= $url ?>/VBSA_scores/scores_index.php">Pennant Scores</a></li>
              <li><a class="dropdown-item" href="<?= $url ?>/galleries/VBSA_team_photo_index.php">Pennant Grand Finals Photos</a></li>
              <li><a class="dropdown-item" href="<?= $url ?>/Club_dir/club_index.php">Clubs</a></li>
              <li><a class="dropdown-item" href="<?= $url ?>/calendar/cal_index.php">Calendar</a></li>
              <li><a class="dropdown-item" href="<?= $url ?>/PreviousRank/rankings_index.php">Rankings</a></li>
              <li><a class="dropdown-item" href="<?= $url ?>/Tournaments/tourn_index.php">Tournament Entries &amp; Conditions</a></li>
              <li><a class="dropdown-item" href="<?= $url ?>/CityClubs/CC_index.php">City Clubs</a></li>
              <li><a class="dropdown-item" href="<?= $url ?>/VBSA/vbsa_contact.php">Contact Us</a></li>
              <li><a class="dropdown-item" href="<?= $url ?>/VBSA/vbsa_about.php">About</a></li>
              <li><a class="dropdown-item" href="<?= $url ?>/VBSA/vbsa_pol_proc.php">Policies & Procedures</a></li>
              <li><a class="dropdown-item" href="<?= $url ?>/Archives/ArchiveIndex.php">Competition Results</a></li>
              <li><a class="dropdown-item" href="<?= $url ?>/VBSA_Help/VBSA_Help.php">Help</a></li>
              <li><a class="dropdown-item" href="<?= $url ?>/Links.php">Links</a></li>
              <li><a class="dropdown-item" href="<?= $url ?>/vbsa_shop/shop_cart.php">Shop (payments, enter t'ments)</a></li>
          </ul>
            <li class="nav-item"><a class="nav-link" href="<?= $url ?>/Admin_DB_VBSA/membership_application_online.php">Join the VBSA</a></li>

            <li class="nav-item"><a class="nav-link" href="<?= $url ?>/calendar/cal_index.php">Calendar</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= $url ?>/PreviousRank/rankings_index.php">Rankings</a></li>
            <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Affiliates</a>
          <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="<?= $url ?>/BBSA/BBSA_index.php">Ballarat BSA</a></li>
              <li><a class="dropdown-item" href="<?= $url ?>/BendBSA/BendBSA_index.php">Bendigo BSA</a></li>
              <li><a class="dropdown-item" href="<?= $url ?>/DVSA/DVSA_index.php">DVSA</a></li>
              <li><a class="dropdown-item" href="<?= $url ?>/MSBA/MSBA_index.php">MSBA</a></li>
              <li><a class="dropdown-item" href="<?= $url ?>/O55/O55_index.php">Over 55's</a></li>
              <li><a class="dropdown-item" href="<?= $url ?>/RSL/RSL_index.php">RSL</a></li>
              <li><a class="dropdown-item" href="<?= $url ?>/Southern/SBSA_index.php">Southern</a></li>
              <li><a class="dropdown-item" href="<?= $url ?>/WSBSA/WSBSA_index.php">Western Suburbs BSA</a></li>
          </ul>
        </li>
        <li class="nav-item"><a class="nav-link" href="<?= $url ?>/Juniors/Junior_Index.php">Junior</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= $url ?>/Womens/Womens_Index.php">Women</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= $url ?>/VBSA/accredited_coaches.php">Coaching</a></li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Referees</a>
          <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="<?= $url ?>/Referees/referee_index.php">Referee Information</a></li>
              <li><a class="dropdown-item" href="<?= $url ?>/Referees/referee_posers.php">Referee Q & A</a></li>
              <li><a class="dropdown-item" href="<?= $url ?>/Referees/referee_contact.php">Qualified Referees List</a></li>
          </ul>
        </li>
        <li class="nav-item"><a href="http://www.facebook.com/pages/Victorian-Billiards-Snooker-Association-VBSA/170438026331018?ref=tn_tnmn" target="_blank"><img src="http://www.vbsa.org.au/images_2016/facebook.fw.png" class="img-responsive" title="Visit the VBSA facebook page"/></a></li>
        </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Main content -->
  <main class="container my-5">
    <div class="row">
      <div class="col-md-3 mb-3">
        <div class="card shadow-sm">
          <div class="card-body">
            <div class="d-grid gap-2 col-12 mx-auto">
              <div class="d-grid gap-2 col-12 mx-auto"> 
                <a href="<?= $url ?>/vbsa_shop/shop_cart.php" class="btn btn-primary btn-sm btn-responsive center-block" role="button">Make a payment or Enter Tournaments</a>
              </div>
              <div class="d-grid gap-2 col-12 mx-auto"> 
                <a href="<?= $url ?>/Club_dir/club_index.php" class="btn btn-primary btn-sm  btn-responsive center-block" role="button">Find a club</a>
              </div>
              <div class="d-grid gap-2 col-12 mx-auto"> 
                <a href="<?= $url ?>/calendar/cal_index.php" class="btn btn-primary btn-sm btn-responsive center-block" role="button">Calendar of events</a>
              </div>
              <div class="d-grid gap-2 col-12 mx-auto"> 
                <a href="<?= $url ?>/VBSA/vbsa_pol_proc.php" class="btn btn-primary btn-sm  btn-responsive center-block" role="button">Membership & team registration forms</a>
              </div>
              <div class="d-grid gap-2 col-12 mx-auto"> 
                <img src="images_2016/aramith.png" class="img-responsive  center-block" style="margin-bottom:10px"/>
                <img src="images_2016/strachan.png" class="img-responsive  center-block" style="margin-bottom:10px"/>
                <img src="images_2016/mitchell.png" class="img-responsive  center-block" style="margin-bottom:10px"/> 
              </div>
            </div>

            <div class="card-body">
              <h5 class="card-title" style="color: red;"><i>Coming events</i></h5>
                <?php do { ?>
                  <div class="fs-6" style="color: red;"><i><?php echo $row_Cal['event']; ?></i></div>
                  <div class="fs-6">
                    Starts: <?php if ($row_Cal['startdate'] != ''): ?>
                    <?php $newDate = date("Y-m-d", strtotime($row_Cal['startdate'])); echo $newDate; ?>
                    <?php endif; ?>
                  </div>
                  <div class="text-end">
                    <a href="calendar/cal_index_detail.php?event_id=<?php echo $row_Cal['event_id']; ?>" class="btn btn-xs btn-primary btn-responsive" role="button">Read More</a>
                  </div>
                    
                  <div>&nbsp;</div>
                <?php } while ($row_Cal = mysql_fetch_assoc($Cal)); ?>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-8 mb-4">
        <div class="card shadow-sm">
          <div class="card-body">
            <h5 class="card-title">VBSA News</h5>
            <div class="search_container text-end">
              <form id="form1" name="form1" method="get" action="index_srch_res.php">
                <input type="text" name="page_content" id="page_content" placeholder="Search items .." />
              </form> 
            </div> 

            <!--<div class="update_container hidden-xs">
              <div class="update_title">Last Updates: </div>
                <div class="update">BBSA - <?php $newDate = date("M d, g:ia", strtotime($row_BBSA['MAXDATE'])); echo $newDate; ?> ,</div>
                <div class="update">BendBSA - <?php $newDate = date("M d, g:ia", strtotime($row_BendBSA['MAXDATE'])); echo $newDate; ?> ,</div>
                <div class="update">City Clubs - <?php $newDate = date("M d, g:ia", strtotime($row_CC['MAXDATE'])); echo $newDate; ?> ,</div>
                <div class="update">DVSA - <?php $newDate = date("M d, g:ia", strtotime($row_DVSA['MAXDATE'])); echo $newDate; ?></div>
                <div class="update">MSBA - <?php $newDate = date("M d, g:ia", strtotime($row_MSBA['MAXDATE'])); echo $newDate; ?></div>
                <div class="update">Over 55's - <?php $newDate = date("M d, g:ia", strtotime($row_O55['MAXDATE'])); echo $newDate; ?></div>
                <div class="update">RSL - <?php $newDate = date("M d, g:ia", strtotime($row_RSL['MAXDATE'])); echo $newDate; ?></div>
                <div class="update">SBSA - <?php $newDate = date("M d, g:ia", strtotime($row_SBSA['MAXDATE'])); echo $newDate; ?></div>
                <div class="update">VBSA - <?php $newDate = date("M d, g:ia", strtotime($row_VBSAmax['MAXDATE'])); echo $newDate; ?></div>
                <div class="update">WSBSA - <?php $newDate = date("M d, g:ia", strtotime($row_WSBSA['MAXDATE'])); echo $newDate; ?></div>
              </div>  
            </div>-->

        <?php do { ?><!--Open News Item Repeat-->    
          <!--Container for news item-->
          <div class="News_item">
            <div class="News_item_header">
            <div class="card_title"><?php echo $row_page_items['Header']; ?></div>
            </div>
              <div class="News_item_content">
              <?php
              if(empty ($row_page_items['item_image']))
              {
              echo " ";
              }
              elseif(isset ($row_page_items['item_image'])) 
              { 
              ?>
              <img class="img-responsive text-end" src="http://vbsa.org.au/../images_frontpage/<?php echo $row_page_items['item_image']; ?>" width="<?php echo $row_page_items['img_size']; ?>" />
              <?php 
              } 
              ?>
        <?php echo $row_page_items['Comment']; ?>
        </div>       
        <div class="news_by">By: <?php echo $row_page_items['By']; ?>&nbsp;&nbsp;<?php $newDate = date("D jS M Y \- g:iA", strtotime($row_page_items['created_on'])) ; echo $newDate; ?>
        </div>
        
<!--Begin "information" footer - contains links to attachments, email, url --> 
<div class="news_item_footer_links" >     
<?php        
// display "Information" and list the web page attachments (from table webpage_attach) if they exist 

$query_att = "Select up_id, up_desc, up_on, up_pdf_name, up_event, item_id, up_type FROM webpage_attach, webpage_items WHERE item_id=ID AND ID= ".$row_page_items['ID'].""; 
$result_att = mysql_query($query_att) or die(mysql_error());    

    if(mysql_num_rows(mysql_query($query_att)) >0 ) {
    echo '<table class="table" style="max-width:600px">';
    echo '<tr>'.'<td colspan="3" class="italic">'."Information".'</td>'.'</tr>'; 
    {
      while($row_att= mysql_fetch_array($result_att)):
           
      // find file extension (Front Page attachments)
      $path_info_att = pathinfo($row_att['up_pdf_name']); 
       
          if ($row_att['up_type'] == 'Attachment'):
                
          echo '<tr>';
          
          // if extension is a .pdf file display pdf after the attachment title and the pdf viewer link. Else display the attachment title only and a blank td
          if($path_info_att['extension']=="pdf")
          {
          echo '<td nowrap="nowrap" style="width:60%">'.$row_att['up_desc']." (pdf)".'</td>';
          // pdf viewer link
          echo '<td style="width:20%">'."<a href=http://www.vbsa.org.au/../ViewerJS/?zoom=page-width#..//Front_page_upload/".$row_att['up_pdf_name']." title=View >".'<span class="glyphicon glyphicon-eye-open">'."</a>",'</td>';
          }
          else echo '<td nowrap="nowrap" style="width:60%">'.$row_att['up_desc'].'</td>'.'<td style="width:20%">&nbsp;</td>' ;
          // download link
          echo '<td style="width:20%">'."<a href=../Front_page_upload/".$row_att['up_pdf_name']." target=_blank title=Download>".'<span class="glyphicon glyphicon-download">'."</a>",'</td>';
          echo '</tr>';
        endif;
                
        if ($row_att['up_type'] == 'URL'):
                
          echo '<tr>';
          echo '<td nowrap="nowrap" colspan="2">'.$row_att['up_desc'].'</td>';
          echo '<td nowrap="nowrap">'."<a href=".$row_att['up_pdf_name']." target=_blank>".'Visit this page'."</a>".'</td>';
          echo '</tr>';
        endif;
                
        if ($row_att['up_type'] == 'Email'):
                
          echo '<tr>';
          echo '<td colspan="3" nowrap="nowrap">'."<a href=mailto:".$row_att['up_pdf_name']." target=_blank>".$row_att['up_desc']."</a>". " (Email)".'</td>';
          echo '</tr>';
          endif;
      endwhile;
              
    } 
    echo '</table>'; }
    else echo "";

// display "Information" and list the calendar attachments if they exist 

$query_event = "SELECT attach_name, Attachment, type FROM calendar_attach, webpage_items WHERE event_number=event_id AND webpage_items.ID = ".$row_page_items['ID'].""; 
$result_event = mysql_query($query_event) or die(mysql_error());    

    if(mysql_num_rows(mysql_query($query_event)) >0 ) {
    echo '<table class="table" style="max-width:600px">';
    echo '<tr>'.'<td colspan="3" class="italic">'."Information".'</td>'.'</tr>'; 
    
    {
    
      while($row_event = mysql_fetch_array($result_event)):    
       
          if ($row_event['type'] == 'Uploaded Attachment'):
        
          // find file extension (Calendar attachments)
          $path_info_cal = pathinfo($row_event['Attachment']); 
                
          echo '<tr>';
          
          // if extension is a .pdf file display pdf after the attachment title and the pdf viewer link. Else display the attachment title only and a blank td
          if($path_info_cal['extension']=="pdf")
          {
          echo '<td nowrap="nowrap" style="width:60%">'.$row_event['attach_name']." (pdf)".'</td>';
          // pdf viewer link
          echo '<td style="width:20%">'."<a href=http://www.vbsa.org.au//ViewerJS/?zoom=page-width#..//calendar/cal_upload/".$row_event['Attachment']." title=View >".'<span class="glyphicon glyphicon-eye-open">'."</a>",'</td>';
          }
          else echo '<td nowrap="nowrap" style="width:60%">'.$row_event['attach_name'].'</td>'.'<td style="width:20%">&nbsp;</td>' ;
          
          // download link
          echo '<td style="width:20%">'."<a href=../calendar/cal_upload/".$row_event['Attachment']." target=_blank>".'<span class="glyphicon glyphicon-download">'."</a>",'</td>';
          echo '</tr>';
        endif;
                
        if ($row_event['type'] == 'URL'):
                
          echo '<tr>';
          echo '<td nowrap="nowrap" colspan="2">'.$row_event['attach_name'].'</td>';
          echo '<td nowrap="nowrap">'."<a href=".$row_event['Attachment']." >".'  Visit this page'."</a>".'</td>';
          echo '</tr>'; 
        endif;
                
        if ($row_event['type'] == 'Email'):
                
          echo '<tr>';
          echo '<td colspan="3" nowrap="nowrap">'."<a href=mailto:".$row_event['Attachment']." target=_blank>".$row_event['attach_name']."</a>". " (Email)".'</td>';
          echo '</tr>';
          endif;
      endwhile;
              
    } 
    echo '</table>'; }
    else echo "";

 ?>

 </div><!--Close footer links container--> <!--Close footer links container--> 

</div><!--Close news item--> 
<div style="clear:both"><hr style="width: 90%; color: #999; height: 1px; background-color: #999; " /> </div>

<?php } while ($row_page_items = mysql_fetch_assoc($page_items)); ?>

          </div>
        </div>
      </div>
    </div>

    <!-- Add more rows or sections as needed -->
  </main>

  <!-- Footer -->
  <footer class="bg-light text-center py-4">
    &copy; <?php echo date('Y'); ?> VBSA. All rights reserved.
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
