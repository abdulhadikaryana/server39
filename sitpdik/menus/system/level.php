<?php
	checkauthentication();
	$h = ekstrak_get(@$get[1]);
	$c = @$_POST['c'];
	
	$addlink	= 72;
	$edlink 	= 73;
	$dellink	= 74;
	
	if (@$_POST['c'])
	{
		$sql = sql_select("xlevel", "*", "kode LIKE '%".$c."%' OR name LIKE '%".$c."%'", "ordering");
	}
	
	else
	{
		$sql = sql_select("xlevel", "*", "", "ordering");
	}
	
	$olist = mysql_query($sql) or die(mysql_error());
	$totpages = mysql_num_rows($olist);
	
	$targetpage = "index.php?p=";
	$limit = 20;
	
	if ($h) $start = ($h - 1) * $limit;
	else $start = 0;
	
	$sql .= " LIMIT ".$start.", ".$limit;
	$olist = mysql_query($sql) or die(mysql_error());
	$nlist = mysql_num_rows($olist);
	
	if ($h == 0) $h = 1;
	$prev = $h - 1;
	$next = $h + 1;
	$lastpage = ceil($totpages/$limit);
	$lpm1 = $lastpage - 1;
 
	$pagination = "";
	
	if($lastpage > 1)
	{	
		$pagination .= "<div class=\"pagination\">";
		
		if ($h > 1) $pagination.= "<a href=\"".$targetpage.enkripsi($p."&h=".$prev)."\"><< Sebelumnya</a>";
		else $pagination.= "<span class=\"disabled\"><< Sebelumnya</span>";
		
		if ($lastpage < 7 + ($adjacents * 2))	
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $h) $pagination.= "<span class=\"current\">".$counter."</span>";
				else $pagination.= "<a href=\"".$targetpage.enkripsi($p."&h=".$counter)."\">".$counter."</a>";
			}
		}
		
		elseif ($lastpage > 5 + ($adjacents * 2))
		{
			if ($h < 1 + ($adjacents * 2))
			{
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
				{
					if ($counter == $h) $pagination.= "<span class=\"current\">".$counter."</span>";
					else $pagination.= "<a href=\"".$targetpage.enkripsi($p."&h=".$counter)."\">".$counter."</a>";
				}
				
				$pagination.= "...";
				$pagination.= "<a href=\"".$targetpage.enkripsi($p."&h=".$lpm1)."\">".$lpm1."</a>";
				$pagination.= "<a href=\"".$targetpage.enkripsi($p."&h=".$lastpage)."\">".$lastpage."</a>";
			}
			
			elseif ($lastpage - ($adjacents * 2) > $h && $h > ($adjacents * 2))
			{
				$pagination.= "<a href=\"".$targetpage.enkripsi($p."&h=1")."\">1</a>";
				$pagination.= "<a href=\"".$targetpage.enkripsi($p."&h=2")."\">2</a>";
				$pagination.= "...";
				
				for ($counter = $h - $adjacents; $counter <= $h + $adjacents; $counter++)
				{
					if ($counter == $h) $pagination.= "<span class=\"current\">".$counter."</span>";
					else $pagination.= "<a href=\"".$targetpage.enkripsi($p."&h=".$counter)."\">".$counter."</a>";
				}
				
				$pagination.= "...";
				$pagination.= "<a href=\"".$targetpage.enkripsi($p."&h=".$lpm1)."\">".$lpm1."</a>";
				$pagination.= "<a href=\"".$targetpage.enkripsi($p."&h=".$lastpage)."\">".$lastpage."</a>";
			}
			
			else
			{
				$pagination.= "<a href=\"".$targetpage.enkripsi($p."&h=1")."\">1</a>";
				$pagination.= "<a href=\"".$targetpage.enkripsi($p."&h=2")."\">2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $h) $pagination.= "<span class=\"current\">".$counter."</span>";
					else $pagination.= "<a href=\"".$targetpage.enkripsi($p."&h=".$counter)."\">".$counter."</a>";
				}
			}
		}
		
		if ($h < $counter - 1)  $pagination.= "<a href=\"".$targetpage.enkripsi($p."&h=".$next)."\">Selanjutnya >></a>";
		else $pagination.= "<span class=\"disabled\">Selanjutnya >></span>";
		
		$pagination.= "</div>";		
	}
