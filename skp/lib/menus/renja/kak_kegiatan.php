<?php
	checkauthentication();
	$p = $_GET['p'];
	$cari = $_GET['cari'];
	$xlevel = $_SESSION['xlevel'];
	$xkdunit = $_SESSION['xkdunit'];
	$table = "thbp_kak_kegiatan";
	$field = get_field($table);
	$ed_link = "index.php?p=".get_edit_link($p);
	$del_link = "index.php?p=".get_delete_link($p);
	$prn_link = "index.php?p=menus/renja/kak_kegiatan_prn";

	if($cari=='') $cari = date('Y')+1;
	
	#Halaman yang akan ditampilkan untuk pertengahan?
	$adjacents = 3;
 	
	switch ($xlevel)
	{
		case '1': 
			$query = "SELECT COUNT(*) as num FROM $table WHERE th LIKE '%$cari%'";
			break;
		case '4':
			$query = "SELECT COUNT(*) as num FROM $table WHERE th LIKE '%$cari%' AND kdunitkerja = '".$xkdunit."'";
			break;
		default:
			$query = "SELECT COUNT(*) as num FROM $table WHERE th LIKE '%$cari%'";
			break;
	}
	
	$total_pages = mysql_fetch_array(mysql_query($query));
	$total_pages = $total_pages[num];
	
	#variabel query
	$targetpage = "index.php?p=$p&cari=$cari"; 	#nama file  (nama file ini)
	$limit = 20; 							#berapa yang akan ditampilkan setiap halaman
	$pagess = $_GET['pagess'];
	if($pagess) 
		$start = ($pagess - 1) * $limit; 			
	else
		$start = 0;							#halaman awal
		
	switch ($xlevel)
	{
		case '1':
			$oList = mysql_query("SELECT * FROM $table WHERE th LIKE '%$cari%' ORDER BY kdunitkerja LIMIT $start, $limit");
			break;
		case '4':
			$oList = mysql_query("SELECT * FROM $table WHERE th LIKE '%$cari%' AND kdunitkerja = '".$xkdunit."' ORDER BY kdunitkerja LIMIT $start, $limit");
			break;
		default:
			$oList = mysql_query("SELECT * FROM $table WHERE th LIKE '%$cari%' ORDER BY kdunitkerja LIMIT $start, $limit");
			break;
	}
	
	$count = mysql_num_rows($oList);
	
	while($List = mysql_fetch_object($oList)) {
		foreach ($field as $k=>$val) {
			$col[$k][] = $List->$val;
		}
	
		$ed[] = $ed_link."&id=".$List->$field[0];
		$del[] = $del_link."&q=".$List_kak_uk['id']."&id=".$List->$field[0];
		$prn[] = $prn_link."&q=".$List_kak_uk['id']."&id=".$List->$field[0];
	}
	
	if ($pagess == 0) $pagess = 1;	#jika variabel kosong maka defaultnya halaman pertama.
	$prev = $pagess - 1;					#halaman sebelumnya
	$next = $pagess + 1;					#halaman berikutnya
	$lastpage = ceil($total_pages/$limit);		
	$lpm1 = $lastpage - 1;						
 
	$pagination = "";
	if($lastpage > 1)
	{	
		$pagination .= "<div class=\"pagination\">";
		#Link halaman sebelumnya
		if ($pagess > 1) 
			$pagination.= "<a href=\"$targetpage&pagess=$prev\"><< Sebelumnya</a>";
		else
			$pagination.= "<span class=\"disabled\"><< Sebelumnya</span>";	
 
		#halaman
		if ($lastpage < 7 + ($adjacents * 2))	
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $pagess)
					$pagination.= "<span class=\"current\">$counter</span>";
				else
					$pagination.= "<a href=\"$targetpage&pagess=$counter\">$counter</a>";					
			}
		}
		elseif($lastpage > 5 + ($adjacents * 2))	#enough pages to hide some
		{
 
			if($pagess < 1 + ($adjacents * 2))		
			{
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
				{
					if ($counter == $pagess)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage&pagess=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"$targetpage&pagess=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"$targetpage&pagess=$lastpage\">$lastpage</a>";		
			}
 
			elseif($lastpage - ($adjacents * 2) > $pagess && $pagess > ($adjacents * 2))
			{
				$pagination.= "<a href=\"$targetpage&pagess=1\">1</a>";
				$pagination.= "<a href=\"$targetpage&pagess=2\">2</a>";
				$pagination.= "...";
				for ($counter = $pagess - $adjacents; $counter <= $pagess + $adjacents; $counter++)
				{
					if ($counter == $pagess)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage&pagess=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"$targetpage&pagess=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"$targetpage&pagess=$lastpage\">$lastpage</a>";		
			}
 
			else
			{
				$pagination.= "<a href=\"$targetpage&pagess=1\">1</a>";
				$pagination.= "<a href=\"$targetpage&pagess=2\">2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $pagess)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage&pagess=$counter\">$counter</a>";					
				}
			}
		}
 
		#link halaman selanjutnya
		if ($pagess < $counter - 1) 
			$pagination.= "<a href=\"$targetpage&pagess=$next\">Selanjutnya >></a>";
		else
			$pagination.= "<span class=\"disabled\">Selanjutnya >></span>";
		$pagination.= "</div>\n";		
	}
	
	echo $pagination;
