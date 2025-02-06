<?php
include '../includes/connection.php';
session_start();

  global $db;
//create report
  $report_qr = $db->prepare
  ("select * from transaction order by trans_d_id desc limit 1");
  $report_qr->execute();
  $result = $report_qr->get_result();
  //TRANS_ID`, `CUST_ID`, `NUMOFITEMS`, `SUBTOTAL`, `LESSVAT`, `NETVAT`, `ADDVAT`, `GRANDTOTAL`, `CASH`, `DATE`, `TRANS_D_ID
  $rows = $result->fetch_assoc();
  //$cnt = count($rows);
  $report =  '<div><table>
          <tr>
            <th>Transaction ID</th>
            <th>Customer ID</th>
            <th>Number of items</th>
            <th>Subtotal</th>
            <th>Netvat</th>
            <th>Grand Total</th>
            <th>Date</th>
          </tr>
          <tr>
            <td>' . $rows["TRANS_ID"] . '</td>
            <td>' . $rows['CUST_ID'] . '</td>
            <td>' . $rows['NUMOFITEMS'] . '</td>
            <td>' . $rows['SUBTOTAL'] . '</td>
            <td>' . $rows['NETVAT'] . '</td>
            <td>' . $rows['GRANDTOTAL'] . '</td>
            <td>' . $rows['DATE'] . '</td>
          </tr>  
        </table></div>';


?>
<html>
  <head>
    <title>Report</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
  </head>
  <body>
    <div id="pageprint">
       <div id="reportbox"><?php echo $report; ?></div>
    </div> 
  <button type="button" onclick="downloadCode();">Save report</button>
  </body>
  <script>
  function generatePDF() {
    
    const element = document.getElementById("pageprint");
    document.getElementById("reportbox").style.display = "block";
    document.getElementById("reportbox").style.marginTop = "0px"; 
    document.getElementById("pageprint").style.border = "1px solid black";
    html2pdf().from(element).save('download.pdf'); 
    }
    
    function downloadCode(){
    var x = document.getElementById("reportbox");  
    generatePDF();
    setTimeout(function() { window.location=window.location;},3000);}
    
</script>
</html>