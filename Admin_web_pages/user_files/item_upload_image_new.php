<form method="post" action="item_upload_image_new.php" enctype="multipart/form-data">
   <input type="file" name="fileupload" />
   <input type="submit" value="Upload">
</form>
<?php
  
  if ($_SERVER['REQUEST_METHOD'] !== 'POST')
  {
      echo "Empty data, please select file";
      die;
  }

  if (!isset($_FILES["fileupload"]))
  {
      echo "Wrong data struct";
      die;
  }

  if ($_FILES["fileupload"]['error'] != 0)
  {
    echo "Uploaded data error";
    die;
  }
  $target_dir    = "../images_frontpage/";
  $target_file   = $target_dir . basename($_FILES["fileupload"]["name"]);
  $allowUpload   = true;
  $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
  $maxfilesize   = 800000;
  $allowtypes    = array('jpg', 'png', 'jpeg', 'gif');

  if(isset($_POST["submit"])) {
      $check = getimagesize($_FILES["fileupload"]["tmp_name"]);
      if($check !== false)
      {
          //
      }
      else
      {
          echo "This's not image file.";
          $allowUpload = false;
      }
  }

    echo("Target " . $target_file . "<br>");

  if (file_exists($target_file))
  {
      echo "File already exist, don't allow overwrite";
      $allowUpload = false;
  }
  if ($_FILES["fileupload"]["size"] > $maxfilesize)
  {
      echo "Please upload file less than $maxfilesize (bytes).";
      $allowUpload = false;
  }

  if (!in_array($imageFileType,$allowtypes ))
  {
      echo "Only allow file type: JPG, PNG, JPEG, GIF";
      $allowUpload = false;
  }

  if ($allowUpload)
  {
      if (move_uploaded_file($_FILES["fileupload"]["tmp_name"], $target_file))
      {
          echo "File ". basename( $_FILES["fileupload"]["name"]). " uploaded success.";

      }
      else
      {
          echo "Error when upload file.";
      }
  }
  else
  {
      echo "Error upload, may be large filesize, or wrong file type...";
  }
?>
<?php /*

//if (!isset($_SESSION)) 
//{
//  session_start();
//}
//include("../../vbsa_online_scores/header.php"); 

//if they DID upload a file...
if($_FILES['excel_file']['name'])
{
    if(!$_FILES['excel_file']['error'])
    {
        $new_file_name = strtolower($_FILES['excel_file']['tmp_name']); //rename file
        if($_FILES['excel_file']['size'] > (1024000)) //can't be larger than 1 MB
        {
            $valid_file = false;
            echo('Your file\'s size is too large.' . "<br>");
        }
        else
        {
            $valid_file = true;
        }

        if($valid_file)
        {
            echo('<br><br><center>Your file '. $_FILES['excel_file']['name'] . " has been uploaded.</center><br>");
        }
    }
    else
    {
        echo('Your upload triggered the following error:  '. $_FILES['excel_file']['error'] . "<br>");
    }
}
else
{
    echo("<form action='item_upload_image_new.php' method='post' enctype='multipart/form-data'>");
    echo("<center>");
    echo("<table border='0' align='center' cellpadding='0' cellspacing='0'>");
    echo("<tr>");
    echo("<td align=center><h2>Upload Fixtures List</h2></td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>&nbsp;</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>Select the Excel File to upload:</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>&nbsp;</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>&nbsp;</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center><input type='file' name='excel_file' size='25' /></td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>&nbsp;</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>&nbsp;</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>&nbsp;</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center><input type='submit' name='submit' value='Upload' /></td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>&nbsp;</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>&nbsp;</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>&nbsp;</td>");
    echo("</tr>");
    echo("</table>");
    echo("</center>");
    echo("</form>");
}
*/
?>