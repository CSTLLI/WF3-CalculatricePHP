<!--**************************************************************************************-->
<!-- 																					  -->
<!-- Project: CalculatriceV3                            / $$      /$$ /$$$$$$$$ /$$$$$$   -->
<!--                  			                        | $$  /$ | $$| $$_____//$$__  $$  -->
<!-- resultat.php                                  	    | $$ /$$$| $$| $$     |__/  \ $$  -->
<!--                                                  	| $$/$$ $$ $$| $$$$$     /$$$$$/  -->
<!-- By: vcastell <valeriocastellipro@gmail.com>	    | $$$$_  $$$$| $$__/    |___  $$  -->
<!--                                              		| $$$/ \  $$$| $$      /$$  \ $$  -->
<!-- Created: 2022/02/15 10:41:35 vcastell   	        | $$/   \  $$| $$     |  $$$$$$/  -->
<!-- Updated: 2022/02/15 23:33:28 vcastell              |__/     \__/|__/      \______/   -->
<!--                                                                     				  -->
<!--**************************************************************************************-->

<!DOCTYPE html>
<html lang='fr'>
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">

		<title>Résultat</title>

		<meta name="description" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- Integration de Bootstrap -->
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	</head>

	<body class="container d-flex d-flex flex-column text-center justify-content-center align-items-center vh-100"></body>
</html>	

<?php
	//### Fonctions

	// fonction qui teste la gestion des erreurs sur la division
	function isDivide($number1, $number2){
		if ($number2 == 0){
			echo "Erreur sur la division.";
			return "DivideError";
		}else {
			return ($number1 / $number2);
		}
	}

	// fonction qui affiche le resultat s'il n'y a pas d'erreur
	function printResult($result){
		if ($result != "DivideError" && $result != "InvalidError"){
			echo "Le resultat est : " . $result;
		} else {
			echo "Erreur sur le calcul";
		}
	}

	//fonction qui stocke les données sur la Session Storage
	function stockResult($number1, $number2, $operator, $result){

		if(isset($_SESSION['historique'])){
			array_push($_SESSION['historique'], $number1 . $operator . $number2 . " = " . $result);
			
			echo "<h3>Historique :</h3>";
			echo "<ul>";

			if (count($_SESSION['historique']) > 6){
				$_SESSION['j']++;
			}

			for($i = count($_SESSION['historique']) - 1; $i > $_SESSION['j']; $i--){
				echo "<li>";
				echo $_SESSION["historique"][$i];
				echo "</li>";
			} 
	           
			echo "</ul>";

		};
		echo "<form method='post'>";
		echo "	<input type='submit' name='clear' value='Vider cet historique'/>";
		echo "</form>";
	}

	// ### MAIN

	// démarrage de la session
	session_start();

	// suppression de la session si on clique sur le bouton
	if(isset($_POST['clear']) && $_POST['clear'] !== ''){
        session_destroy();
        $_POST['clear'] = '';
    }

	if (isset($_POST['formule'])){

		$formule = $_POST['formule'];

		if (str_contains($formule, "+")){
			$values = explode("+", $formule);
			$signe = "+";
		} elseif (str_contains($formule, "-")){
			$values = explode("-", $formule);
			$signe = "-";
		} elseif (str_contains($formule, "*")){
			$values = explode("*", $formule);
			$signe = "*";
		} elseif (str_contains($formule, "/")){
			$values = explode("/", $formule);
			$signe = "/";
		}

		if (is_numeric($values[0]) && is_numeric($values[1])){
			// Declaration des variables
			$number1 = $values[0];
			$number2 = $values[1];

			// fait le calcul en fonction de l'operateur
			$result = match ($signe){
				'+' => $number1 + $number2,
				'-' => $number1 - $number2,
				'*' => $number1 * $number2,
				'/' => isDivide($number1, $number2),
			};			
		} else{
			$result = "InvalidError";
		}
	};

	if (is_numeric($result)){
		printResult($result);

		$_SESSION['historique'][0] = "Historique vide";

		stockResult($number1, $number2, $signe, $result);
	}

	echo "\n <a class='fs-3 fw-bold' href='index.php'>Revenir sur le calcul</a>";
?>