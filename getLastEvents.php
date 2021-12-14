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
	$events = $prospectionETY->getLastEvents();

	if ($ret>0 || (is_countable($events) && sizeof($events)>0)){
		print '<table class="noborder noshadow">';
		print '<tr class="liste_titre">';
		print '<td>Date</td>';
		print '<td>Libellé</td>';
		print '<td>Note</td>';
		print '</tr>';
		foreach ($events as $event) {
			$dateC = date_create_from_format('Y-m-d H:i:s', $event['datec']);
			$dateC = date_format($dateC, 'd/m/y H:i');
			print '<tr '.$bc[$var].'>';
			print '<td>'.$dateC.'</td>';
			print '<td>'.$event['label'].'</td>';
			print '<td>'.$event['note'].'</td>';
			print '</tr>';
		}
		print '</table>';
	}
	else{
		print 'Aucun événement trouvé';
	}
?>