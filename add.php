<?php 
	if(strpos($_SERVER['HTTP_HOST'], 'localhost')!==false) {
		require_once ('config/config_dev.php'); //dev
	} else if(strpos($_SERVER['HTTP_HOST'], 'innovativepictures')!==false) {
		require_once ('config/config_OVH.php');
	} else {
		require_once ('config/config.php'); //prod
	} 

	$bdd = new PDO(DSN, DB_USERNAME, DB_PASSWORD);

	if(isset($_GET["url"]) && ($_GET["url"] != '') && isset($_GET["tag"]) && ($_GET["tag"] != '')) {
		$url = $_GET["url"];
		$tag = strtolower($_GET["tag"]);
	} else {
		if($_GET["url"] == '') {
			echo "Add an URL";
		} else { 
			echo "Add a tag";
		}
		exit();
	}

	$result = json_decode(file_get_contents("http://api.soundcloud.com/resolve.json?url=" . $url . "&client_id=" . APP_ID));

	if(!isset($result->kind)) {
		echo "This is not a playlist";
		exit();
	}

	$id = $result->id;
	$title = $result->title;
	$req = $bdd->prepare("SELECT tag FROM url WHERE url_id = ".$id);
	$req->execute();
	$exists = $req->fetch(PDO::FETCH_COLUMN, 0);

	if(!$exists) {

		$req = $bdd->prepare("INSERT INTO title (title_id, title, artist, artist_id, tag) VALUES (:ti_id, :ti, :ar, :ar_id, :tag)");
		$rel = $bdd->prepare("INSERT INTO title_url (title_id, url_id) VALUES (:ti_id, :url_id)");
		$pll = $bdd->prepare("INSERT INTO url (url_id, url, tag, title) VALUES (:url_id, :url, :tag, :title)");

		$pll->execute(array("url_id" => $id, "url" => $url, "tag" => $tag, "title" => $title));

		foreach ($result->tracks as $titre) {
			$req->execute(array("ti_id" => $titre->id, "ti" => $titre->title, "ar" => $titre->user->username, "ar_id" => $titre->user->id, "tag" => $tag));
			$rel->execute(array("ti_id" => $titre->id, "url_id" => $id));
		}

		echo "Playlist added";
	} else {
		echo "Playlist already registered";
	}

	function viz($array) {
		echo "<pre>";
		var_export($array);
		echo "</pre>";
	}
?>