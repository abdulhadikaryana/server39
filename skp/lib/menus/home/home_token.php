<?php
	#include("../../includes/dbh.php");
	#include("../../includes/functions.php");
	#include("../../includes/func_stemming.php");
	$kalimat = file_get_contents('home_text.txt', true);
	
	if (@$_POST['submit'])
	{
		$kalimat = $_REQUEST['kalimat'];
		$kata = tokenising($kalimat);
		$jml_kata = count($kata);
		echo '<strong>Kalimat :</strong>'.'<br>';
		echo $kalimat.'<br><br>';
		echo '<font color=blue> Hasil Tokenising </font>'.'<br>';
		//--------- tampilkan hasil Tokenising --------------------	
		for ($i=0;$i<$jml_kata;$i++){
				echo 'kata ke ' . ($i+1) .' ==> '.$kata[$i].'<br>';
		}
		echo '<strong>Jumlah kata ada : '.$i.'</strong><br>';
		exit();
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" />
		<title>Stemming</title>
	</head>
	
	<body>
		<p><strong>SIMULASI PROSES TOKENISING</strong></p>
		<p>Masukkan kata/kalimat:</p>
		<form method="post" action="index.php?p=52">
			<p>
			  <textarea name="kalimat" cols="75" rows="15"><?php echo $kalimat ?></textarea>
			  <br /><br />
			    <input name="submit" type="submit" value="Proses" />
          </p>
	</form>
		<p><?php echo @$output ?></p>
	</body>
</html>