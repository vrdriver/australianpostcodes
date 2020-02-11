<?php
/*

Created by S.Monro 2020

A crude postcode insertion for geoJSON files for Australia. It can probably be modified for other countries though.

To make this script, you will need to download the geoJSON boundaries files.
You will also need to have postcodes loaded in a mysql database.

The, change the reading and writing filename variables to reflect the in and the out files.


Resources required:

This is for inserting postcodes, and to be used in conjunction with 

Boundaries
https://github.com/stephenmuss/suburb-boundaries-geojson

Postcodes
https://github.com/vrdriver/australianpostcodes


* To speed up this process, it is recommended to add indexes to your search fields.
In my case, town and state.

This allowed for passing over 2600 postcodes within the php timeout limit.
	

*/

set_time_limit ( 60 );

$hostname='localhost'; //// specify host, i.e. 'localhost'
$user='root'; //// specify username
$pass='root'; //// specify password
$dbase='database'; //// specify database name	

$dbc = mysqli_connect ($hostname, $user, $pass, $dbase) OR die ('Please contact Your IT Department, not the developer. \n<br>' . mysqli_connect_error() . "\n<br>Server: " . $hostname); 

$reading = fopen('act.json', 'r');
$writing = fopen('act.postcode.json', 'w');
$log = fopen('vic.postcode.json.log', 'w');

$a = 0;
$replaced = false;

while (!feof($reading)) 
{
  $line = fgets($reading);
 
  // Capture the state from the previous line
    if (stristr($line,'"State":')) {
        //$state = $line;
        //echo "Hello. searching state";
       
        if (stristr($line,'Australian Capital Territory'))
        {
            $state = "ACT";
        }                
        if (stristr($line,'Victoria'))
        {
            $state =  "VIC";
        }
        if ( stristr($line,'New South Wales'))
        {
            $state =  "NSW";
        }         
        if ( stristr($line,'Western Australia'))
        {
            $state =  "WA";
        }         
        if ( stristr($line,'South Australia'))
        {
            $state =  "SA";
        }         
        if ( stristr($line,'Tasmania'))
        {
            $state =  "TAS";
        }    
        if ( stristr($line,'Queensland'))
        {
            $state =  "QLD";
        }      
        if ( stristr($line,'Northern Territory'))
        {
            $state =  "NT";
        }       
    }

  if (stristr($line,'"Suburb_Name": "')) 
  {    

    $nameposition = strpos($line,'Name":');
    $lengthofname = strlen('Name": "');
    $townname = trim(substr($line,($nameposition + $lengthofname),(strpos($line,'",')-($nameposition + $lengthofname))));    
    $line = '      "Suburb_Name": "' . trim($townname)  .'",' . "\n";

    // Now, look up postcode and insert it.
    $sql = "SELECT pcode FROM auspostcodes WHERE locality = ? AND state = ?"; // SQL with parameters
    $stmt = $dbc->prepare($sql); 
    $stmt->bind_param("ss", $townname, $state);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result    
    
    $found = 0;
    $postcode = 0000;
    if ($result->num_rows > 0)
    {
        // output data of each row
        while ($row = $result->fetch_assoc()) 
        {         
               $found++;
               $postcode = $row["pcode"];
        }
    }

    if($found >0)
    {
        echo "Inserting Postcode: " . $postcode . " for " . trim($townname) . "<br>\n";
        $line = $line . '      "Postcode": "' . trim($postcode)  .'",' . "\n";
    }
    else
    {
        echo "***********************<br>\n";
        echo "***********************<br>\n";
        echo "*********************** Postcode was not matched for: " . trim($townname) . "<br>\n";
        echo "***********************<br>\n";
        echo "***********************<br>\n";
        fputs($log, "Postcode was not matched for: " . trim($townname) . ", " . $state . "\r\n");
    }
    
    $replaced = true;
    $a++;
  }
  fputs($writing, $line);
}
echo "Towns: " . $a;
?>