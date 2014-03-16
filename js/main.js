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
        window.setTimeout(function(){document.getElementById("button_error").className = "hidden"}, 5000);
        getLists();
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

  var req = getXMLHttpRequest();
  req.onreadystatechange = function() { 
    if (req.readyState == 4 && (req.status == 200 || req.status == 0)) {
      getLists();
    }
  };
  req.open("GET", "del.php?del=" + id + "&tag=" + tag, true);
  req.send(null);
}


function playTrack(id) { 
  document.getElementById("div_iframe").className = "row";
  document.getElementById("iframe_sc").src = "https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/" + id + "&auto_play=true&visual=true";
}

function getLists() {

  var req = getXMLHttpRequest();
  req.onreadystatechange = function() { 
    if (req.readyState == 4 && (req.status == 200 || req.status == 0)) {
      document.getElementById("id_lists").innerHTML = "";
      var result = JSON.parse(req.responseText);
      var row = 0;
      var div_row = document.createElement("div");
      div_row.className = "row";
      for(var style in result) {
        if (result.hasOwnProperty(style)) {
          //colonne
          var div_col = document.createElement("div");
          div_col.className = "col-md-6";
          //titre
          var title = document.createElement("h4");
          title.innerHTML = style.substring(0,1).toUpperCase() + style.substring(1,style.length) + '<small>  - <a href="javascript:loadDel(\'\',\'' + style + '\')">Delete tag</a></small>';
          //debut liste 
          var div_list = document.createElement("ul");
          div_list.className = "list-unstyled";

         for(var track in result[style]) {
            var li_item = document.createElement("li");
            li_item.style = "margin-left: 5px";
            li_item.innerHTML = '<a href="javascript:playTrack(' + result[style][track]["id"] + ')">' + result[style][track]["title"] + '</a> <i>by</i> ' + result[style][track]["artist"]
            + "   " +
            '<a href="javascript:loadDel(' + result[style][track]["id"] + ',\'' + style + '\')"><span class="glyphicon glyphicon-remove"></span></a>';
            div_list.appendChild(li_item);
         }
         div_col.appendChild(title);
         div_col.appendChild(div_list);
         div_row.appendChild(div_col);

         if(++row == 2){
          document.getElementById("id_lists").appendChild(div_row);
          row = 0;
          div_row = document.createElement("div");
          div_row.className = "row";
         } 
        }
      }
      if(row != 0)
        document.getElementById("id_lists").appendChild(div_row);
    }

  };
  
  req.open("GET", "get_lists.php", true);
  req.send(null);
}