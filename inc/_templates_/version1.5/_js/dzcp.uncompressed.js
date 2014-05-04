// GLOBAL VARS
var doc = document, ie4 = document.all, opera = window.opera;
var innerLayer, layer, x, y, doWheel = false, offsetX = 15, offsetY = 5;
var tickerc = 0, mTimer = new Array(), tickerTo = new Array(), tickerSpeed = new Array();
var shoutInterval = 15000; // refresh interval of the shoutbox in ms
var teamspeakInterval = 15000; // refresh interval of the teamspeak viewer in ms
var isIE  = (navigator.appVersion.indexOf("MSIE") != -1) ? true : false;
var isWin = (navigator.appVersion.toLowerCase().indexOf("win") != -1) ? true : false;
var isOpera = (navigator.userAgent.indexOf("Opera") != -1) ? true : false;

// DZCP JAVASCRIPT LIBARY FOR JQUERY >= V1.9
var DZCP = {

  //init
    init: function() {
        doc.body.id = 'dzcp-engine-1.6';
        $('body').append('<div id="infoDiv"></div>');

        layer = $('#infoDiv')[0];
        doc.body.onmousemove = DZCP.trackMouse;

        // refresh shoutbox
        if($('#navShout')[0]) window.setInterval("$('#navShout').load('../inc/ajax.php?i=shoutbox');", shoutInterval);

        // refresh teamspeak
        if($('#navTeamspeakContent')[0]) window.setInterval("$('#navTeamspeakContent').load('../inc/ajax.php?i=teamspeak');", teamspeakInterval);

        // init lightbox
        DZCP.initLightbox();
    },

    // init lightbox
    initLightbox: function() {
      $('a[rel^=lightbox]').lightBox({
          fixedNavigation:      true,
          overlayBgColor:       '#000',
             overlayOpacity:       0.8,
            imageLoading:         '../inc/images/lightbox/loading.gif',
             imageBtnClose:        '../inc/images/lightbox/close.gif',
            imageBtnPrev:         '../inc/images/lightbox/prevlabel.gif',
             imageBtnNext:         '../inc/images/lightbox/nextlabel.gif',
            containerResizeSpeed: 350,
            txtImage:             (lng == 'de' ? 'Bild' : 'Image'),
             txtOf:                (lng == 'de' ? 'von' : 'of'),
            maxHeight: screen.height * 0.9,
            maxWidth: screen.width * 0.9
      });
    },

    // handle events
    addEvent : function(obj, evType, fn) {
      if(obj.addEventListener)
      {
        obj.addEventListener(evType, fn, false);
        return true;
      } else if (obj.attachEvent) {
        var r = obj.attachEvent('on' + evType, fn);
        return r;
      } else return false;
    },

    // track mouse
    trackMouse: function(e) {
        innerLayer = $('#infoInnerLayer')[0];
        if(typeof(layer) == 'object') {
        var ie4 = doc.all;
        var ns6 = doc.getElementById && !doc.all;
        var mLeft = 5;
        var mTop = -15;

        x = (ns6) ? e.pageX-mLeft : window.event.clientX+doc.documentElement.scrollLeft - mLeft;
        y = (ns6) ? e.pageY-mTop  : window.event.clientY+doc.documentElement.scrollTop  - mTop;

        if(innerLayer) {
            var layerW = ((ie4) ? innerLayer.offsetWidth : innerLayer.clientWidth) - 3;
          var layerH = (ie4) ? innerLayer.offsetHeight : innerLayer.clientHeight;

        } else {
            var layerW = ((ie4) ? layer.clientWidth : layer.offsetWidth) - 3;
          var layerH = (ie4) ? layer.clientHeight : layer.offsetHeight;
        }
            var winW   = (ns6) ? (window.innerWidth) + window.pageXOffset - 12
                     : doc.documentElement.clientWidth + doc.documentElement.scrollLeft;

            var winH   = (ns6) ? (window.innerHeight) + window.pageYOffset
                     : doc.documentElement.clientHeight + doc.documentElement.scrollTop;

          layer.style.left = ((x + offsetX + layerW >= winW - offsetX) ? x - (layerW + offsetX) : x + offsetX) + 'px';
          layer.style.top  = (y + offsetY) + 'px';
      }
        return true;
    },

    // handle popups
    popup: function(url, x, y) {
        x = parseInt(x); y = parseInt(y) + 50;
        popup = window.open(url, 'Popup', "width=1,height=1,location=0,scrollbars=0,resizable=1,status=0");
        popup.resizeTo(x, y);
        popup.moveTo((screen.width - x) / 2, (screen.height-y) / 2);
        popup.focus();
    },

  // init Gameserver via Ajax
    initGameServer: function(serverID) {
        DZCP.initDynLoader('navGameServer_' + serverID,'server','&serverID=' + serverID);
    },

  // init Teamspeakserver via Ajax
    initTeamspeakServer: function() {
        DZCP.initDynLoader('navTeamspeakServer','teamspeak','');
    },

    // init Ajax DynLoader
    initDynLoader: function(tag,menu,options) {
        var request = $.ajax({ url: "../inc/ajax.php?i=" + menu + options, type: "GET", data: {}, cache:true, dataType: "html", contentType: "application/x-www-form-urlencoded; charset=iso-8859-1" });
        request.done(function(msg) { $('#' + tag).html( msg ).hide().fadeIn("normal"); });
    },

    // submit shoutbox
    shoutSubmit: function() {
      $.post('../shout/index.php?ajax', $('#shoutForm').serialize(),function(req) {
        if(req) alert(req.replace(/  /g, ' '));
        $('#navShout').load('../inc/ajax.php?i=shoutbox');
        if(!req) $('#shouteintrag').prop('value', '');
      });

      return false;
    },

  // switch userlist
    switchuser: function() {
      var url = doc.formChange.changeme.options[doc.formChange.changeme.selectedIndex].value;
      window.location.href = url
    },

  // Templateswitch
    tempswitch: function() {
      var url = doc.form.tempswitch.options[doc.form.tempswitch.selectedIndex].value;
      if(url != 'lazy') DZCP.goTo(url);
    },

  // go to defined url
    goTo: function(url, n) {
      if(n == 1) window.open(url);
      else window.location.href = url
    },

  // limit text lenthn
    maxlength: function(field, countfield, max) {
        if(field.value.length > max) field.value = field.value.substring(0, max);
        else                         countfield.value = max - field.value.length;
    },

  // handle info layer
    showInfo: function(info, kats, text, img, width, height) {
      if(typeof(layer) == 'object')
      {
        var output = '';
        if(kats && text){
            var kat=kats.split(";");
            var texts=text.split(";");
            var katout = "";
            for(var i=0; i<kat.length; ++i) {
                  katout = katout + '<tr><td>'+kat[i]+'</td><td>'+texts[i]+'</td></tr>';
            }
            output = '<tr><td class="infoTop" colspan="2">'+info+'</td></tr>'+katout+'';
        }else if(kats && typeof(text)=="undefined"){
            output = '<tr><td class="infoTop" colspan="2">'+info+'</td></tr><tr><td>'+kats+'</td></tr>';
        }else{
            output = '<tr><td>'+info+'</td></tr>';
        }

        var userimg = "";
        if(img){
            userimg = '<tr><td colspan=2 align=center><img src="'+img+'" width="'+width+'" height="'+height+'" alt="" /></td></tr>';
        }else{
            userimg = '';
        }
        layer.innerHTML =
          '<div id="hDiv">' +
          '  <table class="hperc" cellspacing="0" style="height:100%">' +
          '    <tr>' +
          '      <td style="vertical-align:middle">' +
          '        <div id="infoInnerLayer">' +
          '          <table class="hperc" cellspacing="0">' +
          '              '+output+'' +
          '              '+userimg+'' +
          '          </table>' +
          '        </div>' +
          '      </td>' +
          '    </tr>' +
          '  </table>' +
          '</div>';

      //IE Fix
        if(ie4 && !opera)
        {
          layer.innerHTML += '<iframe id="ieFix" frameborder="0" width="' + $('#hDiv')[0].offsetWidth + '" height="' + $('#hDiv')[0].offsetHeight + '"></iframe>';
          layer.style.display = 'block';
        } else layer.style.display = 'block';
      }
    },

    // handle Steam layer
    showSteamBox: function(user, img, text, text2, status)
    {
        var class_state;
        switch(status) {
            case 1: class_state = 'online'; break; //Online
            case 2: class_state = 'in-game'; break; //Ingame
            default: class_state = 'offline'; break; //Offline
        }

        if(typeof(layer) == 'object')
        {
            layer.innerHTML =
              '<div id="hDiv">' +
              '  <table class="hperc" cellspacing="0" style="height:100%">' +
              '    <tr>' +
              '      <td style="vertical-align:middle">' +
              '        <div id="infoInnerLayer">' +
              '             <table class="steam_box_bg" border="0" cellspacing="0" cellpadding="0">' +
              '              <tr>' +
              '                <td>' +
              '                   <div class="steam_box steam_box_user '+class_state+'">' +
              '                     <div class="steam_box_avatar '+class_state+'"> <img src="'+img+'" /></div>' +
              '                     <div class="steam_box_content">'+user+'<br />' +
              '                     <span class="friendSmallText">'+text+'<br>'+text2+'</span></div>' +
              '                   </div>' +
              '                </td>' +
              '              </tr>' +
              '            </table>' +
              '        </div>' +
              '      </td>' +
              '    </tr>' +
              '  </table>' +
              '</div>';

            //IE Fix
            if(ie4 && !opera)
            {
                layer.innerHTML += '<iframe id="ieFix" frameborder="0" width="' + $('#hDiv')[0].offsetWidth + '" height="' + $('#hDiv')[0].offsetHeight + '"></iframe>';
                layer.style.display = 'block';
            }
            else
                layer.style.display = 'block';
        }
    },

    hideInfo: function() {
      if(typeof(layer) == 'object')
      {
        layer.innerHTML = '';
        layer.style.display = 'none';
      }
    },

  // toggle object
    toggle: function(id) {
      if(id == 0) return;
      else {
        if($('#more' + id).css('display') == 'none')
        {
            $('#more' + id).css('display', '');
            $('#img' + id).prop('src', '../inc/images/collapse.gif');
        } else {
            $('#more' + id).css('display', 'none');
          $('#img' + id).prop('src', '../inc/images/expand.gif');
        }
      }
    },
  // toggle with effect
      fadetoggle: function(id) {
        $("#more_"+id).fadeToggle("slow", "swing");
        if($('#img_'+id).prop('alt') == "hidden") {
            $('#img_'+id).prop({alt: 'normal',
                                src: '../inc/images/toggle_normal.png'});
        } else {
            $('#img_'+id).prop({alt: 'hidden',
                                src: '../inc/images/toggle_hidden.png'});
        }
    },
  // resize images
    resizeImages: function() {
        for(var i=0;i<doc.images.length;i++)
      {
        var d = doc.images[i];

        if(d.className == 'content')
        {
            var imgW = d.width;
            var imgH = d.height;

            if(maxW != 0 && imgW > maxW)
          {
                 d.width = maxW;
                d.height = Math.round(imgH * (maxW / imgW));

                if(!DZCP.linkedImage(d))
            {
              var textLink = doc.createElement("span");
                    var popupLink = doc.createElement("a");

              textLink.appendChild(doc.createElement("br"));
              textLink.setAttribute('class', 'resized');
              textLink.appendChild(doc.createTextNode('auto resized to '+d.width+'x'+d.height+' px'));

              popupLink.setAttribute('href', d.src);
              popupLink.setAttribute('rel', 'lightbox');
              popupLink.appendChild(d.cloneNode(true));

              d.parentNode.appendChild(textLink);
                    d.parentNode.replaceChild(popupLink, d);

              DZCP.initLightbox();
                }
          }
        }
        }
    },

    linkedImage: function(node) {
        do {
            node = node.parentNode;
            if (node.nodeName == 'A') return true;
        }
        while(node.nodeName != 'TD' && node.nodeName != 'BODY');

        return false;
    },

  // ajax calendar switch
    calSwitch: function(m, y) {
      $('#navKalender').load('../inc/ajax.php?i=kalender&month=' + m + '&year=' + y);
    },

  // ajax team switch
    teamSwitch: function(obj) {
      clearTimeout(mTimer[1]);
      $('#navTeam').load('../inc/ajax.php?i=teams&tID=' + obj, DZCP.initTicker('teams', 'h', 60));
    },

  // ajax vote
    ajaxVote: function(id) {
      DZCP.submitButton('contentSubmitVote');
      $.post('../votes/index.php?action=do&ajax=1&what=vote&id=' + id, $('#navAjaxVote').serialize(), function(req) {
        $('#navVote').html(req);
      });

      return false;
    },

  // ajax forum vote
    ajaxFVote: function(id) {
     DZCP.submitButton('contentSubmitFVote');
      $.post('../votes/index.php?action=do&fajax=1&what=fvote&id=' + id, $('#navAjaxFVote').serialize(), function(req) {
        $('#navFVote').html(req);
      });

       return false;
      },

  // ajax preview
    ajaxPreview: function(form) {
      var tag=doc.getElementsByTagName("textarea");
      for(var i=0;i<tag.length;i++)
      {
        var thisTag = tag[i].className;
        var thisID = tag[i].id;
        if(thisTag == "editorStyle" || thisTag == "editorStyleWord" || thisTag == "editorStyleNewsletter")
        {
          var inst = tinyMCE.getInstanceById(thisID);
          $('#' + thisID).prop('value', inst.getBody().innerHTML);
        }
      }

      $('#previewDIV').html('<div style="width:100%;text-align:center">'
                             + ' <img src="../inc/images/admin/loading.gif" alt="" />'
                             + '</div>');

      var addpars = "";
      if(form == 'cwForm') {
          $("input[type=file]").each(function() {
              addpars = addpars + "&" + $(this).prop('name') + "=" + $(this).prop('value');
          });
      }

      var url = prevURL;
      $.post(url, $('#' + form).serialize() + addpars, function(req) {
        $('#previewDIV').html(req);
      });
    },

  // confirm delete
    del: function(txt) {
      txt = txt.replace(/\+/g, ' ');
      txt = txt.replace(/oe/g, 'ö');

      return confirm(txt + '?');
    },



  // forum search
    hideForumFirst: function() {
      $('#allkat').prop('checked', false);
    },

    hideForumAll: function() {
        for(var i = 0; i < doc.forms['search'].elements.length; i++)
        {
            var box = doc.forms['search'].elements[i];

        if(box.id.match(/k_/g))
              box.checked = false;
        }
    },

  // disable submit button
    submitButton: function(id) {
      submitID = (id) ? id : 'contentSubmit';

      $('#' + submitID).prop("disabled", true);
      $('#' + submitID).css('color', '#909090');
      $('#' + submitID).css('cursor', 'default');

      return true;
    },

  // Newticker
    initTicker: function(objID, to, ms) {
     // set settings
      tickerTo[tickerc] = (to == 'h' || to == 'v') ? to : 'v';
      tickerSpeed[tickerc] = (parseInt(ms) <= 10) ? 10 : parseInt(ms);

     // prepare  object
      var orgData = $('#' + objID).html();
      var newData  = '  <div id="scrollDiv' + tickerc +'" class="scrollDiv" style="position:relative;left:0;z-index:1">';
          newData += '    <table id="scrollTable' + tickerc +'" class="scrolltable"  cellpadding="0" cellspacing="0">';
          newData += '      <tr>';
          newData += '        <td onmouseover="clearTimeout(mTimer[' + tickerc +'])" onmouseout="DZCP.startTickerDiv(' + tickerc +')">';
          for(var i=0;i<10;i++) newData += orgData;
          newData += '        </td>';
          newData += '      </tr>';
          newData += '    </table>';
          newData += '  </div>';

      $('#' + objID).html(newData);
     // start ticker
      window.setTimeout("DZCP.startTickerDiv("+tickerc+");",1500);
      tickerc++;
    },

    startTickerDiv: function(subID) {
      tableObj        = $('#scrollTable' + subID)[0];
      obj             = tableObj.parentNode;
      objWidth        = (tickerTo[subID] == 'h') ? tableObj.offsetWidth : tableObj.offsetHeight;
      newWidth        = (Math.floor(objWidth/2)*2)+2;
      obj.style.width = newWidth;

      mTimer[subID] = setInterval("DZCP.moveDiv('"+obj.id+"', " + newWidth + ", " + subID + ");", tickerSpeed[subID]);
    },

    moveDiv: function(obj, width, subID) {
      var thisObj = $('#' + obj)[0];
      if(tickerTo[subID] == 'h') thisObj.style.left = (parseInt(thisObj.style.left) <= (0-(width/2)+2)) ? 0 : parseInt(thisObj.style.left)-1 + 'px';
      else thisObj.style.top = (thisObj.style.top == '' || (parseInt(thisObj.style.top)<(0-(width/2)+6))) ? 0 : parseInt(thisObj.style.top)-1 + 'px';
    },

    //TS3 Settings
    TS3Settings: function(id) {
        if(id == 3) {
            $('#ts3settings').css('display', '');
        } else {
            $('#ts3settings').css('display', 'none');
        }
    }
}

// load global events
$(document).ready(function() { DZCP.init(); });
$(window).load(function() { DZCP.resizeImages(); });