/**
 * base must be the path where you installed the gallery (ex. /gallery ).
 * empty if you installed it in  your document root.
 * it is auto set in the setLinks function, do not edit.
 */

var base = ''

/**
 * on document load set observers on links
 */

document.observe("dom:loaded", function() { setLinks();});

/**
 * morph height of main div to accomodate the images
 */

function morphMain() {
		$('main').morph('height:'+$('container').getStyle('height'), {
			afterFinish: function() { 	
				$('main').removeClassName('loader');
				$('container').fade({from: 0, to:1, duration:0.7});
			}});
}

/**
 * on click event load ajax content and show it with effects
 */

function respondToClick(event) { 
	var element = event.element();
	if(!element.readAttribute('href')) element=element.up();
	var tmp_url = element.readAttribute('href');
	
	/**
	 * IE6 needs this
	 */
	var tmp = new RegExp('^https?:\/\/');
	if(tmp.test(tmp_url)) {
		tmp_url = element.readAttribute('href').substring(7);	
		tmp_url = tmp_url.substring(tmp_url.indexOf('/'));	
	}
	
	var url = 'ajax/'+tmp_url.substr(base.length);
	
	$('main').setStyle({height: $('main').getStyle('height')});	
	$('container').fade({from: 1, to:0, duration:0.7, 
		afterFinish: function() {
			$('main').addClassName('loader');
			
  	  		new Ajax.Request(url, {
  	  			method:'get',
  	  			onSuccess: function(transport) {
  	  				var text = transport.responseText;
  	  				$('container').update(text);
					$('container').setOpacity(0);
					$('container').show();
  					if($('flickr-photo')==null) {
  	  					$('main').setStyle({height: 'auto'});
						$('main').removeClassName('loader');
  	  					$('container').fade({from: 0, to:1, duration:0.7});
  	  				}
  	  				setLinks();
  	  			},
  	  	   		onFailure: function(transport) {
  	  	   			$('main').removeClassName('loader');			
  	  	   			$('container').show();
  	  	   		}
  	  		});
		}});
	Event.stop(event);
}

/** 
 * get base url and observe clicks on links
 */

function setLinks() {
	a = $$("base");
	var url = a[0].readAttribute('href').substring(7);	
	base = url.substring(url.indexOf('/'));

	a = $$("div.photoset a");
	for(i=0;i<a.length;i++) { a[i].observe("click", respondToClick); }	
	
	a = $$("div.collection a");
	for(i=0;i<a.length;i++) { a[i].observe("click", respondToClick); }
	
	a = $$("div#thumbnails a");
	for(i=0;i<a.length;i++) { a[i].observe("click", respondToClick); }	
			
	a = $$("p#navigation a");
	for(i=0;i<a.length;i++) { a[i].observe("click", respondToClick); }

	a = $$("div#footer a");
	for(i=0;i<a.length;i++) { a[i].observe("click", respondToClick); }

	if($("flickr-photo")!=null) $("flickr-photo").observe("load", morphMain);
}