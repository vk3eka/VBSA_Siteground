<?php
define ('MAX_FILE_SIZE', 1000000);

$permitted = array('image/gif', 'image/jpeg', 'image/png', 'image/pjpeg', 'text/plain'); //Set array of permitted filetypes
$error = true; //Define an error boolean variable
$filetype = ""; //Just define it empty.

foreach( $permitted as $key => $value ) //Run through all permitted filetypes
{
  if( $_FILES['file']['type'] == $value ) //If this filetype is actually permitted
    {
        $error = false; //Yay! We can go through
        $filetype = explode("/",$_FILES['file']['type']); //Save the filetype and explode it into an array at /
        $filetype = $filetype[0]; //Take the first part. Image/text etc and stomp it into the filetype variable
    }
}

if  ($_FILES['file']['size'] > 0 && $_FILES['file']['size'] <= MAX_FILE_SIZE) //Check for the size
{
    if( $error == false ) //If the file is permitted
    {
        move_uploaded_file($_FILES["file"]["tmp_name"], "../../images_frontpage/" . $_FILES["file"]["name"]); //Move the file from the temporary position till a new one.
              if( $filetype == "image" ) //If the filetype is image, show it!
              {
                echo '<img src="../../images_frontpage/'.$_FILES["file"]["name"].'">';
              }
              elseif($filetype == "text") //If its text, print it.
              {
                echo nl2br( file_get_contents("../../images_frontpage/".$_FILES["file"]["name"]) );
              }

    }
    else
    {
        echo "Not permitted filetype.";
    }
}
else
{
  echo "File is too large.";
}
?>