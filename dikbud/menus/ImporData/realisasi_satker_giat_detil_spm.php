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
	$table_1 = "d_spmind";
	$table_2 = "d_spmmak";
	$field = get_field($table);
	$ed_link = "index.php?p=".get_edit_link($p);
	$del_link = "index.php?p=".get_delete_link($p);
	
?>
<div class="button2-right"> 
<div class="prev"><a onclick="Back('index.php?p=<?php echo $p_next ?>')">Kembali</a></div>
</div><br><br>
<?php	
echo "<strong> Tahun Anggaran : ".$th. "</strong><br>" ;
echo "<strong> Satker : ".nm_satker($kdsatker). "</strong>" ;
?>
<br />
<table width="738" cellpadding="1" class="adminlist">
  <thead>
    <tr> 
      <th>Kode APBN</th>
      <th colspan="3"><font color="#0FA40B">Kegiatan</font> / <font color="#FF0000">Output</font> / Akun / <font color="#3333FF">SPM </font></th>
      <th>Realisasi<br />Anggaran SPM</th>
    </tr>
  </thead>
  <tbody>
    <tr class="<?php echo $class ?>"> 
      <td width="6%" align="center"><font color="#0FA40B"><strong><?php echo $kdgiat ?></strong></font></td>
      <td colspan="3" align="left"><font color="#0FA40B"><strong><?php echo nm_giat($kdgiat) ?></strong></font></td>
      <td width="12%" align="right"><font color="#0FA40B"><strong><?php echo number_format(real_giat_spm($th,$kdsatker,$kdgiat),"0",",",".") ?></strong></font></td>
    </tr>
    <?php 
	if ( mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$table_1."'")) == 1 AND mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$table_2."'")) == 1 )
	{
	
	$sql = "select sum(totnilmak) as real_giat from $table_1 WHERE thang='$th' AND kdsatker='$kdsatker' and kdgiat='$kdgiat' group by kdgiat";
	$aGiat = mysql_query($sql);
	$Giat = mysql_fetch_array($aGiat);
	
	$sql = "select kdoutput, sum(totnilmak) as real_output from $table_1 WHERE thang='$th' and kdsatker='$kdsatker' and kdgiat='$kdgiat' group by kdoutput order by kdoutput";
	$aOutput = mysql_query($sql);
	while ($Output = mysql_fetch_array($aOutput))
	{
?>
    <tr class="<?php echo $class ?>"> 
      <td align="center"><font color="#FF0000"><?php echo $kdgiat.'.'.$Output['kdoutput'] ?></font></td>
      <td colspan="3" align="left"><font color="#FF0000"><?php echo nm_output_th($th,$kdgiat.$Output['kdoutput']) ?>&nbsp;</font></td>
      <td align="right"><font color="#FF0000"><?php echo number_format($Output['real_output'],"0",",",".") ?></font></td>
    </tr>
    <?php 
	
	$sql = "select nospm,tgspm, sum(totnilmak) as real_spm from $table_1 WHERE thang = '$th' and kdsatker = '$kdsatker' and kdoutput = '$Output[kdoutput]' group by nospm order by nospm ";
	$aSPM = mysql_query($sql);
	while ($SPM = mysql_fetch_array($aSPM))
	{
	?>
    <tr class="<?php echo $class ?>"> 
      <td align="center"><font color="#3333FF">&nbsp;</font></td>
      <td width="15%" align="center"><font color="#3333FF">No : <?php echo $SPM['nospm'] ?></font></td>
      <td width="16%" align="left"><font color="#3333FF">Tgl. <?php echo reformat_tgl($SPM['tgspm']) ?></font></td>
      <td width="20%" align="right"><font color="#3333FF"><?php echo number_format($SPM['real_spm'],"0",",",".") ?></font></td>
      <td align="right"><font color="#3333FF">&nbsp;</font></td>
    </tr>
    <?php 
	$sql = "select kdakun,nilmak from $table_2 WHERE thang = '$th' and kdsatker = '$kdsatker' and nospm = '$SPM[nospm]' order by kdakun";
	$aAkun = mysql_query($sql);
	while ($Akun = mysql_fetch_array($aAkun))
	{
	?>
    <tr class="<?php echo $class ?>"> 
      <td align="center">&nbsp;</td>
      <td align="center"><?php echo $Akun['kdakun'] ?></td>
      <td align="left"><?php echo nm_akun($Akun['kdakun']) ?>&nbsp;</td>
      <td align="right"><?php echo number_format($Akun['nilmak'],"0",",",".") ?></td>
      <td align="right">&nbsp;</td>
    </tr>
    <?php
		} # SP2D
		} # AKUN
		} # OUTPUT
	}else{   # tabel tidak tersedia
	?>
	<tr> 
      <td align="center" colspan="5">Tidak ada data!</td>
    </tr>
	<?php
	}
	?>
  </tbody>
  <tfoot>
    <tr> 
      <td colspan="7">&nbsp;</td>
    </tr>
  </tfoot>
</table>
