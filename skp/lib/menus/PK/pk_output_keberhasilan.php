<?php
	checkauthentication();
	$p = $_GET['p'];
	$cari = $_GET['cari'];
	$xusername = $_SESSION['xusername'];
	$xlevel = $_SESSION['xlevel'];
	$xkdunit = $_SESSION['xkdunit'];
	$kdunit = substr($xkdunit,0,4);
	$kddeputi = substr($xkdunit,0,3);
	$table = "thbp_kak_kegiatan";
	$field = get_field($table);
	$ed_link = "index.php?p=".get_delete_link($p);
	$th = $_SESSION['xth'];
//	if($cari=='') $cari = date('Y');
	
	#Halaman yang akan ditampilkan untuk pertengahan?
	$adjacents = 3;
 
	switch ($xlevel)
	{
		case '1':
			$query = "SELECT COUNT(*) as num FROM $table WHERE th = '$th'";
			break;
		case '3':
			$query = "SELECT COUNT(*) as num FROM $table WHERE th = '$th' AND kdsatker = '".$xusername."'";
			break;
		case '4':
			$query = "SELECT COUNT(*) as num FROM $table WHERE th = '$th' AND left(kdunitkerja,3) = '".$kddeputi."'";
			break;
		case '5':
			$query = "SELECT COUNT(*) as num FROM $table WHERE th = '$th' AND left(kdunitkerja,4) = '".$kdunit."'";
			break;
		default:
			$query = "SELECT COUNT(*) as num FROM $table WHERE th = '$th'";
			break;
	}
	
	
	$total_pages = mysql_fetch_array(mysql_query($query));
	$total_pages = $total_pages[num];
	
	#variabel query
	$targetpage = "index.php?p=$p&cari=$cari"; 	#nama file  (nama file ini)
	$limit = 2; 							#berapa yang akan ditampilkan setiap halaman
	$pagess = $_GET['pagess'];
	if($pagess) 
		$start = ($pagess - 1) * $limit; 			
	else
		$start = 0;							#halaman awal
		
	switch ($xlevel)
	{
		case '1':
			$oList = mysql_query("SELECT * FROM $table WHERE th = '$th' ORDER BY kdsatker LIMIT $start, $limit");
			break;
		case '3':
			$oList = mysql_query("SELECT * FROM $table WHERE th = '$th' AND kdsatker = '".$xusername."' GROUP BY kdsatker ORDER BY kdsatker LIMIT $start, $limit");
			break;
		case '4':
			$oList = mysql_query("SELECT * FROM $table WHERE th = '$th' AND left(kdunitkerja,3) = '".substr($xkdunit,0,3)."' GROUP BY kdsatker ORDER BY kdsatker LIMIT $start, $limit");
			break;
		case '5':
			$oList = mysql_query("SELECT * FROM $table WHERE th = '$th' AND left(kdunitkerja,4) = '".substr($xkdunit,0,4)."' GROUP BY kdsatker ORDER BY kdsatker LIMIT $start, $limit");
			break;
		default:
			$oList = mysql_query("SELECT * FROM $table WHERE th = '$th' ORDER BY kdsatker LIMIT $start, $limit");
			break;
	}
	
	$count = mysql_num_rows($oList);
	
	while($List = mysql_fetch_object($oList)) {
		foreach ($field as $k=>$val) {
			$col[$k][] = $List->$val;
		}
	
		$ed[] = $ed_link;
		$prn[] = $prn_link;
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
<table width="840" cellpadding="1" class="adminlist">
  <thead>
    <tr> 
      <th width="5%" rowspan="2">No.</th>
      <th width="7%" rowspan="2">Unit Kerja</th>
      <th colspan="2">Kode</th>
      <th rowspan="2">Nama Satker dan Kegiatan /<br>
        Output</th>
      <th width="19%" rowspan="2">Kriteria<br />Keberhasilan </th>
      <th width="18%" rowspan="2">Ukuran<br />Keberhasilan</th>
      <th rowspan="2">Aksi</th>
    </tr>
    <tr>
      <th width="7%">Satker</th>
      <th width="7%">Giat</th>
    </tr>
  </thead>
  <tbody>
    <?php
		if ($count == 0) { ?>
    <tr> 
      <td align="center" colspan="11">Tidak ada data!</td>
    </tr>
    <?php
		}
		else {
			
			$sql = "SELECT SUM(jml_anggaran_indikatif) as jml_anggaran FROM thbp_kak_kegiatan WHERE th = '$th'";
			$qu = mysql_query($sql);
			$row = mysql_fetch_array($qu);
			?>
    <?php
			
			foreach ($col[0] as $k=>$val) {
				if ($k % 2 == 1) $class = "row0";
				else $class = "row1"; ?>
    <tr class="<?php echo $class ?>"> 
      <td align="center"><strong><?php echo (($pagess-1)*$limit)+($k+1) ?></strong></td>
      <td align="left"> <strong> 
        <?php if( $col[2][$k] <> $col[2][$k-1] ){ ?>
        <?php echo ket_unit($col[2][$k]) ?> 
        <?php } ?>
        </strong></td>
      <td align="center"><?php echo $col[10][$k] ?></td>
      <td align="center"><strong><?php echo $col[3][$k] ?></strong></td>
      <td align="left"><strong><?php echo nm_satker($col[10][$k]) ?><br /><?php echo nm_giat($col[3][$k]) ?></strong></td>
      <td align="left"><strong>&nbsp;</strong></td>
      <td align="right">&nbsp;</td>
      <td width="6%" align="center">&nbsp;</td>
    </tr>
    <?php 
	$th = $col[1][$k];
	$kdunitkerja = $col[2][$k];
	$kdsatker = $col[10][$k];
	$kdgiat = $col[3][$k];
	$oList_kak_output = mysql_query("SELECT * FROM thbp_kak_output WHERE th = '$th' and kdsatker = '$kdsatker' AND kdgiat = '$kdgiat' ORDER BY kdoutput");
	while($List_kak_output = mysql_fetch_array($oList_kak_output)){
	$kdoutput = $List_kak_output['kdoutput'] ;
	
	$oList = mysql_query("SELECT * FROM thuk_kak_output_ukp4_pp39 WHERE th = '$th' AND kdunitkerja = '$List_kak_output[kdunitkerja]' AND kdgiat = '$kdgiat' AND kdoutput = '$kdoutput' ");
	$List = mysql_fetch_array($oList);
?>
    <tr class="<?php echo $class ?>"> 
      <td align="center">&nbsp;</td>
      <td align="left">&nbsp;</td>
      <td align="center">&nbsp;</td>
      <td align="center" valign="top"><?php echo $List_kak_output['kdoutput'] ?></td>
      <td width="38%" align="left" valign="top"><?php echo nm_output($kdgiat.$List_kak_output['kdoutput']) ?></td>
      <td align="left" valign="top"><?php echo $List['kriteria'] ?></td>
      <td align="left" valign="top"><?php echo $List['ukuran'] ?></td>
      <td align="center" valign="top"> <a href="<?php echo $ed[$k] ?>&amp;q=<?php echo $List['id'] ?>&amp;o=<?php echo $List_kak_output['id'] ?>&pagess=<?php echo $pagess ?>" title="Edit Kriteria dan Ukuran Keberhasilan Output"> 
        <img src="css/images/edit_f2.png" border="0" width="16" height="16"> </a> </td>
    </tr>
    <?php
		}
		}
		} ?>
  </tbody>
  <tfoot>
    <tr> 
      <td colspan="11">&nbsp;</td>
    </tr>
  </tfoot>
</table>