?>
<div align="right">
	<form action="" method="get">
		<input type="hidden" name="p" value="<?php echo $p; ?>" />
		Tahun : 
		<input type="text" name="cari" value="<?php echo @$cari; ?>" />
		<input type="submit" value="Cari" />
		<a href="http://183.91.67.3/sipristek/index.php?p=33">Reset</a>
	</form>
</div><?php echo 'Tahun '.$cari ?><br />
<table width="840" cellpadding="1" class="adminlist">
  <thead>
    <tr> 
      <th width="3%">No.</th>
      <th width="6%">Unit Kerja </th>
      <th width="5%">Kode</th>
      <th width="29%">Kegiatan</th>
      <th width="11%">Pagu<br>
        Indikatif</th>
      <th width="20%">Penanggung<br>
        Jawab</th>
      <th width="9%">Output</th>
      <th width="9%">Status</th>
      <th colspan="2">Aksi</th>
    </tr>
  </thead>
  <tbody><?php
		
	if ($count == 0) { ?>
    	<tr> 
      	<td align="center" colspan="13">Tidak ada data!</td>
    	</tr><?php
	}
	else {
			
		if ($_SESSION['xlevel'] == '1')
		{
			$sql = "SELECT SUM(jml_anggaran_indikatif) as jml_anggaran FROM thbp_kak_kegiatan WHERE th LIKE '%".$cari."%'";
			$qu = mysql_query($sql);
			$row = mysql_fetch_array($qu); ?>
			 <tr> 
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td align="right"><b><?php echo number_format($row['jml_anggaran'],"0",",","."); ?></b></td>
				<td align="right">&nbsp;</td>
				<td align="right">&nbsp;</td>
				<td align="right">&nbsp;</td>
				<td colspan="2">&nbsp;</td>
			 </tr><?php
		}		
			foreach ($col[0] as $k=>$val) {
				if ($k % 2 == 1) $class = "row0";
				else $class = "row1"; ?>
    <tr class="<?php echo $class ?>"> 
      <td align="center"><?php echo (($pagess-1)*$limit)+($k+1) ?></td>
      <td align="left"> 
        <?php if( $col[2][$k] <> $col[2][$k-1] ){ ?>
        <?php echo ket_unit($col[2][$k]) ?> 
        <?php } ?>
      </td>
      <td align="center"><?php echo $col[3][$k] ?></td>
      <td align="left"><?php echo nm_giat($col[3][$k]) ?></td>
      <td align="right"><?php echo number_format($col[7][$k],"0",",",".") ?></td>
      <td align="left"><?php echo pjawaqb_giat($col[1][$k],$col[3][$k]) ?>&nbsp;</td>
      <td align="center"><a href="index.php?p=126&u=<?php echo $col[2][$k]; ?>&g=<?php echo $col[3][$k]; ?>&t=<?php echo $col[1][$k]; ?>" title="Edit">
								Output >>
						</a></td>
      <td align="right">&nbsp;</td>
      <?php 
	$th = $col[1][$k];
	$kdgiat = $col[3][$k];
	$oList_kak_uk = mysql_query("SELECT id FROM thuk_kak_kegiatan WHERE th = '$th' AND kdgiat = '$kdgiat' ");
	$List_kak_uk = mysql_fetch_array($oList_kak_uk);
?>
      <td width="4%" align="center"> <a href="<?php echo $ed[$k] ?>&q=<?php echo $List_kak_uk['id'] ?>" title="Edit KAK Kegiatan"> 
        <img src="css/images/edit_f2.png" border="0" width="16" height="16"> </a> 
      </td>
      <td width="4%" align="center"> <a href="menus/renja/kak_kegiatan_prn.php?q=<?php echo $List_kak_uk['id'] ?>&id=<?php echo $col[0][$k] ?>" title="Cetak KAK Kegiatan" target="_blank"> 
        <img src="css/images/menu/icon-16-print.png" border="0" width="16" height="16">	
        </a> </td>
    </tr>
    <?php
			}
		} ?>
  </tbody>
  <tfoot>
    <tr> 
      <td colspan="13">&nbsp;</td>
    </tr>
  </tfoot>
</table>
