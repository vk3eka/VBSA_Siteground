<?php
@session_start();
$doNew = false;
if (!isset($_SESSION["SubmitCount"])) $_SESSION["SubmitCount"] = 0;
if ((time() - $_SESSION["SubmitCount"]) > 3) $doNew = true;
$_SESSION["SubmitCount"] = time();
?><?php
if ($doNew)  {
  $questionType = array();
  $questionType[0] = "BeforeDay";
  $questionType[1] = "AfterDay";
  $questionType[2] = "Highest";
  $questionType[3] = "Lowest";
  $questionType[4] = "Obvious";
  
  $questionArr = array();
  $questionText[0] = "Which day comes after [day]?";
  $questionText[1] = "Which day comes before [day]?";
  $questionText[2] = "Which number is highest [num1] or [num2]?";
  $questionText[3] = "Which number is lowest [num1] or [num2]?";
  $questionText[4] = "Is [substance] [opt1] or [opt2]?";
  
  $days = array();
  $days[0] = "sunday";
  $days[1] = "monday";
  $days[2] = "tuesday";
  $days[3] = "wednesday";
  $days[4] = "thursday";
  $days[5] = "friday";
  $days[6] = "saturday";
  
  $elements = array();
  $elements[] = array(0  => "ice",1 => "hot",2 => "cold",3  => "cold");
  $elements[] = array(0  => "fire",1 => "hot",2 => "cold",3  => "hot");
  $elements[] = array(0  => "snow",1 => "hot",2 => "cold",3  => "cold");
  $elements[] = array(0  => "lava",1 => "hot",2 => "cold",3  => "hot");
  $elements[] = array(0  => "a freezer",1 => "hot",2 => "cold",3  => "cold");
  $elements[] = array(0  => "an oven",1 => "hot",2 => "cold",3  => "hot");
  $elements[] = array(0  => "water",1 => "wet",2 => "dry",3  => "wet");
  
  $questionNum = rand(0,4);
  $question = $questionText[$questionNum];
  $answer = "none";
  
  if ($questionNum == 0)  {
	$randDay = rand(0,6);
	$question = str_replace("[day]",$days[$randDay],$question);
	$randDay += 1;
	if ($randDay == 7) $randDay=0;
	$answer = $days[$randDay];
  }
  if ($questionNum == 1)  {
	$randDay = rand(0,6);
	$question = str_replace("[day]",$days[$randDay],$question);
	$randDay -= 1;
	if ($randDay == -1) $randDay=6;
	$answer = $days[$randDay];
  }
  if ($questionNum == 2)  {
	$num1 = rand(0,99);
	$num2 = rand(0,99);
	while ($num2==$num1)  {
	 $num2 = rand();
	}
	$question= str_replace("[num1]",$num1,$question);
	$question = str_replace("[num2]",$num2,$question);
	$answer = $num1;
	if ($num2 > $num1) $answer = $num2;
  }
  if ($questionNum == 3)  {
   $num1 = rand(0,99);
   $num2 = rand(0,99);
   while ($num2==$num1)  {
	 $num2 = rand();
   }
	$question= str_replace("[num1]",$num1,$question);
	$question = str_replace("[num2]",$num2,$question);
	$answer = $num1;
	if ($num2 < $num1) $answer = $num2;
  }
  if ($questionNum == 4)  {
	$randElem = rand(0,sizeof($elements)-1);
	$selectedElem = $elements[$randElem];
	$question= str_replace("[substance]",$selectedElem[0],$question);
	$question= str_replace("[opt1]",$selectedElem[1],$question);
	$question= str_replace("[opt2]",$selectedElem[2],$question);
	$answer = $selectedElem[3];
  }
  
  $_SESSION["random_question"] = $question;
  $_SESSION["random_answer"] = $answer;
}
echo($_SESSION["random_question"]);
?>