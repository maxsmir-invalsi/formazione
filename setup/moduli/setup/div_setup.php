<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

$p = array();

$q = " DROP DATABASE IF EXISTS formazione_db;";
$db->query($q, $p);

$q = "	CREATE DATABASE formazione_db;";
$db->query($q, $p);

$q = "	CREATE TABLE formazione_db.prodotti (id INT NOT NULL AUTO_INCREMENT , marca VARCHAR(50) NOT NULL , nome VARCHAR(50) NOT NULL , prezzo DOUBLE NOT NULL ,  PRIMARY KEY (id)) ENGINE = InnoDB;";
$db->query($q, $p);

$q = "	CREATE TABLE formazione_db.reparto (id INT NOT NULL AUTO_INCREMENT , nome VARCHAR(50) NOT NULL , PRIMARY KEY (id)) ENGINE = InnoDB;";
$db->query($q, $p);

$q = "	CREATE TABLE formazione_db.prodotti_reparto (prodotto INT NOT NULL , reparto INT NOT NULL , quantita INT NOT NULL , PRIMARY KEY (prodotto, reparto)) ENGINE = InnoDB;";
$db->query($q, $p);

$q = "	INSERT INTO formazione_db.prodotti (marca, nome, prezzo)
		VALUES 
			('MoltoBueno', 'Pizza Violetta', 4.5),
			('MoltoBueno', 'Pizza Scacciapensieri', 5.2),
			('MoltoBueno', 'Pizza Pizzicante', 7.1),
			('MoltoBueno', 'Pizza Prelibata', 4.5),
			('Sfindus', 'Filetti di Orazio', 4.5),
			('Sfindus', 'Filetti d''identici', 4.5),
			('Divelta', 'Pasta Rigatoni', 0.7),
			('Divelta', 'Pasta Liscioni', 0.7),
			('Divelta', 'Pasta Impastati', 0.8),
			('Multi', 'Sugo di pomodori scaduti', 3.4),
			('Multi', 'Sugo di patate marce', 3.5),
			('Multi', 'Sugo di sale', 3.5),
			('MulinoNero', 'Biscotti Acchiappasonni', 2.9),
			('MulinoNero', 'Biscotti Fuggisvegli', 3.1),
			('MulinoNero', 'Biscotti Lenticole', 2.5),
			('AlceBianco', 'Biscotti Svelticole', 3.6),
			('AlceBianco', 'Biscotti Ammorbanti', 3.8),
			('LatteriaItalia', 'Latte Intero', 1.2),
			('LatteriaItalia', 'Latte A Meta', 0.6),
			('LatteriaItalia', 'Latte A Un Quarto', 0.3),
			('Sfindus', 'Sofficelli Pomodoro', 3.2),
			('Sfindus', 'Sofficelli Pomodargento', 3.0)
			;";
$db->query($q, $p);


$q = "	INSERT INTO formazione_db.reparto (nome)
		VALUES 
			('Surgelati'),
			('Primi piatti'),
			('Colazione')
			;";
$db->query($q, $p);

$q = "	INSERT INTO formazione_db.prodotti_reparto (reparto, prodotto, quantita)
		VALUES 
			(1, 1, 11),
			(1, 2, 12),
			(1, 3, 13),
			(1, 4, 14),
			(1, 5, 15),
			(1, 6, 16),
			(2, 7, 27),
			(2, 8, 28),
			(2, 9, 29),
			(2, 10, 20),
			(2, 11, 21),
			(2, 12, 22),
			(3, 13, 33),
			(3, 14, 34),
			(3, 15, 35),
			(3, 16, 36),
			(3, 17, 37),
			(3, 18, 38),
			(3, 19, 39),
			(3, 20, 30),
			(1, 21, 15),
			(1, 22, 17)
			;";
$db->query($q, $p);

// raccolgo i dati dei prodotti
$q = "	SELECT  a.marca, a.nome, a.prezzo, b.quantita, c.nome AS reparto
		FROM    formazione_db.prodotti a JOIN
				formazione_db.prodotti_reparto b ON a.id = b.prodotto JOIN
				formazione_db.reparto c ON b.reparto = c.id
		ORDER BY a.nome;";

$prodotti_dettagli = $db->query($q, $p);

$html_prodotti = '   <table>
						<tr><th colspan="5">DATI DEI PRODOTTI</th></tr>
						<tr><td colspan="5" style="border:none;"></td></tr>
						<tr>
							<th>Marca</th>
							<th>Prodotto</th>
							<th>Prezzo</th>
							<th>Quantità</th>
							<th>Reparto</th>
						</tr>';
foreach ($prodotti_dettagli as $info) {
	$html_prodotti .= '   <tr>
							<td>' . $info['marca'] . '</td>
							<td>' . $info['nome'] . '</td>
							<td>' . $info['prezzo'] . ' €</td>
							<td>' . $info['quantita'] . '</td>
							<td>' . $info['reparto'] . '</td>
						</tr>';
}

$html_prodotti .= '   </table>';

//raccolgo le informazioni sulle tabelle
$q = "	SELECT 	TABLE_SCHEMA, TABLE_NAME, COLUMN_NAME, DATA_TYPE, CHARACTER_MAXIMUM_LENGTH, COLUMN_KEY, EXTRA
		FROM 	INFORMATION_SCHEMA.COLUMNS
		WHERE 	TABLE_SCHEMA='formazione_db'
		ORDER BY 	CASE 
						WHEN TABLE_NAME = 'prodotti' THEN 1
						WHEN TABLE_NAME = 'reparto' THEN 2
						ELSE 3
					END, ORDINAL_POSITION;";

$prodotti_dettagli = $db->query($q, $p);

$html_tabelle = '   <table class="special" style="position: fixed; top: 50%; transform: translateY(-50%); ">
						<tr><th colspan="3">TABELLE DEL DATABASE</th></tr>
						<tr><td colspan="3" style="border:none;"></td></tr>';
$tabella = '';

foreach ($prodotti_dettagli as $info) {
	if ($info['TABLE_NAME'] != $tabella) {
		if($tabella!=''){$html_tabelle .= '<tr><td colspan="3" style="border:none;">&nbsp;</td></tr>';}

		$tabella = $info['TABLE_NAME'];

		$html_tabelle .= ' <tr>
								<th colspan="3">' . $info['TABLE_SCHEMA'] . '.' . $info['TABLE_NAME'] . '</th>
							</tr>
							<tr>
								<th>Colonna</th>
								<th>Tipo di dati</th>
								<th>Info extra</th>
							</tr>';
	}
	$html_tabelle .= '  <tr>
							<td><b>' . $info['COLUMN_NAME'] . '</b></td>
							<td>' . $info['DATA_TYPE'] . ($info['CHARACTER_MAXIMUM_LENGTH'] ? '(' . $info['CHARACTER_MAXIMUM_LENGTH'] . ')' : '') . '</td>
							<td>' . ($info['COLUMN_KEY'] == 'PRI' ? 'CHIAVE PRIMARIA' : '') . ' ' . $info['EXTRA'] . '</td>
						</tr>';
}

$html_tabelle .= '   </table>';



echo '  <div class="info center padded" style="border-radius: 20px; border: 2px solid orange; top: 50%; left: 50%; transform: translate(-50%, -50%); padding: 20px; position: absolute; background-color: white;">
			Database creato e popolato!
		</div>

		<div class="table" style="display:none;">' . $html_prodotti . '</div>
		<div class="" style="position: fixed;">' . $html_tabelle . '</div>
		
		';


