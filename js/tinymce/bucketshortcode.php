<?php
// Hook into Wordpress so we can use all the custom functions and global variables
$file = dirname(__FILE__);
$file = substr($file, 0, stripos($file, "wp-content") );
require( $file . "/wp-load.php");
?>
<!doctype html>
<html>
<head>
	<title>Bucket Shortcodes</title>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script>
		//QuickSearch
		(function($,window,document,undefined){$.fn.quicksearch=function(target,opt){var timeout,cache,rowcache,jq_results,val='',e=this,options=$.extend({delay:100,selector:null,stripeRows:null,loader:null,noResults:'',matchedResultsCount:0,bind:'keyup',onBefore:function(){return},onAfter:function(){return},show:function(){this.style.display=""},hide:function(){this.style.display="none"},prepareQuery:function(val){return val.toLowerCase().split(' ')},testQuery:function(query,txt,_row){for(var i=0;i<query.length;i+=1){if(txt.indexOf(query[i])===-1){return false}}return true}},opt);this.go=function(){var i=0,numMatchedRows=0,noresults=true,query=options.prepareQuery(val),val_empty=(val.replace(' ','').length===0);for(var i=0,len=rowcache.length;i<len;i++){if(val_empty||options.testQuery(query,cache[i],rowcache[i])){options.show.apply(rowcache[i]);noresults=false;numMatchedRows++}else{options.hide.apply(rowcache[i])}}if(noresults){this.results(false)}else{this.results(true);this.stripe()}this.matchedResultsCount=numMatchedRows;this.loader(false);options.onAfter();return this};this.search=function(submittedVal){val=submittedVal;e.trigger()};this.currentMatchedResults=function(){return this.matchedResultsCount};this.stripe=function(){if(typeof options.stripeRows==="object"&&options.stripeRows!==null){var joined=options.stripeRows.join(' ');var stripeRows_length=options.stripeRows.length;jq_results.not(':hidden').each(function(i){$(this).removeClass(joined).addClass(options.stripeRows[i%stripeRows_length])})}return this};this.strip_html=function(input){var output=input.replace(new RegExp('<[^<]+\>','g'),"");output=$.trim(output.toLowerCase());return output};this.results=function(bool){if(typeof options.noResults==="string"&&options.noResults!==""){if(bool){$(options.noResults).hide()}else{$(options.noResults).show()}}return this};this.loader=function(bool){if(typeof options.loader==="string"&&options.loader!==""){(bool)?$(options.loader).show():$(options.loader).hide()}return this};this.cache=function(){jq_results=$(target);if(typeof options.noResults==="string"&&options.noResults!==""){jq_results=jq_results.not(options.noResults)}var t=(typeof options.selector==="string")?jq_results.find(options.selector):$(target).not(options.noResults);cache=t.map(function(){return e.strip_html(this.innerHTML)});rowcache=jq_results.map(function(){return this});val=val||this.val()||"";return this.go()};this.trigger=function(){this.loader(true);options.onBefore();window.clearTimeout(timeout);timeout=window.setTimeout(function(){e.go()},options.delay);return this};this.cache();this.results(true);this.stripe();this.loader(false);return this.each(function(){$(this).on(options.bind,function(){val=$(this).val();e.trigger()})})}}(jQuery,this,document));
	
		//TINYMCE
		var tinymce=null,tinyMCEPopup,tinyMCE;tinyMCEPopup={init:function(){var b=this,a,c;a=b.getWin();tinymce=a.tinymce;tinyMCE=a.tinyMCE;b.editor=tinymce.EditorManager.activeEditor;b.params=b.editor.windowManager.params;b.features=b.editor.windowManager.features;b.dom=b.editor.windowManager.createInstance("tinymce.dom.DOMUtils",document);if(b.features.popup_css!==false){b.dom.loadCSS(b.features.popup_css||b.editor.settings.popup_css)}b.listeners=[];b.onInit={add:function(e,d){b.listeners.push({func:e,scope:d})}};b.isWindow=!b.getWindowArg("mce_inline");b.id=b.getWindowArg("mce_window_id");b.editor.windowManager.onOpen.dispatch(b.editor.windowManager,window)},getWin:function(){return(!window.frameElement&&window.dialogArguments)||opener||parent||top},getWindowArg:function(c,b){var a=this.params[c];return tinymce.is(a)?a:b},getParam:function(b,a){return this.editor.getParam(b,a)},getLang:function(b,a){return this.editor.getLang(b,a)},execCommand:function(d,c,e,b){b=b||{};b.skip_focus=1;this.restoreSelection();return this.editor.execCommand(d,c,e,b)},resizeToInnerSize:function(){var a=this;setTimeout(function(){var b=a.dom.getViewPort(window);a.editor.windowManager.resizeBy(a.getWindowArg("mce_width")-b.w,a.getWindowArg("mce_height")-b.h,a.id||window)},10)},executeOnLoad:function(s){this.onInit.add(function(){eval(s)})},storeSelection:function(){this.editor.windowManager.bookmark=tinyMCEPopup.editor.selection.getBookmark(1)},restoreSelection:function(){var a=tinyMCEPopup;if(!a.isWindow&&tinymce.isIE){a.editor.selection.moveToBookmark(a.editor.windowManager.bookmark)}},requireLangPack:function(){var b=this,a=b.getWindowArg("plugin_url")||b.getWindowArg("theme_url");if(a&&b.editor.settings.language&&b.features.translate_i18n!==false&&b.editor.settings.language_load!==false){a+="/langs/"+b.editor.settings.language+"_dlg.js";if(!tinymce.ScriptLoader.isDone(a)){document.write('<script type="text/javascript" src="'+tinymce._addVer(a)+'"><\/script>');tinymce.ScriptLoader.markDone(a)}}},pickColor:function(b,a){this.execCommand("mceColorPicker",true,{color:document.getElementById(a).value,func:function(e){document.getElementById(a).value=e;try{document.getElementById(a).onchange()}catch(d){}}})},openBrowser:function(a,c,b){tinyMCEPopup.restoreSelection();this.editor.execCallback("file_browser_callback",a,document.getElementById(a).value,c,window)},confirm:function(b,a,c){this.editor.windowManager.confirm(b,a,c,window)},alert:function(b,a,c){this.editor.windowManager.alert(b,a,c,window)},close:function(){var a=this;function b(){a.editor.windowManager.close(window);tinymce=tinyMCE=a.editor=a.params=a.dom=a.dom.doc=null}if(tinymce.isOpera){a.getWin().setTimeout(b,0)}else{b()}},_restoreSelection:function(){var a=window.event.srcElement;if(a.nodeName=="INPUT"&&(a.type=="submit"||a.type=="button")){tinyMCEPopup.restoreSelection()}},_onDOMLoaded:function(){var b=tinyMCEPopup,d=document.title,e,c,a;if(b.domLoaded){return}b.domLoaded=1;if(b.features.translate_i18n!==false){c=document.body.innerHTML;if(tinymce.isIE){c=c.replace(/ (value|title|alt)=([^"][^\s>]+)/gi,' $1="$2"')}document.dir=b.editor.getParam("directionality","");if((a=b.editor.translate(c))&&a!=c){document.body.innerHTML=a}if((a=b.editor.translate(d))&&a!=d){document.title=d=a}}if(!b.editor.getParam("browser_preferred_colors",false)||!b.isWindow){b.dom.addClass(document.body,"forceColors")}document.body.style.display="";if(tinymce.isIE){document.attachEvent("onmouseup",tinyMCEPopup._restoreSelection);b.dom.add(b.dom.select("head")[0],"base",{target:"_self"})}b.restoreSelection();b.resizeToInnerSize();if(!b.isWindow){b.editor.windowManager.setTitle(window,d)}else{window.focus()}if(!tinymce.isIE&&!b.isWindow){tinymce.dom.Event._add(document,"focus",function(){b.editor.windowManager.focus(b.id)})}tinymce.each(b.dom.select("select"),function(f){f.onkeydown=tinyMCEPopup._accessHandler});tinymce.each(b.listeners,function(f){f.func.call(f.scope,b.editor)});if(b.getWindowArg("mce_auto_focus",true)){window.focus();tinymce.each(document.forms,function(g){tinymce.each(g.elements,function(f){if(b.dom.hasClass(f,"mceFocus")&&!f.disabled){f.focus();return false}})})}document.onkeyup=tinyMCEPopup._closeWinKeyHandler},_accessHandler:function(a){a=a||window.event;if(a.keyCode==13||a.keyCode==32){a=a.target||a.srcElement;if(a.onchange){a.onchange()}return tinymce.dom.Event.cancel(a)}},_closeWinKeyHandler:function(a){a=a||window.event;if(a.keyCode==27){tinyMCEPopup.close()}},_wait:function(){if(document.attachEvent){document.attachEvent("onreadystatechange",function(){if(document.readyState==="complete"){document.detachEvent("onreadystatechange",arguments.callee);tinyMCEPopup._onDOMLoaded()}});if(document.documentElement.doScroll&&window==window.top){(function(){if(tinyMCEPopup.domLoaded){return}try{document.documentElement.doScroll("left")}catch(a){setTimeout(arguments.callee,0);return}tinyMCEPopup._onDOMLoaded()})()}document.attachEvent("onload",tinyMCEPopup._onDOMLoaded)}else{if(document.addEventListener){window.addEventListener("DOMContentLoaded",tinyMCEPopup._onDOMLoaded,false);window.addEventListener("load",tinyMCEPopup._onDOMLoaded,false)}}}};tinyMCEPopup.init();tinyMCEPopup._wait();


		$(function(){
			$('input#search').quicksearch('ul li', {
				'loader' : '.waiting'
			});

			$('li').click(function(){
				$('.selected').removeClass('selected');
				$(this).addClass('selected');
			});

			$('.cancel').click(function(){
				tinyMCEPopup.close();
			});

			$('#wp-link-submit').click(function(){
				var id = $('.selected .item-info').html();
				var title = $('.selected .item-title').html();
				tinyMCE.execCommand('mceInsertContent',false,'[buckets id="' + id + '" title="' + title + '"]');
				tinyMCEPopup.close();
			});

		});
	</script>


	<style>
		* { margin: 0; padding: 0; }
		body { font-family: Arial, sans-serif !important; font-size: 12px !important; background: #f5f5f5; margin: 0 !important; }

		#bs-wrap { padding: 20px 0px; }

		p { padding: 0 20px; margin-bottom: 20px; font-size: 11px; }

		label { margin-left: 20px; }
		input[type=text] { border: 1px solid #dfdfdf; border-radius: 4px; padding: 3px !important; width: 220px; font-size: 12px !important; }

		ul { margin-top: 20px; background: #fff; border: 1px solid #dfdfdf; overflow: auto; height: 185px; }
		ul li { position: relative; padding: 4px 6px; color: #333; border-bottom: 1px solid #f1f1f1; cursor: pointer; margin-bottom: 0; list-style: none; }
		ul li:hover { background: #eaf2fa; color: #151515; }
		ul li.selected { background: #ddd; }
		ul li.selected .item-title { font-weight: bold; }
		.item-info { text-transform: uppercase; font-size: 11px; color: #666; position: absolute; right: 5px; top: 4px; }

		.cancel { color: red !important; text-decoration: none; display: block; width: 100px; float: left; margin: 8px 0 0 10px; }
		.cancel:hover { text-decoration: underline; }

		input.button-primary { float: right; margin: 4px 10px 0 0 !important; font-size: 11px !important; font-weight: bold; background: #21759b url(http://test.paper-robots.com/wp/wp-admin/images/button-grad.png) repeat-x top left; color: #fff; border: 1px solid #298CBA; border-radius: 11px; padding: 3px 8px; }
		input.button-primary:hover { border-color: #13455B; color: #EAF2FA; }

	</style>

</head>


<body>

	<div id="bs-wrap">
		<p>Select which Bucket you'd like and then click Insert.</p>
		<form tabindex="-1">
			<label>Search</label> <input type="text" id="search">
			<img class="waiting" src="<?php echo esc_url( admin_url( 'images/wpspin_light.gif' ) ); ?>" alt="" />
		</form>

		<ul>
			<?php $posts = get_posts(array('numberposts' => -1, 'post_type' => 'buckets')); ?>
			<?php foreach($posts as $post) : ?>
				<li><span class="item-title"><?php echo $post->post_title; ?></span><span class="item-info"><?php echo $post->ID; ?></span></li>
			<?php endforeach; ?>
		</ul>
		
		<a href="#" class="cancel">Cancel</a>

		<input type="submit" tabindex="100" value="Insert" class="button-primary" id="wp-link-submit" name="wp-link-submit">
	</div>

</body>
</html>