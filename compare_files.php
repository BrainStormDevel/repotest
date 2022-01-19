<?php

ini_set('memory_limit', '16M');

function compare_files($file1, $file2)
{
	try {
		$check = true; //Setto il controllo inizialmente a true
		if ((!file_exists($file1)) || (!file_exists($file2)) || (filetype($file1) != 'file') || (filetype($file2) != 'file')) {
			throw new \Exception("Errore NULL", 2); //Se il file non esiste oppure non è un file, genera eccezione
		}
		if (! $fp1 = fopen($file1, 'rb')) {
			throw new \Exception("Errore NULL", 2);	//"Apro" il file in modalità lettura binary in modo da non avere eventuali problemi di interpretazione di codici nelle righe, genera eccezione se il file non esiste o non si hanno i permessi per la lettura
		}
		if (! $fp2 = fopen($file2, 'rb'))					//"Apro" il file in modalità lettura binary in modo da non avere eventuali problemi di interpretazione di codici nelle righe
		{
			fclose($fp1);
			throw new \Exception("Errore NULL", 2);		//Genera eccezione di tipo null se il file non esiste o non si hanno i permessi per la lettura
		}
		while (!feof($fp1) and !feof($fp2))				//Fino a quando il puntatore di lettura dei bytes non arriva alla fine del primo file e del secondo file
			if (fread($fp1, 8192) !== fread($fp2, 8192))	//Confronto 8192 bytes di buffer del primo file con 8192 bytes di buffer del secondo file e, in caso fossero diversi, check viene assegnato a false
			{
				throw new \Exception("Errore False", 1);	//Genera eccezione perchè i files sono diversi
			}
		if (feof($fp1) !== feof($fp2)) {
			throw new \Exception("Errore False", 1);		//Se la fine del contenuto di file1 è diversa dalla fine del contenuto di file2
		}
		fclose($fp1);
		fclose($fp2);
	}
	catch (\Exception $e) {
		if ($e->getCode() == 1) {
			fclose($fp1);
			fclose($fp2);
			$check = false;
		}
		else {
			$check = null;
		}
	}
    return $check;	//Restituisco valore booleano oppure NULL
}

$file1 = 'D:\\file1.txt';		//File 1 esistente molto grande
$file2 = 'D:\\file2.txt';		//File 2 esistente molto grande diverso
$filevuoto = 'D:\\vuoto.txt';					//File 3 esistente ma vuoto
$fileinesistente = null;						//File non esistente
$fileinesistente2 = '';							//File non esistente
$filedir = 'C:\\';								//Directory

echo 'Eseguo test di comparazione dei due files diversi<br><br>';
var_dump(compare_files($file1, $file2));

echo '<br><br>Eseguo test di comparazione dei due files uguali<br><br>';
var_dump(compare_files($file1, $file1));

echo '<br><br>Eseguo test di comparazione dei due files di cui uno vuoto<br><br>';
var_dump(compare_files($file1, $filevuoto));

echo '<br><br>Eseguo test di comparazione dei due files di cui uno inesistente<br><br>';
var_dump(compare_files($fileinesistente, $file2));

echo '<br><br>Eseguo test di comparazione dei due files di cui uno inesistente v2<br><br>';
var_dump(compare_files($file1, $fileinesistente));

echo '<br><br>Eseguo test di comparazione dei due files di cui due inesistenti<br><br>';
var_dump(compare_files($fileinesistente, $fileinesistente2));

echo '<br><br>Eseguo test di comparazione dei due files di cui due inesistenti v2<br><br>';
var_dump(compare_files($fileinesistente, $fileinesistente));

echo '<br><br>Eseguo test di comparazione dei due files di cui due inesistenti v3<br><br>';
var_dump(compare_files($fileinesistente2, $fileinesistente2));

echo '<br><br>Eseguo test di comparazione dei due files di cui due vuoti<br><br>';
var_dump(compare_files($filevuoto, $filevuoto));

echo '<br><br>Eseguo test di comparazione di un file vuoto e una directory<br><br>';
var_dump(compare_files($filevuoto, $filedir));

echo '<br><br>Eseguo test di comparazione di due directory<br><br>';
var_dump(compare_files($filedir, $filedir));