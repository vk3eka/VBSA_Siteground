
<?php
require_once('Models/Fixture.php');

// Run Re gen 

$fixture = new Fixture();

$fixture->LoadFixture();

$jsonData = json_encode($fixture);

echo $jsonData."\n";

?>