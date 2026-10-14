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

<body>
	<div class="container">
		<form action="php/code.php?lang=de" method="post">
			<h3 style="padding-top:2em;">
				<?php echo GetLangText('headline', 'Gedächtnisprotokoll'); ?>
			</h3>


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



			<div>
				<div>
					<div>
						<label id="course-exLabel">
							<?php echo GetLangText('module', 'Modul'); ?>
						</label>
					</div>
					<select style="align-self:center;" id="module" name="module">
						<?php
							foreach ($modules as $item) {
								echo '<option value="' . $item[0] . '">' . $item[1] . '</option>';
							}
						?>
					</select>
				</div>

				<p><?php echo GetLangText('missing', 'Du kannst deine Lehrveranstaltung nicht finden? Dann schreib uns einfach <a href="mailto:fsr@ifsr.de">eine Mail</a> oder <a href="https://github.com/fsr/kpp/blob/master/data/courses.txt">füge sie einfach selbst hinzu</a>.'); ?></p>
				<div>
					<div>
						<label>
							<?php echo GetLangText('examinant', 'Prüfer'); ?>
						</label>
					</div>
					<div>
						<input type="text" id="examinant" name="examinant" required/>
					</div>
				</div>

				<div style="clear: left;">
					<label id="course-sumLabel" for="review">
						<?php echo GetLangText('summary', 'Inhalt und Eindruck'); ?>
						<textarea id="review" name="review" cols="80" rows="5" required></textarea>
					</label><br/>

				</div>
			</div>

			<hr />

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
