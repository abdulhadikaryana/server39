<?php
	checkauthentication();
	$table = "th_rkt";
	$field = array("id","th","kdunitkerja","no_rkt","nm_rkt","no_iku","no_sasaran","target","sub_rkt"); 
	$err = false;
	$p = $_GET['p'];
	$q = $_GET['q'];
	extract($_POST);
	$xusername_sess = $_SESSION['xusername'];
	$xlevel = $_SESSION['xlevel'];
	$xkdunit = $_SESSION['xkdunit'];
	$th = $_SESSION['xth']+1;
	$renstra = th_renstra($th);

	$xmenu_p = xmenu_id($p);
	$p_next = $xmenu_p->parent;
	
	$oList = mysql_query("select * from $table WHERE id = '$q' ");
	$List = mysql_fetch_array($oList);
	$kdunitkerja = $List['kdunitkerja'];
	
	if (isset($form)) {		
		if ($err != true) {
			$lastmodified = now();
			$modifiedby = $xusername_sess;
			$id = $q;
			
			foreach ($field as $k=>$val) {
				$value[$k] = $$val;
			}
			
			if ($q == "") {
				//ADD NEW
				$th = $th ;
				$kdunitkerja = $_REQUEST['kdunitkerja'];
				$no_iku = $_REQUEST['no_ikk'];
				$no_rkt = $_REQUEST['no_rkt'];
				$nm_rkt = $_REQUEST['nm_pk'];
				$target = $_REQUEST['target'];
				$sub_rkt = $_REQUEST['sub_rkt'];
				$no_sasaran = nosasaran_iku($th,$kdunitkerja,$no_iku);
				$sql = "INSERT INTO $table ( id, th, kdunitkerja, no_iku, no_rkt, nm_rkt, target, no_sasaran,sub_rkt)
						VALUES ('','$th','$kdunitkerja','$no_iku','$no_rkt','$nm_rkt','$target','$no_sasaran','$sub_rkt') ";
//				$sql = sql_insert($table,$field,$value);
				$rs = mysql_query($sql);
				
				if ($rs) {
					update_log($sql,$table,1);
					$_SESSION['errmsg'] = "Input data berhasil!";
				}
				else {
					update_log($sql,$table,0);
					$_SESSION['errmsg'] = "Input data gagal!";
				} ?>
				
				<meta http-equiv="refresh" content="0;URL=index.php?p=<?php echo $p_next ?>&xkdunit=<?php echo $_REQUEST['xkdunit'] ?>"><?php
				exit();
			} 
			else {
				// UPDATE
				$th = $th ;
				$kdunitkerja = $_REQUEST['kdunitkerja'];
				$no_iku = $_REQUEST['no_ikk'];
				$no_rkt = $_REQUEST['no_rkt'];
				$nm_rkt = $_REQUEST['nm_pk'];
				$target = $_REQUEST['target'];
				$sub_rkt = $_REQUEST['sub_rkt'];
				$no_sasaran = nosasaran_iku($th,$kdunitkerja,$no_iku);
				$sql = "UPDATE $table SET th = '$th', kdunitkerja = '$kdunitkerja', no_iku = '$no_iku', no_rkt = '$no_rkt', nm_rkt = '$nm_rkt', target = '$target', no_sasaran = '$no_sasaran', sub_rkt = '$sub_rkt' WHERE id = '$q'";
//				$sql = sql_update($table,$field,$value);
				$rs = mysql_query($sql);
				if ($rs) {	
					update_log($sql,$table,1);
					$_SESSION['errmsg'] = "Ubah data berhasil!";
				}
				else {
					update_log($sql,$table,0);
					$_SESSION['errmsg'] = "Ubah data gagal!";			
				} ?>
				
				<meta http-equiv="refresh" content="0;URL=index.php?p=<?php echo $p_next ?>&xkdunit=<?php echo $_REQUEST['xkdunit'] ?>"><?php
				exit();
			}
		}
		else {?>
			<meta http-equiv="refresh" content="0;URL=index.php?p=<?php echo $p_next ?>&xkdunit=<?php echo $_REQUEST['xkdunit'] ?>"><?php		
		}
	} 
	else if (isset($_GET["q"])) {
		$value = get_value($table,$field,"id='".$_GET['q']."'");
	}
	else {
		$value = "";
	} ?>

