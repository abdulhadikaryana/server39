<?php
	checkauthentication();
	
	extract($_POST);
	$xmenu_p = xmenu_id($p);
	$p_next = $xmenu_p->parent;

	$p = $_GET['p'];
	$cari = $_GET['cari'];
	$kdsatker = $_REQUEST['kdsatker'];
	$th = $_REQUEST['th'];
	$table = "m_spmind";
	$field = get_field($table);
	$ed_link = "index.php?p=".get_edit_link($p);
	$del_link = "index.php?p=".get_delete_link($p);
	
	$sql = "select KDGIAT from $table WHERE THANG='$th' AND KDSATKER='$kdsatker' and KDGIAT <> '0000' group by KDGIAT";
	$aGiat = mysql_query($sql);
	$Giat = mysql_fetch_array($aGiat);
	$kdgiat = $Giat['KDGIAT'];
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
      <th colspan="5">Nama Kegiatan/ Output / Akun </th>
      <th>Anggaran</th>
    </tr>
  </thead>
  <tbody>
    <tr class="<?php echo $class ?>"> 
      <td width="6%" align="center"><font color="#0FA40B"><strong><?php echo $kdgiat ?></strong></font></td>
      <td colspan="5" align="left"><font color="#0FA40B"><strong><?php echo nm_giat($kdgiat) ?></strong></font></td>
      <td width="12%" align="right"><font color="#0FA40B"><strong><?php echo number_format(realisasi_giat_jnsbel($th,$kdgiat,'53'),"0",",",".") ?></strong></font></td>
    </tr>
    <?php 
	$sql = "select KDOUTPUT from $table WHERE THANG='$th' and KDSATKER='$kdsatker' and KDGIAT='$kdgiat' group by KDOUTPUT order by KDOUTPUT";
	$aOutput = mysql_query($sql);
	while ($Output = mysql_fetch_array($aOutput))
	{ 
	$kdoutput = $Output['KDOUTPUT'] ;
	$real_output = realisasi_output_jnsbel($th,$kdgiat,$kdoutput,'53');
	if ($real_output <> 0 ){
?>
    <tr class="<?php echo $class ?>"> 
      <td align="center"><font color="#FF0000"><?php echo $kdgiat.'.'.$Output['KDOUTPUT'] ?></font></td>
      <td colspan="5" align="left"><font color="#FF0000"><?php echo nm_output($kdgiat.$Output['KDOUTPUT']) ?>&nbsp;</font></td>
      <td align="right"><font color="#FF0000"><?php echo number_format($real_output,"0",",",".") ?></font></td>
    </tr>
    <?php 
	
	$sql = "select NOSPM,TGSPM,NOSP2D,TGSP2D, sum(TOTNILMAK) as real_spm from $table WHERE THANG='$th' and KDSATKER='$kdsatker' and KDOUTPUT='$Output[KDOUTPUT]' group by concat(NOSPM,NOSP2D)";
	$aSPM = mysql_query($sql);
	while ($SPM = mysql_fetch_array($aSPM))
	{
	?>
    <tr class="<?php echo $class ?>"> 
      <td align="center"><font color="#3333FF">&nbsp;</font></td>
      <td width="15%" align="center"><font color="#3333FF">SPM : <?php echo $SPM['NOSPM'] ?></font></td>
      <td width="16%" align="center"><font color="#3333FF"><?php echo reformat_tgl($SPM['TGSPM']) ?></font></td>
      <td width="15%" align="center"><font color="#3333FF">SP2D : <?php echo $SPM['NOSP2D'] ?></font></td>
      <td width="16%" align="center"><font color="#3333FF"><?php echo reformat_tgl($SPM['TGSP2D']) ?></font></td>
      <td width="20%" align="right"><font color="#3333FF"><?php echo number_format($SPM['real_spm'],"0",",",".") ?></font></td>
      <td align="right"><font color="#3333FF">&nbsp;</font></td>
    </tr>
    <?php 
	$sql = "select KDAKUN,NILMAK from m_spmmak WHERE THANG='$th' and KDSATKER='$kdsatker' and NOSPM='$SPM[NOSPM]' and NOSP2D='$SPM[NOSP2D]' order by KDAKUN";
	$aAkun = mysql_query($sql);
	while ($Akun = mysql_fetch_array($aAkun))
	{
	?>
    <tr class="<?php echo $class ?>"> 
      <td align="center">&nbsp;</td>
      <td align="center"><?php echo $Akun['KDAKUN'] ?></td>
      <td colspan="3" align="left"><?php echo nm_akun($Akun['KDAKUN']) ?>&nbsp;</td>
      <td align="right"><?php echo number_format($Akun['NILMAK'],"0",",",".") ?></td>
      <td align="right">&nbsp;</td>
    </tr>
    <?php
		} # SP2D
		} # AKUN
		} # iif output
		} # OUTPUT
	?>
  </tbody>
  <tfoot>
    <tr> 
      <td colspan="9">&nbsp;</td>
    </tr>
  </tfoot>
</table>
