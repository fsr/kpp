<?php
	$lang = 'de';
	if (isset($_GET['lang']))
		$lang = $_GET['lang'];

	$dict = array();	
	if (file_exists('lang/'.$lang.'.txt') == TRUE)
	{
		$tmpLang = explode("\n", file_get_contents('lang/'.$lang.'.txt'));
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
	
	// Parse Modules
	$modules = array();
	$tmp_modules = explode("\n", file_get_contents('data/modules.txt'));
	for ($i = 1; $i < count($tmp_modules); $i++)
	{
		if ($tmp_modules[$i] == '')
			continue;
		$modules[$i] = explode(";", $tmp_modules[$i]);
	}
	
	// parse courses
	$courses = array();
	$tmp_courses = explode("\n", file_get_contents('data/courses.txt'));
	for ($i = 1; $i < count($tmp_courses); $i++)
	{
		if ($tmp_courses[$i] == '')
			continue;
		$courses[$i] = explode(";", $tmp_courses[$i]);
	}
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
		<script type="text/javascript" language="JavaScript" src="js/main_js.js"></script>
		<link rel="stylesheet" href="style.css" type="text/css" />
	</head>
	<body onLoad="Checkbox_OnChanged(2); Checkbox_OnChanged(3); Checkbox_OnChanged(4);">
		<div class="main">
			<form action="php/code.php?lang=de" method="post">
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
							echo GetLangText('module', 'Modul');
						?>
					</p>
				</div>
				<div class="box">
					<select name="module">
						<?php
							foreach ($modules as $item)
								echo '<option value="'.$item[0].'">'.$item[1].'</option>';
						?>
					</select>
				</div>
				
				<div class="header">
					<p class="headerText">
						<?php
							echo GetLangText('genInfo', 'Allgemeine Informationen');
						?>
					</p>
				</div>
				<div class="box">
					<div class="item">
						<div class="firstItem">
							<label>
								<?php
									echo GetLangText('mainExa', 'Hauptprüfer');
								?>
							</label>
						</div>
						<div class="secondItem"><input type="text" id="examinant" name="examinant" required/></div>
					</div>
					<div class="item">
						<div class="firstItem">
							<label>
								<?php
									echo GetLangText('semAndYear', 'Semester und Jahr');
								?>
							</label>
						</div>
						<div class="secondItem">
							<select id="semesterselect" name="semesterselect" style="width:15%">
								<option value="SS">SS</option>
								<option value="WS">WS</option>
							</select>
							<input style="width: 80%;" type="text" id="year" name="year" value="2000" pattern="(?:19|20)[0-9]{2}" required/>
						</div>
					</div>
				</div>
				
				<div class="header">
					<p class="headerText">
						<?php
							echo GetLangText('courses', 'Lehrveranstaltungen');
						?>
					</p>
				</div>
				<div class="box">
					<div id="courses">
						<?php
							for ($i = 1; $i < 5; $i++)
							{
								echo '<div id="course'.$i.'" name="course'.$i.'">';
								echo '<div style="float:left; margin-top:2px;">';
								if ($i == 1)
									echo '<input type="checkbox" id="course'.$i.'checkbox" name="course'.$i.'checkbox" checked disabled />';
								else
									echo '<input type="checkbox" id="course'.$i.'checkbox" name="course'.$i.'checkbox" onChange="Checkbox_OnChanged('.$i.');"/>';
								echo '</div>';
								
								echo '<div align="right">';
								echo '<select class="courseSelect" id="course'.$i.'select" name="course'.$i.'select">';
								foreach ($courses as $item)
								{
									echo '<option value="'.$item[0].'">'.$item[1].'</option>';
								}
								echo '</select>';
								echo '</div>';
								
								echo '<div class="item">';
								echo '<div class="firstItem">';
								echo '<label id="course'.$i.'exLabel">';
								echo GetLangText('examinant', 'Prüfer');
								echo '</label>';
								echo '</div>';
								echo '<div class="secondItem">';
								if ($i == 1)
									echo '<input type="text" id="course'.$i.'examinant" name="course'.$i.'examinant" required/>';
								else
									echo '<input type="text" id="course'.$i.'examinant" name="course'.$i.'examinant"/>';
								echo '</div>';
								echo '</div>';
								
								echo '<div style="clear: left;">';
								echo '<label id="course'.$i.'sumLabel">';
								echo GetLangText('summary', 'Inhalt und Eindruck');
								echo '</label><br/>';
								if ($i == 1)
									echo '<textarea id="course'.$i.'review" name="course'.$i.'review" cols="80" rows="5" required></textarea>';
								else
									echo '<textarea id="course'.$i.'review" name="course'.$i.'review" cols="80" rows="5"></textarea>';
								echo '</div>';
								echo '<hr />';
								
								echo '</div>';
							}
						?>
						<p class="description">
							<?php
								echo GetLangText('courseDesc', 'Bitte die Checkboxen ankreuzen abhängig von der Anzahl der Veranstaltungen, welche geprüft wurden.');
							?>
						</p>
					</div>
				</div>
				<div class="header">
					<p class="headerText">
						<?php
							echo GetLangText('contact', 'Kontaktinformationen');
						?>
					</p>
				</div>
				<div class="box">
					<div>
						<div class="firstItem">
							<label>
								<?php
									echo GetLangText('email', 'Email-Adresse');
								?>
							</label>
						</div>
						<div class="secondItem"><input type="email" id="email" name="email"/></div>
					</div>
					<p class="description">
						<?php
							echo GetLangText('emailDesc', 'Die Angabe einer Email-Adresse ist freiwillig und dient nur fÃ¼r RÃ¼ckfragen. Sie wird nicht gespeichert oder an Dritte weitergegeben.');
						?>
					</p>
				</div>
				<input type="submit" value="Abschicken"/>
			</form>
		</div>
	</body>
</html>
