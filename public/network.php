<?php

$router_ip = '';
$username = '';
$password = '';
$port = 23;
$timeout = 10;

$connection = fsockopen($router_ip, $port, $errno, $errstr, $timeout);

if(!$connection){
 echo "Connection failed\n";
 exit();
} else {

 fputs($connection, "$username\r\n");
 fputs($connection, "$password\r\n");
 fputs($connection, "cd setup/dhcp/dhcp-table \r\n");
 fputs($connection, "dir \r\n");
 fputs($connection, " ");

 $j = 0;
 while ($j < 16) {
  fgets($connection);
  $j++;
 }
 stream_set_timeout($connection, 2);
 $timeoutCount = 0;
 $content ='';
 $DhcpArray = '';
 (int) $index =0;

$DhcpFile = "C:\IP-Symcon\webfront\user\images\LancomDhcp.txt";
$fh = fopen($DhcpFile, 'w') or die("can't open file");

//$DhcpArray[0] = array ('IP-Address', 'MAC-Address', 'Timeout', 'Hostname', 'Type', 'LAN-Ifc', 'Ethernet-Port', 'VLAN-ID', 'Network-Name');

 while (!feof($connection)){

  $content = fgets($connection);
  $content = str_replace("\r", '', $content);
  $content = str_replace("\n", "", $content);
  $lineArray = explode(' ', $content);
  if (isValidIp($lineArray [0]))
      {
      $DhcpArray[$index]['IP-Address'] = substr ($content, 0,17);
      $DhcpArray[$index]['MAC-Address'] = substr ($content, 17,32-18);
      $DhcpArray[$index]['Timeout'] = substr ($content, 31,41-32);
      $DhcpArray[$index]['Hostname'] = substr ($content, 40,108-41);
      $DhcpArray[$index]['Type'] = substr ($content, 107,125-108);
      $DhcpArray[$index]['LAN-Ifc'] = substr ($content, 124,137-125);
      $DhcpArray[$index]['Ethernet-Port'] = substr ($content, 136,152-137);
      $DhcpArray[$index]['VLAN-ID'] = substr ($content, 151,161-152);
      $DhcpArray[$index]['Network-Name'] = substr ($content, 160);
      fwrite($fh, $content);
      $index +=1;
      }

  # If the router say "press space for more", send space char:
  if (preg_match('/MORE/', $content) ){ // IF current line contain --More-- expression,
   fputs ($connection, " "); // sending space char for next part of output.
  } # The "more" controlling part complated.

  $info = stream_get_meta_data($connection);
  if ($info['timed_out']) { // If timeout of connection info has got a value, the router not returning a output.
   $timeoutCount++; // We want to count, how many times repeating.
  }
  if ($timeoutCount >2){ // If repeating more than 2 times,
   break;   // the connection terminating..
  }
 }
 $content = substr($content,410);

 BetterTable($DhcpArray);




fclose($fh);

}
echo "End.\r\n";

//--------------------------------------------------------------------

function isValidIp($ip)
{/* PCRE Pattern written by Junaid Atari */
    return !preg_match ( '/^([1-9]\d|1\d{0,2}|2[0-5]{2})\.('.
                         '(0|1?\d{0,2}|2[0-5]{2})\.){2}(0|1?'.
                         '\d{0,2}|2[0-5]{2})(\:\d{2,4})?$/',
                         (string) $ip )
            ? false
            : true;
}

//--------------------------------------------------------------

function BetterTable($twoDimArray)
{
$i = 0;
echo "<table>
        <table class='BetterTable' border='1'>";

echo "<tr>";
echo '<td>Line #
</td>';
foreach ($twoDimArray[0] as  $fieldName => $fieldValue)
    {
        echo '<td>'.$fieldName. '</td>';
    }echo '</tr>';
$i = 0;

foreach ($twoDimArray as $rowName => $rowValue)
{
        if ($i%2 == 0)
            Echo "<tr bgcolor=\"#d0d0d0\" >";
        else
            Echo "<tr bgcolor=\"#eeeeee\">";
    $fields = count($twoDimArray[$i]);
    $y = 0;
    echo '<td>'.$i. '</td>';
    foreach ($rowValue as  $fieldName => $fieldValue)
    {
        echo '<td>'.$fieldValue. '</td>';
        $y = $y + 1;
    }
    echo '</tr>';
    $i = $i + 1;
}


echo '</table>';
}


?>
