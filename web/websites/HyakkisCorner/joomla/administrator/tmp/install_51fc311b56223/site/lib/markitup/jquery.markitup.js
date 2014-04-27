// ----------------------------------------------------------------------------
// markItUp! Universal MarkUp Engine, JQuery plugin
// v 1.1.x
// Dual licensed under the MIT and GPL licenses.
// ----------------------------------------------------------------------------
// Copyright (C) 2007-2012 Jay Salvat
// http://markitup.jaysalvat.com/
// ----------------------------------------------------------------------------
// Permission is hereby granted, free of charge, to any person obtaining a copy
// of this software and associated documentation files (the "Software"), to deal
// in the Software without restriction, including without limitation the rights
// to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
// copies of the Software, and to permit persons to whom the Software is
// furnished to do so, subject to the following conditions:
// 
// The above copyright notice and this permission notice shall be included in
// all copies or substantial portions of the Software.
// 
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
// IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
// FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
// AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
// LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
// OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
// THE SOFTWARE.
// ----------------------------------------------------------------------------
(function(c){c.fn.markItUp=function(n,f){var w,D,b,j,i,t;j=i=t=!1;"string"==typeof n&&(w=n,D=f);b={id:"",nameSpace:"",root:"",previewHandler:!1,previewInWindow:"",previewInElement:"",previewAutoRefresh:!0,previewPosition:"after",previewTemplatePath:"~/templates/preview.html",previewParser:!1,previewParserPath:"",previewParserVar:"data",resizeHandle:!0,beforeInsert:"",afterInsert:"",onEnter:{},onShiftEnter:{},onCtrlEnter:{},onTab:{},markupSet:[{}]};c.extend(b,n,f);b.root||c("script").each(function(j,
i){miuScript=c(i).get(0).src.match(/(.*)jquery\.markitup(\.pack)?\.js$/);null!==miuScript&&(b.root=miuScript[1])});return this.each(function(){function f(a,c){return c?a.replace(/("|')~\//g,"$1"+b.root):a.replace(/^~\//,b.root)}function n(a){var h=c("<ul></ul>"),b=0;c("li:hover > ul",h).css("display","block");c.each(a,function(){var a=this,d="",g,f;g=a.key?(a.name||"")+" [Ctrl+"+a.key+"]":a.name||"";key=a.key?'accesskey="'+a.key+'"':"";if(a.separator)d=c('<li class="markItUpSeparator">'+(a.separator||
"")+"</li>").appendTo(h);else{b++;for(f=u.length-1;0<=f;f--)d+=u[f]+"-";d=c('<li class="markItUpButton markItUpButton'+d+b+" "+(a.className||"")+'"><a href="" '+key+' title="'+g+'">'+(a.name||"")+"</a></li>").bind("contextmenu.markItUp",function(){return!1}).bind("click.markItUp",function(){return!1}).bind("focusin.markItUp",function(){e.focus()}).bind("mouseup",function(){a.call&&eval(a.call)();setTimeout(function(){r(a)},1);return!1}).bind("mouseenter.markItUp",function(){c("> ul",this).show();
c(document).one("click",function(){c("ul ul",x).hide()})}).bind("mouseleave.markItUp",function(){c("> ul",this).hide()}).appendTo(h);a.dropMenu&&(u.push(b),c(d).addClass("markItUpDropMenu").append(n(a.dropMenu)))}});u.pop();return h}function l(a){c.isFunction(a)&&(a=a(s));a?(a=a.toString(),a=a.replace(/\(\!\(([\s\S]*?)\)\!\)/g,function(a,c){var b=c.split("|!|");return!0===t?void 0!==b[1]?b[1]:b[0]:void 0===b[1]?"":b[0]}),a=a.replace(/\[\!\[([\s\S]*?)\]\!\]/g,function(a,c){var b=c.split(":!:");if(!0===
v)return!1;value=prompt(b[0],b[1]?b[1]:"");null===value&&(v=!0);return value})):a="";return a}function y(a){var c=l(m.openWith),b=l(m.placeHolder),e=l(m.replaceWith),d=l(m.closeWith),g=l(m.openBlockWith),f=l(m.closeBlockWith),j=m.multiline;if(""!==e)block=c+e+d;else if(""===selection&&""!==b)block=c+b+d;else{var a=a||selection,i=[a],k=[];!0===j&&(i=a.split(/\r?\n/));for(a=0;a<i.length;a++){line=i[a];var n;(n=line.match(/ *$/))?k.push(c+line.replace(/ *$/g,"")+d+n):k.push(c+line+d)}block=k.join("\n")}block=
g+block+f;return{block:block,openWith:c,replaceWith:e,placeHolder:b,closeWith:d}}function r(a){var h,f,p;s=m=a;z();c.extend(s,{line:"",root:b.root,textarea:d,selection:selection||"",caretPosition:g,ctrlKey:j,shiftKey:i,altKey:t});l(b.beforeInsert);l(m.beforeInsert);(!0===j&&!0===i||!0===a.multiline)&&l(m.beforeMultiInsert);c.extend(s,{line:1});if(!0===j&&!0===i){lines=selection.split(/\r?\n/);h=0;f=lines.length;for(p=0;p<f;p++)""!==c.trim(lines[p])?(c.extend(s,{line:++h,selection:lines[p]}),lines[p]=
y(lines[p]).block):lines[p]="";string={block:lines.join("\n")};start=g;h=string.block.length+(c.browser.opera?f-1:0)}else!0===j?(string=y(selection),start=g+string.openWith.length,h=string.block.length-string.openWith.length-string.closeWith.length,h-=string.block.match(/ $/)?1:0,h-=B(string.block)):!0===i?(string=y(selection),start=g,h=string.block.length,h-=B(string.block)):(string=y(selection),start=g+string.block.length,h=0,start-=B(string.block));""===selection&&""===string.replaceWith&&(k+=
E(string.block),start=g+string.openWith.length,h=string.block.length-string.openWith.length-string.closeWith.length,k=e.val().substring(g,e.val().length).length,k-=E(e.val().substring(0,g)));c.extend(s,{caretPosition:g,scrollPosition:A});string.block!==selection&&!1===v?(f=string.block,document.selection?document.selection.createRange().text=f:d.value=d.value.substring(0,g)+f+d.value.substring(g+selection.length,d.value.length),F(start,h)):k=-1;z();c.extend(s,{line:"",selection:selection});(!0===
j&&!0===i||!0===a.multiline)&&l(m.afterMultiInsert);l(m.afterInsert);l(b.afterInsert);q&&b.previewAutoRefresh&&G();i=t=j=v=!1}function E(a){return c.browser.opera?a.length-a.replace(/\n*/g,"").length:0}function B(a){return c.browser.msie?a.length-a.replace(/\r*/g,"").length:0}function F(a,b){if(d.createTextRange){if(c.browser.opera&&9.5<=c.browser.version&&0==b)return!1;range=d.createTextRange();range.collapse(!0);range.moveStart("character",a);range.moveEnd("character",b);range.select()}else d.setSelectionRange&&
d.setSelectionRange(a,a+b);d.scrollTop=A;d.focus()}function z(){d.focus();A=d.scrollTop;if(document.selection)if(selection=document.selection.createRange().text,c.browser.msie){var a=document.selection.createRange(),b=a.duplicate();b.moveToElementText(d);for(g=-1;b.inRange(a);)b.moveStart("character"),g++}else g=d.selectionStart;else g=d.selectionStart,selection=d.value.substring(g,d.selectionEnd);return selection}function G(){if(b.previewHandler&&"function"===typeof b.previewHandler)b.previewHandler(e.val());
else if(b.previewParser&&"function"===typeof b.previewParser){var a=b.previewParser(e.val());C(f(a,1))}else""!==b.previewParserPath?c.ajax({type:"POST",dataType:"text",global:!1,url:b.previewParserPath,data:b.previewParserVar+"="+encodeURIComponent(e.val()),success:function(a){C(f(a,1))}}):J||c.ajax({url:b.previewTemplatePath,dataType:"text",global:!1,success:function(a){C(f(a,1).replace(/<\!-- content --\>/g,e.val()))}});return!1}function C(a){if(b.previewInElement)c(b.previewInElement).html(a);
else if(q&&q.document){try{sp=q.document.documentElement.scrollTop}catch(e){sp=0}q.document.open();q.document.write(a);q.document.close();q.document.documentElement.scrollTop=sp}}function H(a){i=a.shiftKey;t=a.altKey;j=!a.altKey||!a.ctrlKey?a.ctrlKey||a.metaKey:!1;if("keydown"===a.type){if(!0===j&&(li=c('a[accesskey="'+(13==a.keyCode?"\\n":String.fromCharCode(a.keyCode))+'"]',x).parent("li"),0!==li.length))return j=!1,setTimeout(function(){li.triggerHandler("mouseup")},1),!1;if(13===a.keyCode||10===
a.keyCode){if(!0===j)return j=!1,r(b.onCtrlEnter),b.onCtrlEnter.keepDefault;if(!0===i)return i=!1,r(b.onShiftEnter),b.onShiftEnter.keepDefault;r(b.onEnter);return b.onEnter.keepDefault}if(9===a.keyCode){if(!0==i||!0==j||!0==t)return!1;if(-1!==k)return z(),k=e.val().length-k,F(k,0),k=-1,!1;r(b.onTab);return b.onTab.keepDefault}}}var e,d,u,A,g,k,m,s,x,I,q,J,v;e=c(this);d=this;u=[];v=!1;A=g=0;k=-1;b.previewParserPath=f(b.previewParserPath);b.previewTemplatePath=f(b.previewTemplatePath);if(w)switch(w){case "remove":e.unbind(".markItUp").removeClass("markItUpEditor");
e.parent("div").parent("div.markItUp").parent("div").replaceWith(e);e.data("markItUp",null);break;case "insert":r(D);break;default:c.error("Method "+w+" does not exist on jQuery.markItUp")}else nameSpace=id="",b.id?id='id="'+b.id+'"':e.attr("id")&&(id='id="markItUp'+e.attr("id").substr(0,1).toUpperCase()+e.attr("id").substr(1)+'"'),b.nameSpace&&(nameSpace='class="'+b.nameSpace+'"'),e.wrap("<div "+nameSpace+"></div>"),e.wrap("<div "+id+' class="markItUp"></div>'),e.wrap('<div class="markItUpContainer"></div>'),
e.addClass("markItUpEditor"),x=c('<div class="markItUpHeader"></div>').insertBefore(e),c(n(b.markupSet)).appendTo(x),I=c('<div class="markItUpFooter"></div>').insertAfter(e),!0===b.resizeHandle&&!0!==c.browser.safari&&(resizeHandle=c('<div class="markItUpResizeHandle"></div>').insertAfter(e).bind("mousedown.markItUp",function(a){var b=e.height(),d=a.clientY,f,g;f=function(a){e.css("height",Math.max(20,a.clientY+b-d)+"px");return!1};g=function(){c("html").unbind("mousemove.markItUp",f).unbind("mouseup.markItUp",
g);return!1};c("html").bind("mousemove.markItUp",f).bind("mouseup.markItUp",g)}),I.append(resizeHandle)),e.bind("keydown.markItUp",H).bind("keyup",H),e.bind("insertion.markItUp",function(a,b){!1!==b.target&&z();d===c.markItUp.focused&&r(b)}),e.bind("focus.markItUp",function(){c.markItUp.focused=this}),b.previewInElement&&G()})};c.fn.markItUpRemove=function(){return this.each(function(){c(this).markItUp("remove")})};c.markItUp=function(n){var f={target:!1};c.extend(f,n);if(f.target)return c(f.target).each(function(){c(this).focus();
c(this).trigger("insertion",[f])});c("textarea").trigger("insertion",[f])}})(jQuery);