<script language="javascript" src="js/autocombo.js"></script>
<form action="index.php?p=<?php echo $_GET['p'] ?>&xkdunit=<?php echo $_REQUEST['xkdunit'] ?>" method="post" name="form">
	
  <table width="985" cellspacing="1" class="admintable">
    <tr> 
      <td class="key">Tahun</td>
      <td><input type="text" name="th" size="10" value="<?php echo $th ?>" /></td>
    </tr>
    <tr>
      <td class="key">Eselon I</td>
      <td><select name="kdunitkerja" onchange="get_iku_deputi(this.value,<?php echo $th ?>)">
	  	<?php if ( $q <> '' ) { ?>
        <option value="<?php echo $List['kdunitkerja'] ?>"><?php echo  $List['kdunitkerja'].' '.trim(ket_unit($List['kdunitkerja'])) ?></option>
		<?php } ?>
          <option value="">-- Pilih Eselon I --</option>
          <?php
		  switch ( $xlevel )
		  {
		  		case '4':
					$sql = "SELECT kdunit,left(ket_unit_kerja,50) as nmunit FROM tb_unitkerja where kdunit = '$xkdunit' ORDER BY kdunit";
				break;
		  		default:
					$sql = "SELECT kdunit,left(ket_unit_kerja,50) as nmunit FROM tb_unitkerja where kdunit <> '820000' and right(kdunit,3) = '000' ORDER BY kdunit";
				break;
		   }
					$oUnit = mysql_query($sql);
					while ($Unit = mysql_fetch_array($oUnit))
					{ ?>
        <option value="<?php echo $Unit['kdunit'] ?>"><?php echo  $Unit['kdunit'].' '.trim($Unit['nmunit']) ?></option>
          <?php
					} ?>
        </select></td>
    </tr>
    <tr> 
      <td class="key">IKU</td>
      <td><div id="iku-deputi-view"><select name="no_ikk">
        <option value="<?php echo $List['no_iku'] ?>"><?php echo  $List['no_iku'].' '.substr(nm_iku($renstra,$kdunitkerja,$List['no_iku']),0,70) ?></option>
          <option value="" style="width:500px;">-- Pilih IKU --</option>
          <?php
	  $sql = "SELECT * FROM m_iku WHERE ta = '$renstra' and kdunitkerja = '$kdunitkerja' ORDER BY no_sasaran,no_iku";
					$oIKK = mysql_query($sql);
					while ($IKK = mysql_fetch_array($oIKK))
					{ ?>
          <option value="<?php echo $IKK['no_iku']; ?>" style="width:500px;" <?php if ($IKK['no_iku'] == $List['no_ikk']) echo "selected"; ?>><?php echo $IKK['no_iku'].' '.substr($IKK['nm_iku'],0,70) ?></option>
          <?php
					} ?>
        </select></div></td>
    </tr>
    
    <tr> 
      <td class="key">No. Urut </td>
      <td><input type="text" name="no_rkt" size="5" value="<?php echo $List['no_rkt'] ?>" />&nbsp;&nbsp;<font color="#FF66CC">[isi : 1,2,3,...dst sesuai urutan yang diinginkan]</font></td>
    </tr>
    <tr> 
      <td class="key">Kinerja</td>
      <td><div id="indikator-deputi-view"><textarea name="nm_pk" rows="3" cols="50"><?php echo $List['nm_rkt'] ?></textarea></div></td>
    </tr>
    <tr>
      <td class="key">Target</td>
      <td><input type="text" name="target" size="30" value="<?php echo $List['target'] ?>" /></td>
    </tr>
    <tr>
      <td class="key">Ada Sub Kinerja</td>
      <td>
	  <input name="sub_rkt" type="radio" value="0" <?php if( $List['sub_rkt'] == 0 ) echo 'checked="checked"' ?>/> 
        &nbsp;&nbsp;Tidak&nbsp;&nbsp;
	  	<input name="sub_rkt" type="radio" value="1" <?php if( $List['sub_rkt'] == 1 ) echo 'checked="checked"' ?>/> 
        &nbsp;&nbsp;Ya&nbsp;&nbsp;
	  </td>
    </tr>
    
    <tr> 
      <td>&nbsp;</td>
      <td> <div class="button2-right"> 
          <div class="prev"> <a onclick="Cancel('index.php?p=<?php echo $p_next ?>&xkdunit=<?php echo $_REQUEST['xkdunit'] ?>')">Batal</a>          </div>
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