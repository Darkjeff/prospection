<?php
	global $db, $user;
	require '../../main.inc.php';
	dol_include_once('/custom/prospection/class/prospection.class.php');

	$ret=false;

	$fk_soc = GETPOST('rowid');

	$prospectionETY = new Prospection($db);
	$isExist = $prospectionETY->fetchBySoc($fk_soc);
	if(is_null($isExist) || !$isExist){
		$prospectionETY->fk_soc = $fk_soc;
		$ret = $prospectionETY->initLine();
	}else{
		$prospectionETY->fetch($isExist);
	}
	$prospectionETY->userid = $user->id;
	$ret = $prospectionETY->addEvent();

	if ($ret>0){
		print 'ok | '.$ret;
	}
	else{
		print 'ko | '.$ret;
	}
?>