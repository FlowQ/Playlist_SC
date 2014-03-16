<?php 
	if(strpos($_SERVER['HTTP_HOST'], 'localhost')!==false) {
		require_once ('config/config_dev.php'); //dev
	} else if(strpos($_SERVER['HTTP_HOST'], 'innovativepictures')!==false) {
		require_once ('config/config_OVH.php');
	} else {
		require_once ('config/config.php'); //prod
	} 

	$bdd = new PDO(DSN, DB_USERNAME, DB_PASSWORD);

	if(isset($_GET["del"]) && ($_GET["del"] != '') && isset($_GET["tag"]) && ($_GET["tag"] != '')) {
		$id = $_GET["del"];
		$tag = strtolower($_GET["tag"]);

		$req = $bdd->prepare("DELETE FROM title WHERE title_id = ".$id.' AND tag = "'.$tag.'"');

		$req->execute();
	} else {
		if(isset($_GET["tag"]) && ($_GET["tag"] != '')) {
			$tag = strtolower($_GET["tag"]);

			$req = $bdd->prepare('DELETE FROM title WHERE tag = "'.$tag.'"');
			$req->execute();
			$req = $bdd->prepare('DELETE FROM url WHERE tag = "'.$tag.'"');
			$req->execute();
		}
	}
	header('Location: index.php');

?>