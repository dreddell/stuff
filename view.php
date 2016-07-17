<?php
include 'header.php';


$types=['pool','server','rack','aws'];

$type=$_GET['type'];
$id=@$_GET['id'];
echo "<div id='viewbox'>";
if(in_array($type,$types)){
    $sqlite = new sqlite($sqlite);
    include "./templates/$type.php";

}else{
    echo "Type not found!";
}

echo "</div>";