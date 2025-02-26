<?php
$lang = 'de';
if (isset($_GET['lang'])) {
	$lang = $_GET['lang'];
}

$dict = [];
if (file_exists('lang/' . $lang . '.txt') == true) {
	$tmpLang = explode("\n", file_get_contents('lang/' . $lang . '.txt'));
	foreach ($tmpLang as $item) {
		$tmp = explode('=', $item);
		if (count($tmp) < 2) {
			continue;
		}
		$dict[trim($tmp[0])] = trim($tmp[1]);
	}
}

function GetLangText($key, $default)
{
	global $dict;
	if (array_key_exists($key, $dict) == false) {
		return $default;
	} else {
		return $dict[$key];
	}
}

// Parse Modules
$modules = [];
$tmp_modules = explode("\n", file_get_contents('data/modules.txt'));
for ($i = 1; $i < count($tmp_modules); $i++) {
	if ($tmp_modules[$i] == '') {
		continue;
	}
	$modules[$i] = explode(';', $tmp_modules[$i]);
}

// parse courses
$courses = [];
$tmp_courses = explode("\n", file_get_contents('data/courses.txt'));
for ($i = 1; $i < count($tmp_courses); $i++) {
	if ($tmp_courses[$i] == '') {
		continue;
	}
	$courses[$i] = explode(';', $tmp_courses[$i]);
}
?>
<!DOCTYPE html>
<html>

<head>
	<title>
		<?php echo GetLangText(
			'title',
			'Formular zum Eintragen von Prüfungsprotokollen'
		); ?>
	</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
	<script type="text/javascript" language="JavaScript" src="js/main_js.js"></script>
	<link rel="stylesheet" href="pico.min.css">
	<style>
		input[type=checkbox] {
			margin-bottom: 20px;
			margin-right: 20px;
		}
	</style>
</head>

<body onLoad="Checkbox_OnChanged(2); Checkbox_OnChanged(3); Checkbox_OnChanged(4);">
	<div class="container">
		<form action="php/code.php?lang=de" method="post">
			<h3 style="padding-top:2em;">
				<?php echo GetLangText('headline', 'Komplexprüfungsprotokolle'); ?>
			</h3>

			<label for="nudule">
				<?php echo GetLangText('module', 'Modul'); ?>
				<select name="module">
					<?php foreach ($modules as $item) {
						echo '<option value="' . $item[0] . '">' . $item[1] . '</option>';
					} ?>
				</select>
			</label>

			<h4>
				<?php echo GetLangText('genInfo', 'Allgemeine Informationen'); ?>
			</h4>
			<label for="examinant">
				<?php echo GetLangText('mainExa', 'Hauptprüfer'); ?>
				<input type="text" id="examinant" name="examinant" required />
			</label>
			<label for="semesterselect">
				<?php echo GetLangText('semAndYear', 'Semester und Jahr'); ?>
				<div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(0%, 1fr));">
					<select id="semesterselect" name="semesterselect">
						<option value="SS">Sommersemester</option>
						<option value="WS">Wintersemester</option>
					</select>
					<input type="number" id="year" name="year" value="<?= date('Y') ?>" pattern="(?:19|20)[0-9]{2}" required />
				</div>
			</label>


			<h4>
				<?php echo GetLangText('courses', 'Lehrveranstaltungen'); ?>
			</h4>
			<p><?php echo GetLangText('coursesInfo', 'Du kannst eine deiner Lehrveranstaltungen nicht finden? Dann schreib uns einfach <a href="mailto:fsr@ifsr.de">eine Mail</a> oder <a href="https://github.com/fsr/kpp/blob/master/data/courses.txt">füge sie einfach selbst hinzu</a>.'); ?></p>
			<?php for ($i = 1; $i < 5; $i++) {
				echo '<div id="course' . $i . '" name="course' . $i . '">';


				echo '<div style="display: flex;">';
				if ($i == 1) {
					echo '<input type="checkbox" style="align-self: center;" id="course' .
						$i .
						'checkbox" name="course' .
						$i .
						'checkbox" checked disabled />';
				} else {
					echo '<input type="checkbox" style="align-self: center;" id="course' .
						$i .
						'checkbox" name="course' .
						$i .
						'checkbox" onChange="Checkbox_OnChanged(' .
						$i .
						');"/>';
				}
				echo '<select style="align-self:center;" class="courseSelect" id="course' .
					$i .
					'select" name="course' .
					$i .
					'select">';
				foreach ($courses as $item) {
					echo '<option value="' . $item[0] . '">' . $item[1] . '</option>';
				}
				echo '</select>';
				echo '</div>';

				echo '<div class="item">';
				echo '<div class="firstItem">';
				echo '<label id="course' . $i . 'exLabel">';
				echo GetLangText('examinant', 'Prüfer');
				echo '</label>';
				echo '</div>';
				echo '<div class="secondItem">';
				if ($i == 1) {
					echo '<input type="text" id="course' .
						$i .
						'examinant" name="course' .
						$i .
						'examinant" required/>';
				} else {
					echo '<input type="text" id="course' .
						$i .
						'examinant" name="course' .
						$i .
						'examinant"/>';
				}
				echo '</div>';
				echo '</div>';

				echo '<div style="clear: left;">';
				echo '<label id="course' . $i . 'sumLabel" for="course' .
					$i .
					'review">';
				echo GetLangText('summary', 'Inhalt und Eindruck');
				if ($i == 1) {
					echo '<textarea id="course' .
						$i .
						'review" name="course' .
						$i .
						'review" cols="80" rows="5" required></textarea>';
				} else {
					echo '<textarea id="course' .
						$i .
						'review" name="course' .
						$i .
						'review" cols="80" rows="5"></textarea>';
				}
				echo '</label><br/>';

				echo '</div>';
				echo '<hr />';

				echo '</div>';
			} ?>
			<p class="description">
				<?php echo GetLangText(
					'courseDesc',
					'Bitte die Checkboxen ankreuzen abhängig von der Anzahl der Veranstaltungen, welche geprüft wurden.'
				); ?>
			</p>
			<h6>
				<?php echo GetLangText('contact', 'Kontaktinformationen'); ?>
			</h6>
			<label for="email">
				<?php echo GetLangText('email', 'Email-Adresse'); ?>
				<input type="email" id="email" name="email" />
				<small>
					<?php echo GetLangText(
						'emailDesc',
						'Die Angabe einer Email-Adresse ist freiwillig und dient nur fÃ¼r RÃ¼ckfragen. Sie wird nicht gespeichert oder an Dritte weitergegeben.'
					); ?>
				</small>
			</label>

			<input style="background-color:#648010" type="submit" value="Abschicken" />
		</form>
	</div>
	<footer class="container">
	      <small>Built with <a href="https://picocss.com">Pico</a> (MIT Licence)</small><br>
	      <small><a href="https://ifsr.de/impressum">Impressum</a> • <a href="https://ifsr.de/datenschutz">Datenschutz</a></small><br><br>
	      <small><a href="https://github.com/fsr/kpp">Source Code</a></small>
	</footer>
</body>

</html>
