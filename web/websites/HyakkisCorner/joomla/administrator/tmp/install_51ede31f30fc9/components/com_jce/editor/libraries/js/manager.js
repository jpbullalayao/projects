/* JCE Editor - 2.3.3.2 | 13 July 2013 | http://www.joomlacontenteditor.net | Copyright (C) 2006 - 2013 Ryan Demmer. All rights reserved | GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html */
(function($){$.widget("ui.MediaManager",{_actions:[],_buttons:{'folder':{},'file':{}},_dialog:[],_returnedItems:[],_pasteitems:'',_pasteaction:'',_limitcount:0,_limitend:0,options:{base:'',dir:'images',dialog:{list:'#browser-list',tree:'#tree-body',info:'#browser-details-text',limit:'#browser-list-limit',comments:'#browser-details-comment',nav:'#browser-details-nav',status:'#browser-message',message:'#message-info',buttons:'#browser-buttons',actions:'#browser-actions',refresh:'#refresh',search:'#search'},actions:null,buttons:null,tree:true,details:true,search:true,upload:{size:'1024kb',types:{},overwrite:true,limit:false,runtimes:'html5,flash,html4',chunk_size:'1mb',insert:true,dialog:{},elements:null,buttons:{}},folder_new:{dialog:null},rename:{dialog:null},viewable:'jpeg,jpg,gif,png,avi,wmv,wm,asf,asx,wmx,wvx,mov,qt,mpg,mp3,mp4,m4v,mpeg,ogg,ogv,webm,swf,flv,f4v,xml,dcr,rm,ra,ram,divx,html,htm,txt,rtf,pdf,doc,docx,xls,xlsx,ppt,pptx',use_cookies:true,listlimit:'all',expandable:true,websafe_mode:'utf-8',websafe_spaces:false},_init:function(){var self=this;this._addActions(this.options.actions);this._addButtons(this.options.buttons);var dialog=this.options.dialog;var list=document.createElement('ul');$(list).addClass('item-list').attr({'id':'item-list','role':'listbox'}).bind('click.item-list',function(e){var n=e.target,p=n.parentNode;switch(n.nodeName){case'A':if($(p).hasClass('folder')){var u=$(p).data('url')||self._getPreviousDir();return self._changeDir(u);}else{self._setSelectedItems(e,true);self._trigger('onFileClick',e,p);}
break;case'LI':if($(n).hasClass('folder-up')){var u=$(p).data('url')||self._getPreviousDir();return self._changeDir(u);}
if($(n).hasClass('folder')){if(e.pageX<$('a',p).offset().left){var u=$(p).data('url')||self._getPreviousDir();return self._changeDir(u);}}
self._setSelectedItems(e,true);break;case'SPAN':if($(n).hasClass('checked')){$(n).removeClass('checked').attr('aria-checked',false);self._removeSelectedItems([p],true);}else{self._setSelectedItems(e,true);$(n).attr('aria-checked',true);}
break;}
e.preventDefault();}).bind('dblclick.item-list',function(e){e.preventDefault();return false;}).bind('keydown.item-list',function(e){switch(e.which){case 13:n=$('li.selected:last','#item-list').get(0);if(!n)
return;if($(n).hasClass('folder')){var u=$(p).data('url')||self._getPreviousDir();return self._changeDir(u);}else{self._setSelectedItems(e,true);self._trigger('onFileClick',e,n);}
break;case 38:case 40:self._setSelectedItems(e);break;}
e.preventDefault();});$('#browser-list').append(list).bind('scroll.browser-list',function(e){self._updateList();});$('span.details-nav-left, span.details-nav-right','#browser-details-nav').click(function(){var $item=$('li.selected.active','#item-list').removeClass('active');if($(this).hasClass('details-nav-left')){$item.prevAll('li.selected:first').addClass('active');}
if($(this).hasClass('details-nav-right')){$item.nextAll('li.selected:first').addClass('active');}
self._showItemDetails();});$(dialog.limit+'-select').val($.Cookie.get('jce_'+$.Plugin.getName()+'_limit')||this.options.listlimit);$(dialog.limit+'-select').change(function(){self._limitcount=0;if(self.options.use_cookies){$.Cookie.set('wf_'+$.Plugin.getName()+'_limit',$(this).val());}
self.refresh();});$('ul li',dialog.limit).click(function(){var x=0,count=self._limitcount,limit=parseInt(self._limit);if($(this).hasClass('limit-left')){x=count-limit;}
if($(this).hasClass('limit-right')){x=count+limit;}
if($(this).hasClass('limit-right-end')){x=count=self._limitend;}
self._limitcount=x;self.refresh();});$('span.checkbox','#check-all').click(function(e){var el=e.target;if($(el).hasClass('checked')){$(el).removeClass('checked').attr('aria-checked',false);$('span.checkbox',$(dialog.list)).removeClass('checked').attr('aria-checked',false);self._deselectItems();}else{$(el).addClass('checked').attr('aria-checked',true);self._selectItems($('li.folder, li.file',$(dialog.list)).not('li.folder-up'),true);}});this._setupSortables();if(!this.options.search){$('#block-search-icon').addClass('hide');}
if(!this.options.details||!this._treeLoaded()){$('#block-details-icon').addClass('hide');$('#show-details span').addClass('disabled');}
$('#show-search').click(function(){$('#searchbox').toggleClass('hide').attr('aria-hidden',function(){return $(this).hasClass('hide');});$(this).toggleClass('active');if($(this).hasClass('active')){var end=$(this).prevAll(':visible').get(1);var width=$(end).parent().width()-$(end).position().left-parseInt($('#searchbox').css('right'));var spacers=$(end).nextAll(':visible').not(this).children('div.spacer');$('#searchbox').width(width-(spacers.length*$(spacers).outerWidth(true)));$('#search').focus();}});$('body').click(function(e){if($('#searchbox input').val()||$(e.target).is('#show-search, span.layout-icon.search')||$(e.target).parents('#searchbox').length){return;}
$('#searchbox').addClass('hide').attr('aria-hidden',true);$('#show-search').removeClass('active');});$(dialog.search).listFilter({list:dialog.list,items:'#item-list li.file',clear:$('span.search-icon','#searchbox'),filter:'#item-list li',onFilter:function(e,s,cb,scope){if(s=='.'){return;}
if($('#browser-list-limit-select').val()=='all'){return $(this).listFilter('filter',s);}
if(s&&self._isWebSafe(s)){$('#browser-list').one('load.filter',function(){cb.call(scope||self,$('li.file','#item-list').get());});self._getList('',s);}else{self.refresh();}},onFind:function(e,items){if(e.currentTarget==$('span.search-icon','#searchbox').get(0)){if($('#browser-list-limit-select').val()=='all'){return $(this).listFilter('reset');}
self.refresh();}}});$(dialog.refresh).click(function(){self.refresh();});$('#show-details').click(function(e){var span=e.target;$(span).toggleClass('tree');self._showListDetails();});var ih=this._getInterfaceHeight();$(window).bind('resize',function(){self.resize(ih);});$('#layout-full-toggle').toggle(this.options.expandable).click(function(){$('#browser').toggleClass('full-height');self.resize(ih);self._trigger($('#browser').hasClass('full-height')?'onMaximise':'onMinimise');});this._setupDir();self.resize(ih);this._trigger('onInit');},_updateList:function(){var self=this;var area=$('#browser-list').height()+$('#browser-list').scrollTop();$('li.file.jpg, li.file.jpeg, li.file.png, li.file.gif, li.file.bmp','#item-list').not('[data-width]').each(function(){var pos=$(this).position();if(pos.top<area){self._getDimensions(this);}});},_getInterfaceHeight:function(){var ih=Math.max(0,Math.round($('#browser').offset().top)-5);var p=$('#browser').parent('form');if($(p).next(':not(div.actionPanel)').length){ih=(Math.round($('div.actionPanel').offset().top)-10)-$('#browser').innerHeight();}
return ih;},resize:function(ih,init){var fh=$('#browser').hasClass('full-height'),ih=ih||this._getInterfaceHeight();var ap=Math.round($('div.actionPanel').offset().top)-10;$('#browser').css({width:$('body').width()});var bh=(fh?ap:ap-ih)-$('#browser').innerHeight();$('#browser-tree, #tree-body, #browser-list, #browser-details, #browser-details~div.spacer, #browser-buttons').height(function(i,v){return v+bh;});},_translate:function(s,ds){return $.Plugin.translate(s,ds);},_setupSortables:function(){var self=this;$('#item-list').listsort({fields:{'#sort-ext':{attribute:'title',selector:'li.file'},'#sort-name':{attribute:'title',selector:['li.folder:not(.folder-up)','li.file']},'#sort-date':{attribute:'data-modified',selector:['li.folder:not(.folder-up)','li.file']},'#sort-size':{attribute:'data-size',selector:'li.file'}},onSort:function(){self._trigger('onListSort');}});},_validatePath:function(s){function _toUnicode(c){c=c.toString(16).toUpperCase();while(c.length<4){c='0'+c;}
return'\\u'+c;}
if(/\.{2,}/.test(s)||(/:\/\//.test(s)&&s.indexOf($.Plugin.getURI(true))==-1)){return false;}
if(/:\/\//.test(s)){s=$.URL.toRelative(s);}
if(/[^\w\.\-~\s \/]/i.test(s)){for(var i=0,ln=s.length;i<ln;i++){var ch=s[i];if(/[^\w\.\-~\s \/]/i.test(ch)){if(_toUnicode(ch.charCodeAt(0))<'\\u007F'){return false;}}}}
return true;},_cleanPath:function(path){if(path){if(new RegExp(':\/\/').test(path)){path=$.URL.toRelative(path);}
path=path.replace(/^[\/\\]+/,'');if(/\.([a-z0-9]{2,}$)/i.test(path)){path=$.String.dirname(path);path=path.replace(new RegExp(this.options.dir),'').replace(/^[\/\\]+/,'');}}
return path;},_setupDir:function(){var dir='';var src=$(this.element).val();if(src&&this.options.base){if(src.indexOf('://'+this.options.base)!==-1){src=src.replace(/http(s)?:\/\//i,'');src=src.substr(0,src.indexOf('?'));src=src.replace(this.options.base,'');src=src.replace(/^[\/\\]+/,'');}}
if(!this._validatePath(src)){src='';}
if(!src){dir=$.Cookie.get('wf_'+$.Plugin.getName()+'_dir')||'';}
if(!this._validatePath(dir)){dir='';}
this._dir=$.String.encodeURI(dir);if(src&&/:\/\//.test(src)){src=$.URL.toRelative(src);}
if(this._treeLoaded()){this._createTree(src);}else{this._getList(src);}},_toggleTree:function(s){$('#browser').toggleClass('full-width',!s);$('div.layout-left','#browser').attr('aria-hidden',!s);$('#sort-size, #sort-date').attr('aria-hidden',s);$('span.size, span.date','#item-list').attr('aria-hidden',s);},_isWebSafe:function(name){return name===$.String.safe(name,this.options.websafe_mode,this.options.websafe_spaces);},_isViewable:function(name){var button=this._getButton('file','view');var viewable=this.options.viewable;if(button&&button.restrict){viewable=button.restrict;}
return new RegExp('\\.('+viewable.replace(/,/g,'|')+')$','i').test(name);},_buildList:function(o){var self=this,h='';$('#item-list').empty();if(!this._isRoot()){h+='<li class="folder folder-up" title="Up"><a href="javascript:;">...</a></li>';}
if(o.folders.length){$.each(o.folders,function(i,e){var data=[],classes=[];$.each(e.properties,function(k,v){if(v!==''){data.push('data-'+k+'="'+v+'"');}});data.push('data-url="'+(e.url||e.id)+'"');classes.push(self._isWebSafe(e.name)?'safe':'notsafe');classes.push(e.writable?'writable':'notwritable');if(e.classes){classes.push(e.classes);}
h+='<li class="folder '+classes.join(' ')+'" title="'+e.name+'"'+
data.join(' ')+'><span class="checkbox" role="checkbox" aria-checked="false"></span><a href="javascript:;">'+e.name+'</a><span class="date" aria-hidden="true">'+$.String.formatDate(e.properties.modified)+'</span></li>';});}
if(o.total.files){$.each(o.files,function(i,e){var data=[],classes=[];$.each(e.properties,function(k,v){if(v!==''){data.push('data-'+k+'="'+v+'"');}});data.push('data-url="'+(e.url||e.id)+'"');data.push('id="'+e.id+'"');classes.push(self._isWebSafe(e.name)?'safe':'notsafe');classes.push(e.writable?'writable':'notwritable');if(e.selected){self._addReturnedItem({'name':e.id});}
if(e.classes){classes.push(e.classes);}
h+='<li class="file '+$.String.getExt(e.name)+' '+classes.join(' ')+'" title="'+e.name+'"'+
data.join(' ')+'><span class="checkbox" role="checkbox" aria-checked="false"></span><a href="javascript:;">'+e.name+'</a><span class="date" aria-hidden="true">'+$.String.formatDate(e.properties.modified)+'</span><span class="size" aria-hidden="true">'+$.String.formatSize(e.properties.size)+'</span></li>';});}else{h+='<li class="nofile">'+self._translate('no_files','No files')+'</li>';}
$('#item-list').html(h);this._showListDetails();},_showListDetails:function(){var s=!$('span.layout-icon','#show-details').hasClass('tree')&&this._treeLoaded();this._toggleTree(s);},_treeLoaded:function(){return this.options.tree&&typeof $.ui.tree!='undefined';},_createTree:function(src){var self=this,dialog=this.options.dialog;var path=src||this._dir;path=this._cleanPath(path);this.setStatus({message:self._translate('loading','Loading...'),state:'load'});$(dialog.tree).tree({onInit:function(e,callback){$.JSON.request('getTree',path,function(o){$(dialog.tree).html(o);if($.isFunction(callback)){callback.apply();}
self._getList(src);});},onNodeClick:function(e,node){self._changeDir($(node).attr('id'));$(dialog.tree).tree('toggleNode',e,node);},onNodeLoad:function(e,node){$(dialog.tree).tree('toggleLoader',node);$.JSON.request('getTreeItem',$(node).attr('id'),function(o){if(o){if(!o.error){$('ul:first',node).remove();$(dialog.tree).tree('createNode',o.folders,node);$(dialog.tree).tree('toggleNodeState',node,true);}else{alert(o.error);}}
$(dialog.tree).tree('toggleLoader',node);},this);}});},_reset:function(){this._deselectItems();this._returnedItems=[];$.each(this._dialog,function(i,n){$(n).dialog('close');});$('span.checkbox',$('#check-all')).removeClass('checked');$('span','#browser-details-nav').removeClass('visible').attr('aria-hidden',true).filter('span.details-nav-text').empty();},_clearPaste:function(){this._pasteaction='';this._pasteitems='';this._hideButtons($('div.paste','#buttons'));},setStatus:function(o){$(this.options.dialog.status).attr('class',o.state||'');$(this.options.dialog.status).html('<span>'+o.message||''+'</span>');},_setMessage:function(message,classname){return true;},_setLoader:function(){this.setStatus({message:this._translate('message_load','Loading...'),state:'load'});},_resetMessage:function(){return true;},_resetStatus:function(){var self=this,dir=decodeURIComponent(this._dir),$status=$(this.options.dialog.status);this.setStatus({message:'',state:''});$status.empty();var $pathway=$('<ul/>').addClass('pathway').appendTo($status);var sw=$status.width();$root=$('<li/>').html(self._translate('root','Root')).click(function(){self._changeDir('/');}).appendTo($pathway);dir=$.trim(dir.replace(/^\//,''));$count=$('<li class="count">( '+this._foldercount+' '+this._translate('folders','folders')+', '+this._filecount+' '+this._translate('files','files')+')</li>').appendTo($pathway);var w=bw=$root.outerWidth(true)+$count.outerWidth(true);if(dir){var x=1,parts=dir.split('/');$.each(parts,function(i,s){var path=s;if(i>0){path=parts.slice(0,i+1).join('/');}
$item=$('<li title="'+s+'" />').click(function(e){self._changeDir(path);}).html('&rsaquo;&nbsp;'+s).insertBefore($count);w+=$item.outerWidth(true);if(w>(sw-bw)){$('li',$pathway).eq(x++).html('&rsaquo; ...');}});}},_getPreviousDir:function(){if(this._dir.length<2){return this._dir;}
var dirs=this._dir.split('/');var s='';for(var i=0;i<dirs.length-1;i++){s=$.String.path(s,dirs[i]);}
return s;},_addReturnedItem:function(items){if($.type(items)=='array'){$.merge(this._returnedItems,items);}else{this._returnedItems.push(items);}},_returnFile:function(file){this._addReturnedItem({name:$.String.basename(file)});this._changeDir($.String.dirname(file));},_setDir:function(dir){this._dir=dir;},getBaseDir:function(){return this.options.dir;},getCurrentDir:function(){return this._dir;},_isRoot:function(){return this._dir==''||this._dir=='/';},_changeDir:function(dir){this._reset();this._limitcount=0;this._setDir(dir);this._setLoader();this._getList(dir);},_getList:function(src,filter){var path=src||this._dir;if(src&&this.options.use_cookies){$.Cookie.set("wf_"+$.Plugin.getName()+'_dir',this._cleanPath(path));}
this._hideButtons($('div.button','#buttons'));this._limit=$('#browser-list-limit-select').val()||this.options.listlimit;$.JSON.request('getItems',[path,this._limit,this._limitcount,filter||''],this._loadList,this);},refresh:function(){this._reset();this._setLoader();this._getList();},load:function(items){if(items){this._addReturnedItem(items);}
this._setLoader();this._getList();},error:function(error){this._raiseError(error);},startUpload:function(){$('#upload-queue').uploader('start');},stopUpload:function(){$('#upload-queue').uploader('stop');},setUploadStatus:function(o){$('#upload-queue').uploader('setStatus',o);},_loadList:function(o){var dialog=this.options.dialog;if(!$.support.cssFloat){$('#browser-list').attr('unselectable','on');}
this._foldercount=o.total.folders;this._filecount=o.total.files;this._limitend=(o.total.folders+o.total.files)-this._limit;var count=this._limitcount+o.folders.length+o.files.length;if(count<(o.total.folders+o.total.files)){$('#browser-list-limit ul.limit-right li').css('display','inline-block').attr('aria-hidden',false);}else{$('#browser-list-limit ul.limit-right li').hide().attr('aria-hidden',true);}
if((count-this._limit)>0){$('#browser-list-limit ul.limit-left li').css('display','inline-block').attr('aria-hidden',false);}else{$('#browser-list-limit ul.limit-left li').hide().attr('aria-hidden',true);}
if(o.folders.length){this._dir=$.String.encodeURI($.String.dirname(o.folders[0].id)||'/',true);}else if(o.files.length){this._dir=$.String.encodeURI($.String.dirname(o.files[0].id)||'/',true);}
if(!this._isRoot()){$('#folder-list').append('<li class="folder-up" title="Up"><a href="javascript:;>...</a></li>');}
if(this._treeLoaded()){$(dialog.tree).tree('createNode',o.folders,this._dir);}
this._trigger('onBeforeBuildList',null,o);this._buildList(o);this._trigger('onAfterBuildList',null,o);if(this._returnedItems.length){this._findItem(this._returnedItems);this._returnedItems=[];}
if(this._pasteitems){this._showPasteButton();}
$('#browser-list').trigger('load');this._resetStatus();this._resetMessage();this._trigger('onListComplete');},_getDialogOptions:function(dialog){var options=this.options[dialog];var elements='';if(options&&options.elements){if($.isPlainObject(options.elements)){$.each(options.elements,function(k,v){if(v.options){elements+='<p>';elements+='<label for="'+k+'">'+(v.label||k)+'</label>';elements+='<select id="'+k+'" name="'+k+'">';$.each(v.options,function(value,name){elements+='<option value="'+value+'">'+name+'</option>';});elements+='</select>';elements+='</p>';}else{elements+='<p><label for="'+k+'">'+v.label||k+'</label><input id="'+k+'" type="text" name="'+k+'" value="'+v.value||''+'" /></p>';}});}else{return options.elements;}}
return elements;},_execute:function(name){var self=this;var dir=this._dir;dir=dir.replace(/^[\/\\]+/,'');var list=this._serializeSelectedItems();var site=$.Plugin.getURI(true);switch(name){case'help':$.Plugin.help();break;case'insert':this._trigger('onFileInsert',null,$('li.selected','#item-list').get(0));break;case'view':var $item=$('li.selected.active:first','#item-list');var url=$item.data('url');url=/http(s)?:\/\//.test(url)?url:$.String.path(site,url);if($item.data('preview')){url=$item.data('preview');}
var name=$.String.basename($item.attr('title'));if(this._isViewable(name)){if(/\.(jpeg|jpg|gif|png|avi|wmv|wm|asf|asx|wmx|wvx|mov|qt|mpg|mp3|mp4|m4v|mpeg|ogg|ogv|webm|swf|flv|f4v|xml|dcr|rm|ra|ram|divx|pdf)/i.test(name)){$.Dialog.media(name,url);}else{$.Dialog.iframe(name,url,{onFrameLoad:function(e){var iframe=$('div.iframe-preview iframe').get(0);var h=iframe.contentWindow.document.body.innerHTML;var tmpDiv=document.createElement('div');$(tmpDiv).html(h);function toRelative(s){s=$.URL.toRelative(s);return s.replace(/^administrator\//,'');}
$('img, embed',$(tmpDiv)).each(function(){var s=toRelative($(this).attr('src'));if(!/http(s)?:\/\//.test(s)){s=$.String.path(site,s);}
$(this).attr('src',s);});$('a, area',$(tmpDiv)).each(function(){var s=toRelative($(this).attr('href'));if(!/http(s)?:\/\//.test(s)){s=$.String.path(site,s);}
$(this).attr('href',s);});$('object',$(tmpDiv)).each(function(){$('param[name=movie], param[name=src]',this).each(function(){var s=toRelative($(this).attr('value'));if(!/http(s)?:\/\//.test(s)){s=string.path(site,s);}
$(this).attr('value',s);});});iframe.contentWindow.document.body.innerHTML=tmpDiv.innerHTML;}});}}
break;case'upload':this._dialog['upload']=$.Dialog.upload($.extend({elements:this._getDialogOptions('upload'),onOpen:function(){$('#upload-options:empty').hide();$('#upload-dir').val(dir);function _checkName(file){var found=false,msg=self._translate('file_exists_alert','A file with the same name exists in the target folder.');var name=$.String.safe(file.name,self.options.websafe_mode,self.options.websafe_spaces);$('li','file-list').each(function(){if(name==$(this).attr('title')){found=true;}});var el=file.element,span=$('span.queue-name:first',el);if(found){if(!$(el).hasClass('exists')){$(el).addClass('exists');$(span).attr('title',name+'::'+msg);$(span).tips();}}else{if($(el).hasClass('exists')){$(el).removeClass('exists');$(span).attr('title',name);}}
return true;}
$('#upload-queue').uploader($.extend({swf:site+'components/com_jce/editor/libraries/plupload/plupload.flash.swf',xap:site+'components/com_jce/editor/libraries/plupload/plupload.silverlight.xap',url:$('form:first').attr('action'),field:$('input[name=file]:first'),fileSelect:function(e,file){return _checkName(file);},websafe_mode:self.options.websafe_mode,websafe_spaces:self.options.websafe_spaces,fileRename:function(e,file){return _checkName(file);},fileComplete:function(e,file){if($.type(file)=='string'){file={name:file};}
self._addReturnedItem(file);self._trigger('onUploadFile',null,file);},uploadComplete:function(up,files){$('#upload-submit').disabled=false;if(up.total==files.length&&$('#upload-queue').uploader('getErrors')==0){self._getList();window.setTimeout(function(){$(self._dialog['upload']).dialog('close');},1000);self._trigger('onUploadComplete');}}},self.options.upload));self._trigger('onUploadOpen');},dragStop:function(){$('#upload-queue').uploader('refresh');},upload:function(){if($('#upload-queue').uploader('isUploading')){return false;}
var data={'action':'upload','format':'raw'},fields=$.merge($(':input','form').serializeArray(),$(':input','#upload-body').serializeArray());$.each(fields,function(i,field){data[field.name]=field.value;});self._trigger('onUpload',null,data);$('#upload-queue').uploader('upload',data);return false;},beforeClose:function(){$('#upload-queue').uploader('close');},resize:function(){$('#upload-queue').uploader('refresh');}},self.options.upload.dialog));break;case'folder_new':var elements=this._getDialogOptions('folder_new');this._dialog['folder_new']=$.Dialog.prompt(self._translate('folder_new','New Folder'),{text:self._translate('name','Name'),elements:elements,height:elements?200:150,confirm:function(v){if(v){self._setLoader();var args=[dir,$.String.safe(v,self.options.websafe_mode,self.options.websafe_spaces)];$(':input:not(input[name="prompt"])',$(self._dialog['folder_new']).dialog('widget')).each(function(){args.push($(this).val());});$.JSON.request('folderNew',args,function(o){if(o){self._trigger('onFolderNew');$(self._dialog['folder_new']).dialog('close');}
self.refresh();});}}});break;case'copy':case'cut':this._pasteaction=name;this._pasteitems=list;this._showPasteButton();break;case'paste':var fn=(this._pasteaction=='copy')?'copyItem':'moveItem';this._setLoader();var items=this._pasteitems;$.JSON.request(fn,[items,dir],function(o){if(o){if(o.folders.length){if(self._treeLoaded()){$.each(items.split(','),function(i,item){if(fn=='moveItem'){$(self.options.dialog.tree).tree('removeNode',item);}});}}
self._trigger('onPaste');}
self._clearPaste();self.refresh();});break;case'delete':var msg=self._translate('delete_item_alert','Delete Selected Item(s)');this._dialog['confirm']=$.Dialog.confirm(msg,function(state){if(state){self._setLoader();$.JSON.request('deleteItem',list,function(o){if(o){if(o.folders.length){if(self._treeLoaded()){$.each(list.split(','),function(i,item){$(self.options.dialog.tree).tree('removeNode',item);});}
self._trigger('onFolderDelete',null,o.folders);}
if(o.files.length){self._trigger('onFileDelete',null,o.files);}}
self.refresh();});}});break;case'rename':var s=this.getSelectedItems(0);var v=$.String.basename(list);if($(s).hasClass('file')){v=$.String.basename($.String.stripExt(list));}
this._dialog['rename']=$.Dialog.prompt(self._translate('rename','Rename Item'),{text:self._translate('name','Name'),value:v,elements:this._getDialogOptions('rename'),confirm:function(name){name=$.String.safe(name,self.options.websafe_mode,self.options.websafe_spaces);if(v==name){$.Dialog.alert(self._translate('rename_item_name_new','Please specify a new name for the item'));return false;}
self._dialog['confirm']=$.Dialog.confirm(self._translate('rename_item_alert','Renaming files/folders will break existing links. Continue?'),function(state){if(state){self._setLoader();var args=[list,name];$(':input:not(input[name="prompt"])',$(self._dialog['rename']).dialog('widget')).each(function(){args.push($(this).val());});$(self._dialog['rename']).dialog('close');$.JSON.request('renameItem',args,function(o){if(o){self._reset();var item=$.String.path(self._dir,name);if(o.folders.length){if(self._treeLoaded()){$(self.options.dialog.tree).tree('renameNode',list,item);}
self._trigger('onFolderRename',null,list,item);}
if(o.files.length){self._trigger('onFileDelete',null,item);}
if(item){self._addReturnedItem({name:item});}}
self.refresh();});}});}});break;}},_raiseError:function(error){var self=this,err='';switch($.type(error)){case'array':err+='<ul class="error-list">';$.each(error,function(k,v){err+='<li>'+v+'</li>';});err+='</ul>';break;case'string':default:err=error;break;}
this._dialog['alert']=$.Dialog.alert(err,{close:function(){self.refresh();}});},_addActions:function(actions){var self=this;$.each(actions,function(i,action){self._addAction(action);});},_addAction:function(o){var self=this,name=o.name||'',fn=this._execute;if(o.action){fn=o.action;}
var action=document.createElement('span');$(action).addClass('action');if(name){$(action).attr({'id':name,'title':o.title,'role':'button','labelledby':name+'_label'}).addClass(name).append('<span id="'+name+'_label" aria-hidden="true">'+o.title+'</span>');if(o.icon){$(action).css('background-image',$.String.path($.Plugin.getPath(this.options.plugin),o.icon));}
if(o.name){$(action).click(function(){if($.type(fn)=='function'){return fn.call(self,name);}
return self._trigger(fn,name);});}
this._actions[name]=action;}
$(this.options.dialog.actions).append('<span class="spacer"></span>').append(action);if(!$.support.cssFloat&&!window.XMLHttpRequest){$(action).hover(function(){$(this).addClass('hover');},function(){$(this).removeClass('hover');});}},_getAction:function(name){return this._actions[name];},_addButtons:function(buttons){var self=this;if(buttons){if(buttons.folder){$.each(buttons.folder,function(i,button){if(button){self._addButton(button,'folder');}});}
if(buttons.file){$.each(buttons.file,function(i,button){if(button){self._addButton(button,'file');}});}}},_addButton:function(o,type){var self=this,dialog=this.options.dialog,fn=this._execute;if(o.action){fn=o.action;}
var button=$('div.'+o.name,$(dialog.buttons));if(!button.length){button=document.createElement('div');$(button).attr({'title':o.title,'role':'button','aria-labelledby':o.name+'_label'}).append('<span id="'+o.name+'_label" aria-hidden="true">'+o.title+'</span>');if(o.icon){$(button).css('background-image',$.String.path($.Plugin.getPath(this.options.plugin),o.icon));}
if(o.name){var n=o.name;$(button).click(function(){if($('li.selected','#item-list').length||self._pasteitems){if(o.sticky){$(button).toggleClass('ui-state-active');}
if($.type(fn)=='function'){return fn.call(self,n,type);}
return self._trigger(fn,type);}});}
if(!$.support.cssFloat&&!window.XMLHttpRequest){$(button).hover(function(){$(this).addClass('hover');},function(){$(this).removeClass('hover');});}
$(dialog.buttons).append(button);$(button).addClass('button '+o.name+' hide');}
this._buttons[type][o.name]={'name':o.name,'element':button,'trigger':o.trigger,'multiple':o.multiple,'single':$.type(o.single)==='undefined'?true:o.single,'restrict':o.restrict||'','sticky':o.sticky};},_hideAllButtons:function(){var self=this;$('div.button').each(function(){self._hideButton(this);});},_hideButtons:function(buttons){var self=this;$.each(buttons,function(i,button){self._hideButton(button);});},_hideButton:function(button){$(button).removeClass('show').addClass('hide').attr('aria-hidden',true);},_showButtons:function(){var self=this;this._hideAllButtons();var folder=$('li.folder.selected','#item-list');var file=$('li.file.selected','#item-list');if(file.length&&folder.length){var buttons={};var filebtns=this._buttons['file'];var folderbtns=this._buttons['folder'];$.each(filebtns,function(k,o){if(!o.trigger&&o.multiple){if(folderbtns[k]){buttons[k]=o;}}});$.each(folderbtns,function(k,o){if(!o.trigger&&o.multiple){if(filebtns[k]){buttons[k]=o;}}});$.each(buttons,function(k,o){self._showButton(o.element,o.single,true);});}else{var type=file.length?'file':'folder';$.each(this._buttons[type],function(k,o){if(!o.trigger&&!o.restrict){self._showButton(o.element,o.single,o.multiple);}
if(o.restrict){var re=o.restrict.replace(/,/g,'|');var item=self.getSelectedItems(0);if(new RegExp('\\.('+re+')$','i').test($(item).attr('title'))){self._showButton(o.element,o.single,o.multiple);}}});}
if(this._pasteitems){this._showPasteButton();}},_showButton:function(button,single,multiple){if(button){var show=false,n=$('li.selected','#item-list').length;if(n>1){if(multiple){show=true;}}else{if(single){show=true;}}
$(button).toggleClass('hide',!show).toggleClass('show',!show);if(!show){$(button).attr('aria-hidden',false);}}},_getButton:function(type,name){return this._buttons[type][name]||null;},_showPasteButton:function(){this._showButton($('div.paste','#browser-buttons'),true,true);},_isSelectedItem:function(el){return $(el).is('li.selected');},_deselectItems:function(){var dialog=this.options.dialog;$('li.selected','#item-list').removeClass('selected active').children('span.checkbox').removeClass('checked').attr('aria-checked',false);$(dialog.info).empty();$(dialog.comments).empty();var nav=dialog.nav;$.each([nav+'-left',nav+'-right',nav+'-text'],function(i,el){$(el).css('visibility','hidden').attr('aria-hidden',true);});this._hideAllButtons();$('span.checkbox','#check-all').removeClass('checked');},_selectItems:function(items,show){$(items).addClass('selected').children('span.checkbox').addClass('checked').attr('aria-checked',true);if(show){this._showSelectedItems();}
var $list=$('#item-list');if($('span.checked',$list).length==$('li',$list).length){$('span.checkbox','#check-all').addClass('checked').attr('aria-checked',true);}
this._trigger('onSelectItems',null,items);},_removeSelectedItems:function(items,show){$(items).removeClass('selected').children('span.checkbox').removeClass('checked').attr('aria-checked',false);if(show){this._showSelectedItems();}
this._trigger('onRemoveItems',null,items);},getSelectedItems:function(key){var $items=$('li.selected','#item-list');return $items.get(key)||$items;},setSelectedItems:function(items){this._findItem(items);},_setSelectedItems:function(e,multiple){var checkbox=false;var el=e.target;var $list=$('#item-list');if(e.type=='keydown'){el=$('li.selected:last',$list).get(0);$list=$(this.options.dialog.list);if(e.which==38){el=el.previousSibling;}
if(e.which==40){el=el.nextSibling;}
if(!el){return;}
if(el.offsetTop>$list.innerHeight()||el.offsetTop<$list.scrollTop()){$list.scrollTop((el.offsetTop+$(el).innerHeight())-$list.height());}}
if($(el).hasClass('checkbox')){multiple=true;checkbox=true;}
if(el.nodeName!='LI')
el=el.parentNode;var selected=$('li.selected',$list);if(this._isSelectedItem(el)&&selected.length==1){e.preventDefault();return false;}
var items=$('li.folder, li.file',$list);var ctrl=(e.ctrlKey||e.metaKey),shift=e.shiftKey;if(!ctrl&&!shift&&!checkbox||!multiple){$('span.checkbox',el).removeClass('checked').attr('aria-checked',false);this._deselectItems();this._selectItems([el],true);}else if(multiple&&(ctrl||shift||checkbox)){if(ctrl||checkbox){if(this._isSelectedItem(el)){this._removeSelectedItems([el],true);}else{this._selectItems([el],true);}}
if(shift){if(selected.length){var si=$(el,selected).index();var ci=$(el,items).index();var selection=[];this._deselectItems();if(ci>si){for(var i=ci;i>=si;i--){selection.push(items[i]);}}else{for(var i=si;i>=ci;i--){selection.push(items[i]);}}
this._selectItems(selection,true);}else{this._selectItems([el],true);}}}},_showSelectedItems:function(){var $items=$('li.selected','#item-list'),n=$items.length;if(!n){this._reset();}else{$items.first().addClass('active');this._showItemDetails();}},_findItem:function(files,type){var self=this,items=[];type=type||'file';if($.type(files)=='string'){files=[files];}
var insert=false;var $list=$('#item-list');var base=self.getBaseDir();$.each(files,function(i,file){if(file&&file.name){var name=decodeURIComponent(file.name);if($list.length){var item=[];item=$('li.'+type+'[title="'+$.String.basename(name)+'"], li.'+type+'[data-url="'+$.String.path(base,name)+'"]',$list);if(item.length){if(file.insert){insert=true;items=item;self._trigger('onFileClick',null,$(item).get(0));}
if(!insert){$.merge(items,item);}}}}});if(items.length){var top=$(items).get(0).offsetTop-2;$(this.options.dialog.list).animate({scrollTop:Math.round(top)},1500);}
this._selectItems(items,true);},_serializeSelectedItems:function(){var self=this;return $('li.selected','#item-list').map(function(){return $.String.path(self._dir,$(this).attr('title'));}).get().join(',');},_showItemDetails:function(){var self=this,$items=$('li.selected','#item-list'),n=$items.length;var $nav=$('#browser-details-nav');if(n>1){var index=$items.index($items.filter('.active'));if(index){$('span.details-nav-left',$nav).addClass('visible').attr('aria-hidden',false);}else{$('span.details-nav-left',$nav).removeClass('visible').attr('aria-hidden',true);}
if(index+1<n){$('span.details-nav-right',$nav).addClass('visible').attr('aria-hidden',false);}else{$('span.details-nav-right',$nav).removeClass('visible').attr('aria-hidden',true);}
$('span.details-nav-text',$nav).addClass('visible').html(function(){return self._translate('one_of_many','%o of %m').replace('%o',index+1).replace('%m',n);});}else{$('span',$nav).removeClass('visible').attr('aria-hidden',true);}
this._showButtons();this._getItemDetails();},_getDimensions:function(file){var img=new Image();var src=$.String.path($.Plugin.getURI(),$(file).data('url'));$(file).addClass('loading disabled').children('span.checkbox').addClass('disabled');img.onload=function(){$(file).attr({'data-preview':src,'data-width':img.width,'data-height':img.height});$(file).removeClass('loading disabled').children('span.checkbox').removeClass('disabled');};img.src=src;},_getItemDetails:function(){var self=this,dialog=this.options.dialog;var item=$('li.selected.active','#item-list'),title=$.String.basename($(item).attr('title'));var type=$(item).hasClass('folder')?'folder':'file';$(dialog.info).empty().addClass('loader');var path=$.String.path(this._dir,$.String.encodeURI(title));var name=title;var ext='';if(type=='file'){name=$.String.stripExt(title);ext=$.String.getExt(title)+' ';}
var info=document.createElement('dl');$(info).append('<dt>'+name+'</dt><dd>'+ext+self._translate(type,$.String.ucfirst(type))+'</dd><dd id="info-properties"><dl></dl></dd>');if(type=='file'){$(info).append('<dd id="info-preview"></dd>');}
$(dialog.info).removeClass('loader').empty().append(info);var comments='';if($(item).hasClass('notwritable')){comments+='<li class="comments '+type+' notwritable">'+'<span class="hastip" title="'+self._translate('notwritable_desc','Unwritable')+'">'+self._translate('notwritable','Unwritable')+'</span>'+'</li>';}
if($(item).hasClass('notsafe')){comments+='<li class="comments '+type+' notsafe">'+'<span class="hastip" title="'+self._translate('bad_name_desc','Bad file or folder name')+'">'+self._translate('bad_name','Bad file or folder name')+'</span>'+'</li>';}
if($(item).data('trigger')){$.each($(item).data('trigger').split(','),function(i,v){if(v!==''){var button=self._getButton(type,v);if(button){self._showButton(button.element,button.single,button.multiple);}}});}
$.each($(item).data(),function(k,v){if(/^(preview|trigger|width|height|url)$/.test(k)){return;}
if(k=='size'){v=$.String.formatSize(v);}
if(k=='modified'){v=$.String.formatDate(v);}
$('#info-properties dl').append('<dd id="info-'+k.toLowerCase()+'">'+self._translate(''+k,k)+': '+v+'</dd>');});if($(item).data('width')&&$(item).data('height')){$('#info-properties dl').append('<dd id="info-dimensions">'+self._translate('dimensions','Dimensions')+': '+$(item).data('width')+' x '+$(item).data('height')+'</dd>');}
if($(item).data('preview')){$('#info-preview').empty().append('<dl>'+'<dt>'+self._translate('preview','Preview')+': </dt>'+'<dd class="loader"></dd>'+'</dl>');var src=decodeURIComponent($(item).data('preview'));var img=new Image();img.onload=function(){var w=img.width;var h=img.height;if($(item).attr('data-width')||$(item).attr('data-height')){$('#info-dimensions').html(self._translate('dimensions','Dimensions')+': '+w+' x '+h);}
if($.support.backgroundSize){$('dd','#info-preview').css('background-image','url("'+img.src+'")');if(w>100||h>80){$('dd','#info-preview').addClass('resize');}}else{var dim=$.Plugin.sizeToFit(img,{width:100,height:80});$('dd','#info-preview').append($(img).attr('alt',self._translate('preview','Preview')).css(dim));}
$('dd','#info-preview').removeClass('loader');};img.onerror=function(){$('dd',$('#info-preview')).removeClass('loader').addClass('preview-error');};src=/:\/\//.test(src)?src:$.String.encodeURI(src);img.src=src+(src.indexOf('?')==-1?'?':'&')+new Date().getTime();}
if(comments){$(dialog.comments).empty().append('<ul>'+comments+'</ul>');}
$('span.hastip',$(dialog.comments)).tips();var cb=(type=='folder')?'onFolderDetails':'onFileDetails';self._trigger(cb,null,item);},destroy:function(){$.Widget.prototype.destroy.apply(this,arguments);}});$.extend($.ui.MediaManager,{version:"2.3.3.2"});})(jQuery);