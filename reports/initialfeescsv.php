
<?php
require_once '../scripts/connection.php';
$ii = $_POST['single'];
	 $d1=$_POST['date1'];
	 $d2=$_POST['date2'];


foreach ($ii as $a){
$mntharray[] = $a;
}
$mntharray1 = json_encode($mntharray);
$mntharray2 =  str_replace( array('[',']') , ''  , $mntharray1 );


if (in_array("all", $mntharray)){
 // echo $mntharray2;
  //console("HHHH");
 // $own = "Grouped Members";
   $choose = "`initialfees` WHERE DATE(Transactiodate) BETWEEN '$d1'  AND '$d2'  ORDER BY Transactiodate DESC";  
}else{
  
 $choose = "`initialfees` WHERE `MemberNo` IN ({$mntharray2}) AND DATE(Transactiodate) BETWEEN '$d1'  AND '$d2'  ORDER BY Transactiodate DESC";   
}


if(count($_POST)>0){
$query = $conn->query("SELECT 
MemberNo, 
Name, 
Surname, 
ApprovedBenefit, 
Transactiodate, 
Amount 
FROM ".$choose);

if($query->num_rows > 0){ 
    $delimiter = ","; 
    $filename = $a." Fees Report". date('Y-m-d') . ".csv"; 
     
    // Create a file pointer 
    $f = fopen('php://memory', 'w'); 

    // Set column headers 
    $fields = array('MemberNo', 'Name', 'Surname', 'ApprovedBenefit',  'Transactiondate', 'Amount'); 
    fputcsv($f, $fields, $delimiter); 
     
    // Output each row of the data, format line as csv and write to file pointer 
    while($row = $query->fetch_assoc()){ 

        $lineData = array($row['MemberNo'], $row['Name'], $row['Surname'], $row['ApprovedBenefit'],  $row['Transactiodate'], $row['Amount']); 
        fputcsv($f, $lineData, $delimiter); 
    } 


    // Move back to beginning of file 
    fseek($f, 0); 
     
    // Set headers to download file rather than displayed 
    header('Content-Type: text/csv'); 
    header('Content-Disposition: attachment; filename="' . $filename . '";'); 
     
    //output all remaining data on a file pointer 
    fpassthru($f); 
   
} 
exit; 
	 }
?>


