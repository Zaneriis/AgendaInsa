

<?php

	require('EventICal.php');
	require('FileIcsGenerator.php');
	$o = new EventICal();
	$a = new FileIcsGenerator();
	$o = $o->setStart('20200910100000')->setEnd('20200910110000')->setSummary('Espagnole')->setDescription('Espagnole');
	$a->setCalName('LEOPACARYTEST')->addEvent($o);

	$monfichier = fopen('test.ics', 'a+');

	$a->print($monfichier);

	fclose($monfichier);
?>
