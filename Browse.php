<?php
$host = "localhost";
$username = "s109";
$password = "NCTUiou!";
$dbname = "s109_bioPrg";

$myConn = MySql_Connect($host, $username, $password);
MySql_Select_Db($dbname, $myConn);
$l = $_POST["len"];
$sqlCmd = "SELECT idx FROM `pdbFrags_0717035` WHERE `len`='$l' AND `is_head`='1'"; //SELECT
$qry = @MySql_Query($sqlCmd);
if (!$qry) Exit( MySql_Error() );

$Head_id = Array();
while ( $Row = @MySql_Fetch_Row($qry) )
{
  $Head_id[] = $Row[0];
}

MySql_Close($myConn);

$Head_cou = Count($Head_id);
?>

<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <title>Representative Fragmented Protein Database</title>

    <script type="text/javascript" src="jsmol/JSmol.min.js"></script>
    <script  src="http://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
  </head>
  
  <body bgcolor="#BFB97B">
      <p>&nbsp;</p>
      <table width="1200" border="5" style="border:15px #7E5109 groove;" align="center" cellpadding="20">
        <tr>
          <td>
            <p><font size="4" face="Bahnschrift"><a href="Main.php"><b>Home</b></a></font></p>
            <p><font size="5" face="Bahnschrift">Representative protein structure fragments with length = <?php echo $_POST["len"]?> (residues)</font></p>
            <p><b><font size="5" face="Bahnschrift" color="#808000">Number of fragments: <?php echo $Head_cou;?></font></b></p>
          </td>
        </tr>
        
        <tr>
          <td>
          <input type="checkbox" name="drive" id="drive" onClick="sync()">
          <span class="custom-control-indicator"></span>
          <font size="3" face="Bahnschrift">Synchronize</font>
          <button type="button" style="cursor:pointer;" onClick="javascript:syncAll();">Reset positions</button>
          <input type='button' id='but_screenshot' value='Screenshot' onclick='screenshot();'><br/>

          <br><br>
          <table width="756" border="0" align="center" cellpadding="0" cellspacing="1">
          <tr height="165">   

<?php
$index = 0;

//**********************for*******************************>>
foreach ($Head_id as $frag_num)
{
  $index = $index + 1;
?>


            <td width="200" bgcolor="#3E2805"> 
            &nbsp;
				    <font size="3" color="BFB97B" face="Bahnschrift">#<?=$index?></font><br>

                <center>
                  <script type="text/javascript">
                    var jmolApplet_f<?=$frag_num?>;

                    var use = "HTML5";
                    var s   = document.location.search;

                    Jmol._debugCode = (s.indexOf("debugcode") >= 0);
                    jmol_isReady = function(applet) {}

                    script_prot = 'load Frags/f<?=$frag_num?>.pdb;background "#3E2805";select all;spacefill off;wireframe off;cartoon on;select all;color structure;';

                    var Info_f<?=$frag_num?> =
                        {
                          width: 200,
                          height: 200,
                          debug: false,
                          color: "#3E2805",
                          addSelectionOptions: false,
                          serverURL: "",
                          use: use,
                          j2sPath: "jsmol/j2s",
                          readyFunction: jmol_isReady,
                          script: script_prot,
                          disableInitialConsole: true,
                          console: "none",
                        }

                    jmolApplet_f<?=$frag_num?> = Jmol.getApplet("jmolApplet_f<?=$frag_num?>", Info_f<?=$frag_num?>);
                  </script>
                </center>
            </td>


<?php
  if ( $index%5 == 0 && $index == $Head_cou ) continue;
  elseif ( $index%5 == 0 )
    echo '			</tr><tr height="165">';
}
//****************************for************************************<<
?>


<?php
if ( $Head_cou%5 != 0 )
{
  $blank = 5-($Head_cou%5);
//*****************************for**********************************>>
  for ($i=0; $i<$blank; $i=$i+1)
  {
?>

            <td width="200" bgcolor="#3E2805"></td>


<?php
  }
//*****************************for*********************************<<
}
?>
          </tr>
          </table>
	  
      <p>&nbsp;</p>
      <p>&nbsp;</p>
	  
      <script type="text/javascript">
        $(document).ready(function() {
          $('#drive').click();
        });
	  
        function syncAll() {
          var r = 'sync *;set syncScript true;sync * "reset";';
          Jmol.script(jmolApplet_f<?=$Head_id[0]?>, r);
          sync();
        }

        function screenshot(){
          html2canvas(document.body).then(function(canvas){
            var a = document.createElement('a');
            var file_name;
            var input = prompt("Please enter file name:", "image.jpg");
            if (input == null || input == "") {
              return
            } else {
              file_name = input;
            }
            a.href = canvas.toDataURL("image/jpeg").replace("image/jpeg", "image/octet-stream");
            a.download = file_name;
            a.click();
          });
        }
	  
        function sync() {
          var syncing = document.getElementById("drive").checked;
          var k = (syncing ? "sync * on;sync * \"set syncMouse TRUE\"": "sync * off");
          Jmol.script(jmolApplet_f<?=$Head_id[0]?>, k);
        }		
      </script>
  

  </body>
</html>