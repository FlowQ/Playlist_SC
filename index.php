<?php 
  if(strpos($_SERVER['HTTP_HOST'], 'localhost')!==false) {
    require_once ('config/config_dev.php'); //dev
  } else if(strpos($_SERVER['HTTP_HOST'], 'innovativepictures')!==false) {
    require_once ('config/config_OVH.php');
  } else {
    require_once ('config/config.php'); //prod
  } 

  $bdd = new PDO(DSN, DB_USERNAME, DB_PASSWORD);
  $tags_req = $bdd->prepare("SELECT distinct(tag) FROM url");
  $tags_req->execute();

  $tags = $tags_req->fetchall(PDO::FETCH_COLUMN, 0);
?>

<!DOCTYPE html>
<html lang="en">
    <title>Add Soundcloud Playlists</title>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-theme.css" rel="stylesheet">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
<style>
label {
    display: block;
    margin-bottom: 5px;
}
label, input, button, select, textarea {
    font-size: 14px;
    font-weight: normal;
    line-height: 20px;
}
select {
    background-color: #FFFFFF;
    border: 1px solid #CCCCCC;
    width: 220px;
}
select, input[type="file"] {
    height: 30px;
    line-height: 30px;
}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" /> 
</head>
<body>
  <div class="container">
    <div class="page-header">  
      <div class="row">
        <h1>Add Soundcloud Playlists</h1>
        <p class="lead">Add it to your database</p>
      </div>
    </div>
    <div class="row">
      <form role="form" method="post" action="javascript:addURL()">
        <div id="div_url" class="form-group has-feedback">
          <label class="control-label" for="url">Playlist</label>
          <input type="url" class="form-control" id="url" placeholder="Enter URL playlist">
          <span id="span_glyph_url" class="glyphicon glyphicon-ok form-control-feedback hidden"></span>
        </div>

        <div id="div_tag" class="form-group has-feedback">
          <label for="tag">Tag</label>
          <input type="text" class="form-control" id="tag" placeholder="Enter Tag">
          <span id="span_glyph_tag" class="glyphicon glyphicon-ok form-control-feedback hidden"></span>
          <?php echo '<h4>';
          foreach ($tags as $value) {
            echo '<span class="label label-default" style="margin: 3px">'.ucfirst($value).'</span>';
          } 
            echo "</h4>";
          ?>
        </div>
        <div class="row">
          <div class="col-md-6">
            <button type="submit" class="btn btn-default">Submit</button>
          </div>
          <div class="col-md-6">
            <button id="button_error" type="button" class="btn btn-danger hidden pull-right" disabled="disabled"></button>
          </div>
        </div>
      </form>
   </div>
   <br>
   <br>
   <div class="row">
     <?php 
        $list = $bdd->prepare("SELECT title_id from title where tag = :tag GROUP BY title_id ORDER BY count(title_id) DESC");
        foreach ($tags as $value) {
          echo '<div class="col-md-4">';
          echo '<h4>'.ucfirst($value).'</h4>';
          $list->execute(array("tag" => $value));
          $tracks = $list->fetchall(PDO::FETCH_COLUMN, 0);
          for($i = 0 ; $i < min(3, count($tracks)) ; $i++) {
            echo '<div class="media">';
            echo '<iframe class="pull-left media-object" width="80%" height="100px" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/'.$tracks[$i].'&amp;auto_play=false&amp;hide_related=false&amp;show_artwork=true"></iframe>';
            echo '
                  <div class="media-body">
                    <button onclick="loadDel('.$tracks[$i].',\''.$value.'\')" type="button" class="btn btn-default btn-sm">
                      <span class="glyphicon glyphicon-remove"></span>
                    </button>
                  </div>
                </div>';
          }
          echo "</div>";
        }
     ?>
  </div>
  <br>
  <br>
  <br>
</body>

<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/main.js"></script>
<script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">

</html>
