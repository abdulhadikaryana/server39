<?php
	checkauthentication();
	
	extract($_POST);
	$xmenu_p = xmenu_id($p);
	$p_next = $xmenu_p->parent;

	$p = $_GET['p'];
	$cari = $_GET['cari'];
	$kdsatker = $_REQUEST['kdsatker'];
	$th = $_REQUEST['th'];
	$kdgiat = $_REQUEST['kdgiat'];
	$kdoutput = $_REQUEST['kdoutput'];
	$table = "d_item";
	$field = get_field($table);
	$ed_link = "index.php?p=".get_edit_link($p);
	$del_link = "index.php?p=".get_delete_link($p);
	
	$sql = "select sum(JUMLAH) as pagu_giat from $table WHERE THANG='$th' AND KDSATKER='$kdsatker' and KDGIAT='$kdgiat' AND KDOUTPUT = '$kdoutput' group by KDGIAT";
	$aGiat = mysql_query($sql);
	$Giat = mysql_fetch_array($aGiat);

?>
<div class="button2-right"> 
<div class="prev"><a onclick="Back('index.php?p=<?php echo $p_next ?>')">Kembali</a></div>
</div><br><br>
<?php			
echo "<strong> Tahun Anggaran : ".$th. "</strong><br>" ;
echo "<strong> Satker : [".$kdsatker."] ".nm_satker($kdsatker). "</strong><br>" ;
echo "<strong> Kegiatan : [".$kdgiat."] ".nm_giat($kdgiat). "</strong>" ;
?>
<br />
<table width="738" cellpadding="1" class="adminlist">
  <thead>
    <tr> 
      <th width="6%">Kode APBN</th>
      <th colspan="5"><font color="#FF0000">Output</font><br>
        <font color="#339900">Sub Output</font>/<font color="#3333FF">Komponen</font><br>
        <font color="#660066">Sub Komponen</font>/<font color="#663300">Akun</font><br />Item        </th>
      <th width="13%">Anggaran</th>
      <th width="13%">Blokir</th>
    </tr>
  </thead>
  <tbody>
    
    <?php 
	$sql = "select KDOUTPUT, sum(JUMLAH) as pagu_output , sum(RPHBLOKIR) as blokir_output from $table WHERE THANG='$th' and KDSATKER='$kdsatker' and KDGIAT='$kdgiat' AND KDOUTPUT = '$kdoutput' group by KDOUTPUT";
	$aOutput = mysql_query($sql);
	while ($Output = mysql_fetch_array($aOutput))
	{
?>
    <tr class="<?php echo $class ?>"> 
      <td align="center"><font color="#FF0000"><?php echo $Output['KDOUTPUT'] ?></font></td>
      <td colspan="5" align="left"><font color="#FF0000"><?php echo nm_output_th($th,$kdgiat.$Output['KDOUTPUT']) ?>&nbsp;</font></td>
      <td align="right"><font color="#FF0000"><?php echo number_format($Output['pagu_output'],"0",",",".") ?></font></td>
      <td align="right"><font color="#FF0000"><?php echo number_format($Output['blokir_output'],"0",",",".") ?></font></td>
    </tr>
    <?php 
	$sql = "select KDSOUTPUT, sum(JUMLAH) as pagu_soutput, sum(RPHBLOKIR) as blokir_soutput from $table WHERE THANG='$th' and KDSATKER='$kdsatker' and KDGIAT='$kdgiat' and KDOUTPUT='$Output[KDOUTPUT]' group by KDSOUTPUT";
	$aSOutput = mysql_query($sql);
	while ($SOutput = mysql_fetch_array($aSOutput))
	{
	?>
    <tr class="<?php echo $class ?>"> 
      <td align="center"><font color="#FF0000"><?php echo $Output['KDOUTPUT'].'.' ?></font><font color="#339900"><?php echo $SOutput['KDSOUTPUT'] ?></font></td>
      <td colspan="5" align="left"><font color="#339900"><?php echo nm_suboutput($kdgiat.$Output['KDOUTPUT'].$SOutput['KDSOUTPUT'],$th) ?>&nbsp;</font></td>
      <td align="right"><font color="#339900"><?php echo number_format($SOutput['pagu_soutput'],"0",",",".") ?></font></td>
      <td align="right"><font color="#339900"><?php echo number_format($SOutput['blokir_soutput'],"0",",",".") ?></font></td>
    </tr>
    <?php 
	$sql = "select KDKMPNEN, sum(JUMLAH) as pagu_kmpnen, sum(RPHBLOKIR) as blokir_kmpnen from $table WHERE THANG='$th' and KDSATKER='$kdsatker' and KDGIAT='$kdgiat' and KDOUTPUT='$Output[KDOUTPUT]' and KDSOUTPUT='$SOutput[KDSOUTPUT]' group by KDKMPNEN";
	$aKmpnen = mysql_query($sql);
	while ($Kmpnen = mysql_fetch_array($aKmpnen))
	{
	?>
    <tr class="<?php echo $class ?>"> 
      <td align="center"><font color="#3333FF"><?php echo $Kmpnen['KDKMPNEN'] ?></font></td>
      <td colspan="5" align="left"><font color="#3333FF"><?php echo nm_kmpnen($kdgiat.$Output['KDOUTPUT'].$SOutput['KDSOUTPUT'].$Kmpnen['KDKMPNEN'],$th) ?>&nbsp;</font></td>
      <td align="right"><font color="#3333FF"><?php echo number_format($Kmpnen['pagu_kmpnen'],"0",",",".") ?></font></td>
      <td align="right"><font color="#3333FF"><?php echo number_format($Kmpnen['blokir_kmpnen'],"0",",",".") ?></font></td>
    </tr>
    <?php 
	$sql = "select KDSKMPNEN, sum(JUMLAH) as pagu_skmpnen , sum(RPHBLOKIR) as blokir_skmpnen from $table WHERE THANG='$th' and KDSATKER='$kdsatker' and KDGIAT='$kdgiat' and KDOUTPUT='$Output[KDOUTPUT]' and KDSOUTPUT='$SOutput[KDSOUTPUT]' and KDKMPNEN='$Kmpnen[KDKMPNEN]' group by KDSKMPNEN";
	$aSKmpnen = mysql_query($sql);
	while ($SKmpnen = mysql_fetch_array($aSKmpnen))
	{
	?>
    <tr class="<?php echo $class ?>"> 
      <td align="center"><font color="#3333FF"><?php echo $Kmpnen['KDKMPNEN'].'.' ?></font><font color="#660066"><?php echo $SKmpnen['KDSKMPNEN'] ?></font></td>
      <td colspan="5" align="left"><font color="#660066"><?php echo nm_skmpnen($kdgiat.$Output['KDOUTPUT'].$SOutput['KDSOUTPUT'].$Kmpnen['KDKMPNEN'].$SKmpnen['KDSKMPNEN'],$th) ?>&nbsp;</font></td>
      <td align="right"><font color="#660066"><?php echo number_format($SKmpnen['pagu_skmpnen'],"0",",",".") ?></font></td>
      <td align="right"><font color="#660066"><?php echo number_format($SKmpnen['blokir_skmpnen'],"0",",",".") ?></font></td>
    </tr>
    <?php 
	$sql = "select KDAKUN, sum(JUMLAH) as pagu_akun, sum(RPHBLOKIR) as blokir_akun from $table WHERE THANG='$th' and KDSATKER='$kdsatker' and KDGIAT='$kdgiat' and KDOUTPUT='$Output[KDOUTPUT]' and KDSOUTPUT='$SOutput[KDSOUTPUT]' and KDKMPNEN='$Kmpnen[KDKMPNEN]' and KDSKMPNEN='$SKmpnen[KDSKMPNEN]' group by KDAKUN";
	$aAkun = mysql_query($sql);
	while ($Akun = mysql_fetch_array($aAkun))
	{
	?>
    <tr class="<?php echo $class ?>"> 
      <td align="center"><font color="#663300">&nbsp;</font></td>
      <td width="3%" align="center"><font color="#663300"><?php echo $Akun['KDAKUN'] ?>&nbsp;</font></td>
      <td colspan="4" align="left"><font color="#663300"><?php echo nm_akun($Akun['KDAKUN']) ?>&nbsp;</font></td>
      <td align="right"><font color="#663300"><?php echo number_format($Akun['pagu_akun'],"0",",",".") ?></font></td>
      <td align="right"><font color="#663300"><?php echo number_format($Akun['blokir_akun'],"0",",",".") ?></font></td>
    </tr>
    <?php 
	$sql = "select NMITEM,VOLKEG,SATKEG,JUMLAH,HARGASAT,RPHBLOKIR from $table WHERE THANG='$th' and KDSATKER='$kdsatker' and KDGIAT='$kdgiat' and KDOUTPUT='$Output[KDOUTPUT]' and KDSOUTPUT='$SOutput[KDSOUTPUT]' and KDKMPNEN='$Kmpnen[KDKMPNEN]' and KDSKMPNEN='$SKmpnen[KDSKMPNEN]' and KDAKUN='$Akun[KDAKUN]'";
	$aItem = mysql_query($sql);
	while ($Item = mysql_fetch_array($aItem))
	{
	?>
    <tr class="<?php echo $class ?>"> 
      <td align="center">&nbsp;</td>
      <td align="center">&nbsp;</td>
      <td width="57%" align="left"><?php echo $Item['NMITEM'] ?>&nbsp;</td>
      <td width="8%" align="center"><?php echo $Item['VOLKEG'].' '.$Item['SATKEG'] ?>&nbsp;</td>
      <td width="6%" align="right"><?php echo number_format($Item['HARGASAT'],"0",",",".") ?></td>
      <td width="7%" align="right"><?php echo number_format($Item['JUMLAH'],"0",",",".") ?></td>
      <td align="right">&nbsp;</td>
      <td align="right"><?php echo number_format($Item['RPHBLOKIR'],"0",",",".") ?></td>
    </tr>
    <?php
		} # ITEM
		} # AKUN
		} # SUB KMPNEN
		} # KMPNEN
		} # SUB OUTPUT
		} # OUTPUT
	?>
  </tbody>
  <tfoot>
    <tr> 
      <td colspan="10">&nbsp;</td>
    </tr>
  </tfoot>
</table>
