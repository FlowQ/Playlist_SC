function getXMLHttpRequest() {
  var xhr = null;

  if (window.XMLHttpRequest || window.ActiveXObject) {
    if (window.ActiveXObject) {
      try {
        xhr = new ActiveXObject("Msxml2.XMLHTTP");
      } catch(e) {
        xhr = new ActiveXObject("Microsoft.XMLHTTP");
      }
    } else {
      xhr = new XMLHttpRequest(); 
    }
  } else {
    alert("Pas d'Ajax, dommage!");
    return null;
  }

  return xhr;
}

//asynchronous call to the update process
function addURL() {
  var req = getXMLHttpRequest();

  var url = document.getElementById("url").value;
  var tag = document.getElementById("tag").value;

  req.onreadystatechange = function() {
    if (req.readyState == 4 && (req.status == 200 || req.status == 0)) {
      if(req.responseText == "Playlist added") {
        document.getElementById("div_url").className = "form-group has-feedback";
        document.getElementById("div_tag").className = "form-group has-feedback";
        document.getElementById("span_glyph_url").className = "hidden";
        document.getElementById("span_glyph_tag").className = "hidden";
        window.setTimeout(function(){window.location.reload()}, 1500);

        document.getElementById("button_error").className = "btn btn-success pull-right";
      } else {
        if(req.responseText == "Add a tag") {
          document.getElementById("div_tag").className = "form-group has-feedback has-error";
          document.getElementById("span_glyph_tag").className = "glyphicon glyphicon-remove form-control-feedback";
          document.getElementById("span_glyph_url").className = "glyphicon glyphicon-ok form-control-feedback";
          document.getElementById("div_url").className = "form-group has-feedback has-success";  
        } else {
          document.getElementById("div_url").className = "form-group has-feedback has-error";  
          document.getElementById("span_glyph_url").className = "glyphicon glyphicon-remove form-control-feedback";
          document.getElementById("span_glyph_tag").className = "hidden";
          document.getElementById("div_tag").className = "form-group has-feedback";
        }
        document.getElementById("button_error").className = "btn btn-danger pull-right";
      }
      document.getElementById("button_error").innerHTML = req.responseText;
    }
  };
  
  req.open("GET", "add.php?url=" + url + "&tag=" + tag, true);
  req.send(null);
}

function loadDel(id, tag) {
  window.location.href = "del.php?del="+id+"&tag="+tag;
}
