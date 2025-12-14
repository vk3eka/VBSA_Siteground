<?php

require_once('../Connections/connvbsa.php');

$request = 0;
if(isset($_POST['request'])){
   $request = $_POST['request'];
}
// Get username list
if($request == 1){
   $search = "";
   if(isset($_POST['search'])){
      $search = $_POST['search'];
   }

   $query = "SELECT * FROM members WHERE firstname like'%".$search."%'";
   $result = mysqli_query($con,$query);
 
   while($row = mysqli_fetch_array($result) ){
      $response[] = array("value"=>$row['MemberID'],"label"=>$row['firstname']);
   }

   // encoding array to json format
   echo json_encode($response);
   exit;
}

// Get details
if($request == 2){
   
   $userid = 0;
   if(isset($_POST['userid'])){
      $userid = $_POST['userid'];
   }
   $sql = "SELECT * FROM members WHERE MemberID=".$userid;

   $result = mysqli_query($con,$sql); 

   $users_arr = array();

   while( $row = mysqli_fetch_array($result) ){
      $userid = $row['id'];
      $fullname = $row['fname']." ".$row['lname'];
      $email = $row['email'];
      $age = $row['age'];
      $salary = $row['salary'];

      $users_arr[] = array(
          "id" => $userid, 
          "name" => $fullname,
          "email" => $email, 
          "age" =>$age, 
          "salary" =>$salary
      );
   }

   // encoding array to json format
   echo json_encode($users_arr);
   exit;
}