?>

<script language="javascript">
	
	function del(q)
	{
		var r = confirm("Apakah Anda yakin ingin menghapus?");
		
		if (r == true)
		{
	  		window.location.href = "index.php?p="+q;
		}
	}

</script>

<form name="cari" method="post" action="index.php?p=<?php echo enkripsi($p."&q=".$q); ?>">
	<img src="css/images/view.gif" border="0" width="16" height="16" />
    <input type="text" name="c" size="40" value="<?php echo @$c; ?>" />
    <input type="submit" value="&nbsp; &nbsp; Cari &nbsp; &nbsp;" />
</form>

<table class="adminlist" cellpadding="1">
	<thead>
		<tr>
			<th width="4%">#</th>
			<th width="12%">Kode</th>
			<th>Nama</th>
			<th width="8%">Urutan</th>
			<th colspan="2" width="6%" nowrap="nowrap">
            	<a href="index.php?p=<?php echo enkripsi($addlink); ?>"><img src="css/images/add-icon.png" width="16" height="16" /> Tambah</a>
            </th>
		</tr>
	</thead>
	<tbody><?php
		
		if ($nlist == 0)
		{ ?>
			
            <tr><td align="center" colspan="6">Tidak ada data!</td></tr><?php
		
		}
		
		else
		{
			$i = 0;
			
			while ($list = mysql_fetch_array($olist))
			{
				if (@$_POST['c'])
				{
					$id_cari	= $list['kode'];
					$id_cari	= str_replace(strtolower($c), "<font color=\"blue\">".strtolower($c)."</font>", $id_cari);
					$id_cari	= str_replace(strtoupper($c), "<font color=\"blue\">".strtoupper($c)."</font>", $id_cari);
					$id_cari	= str_replace(ucfirst($c), "<font color=\"blue\">".ucfirst($c)."</font>", $id_cari);
					$id_cari	= str_replace(ucwords($c), "<font color=\"blue\">".ucwords($c)."</font>", $id_cari);
					$id[$i]		= $id_cari;
					
					$nama_cari	= $list['name'];
					$nama_cari	= str_replace(strtolower($c), "<font color=\"blue\">".strtolower($c)."</font>", $nama_cari);
					$nama_cari	= str_replace(strtoupper($c), "<font color=\"blue\">".strtoupper($c)."</font>", $nama_cari);
					$nama_cari	= str_replace(ucfirst($c), "<font color=\"blue\">".ucfirst($c)."</font>", $nama_cari);
					$nama_cari	= str_replace(ucwords($c), "<font color=\"blue\">".ucwords($c)."</font>", $nama_cari);
					$nama[$i]	= $nama_cari;
				}
				else
				{
					$id[$i]		= $list['kode'];
					$nama[$i]	= $list['name'];
				}
				
				$urutan[$i]		= $list['ordering'];
				
				if ($i % 2 == 1) $class = "row0";
				else $class = "row1"; ?>
				
                <tr class="<?php echo $class; ?>">
					<td align="center"><?php echo ($i + 1); ?></td>
					<td align="center"><?php echo $id[$i]; ?></td>
					<td><?php echo $nama[$i]; ?></td>
					<td align="center"><?php echo $urutan[$i]; ?></td>
					<td align="center">
						<a href="index.php?p=<?php echo enkripsi($edlink."&q=".$id[$i]); ?>" title="Ubah">
							<img src="css/images/edit_f2.png" border="0" width="16" height="16">
						</a>
					</td>
					<td align="center" title="Hapus">
                        <img src="css/images/stop_f2.png" border="0" width="16" height="16" 
                        onclick="del('<?php echo enkripsi($dellink."&q=".$id[$i]); ?>')">
					</td>
				</tr><?php
				
				$i++;
			}
		} ?>
        
	</tbody>
	<tfoot>
		<tr>
			<td colspan="6"><?php 
				
				if ($pagination == "") echo "&nbsp;";
				else echo $pagination; ?>
            
            </td>
		</tr>
	</tfoot>
</table>