<?php
checkauthentication();
$addlink = 95;
	$omenu = xmenu("parent", "id = '".$p."'");
	$xmenu = mysql_fetch_array($omenu);
	$p_next = $xmenu['parent'];
	$session_name = "Kh41r4";
	$usernamex = @$_SESSION['xusername_'.$session_name];
//	$table = "suratkeluar";
//	$field = get_field($table);
	
//	$omenu = xmenu("parent", "id = '".$p."'");
//	$xmenu = mysql_fetch_array($omenu);
//	$p_next = $xmenu['parent'];

	
	$q = ekstrak_get(@$get[1]);
	$r = ekstrak_get(@$get[2]);
	//echo $q;
	//echo $r;
	
		//$olist = mysql_query("SELECT * FROM suratkeluar WHERE IdSuratKeluar = $q ") or die(mysql_error());
		//$nlist = mysql_num_rows($olist);

/*@session_start();

include("inc.php");
include('lib/func.fileupload.php');

CheckAuthentication();
extract($_GET);
$newpg=$_GET['pg'];
$bError		= false;
$sMessage	= "";

if ($_SESSION['MM__AdminRole'] <> 5) {
	*/
$oSuratBaru = mysql_query("SELECT * FROM suratmasuk WHERE NoSurat = '".$q."' AND DicatatOleh = '".$susername."'" ) or die(mysql_error());

	if (mysql_num_rows($oSuratBaru) == 0) {
		$bError		= true;
		$sMessage	= "Invalid Member ID Request...!!";
	} else {
		$Edit	= mysql_fetch_object($oSuratBaru);
							$StatusDisposisi	= $Edit->StatusDisposisi;
							$StatusBaca			= $Edit->StatusBaca;
							
		$Disposisi = "Terakhir dibaca oleh " . $susername . " pada " . date( " d F Y  H:i:s");
		if (($StatusDisposisi == "0") AND ($StatusBaca == "0") ) { 
		mysql_query("UPDATE suratmasuk SET 
									Disposisi		 = '".$Disposisi ."',
									StatusBaca = 1 
									WHERE IdSuratMasuk = '".$_GET["id"]."'") or die(mysql_error());
				  } 				
	}
//}

if ($_POST["Submit"]) {
	$TglSurat			= $_POST["TglSurat"];
	$NoSurat			= $_POST["NoSurat"];
	$TglTerima			= $_POST["TglTerima"];
	$AsalSurat	 		= $_POST["AsalSurat"];
	$Perihal			= $_POST["Perihal"];
	$TujuanSurat		= $_POST["TujuanSurat"];
  	$IdSifatSurat		= $_POST["IdSifatSurat"];
	$IdKategoriSurat	= $_POST["IdKategoriSurat"];
	$IdKlasifikasiSurat	= $_POST["IdKlasifikasiSurat"];
	$Lampiran			= $_POST["Lampiran"];
	$LokasiFile			= $_POST["LokasiFile"];
	$Keterangan			= $_POST["Keterangan"];


	


	if ($AsalSurat == "") {
		$bError		 = true;
		$sMessage	.= ". Masukkan Asal Surat dengan benar<br />";
	}
	
	if ($Perihal == "") {
		$bError		 = true;
		$sMessage	 .= ". Masukkan Perihal dengan benar<br />";
	}
	if ($IdSifatSurat == "") {
		$bError		 = true;
		$sMessage	 .= ". Pilih Sifat Surat dengan benar<br />";
	}
	if ($IdKategoriSurat == "") {
		$bError		 = true;
		$sMessage	 .= ". Pilih Kategori Surat dengan benar<br />";
	}
	if ($IdKlasifikasiSurat == "") {
		$bError		 = true;
		$sMessage	 .= ". Pilih Klasifikasi Surat dengan benar<br />";
	}
	

	if ($bError != true) {
		if ($q == "") {
			/* ADD NEW
			*/
			mysql_query("INSERT INTO suratmasuk( IdSifat, IdKategori, IdKlasifikasi, TglTerima, NoSurat, TglSurat, AsalSurat, Perihal, Lampiran, TujuanSurat, Disposisi, Retensi, LokasiFile, Keterangan) 
								  VALUES ('".$IdSifatSurat."', '".$IdKategoriSurat."', 
								  		  '".$IdKlasifikasiSurat."', '".$TglTerima."', '".$NoSurat."', 
										  '".$TglSurat."', '".$AsalSurat."', '".$Perihal."', 
										  '".$Lampiran."', '".$TujuanSurat."', '".$Disposisi."',
										  '".$Retensi."', '".$LokasiFile."', '".$Keterangan."')");

			//echo "<meta http-equiv=\"refresh\" content=\"0;URL=update_success.php?act=add_surat_masuk\">";
			exit();
		} else {
			// UPDATE
				mysql_query("UPDATE suratmasuk SET 
							IdSifat 			= '".$IdSifat."',
							IdKategori			= '".$IdKategori."',
							IdKlasifikasi		= '".$IdKlasifikasi."', 
							TglTerima			= '".$TglTerima."',
							NoSurat				= '".$NoSurat."', 
							TglSurat			= '".$TglSurat."', 
							AsalSurat			= '".$AsalSurat."', 
							Perihal				= '".$Perihal."', 
							Lampiran			= '".$Lampiran."', 
							TujuanSurat			= '".$TujuanSurat."', 
							Retensi				= '".$Retensi."', 
							LokasiFile			= '".$LokasiFile."', 
							TujuanSurat			= '".$TujuanSurat."', 
							Keterangan			= '".Keterangan."'
							WHERE IdSuratMasuk = '".$_GET["id"]."'") or die(mysql_error());
				//echo "<meta http-equiv=\"refresh\" content=\"0;URL=update_success.php?act=update_user\">";
				exit();
			
		}
	}
} else if ($q != "") {
	$oEdit = mysql_query("SELECT * FROM suratmasuk WHERE NoSurat = '".$q."'") or die(mysql_error());

	if (mysql_num_rows($oEdit) == 0) {
		$bError		= true;
		$sMessage	= "Invalid Member ID Request...!!";
	} else {
		$Edit	= mysql_fetch_object($oEdit);
							$IdSifat 			= $Edit->IdSifat;
							$IdKategori			= $Edit->IdKategori;
							$IdKlasifikasi		= $Edit->IdKlasifikasi; 
							$TglTerima			= $Edit->TglTerima;
							$NoSurat			= $Edit->NoSurat; 
							$TglSurat			= $Edit->TglSurat; 
							$AsalSurat			= $Edit->AsalSurat; 
							$Perihal			= $Edit->Perihal; 
							$Lampiran			= $Edit->Lampiran; 
							$TujuanSurat		= $Edit->TujuanSurat;
							$Retensi			= $Edit->Retensi; 
							$LokasiFile			= $Edit->LokasiFile; 
							$TujuanSurat		= $Edit->TujuanSurat; 
							$Keterangan			= $Edit->Keterangan;
							$tl					= $Edit->tl;
							$tl_status			= $Edit->tl_status;
							$tglawal			= $Edit->tglawal;
							$tglakhir			= $Edit->tglakhir;
	}
}
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>User Edit - <?=$Site_Name?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="calendar/skins/aqua/theme.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="calendar/calendar.js"></script>
<script language="JavaScript" src="calendar/lang/calendar-en.js"></script>
<script language="JavaScript" src="calendar/calendar-setup.js"></script>
<script>
var halamanbaru;
function poptastic(url)
{
	halamanbaru=window.open(url,'Surat','height=600,width=800');
	if (window.focus) {halamanbaru.focus()}
}
</script>
<body>
<table class="admintable">
<form method="post" name="frmAdministrator" enctype="multipart/form-data" action="index.php?p=<?php echo enkripsi($addlink."&q=".$nosurat[$i]); ?>">
     
                <tr> 
                  <td class="key"><strong>Tanggal Surat</strong></td>
                  <td> <input name="TglSurat" type="text" readonly  class="form" id="TglSurat" value="<?=ViewDateTimeFormat($TglSurat,6)?>" size="30"></td>
                </tr>
                <tr> 
                  <td class="key"><strong>No. Surat</strong></td>
                  <td><input name="NoSurat" type="text" readonly  class="form" id="NoSurat" value="<?=$NoSurat?>" size="50"></td>
                </tr>
                <tr> 
                  <td class="key"><strong>Diterima Tanggal</strong></td>
                  <td> <input name="TglTerima" type="text" readonly  class="form" id="TglTerima" value="<?=ViewDateTimeFormat($TglTerima,6)?>" size="30"></td>
                </tr>
                <tr> 
                  <td class="key"><strong>Asal Surat</strong></td>
                  <td><input name="AsalSurat" readonly type="text" class="form" id="AsalSurat" value="<?=$AsalSurat?>" size="50"></td>
                </tr>
                <tr> 
                  <td class="key"><strong>Isi Ringkas</strong></td>
                  <td><textarea name="Perihal" cols="50" readonly id="Perihal" class="form"><?=$Perihal?></textarea></td>
                </tr>
                <tr> 
                  <td class="key"><strong>Tujuan Surat</strong></td>
                  <td><input name="TujuanSurat" readonly type="text" class="form" id="TujuanSurat" value="<?=$TujuanSurat?>" size="50"></td>
                </tr>
                <tr> 
                  <td class="key"><strong>Sifat Surat </strong></td>
                  <td width="72%"><input name="IdSifat" type="text" class="formAll" id="IdSifat" size="30" readonly value="<?=GetSifatSurat($IdSifat)?>"/></td>
                </tr>
                <tr> 
                  <td class="key"><strong>Kategori Surat </strong></td>
                  <td width="72%"><input name="IdKategori" type="text" class="formAll" id="IdKategori" size="30" readonly value="<?=GetKategoriSurat($IdKategori)?>"/></td>
                </tr>
                <tr> 
                  <td class="key"><strong>Klasifikasi Surat </strong></td>
                  <td width="72%"><input name="IdKlasifikasi" type="text" class="formAll" id="IdKlasifikasi" size="30" readonly value="<?=GetKlasifikasiSurat($IdKlasifikasi)?>"/></td>
                </tr>
                <tr> 
                  <td class="key"><strong>Lampiran</strong></td>
                  <td><input name="Lampiran" readonly type="text" class="form" id="Lampiran" value="<?=$Lampiran?>" size="50"></td>
                </tr>
                <tr> 
                  <td class="key"><strong>File Upload :</strong></td>
                  <td align="left" valign="top" class="fontred"> 
      
                    <?php
					$oFile = mysql_query("SELECT * FROM lokasifile WHERE NoSurat = '".$NoSurat."'") or die(mysql_error());
					if (mysql_num_rows($oFile) == 0) {
                  	?>
              		<tr align="center" bgcolor="#FFFFFF">
                		<td class="fontred"><strong>[No Data Found]</strong></td>
              		</tr>
              		<?php
                    } else {
                        $i	= 1;
                         while($List = mysql_fetch_object($oFile)) {
                    ?>
                    <a href="javascript:poptastic('/sitpdik/files/<?=$List->NamaFile?>');">
                	
                  	<?=$i?>
                	.
                  	<?=$List->NamaFile;?>
				  	</a><br />
				  	<?php
                	$i++;
                        }
                  	}
					?>
                  
                  </td>
                </tr>
                <tr> 
                  <td class="key"><strong>Keterangan</strong></td>
                  <td><input name="Keterangan" readonly type="text" class="form" id="Keterangan" value="<?=$Keterangan?>" size="50"></td>
                </tr>
                
                
               <!-- <tr> 
                  <td class="key"><strong>Perlu Tindak Lanjut</strong></td>
                  <td>
                    <p>
                  <label>
                    <input type="radio" name="tl" value="1" <?php if ($tl == 1) echo "checked=\"checked\""; ?> id="yes" disabled>
                    Ya</label>
                 
                  <label>
                    <input type="radio" name="tl" value="0" <?php if ($tl == 0) echo "checked=\"checked\""; ?> id="no" disabled>
                    Tidak</label>
              
              </p>
                  </td>
                </tr>-->
               <!-- 
                <tr> 
                  <td class="key"><strong>Status Tindak Lanjut</strong></td>
                  <td>
                    <p>
                  <label>
                    <input type="radio" name="tl_status" value="1" <?php if ($tl_status == 1) echo "checked=\"checked\""; ?> id="status1" disabled>
                    Sudah</label>
                 
                  <label>
                    <input type="radio" name="tl_status" value="0" <?php if ($tl_status == 0) echo "checked=\"checked\""; ?> id="status2" disabled >
                    Belum</label>
                    
                     <label>
                    <input type="radio" name="tl_status" value="2" <?php if ($tl_status == 2) echo "checked=\"checked\""; ?> id="status2" disabled >
                    Tidak Perlu Tindak Lanjut</label>
                  
                  </td>
                </tr>
                <tr> 
                  <td class="key"><strong>Tanggal Akhir Tindak Lanjut</strong></td>
                  <td>
                    <input name="date_from" type="text" class="form" id="date_from" size="10" value="<?=$tglawal?>" readonly/>&nbsp;
	          
	          &nbsp; s.d &nbsp;
	          
	          <input name="date_until" type="text" class="form" id="date_until" size="10" value="<?=$tglakhir?>" readonly/>&nbsp;
	          
                  </td>
                </tr>
                -->
               
                <tr> 
                  <td width="26%" align="center" valign="top">&nbsp;</td>
                  <td colspan="2">
                  			
				<div class="button2-right">
					<div class="prev">
						<a onClick="Cancel('index.php?p=<?php echo enkripsi($p_next); ?>')">Batal</a>
					</div>
				</div>
                
				<div class="clr"></div>
				<input type="submit" style="border: 0pt none ; margin: 0pt; padding: 0pt; width: 0px; height: 0px;" value="Simpan" />
				<input type="hidden" name="suratmasuk" value="1" />
				<input type="hidden" name="id" value="<?php echo $q; ?>" />
			</td>
                  
                  </td>
              </tr>
              
                  
      </form>
        
</table>
</body>
</html>
