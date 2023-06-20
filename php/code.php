<?php
	$lang = 'de';
	if ($_GET['lang'])
		$lang = $_GET['lang'];

	$dict = array();	
	if (file_exists('../lang/'.$lang.'.txt') == TRUE)
	{
		$tmpLang = explode("\n", file_get_contents('../lang/'.$lang.'.txt'));
		foreach($tmpLang as $item)
		{
			$tmp = explode("=", $item);
			if (count($tmp) < 2)
				continue;
			$dict[trim($tmp[0])] = trim($tmp[1]);
		}
	}

	function GetLangText($key, $default)
	{
		global $dict;
		if (array_key_exists($key, $dict) == FALSE)
			return $default;
		else
			return $dict[$key];
	}
	
	$logText = '';
	$linkAccept = 'https://www.ifsr.de/kpp/php/accept.php?';
	$linkDecline = 'https://www.ifsr.de/kpp/php/decline.php?';
	
	if($_POST['email']==""){
		$mail = "";
	} else {
		$mail = "\n\nBei Rückfragen bitte an ".$_POST['email']." wenden.";
	}
	

	$head = "Modul: ".$_POST['module'].
	"\nSemester: ".$_POST['semesterselect'].
	" ".$_POST['year'].
	"\nPrüfer: ".$_POST['examinant'].
	"\n";
	
	function CreateCourseFile($courseNr)
	{
		global $linkAccept;
		global $linkDecline;
		global $head;
		global $logText;
		
		$fileName = trim($_POST['course'.$courseNr.'select']).time();

		$input = "\nLehrveranstaltung: ".$_POST['course'.$courseNr.'select'].
			"\nPrüfer: ".$_POST['course'.$courseNr.'examinant'].
			"\n".$_POST['course'.$courseNr.'review'];
		
		if (!is_dir('/var/lib/kpp/unverified/'))
			mkdir('/var/lib/kpp/unverified/');
		file_put_contents('/var/lib/kpp/unverified/'.$fileName, $head.$input);
		
		$linkAccept = $linkAccept.'file'.$courseNr.'='.urlencode($fileName).'&'.
			'dir'.$courseNr.'='.urlencode(trim($_POST['course'.$courseNr.'select'])).'&';
		$linkDecline = $linkDecline.'file'.$courseNr.'='.urlencode($fileName).'&';
		
		$logText = $logText.$input;
	}
	
	foreach ($_POST as $key => $item)
	{
		if(strpos($key,'mail')===false && strlen($item)==0)
		{
			echo GetLangText('incompleteError', 'Bitte alle Felder ausfüllen!');
			exit();
		}
	}
	
	for ($i = 1; $i <= 4; $i++)
	{
		if ($i != 1 && isset($_POST['course'.$i.'checkbox']) == FALSE)
			continue;
		CreateCourseFile($i);
	}
	
	$header = "Content-type: text/plain; charset=utf-8" ."\n";
	$header .= 'From: noreply@ifsr.de' . "\n";
	
	$mReturn = mail('komplexpruef@ifsr.de', 'Komplexpruefungsprotokoll',
	"Ein neues Komplexpruefungsprotokoll mit folgendem Inhalt wurde eingesendet:\n".
	$head.$logText.
	$mail.
	"\n\nFolgender Link bestätigt das Protokoll und macht es öffentlich zugänglich:\n".
	$linkAccept.
	"\n\nSollte das Protokoll nicht den Anfoderungen entsprechen, kann es mit dem folgenden Link gelöscht werden:\n".
	$linkDecline.
	"\n\nMit freundlichen Größen,\nProtokollBot",
	$header);
	
?>	
<!DOCTYPE html>
<html>
	<head>
		<title>
			<?php
				echo GetLangText('title', 'Formular zum Eintragen von Prüfungsprotokollen');
			?>
		</title>
		<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
		<link rel="stylesheet" href="../style.css" type="text/css" />
	</head>
	<body>
		<div class="main">
			<div>
				<p class="headline">
					<?php
							echo GetLangText('headline', 'Komplexprüfungsprotokolle');
					?>
				</p>
			</div>
			<div class="header">
				<p class="headerText">
					<?php
						echo GetLangText('thankYou', 'Vielen Dank!');
					?>
				</p>
			</div>
			<div class="box">
				<p>
					<?php
						echo GetLangText('protocolSuccess', 'Dein Protokoll wird geprüft und so schnell wie möglich hochgeladen.');
					?>
				</p>
			</div>
		</div>
	</body>
</html>
