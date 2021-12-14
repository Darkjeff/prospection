<?php
	global $db, $user;
	require '../../main.inc.php';
	dol_include_once('/custom/prospection/class/prospection.class.php');

	$ret=false;

	$rowid = GETPOST('rowid');
	$field = GETPOST('field');
	$value = GETPOST('value');

	if($field=='fk_stcomm'){
		$prospectionETY = new Prospection($db);
		$prospectionETY->rowid = $rowid;
		$prospectionETY->value = $value;
		$prospectionETY->field = $field;

		$ret = $prospectionETY->updateSociete();
	}else{
		//For prospection table, we use fk_soc instead of rowid
		$fk_soc = $rowid;

		$prospectionETY = new Prospection($db);
		$isExist = $prospectionETY->fetchBySoc($fk_soc);
		if(is_null($isExist) || !$isExist){
			$prospectionETY->fk_soc = $fk_soc;
			$isExist = $prospectionETY->initLine();
		}

		if($field=='date_relance' || $field=='comment'){
			$prospectionETY = new Prospection($db);
			$prospectionETY->rowid = $isExist;
			$prospectionETY->value = $value;
			$prospectionETY->field = $field;

			$ret = $prospectionETY->update();
		}
	}

	if ($ret>0){
		print 'ok | '.$ret;
	}
	else{
		print 'ko | '.$ret;
	}
?>