<?php
	checkauthentication();
	$table = "ref_kerjasama";
	$field = get_field($table);
	$err = false;
	$p = $_GET['p'];
	$pagess = $_REQUEST['pagess'];
	extract($_POST);
	$xusername_sess = $_SESSION['xusername'];
	
	$xmenu_p = xmenu_id($p);
	$p_next = $xmenu_p->parent;
	
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
				$sql = sql_insert($table,$field,$value);
				$rs = mysql_query($sql);
				
				if ($rs) {
					update_log($sql,$table,1);
					$_SESSION['errmsg'] = "Input data berhasil!";
				}
				else {
					update_log($sql,$table,0);
					$_SESSION['errmsg'] = "Input data gagal!";
				} ?>
				
				<meta http-equiv="refresh" content="0;URL=index.php?p=<?php echo $p_next ?>&pagess=<?php echo $pagess ?>"><?php
				exit();
			} 
			else {
				// UPDATE
				$sql = sql_update($table,$field,$value);
				$rs = mysql_query($sql);
				
				if ($rs) {	
					update_log($sql,$table,1);
					$_SESSION['errmsg'] = "Ubah data berhasil!";
				}
				else {
					update_log($sql,$table,0);
					$_SESSION['errmsg'] = "Ubah data gagal!";			
				} ?>
				
				<meta http-equiv="refresh" content="0;URL=index.php?p=<?php echo $p_next ?>&pagess=<?php echo $pagess ?>"><?php
				exit();
			}
		}
		else {?>
			<meta http-equiv="refresh" content="0;URL=index.php?p=<?php echo $p_next ?>&pagess=<?php echo $pagess ?>"><?php		
		}
	} 
	else if (isset($_GET["q"])) {
		$value = get_value($table,$field,"id='".$_GET['q']."'");
	}
	else {
		$value = "";
	} ?>

<form action="index.php?p=<?php echo $_GET['p'] ?>&pagess=<?php echo $pagess ?>" method="post" name="form">
	<table width="643" cellspacing="1" class="admintable">
		
		<tr>
		  <td class="key">No urut </td>
		  <td colspan="3"><input type="text" name="<?php echo $field[1] ?>" size="5" value="<?php echo $value[1] ?>" /></td>
	  </tr>
		<tr>
		  <td width="142" class="key">Unsur Kerjasama</td>
		  <td width="486" colspan="3"><input type="text" name="<?php echo $field[2] ?>" size="70" value="<?php echo $value[2] ?>" /></td>
	  </tr>
		
		<tr>
		  <td class="key">Bobot</td>
		  <td colspan="3"><input type="text" name="<?php echo $field[3] ?>" size="10" value="<?php echo $value[3] ?>" /></td>
	  </tr>
		
		<tr>
			<td>&nbsp;</td>
			<td colspan="3">			
				<div class="button2-right">
					<div class="prev">
						<a onclick="Cancel('index.php?p=<?php echo $p_next ?>&pagess=<?php echo $pagess ?>')">Batal</a>					</div>
				</div>
				<div class="button2-left">
					<div class="next">
						<a onclick="form.submit();">Simpan</a>					</div>
				</div>
				<div class="clr"></div>
				<input style="border: 0pt none ; margin: 0pt; padding: 0pt; width: 0px; height: 0px;" value="Simpan" type="submit">
				<input name="form" type="hidden" value="1" />
				<input name="q" type="hidden" value="<?php echo $_GET['q'] ?>" />			</td>
		</tr>
  </table>
</form>