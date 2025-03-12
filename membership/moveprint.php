<?php
require_once '../scripts/connection.php';
$ii = $_POST['c_id'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title><?php echo $ii;?> Summary Print</title></title>
 
	 <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>

    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
	

   <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>



footer {
  font-size: 14px;
  color: black;
  text-align: center;
}

@page {
  size: A4;
  margin: 11mm 17mm 17mm 17mm;
 
}

@media print {
    

  footer {
    position: fixed;
    bottom: 0;
    display: flex;
align-items: center;
justify-content: center;

  }

  .content-block, p {
    page-break-inside: avoid;
  }

  html, body {
    width: 210mm;
    height: 297mm;
  }
}

@media print {
@page {
           margin-top: 0;
           margin-bottom: 0;
         }
         body  {
           padding-top: 72px;
           padding-bottom: 72px ;
         }

}

#bottom {
   
 display: flex;
align-items: center;
justify-content: center;
}


@media print
{
    html
    {
		font-size: 20px;
		font-family: Arial;
       
    }
    table, tr, th, td {
        border: 1px solid black;
  border-collapse: collapse;
  padding: 10px;
  text-align: left;
  	font-size: 20px;
    }
    td, th {
        
        /*border: 0;  */
        
    }
}

@media print
{
    html
    {
        zoom: 90%;
    }
}

</style>

</head>

<body>
    
<?php
if(count($_POST)>0){
    
$stmt = $conn->prepare("SELECT tblmembers.*, mbr_deceased.FundNames 
                        FROM tblmembers 
                        LEFT JOIN mbr_deceased ON tblmembers.MemberID = mbr_deceased.MemberID 
                        WHERE tblmembers.MemberID = '$ii'");
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
?>
<div class="table-responsive">
    <table class="table datatable" id="free">
        <thead>
            <tr>
                <th scope="col" colspan="6"><img src="header.PNG" width="100%"></th>
            </tr>
            <tr style="text-align: center; background: black; color: white;">
                <th scope="col" colspan="6">MEMBER DETAILS</th>
            </tr>
            <tr>
                <th scope="col" style="vertical-align: top;">Full Name</th>
                <td scope="col"><?php echo $row['MemberFirstname']." ".$row['MemberSurname']; ?></td>
                <th scope="col" style="vertical-align: top;">MemberNo</th>
                <td scope="col"><?php echo $row['MemberNo']; ?></td>
                <th scope="col" style="vertical-align: top;">National ID</th>
                <td scope="col"><?php echo $row['MemberIDnumber']; ?></td>
            </tr>
            <tr>
                <th scope="col" style="vertical-align: top;">Date of Birth</th>
                <td scope="col"><?php echo $row['DateOfBirth']; ?></td>
                <th scope="col" style="vertical-align: top;">Account Opened</th>
                <td scope="col"><?php echo $row['DateAccountOpened']; ?></td>
                <th scope="col" style="vertical-align: top;">Fund Name</th>
                <td scope="col"><?php echo $row['FundNames']; ?></td>
            </tr>
            <tr>
                <th scope="col" style="vertical-align: top;">Approved Benefit</th>
                <td scope="col" style="font-weight: bold;"><?php echo "E ". number_format($row['ApprovedBenefit'], 2); ?></td>
                <th scope="col" style="vertical-align: top;">Terminated</th>
                <td scope="col"><?php echo $row['Terminated']; ?></td>
                <th scope="col" style="vertical-align: top;">Balance</th>
                <?php
                $stmt12 = $conn->prepare("SELECT `NewBalance` from `balances` where `memberID` = '$ii' ");
                $stmt12->execute();
                $result12 = $stmt12->get_result();
                if ($result12->num_rows > 0) {
                    while($row12 = $result12->fetch_assoc()) {
                ?>
                <td scope="col" style="font-weight: bold;"><?php echo "E ". number_format($row12['NewBalance'], 2); ?></td>
                <?php
                    }}else{
                ?>
                <td scope="col" style="font-weight: bold;"><?php echo "No data";?></td>
                <?php    
                    }
                ?>        
            </tr>
            <tr style="text-align: center; background: black; color: white;">
                <th scope="col" colspan="6">Account Summary   [<?php echo date('d-M-Y')?>]</th>
            </tr>    
        </thead>
    </table>
</div>
<?php
    }
}

$stmt12 = $conn->prepare("SELECT SUM(`Amount`) AS `TT3` from `tblmemberaccounts` where TransactionTypeID = '1' AND memberID = '$ii' ");
$stmt12->execute();
$result12 = $stmt12->get_result();
if ($result12->num_rows > 0) {
    while($row12 = $result12->fetch_assoc()) {
?>
<div class="table-responsive">
    <table class="table datatable" id="free">
        <thead>
            <tr>
                <th scope="col" style="vertical-align: top;">Initial Transfer In</th>
                <td scope="col" style="text-align: right;"><?php echo "E ". number_format($row12['TT3'], 2); ?></td>
            </tr>
        </thead>
    </table>
</div>
<?php
    }
} else {
    echo "0 results";
}

$stmt12 = $conn->prepare("SELECT SUM(`Amount`) AS `TT3` from `tblmemberaccounts` where TransactionTypeID = '2' AND memberID = '$ii' ");
$stmt12->execute();
$result12 = $stmt12->get_result();
if ($result12->num_rows > 0) {
    while($row12 = $result12->fetch_assoc()) {
?>
<div class="table-responsive">
    <table class="table datatable" id="free">
        <thead>
            <tr>
                <th scope="col" style="vertical-align: top;">Transfer In Fee</th>
                <td scope="col" style="text-align: right;"><?php echo "E ". number_format($row12['TT3'], 2); ?></td>
            </tr>
        </thead>
    </table>
</div>
<?php
    }
} else {
    echo "0 results";
}

$stmt12 = $conn->prepare("SELECT SUM(`Amount`) AS `TT3` from `tblmemberaccounts` where TransactionTypeID IN ('9' ) AND memberID = '$ii' ");
$stmt12->execute();
$result12 = $stmt12->get_result();
if ($result12->num_rows > 0) {
    while($row12 = $result12->fetch_assoc()) {
?>
<div class="table-responsive">
    <table class="table datatable" id="free">
        <thead>
            <tr>
                <th scope="col" style="vertical-align: top;">Additional Capital</th>
                <td scope="col" style="text-align: right;"><?php echo " E ". number_format($row12['TT3'], 2); ?></td>
            </tr>
        </thead>
    </table>
</div>
<?php
    }
} else {
    echo "0 results";
}
?>
<script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>

<?php
} else {
  header('location: ./');
}
?>
<footer>
     <img src="footer.PNG" width="100%">
    </footer>  
<script type="text/javascript">

$(document).ready(function () {
    window.print();
});

</script>	
</body>

</html>


