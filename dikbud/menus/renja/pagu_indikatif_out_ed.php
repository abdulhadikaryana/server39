<?php
	checkauthentication();
	$table = "thbp_kak_output";
	$err = false;
	$p = $_GET['p'];
	$kdunitkerja = $_GET['u'];
	$kdgiat = $_GET['g'];
	$th = $_GET['t'];
	$u = $_GET['u'];
	extract($_POST);
	$xusername_sess = $_SESSION['xusername'];
	switch ( $th )
	{
	    case 2012 :
		$tab_output = "t_output2012";
		break;
	    case 2013 :
		$tab_output = "t_output2013";
		break;
	    case 2014 :
		$tab_output = "t_output2014";
		break;
	    default :
		$tab_output = "t_output";
		break;
	}	
	$xmenu_p = xmenu_id($p);
	$p_next = $xmenu_p->parent;
	
	if (isset($form)) 
	{		
		if ($err != true) 
		{	
			if ($q == "") 
			{
				//ADD NEW	
				$jml_anggaran		= $_REQUEST['jml_anggaran'];
				// replace
				$jml_anggaran = str_replace('.','',$jml_anggaran);
				$jml_anggaran = str_replace(',','.',$jml_anggaran);
				mysql_query("INSERT INTO thuk_kak_output(id,th,kdunitkerja,kdgiat,kdoutput) 
							VALUE ('','$th','$kdunitkerja','$kdgiat','$kdoutput')" );
				$sql = "INSERT INTO $table(id,th,kdunitkerja,kdgiat,kdoutput,jml_anggaran,volume,satuan) VALUE ('','$th','$kdunitkerja','$kdgiat','$kdoutput','$jml_anggaran','$volume','$satuan')";
				$rs = mysql_query($sql);
				
				if ($rs) 
				{
					update_log($sql,$table,1);
					$_SESSION['errmsg'] = "Input data berhasil!";
				}
				else 
				{
					update_log($sql,$table,0);
					$_SESSION['errmsg'] = "Input data gagal!";
				} ?>
				
				<meta http-equiv="refresh" content="0;URL=index.php?p=453&u=<?php echo $kdunitkerja ?>&g=<?php echo $kdgiat ?>&t=<?php echo $th ?>"><?php
				exit();
			} 
			else {
				// UPDATE
				$jml_anggaran		= $_REQUEST['jml_anggaran'];
				// replace
				$jml_anggaran = str_replace('.','',$jml_anggaran);
				$jml_anggaran = str_replace(',','.',$jml_anggaran);
				$sql = "UPDATE $table SET kdoutput = '$kdoutput', volume = '$volume', satuan = '$satuan' , jml_anggaran = '$jml_anggaran' WHERE id = '$q'";
				$rs = mysql_query($sql);
				
				if ($rs) 
				{	
					update_log($sql,$table,1);
					$_SESSION['errmsg'] = "Ubah data berhasil!";
				}
				else 
				{
					update_log($sql,$table,0);
					$_SESSION['errmsg'] = "Ubah data gagal!";			
				} ?>
				
				<meta http-equiv="refresh" content="0;URL=index.php?p=453&u=<?php echo $kdunitkerja ?>&g=<?php echo $kdgiat ?>&t=<?php echo $th ?>"><?php
				exit();
			}
		}
		else 
		{ ?>
			<meta http-equiv="refresh" content="0;URL=index.php?p=453&u=<?php echo $kdunitkerja ?>&g=<?php echo $kdgiat ?>&t=<?php echo $th ?>"><?php		
		}
	} 
	else if (isset($_GET["q"])) 
	{
		$sql = "SELECT * FROM $table WHERE id = '".$_GET['q']."'";
		$aFungsi = mysql_query($sql);
		$value = mysql_fetch_array($aFungsi);
	}
	else {
		$value = array();
	} ?>

<script>	
	function numberFormat(nStr){
  // change format first
  nStr = nStr.replace(/\./g,'');
  nStr = nStr.replace(/,/g,'.');
  // process
  nStr += '';
  x = nStr.split('.');
  x1 = x[0];
  x2 = x.length > 1 ? '.' + x[1] : '';
  var rgx = /(\d+)(\d{3})/;
  while (rgx.test(x1))
    x1 = x1.replace(rgx, '$1' + ',' + '$2');
  var result = x1 + x2;
  // change again
  result = result.replace(/,/g,';');
  result = result.replace(/\./g,',');
  result = result.replace(/;/g,'.');
  return result;
}
</script>

<form action="index.php?p=453&u=<?php echo $kdunitkerja ?>&g=<?php echo $kdgiat ?>&t=<?php echo $th ?>" method="post" name="form">
	
  <table cellspacing="1" class="admintable">
    <tr> 
      <td width="261" class="key">Tahun</td>
      <td width="458"><input type="text" size="10" value="<?php echo $th ?>" disabled="disabled"/></td>
    </tr>
    <tr> 
      <td class="key">Unit Kerja </td>
      <td><textarea name="" cols="70" rows="1" disabled="disabled"><?php echo nm_unit($kdunitkerja) ?></textarea></td>
    </tr>
    <tr> 
      <td class="key">Kegiatan</td>
      <td><textarea name="" cols="70" rows="2" disabled="disabled"><?php echo '['.$kdgiat.'] '.nm_giat($kdgiat) ?></textarea></td>
    </tr>
    <?php
	  
	  $sql = "SELECT jml_anggaran_renstra,jml_anggaran_dipa,jml_anggaran_indikatif FROM thbp_kak_kegiatan WHERE th = '$th' and kdgiat = '".$kdgiat."' and kdunitkerja = '$kdunitkerja' ";
	  $qu = mysql_query($sql);
	  $row = mysql_fetch_array($qu); ?>
    
    <tr> 
      <td class="key">Anggaran DIPA <?php echo ($th - 1) ?></td>
      <td><input type="text" size="30" value="<?php echo number_format($row['jml_anggaran_dipa'],"0",",","."); ?>" disabled="disabled"/></td>
    </tr>
    <tr> 
      <td class="key">Pagu Indikatif <?php echo $th ?></td>
      <td><input type="text" size="30" value="<?php echo number_format($row['jml_anggaran_indikatif'],"0",",","."); ?>" disabled="disabled"/></td>
    </tr>
    <tr> 
      <td colspan="2" align="center"><strong>Data Output : </strong></td>
    </tr>
    <tr> 
      <td class="key">Output</td>
      <td> <select name="kdoutput">
          <option value="<?php echo @$value['kdoutput'] ?>"><?php echo  @$value['kdoutput'].' '.substr(nm_output_th($th,$kdgiat.@$value['kdoutput']),0,70) ?></option>
          <option value="">- Pilih Output -</option>
          <?php
		  
			$query = mysql_query("select KDOUTPUT, left(NMOUTPUT,60) as nmoutput from $tab_output WHERE KDGIAT='$kdgiat' order by KDOUTPUT");
			while($row = mysql_fetch_array($query)) { ?>
          <option value="<?php echo $row['KDOUTPUT'] ?>"><?php echo  $kdgiat.'.'.$row['KDOUTPUT'].' '.$row['nmoutput']; ?></option>
          <?php
			} ?>
        </select> </td>
    </tr>
    <tr> 
      <td class="key">Volume</td>
      <td><input type="text" name="volume" size="10" value="<?php echo @$value['volume'] ?>" /> 
        &nbsp;&nbsp;<?php echo sat_output_th($th,$kdgiat.@$value['kdoutput']) ?></td>
    </tr>
    <tr> 
      <td class="key">Anggaran</td>
      <td><input type="text" name="jml_anggaran" 
	  		id="jml_anggaran" onkeyup="this.value = numberFormat(this.value);"
	  		value="<?php echo number_format(@$value['jml_anggaran'],"0",",",".") ?>" size="15" /></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td> <div class="button2-right"> 
          <div class="prev"> <a onclick="Back('index.php?p=375&cari=<?php echo $th; ?>')">Kembali</a>          </div>
        </div>
        <div class="button2-left"> 
          <div class="next"> <a onclick="form.submit();">Simpan</a> </div>
        </div>
        <div class="clr"></div>
        <input style="border: 0pt none ; margin: 0pt; padding: 0pt; width: 0px; height: 0px;" value="Simpan" type="submit"> 
        <input name="form" type="hidden" value="1" /> <input name="q" type="hidden" value="<?php echo $_GET['q'] ?>" />      </td>
    </tr>
  </table>
</form>
<br />
<?php
	$sql = "SELECT * FROM $table WHERE th='$th' and kdunitkerja='$kdunitkerja' and kdgiat='$kdgiat' ORDER BY kdoutput";
	$aOutput = mysql_query($sql);
	$count = mysql_num_rows($aOutput);
	$jmlh = 0;
	
	while ($Output = mysql_fetch_array($aOutput))
	{
		$col[0][] = $Output['id'];
		$col[1][] = $Output['kdoutput'];
		$col[2][] = $Output['volume'];
		$col[3][] = $Output['satuan'];
		$col[4][] = $Output['jml_anggaran'];
		$jmlh += $Output['jml_anggaran'];
	}
?>
<table cellpadding="1" class="adminlist">
	<thead>
		<tr>
			<th width="4%">No.</th>
			<th width="4%">Kode Output </th>
			<th>Output</th>
			<th>Volume</th>
			<th>Anggaran</th>
			<th colspan="2" width="6%">Aksi</th>
		</tr>
	</thead>
	<tbody><?php
		if ($count == 0) 
		{ ?>
			<tr><td align="center" colspan="7">Tidak ada data!</td></tr><?php
		}
		else {
			foreach ($col[0] as $k=>$val) {
				if ($k % 2 == 1) $class = "row0";
				else $class = "row1"; ?>
				<tr class="<?php echo $class ?>">
					<td align="center"><?php echo $k+1; ?></td>
					<td align="center"><?php echo $col[1][$k] ?></td>
					<td align="left"><?php echo nm_output_th($th,$kdgiat.$col[1][$k]) ?></td>
					<td align="center"><?php echo $col[2][$k].' '.sat_output_th($th,$kdgiat.$col[1][$k]) ?></td>
					<td align="right"><?php echo number_format($col[4][$k],"0",",",".") ?></td>
					<td align="center" valign="top">
						<a href="index.php?p=453&u=<?php echo $u; ?>&g=<?php echo $kdgiat; ?>&t=<?php echo $th; ?>&q=<?php echo $col[0][$k]; ?>" title="Edit">
							<img src="css/images/edit_f2.png" border="0" width="16" height="16">						</a>					</td>
					<td align="center">
						<a href="index.php?p=454&q=<?php echo $col[0][$k]; ?>" title="Delete">
							<img src="css/images/stop_f2.png" border="0" width="16" height="16">						</a>					</td>
				</tr><?php
			}
		} ?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="4" align="right"><b>Jumlah</b></td>
			<td align="right"><b><?php echo number_format($jmlh,"0",",",".") ?></b></td>
			<td colspan="2">&nbsp;</td>
		</tr>
	</tfoot>
</table>
