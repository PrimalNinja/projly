/*! jQuery UI - v1.10.4 - 2014-05-10
* http://jqueryui.com
* Includes: jquery.ui.core.js, jquery.ui.widget.js, jquery.ui.mouse.js, jquery.ui.position.js, jquery.ui.draggable.js, jquery.ui.droppable.js, jquery.ui.resizable.js, jquery.ui.selectable.js, jquery.ui.sortable.js, jquery.ui.autocomplete.js, jquery.ui.datepicker.js, jquery.ui.menu.js
* Copyright 2014 jQuery Foundation and other contributors; Licensed MIT */

(function(e,t){function i(t,i){var s,a,o,r=t.nodeName.toLowerCase();return"area"===r?(s=t.parentNode,a=s.name,t.href&&a&&"map"===s.nodeName.toLowerCase()?(o=e("img[usemap=#"+a+"]")[0],!!o&&n(o)):!1):(/input|select|textarea|button|object/.test(r)?!t.disabled:"a"===r?t.href||i:i)&&n(t)}function n(t){return e.expr.filters.visible(t)&&!e(t).parents().addBack().filter(function(){return"hidden"===e.css(this,"visibility")}).length}var s=0,a=/^ui-id-\d+$/;e.ui=e.ui||{},e.extend(e.ui,{version:"1.10.4",keyCode:{BACKSPACE:8,COMMA:188,DELETE:46,DOWN:40,END:35,ENTER:13,ESCAPE:27,HOME:36,LEFT:37,NUMPAD_ADD:107,NUMPAD_DECIMAL:110,NUMPAD_DIVIDE:111,NUMPAD_ENTER:108,NUMPAD_MULTIPLY:106,NUMPAD_SUBTRACT:109,PAGE_DOWN:34,PAGE_UP:33,PERIOD:190,RIGHT:39,SPACE:32,TAB:9,UP:38}}),e.fn.extend({focus:function(t){return function(i,n){return"number"==typeof i?this.each(function(){var t=this;setTimeout(function(){e(t).focus(),n&&n.call(t)},i)}):t.apply(this,arguments)}}(e.fn.focus),scrollParent:function(){var t;return t=e.ui.ie&&/(static|relative)/.test(this.css("position"))||/absolute/.test(this.css("position"))?this.parents().filter(function(){return/(relative|absolute|fixed)/.test(e.css(this,"position"))&&/(auto|scroll)/.test(e.css(this,"overflow")+e.css(this,"overflow-y")+e.css(this,"overflow-x"))}).eq(0):this.parents().filter(function(){return/(auto|scroll)/.test(e.css(this,"overflow")+e.css(this,"overflow-y")+e.css(this,"overflow-x"))}).eq(0),/fixed/.test(this.css("position"))||!t.length?e(document):t},zIndex:function(i){if(i!==t)return this.css("zIndex",i);if(this.length)for(var n,s,a=e(this[0]);a.length&&a[0]!==document;){if(n=a.css("position"),("absolute"===n||"relative"===n||"fixed"===n)&&(s=parseInt(a.css("zIndex"),10),!isNaN(s)&&0!==s))return s;a=a.parent()}return 0},uniqueId:function(){return this.each(function(){this.id||(this.id="ui-id-"+ ++s)})},removeUniqueId:function(){return this.each(function(){a.test(this.id)&&e(this).removeAttr("id")})}}),e.extend(e.expr[":"],{data:e.expr.createPseudo?e.expr.createPseudo(function(t){return function(i){return!!e.data(i,t)}}):function(t,i,n){return!!e.data(t,n[3])},focusable:function(t){return i(t,!isNaN(e.attr(t,"tabindex")))},tabbable:function(t){var n=e.attr(t,"tabindex"),s=isNaN(n);return(s||n>=0)&&i(t,!s)}}),e("<a>").outerWidth(1).jquery||e.each(["Width","Height"],function(i,n){function s(t,i,n,s){return e.each(a,function(){i-=parseFloat(e.css(t,"padding"+this))||0,n&&(i-=parseFloat(e.css(t,"border"+this+"Width"))||0),s&&(i-=parseFloat(e.css(t,"margin"+this))||0)}),i}var a="Width"===n?["Left","Right"]:["Top","Bottom"],o=n.toLowerCase(),r={innerWidth:e.fn.innerWidth,innerHeight:e.fn.innerHeight,outerWidth:e.fn.outerWidth,outerHeight:e.fn.outerHeight};e.fn["inner"+n]=function(i){return i===t?r["inner"+n].call(this):this.each(function(){e(this).css(o,s(this,i)+"px")})},e.fn["outer"+n]=function(t,i){return"number"!=typeof t?r["outer"+n].call(this,t):this.each(function(){e(this).css(o,s(this,t,!0,i)+"px")})}}),e.fn.addBack||(e.fn.addBack=function(e){return this.add(null==e?this.prevObject:this.prevObject.filter(e))}),e("<a>").data("a-b","a").removeData("a-b").data("a-b")&&(e.fn.removeData=function(t){return function(i){return arguments.length?t.call(this,e.camelCase(i)):t.call(this)}}(e.fn.removeData)),e.ui.ie=!!/msie [\w.]+/.exec(navigator.userAgent.toLowerCase()),e.support.selectstart="onselectstart"in document.createElement("div"),e.fn.extend({disableSelection:function(){return this.bind((e.support.selectstart?"selectstart":"mousedown")+".ui-disableSelection",function(e){e.preventDefault()})},enableSelection:function(){return this.unbind(".ui-disableSelection")}}),e.extend(e.ui,{plugin:{add:function(t,i,n){var s,a=e.ui[t].prototype;for(s in n)a.plugins[s]=a.plugins[s]||[],a.plugins[s].push([i,n[s]])},call:function(e,t,i){var n,s=e.plugins[t];if(s&&e.element[0].parentNode&&11!==e.element[0].parentNode.nodeType)for(n=0;s.length>n;n++)e.options[s[n][0]]&&s[n][1].apply(e.element,i)}},hasScroll:function(t,i){if("hidden"===e(t).css("overflow"))return!1;var n=i&&"left"===i?"scrollLeft":"scrollTop",s=!1;return t[n]>0?!0:(t[n]=1,s=t[n]>0,t[n]=0,s)}})})(jQuery);(function(t,e){var i=0,s=Array.prototype.slice,n=t.cleanData;t.cleanData=function(e){for(var i,s=0;null!=(i=e[s]);s++)try{t(i).triggerHandler("remove")}catch(o){}n(e)},t.widget=function(i,s,n){var o,a,r,h,l={},c=i.split(".")[0];i=i.split(".")[1],o=c+"-"+i,n||(n=s,s=t.Widget),t.expr[":"][o.toLowerCase()]=function(e){return!!t.data(e,o)},t[c]=t[c]||{},a=t[c][i],r=t[c][i]=function(t,i){return this._createWidget?(arguments.length&&this._createWidget(t,i),e):new r(t,i)},t.extend(r,a,{version:n.version,_proto:t.extend({},n),_childConstructors:[]}),h=new s,h.options=t.widget.extend({},h.options),t.each(n,function(i,n){return t.isFunction(n)?(l[i]=function(){var t=function(){return s.prototype[i].apply(this,arguments)},e=function(t){return s.prototype[i].apply(this,t)};return function(){var i,s=this._super,o=this._superApply;return this._super=t,this._superApply=e,i=n.apply(this,arguments),this._super=s,this._superApply=o,i}}(),e):(l[i]=n,e)}),r.prototype=t.widget.extend(h,{widgetEventPrefix:a?h.widgetEventPrefix||i:i},l,{constructor:r,namespace:c,widgetName:i,widgetFullName:o}),a?(t.each(a._childConstructors,function(e,i){var s=i.prototype;t.widget(s.namespace+"."+s.widgetName,r,i._proto)}),delete a._childConstructors):s._childConstructors.push(r),t.widget.bridge(i,r)},t.widget.extend=function(i){for(var n,o,a=s.call(arguments,1),r=0,h=a.length;h>r;r++)for(n in a[r])o=a[r][n],a[r].hasOwnProperty(n)&&o!==e&&(i[n]=t.isPlainObject(o)?t.isPlainObject(i[n])?t.widget.extend({},i[n],o):t.widget.extend({},o):o);return i},t.widget.bridge=function(i,n){var o=n.prototype.widgetFullName||i;t.fn[i]=function(a){var r="string"==typeof a,h=s.call(arguments,1),l=this;return a=!r&&h.length?t.widget.extend.apply(null,[a].concat(h)):a,r?this.each(function(){var s,n=t.data(this,o);return n?t.isFunction(n[a])&&"_"!==a.charAt(0)?(s=n[a].apply(n,h),s!==n&&s!==e?(l=s&&s.jquery?l.pushStack(s.get()):s,!1):e):t.error("no such method '"+a+"' for "+i+" widget instance"):t.error("cannot call methods on "+i+" prior to initialization; "+"attempted to call method '"+a+"'")}):this.each(function(){var e=t.data(this,o);e?e.option(a||{})._init():t.data(this,o,new n(a,this))}),l}},t.Widget=function(){},t.Widget._childConstructors=[],t.Widget.prototype={widgetName:"widget",widgetEventPrefix:"",defaultElement:"<div>",options:{disabled:!1,create:null},_createWidget:function(e,s){s=t(s||this.defaultElement||this)[0],this.element=t(s),this.uuid=i++,this.eventNamespace="."+this.widgetName+this.uuid,this.options=t.widget.extend({},this.options,this._getCreateOptions(),e),this.bindings=t(),this.hoverable=t(),this.focusable=t(),s!==this&&(t.data(s,this.widgetFullName,this),this._on(!0,this.element,{remove:function(t){t.target===s&&this.destroy()}}),this.document=t(s.style?s.ownerDocument:s.document||s),this.window=t(this.document[0].defaultView||this.document[0].parentWindow)),this._create(),this._trigger("create",null,this._getCreateEventData()),this._init()},_getCreateOptions:t.noop,_getCreateEventData:t.noop,_create:t.noop,_init:t.noop,destroy:function(){this._destroy(),this.element.unbind(this.eventNamespace).removeData(this.widgetName).removeData(this.widgetFullName).removeData(t.camelCase(this.widgetFullName)),this.widget().unbind(this.eventNamespace).removeAttr("aria-disabled").removeClass(this.widgetFullName+"-disabled "+"ui-state-disabled"),this.bindings.unbind(this.eventNamespace),this.hoverable.removeClass("ui-state-hover"),this.focusable.removeClass("ui-state-focus")},_destroy:t.noop,widget:function(){return this.element},option:function(i,s){var n,o,a,r=i;if(0===arguments.length)return t.widget.extend({},this.options);if("string"==typeof i)if(r={},n=i.split("."),i=n.shift(),n.length){for(o=r[i]=t.widget.extend({},this.options[i]),a=0;n.length-1>a;a++)o[n[a]]=o[n[a]]||{},o=o[n[a]];if(i=n.pop(),1===arguments.length)return o[i]===e?null:o[i];o[i]=s}else{if(1===arguments.length)return this.options[i]===e?null:this.options[i];r[i]=s}return this._setOptions(r),this},_setOptions:function(t){var e;for(e in t)this._setOption(e,t[e]);return this},_setOption:function(t,e){return this.options[t]=e,"disabled"===t&&(this.widget().toggleClass(this.widgetFullName+"-disabled ui-state-disabled",!!e).attr("aria-disabled",e),this.hoverable.removeClass("ui-state-hover"),this.focusable.removeClass("ui-state-focus")),this},enable:function(){return this._setOption("disabled",!1)},disable:function(){return this._setOption("disabled",!0)},_on:function(i,s,n){var o,a=this;"boolean"!=typeof i&&(n=s,s=i,i=!1),n?(s=o=t(s),this.bindings=this.bindings.add(s)):(n=s,s=this.element,o=this.widget()),t.each(n,function(n,r){function h(){return i||a.options.disabled!==!0&&!t(this).hasClass("ui-state-disabled")?("string"==typeof r?a[r]:r).apply(a,arguments):e}"string"!=typeof r&&(h.guid=r.guid=r.guid||h.guid||t.guid++);var l=n.match(/^(\w+)\s*(.*)$/),c=l[1]+a.eventNamespace,u=l[2];u?o.delegate(u,c,h):s.bind(c,h)})},_off:function(t,e){e=(e||"").split(" ").join(this.eventNamespace+" ")+this.eventNamespace,t.unbind(e).undelegate(e)},_delay:function(t,e){function i(){return("string"==typeof t?s[t]:t).apply(s,arguments)}var s=this;return setTimeout(i,e||0)},_hoverable:function(e){this.hoverable=this.hoverable.add(e),this._on(e,{mouseenter:function(e){t(e.currentTarget).addClass("ui-state-hover")},mouseleave:function(e){t(e.currentTarget).removeClass("ui-state-hover")}})},_focusable:function(e){this.focusable=this.focusable.add(e),this._on(e,{focusin:function(e){t(e.currentTarget).addClass("ui-state-focus")},focusout:function(e){t(e.currentTarget).removeClass("ui-state-focus")}})},_trigger:function(e,i,s){var n,o,a=this.options[e];if(s=s||{},i=t.Event(i),i.type=(e===this.widgetEventPrefix?e:this.widgetEventPrefix+e).toLowerCase(),i.target=this.element[0],o=i.originalEvent)for(n in o)n in i||(i[n]=o[n]);return this.element.trigger(i,s),!(t.isFunction(a)&&a.apply(this.element[0],[i].concat(s))===!1||i.isDefaultPrevented())}},t.each({show:"fadeIn",hide:"fadeOut"},function(e,i){t.Widget.prototype["_"+e]=function(s,n,o){"string"==typeof n&&(n={effect:n});var a,r=n?n===!0||"number"==typeof n?i:n.effect||i:e;n=n||{},"number"==typeof n&&(n={duration:n}),a=!t.isEmptyObject(n),n.complete=o,n.delay&&s.delay(n.delay),a&&t.effects&&t.effects.effect[r]?s[e](n):r!==e&&s[r]?s[r](n.duration,n.easing,o):s.queue(function(i){t(this)[e](),o&&o.call(s[0]),i()})}})})(jQuery);(function(t){var e=!1;t(document).mouseup(function(){e=!1}),t.widget("ui.mouse",{version:"1.10.4",options:{cancel:"input,textarea,button,select,option",distance:1,delay:0},_mouseInit:function(){var e=this;this.element.bind("mousedown."+this.widgetName,function(t){return e._mouseDown(t)}).bind("click."+this.widgetName,function(i){return!0===t.data(i.target,e.widgetName+".preventClickEvent")?(t.removeData(i.target,e.widgetName+".preventClickEvent"),i.stopImmediatePropagation(),!1):undefined}),this.started=!1},_mouseDestroy:function(){this.element.unbind("."+this.widgetName),this._mouseMoveDelegate&&t(document).unbind("mousemove."+this.widgetName,this._mouseMoveDelegate).unbind("mouseup."+this.widgetName,this._mouseUpDelegate)},_mouseDown:function(i){if(!e){this._mouseStarted&&this._mouseUp(i),this._mouseDownEvent=i;var s=this,n=1===i.which,a="string"==typeof this.options.cancel&&i.target.nodeName?t(i.target).closest(this.options.cancel).length:!1;return n&&!a&&this._mouseCapture(i)?(this.mouseDelayMet=!this.options.delay,this.mouseDelayMet||(this._mouseDelayTimer=setTimeout(function(){s.mouseDelayMet=!0},this.options.delay)),this._mouseDistanceMet(i)&&this._mouseDelayMet(i)&&(this._mouseStarted=this._mouseStart(i)!==!1,!this._mouseStarted)?(i.preventDefault(),!0):(!0===t.data(i.target,this.widgetName+".preventClickEvent")&&t.removeData(i.target,this.widgetName+".preventClickEvent"),this._mouseMoveDelegate=function(t){return s._mouseMove(t)},this._mouseUpDelegate=function(t){return s._mouseUp(t)},t(document).bind("mousemove."+this.widgetName,this._mouseMoveDelegate).bind("mouseup."+this.widgetName,this._mouseUpDelegate),i.preventDefault(),e=!0,!0)):!0}},_mouseMove:function(e){return t.ui.ie&&(!document.documentMode||9>document.documentMode)&&!e.button?this._mouseUp(e):this._mouseStarted?(this._mouseDrag(e),e.preventDefault()):(this._mouseDistanceMet(e)&&this._mouseDelayMet(e)&&(this._mouseStarted=this._mouseStart(this._mouseDownEvent,e)!==!1,this._mouseStarted?this._mouseDrag(e):this._mouseUp(e)),!this._mouseStarted)},_mouseUp:function(e){return t(document).unbind("mousemove."+this.widgetName,this._mouseMoveDelegate).unbind("mouseup."+this.widgetName,this._mouseUpDelegate),this._mouseStarted&&(this._mouseStarted=!1,e.target===this._mouseDownEvent.target&&t.data(e.target,this.widgetName+".preventClickEvent",!0),this._mouseStop(e)),!1},_mouseDistanceMet:function(t){return Math.max(Math.abs(this._mouseDownEvent.pageX-t.pageX),Math.abs(this._mouseDownEvent.pageY-t.pageY))>=this.options.distance},_mouseDelayMet:function(){return this.mouseDelayMet},_mouseStart:function(){},_mouseDrag:function(){},_mouseStop:function(){},_mouseCapture:function(){return!0}})})(jQuery);(function(t,e){function i(t,e,i){return[parseFloat(t[0])*(p.test(t[0])?e/100:1),parseFloat(t[1])*(p.test(t[1])?i/100:1)]}function s(e,i){return parseInt(t.css(e,i),10)||0}function n(e){var i=e[0];return 9===i.nodeType?{width:e.width(),height:e.height(),offset:{top:0,left:0}}:t.isWindow(i)?{width:e.width(),height:e.height(),offset:{top:e.scrollTop(),left:e.scrollLeft()}}:i.preventDefault?{width:0,height:0,offset:{top:i.pageY,left:i.pageX}}:{width:e.outerWidth(),height:e.outerHeight(),offset:e.offset()}}t.ui=t.ui||{};var a,o=Math.max,r=Math.abs,l=Math.round,h=/left|center|right/,c=/top|center|bottom/,u=/[\+\-]\d+(\.[\d]+)?%?/,d=/^\w+/,p=/%$/,f=t.fn.position;t.position={scrollbarWidth:function(){if(a!==e)return a;var i,s,n=t("<div style='display:block;position:absolute;width:50px;height:50px;overflow:hidden;'><div style='height:100px;width:auto;'></div></div>"),o=n.children()[0];return t("body").append(n),i=o.offsetWidth,n.css("overflow","scroll"),s=o.offsetWidth,i===s&&(s=n[0].clientWidth),n.remove(),a=i-s},getScrollInfo:function(e){var i=e.isWindow||e.isDocument?"":e.element.css("overflow-x"),s=e.isWindow||e.isDocument?"":e.element.css("overflow-y"),n="scroll"===i||"auto"===i&&e.width<e.element[0].scrollWidth,a="scroll"===s||"auto"===s&&e.height<e.element[0].scrollHeight;return{width:a?t.position.scrollbarWidth():0,height:n?t.position.scrollbarWidth():0}},getWithinInfo:function(e){var i=t(e||window),s=t.isWindow(i[0]),n=!!i[0]&&9===i[0].nodeType;return{element:i,isWindow:s,isDocument:n,offset:i.offset()||{left:0,top:0},scrollLeft:i.scrollLeft(),scrollTop:i.scrollTop(),width:s?i.width():i.outerWidth(),height:s?i.height():i.outerHeight()}}},t.fn.position=function(e){if(!e||!e.of)return f.apply(this,arguments);e=t.extend({},e);var a,p,g,m,v,_,b=t(e.of),y=t.position.getWithinInfo(e.within),k=t.position.getScrollInfo(y),w=(e.collision||"flip").split(" "),D={};return _=n(b),b[0].preventDefault&&(e.at="left top"),p=_.width,g=_.height,m=_.offset,v=t.extend({},m),t.each(["my","at"],function(){var t,i,s=(e[this]||"").split(" ");1===s.length&&(s=h.test(s[0])?s.concat(["center"]):c.test(s[0])?["center"].concat(s):["center","center"]),s[0]=h.test(s[0])?s[0]:"center",s[1]=c.test(s[1])?s[1]:"center",t=u.exec(s[0]),i=u.exec(s[1]),D[this]=[t?t[0]:0,i?i[0]:0],e[this]=[d.exec(s[0])[0],d.exec(s[1])[0]]}),1===w.length&&(w[1]=w[0]),"right"===e.at[0]?v.left+=p:"center"===e.at[0]&&(v.left+=p/2),"bottom"===e.at[1]?v.top+=g:"center"===e.at[1]&&(v.top+=g/2),a=i(D.at,p,g),v.left+=a[0],v.top+=a[1],this.each(function(){var n,h,c=t(this),u=c.outerWidth(),d=c.outerHeight(),f=s(this,"marginLeft"),_=s(this,"marginTop"),x=u+f+s(this,"marginRight")+k.width,C=d+_+s(this,"marginBottom")+k.height,M=t.extend({},v),T=i(D.my,c.outerWidth(),c.outerHeight());"right"===e.my[0]?M.left-=u:"center"===e.my[0]&&(M.left-=u/2),"bottom"===e.my[1]?M.top-=d:"center"===e.my[1]&&(M.top-=d/2),M.left+=T[0],M.top+=T[1],t.support.offsetFractions||(M.left=l(M.left),M.top=l(M.top)),n={marginLeft:f,marginTop:_},t.each(["left","top"],function(i,s){t.ui.position[w[i]]&&t.ui.position[w[i]][s](M,{targetWidth:p,targetHeight:g,elemWidth:u,elemHeight:d,collisionPosition:n,collisionWidth:x,collisionHeight:C,offset:[a[0]+T[0],a[1]+T[1]],my:e.my,at:e.at,within:y,elem:c})}),e.using&&(h=function(t){var i=m.left-M.left,s=i+p-u,n=m.top-M.top,a=n+g-d,l={target:{element:b,left:m.left,top:m.top,width:p,height:g},element:{element:c,left:M.left,top:M.top,width:u,height:d},horizontal:0>s?"left":i>0?"right":"center",vertical:0>a?"top":n>0?"bottom":"middle"};u>p&&p>r(i+s)&&(l.horizontal="center"),d>g&&g>r(n+a)&&(l.vertical="middle"),l.important=o(r(i),r(s))>o(r(n),r(a))?"horizontal":"vertical",e.using.call(this,t,l)}),c.offset(t.extend(M,{using:h}))})},t.ui.position={fit:{left:function(t,e){var i,s=e.within,n=s.isWindow?s.scrollLeft:s.offset.left,a=s.width,r=t.left-e.collisionPosition.marginLeft,l=n-r,h=r+e.collisionWidth-a-n;e.collisionWidth>a?l>0&&0>=h?(i=t.left+l+e.collisionWidth-a-n,t.left+=l-i):t.left=h>0&&0>=l?n:l>h?n+a-e.collisionWidth:n:l>0?t.left+=l:h>0?t.left-=h:t.left=o(t.left-r,t.left)},top:function(t,e){var i,s=e.within,n=s.isWindow?s.scrollTop:s.offset.top,a=e.within.height,r=t.top-e.collisionPosition.marginTop,l=n-r,h=r+e.collisionHeight-a-n;e.collisionHeight>a?l>0&&0>=h?(i=t.top+l+e.collisionHeight-a-n,t.top+=l-i):t.top=h>0&&0>=l?n:l>h?n+a-e.collisionHeight:n:l>0?t.top+=l:h>0?t.top-=h:t.top=o(t.top-r,t.top)}},flip:{left:function(t,e){var i,s,n=e.within,a=n.offset.left+n.scrollLeft,o=n.width,l=n.isWindow?n.scrollLeft:n.offset.left,h=t.left-e.collisionPosition.marginLeft,c=h-l,u=h+e.collisionWidth-o-l,d="left"===e.my[0]?-e.elemWidth:"right"===e.my[0]?e.elemWidth:0,p="left"===e.at[0]?e.targetWidth:"right"===e.at[0]?-e.targetWidth:0,f=-2*e.offset[0];0>c?(i=t.left+d+p+f+e.collisionWidth-o-a,(0>i||r(c)>i)&&(t.left+=d+p+f)):u>0&&(s=t.left-e.collisionPosition.marginLeft+d+p+f-l,(s>0||u>r(s))&&(t.left+=d+p+f))},top:function(t,e){var i,s,n=e.within,a=n.offset.top+n.scrollTop,o=n.height,l=n.isWindow?n.scrollTop:n.offset.top,h=t.top-e.collisionPosition.marginTop,c=h-l,u=h+e.collisionHeight-o-l,d="top"===e.my[1],p=d?-e.elemHeight:"bottom"===e.my[1]?e.elemHeight:0,f="top"===e.at[1]?e.targetHeight:"bottom"===e.at[1]?-e.targetHeight:0,g=-2*e.offset[1];0>c?(s=t.top+p+f+g+e.collisionHeight-o-a,t.top+p+f+g>c&&(0>s||r(c)>s)&&(t.top+=p+f+g)):u>0&&(i=t.top-e.collisionPosition.marginTop+p+f+g-l,t.top+p+f+g>u&&(i>0||u>r(i))&&(t.top+=p+f+g))}},flipfit:{left:function(){t.ui.position.flip.left.apply(this,arguments),t.ui.position.fit.left.apply(this,arguments)},top:function(){t.ui.position.flip.top.apply(this,arguments),t.ui.position.fit.top.apply(this,arguments)}}},function(){var e,i,s,n,a,o=document.getElementsByTagName("body")[0],r=document.createElement("div");e=document.createElement(o?"div":"body"),s={visibility:"hidden",width:0,height:0,border:0,margin:0,background:"none"},o&&t.extend(s,{position:"absolute",left:"-1000px",top:"-1000px"});for(a in s)e.style[a]=s[a];e.appendChild(r),i=o||document.documentElement,i.insertBefore(e,i.firstChild),r.style.cssText="position: absolute; left: 10.7432222px;",n=t(r).offset().left,t.support.offsetFractions=n>10&&11>n,e.innerHTML="",i.removeChild(e)}()})(jQuery);(function(t){t.widget("ui.draggable",t.ui.mouse,{version:"1.10.4",widgetEventPrefix:"drag",options:{addClasses:!0,appendTo:"parent",axis:!1,connectToSortable:!1,containment:!1,cursor:"auto",cursorAt:!1,grid:!1,handle:!1,helper:"original",iframeFix:!1,opacity:!1,refreshPositions:!1,revert:!1,revertDuration:500,scope:"default",scroll:!0,scrollSensitivity:20,scrollSpeed:20,snap:!1,snapMode:"both",snapTolerance:20,stack:!1,zIndex:!1,drag:null,start:null,stop:null},_create:function(){"original"!==this.options.helper||/^(?:r|a|f)/.test(this.element.css("position"))||(this.element[0].style.position="relative"),this.options.addClasses&&this.element.addClass("ui-draggable"),this.options.disabled&&this.element.addClass("ui-draggable-disabled"),this._mouseInit()},_destroy:function(){this.element.removeClass("ui-draggable ui-draggable-dragging ui-draggable-disabled"),this._mouseDestroy()},_mouseCapture:function(e){var i=this.options;return this.helper||i.disabled||t(e.target).closest(".ui-resizable-handle").length>0?!1:(this.handle=this._getHandle(e),this.handle?(t(i.iframeFix===!0?"iframe":i.iframeFix).each(function(){t("<div class='ui-draggable-iframeFix' style='background: #fff;'></div>").css({width:this.offsetWidth+"px",height:this.offsetHeight+"px",position:"absolute",opacity:"0.001",zIndex:1e3}).css(t(this).offset()).appendTo("body")}),!0):!1)},_mouseStart:function(e){var i=this.options;return this.helper=this._createHelper(e),this.helper.addClass("ui-draggable-dragging"),this._cacheHelperProportions(),t.ui.ddmanager&&(t.ui.ddmanager.current=this),this._cacheMargins(),this.cssPosition=this.helper.css("position"),this.scrollParent=this.helper.scrollParent(),this.offsetParent=this.helper.offsetParent(),this.offsetParentCssPosition=this.offsetParent.css("position"),this.offset=this.positionAbs=this.element.offset(),this.offset={top:this.offset.top-this.margins.top,left:this.offset.left-this.margins.left},this.offset.scroll=!1,t.extend(this.offset,{click:{left:e.pageX-this.offset.left,top:e.pageY-this.offset.top},parent:this._getParentOffset(),relative:this._getRelativeOffset()}),this.originalPosition=this.position=this._generatePosition(e),this.originalPageX=e.pageX,this.originalPageY=e.pageY,i.cursorAt&&this._adjustOffsetFromHelper(i.cursorAt),this._setContainment(),this._trigger("start",e)===!1?(this._clear(),!1):(this._cacheHelperProportions(),t.ui.ddmanager&&!i.dropBehaviour&&t.ui.ddmanager.prepareOffsets(this,e),this._mouseDrag(e,!0),t.ui.ddmanager&&t.ui.ddmanager.dragStart(this,e),!0)},_mouseDrag:function(e,i){if("fixed"===this.offsetParentCssPosition&&(this.offset.parent=this._getParentOffset()),this.position=this._generatePosition(e),this.positionAbs=this._convertPositionTo("absolute"),!i){var s=this._uiHash();if(this._trigger("drag",e,s)===!1)return this._mouseUp({}),!1;this.position=s.position}return this.options.axis&&"y"===this.options.axis||(this.helper[0].style.left=this.position.left+"px"),this.options.axis&&"x"===this.options.axis||(this.helper[0].style.top=this.position.top+"px"),t.ui.ddmanager&&t.ui.ddmanager.drag(this,e),!1},_mouseStop:function(e){var i=this,s=!1;return t.ui.ddmanager&&!this.options.dropBehaviour&&(s=t.ui.ddmanager.drop(this,e)),this.dropped&&(s=this.dropped,this.dropped=!1),"original"!==this.options.helper||t.contains(this.element[0].ownerDocument,this.element[0])?("invalid"===this.options.revert&&!s||"valid"===this.options.revert&&s||this.options.revert===!0||t.isFunction(this.options.revert)&&this.options.revert.call(this.element,s)?t(this.helper).animate(this.originalPosition,parseInt(this.options.revertDuration,10),function(){i._trigger("stop",e)!==!1&&i._clear()}):this._trigger("stop",e)!==!1&&this._clear(),!1):!1},_mouseUp:function(e){return t("div.ui-draggable-iframeFix").each(function(){this.parentNode.removeChild(this)}),t.ui.ddmanager&&t.ui.ddmanager.dragStop(this,e),t.ui.mouse.prototype._mouseUp.call(this,e)},cancel:function(){return this.helper.is(".ui-draggable-dragging")?this._mouseUp({}):this._clear(),this},_getHandle:function(e){return this.options.handle?!!t(e.target).closest(this.element.find(this.options.handle)).length:!0},_createHelper:function(e){var i=this.options,s=t.isFunction(i.helper)?t(i.helper.apply(this.element[0],[e])):"clone"===i.helper?this.element.clone().removeAttr("id"):this.element;return s.parents("body").length||s.appendTo("parent"===i.appendTo?this.element[0].parentNode:i.appendTo),s[0]===this.element[0]||/(fixed|absolute)/.test(s.css("position"))||s.css("position","absolute"),s},_adjustOffsetFromHelper:function(e){"string"==typeof e&&(e=e.split(" ")),t.isArray(e)&&(e={left:+e[0],top:+e[1]||0}),"left"in e&&(this.offset.click.left=e.left+this.margins.left),"right"in e&&(this.offset.click.left=this.helperProportions.width-e.right+this.margins.left),"top"in e&&(this.offset.click.top=e.top+this.margins.top),"bottom"in e&&(this.offset.click.top=this.helperProportions.height-e.bottom+this.margins.top)},_getParentOffset:function(){var e=this.offsetParent.offset();return"absolute"===this.cssPosition&&this.scrollParent[0]!==document&&t.contains(this.scrollParent[0],this.offsetParent[0])&&(e.left+=this.scrollParent.scrollLeft(),e.top+=this.scrollParent.scrollTop()),(this.offsetParent[0]===document.body||this.offsetParent[0].tagName&&"html"===this.offsetParent[0].tagName.toLowerCase()&&t.ui.ie)&&(e={top:0,left:0}),{top:e.top+(parseInt(this.offsetParent.css("borderTopWidth"),10)||0),left:e.left+(parseInt(this.offsetParent.css("borderLeftWidth"),10)||0)}},_getRelativeOffset:function(){if("relative"===this.cssPosition){var t=this.element.position();return{top:t.top-(parseInt(this.helper.css("top"),10)||0)+this.scrollParent.scrollTop(),left:t.left-(parseInt(this.helper.css("left"),10)||0)+this.scrollParent.scrollLeft()}}return{top:0,left:0}},_cacheMargins:function(){this.margins={left:parseInt(this.element.css("marginLeft"),10)||0,top:parseInt(this.element.css("marginTop"),10)||0,right:parseInt(this.element.css("marginRight"),10)||0,bottom:parseInt(this.element.css("marginBottom"),10)||0}},_cacheHelperProportions:function(){this.helperProportions={width:this.helper.outerWidth(),height:this.helper.outerHeight()}},_setContainment:function(){var e,i,s,n=this.options;return n.containment?"window"===n.containment?(this.containment=[t(window).scrollLeft()-this.offset.relative.left-this.offset.parent.left,t(window).scrollTop()-this.offset.relative.top-this.offset.parent.top,t(window).scrollLeft()+t(window).width()-this.helperProportions.width-this.margins.left,t(window).scrollTop()+(t(window).height()||document.body.parentNode.scrollHeight)-this.helperProportions.height-this.margins.top],undefined):"document"===n.containment?(this.containment=[0,0,t(document).width()-this.helperProportions.width-this.margins.left,(t(document).height()||document.body.parentNode.scrollHeight)-this.helperProportions.height-this.margins.top],undefined):n.containment.constructor===Array?(this.containment=n.containment,undefined):("parent"===n.containment&&(n.containment=this.helper[0].parentNode),i=t(n.containment),s=i[0],s&&(e="hidden"!==i.css("overflow"),this.containment=[(parseInt(i.css("borderLeftWidth"),10)||0)+(parseInt(i.css("paddingLeft"),10)||0),(parseInt(i.css("borderTopWidth"),10)||0)+(parseInt(i.css("paddingTop"),10)||0),(e?Math.max(s.scrollWidth,s.offsetWidth):s.offsetWidth)-(parseInt(i.css("borderRightWidth"),10)||0)-(parseInt(i.css("paddingRight"),10)||0)-this.helperProportions.width-this.margins.left-this.margins.right,(e?Math.max(s.scrollHeight,s.offsetHeight):s.offsetHeight)-(parseInt(i.css("borderBottomWidth"),10)||0)-(parseInt(i.css("paddingBottom"),10)||0)-this.helperProportions.height-this.margins.top-this.margins.bottom],this.relative_container=i),undefined):(this.containment=null,undefined)},_convertPositionTo:function(e,i){i||(i=this.position);var s="absolute"===e?1:-1,n="absolute"!==this.cssPosition||this.scrollParent[0]!==document&&t.contains(this.scrollParent[0],this.offsetParent[0])?this.scrollParent:this.offsetParent;return this.offset.scroll||(this.offset.scroll={top:n.scrollTop(),left:n.scrollLeft()}),{top:i.top+this.offset.relative.top*s+this.offset.parent.top*s-("fixed"===this.cssPosition?-this.scrollParent.scrollTop():this.offset.scroll.top)*s,left:i.left+this.offset.relative.left*s+this.offset.parent.left*s-("fixed"===this.cssPosition?-this.scrollParent.scrollLeft():this.offset.scroll.left)*s}},_generatePosition:function(e){var i,s,n,a,o=this.options,r="absolute"!==this.cssPosition||this.scrollParent[0]!==document&&t.contains(this.scrollParent[0],this.offsetParent[0])?this.scrollParent:this.offsetParent,l=e.pageX,h=e.pageY;return this.offset.scroll||(this.offset.scroll={top:r.scrollTop(),left:r.scrollLeft()}),this.originalPosition&&(this.containment&&(this.relative_container?(s=this.relative_container.offset(),i=[this.containment[0]+s.left,this.containment[1]+s.top,this.containment[2]+s.left,this.containment[3]+s.top]):i=this.containment,e.pageX-this.offset.click.left<i[0]&&(l=i[0]+this.offset.click.left),e.pageY-this.offset.click.top<i[1]&&(h=i[1]+this.offset.click.top),e.pageX-this.offset.click.left>i[2]&&(l=i[2]+this.offset.click.left),e.pageY-this.offset.click.top>i[3]&&(h=i[3]+this.offset.click.top)),o.grid&&(n=o.grid[1]?this.originalPageY+Math.round((h-this.originalPageY)/o.grid[1])*o.grid[1]:this.originalPageY,h=i?n-this.offset.click.top>=i[1]||n-this.offset.click.top>i[3]?n:n-this.offset.click.top>=i[1]?n-o.grid[1]:n+o.grid[1]:n,a=o.grid[0]?this.originalPageX+Math.round((l-this.originalPageX)/o.grid[0])*o.grid[0]:this.originalPageX,l=i?a-this.offset.click.left>=i[0]||a-this.offset.click.left>i[2]?a:a-this.offset.click.left>=i[0]?a-o.grid[0]:a+o.grid[0]:a)),{top:h-this.offset.click.top-this.offset.relative.top-this.offset.parent.top+("fixed"===this.cssPosition?-this.scrollParent.scrollTop():this.offset.scroll.top),left:l-this.offset.click.left-this.offset.relative.left-this.offset.parent.left+("fixed"===this.cssPosition?-this.scrollParent.scrollLeft():this.offset.scroll.left)}},_clear:function(){this.helper.removeClass("ui-draggable-dragging"),this.helper[0]===this.element[0]||this.cancelHelperRemoval||this.helper.remove(),this.helper=null,this.cancelHelperRemoval=!1},_trigger:function(e,i,s){return s=s||this._uiHash(),t.ui.plugin.call(this,e,[i,s]),"drag"===e&&(this.positionAbs=this._convertPositionTo("absolute")),t.Widget.prototype._trigger.call(this,e,i,s)},plugins:{},_uiHash:function(){return{helper:this.helper,position:this.position,originalPosition:this.originalPosition,offset:this.positionAbs}}}),t.ui.plugin.add("draggable","connectToSortable",{start:function(e,i){var s=t(this).data("ui-draggable"),n=s.options,a=t.extend({},i,{item:s.element});s.sortables=[],t(n.connectToSortable).each(function(){var i=t.data(this,"ui-sortable");i&&!i.options.disabled&&(s.sortables.push({instance:i,shouldRevert:i.options.revert}),i.refreshPositions(),i._trigger("activate",e,a))})},stop:function(e,i){var s=t(this).data("ui-draggable"),n=t.extend({},i,{item:s.element});t.each(s.sortables,function(){this.instance.isOver?(this.instance.isOver=0,s.cancelHelperRemoval=!0,this.instance.cancelHelperRemoval=!1,this.shouldRevert&&(this.instance.options.revert=this.shouldRevert),this.instance._mouseStop(e),this.instance.options.helper=this.instance.options._helper,"original"===s.options.helper&&this.instance.currentItem.css({top:"auto",left:"auto"})):(this.instance.cancelHelperRemoval=!1,this.instance._trigger("deactivate",e,n))})},drag:function(e,i){var s=t(this).data("ui-draggable"),n=this;t.each(s.sortables,function(){var a=!1,o=this;this.instance.positionAbs=s.positionAbs,this.instance.helperProportions=s.helperProportions,this.instance.offset.click=s.offset.click,this.instance._intersectsWith(this.instance.containerCache)&&(a=!0,t.each(s.sortables,function(){return this.instance.positionAbs=s.positionAbs,this.instance.helperProportions=s.helperProportions,this.instance.offset.click=s.offset.click,this!==o&&this.instance._intersectsWith(this.instance.containerCache)&&t.contains(o.instance.element[0],this.instance.element[0])&&(a=!1),a})),a?(this.instance.isOver||(this.instance.isOver=1,this.instance.currentItem=t(n).clone().removeAttr("id").appendTo(this.instance.element).data("ui-sortable-item",!0),this.instance.options._helper=this.instance.options.helper,this.instance.options.helper=function(){return i.helper[0]},e.target=this.instance.currentItem[0],this.instance._mouseCapture(e,!0),this.instance._mouseStart(e,!0,!0),this.instance.offset.click.top=s.offset.click.top,this.instance.offset.click.left=s.offset.click.left,this.instance.offset.parent.left-=s.offset.parent.left-this.instance.offset.parent.left,this.instance.offset.parent.top-=s.offset.parent.top-this.instance.offset.parent.top,s._trigger("toSortable",e),s.dropped=this.instance.element,s.currentItem=s.element,this.instance.fromOutside=s),this.instance.currentItem&&this.instance._mouseDrag(e)):this.instance.isOver&&(this.instance.isOver=0,this.instance.cancelHelperRemoval=!0,this.instance.options.revert=!1,this.instance._trigger("out",e,this.instance._uiHash(this.instance)),this.instance._mouseStop(e,!0),this.instance.options.helper=this.instance.options._helper,this.instance.currentItem.remove(),this.instance.placeholder&&this.instance.placeholder.remove(),s._trigger("fromSortable",e),s.dropped=!1)})}}),t.ui.plugin.add("draggable","cursor",{start:function(){var e=t("body"),i=t(this).data("ui-draggable").options;e.css("cursor")&&(i._cursor=e.css("cursor")),e.css("cursor",i.cursor)},stop:function(){var e=t(this).data("ui-draggable").options;e._cursor&&t("body").css("cursor",e._cursor)}}),t.ui.plugin.add("draggable","opacity",{start:function(e,i){var s=t(i.helper),n=t(this).data("ui-draggable").options;s.css("opacity")&&(n._opacity=s.css("opacity")),s.css("opacity",n.opacity)},stop:function(e,i){var s=t(this).data("ui-draggable").options;s._opacity&&t(i.helper).css("opacity",s._opacity)}}),t.ui.plugin.add("draggable","scroll",{start:function(){var e=t(this).data("ui-draggable");e.scrollParent[0]!==document&&"HTML"!==e.scrollParent[0].tagName&&(e.overflowOffset=e.scrollParent.offset())},drag:function(e){var i=t(this).data("ui-draggable"),s=i.options,n=!1;i.scrollParent[0]!==document&&"HTML"!==i.scrollParent[0].tagName?(s.axis&&"x"===s.axis||(i.overflowOffset.top+i.scrollParent[0].offsetHeight-e.pageY<s.scrollSensitivity?i.scrollParent[0].scrollTop=n=i.scrollParent[0].scrollTop+s.scrollSpeed:e.pageY-i.overflowOffset.top<s.scrollSensitivity&&(i.scrollParent[0].scrollTop=n=i.scrollParent[0].scrollTop-s.scrollSpeed)),s.axis&&"y"===s.axis||(i.overflowOffset.left+i.scrollParent[0].offsetWidth-e.pageX<s.scrollSensitivity?i.scrollParent[0].scrollLeft=n=i.scrollParent[0].scrollLeft+s.scrollSpeed:e.pageX-i.overflowOffset.left<s.scrollSensitivity&&(i.scrollParent[0].scrollLeft=n=i.scrollParent[0].scrollLeft-s.scrollSpeed))):(s.axis&&"x"===s.axis||(e.pageY-t(document).scrollTop()<s.scrollSensitivity?n=t(document).scrollTop(t(document).scrollTop()-s.scrollSpeed):t(window).height()-(e.pageY-t(document).scrollTop())<s.scrollSensitivity&&(n=t(document).scrollTop(t(document).scrollTop()+s.scrollSpeed))),s.axis&&"y"===s.axis||(e.pageX-t(document).scrollLeft()<s.scrollSensitivity?n=t(document).scrollLeft(t(document).scrollLeft()-s.scrollSpeed):t(window).width()-(e.pageX-t(document).scrollLeft())<s.scrollSensitivity&&(n=t(document).scrollLeft(t(document).scrollLeft()+s.scrollSpeed)))),n!==!1&&t.ui.ddmanager&&!s.dropBehaviour&&t.ui.ddmanager.prepareOffsets(i,e)}}),t.ui.plugin.add("draggable","snap",{start:function(){var e=t(this).data("ui-draggable"),i=e.options;e.snapElements=[],t(i.snap.constructor!==String?i.snap.items||":data(ui-draggable)":i.snap).each(function(){var i=t(this),s=i.offset();this!==e.element[0]&&e.snapElements.push({item:this,width:i.outerWidth(),height:i.outerHeight(),top:s.top,left:s.left})})},drag:function(e,i){var s,n,a,o,r,l,h,c,u,d,p=t(this).data("ui-draggable"),g=p.options,f=g.snapTolerance,m=i.offset.left,_=m+p.helperProportions.width,v=i.offset.top,b=v+p.helperProportions.height;for(u=p.snapElements.length-1;u>=0;u--)r=p.snapElements[u].left,l=r+p.snapElements[u].width,h=p.snapElements[u].top,c=h+p.snapElements[u].height,r-f>_||m>l+f||h-f>b||v>c+f||!t.contains(p.snapElements[u].item.ownerDocument,p.snapElements[u].item)?(p.snapElements[u].snapping&&p.options.snap.release&&p.options.snap.release.call(p.element,e,t.extend(p._uiHash(),{snapItem:p.snapElements[u].item})),p.snapElements[u].snapping=!1):("inner"!==g.snapMode&&(s=f>=Math.abs(h-b),n=f>=Math.abs(c-v),a=f>=Math.abs(r-_),o=f>=Math.abs(l-m),s&&(i.position.top=p._convertPositionTo("relative",{top:h-p.helperProportions.height,left:0}).top-p.margins.top),n&&(i.position.top=p._convertPositionTo("relative",{top:c,left:0}).top-p.margins.top),a&&(i.position.left=p._convertPositionTo("relative",{top:0,left:r-p.helperProportions.width}).left-p.margins.left),o&&(i.position.left=p._convertPositionTo("relative",{top:0,left:l}).left-p.margins.left)),d=s||n||a||o,"outer"!==g.snapMode&&(s=f>=Math.abs(h-v),n=f>=Math.abs(c-b),a=f>=Math.abs(r-m),o=f>=Math.abs(l-_),s&&(i.position.top=p._convertPositionTo("relative",{top:h,left:0}).top-p.margins.top),n&&(i.position.top=p._convertPositionTo("relative",{top:c-p.helperProportions.height,left:0}).top-p.margins.top),a&&(i.position.left=p._convertPositionTo("relative",{top:0,left:r}).left-p.margins.left),o&&(i.position.left=p._convertPositionTo("relative",{top:0,left:l-p.helperProportions.width}).left-p.margins.left)),!p.snapElements[u].snapping&&(s||n||a||o||d)&&p.options.snap.snap&&p.options.snap.snap.call(p.element,e,t.extend(p._uiHash(),{snapItem:p.snapElements[u].item})),p.snapElements[u].snapping=s||n||a||o||d)}}),t.ui.plugin.add("draggable","stack",{start:function(){var e,i=this.data("ui-draggable").options,s=t.makeArray(t(i.stack)).sort(function(e,i){return(parseInt(t(e).css("zIndex"),10)||0)-(parseInt(t(i).css("zIndex"),10)||0)});s.length&&(e=parseInt(t(s[0]).css("zIndex"),10)||0,t(s).each(function(i){t(this).css("zIndex",e+i)}),this.css("zIndex",e+s.length))}}),t.ui.plugin.add("draggable","zIndex",{start:function(e,i){var s=t(i.helper),n=t(this).data("ui-draggable").options;s.css("zIndex")&&(n._zIndex=s.css("zIndex")),s.css("zIndex",n.zIndex)},stop:function(e,i){var s=t(this).data("ui-draggable").options;s._zIndex&&t(i.helper).css("zIndex",s._zIndex)}})})(jQuery);(function(t){function e(t,e,i){return t>e&&e+i>t}t.widget("ui.droppable",{version:"1.10.4",widgetEventPrefix:"drop",options:{accept:"*",activeClass:!1,addClasses:!0,greedy:!1,hoverClass:!1,scope:"default",tolerance:"intersect",activate:null,deactivate:null,drop:null,out:null,over:null},_create:function(){var e,i=this.options,s=i.accept;this.isover=!1,this.isout=!0,this.accept=t.isFunction(s)?s:function(t){return t.is(s)},this.proportions=function(){return arguments.length?(e=arguments[0],undefined):e?e:e={width:this.element[0].offsetWidth,height:this.element[0].offsetHeight}},t.ui.ddmanager.droppables[i.scope]=t.ui.ddmanager.droppables[i.scope]||[],t.ui.ddmanager.droppables[i.scope].push(this),i.addClasses&&this.element.addClass("ui-droppable")},_destroy:function(){for(var e=0,i=t.ui.ddmanager.droppables[this.options.scope];i.length>e;e++)i[e]===this&&i.splice(e,1);this.element.removeClass("ui-droppable ui-droppable-disabled")},_setOption:function(e,i){"accept"===e&&(this.accept=t.isFunction(i)?i:function(t){return t.is(i)}),t.Widget.prototype._setOption.apply(this,arguments)},_activate:function(e){var i=t.ui.ddmanager.current;this.options.activeClass&&this.element.addClass(this.options.activeClass),i&&this._trigger("activate",e,this.ui(i))},_deactivate:function(e){var i=t.ui.ddmanager.current;this.options.activeClass&&this.element.removeClass(this.options.activeClass),i&&this._trigger("deactivate",e,this.ui(i))},_over:function(e){var i=t.ui.ddmanager.current;i&&(i.currentItem||i.element)[0]!==this.element[0]&&this.accept.call(this.element[0],i.currentItem||i.element)&&(this.options.hoverClass&&this.element.addClass(this.options.hoverClass),this._trigger("over",e,this.ui(i)))},_out:function(e){var i=t.ui.ddmanager.current;i&&(i.currentItem||i.element)[0]!==this.element[0]&&this.accept.call(this.element[0],i.currentItem||i.element)&&(this.options.hoverClass&&this.element.removeClass(this.options.hoverClass),this._trigger("out",e,this.ui(i)))},_drop:function(e,i){var s=i||t.ui.ddmanager.current,n=!1;return s&&(s.currentItem||s.element)[0]!==this.element[0]?(this.element.find(":data(ui-droppable)").not(".ui-draggable-dragging").each(function(){var e=t.data(this,"ui-droppable");return e.options.greedy&&!e.options.disabled&&e.options.scope===s.options.scope&&e.accept.call(e.element[0],s.currentItem||s.element)&&t.ui.intersect(s,t.extend(e,{offset:e.element.offset()}),e.options.tolerance)?(n=!0,!1):undefined}),n?!1:this.accept.call(this.element[0],s.currentItem||s.element)?(this.options.activeClass&&this.element.removeClass(this.options.activeClass),this.options.hoverClass&&this.element.removeClass(this.options.hoverClass),this._trigger("drop",e,this.ui(s)),this.element):!1):!1},ui:function(t){return{draggable:t.currentItem||t.element,helper:t.helper,position:t.position,offset:t.positionAbs}}}),t.ui.intersect=function(t,i,s){if(!i.offset)return!1;var n,a,o=(t.positionAbs||t.position.absolute).left,r=(t.positionAbs||t.position.absolute).top,l=o+t.helperProportions.width,h=r+t.helperProportions.height,c=i.offset.left,u=i.offset.top,d=c+i.proportions().width,p=u+i.proportions().height;switch(s){case"fit":return o>=c&&d>=l&&r>=u&&p>=h;case"intersect":return o+t.helperProportions.width/2>c&&d>l-t.helperProportions.width/2&&r+t.helperProportions.height/2>u&&p>h-t.helperProportions.height/2;case"pointer":return n=(t.positionAbs||t.position.absolute).left+(t.clickOffset||t.offset.click).left,a=(t.positionAbs||t.position.absolute).top+(t.clickOffset||t.offset.click).top,e(a,u,i.proportions().height)&&e(n,c,i.proportions().width);case"touch":return(r>=u&&p>=r||h>=u&&p>=h||u>r&&h>p)&&(o>=c&&d>=o||l>=c&&d>=l||c>o&&l>d);default:return!1}},t.ui.ddmanager={current:null,droppables:{"default":[]},prepareOffsets:function(e,i){var s,n,a=t.ui.ddmanager.droppables[e.options.scope]||[],o=i?i.type:null,r=(e.currentItem||e.element).find(":data(ui-droppable)").addBack();t:for(s=0;a.length>s;s++)if(!(a[s].options.disabled||e&&!a[s].accept.call(a[s].element[0],e.currentItem||e.element))){for(n=0;r.length>n;n++)if(r[n]===a[s].element[0]){a[s].proportions().height=0;continue t}a[s].visible="none"!==a[s].element.css("display"),a[s].visible&&("mousedown"===o&&a[s]._activate.call(a[s],i),a[s].offset=a[s].element.offset(),a[s].proportions({width:a[s].element[0].offsetWidth,height:a[s].element[0].offsetHeight}))}},drop:function(e,i){var s=!1;return t.each((t.ui.ddmanager.droppables[e.options.scope]||[]).slice(),function(){this.options&&(!this.options.disabled&&this.visible&&t.ui.intersect(e,this,this.options.tolerance)&&(s=this._drop.call(this,i)||s),!this.options.disabled&&this.visible&&this.accept.call(this.element[0],e.currentItem||e.element)&&(this.isout=!0,this.isover=!1,this._deactivate.call(this,i)))}),s},dragStart:function(e,i){e.element.parentsUntil("body").bind("scroll.droppable",function(){e.options.refreshPositions||t.ui.ddmanager.prepareOffsets(e,i)})},drag:function(e,i){e.options.refreshPositions&&t.ui.ddmanager.prepareOffsets(e,i),t.each(t.ui.ddmanager.droppables[e.options.scope]||[],function(){if(!this.options.disabled&&!this.greedyChild&&this.visible){var s,n,a,o=t.ui.intersect(e,this,this.options.tolerance),r=!o&&this.isover?"isout":o&&!this.isover?"isover":null;r&&(this.options.greedy&&(n=this.options.scope,a=this.element.parents(":data(ui-droppable)").filter(function(){return t.data(this,"ui-droppable").options.scope===n}),a.length&&(s=t.data(a[0],"ui-droppable"),s.greedyChild="isover"===r)),s&&"isover"===r&&(s.isover=!1,s.isout=!0,s._out.call(s,i)),this[r]=!0,this["isout"===r?"isover":"isout"]=!1,this["isover"===r?"_over":"_out"].call(this,i),s&&"isout"===r&&(s.isout=!1,s.isover=!0,s._over.call(s,i)))}})},dragStop:function(e,i){e.element.parentsUntil("body").unbind("scroll.droppable"),e.options.refreshPositions||t.ui.ddmanager.prepareOffsets(e,i)}}})(jQuery);(function(t){function e(t){return parseInt(t,10)||0}function i(t){return!isNaN(parseInt(t,10))}t.widget("ui.resizable",t.ui.mouse,{version:"1.10.4",widgetEventPrefix:"resize",options:{alsoResize:!1,animate:!1,animateDuration:"slow",animateEasing:"swing",aspectRatio:!1,autoHide:!1,containment:!1,ghost:!1,grid:!1,handles:"e,s,se",helper:!1,maxHeight:null,maxWidth:null,minHeight:10,minWidth:10,zIndex:90,resize:null,start:null,stop:null},_create:function(){var e,i,s,n,a,o=this,r=this.options;if(this.element.addClass("ui-resizable"),t.extend(this,{_aspectRatio:!!r.aspectRatio,aspectRatio:r.aspectRatio,originalElement:this.element,_proportionallyResizeElements:[],_helper:r.helper||r.ghost||r.animate?r.helper||"ui-resizable-helper":null}),this.element[0].nodeName.match(/canvas|textarea|input|select|button|img/i)&&(this.element.wrap(t("<div class='ui-wrapper' style='overflow: hidden;'></div>").css({position:this.element.css("position"),width:this.element.outerWidth(),height:this.element.outerHeight(),top:this.element.css("top"),left:this.element.css("left")})),this.element=this.element.parent().data("ui-resizable",this.element.data("ui-resizable")),this.elementIsWrapper=!0,this.element.css({marginLeft:this.originalElement.css("marginLeft"),marginTop:this.originalElement.css("marginTop"),marginRight:this.originalElement.css("marginRight"),marginBottom:this.originalElement.css("marginBottom")}),this.originalElement.css({marginLeft:0,marginTop:0,marginRight:0,marginBottom:0}),this.originalResizeStyle=this.originalElement.css("resize"),this.originalElement.css("resize","none"),this._proportionallyResizeElements.push(this.originalElement.css({position:"static",zoom:1,display:"block"})),this.originalElement.css({margin:this.originalElement.css("margin")}),this._proportionallyResize()),this.handles=r.handles||(t(".ui-resizable-handle",this.element).length?{n:".ui-resizable-n",e:".ui-resizable-e",s:".ui-resizable-s",w:".ui-resizable-w",se:".ui-resizable-se",sw:".ui-resizable-sw",ne:".ui-resizable-ne",nw:".ui-resizable-nw"}:"e,s,se"),this.handles.constructor===String)for("all"===this.handles&&(this.handles="n,e,s,w,se,sw,ne,nw"),e=this.handles.split(","),this.handles={},i=0;e.length>i;i++)s=t.trim(e[i]),a="ui-resizable-"+s,n=t("<div class='ui-resizable-handle "+a+"'></div>"),n.css({zIndex:r.zIndex}),"se"===s&&n.addClass("ui-icon ui-icon-gripsmall-diagonal-se"),this.handles[s]=".ui-resizable-"+s,this.element.append(n);this._renderAxis=function(e){var i,s,n,a;e=e||this.element;for(i in this.handles)this.handles[i].constructor===String&&(this.handles[i]=t(this.handles[i],this.element).show()),this.elementIsWrapper&&this.originalElement[0].nodeName.match(/textarea|input|select|button/i)&&(s=t(this.handles[i],this.element),a=/sw|ne|nw|se|n|s/.test(i)?s.outerHeight():s.outerWidth(),n=["padding",/ne|nw|n/.test(i)?"Top":/se|sw|s/.test(i)?"Bottom":/^e$/.test(i)?"Right":"Left"].join(""),e.css(n,a),this._proportionallyResize()),t(this.handles[i]).length},this._renderAxis(this.element),this._handles=t(".ui-resizable-handle",this.element).disableSelection(),this._handles.mouseover(function(){o.resizing||(this.className&&(n=this.className.match(/ui-resizable-(se|sw|ne|nw|n|e|s|w)/i)),o.axis=n&&n[1]?n[1]:"se")}),r.autoHide&&(this._handles.hide(),t(this.element).addClass("ui-resizable-autohide").mouseenter(function(){r.disabled||(t(this).removeClass("ui-resizable-autohide"),o._handles.show())}).mouseleave(function(){r.disabled||o.resizing||(t(this).addClass("ui-resizable-autohide"),o._handles.hide())})),this._mouseInit()},_destroy:function(){this._mouseDestroy();var e,i=function(e){t(e).removeClass("ui-resizable ui-resizable-disabled ui-resizable-resizing").removeData("resizable").removeData("ui-resizable").unbind(".resizable").find(".ui-resizable-handle").remove()};return this.elementIsWrapper&&(i(this.element),e=this.element,this.originalElement.css({position:e.css("position"),width:e.outerWidth(),height:e.outerHeight(),top:e.css("top"),left:e.css("left")}).insertAfter(e),e.remove()),this.originalElement.css("resize",this.originalResizeStyle),i(this.originalElement),this},_mouseCapture:function(e){var i,s,n=!1;for(i in this.handles)s=t(this.handles[i])[0],(s===e.target||t.contains(s,e.target))&&(n=!0);return!this.options.disabled&&n},_mouseStart:function(i){var s,n,a,o=this.options,r=this.element.position(),h=this.element;return this.resizing=!0,/absolute/.test(h.css("position"))?h.css({position:"absolute",top:h.css("top"),left:h.css("left")}):h.is(".ui-draggable")&&h.css({position:"absolute",top:r.top,left:r.left}),this._renderProxy(),s=e(this.helper.css("left")),n=e(this.helper.css("top")),o.containment&&(s+=t(o.containment).scrollLeft()||0,n+=t(o.containment).scrollTop()||0),this.offset=this.helper.offset(),this.position={left:s,top:n},this.size=this._helper?{width:this.helper.width(),height:this.helper.height()}:{width:h.width(),height:h.height()},this.originalSize=this._helper?{width:h.outerWidth(),height:h.outerHeight()}:{width:h.width(),height:h.height()},this.originalPosition={left:s,top:n},this.sizeDiff={width:h.outerWidth()-h.width(),height:h.outerHeight()-h.height()},this.originalMousePosition={left:i.pageX,top:i.pageY},this.aspectRatio="number"==typeof o.aspectRatio?o.aspectRatio:this.originalSize.width/this.originalSize.height||1,a=t(".ui-resizable-"+this.axis).css("cursor"),t("body").css("cursor","auto"===a?this.axis+"-resize":a),h.addClass("ui-resizable-resizing"),this._propagate("start",i),!0},_mouseDrag:function(e){var i,s=this.helper,n={},a=this.originalMousePosition,o=this.axis,r=this.position.top,h=this.position.left,l=this.size.width,c=this.size.height,u=e.pageX-a.left||0,d=e.pageY-a.top||0,p=this._change[o];return p?(i=p.apply(this,[e,u,d]),this._updateVirtualBoundaries(e.shiftKey),(this._aspectRatio||e.shiftKey)&&(i=this._updateRatio(i,e)),i=this._respectSize(i,e),this._updateCache(i),this._propagate("resize",e),this.position.top!==r&&(n.top=this.position.top+"px"),this.position.left!==h&&(n.left=this.position.left+"px"),this.size.width!==l&&(n.width=this.size.width+"px"),this.size.height!==c&&(n.height=this.size.height+"px"),s.css(n),!this._helper&&this._proportionallyResizeElements.length&&this._proportionallyResize(),t.isEmptyObject(n)||this._trigger("resize",e,this.ui()),!1):!1},_mouseStop:function(e){this.resizing=!1;var i,s,n,a,o,r,h,l=this.options,c=this;return this._helper&&(i=this._proportionallyResizeElements,s=i.length&&/textarea/i.test(i[0].nodeName),n=s&&t.ui.hasScroll(i[0],"left")?0:c.sizeDiff.height,a=s?0:c.sizeDiff.width,o={width:c.helper.width()-a,height:c.helper.height()-n},r=parseInt(c.element.css("left"),10)+(c.position.left-c.originalPosition.left)||null,h=parseInt(c.element.css("top"),10)+(c.position.top-c.originalPosition.top)||null,l.animate||this.element.css(t.extend(o,{top:h,left:r})),c.helper.height(c.size.height),c.helper.width(c.size.width),this._helper&&!l.animate&&this._proportionallyResize()),t("body").css("cursor","auto"),this.element.removeClass("ui-resizable-resizing"),this._propagate("stop",e),this._helper&&this.helper.remove(),!1},_updateVirtualBoundaries:function(t){var e,s,n,a,o,r=this.options;o={minWidth:i(r.minWidth)?r.minWidth:0,maxWidth:i(r.maxWidth)?r.maxWidth:1/0,minHeight:i(r.minHeight)?r.minHeight:0,maxHeight:i(r.maxHeight)?r.maxHeight:1/0},(this._aspectRatio||t)&&(e=o.minHeight*this.aspectRatio,n=o.minWidth/this.aspectRatio,s=o.maxHeight*this.aspectRatio,a=o.maxWidth/this.aspectRatio,e>o.minWidth&&(o.minWidth=e),n>o.minHeight&&(o.minHeight=n),o.maxWidth>s&&(o.maxWidth=s),o.maxHeight>a&&(o.maxHeight=a)),this._vBoundaries=o},_updateCache:function(t){this.offset=this.helper.offset(),i(t.left)&&(this.position.left=t.left),i(t.top)&&(this.position.top=t.top),i(t.height)&&(this.size.height=t.height),i(t.width)&&(this.size.width=t.width)},_updateRatio:function(t){var e=this.position,s=this.size,n=this.axis;return i(t.height)?t.width=t.height*this.aspectRatio:i(t.width)&&(t.height=t.width/this.aspectRatio),"sw"===n&&(t.left=e.left+(s.width-t.width),t.top=null),"nw"===n&&(t.top=e.top+(s.height-t.height),t.left=e.left+(s.width-t.width)),t},_respectSize:function(t){var e=this._vBoundaries,s=this.axis,n=i(t.width)&&e.maxWidth&&e.maxWidth<t.width,a=i(t.height)&&e.maxHeight&&e.maxHeight<t.height,o=i(t.width)&&e.minWidth&&e.minWidth>t.width,r=i(t.height)&&e.minHeight&&e.minHeight>t.height,h=this.originalPosition.left+this.originalSize.width,l=this.position.top+this.size.height,c=/sw|nw|w/.test(s),u=/nw|ne|n/.test(s);return o&&(t.width=e.minWidth),r&&(t.height=e.minHeight),n&&(t.width=e.maxWidth),a&&(t.height=e.maxHeight),o&&c&&(t.left=h-e.minWidth),n&&c&&(t.left=h-e.maxWidth),r&&u&&(t.top=l-e.minHeight),a&&u&&(t.top=l-e.maxHeight),t.width||t.height||t.left||!t.top?t.width||t.height||t.top||!t.left||(t.left=null):t.top=null,t},_proportionallyResize:function(){if(this._proportionallyResizeElements.length){var t,e,i,s,n,a=this.helper||this.element;for(t=0;this._proportionallyResizeElements.length>t;t++){if(n=this._proportionallyResizeElements[t],!this.borderDif)for(this.borderDif=[],i=[n.css("borderTopWidth"),n.css("borderRightWidth"),n.css("borderBottomWidth"),n.css("borderLeftWidth")],s=[n.css("paddingTop"),n.css("paddingRight"),n.css("paddingBottom"),n.css("paddingLeft")],e=0;i.length>e;e++)this.borderDif[e]=(parseInt(i[e],10)||0)+(parseInt(s[e],10)||0);n.css({height:a.height()-this.borderDif[0]-this.borderDif[2]||0,width:a.width()-this.borderDif[1]-this.borderDif[3]||0})}}},_renderProxy:function(){var e=this.element,i=this.options;this.elementOffset=e.offset(),this._helper?(this.helper=this.helper||t("<div style='overflow:hidden;'></div>"),this.helper.addClass(this._helper).css({width:this.element.outerWidth()-1,height:this.element.outerHeight()-1,position:"absolute",left:this.elementOffset.left+"px",top:this.elementOffset.top+"px",zIndex:++i.zIndex}),this.helper.appendTo("body").disableSelection()):this.helper=this.element},_change:{e:function(t,e){return{width:this.originalSize.width+e}},w:function(t,e){var i=this.originalSize,s=this.originalPosition;return{left:s.left+e,width:i.width-e}},n:function(t,e,i){var s=this.originalSize,n=this.originalPosition;return{top:n.top+i,height:s.height-i}},s:function(t,e,i){return{height:this.originalSize.height+i}},se:function(e,i,s){return t.extend(this._change.s.apply(this,arguments),this._change.e.apply(this,[e,i,s]))},sw:function(e,i,s){return t.extend(this._change.s.apply(this,arguments),this._change.w.apply(this,[e,i,s]))},ne:function(e,i,s){return t.extend(this._change.n.apply(this,arguments),this._change.e.apply(this,[e,i,s]))},nw:function(e,i,s){return t.extend(this._change.n.apply(this,arguments),this._change.w.apply(this,[e,i,s]))}},_propagate:function(e,i){t.ui.plugin.call(this,e,[i,this.ui()]),"resize"!==e&&this._trigger(e,i,this.ui())},plugins:{},ui:function(){return{originalElement:this.originalElement,element:this.element,helper:this.helper,position:this.position,size:this.size,originalSize:this.originalSize,originalPosition:this.originalPosition}}}),t.ui.plugin.add("resizable","animate",{stop:function(e){var i=t(this).data("ui-resizable"),s=i.options,n=i._proportionallyResizeElements,a=n.length&&/textarea/i.test(n[0].nodeName),o=a&&t.ui.hasScroll(n[0],"left")?0:i.sizeDiff.height,r=a?0:i.sizeDiff.width,h={width:i.size.width-r,height:i.size.height-o},l=parseInt(i.element.css("left"),10)+(i.position.left-i.originalPosition.left)||null,c=parseInt(i.element.css("top"),10)+(i.position.top-i.originalPosition.top)||null;i.element.animate(t.extend(h,c&&l?{top:c,left:l}:{}),{duration:s.animateDuration,easing:s.animateEasing,step:function(){var s={width:parseInt(i.element.css("width"),10),height:parseInt(i.element.css("height"),10),top:parseInt(i.element.css("top"),10),left:parseInt(i.element.css("left"),10)};n&&n.length&&t(n[0]).css({width:s.width,height:s.height}),i._updateCache(s),i._propagate("resize",e)}})}}),t.ui.plugin.add("resizable","containment",{start:function(){var i,s,n,a,o,r,h,l=t(this).data("ui-resizable"),c=l.options,u=l.element,d=c.containment,p=d instanceof t?d.get(0):/parent/.test(d)?u.parent().get(0):d;p&&(l.containerElement=t(p),/document/.test(d)||d===document?(l.containerOffset={left:0,top:0},l.containerPosition={left:0,top:0},l.parentData={element:t(document),left:0,top:0,width:t(document).width(),height:t(document).height()||document.body.parentNode.scrollHeight}):(i=t(p),s=[],t(["Top","Right","Left","Bottom"]).each(function(t,n){s[t]=e(i.css("padding"+n))}),l.containerOffset=i.offset(),l.containerPosition=i.position(),l.containerSize={height:i.innerHeight()-s[3],width:i.innerWidth()-s[1]},n=l.containerOffset,a=l.containerSize.height,o=l.containerSize.width,r=t.ui.hasScroll(p,"left")?p.scrollWidth:o,h=t.ui.hasScroll(p)?p.scrollHeight:a,l.parentData={element:p,left:n.left,top:n.top,width:r,height:h}))},resize:function(e){var i,s,n,a,o=t(this).data("ui-resizable"),r=o.options,h=o.containerOffset,l=o.position,c=o._aspectRatio||e.shiftKey,u={top:0,left:0},d=o.containerElement;d[0]!==document&&/static/.test(d.css("position"))&&(u=h),l.left<(o._helper?h.left:0)&&(o.size.width=o.size.width+(o._helper?o.position.left-h.left:o.position.left-u.left),c&&(o.size.height=o.size.width/o.aspectRatio),o.position.left=r.helper?h.left:0),l.top<(o._helper?h.top:0)&&(o.size.height=o.size.height+(o._helper?o.position.top-h.top:o.position.top),c&&(o.size.width=o.size.height*o.aspectRatio),o.position.top=o._helper?h.top:0),o.offset.left=o.parentData.left+o.position.left,o.offset.top=o.parentData.top+o.position.top,i=Math.abs((o._helper?o.offset.left-u.left:o.offset.left-u.left)+o.sizeDiff.width),s=Math.abs((o._helper?o.offset.top-u.top:o.offset.top-h.top)+o.sizeDiff.height),n=o.containerElement.get(0)===o.element.parent().get(0),a=/relative|absolute/.test(o.containerElement.css("position")),n&&a&&(i-=Math.abs(o.parentData.left)),i+o.size.width>=o.parentData.width&&(o.size.width=o.parentData.width-i,c&&(o.size.height=o.size.width/o.aspectRatio)),s+o.size.height>=o.parentData.height&&(o.size.height=o.parentData.height-s,c&&(o.size.width=o.size.height*o.aspectRatio))},stop:function(){var e=t(this).data("ui-resizable"),i=e.options,s=e.containerOffset,n=e.containerPosition,a=e.containerElement,o=t(e.helper),r=o.offset(),h=o.outerWidth()-e.sizeDiff.width,l=o.outerHeight()-e.sizeDiff.height;e._helper&&!i.animate&&/relative/.test(a.css("position"))&&t(this).css({left:r.left-n.left-s.left,width:h,height:l}),e._helper&&!i.animate&&/static/.test(a.css("position"))&&t(this).css({left:r.left-n.left-s.left,width:h,height:l})}}),t.ui.plugin.add("resizable","alsoResize",{start:function(){var e=t(this).data("ui-resizable"),i=e.options,s=function(e){t(e).each(function(){var e=t(this);e.data("ui-resizable-alsoresize",{width:parseInt(e.width(),10),height:parseInt(e.height(),10),left:parseInt(e.css("left"),10),top:parseInt(e.css("top"),10)})})};"object"!=typeof i.alsoResize||i.alsoResize.parentNode?s(i.alsoResize):i.alsoResize.length?(i.alsoResize=i.alsoResize[0],s(i.alsoResize)):t.each(i.alsoResize,function(t){s(t)})},resize:function(e,i){var s=t(this).data("ui-resizable"),n=s.options,a=s.originalSize,o=s.originalPosition,r={height:s.size.height-a.height||0,width:s.size.width-a.width||0,top:s.position.top-o.top||0,left:s.position.left-o.left||0},h=function(e,s){t(e).each(function(){var e=t(this),n=t(this).data("ui-resizable-alsoresize"),a={},o=s&&s.length?s:e.parents(i.originalElement[0]).length?["width","height"]:["width","height","top","left"];t.each(o,function(t,e){var i=(n[e]||0)+(r[e]||0);i&&i>=0&&(a[e]=i||null)}),e.css(a)})};"object"!=typeof n.alsoResize||n.alsoResize.nodeType?h(n.alsoResize):t.each(n.alsoResize,function(t,e){h(t,e)})},stop:function(){t(this).removeData("resizable-alsoresize")}}),t.ui.plugin.add("resizable","ghost",{start:function(){var e=t(this).data("ui-resizable"),i=e.options,s=e.size;e.ghost=e.originalElement.clone(),e.ghost.css({opacity:.25,display:"block",position:"relative",height:s.height,width:s.width,margin:0,left:0,top:0}).addClass("ui-resizable-ghost").addClass("string"==typeof i.ghost?i.ghost:""),e.ghost.appendTo(e.helper)},resize:function(){var e=t(this).data("ui-resizable");e.ghost&&e.ghost.css({position:"relative",height:e.size.height,width:e.size.width})},stop:function(){var e=t(this).data("ui-resizable");e.ghost&&e.helper&&e.helper.get(0).removeChild(e.ghost.get(0))}}),t.ui.plugin.add("resizable","grid",{resize:function(){var e=t(this).data("ui-resizable"),i=e.options,s=e.size,n=e.originalSize,a=e.originalPosition,o=e.axis,r="number"==typeof i.grid?[i.grid,i.grid]:i.grid,h=r[0]||1,l=r[1]||1,c=Math.round((s.width-n.width)/h)*h,u=Math.round((s.height-n.height)/l)*l,d=n.width+c,p=n.height+u,f=i.maxWidth&&d>i.maxWidth,g=i.maxHeight&&p>i.maxHeight,m=i.minWidth&&i.minWidth>d,v=i.minHeight&&i.minHeight>p;i.grid=r,m&&(d+=h),v&&(p+=l),f&&(d-=h),g&&(p-=l),/^(se|s|e)$/.test(o)?(e.size.width=d,e.size.height=p):/^(ne)$/.test(o)?(e.size.width=d,e.size.height=p,e.position.top=a.top-u):/^(sw)$/.test(o)?(e.size.width=d,e.size.height=p,e.position.left=a.left-c):(p-l>0?(e.size.height=p,e.position.top=a.top-u):(e.size.height=l,e.position.top=a.top+n.height-l),d-h>0?(e.size.width=d,e.position.left=a.left-c):(e.size.width=h,e.position.left=a.left+n.width-h))}})})(jQuery);(function(t){t.widget("ui.selectable",t.ui.mouse,{version:"1.10.4",options:{appendTo:"body",autoRefresh:!0,distance:0,filter:"*",tolerance:"touch",selected:null,selecting:null,start:null,stop:null,unselected:null,unselecting:null},_create:function(){var e,i=this;this.element.addClass("ui-selectable"),this.dragged=!1,this.refresh=function(){e=t(i.options.filter,i.element[0]),e.addClass("ui-selectee"),e.each(function(){var e=t(this),i=e.offset();t.data(this,"selectable-item",{element:this,$element:e,left:i.left,top:i.top,right:i.left+e.outerWidth(),bottom:i.top+e.outerHeight(),startselected:!1,selected:e.hasClass("ui-selected"),selecting:e.hasClass("ui-selecting"),unselecting:e.hasClass("ui-unselecting")})})},this.refresh(),this.selectees=e.addClass("ui-selectee"),this._mouseInit(),this.helper=t("<div class='ui-selectable-helper'></div>")},_destroy:function(){this.selectees.removeClass("ui-selectee").removeData("selectable-item"),this.element.removeClass("ui-selectable ui-selectable-disabled"),this._mouseDestroy()},_mouseStart:function(e){var i=this,s=this.options;this.opos=[e.pageX,e.pageY],this.options.disabled||(this.selectees=t(s.filter,this.element[0]),this._trigger("start",e),t(s.appendTo).append(this.helper),this.helper.css({left:e.pageX,top:e.pageY,width:0,height:0}),s.autoRefresh&&this.refresh(),this.selectees.filter(".ui-selected").each(function(){var s=t.data(this,"selectable-item");s.startselected=!0,e.metaKey||e.ctrlKey||(s.$element.removeClass("ui-selected"),s.selected=!1,s.$element.addClass("ui-unselecting"),s.unselecting=!0,i._trigger("unselecting",e,{unselecting:s.element}))}),t(e.target).parents().addBack().each(function(){var s,n=t.data(this,"selectable-item");return n?(s=!e.metaKey&&!e.ctrlKey||!n.$element.hasClass("ui-selected"),n.$element.removeClass(s?"ui-unselecting":"ui-selected").addClass(s?"ui-selecting":"ui-unselecting"),n.unselecting=!s,n.selecting=s,n.selected=s,s?i._trigger("selecting",e,{selecting:n.element}):i._trigger("unselecting",e,{unselecting:n.element}),!1):undefined}))},_mouseDrag:function(e){if(this.dragged=!0,!this.options.disabled){var i,s=this,n=this.options,a=this.opos[0],o=this.opos[1],r=e.pageX,l=e.pageY;return a>r&&(i=r,r=a,a=i),o>l&&(i=l,l=o,o=i),this.helper.css({left:a,top:o,width:r-a,height:l-o}),this.selectees.each(function(){var i=t.data(this,"selectable-item"),h=!1;i&&i.element!==s.element[0]&&("touch"===n.tolerance?h=!(i.left>r||a>i.right||i.top>l||o>i.bottom):"fit"===n.tolerance&&(h=i.left>a&&r>i.right&&i.top>o&&l>i.bottom),h?(i.selected&&(i.$element.removeClass("ui-selected"),i.selected=!1),i.unselecting&&(i.$element.removeClass("ui-unselecting"),i.unselecting=!1),i.selecting||(i.$element.addClass("ui-selecting"),i.selecting=!0,s._trigger("selecting",e,{selecting:i.element}))):(i.selecting&&((e.metaKey||e.ctrlKey)&&i.startselected?(i.$element.removeClass("ui-selecting"),i.selecting=!1,i.$element.addClass("ui-selected"),i.selected=!0):(i.$element.removeClass("ui-selecting"),i.selecting=!1,i.startselected&&(i.$element.addClass("ui-unselecting"),i.unselecting=!0),s._trigger("unselecting",e,{unselecting:i.element}))),i.selected&&(e.metaKey||e.ctrlKey||i.startselected||(i.$element.removeClass("ui-selected"),i.selected=!1,i.$element.addClass("ui-unselecting"),i.unselecting=!0,s._trigger("unselecting",e,{unselecting:i.element})))))}),!1}},_mouseStop:function(e){var i=this;return this.dragged=!1,t(".ui-unselecting",this.element[0]).each(function(){var s=t.data(this,"selectable-item");s.$element.removeClass("ui-unselecting"),s.unselecting=!1,s.startselected=!1,i._trigger("unselected",e,{unselected:s.element})}),t(".ui-selecting",this.element[0]).each(function(){var s=t.data(this,"selectable-item");s.$element.removeClass("ui-selecting").addClass("ui-selected"),s.selecting=!1,s.selected=!0,s.startselected=!0,i._trigger("selected",e,{selected:s.element})}),this._trigger("stop",e),this.helper.remove(),!1}})})(jQuery);(function(t){function e(t,e,i){return t>e&&e+i>t}function i(t){return/left|right/.test(t.css("float"))||/inline|table-cell/.test(t.css("display"))}t.widget("ui.sortable",t.ui.mouse,{version:"1.10.4",widgetEventPrefix:"sort",ready:!1,options:{appendTo:"parent",axis:!1,connectWith:!1,containment:!1,cursor:"auto",cursorAt:!1,dropOnEmpty:!0,forcePlaceholderSize:!1,forceHelperSize:!1,grid:!1,handle:!1,helper:"original",items:"> *",opacity:!1,placeholder:!1,revert:!1,scroll:!0,scrollSensitivity:20,scrollSpeed:20,scope:"default",tolerance:"intersect",zIndex:1e3,activate:null,beforeStop:null,change:null,deactivate:null,out:null,over:null,receive:null,remove:null,sort:null,start:null,stop:null,update:null},_create:function(){var t=this.options;this.containerCache={},this.element.addClass("ui-sortable"),this.refresh(),this.floating=this.items.length?"x"===t.axis||i(this.items[0].item):!1,this.offset=this.element.offset(),this._mouseInit(),this.ready=!0},_destroy:function(){this.element.removeClass("ui-sortable ui-sortable-disabled"),this._mouseDestroy();for(var t=this.items.length-1;t>=0;t--)this.items[t].item.removeData(this.widgetName+"-item");return this},_setOption:function(e,i){"disabled"===e?(this.options[e]=i,this.widget().toggleClass("ui-sortable-disabled",!!i)):t.Widget.prototype._setOption.apply(this,arguments)},_mouseCapture:function(e,i){var s=null,n=!1,o=this;return this.reverting?!1:this.options.disabled||"static"===this.options.type?!1:(this._refreshItems(e),t(e.target).parents().each(function(){return t.data(this,o.widgetName+"-item")===o?(s=t(this),!1):undefined}),t.data(e.target,o.widgetName+"-item")===o&&(s=t(e.target)),s?!this.options.handle||i||(t(this.options.handle,s).find("*").addBack().each(function(){this===e.target&&(n=!0)}),n)?(this.currentItem=s,this._removeCurrentsFromItems(),!0):!1:!1)},_mouseStart:function(e,i,s){var n,o,a=this.options;if(this.currentContainer=this,this.refreshPositions(),this.helper=this._createHelper(e),this._cacheHelperProportions(),this._cacheMargins(),this.scrollParent=this.helper.scrollParent(),this.offset=this.currentItem.offset(),this.offset={top:this.offset.top-this.margins.top,left:this.offset.left-this.margins.left},t.extend(this.offset,{click:{left:e.pageX-this.offset.left,top:e.pageY-this.offset.top},parent:this._getParentOffset(),relative:this._getRelativeOffset()}),this.helper.css("position","absolute"),this.cssPosition=this.helper.css("position"),this.originalPosition=this._generatePosition(e),this.originalPageX=e.pageX,this.originalPageY=e.pageY,a.cursorAt&&this._adjustOffsetFromHelper(a.cursorAt),this.domPosition={prev:this.currentItem.prev()[0],parent:this.currentItem.parent()[0]},this.helper[0]!==this.currentItem[0]&&this.currentItem.hide(),this._createPlaceholder(),a.containment&&this._setContainment(),a.cursor&&"auto"!==a.cursor&&(o=this.document.find("body"),this.storedCursor=o.css("cursor"),o.css("cursor",a.cursor),this.storedStylesheet=t("<style>*{ cursor: "+a.cursor+" !important; }</style>").appendTo(o)),a.opacity&&(this.helper.css("opacity")&&(this._storedOpacity=this.helper.css("opacity")),this.helper.css("opacity",a.opacity)),a.zIndex&&(this.helper.css("zIndex")&&(this._storedZIndex=this.helper.css("zIndex")),this.helper.css("zIndex",a.zIndex)),this.scrollParent[0]!==document&&"HTML"!==this.scrollParent[0].tagName&&(this.overflowOffset=this.scrollParent.offset()),this._trigger("start",e,this._uiHash()),this._preserveHelperProportions||this._cacheHelperProportions(),!s)for(n=this.containers.length-1;n>=0;n--)this.containers[n]._trigger("activate",e,this._uiHash(this));return t.ui.ddmanager&&(t.ui.ddmanager.current=this),t.ui.ddmanager&&!a.dropBehaviour&&t.ui.ddmanager.prepareOffsets(this,e),this.dragging=!0,this.helper.addClass("ui-sortable-helper"),this._mouseDrag(e),!0},_mouseDrag:function(e){var i,s,n,o,a=this.options,r=!1;for(this.position=this._generatePosition(e),this.positionAbs=this._convertPositionTo("absolute"),this.lastPositionAbs||(this.lastPositionAbs=this.positionAbs),this.options.scroll&&(this.scrollParent[0]!==document&&"HTML"!==this.scrollParent[0].tagName?(this.overflowOffset.top+this.scrollParent[0].offsetHeight-e.pageY<a.scrollSensitivity?this.scrollParent[0].scrollTop=r=this.scrollParent[0].scrollTop+a.scrollSpeed:e.pageY-this.overflowOffset.top<a.scrollSensitivity&&(this.scrollParent[0].scrollTop=r=this.scrollParent[0].scrollTop-a.scrollSpeed),this.overflowOffset.left+this.scrollParent[0].offsetWidth-e.pageX<a.scrollSensitivity?this.scrollParent[0].scrollLeft=r=this.scrollParent[0].scrollLeft+a.scrollSpeed:e.pageX-this.overflowOffset.left<a.scrollSensitivity&&(this.scrollParent[0].scrollLeft=r=this.scrollParent[0].scrollLeft-a.scrollSpeed)):(e.pageY-t(document).scrollTop()<a.scrollSensitivity?r=t(document).scrollTop(t(document).scrollTop()-a.scrollSpeed):t(window).height()-(e.pageY-t(document).scrollTop())<a.scrollSensitivity&&(r=t(document).scrollTop(t(document).scrollTop()+a.scrollSpeed)),e.pageX-t(document).scrollLeft()<a.scrollSensitivity?r=t(document).scrollLeft(t(document).scrollLeft()-a.scrollSpeed):t(window).width()-(e.pageX-t(document).scrollLeft())<a.scrollSensitivity&&(r=t(document).scrollLeft(t(document).scrollLeft()+a.scrollSpeed))),r!==!1&&t.ui.ddmanager&&!a.dropBehaviour&&t.ui.ddmanager.prepareOffsets(this,e)),this.positionAbs=this._convertPositionTo("absolute"),this.options.axis&&"y"===this.options.axis||(this.helper[0].style.left=this.position.left+"px"),this.options.axis&&"x"===this.options.axis||(this.helper[0].style.top=this.position.top+"px"),i=this.items.length-1;i>=0;i--)if(s=this.items[i],n=s.item[0],o=this._intersectsWithPointer(s),o&&s.instance===this.currentContainer&&n!==this.currentItem[0]&&this.placeholder[1===o?"next":"prev"]()[0]!==n&&!t.contains(this.placeholder[0],n)&&("semi-dynamic"===this.options.type?!t.contains(this.element[0],n):!0)){if(this.direction=1===o?"down":"up","pointer"!==this.options.tolerance&&!this._intersectsWithSides(s))break;this._rearrange(e,s),this._trigger("change",e,this._uiHash());break}return this._contactContainers(e),t.ui.ddmanager&&t.ui.ddmanager.drag(this,e),this._trigger("sort",e,this._uiHash()),this.lastPositionAbs=this.positionAbs,!1},_mouseStop:function(e,i){if(e){if(t.ui.ddmanager&&!this.options.dropBehaviour&&t.ui.ddmanager.drop(this,e),this.options.revert){var s=this,n=this.placeholder.offset(),o=this.options.axis,a={};o&&"x"!==o||(a.left=n.left-this.offset.parent.left-this.margins.left+(this.offsetParent[0]===document.body?0:this.offsetParent[0].scrollLeft)),o&&"y"!==o||(a.top=n.top-this.offset.parent.top-this.margins.top+(this.offsetParent[0]===document.body?0:this.offsetParent[0].scrollTop)),this.reverting=!0,t(this.helper).animate(a,parseInt(this.options.revert,10)||500,function(){s._clear(e)})}else this._clear(e,i);return!1}},cancel:function(){if(this.dragging){this._mouseUp({target:null}),"original"===this.options.helper?this.currentItem.css(this._storedCSS).removeClass("ui-sortable-helper"):this.currentItem.show();for(var e=this.containers.length-1;e>=0;e--)this.containers[e]._trigger("deactivate",null,this._uiHash(this)),this.containers[e].containerCache.over&&(this.containers[e]._trigger("out",null,this._uiHash(this)),this.containers[e].containerCache.over=0)}return this.placeholder&&(this.placeholder[0].parentNode&&this.placeholder[0].parentNode.removeChild(this.placeholder[0]),"original"!==this.options.helper&&this.helper&&this.helper[0].parentNode&&this.helper.remove(),t.extend(this,{helper:null,dragging:!1,reverting:!1,_noFinalSort:null}),this.domPosition.prev?t(this.domPosition.prev).after(this.currentItem):t(this.domPosition.parent).prepend(this.currentItem)),this},serialize:function(e){var i=this._getItemsAsjQuery(e&&e.connected),s=[];return e=e||{},t(i).each(function(){var i=(t(e.item||this).attr(e.attribute||"id")||"").match(e.expression||/(.+)[\-=_](.+)/);i&&s.push((e.key||i[1]+"[]")+"="+(e.key&&e.expression?i[1]:i[2]))}),!s.length&&e.key&&s.push(e.key+"="),s.join("&")},toArray:function(e){var i=this._getItemsAsjQuery(e&&e.connected),s=[];return e=e||{},i.each(function(){s.push(t(e.item||this).attr(e.attribute||"id")||"")}),s},_intersectsWith:function(t){var e=this.positionAbs.left,i=e+this.helperProportions.width,s=this.positionAbs.top,n=s+this.helperProportions.height,o=t.left,a=o+t.width,r=t.top,h=r+t.height,l=this.offset.click.top,c=this.offset.click.left,u="x"===this.options.axis||s+l>r&&h>s+l,d="y"===this.options.axis||e+c>o&&a>e+c,p=u&&d;return"pointer"===this.options.tolerance||this.options.forcePointerForContainers||"pointer"!==this.options.tolerance&&this.helperProportions[this.floating?"width":"height"]>t[this.floating?"width":"height"]?p:e+this.helperProportions.width/2>o&&a>i-this.helperProportions.width/2&&s+this.helperProportions.height/2>r&&h>n-this.helperProportions.height/2},_intersectsWithPointer:function(t){var i="x"===this.options.axis||e(this.positionAbs.top+this.offset.click.top,t.top,t.height),s="y"===this.options.axis||e(this.positionAbs.left+this.offset.click.left,t.left,t.width),n=i&&s,o=this._getDragVerticalDirection(),a=this._getDragHorizontalDirection();return n?this.floating?a&&"right"===a||"down"===o?2:1:o&&("down"===o?2:1):!1},_intersectsWithSides:function(t){var i=e(this.positionAbs.top+this.offset.click.top,t.top+t.height/2,t.height),s=e(this.positionAbs.left+this.offset.click.left,t.left+t.width/2,t.width),n=this._getDragVerticalDirection(),o=this._getDragHorizontalDirection();return this.floating&&o?"right"===o&&s||"left"===o&&!s:n&&("down"===n&&i||"up"===n&&!i)},_getDragVerticalDirection:function(){var t=this.positionAbs.top-this.lastPositionAbs.top;return 0!==t&&(t>0?"down":"up")},_getDragHorizontalDirection:function(){var t=this.positionAbs.left-this.lastPositionAbs.left;return 0!==t&&(t>0?"right":"left")},refresh:function(t){return this._refreshItems(t),this.refreshPositions(),this},_connectWith:function(){var t=this.options;return t.connectWith.constructor===String?[t.connectWith]:t.connectWith},_getItemsAsjQuery:function(e){function i(){r.push(this)}var s,n,o,a,r=[],h=[],l=this._connectWith();if(l&&e)for(s=l.length-1;s>=0;s--)for(o=t(l[s]),n=o.length-1;n>=0;n--)a=t.data(o[n],this.widgetFullName),a&&a!==this&&!a.options.disabled&&h.push([t.isFunction(a.options.items)?a.options.items.call(a.element):t(a.options.items,a.element).not(".ui-sortable-helper").not(".ui-sortable-placeholder"),a]);for(h.push([t.isFunction(this.options.items)?this.options.items.call(this.element,null,{options:this.options,item:this.currentItem}):t(this.options.items,this.element).not(".ui-sortable-helper").not(".ui-sortable-placeholder"),this]),s=h.length-1;s>=0;s--)h[s][0].each(i);return t(r)},_removeCurrentsFromItems:function(){var e=this.currentItem.find(":data("+this.widgetName+"-item)");this.items=t.grep(this.items,function(t){for(var i=0;e.length>i;i++)if(e[i]===t.item[0])return!1;return!0})},_refreshItems:function(e){this.items=[],this.containers=[this];var i,s,n,o,a,r,h,l,c=this.items,u=[[t.isFunction(this.options.items)?this.options.items.call(this.element[0],e,{item:this.currentItem}):t(this.options.items,this.element),this]],d=this._connectWith();if(d&&this.ready)for(i=d.length-1;i>=0;i--)for(n=t(d[i]),s=n.length-1;s>=0;s--)o=t.data(n[s],this.widgetFullName),o&&o!==this&&!o.options.disabled&&(u.push([t.isFunction(o.options.items)?o.options.items.call(o.element[0],e,{item:this.currentItem}):t(o.options.items,o.element),o]),this.containers.push(o));for(i=u.length-1;i>=0;i--)for(a=u[i][1],r=u[i][0],s=0,l=r.length;l>s;s++)h=t(r[s]),h.data(this.widgetName+"-item",a),c.push({item:h,instance:a,width:0,height:0,left:0,top:0})},refreshPositions:function(e){this.offsetParent&&this.helper&&(this.offset.parent=this._getParentOffset());var i,s,n,o;for(i=this.items.length-1;i>=0;i--)s=this.items[i],s.instance!==this.currentContainer&&this.currentContainer&&s.item[0]!==this.currentItem[0]||(n=this.options.toleranceElement?t(this.options.toleranceElement,s.item):s.item,e||(s.width=n.outerWidth(),s.height=n.outerHeight()),o=n.offset(),s.left=o.left,s.top=o.top);if(this.options.custom&&this.options.custom.refreshContainers)this.options.custom.refreshContainers.call(this);else for(i=this.containers.length-1;i>=0;i--)o=this.containers[i].element.offset(),this.containers[i].containerCache.left=o.left,this.containers[i].containerCache.top=o.top,this.containers[i].containerCache.width=this.containers[i].element.outerWidth(),this.containers[i].containerCache.height=this.containers[i].element.outerHeight();return this},_createPlaceholder:function(e){e=e||this;var i,s=e.options;s.placeholder&&s.placeholder.constructor!==String||(i=s.placeholder,s.placeholder={element:function(){var s=e.currentItem[0].nodeName.toLowerCase(),n=t("<"+s+">",e.document[0]).addClass(i||e.currentItem[0].className+" ui-sortable-placeholder").removeClass("ui-sortable-helper");return"tr"===s?e.currentItem.children().each(function(){t("<td>&#160;</td>",e.document[0]).attr("colspan",t(this).attr("colspan")||1).appendTo(n)}):"img"===s&&n.attr("src",e.currentItem.attr("src")),i||n.css("visibility","hidden"),n},update:function(t,n){(!i||s.forcePlaceholderSize)&&(n.height()||n.height(e.currentItem.innerHeight()-parseInt(e.currentItem.css("paddingTop")||0,10)-parseInt(e.currentItem.css("paddingBottom")||0,10)),n.width()||n.width(e.currentItem.innerWidth()-parseInt(e.currentItem.css("paddingLeft")||0,10)-parseInt(e.currentItem.css("paddingRight")||0,10)))}}),e.placeholder=t(s.placeholder.element.call(e.element,e.currentItem)),e.currentItem.after(e.placeholder),s.placeholder.update(e,e.placeholder)},_contactContainers:function(s){var n,o,a,r,h,l,c,u,d,p,f=null,g=null;for(n=this.containers.length-1;n>=0;n--)if(!t.contains(this.currentItem[0],this.containers[n].element[0]))if(this._intersectsWith(this.containers[n].containerCache)){if(f&&t.contains(this.containers[n].element[0],f.element[0]))continue;f=this.containers[n],g=n}else this.containers[n].containerCache.over&&(this.containers[n]._trigger("out",s,this._uiHash(this)),this.containers[n].containerCache.over=0);if(f)if(1===this.containers.length)this.containers[g].containerCache.over||(this.containers[g]._trigger("over",s,this._uiHash(this)),this.containers[g].containerCache.over=1);else{for(a=1e4,r=null,p=f.floating||i(this.currentItem),h=p?"left":"top",l=p?"width":"height",c=this.positionAbs[h]+this.offset.click[h],o=this.items.length-1;o>=0;o--)t.contains(this.containers[g].element[0],this.items[o].item[0])&&this.items[o].item[0]!==this.currentItem[0]&&(!p||e(this.positionAbs.top+this.offset.click.top,this.items[o].top,this.items[o].height))&&(u=this.items[o].item.offset()[h],d=!1,Math.abs(u-c)>Math.abs(u+this.items[o][l]-c)&&(d=!0,u+=this.items[o][l]),a>Math.abs(u-c)&&(a=Math.abs(u-c),r=this.items[o],this.direction=d?"up":"down"));if(!r&&!this.options.dropOnEmpty)return;if(this.currentContainer===this.containers[g])return;r?this._rearrange(s,r,null,!0):this._rearrange(s,null,this.containers[g].element,!0),this._trigger("change",s,this._uiHash()),this.containers[g]._trigger("change",s,this._uiHash(this)),this.currentContainer=this.containers[g],this.options.placeholder.update(this.currentContainer,this.placeholder),this.containers[g]._trigger("over",s,this._uiHash(this)),this.containers[g].containerCache.over=1}},_createHelper:function(e){var i=this.options,s=t.isFunction(i.helper)?t(i.helper.apply(this.element[0],[e,this.currentItem])):"clone"===i.helper?this.currentItem.clone():this.currentItem;return s.parents("body").length||t("parent"!==i.appendTo?i.appendTo:this.currentItem[0].parentNode)[0].appendChild(s[0]),s[0]===this.currentItem[0]&&(this._storedCSS={width:this.currentItem[0].style.width,height:this.currentItem[0].style.height,position:this.currentItem.css("position"),top:this.currentItem.css("top"),left:this.currentItem.css("left")}),(!s[0].style.width||i.forceHelperSize)&&s.width(this.currentItem.width()),(!s[0].style.height||i.forceHelperSize)&&s.height(this.currentItem.height()),s},_adjustOffsetFromHelper:function(e){"string"==typeof e&&(e=e.split(" ")),t.isArray(e)&&(e={left:+e[0],top:+e[1]||0}),"left"in e&&(this.offset.click.left=e.left+this.margins.left),"right"in e&&(this.offset.click.left=this.helperProportions.width-e.right+this.margins.left),"top"in e&&(this.offset.click.top=e.top+this.margins.top),"bottom"in e&&(this.offset.click.top=this.helperProportions.height-e.bottom+this.margins.top)},_getParentOffset:function(){this.offsetParent=this.helper.offsetParent();var e=this.offsetParent.offset();return"absolute"===this.cssPosition&&this.scrollParent[0]!==document&&t.contains(this.scrollParent[0],this.offsetParent[0])&&(e.left+=this.scrollParent.scrollLeft(),e.top+=this.scrollParent.scrollTop()),(this.offsetParent[0]===document.body||this.offsetParent[0].tagName&&"html"===this.offsetParent[0].tagName.toLowerCase()&&t.ui.ie)&&(e={top:0,left:0}),{top:e.top+(parseInt(this.offsetParent.css("borderTopWidth"),10)||0),left:e.left+(parseInt(this.offsetParent.css("borderLeftWidth"),10)||0)}},_getRelativeOffset:function(){if("relative"===this.cssPosition){var t=this.currentItem.position();return{top:t.top-(parseInt(this.helper.css("top"),10)||0)+this.scrollParent.scrollTop(),left:t.left-(parseInt(this.helper.css("left"),10)||0)+this.scrollParent.scrollLeft()}}return{top:0,left:0}},_cacheMargins:function(){this.margins={left:parseInt(this.currentItem.css("marginLeft"),10)||0,top:parseInt(this.currentItem.css("marginTop"),10)||0}},_cacheHelperProportions:function(){this.helperProportions={width:this.helper.outerWidth(),height:this.helper.outerHeight()}},_setContainment:function(){var e,i,s,n=this.options;"parent"===n.containment&&(n.containment=this.helper[0].parentNode),("document"===n.containment||"window"===n.containment)&&(this.containment=[0-this.offset.relative.left-this.offset.parent.left,0-this.offset.relative.top-this.offset.parent.top,t("document"===n.containment?document:window).width()-this.helperProportions.width-this.margins.left,(t("document"===n.containment?document:window).height()||document.body.parentNode.scrollHeight)-this.helperProportions.height-this.margins.top]),/^(document|window|parent)$/.test(n.containment)||(e=t(n.containment)[0],i=t(n.containment).offset(),s="hidden"!==t(e).css("overflow"),this.containment=[i.left+(parseInt(t(e).css("borderLeftWidth"),10)||0)+(parseInt(t(e).css("paddingLeft"),10)||0)-this.margins.left,i.top+(parseInt(t(e).css("borderTopWidth"),10)||0)+(parseInt(t(e).css("paddingTop"),10)||0)-this.margins.top,i.left+(s?Math.max(e.scrollWidth,e.offsetWidth):e.offsetWidth)-(parseInt(t(e).css("borderLeftWidth"),10)||0)-(parseInt(t(e).css("paddingRight"),10)||0)-this.helperProportions.width-this.margins.left,i.top+(s?Math.max(e.scrollHeight,e.offsetHeight):e.offsetHeight)-(parseInt(t(e).css("borderTopWidth"),10)||0)-(parseInt(t(e).css("paddingBottom"),10)||0)-this.helperProportions.height-this.margins.top])},_convertPositionTo:function(e,i){i||(i=this.position);var s="absolute"===e?1:-1,n="absolute"!==this.cssPosition||this.scrollParent[0]!==document&&t.contains(this.scrollParent[0],this.offsetParent[0])?this.scrollParent:this.offsetParent,o=/(html|body)/i.test(n[0].tagName);return{top:i.top+this.offset.relative.top*s+this.offset.parent.top*s-("fixed"===this.cssPosition?-this.scrollParent.scrollTop():o?0:n.scrollTop())*s,left:i.left+this.offset.relative.left*s+this.offset.parent.left*s-("fixed"===this.cssPosition?-this.scrollParent.scrollLeft():o?0:n.scrollLeft())*s}},_generatePosition:function(e){var i,s,n=this.options,o=e.pageX,a=e.pageY,r="absolute"!==this.cssPosition||this.scrollParent[0]!==document&&t.contains(this.scrollParent[0],this.offsetParent[0])?this.scrollParent:this.offsetParent,h=/(html|body)/i.test(r[0].tagName);return"relative"!==this.cssPosition||this.scrollParent[0]!==document&&this.scrollParent[0]!==this.offsetParent[0]||(this.offset.relative=this._getRelativeOffset()),this.originalPosition&&(this.containment&&(e.pageX-this.offset.click.left<this.containment[0]&&(o=this.containment[0]+this.offset.click.left),e.pageY-this.offset.click.top<this.containment[1]&&(a=this.containment[1]+this.offset.click.top),e.pageX-this.offset.click.left>this.containment[2]&&(o=this.containment[2]+this.offset.click.left),e.pageY-this.offset.click.top>this.containment[3]&&(a=this.containment[3]+this.offset.click.top)),n.grid&&(i=this.originalPageY+Math.round((a-this.originalPageY)/n.grid[1])*n.grid[1],a=this.containment?i-this.offset.click.top>=this.containment[1]&&i-this.offset.click.top<=this.containment[3]?i:i-this.offset.click.top>=this.containment[1]?i-n.grid[1]:i+n.grid[1]:i,s=this.originalPageX+Math.round((o-this.originalPageX)/n.grid[0])*n.grid[0],o=this.containment?s-this.offset.click.left>=this.containment[0]&&s-this.offset.click.left<=this.containment[2]?s:s-this.offset.click.left>=this.containment[0]?s-n.grid[0]:s+n.grid[0]:s)),{top:a-this.offset.click.top-this.offset.relative.top-this.offset.parent.top+("fixed"===this.cssPosition?-this.scrollParent.scrollTop():h?0:r.scrollTop()),left:o-this.offset.click.left-this.offset.relative.left-this.offset.parent.left+("fixed"===this.cssPosition?-this.scrollParent.scrollLeft():h?0:r.scrollLeft())}},_rearrange:function(t,e,i,s){i?i[0].appendChild(this.placeholder[0]):e.item[0].parentNode.insertBefore(this.placeholder[0],"down"===this.direction?e.item[0]:e.item[0].nextSibling),this.counter=this.counter?++this.counter:1;var n=this.counter;this._delay(function(){n===this.counter&&this.refreshPositions(!s)})},_clear:function(t,e){function i(t,e,i){return function(s){i._trigger(t,s,e._uiHash(e))}}this.reverting=!1;var s,n=[];if(!this._noFinalSort&&this.currentItem.parent().length&&this.placeholder.before(this.currentItem),this._noFinalSort=null,this.helper[0]===this.currentItem[0]){for(s in this._storedCSS)("auto"===this._storedCSS[s]||"static"===this._storedCSS[s])&&(this._storedCSS[s]="");this.currentItem.css(this._storedCSS).removeClass("ui-sortable-helper")}else this.currentItem.show();for(this.fromOutside&&!e&&n.push(function(t){this._trigger("receive",t,this._uiHash(this.fromOutside))}),!this.fromOutside&&this.domPosition.prev===this.currentItem.prev().not(".ui-sortable-helper")[0]&&this.domPosition.parent===this.currentItem.parent()[0]||e||n.push(function(t){this._trigger("update",t,this._uiHash())}),this!==this.currentContainer&&(e||(n.push(function(t){this._trigger("remove",t,this._uiHash())}),n.push(function(t){return function(e){t._trigger("receive",e,this._uiHash(this))}}.call(this,this.currentContainer)),n.push(function(t){return function(e){t._trigger("update",e,this._uiHash(this))}}.call(this,this.currentContainer)))),s=this.containers.length-1;s>=0;s--)e||n.push(i("deactivate",this,this.containers[s])),this.containers[s].containerCache.over&&(n.push(i("out",this,this.containers[s])),this.containers[s].containerCache.over=0);if(this.storedCursor&&(this.document.find("body").css("cursor",this.storedCursor),this.storedStylesheet.remove()),this._storedOpacity&&this.helper.css("opacity",this._storedOpacity),this._storedZIndex&&this.helper.css("zIndex","auto"===this._storedZIndex?"":this._storedZIndex),this.dragging=!1,this.cancelHelperRemoval){if(!e){for(this._trigger("beforeStop",t,this._uiHash()),s=0;n.length>s;s++)n[s].call(this,t);this._trigger("stop",t,this._uiHash())}return this.fromOutside=!1,!1}if(e||this._trigger("beforeStop",t,this._uiHash()),this.placeholder[0].parentNode.removeChild(this.placeholder[0]),this.helper[0]!==this.currentItem[0]&&this.helper.remove(),this.helper=null,!e){for(s=0;n.length>s;s++)n[s].call(this,t);this._trigger("stop",t,this._uiHash())}return this.fromOutside=!1,!0},_trigger:function(){t.Widget.prototype._trigger.apply(this,arguments)===!1&&this.cancel()},_uiHash:function(e){var i=e||this;return{helper:i.helper,placeholder:i.placeholder||t([]),position:i.position,originalPosition:i.originalPosition,offset:i.positionAbs,item:i.currentItem,sender:e?e.element:null}}})})(jQuery);(function(e){e.widget("ui.autocomplete",{version:"1.10.4",defaultElement:"<input>",options:{appendTo:null,autoFocus:!1,delay:300,minLength:1,position:{my:"left top",at:"left bottom",collision:"none"},source:null,change:null,close:null,focus:null,open:null,response:null,search:null,select:null},requestIndex:0,pending:0,_create:function(){var t,i,s,n=this.element[0].nodeName.toLowerCase(),a="textarea"===n,o="input"===n;this.isMultiLine=a?!0:o?!1:this.element.prop("isContentEditable"),this.valueMethod=this.element[a||o?"val":"text"],this.isNewMenu=!0,this.element.addClass("ui-autocomplete-input").attr("autocomplete","off"),this._on(this.element,{keydown:function(n){if(this.element.prop("readOnly"))return t=!0,s=!0,i=!0,undefined;t=!1,s=!1,i=!1;var a=e.ui.keyCode;switch(n.keyCode){case a.PAGE_UP:t=!0,this._move("previousPage",n);break;case a.PAGE_DOWN:t=!0,this._move("nextPage",n);break;case a.UP:t=!0,this._keyEvent("previous",n);break;case a.DOWN:t=!0,this._keyEvent("next",n);break;case a.ENTER:case a.NUMPAD_ENTER:this.menu.active&&(t=!0,n.preventDefault(),this.menu.select(n));break;case a.TAB:this.menu.active&&this.menu.select(n);break;case a.ESCAPE:this.menu.element.is(":visible")&&(this._value(this.term),this.close(n),n.preventDefault());break;default:i=!0,this._searchTimeout(n)}},keypress:function(s){if(t)return t=!1,(!this.isMultiLine||this.menu.element.is(":visible"))&&s.preventDefault(),undefined;if(!i){var n=e.ui.keyCode;switch(s.keyCode){case n.PAGE_UP:this._move("previousPage",s);break;case n.PAGE_DOWN:this._move("nextPage",s);break;case n.UP:this._keyEvent("previous",s);break;case n.DOWN:this._keyEvent("next",s)}}},input:function(e){return s?(s=!1,e.preventDefault(),undefined):(this._searchTimeout(e),undefined)},focus:function(){this.selectedItem=null,this.previous=this._value()},blur:function(e){return this.cancelBlur?(delete this.cancelBlur,undefined):(clearTimeout(this.searching),this.close(e),this._change(e),undefined)}}),this._initSource(),this.menu=e("<ul>").addClass("ui-autocomplete ui-front").appendTo(this._appendTo()).menu({role:null}).hide().data("ui-menu"),this._on(this.menu.element,{mousedown:function(t){t.preventDefault(),this.cancelBlur=!0,this._delay(function(){delete this.cancelBlur});var i=this.menu.element[0];e(t.target).closest(".ui-menu-item").length||this._delay(function(){var t=this;this.document.one("mousedown",function(s){s.target===t.element[0]||s.target===i||e.contains(i,s.target)||t.close()})})},menufocus:function(t,i){if(this.isNewMenu&&(this.isNewMenu=!1,t.originalEvent&&/^mouse/.test(t.originalEvent.type)))return this.menu.blur(),this.document.one("mousemove",function(){e(t.target).trigger(t.originalEvent)}),undefined;var s=i.item.data("ui-autocomplete-item");!1!==this._trigger("focus",t,{item:s})?t.originalEvent&&/^key/.test(t.originalEvent.type)&&this._value(s.value):this.liveRegion.text(s.value)},menuselect:function(e,t){var i=t.item.data("ui-autocomplete-item"),s=this.previous;this.element[0]!==this.document[0].activeElement&&(this.element.focus(),this.previous=s,this._delay(function(){this.previous=s,this.selectedItem=i})),!1!==this._trigger("select",e,{item:i})&&this._value(i.value),this.term=this._value(),this.close(e),this.selectedItem=i}}),this.liveRegion=e("<span>",{role:"status","aria-live":"polite"}).addClass("ui-helper-hidden-accessible").insertBefore(this.element),this._on(this.window,{beforeunload:function(){this.element.removeAttr("autocomplete")}})},_destroy:function(){clearTimeout(this.searching),this.element.removeClass("ui-autocomplete-input").removeAttr("autocomplete"),this.menu.element.remove(),this.liveRegion.remove()},_setOption:function(e,t){this._super(e,t),"source"===e&&this._initSource(),"appendTo"===e&&this.menu.element.appendTo(this._appendTo()),"disabled"===e&&t&&this.xhr&&this.xhr.abort()},_appendTo:function(){var t=this.options.appendTo;return t&&(t=t.jquery||t.nodeType?e(t):this.document.find(t).eq(0)),t||(t=this.element.closest(".ui-front")),t.length||(t=this.document[0].body),t},_initSource:function(){var t,i,s=this;e.isArray(this.options.source)?(t=this.options.source,this.source=function(i,s){s(e.ui.autocomplete.filter(t,i.term))}):"string"==typeof this.options.source?(i=this.options.source,this.source=function(t,n){s.xhr&&s.xhr.abort(),s.xhr=e.ajax({url:i,data:t,dataType:"json",success:function(e){n(e)},error:function(){n([])}})}):this.source=this.options.source},_searchTimeout:function(e){clearTimeout(this.searching),this.searching=this._delay(function(){this.term!==this._value()&&(this.selectedItem=null,this.search(null,e))},this.options.delay)},search:function(e,t){return e=null!=e?e:this._value(),this.term=this._value(),e.length<this.options.minLength?this.close(t):this._trigger("search",t)!==!1?this._search(e):undefined},_search:function(e){this.pending++,this.element.addClass("ui-autocomplete-loading"),this.cancelSearch=!1,this.source({term:e},this._response())},_response:function(){var t=++this.requestIndex;return e.proxy(function(e){t===this.requestIndex&&this.__response(e),this.pending--,this.pending||this.element.removeClass("ui-autocomplete-loading")},this)},__response:function(e){e&&(e=this._normalize(e)),this._trigger("response",null,{content:e}),!this.options.disabled&&e&&e.length&&!this.cancelSearch?(this._suggest(e),this._trigger("open")):this._close()},close:function(e){this.cancelSearch=!0,this._close(e)},_close:function(e){this.menu.element.is(":visible")&&(this.menu.element.hide(),this.menu.blur(),this.isNewMenu=!0,this._trigger("close",e))},_change:function(e){this.previous!==this._value()&&this._trigger("change",e,{item:this.selectedItem})},_normalize:function(t){return t.length&&t[0].label&&t[0].value?t:e.map(t,function(t){return"string"==typeof t?{label:t,value:t}:e.extend({label:t.label||t.value,value:t.value||t.label},t)})},_suggest:function(t){var i=this.menu.element.empty();this._renderMenu(i,t),this.isNewMenu=!0,this.menu.refresh(),i.show(),this._resizeMenu(),i.position(e.extend({of:this.element},this.options.position)),this.options.autoFocus&&this.menu.next()},_resizeMenu:function(){var e=this.menu.element;e.outerWidth(Math.max(e.width("").outerWidth()+1,this.element.outerWidth()))},_renderMenu:function(t,i){var s=this;e.each(i,function(e,i){s._renderItemData(t,i)})},_renderItemData:function(e,t){return this._renderItem(e,t).data("ui-autocomplete-item",t)},_renderItem:function(t,i){return e("<li>").append(e("<a>").text(i.label)).appendTo(t)},_move:function(e,t){return this.menu.element.is(":visible")?this.menu.isFirstItem()&&/^previous/.test(e)||this.menu.isLastItem()&&/^next/.test(e)?(this._value(this.term),this.menu.blur(),undefined):(this.menu[e](t),undefined):(this.search(null,t),undefined)},widget:function(){return this.menu.element},_value:function(){return this.valueMethod.apply(this.element,arguments)},_keyEvent:function(e,t){(!this.isMultiLine||this.menu.element.is(":visible"))&&(this._move(e,t),t.preventDefault())}}),e.extend(e.ui.autocomplete,{escapeRegex:function(e){return e.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g,"\\$&")},filter:function(t,i){var s=RegExp(e.ui.autocomplete.escapeRegex(i),"i");return e.grep(t,function(e){return s.test(e.label||e.value||e)})}}),e.widget("ui.autocomplete",e.ui.autocomplete,{options:{messages:{noResults:"No search results.",results:function(e){return e+(e>1?" results are":" result is")+" available, use up and down arrow keys to navigate."}}},__response:function(e){var t;this._superApply(arguments),this.options.disabled||this.cancelSearch||(t=e&&e.length?this.options.messages.results(e.length):this.options.messages.noResults,this.liveRegion.text(t))}})})(jQuery);(function(e,t){function i(){this._curInst=null,this._keyEvent=!1,this._disabledInputs=[],this._datepickerShowing=!1,this._inDialog=!1,this._mainDivId="ui-datepicker-div",this._inlineClass="ui-datepicker-inline",this._appendClass="ui-datepicker-append",this._triggerClass="ui-datepicker-trigger",this._dialogClass="ui-datepicker-dialog",this._disableClass="ui-datepicker-disabled",this._unselectableClass="ui-datepicker-unselectable",this._currentClass="ui-datepicker-current-day",this._dayOverClass="ui-datepicker-days-cell-over",this.regional=[],this.regional[""]={closeText:"Done",prevText:"Prev",nextText:"Next",currentText:"Today",monthNames:["January","February","March","April","May","June","July","August","September","October","November","December"],monthNamesShort:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],dayNames:["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],dayNamesShort:["Sun","Mon","Tue","Wed","Thu","Fri","Sat"],dayNamesMin:["Su","Mo","Tu","We","Th","Fr","Sa"],weekHeader:"Wk",dateFormat:"mm/dd/yy",firstDay:0,isRTL:!1,showMonthAfterYear:!1,yearSuffix:""},this._defaults={showOn:"focus",showAnim:"fadeIn",showOptions:{},defaultDate:null,appendText:"",buttonText:"...",buttonImage:"",buttonImageOnly:!1,hideIfNoPrevNext:!1,navigationAsDateFormat:!1,gotoCurrent:!1,changeMonth:!1,changeYear:!1,yearRange:"c-10:c+10",showOtherMonths:!1,selectOtherMonths:!1,showWeek:!1,calculateWeek:this.iso8601Week,shortYearCutoff:"+10",minDate:null,maxDate:null,duration:"fast",beforeShowDay:null,beforeShow:null,onSelect:null,onChangeMonthYear:null,onClose:null,numberOfMonths:1,showCurrentAtPos:0,stepMonths:1,stepBigMonths:12,altField:"",altFormat:"",constrainInput:!0,showButtonPanel:!1,autoSize:!1,disabled:!1},e.extend(this._defaults,this.regional[""]),this.dpDiv=a(e("<div id='"+this._mainDivId+"' class='ui-datepicker ui-widget ui-widget-content ui-helper-clearfix ui-corner-all'></div>"))}function a(t){var i="button, .ui-datepicker-prev, .ui-datepicker-next, .ui-datepicker-calendar td a";return t.delegate(i,"mouseout",function(){e(this).removeClass("ui-state-hover"),-1!==this.className.indexOf("ui-datepicker-prev")&&e(this).removeClass("ui-datepicker-prev-hover"),-1!==this.className.indexOf("ui-datepicker-next")&&e(this).removeClass("ui-datepicker-next-hover")}).delegate(i,"mouseover",function(){e.datepicker._isDisabledDatepicker(n.inline?t.parent()[0]:n.input[0])||(e(this).parents(".ui-datepicker-calendar").find("a").removeClass("ui-state-hover"),e(this).addClass("ui-state-hover"),-1!==this.className.indexOf("ui-datepicker-prev")&&e(this).addClass("ui-datepicker-prev-hover"),-1!==this.className.indexOf("ui-datepicker-next")&&e(this).addClass("ui-datepicker-next-hover"))})}function s(t,i){e.extend(t,i);for(var a in i)null==i[a]&&(t[a]=i[a]);return t}e.extend(e.ui,{datepicker:{version:"1.10.4"}});var n,r="datepicker";e.extend(i.prototype,{markerClassName:"hasDatepicker",maxRows:4,_widgetDatepicker:function(){return this.dpDiv},setDefaults:function(e){return s(this._defaults,e||{}),this},_attachDatepicker:function(t,i){var a,s,n;a=t.nodeName.toLowerCase(),s="div"===a||"span"===a,t.id||(this.uuid+=1,t.id="dp"+this.uuid),n=this._newInst(e(t),s),n.settings=e.extend({},i||{}),"input"===a?this._connectDatepicker(t,n):s&&this._inlineDatepicker(t,n)},_newInst:function(t,i){var s=t[0].id.replace(/([^A-Za-z0-9_\-])/g,"\\\\$1");return{id:s,input:t,selectedDay:0,selectedMonth:0,selectedYear:0,drawMonth:0,drawYear:0,inline:i,dpDiv:i?a(e("<div class='"+this._inlineClass+" ui-datepicker ui-widget ui-widget-content ui-helper-clearfix ui-corner-all'></div>")):this.dpDiv}},_connectDatepicker:function(t,i){var a=e(t);i.append=e([]),i.trigger=e([]),a.hasClass(this.markerClassName)||(this._attachments(a,i),a.addClass(this.markerClassName).keydown(this._doKeyDown).keypress(this._doKeyPress).keyup(this._doKeyUp),this._autoSize(i),e.data(t,r,i),i.settings.disabled&&this._disableDatepicker(t))},_attachments:function(t,i){var a,s,n,r=this._get(i,"appendText"),o=this._get(i,"isRTL");i.append&&i.append.remove(),r&&(i.append=e("<span class='"+this._appendClass+"'>"+r+"</span>"),t[o?"before":"after"](i.append)),t.unbind("focus",this._showDatepicker),i.trigger&&i.trigger.remove(),a=this._get(i,"showOn"),("focus"===a||"both"===a)&&t.focus(this._showDatepicker),("button"===a||"both"===a)&&(s=this._get(i,"buttonText"),n=this._get(i,"buttonImage"),i.trigger=e(this._get(i,"buttonImageOnly")?e("<img/>").addClass(this._triggerClass).attr({src:n,alt:s,title:s}):e("<button type='button'></button>").addClass(this._triggerClass).html(n?e("<img/>").attr({src:n,alt:s,title:s}):s)),t[o?"before":"after"](i.trigger),i.trigger.click(function(){return e.datepicker._datepickerShowing&&e.datepicker._lastInput===t[0]?e.datepicker._hideDatepicker():e.datepicker._datepickerShowing&&e.datepicker._lastInput!==t[0]?(e.datepicker._hideDatepicker(),e.datepicker._showDatepicker(t[0])):e.datepicker._showDatepicker(t[0]),!1}))},_autoSize:function(e){if(this._get(e,"autoSize")&&!e.inline){var t,i,a,s,n=new Date(2009,11,20),r=this._get(e,"dateFormat");r.match(/[DM]/)&&(t=function(e){for(i=0,a=0,s=0;e.length>s;s++)e[s].length>i&&(i=e[s].length,a=s);return a},n.setMonth(t(this._get(e,r.match(/MM/)?"monthNames":"monthNamesShort"))),n.setDate(t(this._get(e,r.match(/DD/)?"dayNames":"dayNamesShort"))+20-n.getDay())),e.input.attr("size",this._formatDate(e,n).length)}},_inlineDatepicker:function(t,i){var a=e(t);a.hasClass(this.markerClassName)||(a.addClass(this.markerClassName).append(i.dpDiv),e.data(t,r,i),this._setDate(i,this._getDefaultDate(i),!0),this._updateDatepicker(i),this._updateAlternate(i),i.settings.disabled&&this._disableDatepicker(t),i.dpDiv.css("display","block"))},_dialogDatepicker:function(t,i,a,n,o){var u,c,h,l,d,p=this._dialogInst;return p||(this.uuid+=1,u="dp"+this.uuid,this._dialogInput=e("<input type='text' id='"+u+"' style='position: absolute; top: -100px; width: 0px;'/>"),this._dialogInput.keydown(this._doKeyDown),e("body").append(this._dialogInput),p=this._dialogInst=this._newInst(this._dialogInput,!1),p.settings={},e.data(this._dialogInput[0],r,p)),s(p.settings,n||{}),i=i&&i.constructor===Date?this._formatDate(p,i):i,this._dialogInput.val(i),this._pos=o?o.length?o:[o.pageX,o.pageY]:null,this._pos||(c=document.documentElement.clientWidth,h=document.documentElement.clientHeight,l=document.documentElement.scrollLeft||document.body.scrollLeft,d=document.documentElement.scrollTop||document.body.scrollTop,this._pos=[c/2-100+l,h/2-150+d]),this._dialogInput.css("left",this._pos[0]+20+"px").css("top",this._pos[1]+"px"),p.settings.onSelect=a,this._inDialog=!0,this.dpDiv.addClass(this._dialogClass),this._showDatepicker(this._dialogInput[0]),e.blockUI&&e.blockUI(this.dpDiv),e.data(this._dialogInput[0],r,p),this},_destroyDatepicker:function(t){var i,a=e(t),s=e.data(t,r);a.hasClass(this.markerClassName)&&(i=t.nodeName.toLowerCase(),e.removeData(t,r),"input"===i?(s.append.remove(),s.trigger.remove(),a.removeClass(this.markerClassName).unbind("focus",this._showDatepicker).unbind("keydown",this._doKeyDown).unbind("keypress",this._doKeyPress).unbind("keyup",this._doKeyUp)):("div"===i||"span"===i)&&a.removeClass(this.markerClassName).empty())},_enableDatepicker:function(t){var i,a,s=e(t),n=e.data(t,r);s.hasClass(this.markerClassName)&&(i=t.nodeName.toLowerCase(),"input"===i?(t.disabled=!1,n.trigger.filter("button").each(function(){this.disabled=!1}).end().filter("img").css({opacity:"1.0",cursor:""})):("div"===i||"span"===i)&&(a=s.children("."+this._inlineClass),a.children().removeClass("ui-state-disabled"),a.find("select.ui-datepicker-month, select.ui-datepicker-year").prop("disabled",!1)),this._disabledInputs=e.map(this._disabledInputs,function(e){return e===t?null:e}))},_disableDatepicker:function(t){var i,a,s=e(t),n=e.data(t,r);s.hasClass(this.markerClassName)&&(i=t.nodeName.toLowerCase(),"input"===i?(t.disabled=!0,n.trigger.filter("button").each(function(){this.disabled=!0}).end().filter("img").css({opacity:"0.5",cursor:"default"})):("div"===i||"span"===i)&&(a=s.children("."+this._inlineClass),a.children().addClass("ui-state-disabled"),a.find("select.ui-datepicker-month, select.ui-datepicker-year").prop("disabled",!0)),this._disabledInputs=e.map(this._disabledInputs,function(e){return e===t?null:e}),this._disabledInputs[this._disabledInputs.length]=t)},_isDisabledDatepicker:function(e){if(!e)return!1;for(var t=0;this._disabledInputs.length>t;t++)if(this._disabledInputs[t]===e)return!0;return!1},_getInst:function(t){try{return e.data(t,r)}catch(i){throw"Missing instance data for this datepicker"}},_optionDatepicker:function(i,a,n){var r,o,u,c,h=this._getInst(i);return 2===arguments.length&&"string"==typeof a?"defaults"===a?e.extend({},e.datepicker._defaults):h?"all"===a?e.extend({},h.settings):this._get(h,a):null:(r=a||{},"string"==typeof a&&(r={},r[a]=n),h&&(this._curInst===h&&this._hideDatepicker(),o=this._getDateDatepicker(i,!0),u=this._getMinMaxDate(h,"min"),c=this._getMinMaxDate(h,"max"),s(h.settings,r),null!==u&&r.dateFormat!==t&&r.minDate===t&&(h.settings.minDate=this._formatDate(h,u)),null!==c&&r.dateFormat!==t&&r.maxDate===t&&(h.settings.maxDate=this._formatDate(h,c)),"disabled"in r&&(r.disabled?this._disableDatepicker(i):this._enableDatepicker(i)),this._attachments(e(i),h),this._autoSize(h),this._setDate(h,o),this._updateAlternate(h),this._updateDatepicker(h)),t)},_changeDatepicker:function(e,t,i){this._optionDatepicker(e,t,i)},_refreshDatepicker:function(e){var t=this._getInst(e);t&&this._updateDatepicker(t)},_setDateDatepicker:function(e,t){var i=this._getInst(e);i&&(this._setDate(i,t),this._updateDatepicker(i),this._updateAlternate(i))},_getDateDatepicker:function(e,t){var i=this._getInst(e);return i&&!i.inline&&this._setDateFromField(i,t),i?this._getDate(i):null},_doKeyDown:function(t){var i,a,s,n=e.datepicker._getInst(t.target),r=!0,o=n.dpDiv.is(".ui-datepicker-rtl");if(n._keyEvent=!0,e.datepicker._datepickerShowing)switch(t.keyCode){case 9:e.datepicker._hideDatepicker(),r=!1;break;case 13:return s=e("td."+e.datepicker._dayOverClass+":not(."+e.datepicker._currentClass+")",n.dpDiv),s[0]&&e.datepicker._selectDay(t.target,n.selectedMonth,n.selectedYear,s[0]),i=e.datepicker._get(n,"onSelect"),i?(a=e.datepicker._formatDate(n),i.apply(n.input?n.input[0]:null,[a,n])):e.datepicker._hideDatepicker(),!1;case 27:e.datepicker._hideDatepicker();break;case 33:e.datepicker._adjustDate(t.target,t.ctrlKey?-e.datepicker._get(n,"stepBigMonths"):-e.datepicker._get(n,"stepMonths"),"M");break;case 34:e.datepicker._adjustDate(t.target,t.ctrlKey?+e.datepicker._get(n,"stepBigMonths"):+e.datepicker._get(n,"stepMonths"),"M");break;case 35:(t.ctrlKey||t.metaKey)&&e.datepicker._clearDate(t.target),r=t.ctrlKey||t.metaKey;break;case 36:(t.ctrlKey||t.metaKey)&&e.datepicker._gotoToday(t.target),r=t.ctrlKey||t.metaKey;break;case 37:(t.ctrlKey||t.metaKey)&&e.datepicker._adjustDate(t.target,o?1:-1,"D"),r=t.ctrlKey||t.metaKey,t.originalEvent.altKey&&e.datepicker._adjustDate(t.target,t.ctrlKey?-e.datepicker._get(n,"stepBigMonths"):-e.datepicker._get(n,"stepMonths"),"M");break;case 38:(t.ctrlKey||t.metaKey)&&e.datepicker._adjustDate(t.target,-7,"D"),r=t.ctrlKey||t.metaKey;break;case 39:(t.ctrlKey||t.metaKey)&&e.datepicker._adjustDate(t.target,o?-1:1,"D"),r=t.ctrlKey||t.metaKey,t.originalEvent.altKey&&e.datepicker._adjustDate(t.target,t.ctrlKey?+e.datepicker._get(n,"stepBigMonths"):+e.datepicker._get(n,"stepMonths"),"M");break;case 40:(t.ctrlKey||t.metaKey)&&e.datepicker._adjustDate(t.target,7,"D"),r=t.ctrlKey||t.metaKey;break;default:r=!1}else 36===t.keyCode&&t.ctrlKey?e.datepicker._showDatepicker(this):r=!1;r&&(t.preventDefault(),t.stopPropagation())},_doKeyPress:function(i){var a,s,n=e.datepicker._getInst(i.target);return e.datepicker._get(n,"constrainInput")?(a=e.datepicker._possibleChars(e.datepicker._get(n,"dateFormat")),s=String.fromCharCode(null==i.charCode?i.keyCode:i.charCode),i.ctrlKey||i.metaKey||" ">s||!a||a.indexOf(s)>-1):t},_doKeyUp:function(t){var i,a=e.datepicker._getInst(t.target);if(a.input.val()!==a.lastVal)try{i=e.datepicker.parseDate(e.datepicker._get(a,"dateFormat"),a.input?a.input.val():null,e.datepicker._getFormatConfig(a)),i&&(e.datepicker._setDateFromField(a),e.datepicker._updateAlternate(a),e.datepicker._updateDatepicker(a))}catch(s){}return!0},_showDatepicker:function(t){if(t=t.target||t,"input"!==t.nodeName.toLowerCase()&&(t=e("input",t.parentNode)[0]),!e.datepicker._isDisabledDatepicker(t)&&e.datepicker._lastInput!==t){var i,a,n,r,o,u,c;i=e.datepicker._getInst(t),e.datepicker._curInst&&e.datepicker._curInst!==i&&(e.datepicker._curInst.dpDiv.stop(!0,!0),i&&e.datepicker._datepickerShowing&&e.datepicker._hideDatepicker(e.datepicker._curInst.input[0])),a=e.datepicker._get(i,"beforeShow"),n=a?a.apply(t,[t,i]):{},n!==!1&&(s(i.settings,n),i.lastVal=null,e.datepicker._lastInput=t,e.datepicker._setDateFromField(i),e.datepicker._inDialog&&(t.value=""),e.datepicker._pos||(e.datepicker._pos=e.datepicker._findPos(t),e.datepicker._pos[1]+=t.offsetHeight),r=!1,e(t).parents().each(function(){return r|="fixed"===e(this).css("position"),!r}),o={left:e.datepicker._pos[0],top:e.datepicker._pos[1]},e.datepicker._pos=null,i.dpDiv.empty(),i.dpDiv.css({position:"absolute",display:"block",top:"-1000px"}),e.datepicker._updateDatepicker(i),o=e.datepicker._checkOffset(i,o,r),i.dpDiv.css({position:e.datepicker._inDialog&&e.blockUI?"static":r?"fixed":"absolute",display:"none",left:o.left+"px",top:o.top+"px"}),i.inline||(u=e.datepicker._get(i,"showAnim"),c=e.datepicker._get(i,"duration"),i.dpDiv.zIndex(e(t).zIndex()+1),e.datepicker._datepickerShowing=!0,e.effects&&e.effects.effect[u]?i.dpDiv.show(u,e.datepicker._get(i,"showOptions"),c):i.dpDiv[u||"show"](u?c:null),e.datepicker._shouldFocusInput(i)&&i.input.focus(),e.datepicker._curInst=i))}},_updateDatepicker:function(t){this.maxRows=4,n=t,t.dpDiv.empty().append(this._generateHTML(t)),this._attachHandlers(t),t.dpDiv.find("."+this._dayOverClass+" a").mouseover();var i,a=this._getNumberOfMonths(t),s=a[1],r=17;t.dpDiv.removeClass("ui-datepicker-multi-2 ui-datepicker-multi-3 ui-datepicker-multi-4").width(""),s>1&&t.dpDiv.addClass("ui-datepicker-multi-"+s).css("width",r*s+"em"),t.dpDiv[(1!==a[0]||1!==a[1]?"add":"remove")+"Class"]("ui-datepicker-multi"),t.dpDiv[(this._get(t,"isRTL")?"add":"remove")+"Class"]("ui-datepicker-rtl"),t===e.datepicker._curInst&&e.datepicker._datepickerShowing&&e.datepicker._shouldFocusInput(t)&&t.input.focus(),t.yearshtml&&(i=t.yearshtml,setTimeout(function(){i===t.yearshtml&&t.yearshtml&&t.dpDiv.find("select.ui-datepicker-year:first").replaceWith(t.yearshtml),i=t.yearshtml=null},0))},_shouldFocusInput:function(e){return e.input&&e.input.is(":visible")&&!e.input.is(":disabled")&&!e.input.is(":focus")},_checkOffset:function(t,i,a){var s=t.dpDiv.outerWidth(),n=t.dpDiv.outerHeight(),r=t.input?t.input.outerWidth():0,o=t.input?t.input.outerHeight():0,u=document.documentElement.clientWidth+(a?0:e(document).scrollLeft()),c=document.documentElement.clientHeight+(a?0:e(document).scrollTop());return i.left-=this._get(t,"isRTL")?s-r:0,i.left-=a&&i.left===t.input.offset().left?e(document).scrollLeft():0,i.top-=a&&i.top===t.input.offset().top+o?e(document).scrollTop():0,i.left-=Math.min(i.left,i.left+s>u&&u>s?Math.abs(i.left+s-u):0),i.top-=Math.min(i.top,i.top+n>c&&c>n?Math.abs(n+o):0),i},_findPos:function(t){for(var i,a=this._getInst(t),s=this._get(a,"isRTL");t&&("hidden"===t.type||1!==t.nodeType||e.expr.filters.hidden(t));)t=t[s?"previousSibling":"nextSibling"];return i=e(t).offset(),[i.left,i.top]},_hideDatepicker:function(t){var i,a,s,n,o=this._curInst;!o||t&&o!==e.data(t,r)||this._datepickerShowing&&(i=this._get(o,"showAnim"),a=this._get(o,"duration"),s=function(){e.datepicker._tidyDialog(o)},e.effects&&(e.effects.effect[i]||e.effects[i])?o.dpDiv.hide(i,e.datepicker._get(o,"showOptions"),a,s):o.dpDiv["slideDown"===i?"slideUp":"fadeIn"===i?"fadeOut":"hide"](i?a:null,s),i||s(),this._datepickerShowing=!1,n=this._get(o,"onClose"),n&&n.apply(o.input?o.input[0]:null,[o.input?o.input.val():"",o]),this._lastInput=null,this._inDialog&&(this._dialogInput.css({position:"absolute",left:"0",top:"-100px"}),e.blockUI&&(e.unblockUI(),e("body").append(this.dpDiv))),this._inDialog=!1)},_tidyDialog:function(e){e.dpDiv.removeClass(this._dialogClass).unbind(".ui-datepicker-calendar")},_checkExternalClick:function(t){if(e.datepicker._curInst){var i=e(t.target),a=e.datepicker._getInst(i[0]);(i[0].id!==e.datepicker._mainDivId&&0===i.parents("#"+e.datepicker._mainDivId).length&&!i.hasClass(e.datepicker.markerClassName)&&!i.closest("."+e.datepicker._triggerClass).length&&e.datepicker._datepickerShowing&&(!e.datepicker._inDialog||!e.blockUI)||i.hasClass(e.datepicker.markerClassName)&&e.datepicker._curInst!==a)&&e.datepicker._hideDatepicker()}},_adjustDate:function(t,i,a){var s=e(t),n=this._getInst(s[0]);this._isDisabledDatepicker(s[0])||(this._adjustInstDate(n,i+("M"===a?this._get(n,"showCurrentAtPos"):0),a),this._updateDatepicker(n))},_gotoToday:function(t){var i,a=e(t),s=this._getInst(a[0]);this._get(s,"gotoCurrent")&&s.currentDay?(s.selectedDay=s.currentDay,s.drawMonth=s.selectedMonth=s.currentMonth,s.drawYear=s.selectedYear=s.currentYear):(i=new Date,s.selectedDay=i.getDate(),s.drawMonth=s.selectedMonth=i.getMonth(),s.drawYear=s.selectedYear=i.getFullYear()),this._notifyChange(s),this._adjustDate(a)},_selectMonthYear:function(t,i,a){var s=e(t),n=this._getInst(s[0]);n["selected"+("M"===a?"Month":"Year")]=n["draw"+("M"===a?"Month":"Year")]=parseInt(i.options[i.selectedIndex].value,10),this._notifyChange(n),this._adjustDate(s)},_selectDay:function(t,i,a,s){var n,r=e(t);e(s).hasClass(this._unselectableClass)||this._isDisabledDatepicker(r[0])||(n=this._getInst(r[0]),n.selectedDay=n.currentDay=e("a",s).html(),n.selectedMonth=n.currentMonth=i,n.selectedYear=n.currentYear=a,this._selectDate(t,this._formatDate(n,n.currentDay,n.currentMonth,n.currentYear)))},_clearDate:function(t){var i=e(t);this._selectDate(i,"")},_selectDate:function(t,i){var a,s=e(t),n=this._getInst(s[0]);i=null!=i?i:this._formatDate(n),n.input&&n.input.val(i),this._updateAlternate(n),a=this._get(n,"onSelect"),a?a.apply(n.input?n.input[0]:null,[i,n]):n.input&&n.input.trigger("change"),n.inline?this._updateDatepicker(n):(this._hideDatepicker(),this._lastInput=n.input[0],"object"!=typeof n.input[0]&&n.input.focus(),this._lastInput=null)},_updateAlternate:function(t){var i,a,s,n=this._get(t,"altField");n&&(i=this._get(t,"altFormat")||this._get(t,"dateFormat"),a=this._getDate(t),s=this.formatDate(i,a,this._getFormatConfig(t)),e(n).each(function(){e(this).val(s)}))},noWeekends:function(e){var t=e.getDay();return[t>0&&6>t,""]},iso8601Week:function(e){var t,i=new Date(e.getTime());return i.setDate(i.getDate()+4-(i.getDay()||7)),t=i.getTime(),i.setMonth(0),i.setDate(1),Math.floor(Math.round((t-i)/864e5)/7)+1},parseDate:function(i,a,s){if(null==i||null==a)throw"Invalid arguments";if(a="object"==typeof a?""+a:a+"",""===a)return null;var n,r,o,u,c=0,h=(s?s.shortYearCutoff:null)||this._defaults.shortYearCutoff,l="string"!=typeof h?h:(new Date).getFullYear()%100+parseInt(h,10),d=(s?s.dayNamesShort:null)||this._defaults.dayNamesShort,p=(s?s.dayNames:null)||this._defaults.dayNames,g=(s?s.monthNamesShort:null)||this._defaults.monthNamesShort,m=(s?s.monthNames:null)||this._defaults.monthNames,f=-1,_=-1,v=-1,k=-1,y=!1,b=function(e){var t=i.length>n+1&&i.charAt(n+1)===e;return t&&n++,t},D=function(e){var t=b(e),i="@"===e?14:"!"===e?20:"y"===e&&t?4:"o"===e?3:2,s=RegExp("^\\d{1,"+i+"}"),n=a.substring(c).match(s);if(!n)throw"Missing number at position "+c;return c+=n[0].length,parseInt(n[0],10)},w=function(i,s,n){var r=-1,o=e.map(b(i)?n:s,function(e,t){return[[t,e]]}).sort(function(e,t){return-(e[1].length-t[1].length)});if(e.each(o,function(e,i){var s=i[1];return a.substr(c,s.length).toLowerCase()===s.toLowerCase()?(r=i[0],c+=s.length,!1):t}),-1!==r)return r+1;throw"Unknown name at position "+c},M=function(){if(a.charAt(c)!==i.charAt(n))throw"Unexpected literal at position "+c;c++};for(n=0;i.length>n;n++)if(y)"'"!==i.charAt(n)||b("'")?M():y=!1;else switch(i.charAt(n)){case"d":v=D("d");break;case"D":w("D",d,p);break;case"o":k=D("o");break;case"m":_=D("m");break;case"M":_=w("M",g,m);break;case"y":f=D("y");break;case"@":u=new Date(D("@")),f=u.getFullYear(),_=u.getMonth()+1,v=u.getDate();break;case"!":u=new Date((D("!")-this._ticksTo1970)/1e4),f=u.getFullYear(),_=u.getMonth()+1,v=u.getDate();break;case"'":b("'")?M():y=!0;break;default:M()}if(a.length>c&&(o=a.substr(c),!/^\s+/.test(o)))throw"Extra/unparsed characters found in date: "+o;if(-1===f?f=(new Date).getFullYear():100>f&&(f+=(new Date).getFullYear()-(new Date).getFullYear()%100+(l>=f?0:-100)),k>-1)for(_=1,v=k;;){if(r=this._getDaysInMonth(f,_-1),r>=v)break;_++,v-=r}if(u=this._daylightSavingAdjust(new Date(f,_-1,v)),u.getFullYear()!==f||u.getMonth()+1!==_||u.getDate()!==v)throw"Invalid date";return u},ATOM:"yy-mm-dd",COOKIE:"D, dd M yy",ISO_8601:"yy-mm-dd",RFC_822:"D, d M y",RFC_850:"DD, dd-M-y",RFC_1036:"D, d M y",RFC_1123:"D, d M yy",RFC_2822:"D, d M yy",RSS:"D, d M y",TICKS:"!",TIMESTAMP:"@",W3C:"yy-mm-dd",_ticksTo1970:1e7*60*60*24*(718685+Math.floor(492.5)-Math.floor(19.7)+Math.floor(4.925)),formatDate:function(e,t,i){if(!t)return"";var a,s=(i?i.dayNamesShort:null)||this._defaults.dayNamesShort,n=(i?i.dayNames:null)||this._defaults.dayNames,r=(i?i.monthNamesShort:null)||this._defaults.monthNamesShort,o=(i?i.monthNames:null)||this._defaults.monthNames,u=function(t){var i=e.length>a+1&&e.charAt(a+1)===t;return i&&a++,i},c=function(e,t,i){var a=""+t;if(u(e))for(;i>a.length;)a="0"+a;return a},h=function(e,t,i,a){return u(e)?a[t]:i[t]},l="",d=!1;if(t)for(a=0;e.length>a;a++)if(d)"'"!==e.charAt(a)||u("'")?l+=e.charAt(a):d=!1;else switch(e.charAt(a)){case"d":l+=c("d",t.getDate(),2);break;case"D":l+=h("D",t.getDay(),s,n);break;case"o":l+=c("o",Math.round((new Date(t.getFullYear(),t.getMonth(),t.getDate()).getTime()-new Date(t.getFullYear(),0,0).getTime())/864e5),3);break;case"m":l+=c("m",t.getMonth()+1,2);break;case"M":l+=h("M",t.getMonth(),r,o);break;case"y":l+=u("y")?t.getFullYear():(10>t.getYear()%100?"0":"")+t.getYear()%100;break;case"@":l+=t.getTime();break;case"!":l+=1e4*t.getTime()+this._ticksTo1970;break;case"'":u("'")?l+="'":d=!0;break;default:l+=e.charAt(a)}return l},_possibleChars:function(e){var t,i="",a=!1,s=function(i){var a=e.length>t+1&&e.charAt(t+1)===i;return a&&t++,a};for(t=0;e.length>t;t++)if(a)"'"!==e.charAt(t)||s("'")?i+=e.charAt(t):a=!1;else switch(e.charAt(t)){case"d":case"m":case"y":case"@":i+="0123456789";break;case"D":case"M":return null;case"'":s("'")?i+="'":a=!0;break;default:i+=e.charAt(t)}return i},_get:function(e,i){return e.settings[i]!==t?e.settings[i]:this._defaults[i]},_setDateFromField:function(e,t){if(e.input.val()!==e.lastVal){var i=this._get(e,"dateFormat"),a=e.lastVal=e.input?e.input.val():null,s=this._getDefaultDate(e),n=s,r=this._getFormatConfig(e);try{n=this.parseDate(i,a,r)||s}catch(o){a=t?"":a}e.selectedDay=n.getDate(),e.drawMonth=e.selectedMonth=n.getMonth(),e.drawYear=e.selectedYear=n.getFullYear(),e.currentDay=a?n.getDate():0,e.currentMonth=a?n.getMonth():0,e.currentYear=a?n.getFullYear():0,this._adjustInstDate(e)}},_getDefaultDate:function(e){return this._restrictMinMax(e,this._determineDate(e,this._get(e,"defaultDate"),new Date))},_determineDate:function(t,i,a){var s=function(e){var t=new Date;return t.setDate(t.getDate()+e),t},n=function(i){try{return e.datepicker.parseDate(e.datepicker._get(t,"dateFormat"),i,e.datepicker._getFormatConfig(t))}catch(a){}for(var s=(i.toLowerCase().match(/^c/)?e.datepicker._getDate(t):null)||new Date,n=s.getFullYear(),r=s.getMonth(),o=s.getDate(),u=/([+\-]?[0-9]+)\s*(d|D|w|W|m|M|y|Y)?/g,c=u.exec(i);c;){switch(c[2]||"d"){case"d":case"D":o+=parseInt(c[1],10);break;case"w":case"W":o+=7*parseInt(c[1],10);break;case"m":case"M":r+=parseInt(c[1],10),o=Math.min(o,e.datepicker._getDaysInMonth(n,r));break;case"y":case"Y":n+=parseInt(c[1],10),o=Math.min(o,e.datepicker._getDaysInMonth(n,r))}c=u.exec(i)}return new Date(n,r,o)},r=null==i||""===i?a:"string"==typeof i?n(i):"number"==typeof i?isNaN(i)?a:s(i):new Date(i.getTime());return r=r&&"Invalid Date"==""+r?a:r,r&&(r.setHours(0),r.setMinutes(0),r.setSeconds(0),r.setMilliseconds(0)),this._daylightSavingAdjust(r)},_daylightSavingAdjust:function(e){return e?(e.setHours(e.getHours()>12?e.getHours()+2:0),e):null},_setDate:function(e,t,i){var a=!t,s=e.selectedMonth,n=e.selectedYear,r=this._restrictMinMax(e,this._determineDate(e,t,new Date));e.selectedDay=e.currentDay=r.getDate(),e.drawMonth=e.selectedMonth=e.currentMonth=r.getMonth(),e.drawYear=e.selectedYear=e.currentYear=r.getFullYear(),s===e.selectedMonth&&n===e.selectedYear||i||this._notifyChange(e),this._adjustInstDate(e),e.input&&e.input.val(a?"":this._formatDate(e))},_getDate:function(e){var t=!e.currentYear||e.input&&""===e.input.val()?null:this._daylightSavingAdjust(new Date(e.currentYear,e.currentMonth,e.currentDay));return t},_attachHandlers:function(t){var i=this._get(t,"stepMonths"),a="#"+t.id.replace(/\\\\/g,"\\");t.dpDiv.find("[data-handler]").map(function(){var t={prev:function(){e.datepicker._adjustDate(a,-i,"M")},next:function(){e.datepicker._adjustDate(a,+i,"M")},hide:function(){e.datepicker._hideDatepicker()},today:function(){e.datepicker._gotoToday(a)},selectDay:function(){return e.datepicker._selectDay(a,+this.getAttribute("data-month"),+this.getAttribute("data-year"),this),!1},selectMonth:function(){return e.datepicker._selectMonthYear(a,this,"M"),!1},selectYear:function(){return e.datepicker._selectMonthYear(a,this,"Y"),!1}};e(this).bind(this.getAttribute("data-event"),t[this.getAttribute("data-handler")])})},_generateHTML:function(e){var t,i,a,s,n,r,o,u,c,h,l,d,p,g,m,f,_,v,k,y,b,D,w,M,C,x,I,N,T,A,E,S,Y,F,P,O,j,K,R,H=new Date,W=this._daylightSavingAdjust(new Date(H.getFullYear(),H.getMonth(),H.getDate())),L=this._get(e,"isRTL"),U=this._get(e,"showButtonPanel"),B=this._get(e,"hideIfNoPrevNext"),z=this._get(e,"navigationAsDateFormat"),q=this._getNumberOfMonths(e),G=this._get(e,"showCurrentAtPos"),J=this._get(e,"stepMonths"),Q=1!==q[0]||1!==q[1],V=this._daylightSavingAdjust(e.currentDay?new Date(e.currentYear,e.currentMonth,e.currentDay):new Date(9999,9,9)),$=this._getMinMaxDate(e,"min"),X=this._getMinMaxDate(e,"max"),Z=e.drawMonth-G,et=e.drawYear;if(0>Z&&(Z+=12,et--),X)for(t=this._daylightSavingAdjust(new Date(X.getFullYear(),X.getMonth()-q[0]*q[1]+1,X.getDate())),t=$&&$>t?$:t;this._daylightSavingAdjust(new Date(et,Z,1))>t;)Z--,0>Z&&(Z=11,et--);for(e.drawMonth=Z,e.drawYear=et,i=this._get(e,"prevText"),i=z?this.formatDate(i,this._daylightSavingAdjust(new Date(et,Z-J,1)),this._getFormatConfig(e)):i,a=this._canAdjustMonth(e,-1,et,Z)?"<a class='ui-datepicker-prev ui-corner-all' data-handler='prev' data-event='click' title='"+i+"'><span class='ui-icon ui-icon-circle-triangle-"+(L?"e":"w")+"'>"+i+"</span></a>":B?"":"<a class='ui-datepicker-prev ui-corner-all ui-state-disabled' title='"+i+"'><span class='ui-icon ui-icon-circle-triangle-"+(L?"e":"w")+"'>"+i+"</span></a>",s=this._get(e,"nextText"),s=z?this.formatDate(s,this._daylightSavingAdjust(new Date(et,Z+J,1)),this._getFormatConfig(e)):s,n=this._canAdjustMonth(e,1,et,Z)?"<a class='ui-datepicker-next ui-corner-all' data-handler='next' data-event='click' title='"+s+"'><span class='ui-icon ui-icon-circle-triangle-"+(L?"w":"e")+"'>"+s+"</span></a>":B?"":"<a class='ui-datepicker-next ui-corner-all ui-state-disabled' title='"+s+"'><span class='ui-icon ui-icon-circle-triangle-"+(L?"w":"e")+"'>"+s+"</span></a>",r=this._get(e,"currentText"),o=this._get(e,"gotoCurrent")&&e.currentDay?V:W,r=z?this.formatDate(r,o,this._getFormatConfig(e)):r,u=e.inline?"":"<button type='button' class='ui-datepicker-close ui-state-default ui-priority-primary ui-corner-all' data-handler='hide' data-event='click'>"+this._get(e,"closeText")+"</button>",c=U?"<div class='ui-datepicker-buttonpane ui-widget-content'>"+(L?u:"")+(this._isInRange(e,o)?"<button type='button' class='ui-datepicker-current ui-state-default ui-priority-secondary ui-corner-all' data-handler='today' data-event='click'>"+r+"</button>":"")+(L?"":u)+"</div>":"",h=parseInt(this._get(e,"firstDay"),10),h=isNaN(h)?0:h,l=this._get(e,"showWeek"),d=this._get(e,"dayNames"),p=this._get(e,"dayNamesMin"),g=this._get(e,"monthNames"),m=this._get(e,"monthNamesShort"),f=this._get(e,"beforeShowDay"),_=this._get(e,"showOtherMonths"),v=this._get(e,"selectOtherMonths"),k=this._getDefaultDate(e),y="",D=0;q[0]>D;D++){for(w="",this.maxRows=4,M=0;q[1]>M;M++){if(C=this._daylightSavingAdjust(new Date(et,Z,e.selectedDay)),x=" ui-corner-all",I="",Q){if(I+="<div class='ui-datepicker-group",q[1]>1)switch(M){case 0:I+=" ui-datepicker-group-first",x=" ui-corner-"+(L?"right":"left");break;case q[1]-1:I+=" ui-datepicker-group-last",x=" ui-corner-"+(L?"left":"right");break;default:I+=" ui-datepicker-group-middle",x=""}I+="'>"}for(I+="<div class='ui-datepicker-header ui-widget-header ui-helper-clearfix"+x+"'>"+(/all|left/.test(x)&&0===D?L?n:a:"")+(/all|right/.test(x)&&0===D?L?a:n:"")+this._generateMonthYearHeader(e,Z,et,$,X,D>0||M>0,g,m)+"</div><table class='ui-datepicker-calendar'><thead>"+"<tr>",N=l?"<th class='ui-datepicker-week-col'>"+this._get(e,"weekHeader")+"</th>":"",b=0;7>b;b++)T=(b+h)%7,N+="<th"+((b+h+6)%7>=5?" class='ui-datepicker-week-end'":"")+">"+"<span title='"+d[T]+"'>"+p[T]+"</span></th>";for(I+=N+"</tr></thead><tbody>",A=this._getDaysInMonth(et,Z),et===e.selectedYear&&Z===e.selectedMonth&&(e.selectedDay=Math.min(e.selectedDay,A)),E=(this._getFirstDayOfMonth(et,Z)-h+7)%7,S=Math.ceil((E+A)/7),Y=Q?this.maxRows>S?this.maxRows:S:S,this.maxRows=Y,F=this._daylightSavingAdjust(new Date(et,Z,1-E)),P=0;Y>P;P++){for(I+="<tr>",O=l?"<td class='ui-datepicker-week-col'>"+this._get(e,"calculateWeek")(F)+"</td>":"",b=0;7>b;b++)j=f?f.apply(e.input?e.input[0]:null,[F]):[!0,""],K=F.getMonth()!==Z,R=K&&!v||!j[0]||$&&$>F||X&&F>X,O+="<td class='"+((b+h+6)%7>=5?" ui-datepicker-week-end":"")+(K?" ui-datepicker-other-month":"")+(F.getTime()===C.getTime()&&Z===e.selectedMonth&&e._keyEvent||k.getTime()===F.getTime()&&k.getTime()===C.getTime()?" "+this._dayOverClass:"")+(R?" "+this._unselectableClass+" ui-state-disabled":"")+(K&&!_?"":" "+j[1]+(F.getTime()===V.getTime()?" "+this._currentClass:"")+(F.getTime()===W.getTime()?" ui-datepicker-today":""))+"'"+(K&&!_||!j[2]?"":" title='"+j[2].replace(/'/g,"&#39;")+"'")+(R?"":" data-handler='selectDay' data-event='click' data-month='"+F.getMonth()+"' data-year='"+F.getFullYear()+"'")+">"+(K&&!_?"&#xa0;":R?"<span class='ui-state-default'>"+F.getDate()+"</span>":"<a class='ui-state-default"+(F.getTime()===W.getTime()?" ui-state-highlight":"")+(F.getTime()===V.getTime()?" ui-state-active":"")+(K?" ui-priority-secondary":"")+"' href='#'>"+F.getDate()+"</a>")+"</td>",F.setDate(F.getDate()+1),F=this._daylightSavingAdjust(F);I+=O+"</tr>"}Z++,Z>11&&(Z=0,et++),I+="</tbody></table>"+(Q?"</div>"+(q[0]>0&&M===q[1]-1?"<div class='ui-datepicker-row-break'></div>":""):""),w+=I}y+=w}return y+=c,e._keyEvent=!1,y},_generateMonthYearHeader:function(e,t,i,a,s,n,r,o){var u,c,h,l,d,p,g,m,f=this._get(e,"changeMonth"),_=this._get(e,"changeYear"),v=this._get(e,"showMonthAfterYear"),k="<div class='ui-datepicker-title'>",y="";if(n||!f)y+="<span class='ui-datepicker-month'>"+r[t]+"</span>";else{for(u=a&&a.getFullYear()===i,c=s&&s.getFullYear()===i,y+="<select class='ui-datepicker-month' data-handler='selectMonth' data-event='change'>",h=0;12>h;h++)(!u||h>=a.getMonth())&&(!c||s.getMonth()>=h)&&(y+="<option value='"+h+"'"+(h===t?" selected='selected'":"")+">"+o[h]+"</option>");y+="</select>"}if(v||(k+=y+(!n&&f&&_?"":"&#xa0;")),!e.yearshtml)if(e.yearshtml="",n||!_)k+="<span class='ui-datepicker-year'>"+i+"</span>";else{for(l=this._get(e,"yearRange").split(":"),d=(new Date).getFullYear(),p=function(e){var t=e.match(/c[+\-].*/)?i+parseInt(e.substring(1),10):e.match(/[+\-].*/)?d+parseInt(e,10):parseInt(e,10);
return isNaN(t)?d:t},g=p(l[0]),m=Math.max(g,p(l[1]||"")),g=a?Math.max(g,a.getFullYear()):g,m=s?Math.min(m,s.getFullYear()):m,e.yearshtml+="<select class='ui-datepicker-year' data-handler='selectYear' data-event='change'>";m>=g;g++)e.yearshtml+="<option value='"+g+"'"+(g===i?" selected='selected'":"")+">"+g+"</option>";e.yearshtml+="</select>",k+=e.yearshtml,e.yearshtml=null}return k+=this._get(e,"yearSuffix"),v&&(k+=(!n&&f&&_?"":"&#xa0;")+y),k+="</div>"},_adjustInstDate:function(e,t,i){var a=e.drawYear+("Y"===i?t:0),s=e.drawMonth+("M"===i?t:0),n=Math.min(e.selectedDay,this._getDaysInMonth(a,s))+("D"===i?t:0),r=this._restrictMinMax(e,this._daylightSavingAdjust(new Date(a,s,n)));e.selectedDay=r.getDate(),e.drawMonth=e.selectedMonth=r.getMonth(),e.drawYear=e.selectedYear=r.getFullYear(),("M"===i||"Y"===i)&&this._notifyChange(e)},_restrictMinMax:function(e,t){var i=this._getMinMaxDate(e,"min"),a=this._getMinMaxDate(e,"max"),s=i&&i>t?i:t;return a&&s>a?a:s},_notifyChange:function(e){var t=this._get(e,"onChangeMonthYear");t&&t.apply(e.input?e.input[0]:null,[e.selectedYear,e.selectedMonth+1,e])},_getNumberOfMonths:function(e){var t=this._get(e,"numberOfMonths");return null==t?[1,1]:"number"==typeof t?[1,t]:t},_getMinMaxDate:function(e,t){return this._determineDate(e,this._get(e,t+"Date"),null)},_getDaysInMonth:function(e,t){return 32-this._daylightSavingAdjust(new Date(e,t,32)).getDate()},_getFirstDayOfMonth:function(e,t){return new Date(e,t,1).getDay()},_canAdjustMonth:function(e,t,i,a){var s=this._getNumberOfMonths(e),n=this._daylightSavingAdjust(new Date(i,a+(0>t?t:s[0]*s[1]),1));return 0>t&&n.setDate(this._getDaysInMonth(n.getFullYear(),n.getMonth())),this._isInRange(e,n)},_isInRange:function(e,t){var i,a,s=this._getMinMaxDate(e,"min"),n=this._getMinMaxDate(e,"max"),r=null,o=null,u=this._get(e,"yearRange");return u&&(i=u.split(":"),a=(new Date).getFullYear(),r=parseInt(i[0],10),o=parseInt(i[1],10),i[0].match(/[+\-].*/)&&(r+=a),i[1].match(/[+\-].*/)&&(o+=a)),(!s||t.getTime()>=s.getTime())&&(!n||t.getTime()<=n.getTime())&&(!r||t.getFullYear()>=r)&&(!o||o>=t.getFullYear())},_getFormatConfig:function(e){var t=this._get(e,"shortYearCutoff");return t="string"!=typeof t?t:(new Date).getFullYear()%100+parseInt(t,10),{shortYearCutoff:t,dayNamesShort:this._get(e,"dayNamesShort"),dayNames:this._get(e,"dayNames"),monthNamesShort:this._get(e,"monthNamesShort"),monthNames:this._get(e,"monthNames")}},_formatDate:function(e,t,i,a){t||(e.currentDay=e.selectedDay,e.currentMonth=e.selectedMonth,e.currentYear=e.selectedYear);var s=t?"object"==typeof t?t:this._daylightSavingAdjust(new Date(a,i,t)):this._daylightSavingAdjust(new Date(e.currentYear,e.currentMonth,e.currentDay));return this.formatDate(this._get(e,"dateFormat"),s,this._getFormatConfig(e))}}),e.fn.datepicker=function(t){if(!this.length)return this;e.datepicker.initialized||(e(document).mousedown(e.datepicker._checkExternalClick),e.datepicker.initialized=!0),0===e("#"+e.datepicker._mainDivId).length&&e("body").append(e.datepicker.dpDiv);var i=Array.prototype.slice.call(arguments,1);return"string"!=typeof t||"isDisabled"!==t&&"getDate"!==t&&"widget"!==t?"option"===t&&2===arguments.length&&"string"==typeof arguments[1]?e.datepicker["_"+t+"Datepicker"].apply(e.datepicker,[this[0]].concat(i)):this.each(function(){"string"==typeof t?e.datepicker["_"+t+"Datepicker"].apply(e.datepicker,[this].concat(i)):e.datepicker._attachDatepicker(this,t)}):e.datepicker["_"+t+"Datepicker"].apply(e.datepicker,[this[0]].concat(i))},e.datepicker=new i,e.datepicker.initialized=!1,e.datepicker.uuid=(new Date).getTime(),e.datepicker.version="1.10.4"})(jQuery);(function(t){t.widget("ui.menu",{version:"1.10.4",defaultElement:"<ul>",delay:300,options:{icons:{submenu:"ui-icon-carat-1-e"},menus:"ul",position:{my:"left top",at:"right top"},role:"menu",blur:null,focus:null,select:null},_create:function(){this.activeMenu=this.element,this.mouseHandled=!1,this.element.uniqueId().addClass("ui-menu ui-widget ui-widget-content ui-corner-all").toggleClass("ui-menu-icons",!!this.element.find(".ui-icon").length).attr({role:this.options.role,tabIndex:0}).bind("click"+this.eventNamespace,t.proxy(function(t){this.options.disabled&&t.preventDefault()},this)),this.options.disabled&&this.element.addClass("ui-state-disabled").attr("aria-disabled","true"),this._on({"mousedown .ui-menu-item > a":function(t){t.preventDefault()},"click .ui-state-disabled > a":function(t){t.preventDefault()},"click .ui-menu-item:has(a)":function(e){var i=t(e.target).closest(".ui-menu-item");!this.mouseHandled&&i.not(".ui-state-disabled").length&&(this.select(e),e.isPropagationStopped()||(this.mouseHandled=!0),i.has(".ui-menu").length?this.expand(e):!this.element.is(":focus")&&t(this.document[0].activeElement).closest(".ui-menu").length&&(this.element.trigger("focus",[!0]),this.active&&1===this.active.parents(".ui-menu").length&&clearTimeout(this.timer)))},"mouseenter .ui-menu-item":function(e){var i=t(e.currentTarget);i.siblings().children(".ui-state-active").removeClass("ui-state-active"),this.focus(e,i)},mouseleave:"collapseAll","mouseleave .ui-menu":"collapseAll",focus:function(t,e){var i=this.active||this.element.children(".ui-menu-item").eq(0);e||this.focus(t,i)},blur:function(e){this._delay(function(){t.contains(this.element[0],this.document[0].activeElement)||this.collapseAll(e)})},keydown:"_keydown"}),this.refresh(),this._on(this.document,{click:function(e){t(e.target).closest(".ui-menu").length||this.collapseAll(e),this.mouseHandled=!1}})},_destroy:function(){this.element.removeAttr("aria-activedescendant").find(".ui-menu").addBack().removeClass("ui-menu ui-widget ui-widget-content ui-corner-all ui-menu-icons").removeAttr("role").removeAttr("tabIndex").removeAttr("aria-labelledby").removeAttr("aria-expanded").removeAttr("aria-hidden").removeAttr("aria-disabled").removeUniqueId().show(),this.element.find(".ui-menu-item").removeClass("ui-menu-item").removeAttr("role").removeAttr("aria-disabled").children("a").removeUniqueId().removeClass("ui-corner-all ui-state-hover").removeAttr("tabIndex").removeAttr("role").removeAttr("aria-haspopup").children().each(function(){var e=t(this);e.data("ui-menu-submenu-carat")&&e.remove()}),this.element.find(".ui-menu-divider").removeClass("ui-menu-divider ui-widget-content")},_keydown:function(e){function i(t){return t.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g,"\\$&")}var s,n,a,o,r,l=!0;switch(e.keyCode){case t.ui.keyCode.PAGE_UP:this.previousPage(e);break;case t.ui.keyCode.PAGE_DOWN:this.nextPage(e);break;case t.ui.keyCode.HOME:this._move("first","first",e);break;case t.ui.keyCode.END:this._move("last","last",e);break;case t.ui.keyCode.UP:this.previous(e);break;case t.ui.keyCode.DOWN:this.next(e);break;case t.ui.keyCode.LEFT:this.collapse(e);break;case t.ui.keyCode.RIGHT:this.active&&!this.active.is(".ui-state-disabled")&&this.expand(e);break;case t.ui.keyCode.ENTER:case t.ui.keyCode.SPACE:this._activate(e);break;case t.ui.keyCode.ESCAPE:this.collapse(e);break;default:l=!1,n=this.previousFilter||"",a=String.fromCharCode(e.keyCode),o=!1,clearTimeout(this.filterTimer),a===n?o=!0:a=n+a,r=RegExp("^"+i(a),"i"),s=this.activeMenu.children(".ui-menu-item").filter(function(){return r.test(t(this).children("a").text())}),s=o&&-1!==s.index(this.active.next())?this.active.nextAll(".ui-menu-item"):s,s.length||(a=String.fromCharCode(e.keyCode),r=RegExp("^"+i(a),"i"),s=this.activeMenu.children(".ui-menu-item").filter(function(){return r.test(t(this).children("a").text())})),s.length?(this.focus(e,s),s.length>1?(this.previousFilter=a,this.filterTimer=this._delay(function(){delete this.previousFilter},1e3)):delete this.previousFilter):delete this.previousFilter}l&&e.preventDefault()},_activate:function(t){this.active.is(".ui-state-disabled")||(this.active.children("a[aria-haspopup='true']").length?this.expand(t):this.select(t))},refresh:function(){var e,i=this.options.icons.submenu,s=this.element.find(this.options.menus);this.element.toggleClass("ui-menu-icons",!!this.element.find(".ui-icon").length),s.filter(":not(.ui-menu)").addClass("ui-menu ui-widget ui-widget-content ui-corner-all").hide().attr({role:this.options.role,"aria-hidden":"true","aria-expanded":"false"}).each(function(){var e=t(this),s=e.prev("a"),n=t("<span>").addClass("ui-menu-icon ui-icon "+i).data("ui-menu-submenu-carat",!0);s.attr("aria-haspopup","true").prepend(n),e.attr("aria-labelledby",s.attr("id"))}),e=s.add(this.element),e.children(":not(.ui-menu-item):has(a)").addClass("ui-menu-item").attr("role","presentation").children("a").uniqueId().addClass("ui-corner-all").attr({tabIndex:-1,role:this._itemRole()}),e.children(":not(.ui-menu-item)").each(function(){var e=t(this);/[^\-\u2014\u2013\s]/.test(e.text())||e.addClass("ui-widget-content ui-menu-divider")}),e.children(".ui-state-disabled").attr("aria-disabled","true"),this.active&&!t.contains(this.element[0],this.active[0])&&this.blur()},_itemRole:function(){return{menu:"menuitem",listbox:"option"}[this.options.role]},_setOption:function(t,e){"icons"===t&&this.element.find(".ui-menu-icon").removeClass(this.options.icons.submenu).addClass(e.submenu),this._super(t,e)},focus:function(t,e){var i,s;this.blur(t,t&&"focus"===t.type),this._scrollIntoView(e),this.active=e.first(),s=this.active.children("a").addClass("ui-state-focus"),this.options.role&&this.element.attr("aria-activedescendant",s.attr("id")),this.active.parent().closest(".ui-menu-item").children("a:first").addClass("ui-state-active"),t&&"keydown"===t.type?this._close():this.timer=this._delay(function(){this._close()},this.delay),i=e.children(".ui-menu"),i.length&&t&&/^mouse/.test(t.type)&&this._startOpening(i),this.activeMenu=e.parent(),this._trigger("focus",t,{item:e})},_scrollIntoView:function(e){var i,s,n,a,o,r;this._hasScroll()&&(i=parseFloat(t.css(this.activeMenu[0],"borderTopWidth"))||0,s=parseFloat(t.css(this.activeMenu[0],"paddingTop"))||0,n=e.offset().top-this.activeMenu.offset().top-i-s,a=this.activeMenu.scrollTop(),o=this.activeMenu.height(),r=e.height(),0>n?this.activeMenu.scrollTop(a+n):n+r>o&&this.activeMenu.scrollTop(a+n-o+r))},blur:function(t,e){e||clearTimeout(this.timer),this.active&&(this.active.children("a").removeClass("ui-state-focus"),this.active=null,this._trigger("blur",t,{item:this.active}))},_startOpening:function(t){clearTimeout(this.timer),"true"===t.attr("aria-hidden")&&(this.timer=this._delay(function(){this._close(),this._open(t)},this.delay))},_open:function(e){var i=t.extend({of:this.active},this.options.position);clearTimeout(this.timer),this.element.find(".ui-menu").not(e.parents(".ui-menu")).hide().attr("aria-hidden","true"),e.show().removeAttr("aria-hidden").attr("aria-expanded","true").position(i)},collapseAll:function(e,i){clearTimeout(this.timer),this.timer=this._delay(function(){var s=i?this.element:t(e&&e.target).closest(this.element.find(".ui-menu"));s.length||(s=this.element),this._close(s),this.blur(e),this.activeMenu=s},this.delay)},_close:function(t){t||(t=this.active?this.active.parent():this.element),t.find(".ui-menu").hide().attr("aria-hidden","true").attr("aria-expanded","false").end().find("a.ui-state-active").removeClass("ui-state-active")},collapse:function(t){var e=this.active&&this.active.parent().closest(".ui-menu-item",this.element);e&&e.length&&(this._close(),this.focus(t,e))},expand:function(t){var e=this.active&&this.active.children(".ui-menu ").children(".ui-menu-item").first();e&&e.length&&(this._open(e.parent()),this._delay(function(){this.focus(t,e)}))},next:function(t){this._move("next","first",t)},previous:function(t){this._move("prev","last",t)},isFirstItem:function(){return this.active&&!this.active.prevAll(".ui-menu-item").length},isLastItem:function(){return this.active&&!this.active.nextAll(".ui-menu-item").length},_move:function(t,e,i){var s;this.active&&(s="first"===t||"last"===t?this.active["first"===t?"prevAll":"nextAll"](".ui-menu-item").eq(-1):this.active[t+"All"](".ui-menu-item").eq(0)),s&&s.length&&this.active||(s=this.activeMenu.children(".ui-menu-item")[e]()),this.focus(i,s)},nextPage:function(e){var i,s,n;return this.active?(this.isLastItem()||(this._hasScroll()?(s=this.active.offset().top,n=this.element.height(),this.active.nextAll(".ui-menu-item").each(function(){return i=t(this),0>i.offset().top-s-n}),this.focus(e,i)):this.focus(e,this.activeMenu.children(".ui-menu-item")[this.active?"last":"first"]())),undefined):(this.next(e),undefined)},previousPage:function(e){var i,s,n;return this.active?(this.isFirstItem()||(this._hasScroll()?(s=this.active.offset().top,n=this.element.height(),this.active.prevAll(".ui-menu-item").each(function(){return i=t(this),i.offset().top-s+n>0}),this.focus(e,i)):this.focus(e,this.activeMenu.children(".ui-menu-item").first())),undefined):(this.next(e),undefined)},_hasScroll:function(){return this.element.outerHeight()<this.element.prop("scrollHeight")},select:function(e){this.active=this.active||t(e.target).closest(".ui-menu-item");var i={item:this.active};this.active.has(".ui-menu").length||this.collapseAll(e,!0),this._trigger("select",e,i)}})})(jQuery);!function(t){"use strict";var e=t.HTMLCanvasElement&&t.HTMLCanvasElement.prototype,n=t.Blob&&function(){try{return Boolean(new Blob)}catch(t){return!1}}(),o=n&&t.Uint8Array&&function(){try{return 100===new Blob([new Uint8Array(100)]).size}catch(t){return!1}}(),r=t.BlobBuilder||t.WebKitBlobBuilder||t.MozBlobBuilder||t.MSBlobBuilder,i=(n||r)&&t.atob&&t.ArrayBuffer&&t.Uint8Array&&function(t){var e,i,a,l,u,B;for(e=t.split(",")[0].indexOf("base64")>=0?atob(t.split(",")[1]):decodeURIComponent(t.split(",")[1]),i=new ArrayBuffer(e.length),a=new Uint8Array(i),l=0;l<e.length;l+=1)a[l]=e.charCodeAt(l);return u=t.split(",")[0].split(":")[1].split(";")[0],n?new Blob([o?a:i],{type:u}):(B=new r,B.append(i),B.getBlob(u))};t.HTMLCanvasElement&&!e.toBlob&&(e.mozGetAsFile?e.toBlob=function(t,n,o){o&&e.toDataURL&&i?t(i(this.toDataURL(n,o))):t(this.mozGetAsFile("blob",n))}:e.toDataURL&&i&&(e.toBlob=function(t,e,n){t(i(this.toDataURL(e,n)))})),"function"==typeof define&&define.amd?define(function(){return i}):t.dataURLtoBlob=i}(this);// ┌────────────────────────────────────────────────────────────────────┐ \\
// │ Raphaël 2.1.2 - JavaScript Vector Library                          │ \\
// ├────────────────────────────────────────────────────────────────────┤ \\
// │ Copyright © 2008-2012 Dmitry Baranovskiy (http://raphaeljs.com)    │ \\
// │ Copyright © 2008-2012 Sencha Labs (http://sencha.com)              │ \\
// ├────────────────────────────────────────────────────────────────────┤ \\
// │ Licensed under the MIT (http://raphaeljs.com/license.html) license.│ \\
// └────────────────────────────────────────────────────────────────────┘ \\
!function(a){var b,c,d="0.4.2",e="hasOwnProperty",f=/[\.\/]/,g="*",h=function(){},i=function(a,b){return a-b},j={n:{}},k=function(a,d){a=String(a);var e,f=c,g=Array.prototype.slice.call(arguments,2),h=k.listeners(a),j=0,l=[],m={},n=[],o=b;b=a,c=0;for(var p=0,q=h.length;q>p;p++)"zIndex"in h[p]&&(l.push(h[p].zIndex),h[p].zIndex<0&&(m[h[p].zIndex]=h[p]));for(l.sort(i);l[j]<0;)if(e=m[l[j++]],n.push(e.apply(d,g)),c)return c=f,n;for(p=0;q>p;p++)if(e=h[p],"zIndex"in e)if(e.zIndex==l[j]){if(n.push(e.apply(d,g)),c)break;do if(j++,e=m[l[j]],e&&n.push(e.apply(d,g)),c)break;while(e)}else m[e.zIndex]=e;else if(n.push(e.apply(d,g)),c)break;return c=f,b=o,n.length?n:null};k._events=j,k.listeners=function(a){var b,c,d,e,h,i,k,l,m=a.split(f),n=j,o=[n],p=[];for(e=0,h=m.length;h>e;e++){for(l=[],i=0,k=o.length;k>i;i++)for(n=o[i].n,c=[n[m[e]],n[g]],d=2;d--;)b=c[d],b&&(l.push(b),p=p.concat(b.f||[]));o=l}return p},k.on=function(a,b){if(a=String(a),"function"!=typeof b)return function(){};for(var c=a.split(f),d=j,e=0,g=c.length;g>e;e++)d=d.n,d=d.hasOwnProperty(c[e])&&d[c[e]]||(d[c[e]]={n:{}});for(d.f=d.f||[],e=0,g=d.f.length;g>e;e++)if(d.f[e]==b)return h;return d.f.push(b),function(a){+a==+a&&(b.zIndex=+a)}},k.f=function(a){var b=[].slice.call(arguments,1);return function(){k.apply(null,[a,null].concat(b).concat([].slice.call(arguments,0)))}},k.stop=function(){c=1},k.nt=function(a){return a?new RegExp("(?:\\.|\\/|^)"+a+"(?:\\.|\\/|$)").test(b):b},k.nts=function(){return b.split(f)},k.off=k.unbind=function(a,b){if(!a)return k._events=j={n:{}},void 0;var c,d,h,i,l,m,n,o=a.split(f),p=[j];for(i=0,l=o.length;l>i;i++)for(m=0;m<p.length;m+=h.length-2){if(h=[m,1],c=p[m].n,o[i]!=g)c[o[i]]&&h.push(c[o[i]]);else for(d in c)c[e](d)&&h.push(c[d]);p.splice.apply(p,h)}for(i=0,l=p.length;l>i;i++)for(c=p[i];c.n;){if(b){if(c.f){for(m=0,n=c.f.length;n>m;m++)if(c.f[m]==b){c.f.splice(m,1);break}!c.f.length&&delete c.f}for(d in c.n)if(c.n[e](d)&&c.n[d].f){var q=c.n[d].f;for(m=0,n=q.length;n>m;m++)if(q[m]==b){q.splice(m,1);break}!q.length&&delete c.n[d].f}}else{delete c.f;for(d in c.n)c.n[e](d)&&c.n[d].f&&delete c.n[d].f}c=c.n}},k.once=function(a,b){var c=function(){return k.unbind(a,c),b.apply(this,arguments)};return k.on(a,c)},k.version=d,k.toString=function(){return"You are running Eve "+d},"undefined"!=typeof module&&module.exports?module.exports=k:"undefined"!=typeof define?define("eve",[],function(){return k}):a.eve=k}(this),function(a,b){"function"==typeof define&&define.amd?define(["eve"],function(c){return b(a,c)}):b(a,a.eve)}(this,function(a,b){function c(a){if(c.is(a,"function"))return u?a():b.on("raphael.DOMload",a);if(c.is(a,V))return c._engine.create[D](c,a.splice(0,3+c.is(a[0],T))).add(a);var d=Array.prototype.slice.call(arguments,0);if(c.is(d[d.length-1],"function")){var e=d.pop();return u?e.call(c._engine.create[D](c,d)):b.on("raphael.DOMload",function(){e.call(c._engine.create[D](c,d))})}return c._engine.create[D](c,arguments)}function d(a){if("function"==typeof a||Object(a)!==a)return a;var b=new a.constructor;for(var c in a)a[z](c)&&(b[c]=d(a[c]));return b}function e(a,b){for(var c=0,d=a.length;d>c;c++)if(a[c]===b)return a.push(a.splice(c,1)[0])}function f(a,b,c){function d(){var f=Array.prototype.slice.call(arguments,0),g=f.join("␀"),h=d.cache=d.cache||{},i=d.count=d.count||[];return h[z](g)?(e(i,g),c?c(h[g]):h[g]):(i.length>=1e3&&delete h[i.shift()],i.push(g),h[g]=a[D](b,f),c?c(h[g]):h[g])}return d}function g(){return this.hex}function h(a,b){for(var c=[],d=0,e=a.length;e-2*!b>d;d+=2){var f=[{x:+a[d-2],y:+a[d-1]},{x:+a[d],y:+a[d+1]},{x:+a[d+2],y:+a[d+3]},{x:+a[d+4],y:+a[d+5]}];b?d?e-4==d?f[3]={x:+a[0],y:+a[1]}:e-2==d&&(f[2]={x:+a[0],y:+a[1]},f[3]={x:+a[2],y:+a[3]}):f[0]={x:+a[e-2],y:+a[e-1]}:e-4==d?f[3]=f[2]:d||(f[0]={x:+a[d],y:+a[d+1]}),c.push(["C",(-f[0].x+6*f[1].x+f[2].x)/6,(-f[0].y+6*f[1].y+f[2].y)/6,(f[1].x+6*f[2].x-f[3].x)/6,(f[1].y+6*f[2].y-f[3].y)/6,f[2].x,f[2].y])}return c}function i(a,b,c,d,e){var f=-3*b+9*c-9*d+3*e,g=a*f+6*b-12*c+6*d;return a*g-3*b+3*c}function j(a,b,c,d,e,f,g,h,j){null==j&&(j=1),j=j>1?1:0>j?0:j;for(var k=j/2,l=12,m=[-.1252,.1252,-.3678,.3678,-.5873,.5873,-.7699,.7699,-.9041,.9041,-.9816,.9816],n=[.2491,.2491,.2335,.2335,.2032,.2032,.1601,.1601,.1069,.1069,.0472,.0472],o=0,p=0;l>p;p++){var q=k*m[p]+k,r=i(q,a,c,e,g),s=i(q,b,d,f,h),t=r*r+s*s;o+=n[p]*N.sqrt(t)}return k*o}function k(a,b,c,d,e,f,g,h,i){if(!(0>i||j(a,b,c,d,e,f,g,h)<i)){var k,l=1,m=l/2,n=l-m,o=.01;for(k=j(a,b,c,d,e,f,g,h,n);Q(k-i)>o;)m/=2,n+=(i>k?1:-1)*m,k=j(a,b,c,d,e,f,g,h,n);return n}}function l(a,b,c,d,e,f,g,h){if(!(O(a,c)<P(e,g)||P(a,c)>O(e,g)||O(b,d)<P(f,h)||P(b,d)>O(f,h))){var i=(a*d-b*c)*(e-g)-(a-c)*(e*h-f*g),j=(a*d-b*c)*(f-h)-(b-d)*(e*h-f*g),k=(a-c)*(f-h)-(b-d)*(e-g);if(k){var l=i/k,m=j/k,n=+l.toFixed(2),o=+m.toFixed(2);if(!(n<+P(a,c).toFixed(2)||n>+O(a,c).toFixed(2)||n<+P(e,g).toFixed(2)||n>+O(e,g).toFixed(2)||o<+P(b,d).toFixed(2)||o>+O(b,d).toFixed(2)||o<+P(f,h).toFixed(2)||o>+O(f,h).toFixed(2)))return{x:l,y:m}}}}function m(a,b,d){var e=c.bezierBBox(a),f=c.bezierBBox(b);if(!c.isBBoxIntersect(e,f))return d?0:[];for(var g=j.apply(0,a),h=j.apply(0,b),i=O(~~(g/5),1),k=O(~~(h/5),1),m=[],n=[],o={},p=d?0:[],q=0;i+1>q;q++){var r=c.findDotsAtSegment.apply(c,a.concat(q/i));m.push({x:r.x,y:r.y,t:q/i})}for(q=0;k+1>q;q++)r=c.findDotsAtSegment.apply(c,b.concat(q/k)),n.push({x:r.x,y:r.y,t:q/k});for(q=0;i>q;q++)for(var s=0;k>s;s++){var t=m[q],u=m[q+1],v=n[s],w=n[s+1],x=Q(u.x-t.x)<.001?"y":"x",y=Q(w.x-v.x)<.001?"y":"x",z=l(t.x,t.y,u.x,u.y,v.x,v.y,w.x,w.y);if(z){if(o[z.x.toFixed(4)]==z.y.toFixed(4))continue;o[z.x.toFixed(4)]=z.y.toFixed(4);var A=t.t+Q((z[x]-t[x])/(u[x]-t[x]))*(u.t-t.t),B=v.t+Q((z[y]-v[y])/(w[y]-v[y]))*(w.t-v.t);A>=0&&1.001>=A&&B>=0&&1.001>=B&&(d?p++:p.push({x:z.x,y:z.y,t1:P(A,1),t2:P(B,1)}))}}return p}function n(a,b,d){a=c._path2curve(a),b=c._path2curve(b);for(var e,f,g,h,i,j,k,l,n,o,p=d?0:[],q=0,r=a.length;r>q;q++){var s=a[q];if("M"==s[0])e=i=s[1],f=j=s[2];else{"C"==s[0]?(n=[e,f].concat(s.slice(1)),e=n[6],f=n[7]):(n=[e,f,e,f,i,j,i,j],e=i,f=j);for(var t=0,u=b.length;u>t;t++){var v=b[t];if("M"==v[0])g=k=v[1],h=l=v[2];else{"C"==v[0]?(o=[g,h].concat(v.slice(1)),g=o[6],h=o[7]):(o=[g,h,g,h,k,l,k,l],g=k,h=l);var w=m(n,o,d);if(d)p+=w;else{for(var x=0,y=w.length;y>x;x++)w[x].segment1=q,w[x].segment2=t,w[x].bez1=n,w[x].bez2=o;p=p.concat(w)}}}}}return p}function o(a,b,c,d,e,f){null!=a?(this.a=+a,this.b=+b,this.c=+c,this.d=+d,this.e=+e,this.f=+f):(this.a=1,this.b=0,this.c=0,this.d=1,this.e=0,this.f=0)}function p(){return this.x+H+this.y+H+this.width+" × "+this.height}function q(a,b,c,d,e,f){function g(a){return((l*a+k)*a+j)*a}function h(a,b){var c=i(a,b);return((o*c+n)*c+m)*c}function i(a,b){var c,d,e,f,h,i;for(e=a,i=0;8>i;i++){if(f=g(e)-a,Q(f)<b)return e;if(h=(3*l*e+2*k)*e+j,Q(h)<1e-6)break;e-=f/h}if(c=0,d=1,e=a,c>e)return c;if(e>d)return d;for(;d>c;){if(f=g(e),Q(f-a)<b)return e;a>f?c=e:d=e,e=(d-c)/2+c}return e}var j=3*b,k=3*(d-b)-j,l=1-j-k,m=3*c,n=3*(e-c)-m,o=1-m-n;return h(a,1/(200*f))}function r(a,b){var c=[],d={};if(this.ms=b,this.times=1,a){for(var e in a)a[z](e)&&(d[_(e)]=a[e],c.push(_(e)));c.sort(lb)}this.anim=d,this.top=c[c.length-1],this.percents=c}function s(a,d,e,f,g,h){e=_(e);var i,j,k,l,m,n,p=a.ms,r={},s={},t={};if(f)for(v=0,x=ic.length;x>v;v++){var u=ic[v];if(u.el.id==d.id&&u.anim==a){u.percent!=e?(ic.splice(v,1),k=1):j=u,d.attr(u.totalOrigin);break}}else f=+s;for(var v=0,x=a.percents.length;x>v;v++){if(a.percents[v]==e||a.percents[v]>f*a.top){e=a.percents[v],m=a.percents[v-1]||0,p=p/a.top*(e-m),l=a.percents[v+1],i=a.anim[e];break}f&&d.attr(a.anim[a.percents[v]])}if(i){if(j)j.initstatus=f,j.start=new Date-j.ms*f;else{for(var y in i)if(i[z](y)&&(db[z](y)||d.paper.customAttributes[z](y)))switch(r[y]=d.attr(y),null==r[y]&&(r[y]=cb[y]),s[y]=i[y],db[y]){case T:t[y]=(s[y]-r[y])/p;break;case"colour":r[y]=c.getRGB(r[y]);var A=c.getRGB(s[y]);t[y]={r:(A.r-r[y].r)/p,g:(A.g-r[y].g)/p,b:(A.b-r[y].b)/p};break;case"path":var B=Kb(r[y],s[y]),C=B[1];for(r[y]=B[0],t[y]=[],v=0,x=r[y].length;x>v;v++){t[y][v]=[0];for(var D=1,F=r[y][v].length;F>D;D++)t[y][v][D]=(C[v][D]-r[y][v][D])/p}break;case"transform":var G=d._,H=Pb(G[y],s[y]);if(H)for(r[y]=H.from,s[y]=H.to,t[y]=[],t[y].real=!0,v=0,x=r[y].length;x>v;v++)for(t[y][v]=[r[y][v][0]],D=1,F=r[y][v].length;F>D;D++)t[y][v][D]=(s[y][v][D]-r[y][v][D])/p;else{var K=d.matrix||new o,L={_:{transform:G.transform},getBBox:function(){return d.getBBox(1)}};r[y]=[K.a,K.b,K.c,K.d,K.e,K.f],Nb(L,s[y]),s[y]=L._.transform,t[y]=[(L.matrix.a-K.a)/p,(L.matrix.b-K.b)/p,(L.matrix.c-K.c)/p,(L.matrix.d-K.d)/p,(L.matrix.e-K.e)/p,(L.matrix.f-K.f)/p]}break;case"csv":var M=I(i[y])[J](w),N=I(r[y])[J](w);if("clip-rect"==y)for(r[y]=N,t[y]=[],v=N.length;v--;)t[y][v]=(M[v]-r[y][v])/p;s[y]=M;break;default:for(M=[][E](i[y]),N=[][E](r[y]),t[y]=[],v=d.paper.customAttributes[y].length;v--;)t[y][v]=((M[v]||0)-(N[v]||0))/p}var O=i.easing,P=c.easing_formulas[O];if(!P)if(P=I(O).match(Z),P&&5==P.length){var Q=P;P=function(a){return q(a,+Q[1],+Q[2],+Q[3],+Q[4],p)}}else P=nb;if(n=i.start||a.start||+new Date,u={anim:a,percent:e,timestamp:n,start:n+(a.del||0),status:0,initstatus:f||0,stop:!1,ms:p,easing:P,from:r,diff:t,to:s,el:d,callback:i.callback,prev:m,next:l,repeat:h||a.times,origin:d.attr(),totalOrigin:g},ic.push(u),f&&!j&&!k&&(u.stop=!0,u.start=new Date-p*f,1==ic.length))return kc();k&&(u.start=new Date-u.ms*f),1==ic.length&&jc(kc)}b("raphael.anim.start."+d.id,d,a)}}function t(a){for(var b=0;b<ic.length;b++)ic[b].el.paper==a&&ic.splice(b--,1)}c.version="2.1.2",c.eve=b;var u,v,w=/[, ]+/,x={circle:1,rect:1,path:1,ellipse:1,text:1,image:1},y=/\{(\d+)\}/g,z="hasOwnProperty",A={doc:document,win:a},B={was:Object.prototype[z].call(A.win,"Raphael"),is:A.win.Raphael},C=function(){this.ca=this.customAttributes={}},D="apply",E="concat",F="ontouchstart"in A.win||A.win.DocumentTouch&&A.doc instanceof DocumentTouch,G="",H=" ",I=String,J="split",K="click dblclick mousedown mousemove mouseout mouseover mouseup touchstart touchmove touchend touchcancel"[J](H),L={mousedown:"touchstart",mousemove:"touchmove",mouseup:"touchend"},M=I.prototype.toLowerCase,N=Math,O=N.max,P=N.min,Q=N.abs,R=N.pow,S=N.PI,T="number",U="string",V="array",W=Object.prototype.toString,X=(c._ISURL=/^url\(['"]?([^\)]+?)['"]?\)$/i,/^\s*((#[a-f\d]{6})|(#[a-f\d]{3})|rgba?\(\s*([\d\.]+%?\s*,\s*[\d\.]+%?\s*,\s*[\d\.]+%?(?:\s*,\s*[\d\.]+%?)?)\s*\)|hsba?\(\s*([\d\.]+(?:deg|\xb0|%)?\s*,\s*[\d\.]+%?\s*,\s*[\d\.]+(?:%?\s*,\s*[\d\.]+)?)%?\s*\)|hsla?\(\s*([\d\.]+(?:deg|\xb0|%)?\s*,\s*[\d\.]+%?\s*,\s*[\d\.]+(?:%?\s*,\s*[\d\.]+)?)%?\s*\))\s*$/i),Y={NaN:1,Infinity:1,"-Infinity":1},Z=/^(?:cubic-)?bezier\(([^,]+),([^,]+),([^,]+),([^\)]+)\)/,$=N.round,_=parseFloat,ab=parseInt,bb=I.prototype.toUpperCase,cb=c._availableAttrs={"arrow-end":"none","arrow-start":"none",blur:0,"clip-rect":"0 0 1e9 1e9",cursor:"default",cx:0,cy:0,fill:"#fff","fill-opacity":1,font:'10px "Arial"',"font-family":'"Arial"',"font-size":"10","font-style":"normal","font-weight":400,gradient:0,height:0,href:"http://raphaeljs.com/","letter-spacing":0,opacity:1,path:"M0,0",r:0,rx:0,ry:0,src:"",stroke:"#000","stroke-dasharray":"","stroke-linecap":"butt","stroke-linejoin":"butt","stroke-miterlimit":0,"stroke-opacity":1,"stroke-width":1,target:"_blank","text-anchor":"middle",title:"Raphael",transform:"",width:0,x:0,y:0},db=c._availableAnimAttrs={blur:T,"clip-rect":"csv",cx:T,cy:T,fill:"colour","fill-opacity":T,"font-size":T,height:T,opacity:T,path:"path",r:T,rx:T,ry:T,stroke:"colour","stroke-opacity":T,"stroke-width":T,transform:"transform",width:T,x:T,y:T},eb=/[\x09\x0a\x0b\x0c\x0d\x20\xa0\u1680\u180e\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u202f\u205f\u3000\u2028\u2029]*,[\x09\x0a\x0b\x0c\x0d\x20\xa0\u1680\u180e\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u202f\u205f\u3000\u2028\u2029]*/,fb={hs:1,rg:1},gb=/,?([achlmqrstvxz]),?/gi,hb=/([achlmrqstvz])[\x09\x0a\x0b\x0c\x0d\x20\xa0\u1680\u180e\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u202f\u205f\u3000\u2028\u2029,]*((-?\d*\.?\d*(?:e[\-+]?\d+)?[\x09\x0a\x0b\x0c\x0d\x20\xa0\u1680\u180e\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u202f\u205f\u3000\u2028\u2029]*,?[\x09\x0a\x0b\x0c\x0d\x20\xa0\u1680\u180e\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u202f\u205f\u3000\u2028\u2029]*)+)/gi,ib=/([rstm])[\x09\x0a\x0b\x0c\x0d\x20\xa0\u1680\u180e\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u202f\u205f\u3000\u2028\u2029,]*((-?\d*\.?\d*(?:e[\-+]?\d+)?[\x09\x0a\x0b\x0c\x0d\x20\xa0\u1680\u180e\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u202f\u205f\u3000\u2028\u2029]*,?[\x09\x0a\x0b\x0c\x0d\x20\xa0\u1680\u180e\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u202f\u205f\u3000\u2028\u2029]*)+)/gi,jb=/(-?\d*\.?\d*(?:e[\-+]?\d+)?)[\x09\x0a\x0b\x0c\x0d\x20\xa0\u1680\u180e\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u202f\u205f\u3000\u2028\u2029]*,?[\x09\x0a\x0b\x0c\x0d\x20\xa0\u1680\u180e\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u202f\u205f\u3000\u2028\u2029]*/gi,kb=(c._radial_gradient=/^r(?:\(([^,]+?)[\x09\x0a\x0b\x0c\x0d\x20\xa0\u1680\u180e\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u202f\u205f\u3000\u2028\u2029]*,[\x09\x0a\x0b\x0c\x0d\x20\xa0\u1680\u180e\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u202f\u205f\u3000\u2028\u2029]*([^\)]+?)\))?/,{}),lb=function(a,b){return _(a)-_(b)},mb=function(){},nb=function(a){return a},ob=c._rectPath=function(a,b,c,d,e){return e?[["M",a+e,b],["l",c-2*e,0],["a",e,e,0,0,1,e,e],["l",0,d-2*e],["a",e,e,0,0,1,-e,e],["l",2*e-c,0],["a",e,e,0,0,1,-e,-e],["l",0,2*e-d],["a",e,e,0,0,1,e,-e],["z"]]:[["M",a,b],["l",c,0],["l",0,d],["l",-c,0],["z"]]},pb=function(a,b,c,d){return null==d&&(d=c),[["M",a,b],["m",0,-d],["a",c,d,0,1,1,0,2*d],["a",c,d,0,1,1,0,-2*d],["z"]]},qb=c._getPath={path:function(a){return a.attr("path")},circle:function(a){var b=a.attrs;return pb(b.cx,b.cy,b.r)},ellipse:function(a){var b=a.attrs;return pb(b.cx,b.cy,b.rx,b.ry)},rect:function(a){var b=a.attrs;return ob(b.x,b.y,b.width,b.height,b.r)},image:function(a){var b=a.attrs;return ob(b.x,b.y,b.width,b.height)},text:function(a){var b=a._getBBox();return ob(b.x,b.y,b.width,b.height)},set:function(a){var b=a._getBBox();return ob(b.x,b.y,b.width,b.height)}},rb=c.mapPath=function(a,b){if(!b)return a;var c,d,e,f,g,h,i;for(a=Kb(a),e=0,g=a.length;g>e;e++)for(i=a[e],f=1,h=i.length;h>f;f+=2)c=b.x(i[f],i[f+1]),d=b.y(i[f],i[f+1]),i[f]=c,i[f+1]=d;return a};if(c._g=A,c.type=A.win.SVGAngle||A.doc.implementation.hasFeature("http://www.w3.org/TR/SVG11/feature#BasicStructure","1.1")?"SVG":"VML","VML"==c.type){var sb,tb=A.doc.createElement("div");if(tb.innerHTML='<v:shape adj="1"/>',sb=tb.firstChild,sb.style.behavior="url(#default#VML)",!sb||"object"!=typeof sb.adj)return c.type=G;tb=null}c.svg=!(c.vml="VML"==c.type),c._Paper=C,c.fn=v=C.prototype=c.prototype,c._id=0,c._oid=0,c.is=function(a,b){return b=M.call(b),"finite"==b?!Y[z](+a):"array"==b?a instanceof Array:"null"==b&&null===a||b==typeof a&&null!==a||"object"==b&&a===Object(a)||"array"==b&&Array.isArray&&Array.isArray(a)||W.call(a).slice(8,-1).toLowerCase()==b},c.angle=function(a,b,d,e,f,g){if(null==f){var h=a-d,i=b-e;return h||i?(180+180*N.atan2(-i,-h)/S+360)%360:0}return c.angle(a,b,f,g)-c.angle(d,e,f,g)},c.rad=function(a){return a%360*S/180},c.deg=function(a){return 180*a/S%360},c.snapTo=function(a,b,d){if(d=c.is(d,"finite")?d:10,c.is(a,V)){for(var e=a.length;e--;)if(Q(a[e]-b)<=d)return a[e]}else{a=+a;var f=b%a;if(d>f)return b-f;if(f>a-d)return b-f+a}return b},c.createUUID=function(a,b){return function(){return"xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx".replace(a,b).toUpperCase()}}(/[xy]/g,function(a){var b=0|16*N.random(),c="x"==a?b:8|3&b;return c.toString(16)}),c.setWindow=function(a){b("raphael.setWindow",c,A.win,a),A.win=a,A.doc=A.win.document,c._engine.initWin&&c._engine.initWin(A.win)};var ub=function(a){if(c.vml){var b,d=/^\s+|\s+$/g;try{var e=new ActiveXObject("htmlfile");e.write("<body>"),e.close(),b=e.body}catch(g){b=createPopup().document.body}var h=b.createTextRange();ub=f(function(a){try{b.style.color=I(a).replace(d,G);var c=h.queryCommandValue("ForeColor");return c=(255&c)<<16|65280&c|(16711680&c)>>>16,"#"+("000000"+c.toString(16)).slice(-6)}catch(e){return"none"}})}else{var i=A.doc.createElement("i");i.title="Raphaël Colour Picker",i.style.display="none",A.doc.body.appendChild(i),ub=f(function(a){return i.style.color=a,A.doc.defaultView.getComputedStyle(i,G).getPropertyValue("color")})}return ub(a)},vb=function(){return"hsb("+[this.h,this.s,this.b]+")"},wb=function(){return"hsl("+[this.h,this.s,this.l]+")"},xb=function(){return this.hex},yb=function(a,b,d){if(null==b&&c.is(a,"object")&&"r"in a&&"g"in a&&"b"in a&&(d=a.b,b=a.g,a=a.r),null==b&&c.is(a,U)){var e=c.getRGB(a);a=e.r,b=e.g,d=e.b}return(a>1||b>1||d>1)&&(a/=255,b/=255,d/=255),[a,b,d]},zb=function(a,b,d,e){a*=255,b*=255,d*=255;var f={r:a,g:b,b:d,hex:c.rgb(a,b,d),toString:xb};return c.is(e,"finite")&&(f.opacity=e),f};c.color=function(a){var b;return c.is(a,"object")&&"h"in a&&"s"in a&&"b"in a?(b=c.hsb2rgb(a),a.r=b.r,a.g=b.g,a.b=b.b,a.hex=b.hex):c.is(a,"object")&&"h"in a&&"s"in a&&"l"in a?(b=c.hsl2rgb(a),a.r=b.r,a.g=b.g,a.b=b.b,a.hex=b.hex):(c.is(a,"string")&&(a=c.getRGB(a)),c.is(a,"object")&&"r"in a&&"g"in a&&"b"in a?(b=c.rgb2hsl(a),a.h=b.h,a.s=b.s,a.l=b.l,b=c.rgb2hsb(a),a.v=b.b):(a={hex:"none"},a.r=a.g=a.b=a.h=a.s=a.v=a.l=-1)),a.toString=xb,a},c.hsb2rgb=function(a,b,c,d){this.is(a,"object")&&"h"in a&&"s"in a&&"b"in a&&(c=a.b,b=a.s,a=a.h,d=a.o),a*=360;var e,f,g,h,i;return a=a%360/60,i=c*b,h=i*(1-Q(a%2-1)),e=f=g=c-i,a=~~a,e+=[i,h,0,0,h,i][a],f+=[h,i,i,h,0,0][a],g+=[0,0,h,i,i,h][a],zb(e,f,g,d)},c.hsl2rgb=function(a,b,c,d){this.is(a,"object")&&"h"in a&&"s"in a&&"l"in a&&(c=a.l,b=a.s,a=a.h),(a>1||b>1||c>1)&&(a/=360,b/=100,c/=100),a*=360;var e,f,g,h,i;return a=a%360/60,i=2*b*(.5>c?c:1-c),h=i*(1-Q(a%2-1)),e=f=g=c-i/2,a=~~a,e+=[i,h,0,0,h,i][a],f+=[h,i,i,h,0,0][a],g+=[0,0,h,i,i,h][a],zb(e,f,g,d)},c.rgb2hsb=function(a,b,c){c=yb(a,b,c),a=c[0],b=c[1],c=c[2];var d,e,f,g;return f=O(a,b,c),g=f-P(a,b,c),d=0==g?null:f==a?(b-c)/g:f==b?(c-a)/g+2:(a-b)/g+4,d=60*((d+360)%6)/360,e=0==g?0:g/f,{h:d,s:e,b:f,toString:vb}},c.rgb2hsl=function(a,b,c){c=yb(a,b,c),a=c[0],b=c[1],c=c[2];var d,e,f,g,h,i;return g=O(a,b,c),h=P(a,b,c),i=g-h,d=0==i?null:g==a?(b-c)/i:g==b?(c-a)/i+2:(a-b)/i+4,d=60*((d+360)%6)/360,f=(g+h)/2,e=0==i?0:.5>f?i/(2*f):i/(2-2*f),{h:d,s:e,l:f,toString:wb}},c._path2string=function(){return this.join(",").replace(gb,"$1")},c._preload=function(a,b){var c=A.doc.createElement("img");c.style.cssText="position:absolute;left:-9999em;top:-9999em",c.onload=function(){b.call(this),this.onload=null,A.doc.body.removeChild(this)},c.onerror=function(){A.doc.body.removeChild(this)},A.doc.body.appendChild(c),c.src=a},c.getRGB=f(function(a){if(!a||(a=I(a)).indexOf("-")+1)return{r:-1,g:-1,b:-1,hex:"none",error:1,toString:g};if("none"==a)return{r:-1,g:-1,b:-1,hex:"none",toString:g};!(fb[z](a.toLowerCase().substring(0,2))||"#"==a.charAt())&&(a=ub(a));var b,d,e,f,h,i,j=a.match(X);return j?(j[2]&&(e=ab(j[2].substring(5),16),d=ab(j[2].substring(3,5),16),b=ab(j[2].substring(1,3),16)),j[3]&&(e=ab((h=j[3].charAt(3))+h,16),d=ab((h=j[3].charAt(2))+h,16),b=ab((h=j[3].charAt(1))+h,16)),j[4]&&(i=j[4][J](eb),b=_(i[0]),"%"==i[0].slice(-1)&&(b*=2.55),d=_(i[1]),"%"==i[1].slice(-1)&&(d*=2.55),e=_(i[2]),"%"==i[2].slice(-1)&&(e*=2.55),"rgba"==j[1].toLowerCase().slice(0,4)&&(f=_(i[3])),i[3]&&"%"==i[3].slice(-1)&&(f/=100)),j[5]?(i=j[5][J](eb),b=_(i[0]),"%"==i[0].slice(-1)&&(b*=2.55),d=_(i[1]),"%"==i[1].slice(-1)&&(d*=2.55),e=_(i[2]),"%"==i[2].slice(-1)&&(e*=2.55),("deg"==i[0].slice(-3)||"°"==i[0].slice(-1))&&(b/=360),"hsba"==j[1].toLowerCase().slice(0,4)&&(f=_(i[3])),i[3]&&"%"==i[3].slice(-1)&&(f/=100),c.hsb2rgb(b,d,e,f)):j[6]?(i=j[6][J](eb),b=_(i[0]),"%"==i[0].slice(-1)&&(b*=2.55),d=_(i[1]),"%"==i[1].slice(-1)&&(d*=2.55),e=_(i[2]),"%"==i[2].slice(-1)&&(e*=2.55),("deg"==i[0].slice(-3)||"°"==i[0].slice(-1))&&(b/=360),"hsla"==j[1].toLowerCase().slice(0,4)&&(f=_(i[3])),i[3]&&"%"==i[3].slice(-1)&&(f/=100),c.hsl2rgb(b,d,e,f)):(j={r:b,g:d,b:e,toString:g},j.hex="#"+(16777216|e|d<<8|b<<16).toString(16).slice(1),c.is(f,"finite")&&(j.opacity=f),j)):{r:-1,g:-1,b:-1,hex:"none",error:1,toString:g}},c),c.hsb=f(function(a,b,d){return c.hsb2rgb(a,b,d).hex}),c.hsl=f(function(a,b,d){return c.hsl2rgb(a,b,d).hex}),c.rgb=f(function(a,b,c){return"#"+(16777216|c|b<<8|a<<16).toString(16).slice(1)}),c.getColor=function(a){var b=this.getColor.start=this.getColor.start||{h:0,s:1,b:a||.75},c=this.hsb2rgb(b.h,b.s,b.b);return b.h+=.075,b.h>1&&(b.h=0,b.s-=.2,b.s<=0&&(this.getColor.start={h:0,s:1,b:b.b})),c.hex},c.getColor.reset=function(){delete this.start},c.parsePathString=function(a){if(!a)return null;var b=Ab(a);if(b.arr)return Cb(b.arr);var d={a:7,c:6,h:1,l:2,m:2,r:4,q:4,s:4,t:2,v:1,z:0},e=[];return c.is(a,V)&&c.is(a[0],V)&&(e=Cb(a)),e.length||I(a).replace(hb,function(a,b,c){var f=[],g=b.toLowerCase();if(c.replace(jb,function(a,b){b&&f.push(+b)}),"m"==g&&f.length>2&&(e.push([b][E](f.splice(0,2))),g="l",b="m"==b?"l":"L"),"r"==g)e.push([b][E](f));else for(;f.length>=d[g]&&(e.push([b][E](f.splice(0,d[g]))),d[g]););}),e.toString=c._path2string,b.arr=Cb(e),e},c.parseTransformString=f(function(a){if(!a)return null;var b=[];return c.is(a,V)&&c.is(a[0],V)&&(b=Cb(a)),b.length||I(a).replace(ib,function(a,c,d){var e=[];M.call(c),d.replace(jb,function(a,b){b&&e.push(+b)}),b.push([c][E](e))}),b.toString=c._path2string,b});var Ab=function(a){var b=Ab.ps=Ab.ps||{};return b[a]?b[a].sleep=100:b[a]={sleep:100},setTimeout(function(){for(var c in b)b[z](c)&&c!=a&&(b[c].sleep--,!b[c].sleep&&delete b[c])}),b[a]};c.findDotsAtSegment=function(a,b,c,d,e,f,g,h,i){var j=1-i,k=R(j,3),l=R(j,2),m=i*i,n=m*i,o=k*a+3*l*i*c+3*j*i*i*e+n*g,p=k*b+3*l*i*d+3*j*i*i*f+n*h,q=a+2*i*(c-a)+m*(e-2*c+a),r=b+2*i*(d-b)+m*(f-2*d+b),s=c+2*i*(e-c)+m*(g-2*e+c),t=d+2*i*(f-d)+m*(h-2*f+d),u=j*a+i*c,v=j*b+i*d,w=j*e+i*g,x=j*f+i*h,y=90-180*N.atan2(q-s,r-t)/S;return(q>s||t>r)&&(y+=180),{x:o,y:p,m:{x:q,y:r},n:{x:s,y:t},start:{x:u,y:v},end:{x:w,y:x},alpha:y}},c.bezierBBox=function(a,b,d,e,f,g,h,i){c.is(a,"array")||(a=[a,b,d,e,f,g,h,i]);var j=Jb.apply(null,a);return{x:j.min.x,y:j.min.y,x2:j.max.x,y2:j.max.y,width:j.max.x-j.min.x,height:j.max.y-j.min.y}},c.isPointInsideBBox=function(a,b,c){return b>=a.x&&b<=a.x2&&c>=a.y&&c<=a.y2},c.isBBoxIntersect=function(a,b){var d=c.isPointInsideBBox;return d(b,a.x,a.y)||d(b,a.x2,a.y)||d(b,a.x,a.y2)||d(b,a.x2,a.y2)||d(a,b.x,b.y)||d(a,b.x2,b.y)||d(a,b.x,b.y2)||d(a,b.x2,b.y2)||(a.x<b.x2&&a.x>b.x||b.x<a.x2&&b.x>a.x)&&(a.y<b.y2&&a.y>b.y||b.y<a.y2&&b.y>a.y)},c.pathIntersection=function(a,b){return n(a,b)},c.pathIntersectionNumber=function(a,b){return n(a,b,1)},c.isPointInsidePath=function(a,b,d){var e=c.pathBBox(a);return c.isPointInsideBBox(e,b,d)&&1==n(a,[["M",b,d],["H",e.x2+10]],1)%2},c._removedFactory=function(a){return function(){b("raphael.log",null,"Raphaël: you are calling to method “"+a+"” of removed object",a)}};var Bb=c.pathBBox=function(a){var b=Ab(a);if(b.bbox)return d(b.bbox);if(!a)return{x:0,y:0,width:0,height:0,x2:0,y2:0};a=Kb(a);for(var c,e=0,f=0,g=[],h=[],i=0,j=a.length;j>i;i++)if(c=a[i],"M"==c[0])e=c[1],f=c[2],g.push(e),h.push(f);else{var k=Jb(e,f,c[1],c[2],c[3],c[4],c[5],c[6]);g=g[E](k.min.x,k.max.x),h=h[E](k.min.y,k.max.y),e=c[5],f=c[6]}var l=P[D](0,g),m=P[D](0,h),n=O[D](0,g),o=O[D](0,h),p=n-l,q=o-m,r={x:l,y:m,x2:n,y2:o,width:p,height:q,cx:l+p/2,cy:m+q/2};return b.bbox=d(r),r},Cb=function(a){var b=d(a);return b.toString=c._path2string,b},Db=c._pathToRelative=function(a){var b=Ab(a);if(b.rel)return Cb(b.rel);c.is(a,V)&&c.is(a&&a[0],V)||(a=c.parsePathString(a));var d=[],e=0,f=0,g=0,h=0,i=0;"M"==a[0][0]&&(e=a[0][1],f=a[0][2],g=e,h=f,i++,d.push(["M",e,f]));for(var j=i,k=a.length;k>j;j++){var l=d[j]=[],m=a[j];if(m[0]!=M.call(m[0]))switch(l[0]=M.call(m[0]),l[0]){case"a":l[1]=m[1],l[2]=m[2],l[3]=m[3],l[4]=m[4],l[5]=m[5],l[6]=+(m[6]-e).toFixed(3),l[7]=+(m[7]-f).toFixed(3);break;case"v":l[1]=+(m[1]-f).toFixed(3);break;case"m":g=m[1],h=m[2];default:for(var n=1,o=m.length;o>n;n++)l[n]=+(m[n]-(n%2?e:f)).toFixed(3)}else{l=d[j]=[],"m"==m[0]&&(g=m[1]+e,h=m[2]+f);for(var p=0,q=m.length;q>p;p++)d[j][p]=m[p]}var r=d[j].length;switch(d[j][0]){case"z":e=g,f=h;break;case"h":e+=+d[j][r-1];break;case"v":f+=+d[j][r-1];break;default:e+=+d[j][r-2],f+=+d[j][r-1]}}return d.toString=c._path2string,b.rel=Cb(d),d},Eb=c._pathToAbsolute=function(a){var b=Ab(a);if(b.abs)return Cb(b.abs);if(c.is(a,V)&&c.is(a&&a[0],V)||(a=c.parsePathString(a)),!a||!a.length)return[["M",0,0]];var d=[],e=0,f=0,g=0,i=0,j=0;"M"==a[0][0]&&(e=+a[0][1],f=+a[0][2],g=e,i=f,j++,d[0]=["M",e,f]);for(var k,l,m=3==a.length&&"M"==a[0][0]&&"R"==a[1][0].toUpperCase()&&"Z"==a[2][0].toUpperCase(),n=j,o=a.length;o>n;n++){if(d.push(k=[]),l=a[n],l[0]!=bb.call(l[0]))switch(k[0]=bb.call(l[0]),k[0]){case"A":k[1]=l[1],k[2]=l[2],k[3]=l[3],k[4]=l[4],k[5]=l[5],k[6]=+(l[6]+e),k[7]=+(l[7]+f);break;case"V":k[1]=+l[1]+f;break;case"H":k[1]=+l[1]+e;break;case"R":for(var p=[e,f][E](l.slice(1)),q=2,r=p.length;r>q;q++)p[q]=+p[q]+e,p[++q]=+p[q]+f;d.pop(),d=d[E](h(p,m));break;case"M":g=+l[1]+e,i=+l[2]+f;default:for(q=1,r=l.length;r>q;q++)k[q]=+l[q]+(q%2?e:f)}else if("R"==l[0])p=[e,f][E](l.slice(1)),d.pop(),d=d[E](h(p,m)),k=["R"][E](l.slice(-2));else for(var s=0,t=l.length;t>s;s++)k[s]=l[s];switch(k[0]){case"Z":e=g,f=i;break;case"H":e=k[1];break;case"V":f=k[1];break;case"M":g=k[k.length-2],i=k[k.length-1];default:e=k[k.length-2],f=k[k.length-1]}}return d.toString=c._path2string,b.abs=Cb(d),d},Fb=function(a,b,c,d){return[a,b,c,d,c,d]},Gb=function(a,b,c,d,e,f){var g=1/3,h=2/3;return[g*a+h*c,g*b+h*d,g*e+h*c,g*f+h*d,e,f]},Hb=function(a,b,c,d,e,g,h,i,j,k){var l,m=120*S/180,n=S/180*(+e||0),o=[],p=f(function(a,b,c){var d=a*N.cos(c)-b*N.sin(c),e=a*N.sin(c)+b*N.cos(c);return{x:d,y:e}});if(k)y=k[0],z=k[1],w=k[2],x=k[3];else{l=p(a,b,-n),a=l.x,b=l.y,l=p(i,j,-n),i=l.x,j=l.y;var q=(N.cos(S/180*e),N.sin(S/180*e),(a-i)/2),r=(b-j)/2,s=q*q/(c*c)+r*r/(d*d);s>1&&(s=N.sqrt(s),c=s*c,d=s*d);var t=c*c,u=d*d,v=(g==h?-1:1)*N.sqrt(Q((t*u-t*r*r-u*q*q)/(t*r*r+u*q*q))),w=v*c*r/d+(a+i)/2,x=v*-d*q/c+(b+j)/2,y=N.asin(((b-x)/d).toFixed(9)),z=N.asin(((j-x)/d).toFixed(9));y=w>a?S-y:y,z=w>i?S-z:z,0>y&&(y=2*S+y),0>z&&(z=2*S+z),h&&y>z&&(y-=2*S),!h&&z>y&&(z-=2*S)}var A=z-y;if(Q(A)>m){var B=z,C=i,D=j;z=y+m*(h&&z>y?1:-1),i=w+c*N.cos(z),j=x+d*N.sin(z),o=Hb(i,j,c,d,e,0,h,C,D,[z,B,w,x])}A=z-y;var F=N.cos(y),G=N.sin(y),H=N.cos(z),I=N.sin(z),K=N.tan(A/4),L=4/3*c*K,M=4/3*d*K,O=[a,b],P=[a+L*G,b-M*F],R=[i+L*I,j-M*H],T=[i,j];if(P[0]=2*O[0]-P[0],P[1]=2*O[1]-P[1],k)return[P,R,T][E](o);o=[P,R,T][E](o).join()[J](",");for(var U=[],V=0,W=o.length;W>V;V++)U[V]=V%2?p(o[V-1],o[V],n).y:p(o[V],o[V+1],n).x;return U},Ib=function(a,b,c,d,e,f,g,h,i){var j=1-i;return{x:R(j,3)*a+3*R(j,2)*i*c+3*j*i*i*e+R(i,3)*g,y:R(j,3)*b+3*R(j,2)*i*d+3*j*i*i*f+R(i,3)*h}},Jb=f(function(a,b,c,d,e,f,g,h){var i,j=e-2*c+a-(g-2*e+c),k=2*(c-a)-2*(e-c),l=a-c,m=(-k+N.sqrt(k*k-4*j*l))/2/j,n=(-k-N.sqrt(k*k-4*j*l))/2/j,o=[b,h],p=[a,g];return Q(m)>"1e12"&&(m=.5),Q(n)>"1e12"&&(n=.5),m>0&&1>m&&(i=Ib(a,b,c,d,e,f,g,h,m),p.push(i.x),o.push(i.y)),n>0&&1>n&&(i=Ib(a,b,c,d,e,f,g,h,n),p.push(i.x),o.push(i.y)),j=f-2*d+b-(h-2*f+d),k=2*(d-b)-2*(f-d),l=b-d,m=(-k+N.sqrt(k*k-4*j*l))/2/j,n=(-k-N.sqrt(k*k-4*j*l))/2/j,Q(m)>"1e12"&&(m=.5),Q(n)>"1e12"&&(n=.5),m>0&&1>m&&(i=Ib(a,b,c,d,e,f,g,h,m),p.push(i.x),o.push(i.y)),n>0&&1>n&&(i=Ib(a,b,c,d,e,f,g,h,n),p.push(i.x),o.push(i.y)),{min:{x:P[D](0,p),y:P[D](0,o)},max:{x:O[D](0,p),y:O[D](0,o)}}}),Kb=c._path2curve=f(function(a,b){var c=!b&&Ab(a);if(!b&&c.curve)return Cb(c.curve);for(var d=Eb(a),e=b&&Eb(b),f={x:0,y:0,bx:0,by:0,X:0,Y:0,qx:null,qy:null},g={x:0,y:0,bx:0,by:0,X:0,Y:0,qx:null,qy:null},h=(function(a,b,c){var d,e;if(!a)return["C",b.x,b.y,b.x,b.y,b.x,b.y];switch(!(a[0]in{T:1,Q:1})&&(b.qx=b.qy=null),a[0]){case"M":b.X=a[1],b.Y=a[2];break;case"A":a=["C"][E](Hb[D](0,[b.x,b.y][E](a.slice(1))));break;case"S":"C"==c||"S"==c?(d=2*b.x-b.bx,e=2*b.y-b.by):(d=b.x,e=b.y),a=["C",d,e][E](a.slice(1));break;case"T":"Q"==c||"T"==c?(b.qx=2*b.x-b.qx,b.qy=2*b.y-b.qy):(b.qx=b.x,b.qy=b.y),a=["C"][E](Gb(b.x,b.y,b.qx,b.qy,a[1],a[2]));break;case"Q":b.qx=a[1],b.qy=a[2],a=["C"][E](Gb(b.x,b.y,a[1],a[2],a[3],a[4]));break;case"L":a=["C"][E](Fb(b.x,b.y,a[1],a[2]));break;case"H":a=["C"][E](Fb(b.x,b.y,a[1],b.y));break;case"V":a=["C"][E](Fb(b.x,b.y,b.x,a[1]));break;case"Z":a=["C"][E](Fb(b.x,b.y,b.X,b.Y))}return a}),i=function(a,b){if(a[b].length>7){a[b].shift();for(var c=a[b];c.length;)a.splice(b++,0,["C"][E](c.splice(0,6)));a.splice(b,1),l=O(d.length,e&&e.length||0)}},j=function(a,b,c,f,g){a&&b&&"M"==a[g][0]&&"M"!=b[g][0]&&(b.splice(g,0,["M",f.x,f.y]),c.bx=0,c.by=0,c.x=a[g][1],c.y=a[g][2],l=O(d.length,e&&e.length||0))},k=0,l=O(d.length,e&&e.length||0);l>k;k++){d[k]=h(d[k],f),i(d,k),e&&(e[k]=h(e[k],g)),e&&i(e,k),j(d,e,f,g,k),j(e,d,g,f,k);var m=d[k],n=e&&e[k],o=m.length,p=e&&n.length;f.x=m[o-2],f.y=m[o-1],f.bx=_(m[o-4])||f.x,f.by=_(m[o-3])||f.y,g.bx=e&&(_(n[p-4])||g.x),g.by=e&&(_(n[p-3])||g.y),g.x=e&&n[p-2],g.y=e&&n[p-1]}return e||(c.curve=Cb(d)),e?[d,e]:d},null,Cb),Lb=(c._parseDots=f(function(a){for(var b=[],d=0,e=a.length;e>d;d++){var f={},g=a[d].match(/^([^:]*):?([\d\.]*)/);if(f.color=c.getRGB(g[1]),f.color.error)return null;f.color=f.color.hex,g[2]&&(f.offset=g[2]+"%"),b.push(f)}for(d=1,e=b.length-1;e>d;d++)if(!b[d].offset){for(var h=_(b[d-1].offset||0),i=0,j=d+1;e>j;j++)if(b[j].offset){i=b[j].offset;break}i||(i=100,j=e),i=_(i);for(var k=(i-h)/(j-d+1);j>d;d++)h+=k,b[d].offset=h+"%"}return b}),c._tear=function(a,b){a==b.top&&(b.top=a.prev),a==b.bottom&&(b.bottom=a.next),a.next&&(a.next.prev=a.prev),a.prev&&(a.prev.next=a.next)}),Mb=(c._tofront=function(a,b){b.top!==a&&(Lb(a,b),a.next=null,a.prev=b.top,b.top.next=a,b.top=a)},c._toback=function(a,b){b.bottom!==a&&(Lb(a,b),a.next=b.bottom,a.prev=null,b.bottom.prev=a,b.bottom=a)},c._insertafter=function(a,b,c){Lb(a,c),b==c.top&&(c.top=a),b.next&&(b.next.prev=a),a.next=b.next,a.prev=b,b.next=a},c._insertbefore=function(a,b,c){Lb(a,c),b==c.bottom&&(c.bottom=a),b.prev&&(b.prev.next=a),a.prev=b.prev,b.prev=a,a.next=b},c.toMatrix=function(a,b){var c=Bb(a),d={_:{transform:G},getBBox:function(){return c}};return Nb(d,b),d.matrix}),Nb=(c.transformPath=function(a,b){return rb(a,Mb(a,b))},c._extractTransform=function(a,b){if(null==b)return a._.transform;b=I(b).replace(/\.{3}|\u2026/g,a._.transform||G);var d=c.parseTransformString(b),e=0,f=0,g=0,h=1,i=1,j=a._,k=new o;if(j.transform=d||[],d)for(var l=0,m=d.length;m>l;l++){var n,p,q,r,s,t=d[l],u=t.length,v=I(t[0]).toLowerCase(),w=t[0]!=v,x=w?k.invert():0;"t"==v&&3==u?w?(n=x.x(0,0),p=x.y(0,0),q=x.x(t[1],t[2]),r=x.y(t[1],t[2]),k.translate(q-n,r-p)):k.translate(t[1],t[2]):"r"==v?2==u?(s=s||a.getBBox(1),k.rotate(t[1],s.x+s.width/2,s.y+s.height/2),e+=t[1]):4==u&&(w?(q=x.x(t[2],t[3]),r=x.y(t[2],t[3]),k.rotate(t[1],q,r)):k.rotate(t[1],t[2],t[3]),e+=t[1]):"s"==v?2==u||3==u?(s=s||a.getBBox(1),k.scale(t[1],t[u-1],s.x+s.width/2,s.y+s.height/2),h*=t[1],i*=t[u-1]):5==u&&(w?(q=x.x(t[3],t[4]),r=x.y(t[3],t[4]),k.scale(t[1],t[2],q,r)):k.scale(t[1],t[2],t[3],t[4]),h*=t[1],i*=t[2]):"m"==v&&7==u&&k.add(t[1],t[2],t[3],t[4],t[5],t[6]),j.dirtyT=1,a.matrix=k}a.matrix=k,j.sx=h,j.sy=i,j.deg=e,j.dx=f=k.e,j.dy=g=k.f,1==h&&1==i&&!e&&j.bbox?(j.bbox.x+=+f,j.bbox.y+=+g):j.dirtyT=1}),Ob=function(a){var b=a[0];switch(b.toLowerCase()){case"t":return[b,0,0];case"m":return[b,1,0,0,1,0,0];case"r":return 4==a.length?[b,0,a[2],a[3]]:[b,0];case"s":return 5==a.length?[b,1,1,a[3],a[4]]:3==a.length?[b,1,1]:[b,1]}},Pb=c._equaliseTransform=function(a,b){b=I(b).replace(/\.{3}|\u2026/g,a),a=c.parseTransformString(a)||[],b=c.parseTransformString(b)||[];for(var d,e,f,g,h=O(a.length,b.length),i=[],j=[],k=0;h>k;k++){if(f=a[k]||Ob(b[k]),g=b[k]||Ob(f),f[0]!=g[0]||"r"==f[0].toLowerCase()&&(f[2]!=g[2]||f[3]!=g[3])||"s"==f[0].toLowerCase()&&(f[3]!=g[3]||f[4]!=g[4]))return;for(i[k]=[],j[k]=[],d=0,e=O(f.length,g.length);e>d;d++)d in f&&(i[k][d]=f[d]),d in g&&(j[k][d]=g[d])
}return{from:i,to:j}};c._getContainer=function(a,b,d,e){var f;return f=null!=e||c.is(a,"object")?a:A.doc.getElementById(a),null!=f?f.tagName?null==b?{container:f,width:f.style.pixelWidth||f.offsetWidth,height:f.style.pixelHeight||f.offsetHeight}:{container:f,width:b,height:d}:{container:1,x:a,y:b,width:d,height:e}:void 0},c.pathToRelative=Db,c._engine={},c.path2curve=Kb,c.matrix=function(a,b,c,d,e,f){return new o(a,b,c,d,e,f)},function(a){function b(a){return a[0]*a[0]+a[1]*a[1]}function d(a){var c=N.sqrt(b(a));a[0]&&(a[0]/=c),a[1]&&(a[1]/=c)}a.add=function(a,b,c,d,e,f){var g,h,i,j,k=[[],[],[]],l=[[this.a,this.c,this.e],[this.b,this.d,this.f],[0,0,1]],m=[[a,c,e],[b,d,f],[0,0,1]];for(a&&a instanceof o&&(m=[[a.a,a.c,a.e],[a.b,a.d,a.f],[0,0,1]]),g=0;3>g;g++)for(h=0;3>h;h++){for(j=0,i=0;3>i;i++)j+=l[g][i]*m[i][h];k[g][h]=j}this.a=k[0][0],this.b=k[1][0],this.c=k[0][1],this.d=k[1][1],this.e=k[0][2],this.f=k[1][2]},a.invert=function(){var a=this,b=a.a*a.d-a.b*a.c;return new o(a.d/b,-a.b/b,-a.c/b,a.a/b,(a.c*a.f-a.d*a.e)/b,(a.b*a.e-a.a*a.f)/b)},a.clone=function(){return new o(this.a,this.b,this.c,this.d,this.e,this.f)},a.translate=function(a,b){this.add(1,0,0,1,a,b)},a.scale=function(a,b,c,d){null==b&&(b=a),(c||d)&&this.add(1,0,0,1,c,d),this.add(a,0,0,b,0,0),(c||d)&&this.add(1,0,0,1,-c,-d)},a.rotate=function(a,b,d){a=c.rad(a),b=b||0,d=d||0;var e=+N.cos(a).toFixed(9),f=+N.sin(a).toFixed(9);this.add(e,f,-f,e,b,d),this.add(1,0,0,1,-b,-d)},a.x=function(a,b){return a*this.a+b*this.c+this.e},a.y=function(a,b){return a*this.b+b*this.d+this.f},a.get=function(a){return+this[I.fromCharCode(97+a)].toFixed(4)},a.toString=function(){return c.svg?"matrix("+[this.get(0),this.get(1),this.get(2),this.get(3),this.get(4),this.get(5)].join()+")":[this.get(0),this.get(2),this.get(1),this.get(3),0,0].join()},a.toFilter=function(){return"progid:DXImageTransform.Microsoft.Matrix(M11="+this.get(0)+", M12="+this.get(2)+", M21="+this.get(1)+", M22="+this.get(3)+", Dx="+this.get(4)+", Dy="+this.get(5)+", sizingmethod='auto expand')"},a.offset=function(){return[this.e.toFixed(4),this.f.toFixed(4)]},a.split=function(){var a={};a.dx=this.e,a.dy=this.f;var e=[[this.a,this.c],[this.b,this.d]];a.scalex=N.sqrt(b(e[0])),d(e[0]),a.shear=e[0][0]*e[1][0]+e[0][1]*e[1][1],e[1]=[e[1][0]-e[0][0]*a.shear,e[1][1]-e[0][1]*a.shear],a.scaley=N.sqrt(b(e[1])),d(e[1]),a.shear/=a.scaley;var f=-e[0][1],g=e[1][1];return 0>g?(a.rotate=c.deg(N.acos(g)),0>f&&(a.rotate=360-a.rotate)):a.rotate=c.deg(N.asin(f)),a.isSimple=!(+a.shear.toFixed(9)||a.scalex.toFixed(9)!=a.scaley.toFixed(9)&&a.rotate),a.isSuperSimple=!+a.shear.toFixed(9)&&a.scalex.toFixed(9)==a.scaley.toFixed(9)&&!a.rotate,a.noRotation=!+a.shear.toFixed(9)&&!a.rotate,a},a.toTransformString=function(a){var b=a||this[J]();return b.isSimple?(b.scalex=+b.scalex.toFixed(4),b.scaley=+b.scaley.toFixed(4),b.rotate=+b.rotate.toFixed(4),(b.dx||b.dy?"t"+[b.dx,b.dy]:G)+(1!=b.scalex||1!=b.scaley?"s"+[b.scalex,b.scaley,0,0]:G)+(b.rotate?"r"+[b.rotate,0,0]:G)):"m"+[this.get(0),this.get(1),this.get(2),this.get(3),this.get(4),this.get(5)]}}(o.prototype);var Qb=navigator.userAgent.match(/Version\/(.*?)\s/)||navigator.userAgent.match(/Chrome\/(\d+)/);v.safari="Apple Computer, Inc."==navigator.vendor&&(Qb&&Qb[1]<4||"iP"==navigator.platform.slice(0,2))||"Google Inc."==navigator.vendor&&Qb&&Qb[1]<8?function(){var a=this.rect(-99,-99,this.width+99,this.height+99).attr({stroke:"none"});setTimeout(function(){a.remove()})}:mb;for(var Rb=function(){this.returnValue=!1},Sb=function(){return this.originalEvent.preventDefault()},Tb=function(){this.cancelBubble=!0},Ub=function(){return this.originalEvent.stopPropagation()},Vb=function(a){var b=A.doc.documentElement.scrollTop||A.doc.body.scrollTop,c=A.doc.documentElement.scrollLeft||A.doc.body.scrollLeft;return{x:a.clientX+c,y:a.clientY+b}},Wb=function(){return A.doc.addEventListener?function(a,b,c,d){var e=function(a){var b=Vb(a);return c.call(d,a,b.x,b.y)};if(a.addEventListener(b,e,!1),F&&L[b]){var f=function(b){for(var e=Vb(b),f=b,g=0,h=b.targetTouches&&b.targetTouches.length;h>g;g++)if(b.targetTouches[g].target==a){b=b.targetTouches[g],b.originalEvent=f,b.preventDefault=Sb,b.stopPropagation=Ub;break}return c.call(d,b,e.x,e.y)};a.addEventListener(L[b],f,!1)}return function(){return a.removeEventListener(b,e,!1),F&&L[b]&&a.removeEventListener(L[b],e,!1),!0}}:A.doc.attachEvent?function(a,b,c,d){var e=function(a){a=a||A.win.event;var b=A.doc.documentElement.scrollTop||A.doc.body.scrollTop,e=A.doc.documentElement.scrollLeft||A.doc.body.scrollLeft,f=a.clientX+e,g=a.clientY+b;return a.preventDefault=a.preventDefault||Rb,a.stopPropagation=a.stopPropagation||Tb,c.call(d,a,f,g)};a.attachEvent("on"+b,e);var f=function(){return a.detachEvent("on"+b,e),!0};return f}:void 0}(),Xb=[],Yb=function(a){for(var c,d=a.clientX,e=a.clientY,f=A.doc.documentElement.scrollTop||A.doc.body.scrollTop,g=A.doc.documentElement.scrollLeft||A.doc.body.scrollLeft,h=Xb.length;h--;){if(c=Xb[h],F&&a.touches){for(var i,j=a.touches.length;j--;)if(i=a.touches[j],i.identifier==c.el._drag.id){d=i.clientX,e=i.clientY,(a.originalEvent?a.originalEvent:a).preventDefault();break}}else a.preventDefault();var k,l=c.el.node,m=l.nextSibling,n=l.parentNode,o=l.style.display;A.win.opera&&n.removeChild(l),l.style.display="none",k=c.el.paper.getElementByPoint(d,e),l.style.display=o,A.win.opera&&(m?n.insertBefore(l,m):n.appendChild(l)),k&&b("raphael.drag.over."+c.el.id,c.el,k),d+=g,e+=f,b("raphael.drag.move."+c.el.id,c.move_scope||c.el,d-c.el._drag.x,e-c.el._drag.y,d,e,a)}},Zb=function(a){c.unmousemove(Yb).unmouseup(Zb);for(var d,e=Xb.length;e--;)d=Xb[e],d.el._drag={},b("raphael.drag.end."+d.el.id,d.end_scope||d.start_scope||d.move_scope||d.el,a);Xb=[]},$b=c.el={},_b=K.length;_b--;)!function(a){c[a]=$b[a]=function(b,d){return c.is(b,"function")&&(this.events=this.events||[],this.events.push({name:a,f:b,unbind:Wb(this.shape||this.node||A.doc,a,b,d||this)})),this},c["un"+a]=$b["un"+a]=function(b){for(var d=this.events||[],e=d.length;e--;)d[e].name!=a||!c.is(b,"undefined")&&d[e].f!=b||(d[e].unbind(),d.splice(e,1),!d.length&&delete this.events);return this}}(K[_b]);$b.data=function(a,d){var e=kb[this.id]=kb[this.id]||{};if(0==arguments.length)return e;if(1==arguments.length){if(c.is(a,"object")){for(var f in a)a[z](f)&&this.data(f,a[f]);return this}return b("raphael.data.get."+this.id,this,e[a],a),e[a]}return e[a]=d,b("raphael.data.set."+this.id,this,d,a),this},$b.removeData=function(a){return null==a?kb[this.id]={}:kb[this.id]&&delete kb[this.id][a],this},$b.getData=function(){return d(kb[this.id]||{})},$b.hover=function(a,b,c,d){return this.mouseover(a,c).mouseout(b,d||c)},$b.unhover=function(a,b){return this.unmouseover(a).unmouseout(b)};var ac=[];$b.drag=function(a,d,e,f,g,h){function i(i){(i.originalEvent||i).preventDefault();var j=i.clientX,k=i.clientY,l=A.doc.documentElement.scrollTop||A.doc.body.scrollTop,m=A.doc.documentElement.scrollLeft||A.doc.body.scrollLeft;if(this._drag.id=i.identifier,F&&i.touches)for(var n,o=i.touches.length;o--;)if(n=i.touches[o],this._drag.id=n.identifier,n.identifier==this._drag.id){j=n.clientX,k=n.clientY;break}this._drag.x=j+m,this._drag.y=k+l,!Xb.length&&c.mousemove(Yb).mouseup(Zb),Xb.push({el:this,move_scope:f,start_scope:g,end_scope:h}),d&&b.on("raphael.drag.start."+this.id,d),a&&b.on("raphael.drag.move."+this.id,a),e&&b.on("raphael.drag.end."+this.id,e),b("raphael.drag.start."+this.id,g||f||this,i.clientX+m,i.clientY+l,i)}return this._drag={},ac.push({el:this,start:i}),this.mousedown(i),this},$b.onDragOver=function(a){a?b.on("raphael.drag.over."+this.id,a):b.unbind("raphael.drag.over."+this.id)},$b.undrag=function(){for(var a=ac.length;a--;)ac[a].el==this&&(this.unmousedown(ac[a].start),ac.splice(a,1),b.unbind("raphael.drag.*."+this.id));!ac.length&&c.unmousemove(Yb).unmouseup(Zb),Xb=[]},v.circle=function(a,b,d){var e=c._engine.circle(this,a||0,b||0,d||0);return this.__set__&&this.__set__.push(e),e},v.rect=function(a,b,d,e,f){var g=c._engine.rect(this,a||0,b||0,d||0,e||0,f||0);return this.__set__&&this.__set__.push(g),g},v.ellipse=function(a,b,d,e){var f=c._engine.ellipse(this,a||0,b||0,d||0,e||0);return this.__set__&&this.__set__.push(f),f},v.path=function(a){a&&!c.is(a,U)&&!c.is(a[0],V)&&(a+=G);var b=c._engine.path(c.format[D](c,arguments),this);return this.__set__&&this.__set__.push(b),b},v.image=function(a,b,d,e,f){var g=c._engine.image(this,a||"about:blank",b||0,d||0,e||0,f||0);return this.__set__&&this.__set__.push(g),g},v.text=function(a,b,d){var e=c._engine.text(this,a||0,b||0,I(d));return this.__set__&&this.__set__.push(e),e},v.set=function(a){!c.is(a,"array")&&(a=Array.prototype.splice.call(arguments,0,arguments.length));var b=new mc(a);return this.__set__&&this.__set__.push(b),b.paper=this,b.type="set",b},v.setStart=function(a){this.__set__=a||this.set()},v.setFinish=function(){var a=this.__set__;return delete this.__set__,a},v.setSize=function(a,b){return c._engine.setSize.call(this,a,b)},v.setViewBox=function(a,b,d,e,f){return c._engine.setViewBox.call(this,a,b,d,e,f)},v.top=v.bottom=null,v.raphael=c;var bc=function(a){var b=a.getBoundingClientRect(),c=a.ownerDocument,d=c.body,e=c.documentElement,f=e.clientTop||d.clientTop||0,g=e.clientLeft||d.clientLeft||0,h=b.top+(A.win.pageYOffset||e.scrollTop||d.scrollTop)-f,i=b.left+(A.win.pageXOffset||e.scrollLeft||d.scrollLeft)-g;return{y:h,x:i}};v.getElementByPoint=function(a,b){var c=this,d=c.canvas,e=A.doc.elementFromPoint(a,b);if(A.win.opera&&"svg"==e.tagName){var f=bc(d),g=d.createSVGRect();g.x=a-f.x,g.y=b-f.y,g.width=g.height=1;var h=d.getIntersectionList(g,null);h.length&&(e=h[h.length-1])}if(!e)return null;for(;e.parentNode&&e!=d.parentNode&&!e.raphael;)e=e.parentNode;return e==c.canvas.parentNode&&(e=d),e=e&&e.raphael?c.getById(e.raphaelid):null},v.getElementsByBBox=function(a){var b=this.set();return this.forEach(function(d){c.isBBoxIntersect(d.getBBox(),a)&&b.push(d)}),b},v.getById=function(a){for(var b=this.bottom;b;){if(b.id==a)return b;b=b.next}return null},v.forEach=function(a,b){for(var c=this.bottom;c;){if(a.call(b,c)===!1)return this;c=c.next}return this},v.getElementsByPoint=function(a,b){var c=this.set();return this.forEach(function(d){d.isPointInside(a,b)&&c.push(d)}),c},$b.isPointInside=function(a,b){var d=this.realPath=qb[this.type](this);return this.attr("transform")&&this.attr("transform").length&&(d=c.transformPath(d,this.attr("transform"))),c.isPointInsidePath(d,a,b)},$b.getBBox=function(a){if(this.removed)return{};var b=this._;return a?((b.dirty||!b.bboxwt)&&(this.realPath=qb[this.type](this),b.bboxwt=Bb(this.realPath),b.bboxwt.toString=p,b.dirty=0),b.bboxwt):((b.dirty||b.dirtyT||!b.bbox)&&((b.dirty||!this.realPath)&&(b.bboxwt=0,this.realPath=qb[this.type](this)),b.bbox=Bb(rb(this.realPath,this.matrix)),b.bbox.toString=p,b.dirty=b.dirtyT=0),b.bbox)},$b.clone=function(){if(this.removed)return null;var a=this.paper[this.type]().attr(this.attr());return this.__set__&&this.__set__.push(a),a},$b.glow=function(a){if("text"==this.type)return null;a=a||{};var b={width:(a.width||10)+(+this.attr("stroke-width")||1),fill:a.fill||!1,opacity:a.opacity||.5,offsetx:a.offsetx||0,offsety:a.offsety||0,color:a.color||"#000"},c=b.width/2,d=this.paper,e=d.set(),f=this.realPath||qb[this.type](this);f=this.matrix?rb(f,this.matrix):f;for(var g=1;c+1>g;g++)e.push(d.path(f).attr({stroke:b.color,fill:b.fill?b.color:"none","stroke-linejoin":"round","stroke-linecap":"round","stroke-width":+(b.width/c*g).toFixed(3),opacity:+(b.opacity/c).toFixed(3)}));return e.insertBefore(this).translate(b.offsetx,b.offsety)};var cc=function(a,b,d,e,f,g,h,i,l){return null==l?j(a,b,d,e,f,g,h,i):c.findDotsAtSegment(a,b,d,e,f,g,h,i,k(a,b,d,e,f,g,h,i,l))},dc=function(a,b){return function(d,e,f){d=Kb(d);for(var g,h,i,j,k,l="",m={},n=0,o=0,p=d.length;p>o;o++){if(i=d[o],"M"==i[0])g=+i[1],h=+i[2];else{if(j=cc(g,h,i[1],i[2],i[3],i[4],i[5],i[6]),n+j>e){if(b&&!m.start){if(k=cc(g,h,i[1],i[2],i[3],i[4],i[5],i[6],e-n),l+=["C"+k.start.x,k.start.y,k.m.x,k.m.y,k.x,k.y],f)return l;m.start=l,l=["M"+k.x,k.y+"C"+k.n.x,k.n.y,k.end.x,k.end.y,i[5],i[6]].join(),n+=j,g=+i[5],h=+i[6];continue}if(!a&&!b)return k=cc(g,h,i[1],i[2],i[3],i[4],i[5],i[6],e-n),{x:k.x,y:k.y,alpha:k.alpha}}n+=j,g=+i[5],h=+i[6]}l+=i.shift()+i}return m.end=l,k=a?n:b?m:c.findDotsAtSegment(g,h,i[0],i[1],i[2],i[3],i[4],i[5],1),k.alpha&&(k={x:k.x,y:k.y,alpha:k.alpha}),k}},ec=dc(1),fc=dc(),gc=dc(0,1);c.getTotalLength=ec,c.getPointAtLength=fc,c.getSubpath=function(a,b,c){if(this.getTotalLength(a)-c<1e-6)return gc(a,b).end;var d=gc(a,c,1);return b?gc(d,b).end:d},$b.getTotalLength=function(){var a=this.getPath();if(a)return this.node.getTotalLength?this.node.getTotalLength():ec(a)},$b.getPointAtLength=function(a){var b=this.getPath();if(b)return fc(b,a)},$b.getPath=function(){var a,b=c._getPath[this.type];if("text"!=this.type&&"set"!=this.type)return b&&(a=b(this)),a},$b.getSubpath=function(a,b){var d=this.getPath();if(d)return c.getSubpath(d,a,b)};var hc=c.easing_formulas={linear:function(a){return a},"<":function(a){return R(a,1.7)},">":function(a){return R(a,.48)},"<>":function(a){var b=.48-a/1.04,c=N.sqrt(.1734+b*b),d=c-b,e=R(Q(d),1/3)*(0>d?-1:1),f=-c-b,g=R(Q(f),1/3)*(0>f?-1:1),h=e+g+.5;return 3*(1-h)*h*h+h*h*h},backIn:function(a){var b=1.70158;return a*a*((b+1)*a-b)},backOut:function(a){a-=1;var b=1.70158;return a*a*((b+1)*a+b)+1},elastic:function(a){return a==!!a?a:R(2,-10*a)*N.sin((a-.075)*2*S/.3)+1},bounce:function(a){var b,c=7.5625,d=2.75;return 1/d>a?b=c*a*a:2/d>a?(a-=1.5/d,b=c*a*a+.75):2.5/d>a?(a-=2.25/d,b=c*a*a+.9375):(a-=2.625/d,b=c*a*a+.984375),b}};hc.easeIn=hc["ease-in"]=hc["<"],hc.easeOut=hc["ease-out"]=hc[">"],hc.easeInOut=hc["ease-in-out"]=hc["<>"],hc["back-in"]=hc.backIn,hc["back-out"]=hc.backOut;var ic=[],jc=a.requestAnimationFrame||a.webkitRequestAnimationFrame||a.mozRequestAnimationFrame||a.oRequestAnimationFrame||a.msRequestAnimationFrame||function(a){setTimeout(a,16)},kc=function(){for(var a=+new Date,d=0;d<ic.length;d++){var e=ic[d];if(!e.el.removed&&!e.paused){var f,g,h=a-e.start,i=e.ms,j=e.easing,k=e.from,l=e.diff,m=e.to,n=(e.t,e.el),o={},p={};if(e.initstatus?(h=(e.initstatus*e.anim.top-e.prev)/(e.percent-e.prev)*i,e.status=e.initstatus,delete e.initstatus,e.stop&&ic.splice(d--,1)):e.status=(e.prev+(e.percent-e.prev)*(h/i))/e.anim.top,!(0>h))if(i>h){var q=j(h/i);for(var r in k)if(k[z](r)){switch(db[r]){case T:f=+k[r]+q*i*l[r];break;case"colour":f="rgb("+[lc($(k[r].r+q*i*l[r].r)),lc($(k[r].g+q*i*l[r].g)),lc($(k[r].b+q*i*l[r].b))].join(",")+")";break;case"path":f=[];for(var t=0,u=k[r].length;u>t;t++){f[t]=[k[r][t][0]];for(var v=1,w=k[r][t].length;w>v;v++)f[t][v]=+k[r][t][v]+q*i*l[r][t][v];f[t]=f[t].join(H)}f=f.join(H);break;case"transform":if(l[r].real)for(f=[],t=0,u=k[r].length;u>t;t++)for(f[t]=[k[r][t][0]],v=1,w=k[r][t].length;w>v;v++)f[t][v]=k[r][t][v]+q*i*l[r][t][v];else{var x=function(a){return+k[r][a]+q*i*l[r][a]};f=[["m",x(0),x(1),x(2),x(3),x(4),x(5)]]}break;case"csv":if("clip-rect"==r)for(f=[],t=4;t--;)f[t]=+k[r][t]+q*i*l[r][t];break;default:var y=[][E](k[r]);for(f=[],t=n.paper.customAttributes[r].length;t--;)f[t]=+y[t]+q*i*l[r][t]}o[r]=f}n.attr(o),function(a,c,d){setTimeout(function(){b("raphael.anim.frame."+a,c,d)})}(n.id,n,e.anim)}else{if(function(a,d,e){setTimeout(function(){b("raphael.anim.frame."+d.id,d,e),b("raphael.anim.finish."+d.id,d,e),c.is(a,"function")&&a.call(d)})}(e.callback,n,e.anim),n.attr(m),ic.splice(d--,1),e.repeat>1&&!e.next){for(g in m)m[z](g)&&(p[g]=e.totalOrigin[g]);e.el.attr(p),s(e.anim,e.el,e.anim.percents[0],null,e.totalOrigin,e.repeat-1)}e.next&&!e.stop&&s(e.anim,e.el,e.next,null,e.totalOrigin,e.repeat)}}}c.svg&&n&&n.paper&&n.paper.safari(),ic.length&&jc(kc)},lc=function(a){return a>255?255:0>a?0:a};$b.animateWith=function(a,b,d,e,f,g){var h=this;if(h.removed)return g&&g.call(h),h;var i=d instanceof r?d:c.animation(d,e,f,g);s(i,h,i.percents[0],null,h.attr());for(var j=0,k=ic.length;k>j;j++)if(ic[j].anim==b&&ic[j].el==a){ic[k-1].start=ic[j].start;break}return h},$b.onAnimation=function(a){return a?b.on("raphael.anim.frame."+this.id,a):b.unbind("raphael.anim.frame."+this.id),this},r.prototype.delay=function(a){var b=new r(this.anim,this.ms);return b.times=this.times,b.del=+a||0,b},r.prototype.repeat=function(a){var b=new r(this.anim,this.ms);return b.del=this.del,b.times=N.floor(O(a,0))||1,b},c.animation=function(a,b,d,e){if(a instanceof r)return a;(c.is(d,"function")||!d)&&(e=e||d||null,d=null),a=Object(a),b=+b||0;var f,g,h={};for(g in a)a[z](g)&&_(g)!=g&&_(g)+"%"!=g&&(f=!0,h[g]=a[g]);return f?(d&&(h.easing=d),e&&(h.callback=e),new r({100:h},b)):new r(a,b)},$b.animate=function(a,b,d,e){var f=this;if(f.removed)return e&&e.call(f),f;var g=a instanceof r?a:c.animation(a,b,d,e);return s(g,f,g.percents[0],null,f.attr()),f},$b.setTime=function(a,b){return a&&null!=b&&this.status(a,P(b,a.ms)/a.ms),this},$b.status=function(a,b){var c,d,e=[],f=0;if(null!=b)return s(a,this,-1,P(b,1)),this;for(c=ic.length;c>f;f++)if(d=ic[f],d.el.id==this.id&&(!a||d.anim==a)){if(a)return d.status;e.push({anim:d.anim,status:d.status})}return a?0:e},$b.pause=function(a){for(var c=0;c<ic.length;c++)ic[c].el.id!=this.id||a&&ic[c].anim!=a||b("raphael.anim.pause."+this.id,this,ic[c].anim)!==!1&&(ic[c].paused=!0);return this},$b.resume=function(a){for(var c=0;c<ic.length;c++)if(ic[c].el.id==this.id&&(!a||ic[c].anim==a)){var d=ic[c];b("raphael.anim.resume."+this.id,this,d.anim)!==!1&&(delete d.paused,this.status(d.anim,d.status))}return this},$b.stop=function(a){for(var c=0;c<ic.length;c++)ic[c].el.id!=this.id||a&&ic[c].anim!=a||b("raphael.anim.stop."+this.id,this,ic[c].anim)!==!1&&ic.splice(c--,1);return this},b.on("raphael.remove",t),b.on("raphael.clear",t),$b.toString=function(){return"Raphaël’s object"};var mc=function(a){if(this.items=[],this.length=0,this.type="set",a)for(var b=0,c=a.length;c>b;b++)!a[b]||a[b].constructor!=$b.constructor&&a[b].constructor!=mc||(this[this.items.length]=this.items[this.items.length]=a[b],this.length++)},nc=mc.prototype;nc.push=function(){for(var a,b,c=0,d=arguments.length;d>c;c++)a=arguments[c],!a||a.constructor!=$b.constructor&&a.constructor!=mc||(b=this.items.length,this[b]=this.items[b]=a,this.length++);return this},nc.pop=function(){return this.length&&delete this[this.length--],this.items.pop()},nc.forEach=function(a,b){for(var c=0,d=this.items.length;d>c;c++)if(a.call(b,this.items[c],c)===!1)return this;return this};for(var oc in $b)$b[z](oc)&&(nc[oc]=function(a){return function(){var b=arguments;return this.forEach(function(c){c[a][D](c,b)})}}(oc));return nc.attr=function(a,b){if(a&&c.is(a,V)&&c.is(a[0],"object"))for(var d=0,e=a.length;e>d;d++)this.items[d].attr(a[d]);else for(var f=0,g=this.items.length;g>f;f++)this.items[f].attr(a,b);return this},nc.clear=function(){for(;this.length;)this.pop()},nc.splice=function(a,b){a=0>a?O(this.length+a,0):a,b=O(0,P(this.length-a,b));var c,d=[],e=[],f=[];for(c=2;c<arguments.length;c++)f.push(arguments[c]);for(c=0;b>c;c++)e.push(this[a+c]);for(;c<this.length-a;c++)d.push(this[a+c]);var g=f.length;for(c=0;c<g+d.length;c++)this.items[a+c]=this[a+c]=g>c?f[c]:d[c-g];for(c=this.items.length=this.length-=b-g;this[c];)delete this[c++];return new mc(e)},nc.exclude=function(a){for(var b=0,c=this.length;c>b;b++)if(this[b]==a)return this.splice(b,1),!0},nc.animate=function(a,b,d,e){(c.is(d,"function")||!d)&&(e=d||null);var f,g,h=this.items.length,i=h,j=this;if(!h)return this;e&&(g=function(){!--h&&e.call(j)}),d=c.is(d,U)?d:g;var k=c.animation(a,b,d,g);for(f=this.items[--i].animate(k);i--;)this.items[i]&&!this.items[i].removed&&this.items[i].animateWith(f,k,k),this.items[i]&&!this.items[i].removed||h--;return this},nc.insertAfter=function(a){for(var b=this.items.length;b--;)this.items[b].insertAfter(a);return this},nc.getBBox=function(){for(var a=[],b=[],c=[],d=[],e=this.items.length;e--;)if(!this.items[e].removed){var f=this.items[e].getBBox();a.push(f.x),b.push(f.y),c.push(f.x+f.width),d.push(f.y+f.height)}return a=P[D](0,a),b=P[D](0,b),c=O[D](0,c),d=O[D](0,d),{x:a,y:b,x2:c,y2:d,width:c-a,height:d-b}},nc.clone=function(a){a=this.paper.set();for(var b=0,c=this.items.length;c>b;b++)a.push(this.items[b].clone());return a},nc.toString=function(){return"Raphaël‘s set"},nc.glow=function(a){var b=this.paper.set();return this.forEach(function(c){var d=c.glow(a);null!=d&&d.forEach(function(a){b.push(a)})}),b},nc.isPointInside=function(a,b){var c=!1;return this.forEach(function(d){return d.isPointInside(a,b)?(console.log("runned"),c=!0,!1):void 0}),c},c.registerFont=function(a){if(!a.face)return a;this.fonts=this.fonts||{};var b={w:a.w,face:{},glyphs:{}},c=a.face["font-family"];for(var d in a.face)a.face[z](d)&&(b.face[d]=a.face[d]);if(this.fonts[c]?this.fonts[c].push(b):this.fonts[c]=[b],!a.svg){b.face["units-per-em"]=ab(a.face["units-per-em"],10);for(var e in a.glyphs)if(a.glyphs[z](e)){var f=a.glyphs[e];if(b.glyphs[e]={w:f.w,k:{},d:f.d&&"M"+f.d.replace(/[mlcxtrv]/g,function(a){return{l:"L",c:"C",x:"z",t:"m",r:"l",v:"c"}[a]||"M"})+"z"},f.k)for(var g in f.k)f[z](g)&&(b.glyphs[e].k[g]=f.k[g])}}return a},v.getFont=function(a,b,d,e){if(e=e||"normal",d=d||"normal",b=+b||{normal:400,bold:700,lighter:300,bolder:800}[b]||400,c.fonts){var f=c.fonts[a];if(!f){var g=new RegExp("(^|\\s)"+a.replace(/[^\w\d\s+!~.:_-]/g,G)+"(\\s|$)","i");for(var h in c.fonts)if(c.fonts[z](h)&&g.test(h)){f=c.fonts[h];break}}var i;if(f)for(var j=0,k=f.length;k>j&&(i=f[j],i.face["font-weight"]!=b||i.face["font-style"]!=d&&i.face["font-style"]||i.face["font-stretch"]!=e);j++);return i}},v.print=function(a,b,d,e,f,g,h,i){g=g||"middle",h=O(P(h||0,1),-1),i=O(P(i||1,3),1);var j,k=I(d)[J](G),l=0,m=0,n=G;if(c.is(e,"string")&&(e=this.getFont(e)),e){j=(f||16)/e.face["units-per-em"];for(var o=e.face.bbox[J](w),p=+o[0],q=o[3]-o[1],r=0,s=+o[1]+("baseline"==g?q+ +e.face.descent:q/2),t=0,u=k.length;u>t;t++){if("\n"==k[t])l=0,x=0,m=0,r+=q*i;else{var v=m&&e.glyphs[k[t-1]]||{},x=e.glyphs[k[t]];l+=m?(v.w||e.w)+(v.k&&v.k[k[t]]||0)+e.w*h:0,m=1}x&&x.d&&(n+=c.transformPath(x.d,["t",l*j,r*j,"s",j,j,p,s,"t",(a-p)/j,(b-s)/j]))}}return this.path(n).attr({fill:"#000",stroke:"none"})},v.add=function(a){if(c.is(a,"array"))for(var b,d=this.set(),e=0,f=a.length;f>e;e++)b=a[e]||{},x[z](b.type)&&d.push(this[b.type]().attr(b));return d},c.format=function(a,b){var d=c.is(b,V)?[0][E](b):arguments;return a&&c.is(a,U)&&d.length-1&&(a=a.replace(y,function(a,b){return null==d[++b]?G:d[b]})),a||G},c.fullfill=function(){var a=/\{([^\}]+)\}/g,b=/(?:(?:^|\.)(.+?)(?=\[|\.|$|\()|\[('|")(.+?)\2\])(\(\))?/g,c=function(a,c,d){var e=d;return c.replace(b,function(a,b,c,d,f){b=b||d,e&&(b in e&&(e=e[b]),"function"==typeof e&&f&&(e=e()))}),e=(null==e||e==d?a:e)+""};return function(b,d){return String(b).replace(a,function(a,b){return c(a,b,d)})}}(),c.ninja=function(){return B.was?A.win.Raphael=B.is:delete Raphael,c},c.st=nc,function(a,b,d){function e(){/in/.test(a.readyState)?setTimeout(e,9):c.eve("raphael.DOMload")}null==a.readyState&&a.addEventListener&&(a.addEventListener(b,d=function(){a.removeEventListener(b,d,!1),a.readyState="complete"},!1),a.readyState="loading"),e()}(document,"DOMContentLoaded"),b.on("raphael.DOMload",function(){u=!0}),function(){if(c.svg){var a="hasOwnProperty",b=String,d=parseFloat,e=parseInt,f=Math,g=f.max,h=f.abs,i=f.pow,j=/[, ]+/,k=c.eve,l="",m=" ",n="http://www.w3.org/1999/xlink",o={block:"M5,0 0,2.5 5,5z",classic:"M5,0 0,2.5 5,5 3.5,3 3.5,2z",diamond:"M2.5,0 5,2.5 2.5,5 0,2.5z",open:"M6,1 1,3.5 6,6",oval:"M2.5,0A2.5,2.5,0,0,1,2.5,5 2.5,2.5,0,0,1,2.5,0z"},p={};c.toString=function(){return"Your browser supports SVG.\nYou are running Raphaël "+this.version};var q=function(d,e){if(e){"string"==typeof d&&(d=q(d));for(var f in e)e[a](f)&&("xlink:"==f.substring(0,6)?d.setAttributeNS(n,f.substring(6),b(e[f])):d.setAttribute(f,b(e[f])))}else d=c._g.doc.createElementNS("http://www.w3.org/2000/svg",d),d.style&&(d.style.webkitTapHighlightColor="rgba(0,0,0,0)");return d},r=function(a,e){var j="linear",k=a.id+e,m=.5,n=.5,o=a.node,p=a.paper,r=o.style,s=c._g.doc.getElementById(k);if(!s){if(e=b(e).replace(c._radial_gradient,function(a,b,c){if(j="radial",b&&c){m=d(b),n=d(c);var e=2*(n>.5)-1;i(m-.5,2)+i(n-.5,2)>.25&&(n=f.sqrt(.25-i(m-.5,2))*e+.5)&&.5!=n&&(n=n.toFixed(5)-1e-5*e)}return l}),e=e.split(/\s*\-\s*/),"linear"==j){var t=e.shift();if(t=-d(t),isNaN(t))return null;var u=[0,0,f.cos(c.rad(t)),f.sin(c.rad(t))],v=1/(g(h(u[2]),h(u[3]))||1);u[2]*=v,u[3]*=v,u[2]<0&&(u[0]=-u[2],u[2]=0),u[3]<0&&(u[1]=-u[3],u[3]=0)}var w=c._parseDots(e);if(!w)return null;if(k=k.replace(/[\(\)\s,\xb0#]/g,"_"),a.gradient&&k!=a.gradient.id&&(p.defs.removeChild(a.gradient),delete a.gradient),!a.gradient){s=q(j+"Gradient",{id:k}),a.gradient=s,q(s,"radial"==j?{fx:m,fy:n}:{x1:u[0],y1:u[1],x2:u[2],y2:u[3],gradientTransform:a.matrix.invert()}),p.defs.appendChild(s);for(var x=0,y=w.length;y>x;x++)s.appendChild(q("stop",{offset:w[x].offset?w[x].offset:x?"100%":"0%","stop-color":w[x].color||"#fff"}))}}return q(o,{fill:"url(#"+k+")",opacity:1,"fill-opacity":1}),r.fill=l,r.opacity=1,r.fillOpacity=1,1},s=function(a){var b=a.getBBox(1);q(a.pattern,{patternTransform:a.matrix.invert()+" translate("+b.x+","+b.y+")"})},t=function(d,e,f){if("path"==d.type){for(var g,h,i,j,k,m=b(e).toLowerCase().split("-"),n=d.paper,r=f?"end":"start",s=d.node,t=d.attrs,u=t["stroke-width"],v=m.length,w="classic",x=3,y=3,z=5;v--;)switch(m[v]){case"block":case"classic":case"oval":case"diamond":case"open":case"none":w=m[v];break;case"wide":y=5;break;case"narrow":y=2;break;case"long":x=5;break;case"short":x=2}if("open"==w?(x+=2,y+=2,z+=2,i=1,j=f?4:1,k={fill:"none",stroke:t.stroke}):(j=i=x/2,k={fill:t.stroke,stroke:"none"}),d._.arrows?f?(d._.arrows.endPath&&p[d._.arrows.endPath]--,d._.arrows.endMarker&&p[d._.arrows.endMarker]--):(d._.arrows.startPath&&p[d._.arrows.startPath]--,d._.arrows.startMarker&&p[d._.arrows.startMarker]--):d._.arrows={},"none"!=w){var A="raphael-marker-"+w,B="raphael-marker-"+r+w+x+y;c._g.doc.getElementById(A)?p[A]++:(n.defs.appendChild(q(q("path"),{"stroke-linecap":"round",d:o[w],id:A})),p[A]=1);var C,D=c._g.doc.getElementById(B);D?(p[B]++,C=D.getElementsByTagName("use")[0]):(D=q(q("marker"),{id:B,markerHeight:y,markerWidth:x,orient:"auto",refX:j,refY:y/2}),C=q(q("use"),{"xlink:href":"#"+A,transform:(f?"rotate(180 "+x/2+" "+y/2+") ":l)+"scale("+x/z+","+y/z+")","stroke-width":(1/((x/z+y/z)/2)).toFixed(4)}),D.appendChild(C),n.defs.appendChild(D),p[B]=1),q(C,k);var E=i*("diamond"!=w&&"oval"!=w);f?(g=d._.arrows.startdx*u||0,h=c.getTotalLength(t.path)-E*u):(g=E*u,h=c.getTotalLength(t.path)-(d._.arrows.enddx*u||0)),k={},k["marker-"+r]="url(#"+B+")",(h||g)&&(k.d=c.getSubpath(t.path,g,h)),q(s,k),d._.arrows[r+"Path"]=A,d._.arrows[r+"Marker"]=B,d._.arrows[r+"dx"]=E,d._.arrows[r+"Type"]=w,d._.arrows[r+"String"]=e}else f?(g=d._.arrows.startdx*u||0,h=c.getTotalLength(t.path)-g):(g=0,h=c.getTotalLength(t.path)-(d._.arrows.enddx*u||0)),d._.arrows[r+"Path"]&&q(s,{d:c.getSubpath(t.path,g,h)}),delete d._.arrows[r+"Path"],delete d._.arrows[r+"Marker"],delete d._.arrows[r+"dx"],delete d._.arrows[r+"Type"],delete d._.arrows[r+"String"];for(k in p)if(p[a](k)&&!p[k]){var F=c._g.doc.getElementById(k);F&&F.parentNode.removeChild(F)}}},u={"":[0],none:[0],"-":[3,1],".":[1,1],"-.":[3,1,1,1],"-..":[3,1,1,1,1,1],". ":[1,3],"- ":[4,3],"--":[8,3],"- .":[4,3,1,3],"--.":[8,3,1,3],"--..":[8,3,1,3,1,3]},v=function(a,c,d){if(c=u[b(c).toLowerCase()]){for(var e=a.attrs["stroke-width"]||"1",f={round:e,square:e,butt:0}[a.attrs["stroke-linecap"]||d["stroke-linecap"]]||0,g=[],h=c.length;h--;)g[h]=c[h]*e+(h%2?1:-1)*f;q(a.node,{"stroke-dasharray":g.join(",")})}},w=function(d,f){var i=d.node,k=d.attrs,m=i.style.visibility;i.style.visibility="hidden";for(var o in f)if(f[a](o)){if(!c._availableAttrs[a](o))continue;var p=f[o];switch(k[o]=p,o){case"blur":d.blur(p);break;case"href":case"title":var u=q("title"),w=c._g.doc.createTextNode(p);u.appendChild(w),i.appendChild(u);break;case"target":var x=i.parentNode;if("a"!=x.tagName.toLowerCase()){var u=q("a");x.insertBefore(u,i),u.appendChild(i),x=u}"target"==o?x.setAttributeNS(n,"show","blank"==p?"new":p):x.setAttributeNS(n,o,p);break;case"cursor":i.style.cursor=p;break;case"transform":d.transform(p);break;case"arrow-start":t(d,p);break;case"arrow-end":t(d,p,1);break;case"clip-rect":var z=b(p).split(j);if(4==z.length){d.clip&&d.clip.parentNode.parentNode.removeChild(d.clip.parentNode);var A=q("clipPath"),B=q("rect");A.id=c.createUUID(),q(B,{x:z[0],y:z[1],width:z[2],height:z[3]}),A.appendChild(B),d.paper.defs.appendChild(A),q(i,{"clip-path":"url(#"+A.id+")"}),d.clip=B}if(!p){var C=i.getAttribute("clip-path");if(C){var D=c._g.doc.getElementById(C.replace(/(^url\(#|\)$)/g,l));D&&D.parentNode.removeChild(D),q(i,{"clip-path":l}),delete d.clip}}break;case"path":"path"==d.type&&(q(i,{d:p?k.path=c._pathToAbsolute(p):"M0,0"}),d._.dirty=1,d._.arrows&&("startString"in d._.arrows&&t(d,d._.arrows.startString),"endString"in d._.arrows&&t(d,d._.arrows.endString,1)));break;case"width":if(i.setAttribute(o,p),d._.dirty=1,!k.fx)break;o="x",p=k.x;case"x":k.fx&&(p=-k.x-(k.width||0));case"rx":if("rx"==o&&"rect"==d.type)break;case"cx":i.setAttribute(o,p),d.pattern&&s(d),d._.dirty=1;break;case"height":if(i.setAttribute(o,p),d._.dirty=1,!k.fy)break;o="y",p=k.y;case"y":k.fy&&(p=-k.y-(k.height||0));case"ry":if("ry"==o&&"rect"==d.type)break;case"cy":i.setAttribute(o,p),d.pattern&&s(d),d._.dirty=1;break;case"r":"rect"==d.type?q(i,{rx:p,ry:p}):i.setAttribute(o,p),d._.dirty=1;break;case"src":"image"==d.type&&i.setAttributeNS(n,"href",p);break;case"stroke-width":(1!=d._.sx||1!=d._.sy)&&(p/=g(h(d._.sx),h(d._.sy))||1),d.paper._vbSize&&(p*=d.paper._vbSize),i.setAttribute(o,p),k["stroke-dasharray"]&&v(d,k["stroke-dasharray"],f),d._.arrows&&("startString"in d._.arrows&&t(d,d._.arrows.startString),"endString"in d._.arrows&&t(d,d._.arrows.endString,1));break;case"stroke-dasharray":v(d,p,f);break;case"fill":var E=b(p).match(c._ISURL);if(E){A=q("pattern");var F=q("image");A.id=c.createUUID(),q(A,{x:0,y:0,patternUnits:"userSpaceOnUse",height:1,width:1}),q(F,{x:0,y:0,"xlink:href":E[1]}),A.appendChild(F),function(a){c._preload(E[1],function(){var b=this.offsetWidth,c=this.offsetHeight;q(a,{width:b,height:c}),q(F,{width:b,height:c}),d.paper.safari()})}(A),d.paper.defs.appendChild(A),q(i,{fill:"url(#"+A.id+")"}),d.pattern=A,d.pattern&&s(d);break}var G=c.getRGB(p);if(G.error){if(("circle"==d.type||"ellipse"==d.type||"r"!=b(p).charAt())&&r(d,p)){if("opacity"in k||"fill-opacity"in k){var H=c._g.doc.getElementById(i.getAttribute("fill").replace(/^url\(#|\)$/g,l));if(H){var I=H.getElementsByTagName("stop");q(I[I.length-1],{"stop-opacity":("opacity"in k?k.opacity:1)*("fill-opacity"in k?k["fill-opacity"]:1)})}}k.gradient=p,k.fill="none";break}}else delete f.gradient,delete k.gradient,!c.is(k.opacity,"undefined")&&c.is(f.opacity,"undefined")&&q(i,{opacity:k.opacity}),!c.is(k["fill-opacity"],"undefined")&&c.is(f["fill-opacity"],"undefined")&&q(i,{"fill-opacity":k["fill-opacity"]});G[a]("opacity")&&q(i,{"fill-opacity":G.opacity>1?G.opacity/100:G.opacity});case"stroke":G=c.getRGB(p),i.setAttribute(o,G.hex),"stroke"==o&&G[a]("opacity")&&q(i,{"stroke-opacity":G.opacity>1?G.opacity/100:G.opacity}),"stroke"==o&&d._.arrows&&("startString"in d._.arrows&&t(d,d._.arrows.startString),"endString"in d._.arrows&&t(d,d._.arrows.endString,1));break;case"gradient":("circle"==d.type||"ellipse"==d.type||"r"!=b(p).charAt())&&r(d,p);break;case"opacity":k.gradient&&!k[a]("stroke-opacity")&&q(i,{"stroke-opacity":p>1?p/100:p});case"fill-opacity":if(k.gradient){H=c._g.doc.getElementById(i.getAttribute("fill").replace(/^url\(#|\)$/g,l)),H&&(I=H.getElementsByTagName("stop"),q(I[I.length-1],{"stop-opacity":p}));break}default:"font-size"==o&&(p=e(p,10)+"px");var J=o.replace(/(\-.)/g,function(a){return a.substring(1).toUpperCase()});i.style[J]=p,d._.dirty=1,i.setAttribute(o,p)}}y(d,f),i.style.visibility=m},x=1.2,y=function(d,f){if("text"==d.type&&(f[a]("text")||f[a]("font")||f[a]("font-size")||f[a]("x")||f[a]("y"))){var g=d.attrs,h=d.node,i=h.firstChild?e(c._g.doc.defaultView.getComputedStyle(h.firstChild,l).getPropertyValue("font-size"),10):10;
if(f[a]("text")){for(g.text=f.text;h.firstChild;)h.removeChild(h.firstChild);for(var j,k=b(f.text).split("\n"),m=[],n=0,o=k.length;o>n;n++)j=q("tspan"),n&&q(j,{dy:i*x,x:g.x}),j.appendChild(c._g.doc.createTextNode(k[n])),h.appendChild(j),m[n]=j}else for(m=h.getElementsByTagName("tspan"),n=0,o=m.length;o>n;n++)n?q(m[n],{dy:i*x,x:g.x}):q(m[0],{dy:0});q(h,{x:g.x,y:g.y}),d._.dirty=1;var p=d._getBBox(),r=g.y-(p.y+p.height/2);r&&c.is(r,"finite")&&q(m[0],{dy:r})}},z=function(a,b){this[0]=this.node=a,a.raphael=!0,this.id=c._oid++,a.raphaelid=this.id,this.matrix=c.matrix(),this.realPath=null,this.paper=b,this.attrs=this.attrs||{},this._={transform:[],sx:1,sy:1,deg:0,dx:0,dy:0,dirty:1},!b.bottom&&(b.bottom=this),this.prev=b.top,b.top&&(b.top.next=this),b.top=this,this.next=null},A=c.el;z.prototype=A,A.constructor=z,c._engine.path=function(a,b){var c=q("path");b.canvas&&b.canvas.appendChild(c);var d=new z(c,b);return d.type="path",w(d,{fill:"none",stroke:"#000",path:a}),d},A.rotate=function(a,c,e){if(this.removed)return this;if(a=b(a).split(j),a.length-1&&(c=d(a[1]),e=d(a[2])),a=d(a[0]),null==e&&(c=e),null==c||null==e){var f=this.getBBox(1);c=f.x+f.width/2,e=f.y+f.height/2}return this.transform(this._.transform.concat([["r",a,c,e]])),this},A.scale=function(a,c,e,f){if(this.removed)return this;if(a=b(a).split(j),a.length-1&&(c=d(a[1]),e=d(a[2]),f=d(a[3])),a=d(a[0]),null==c&&(c=a),null==f&&(e=f),null==e||null==f)var g=this.getBBox(1);return e=null==e?g.x+g.width/2:e,f=null==f?g.y+g.height/2:f,this.transform(this._.transform.concat([["s",a,c,e,f]])),this},A.translate=function(a,c){return this.removed?this:(a=b(a).split(j),a.length-1&&(c=d(a[1])),a=d(a[0])||0,c=+c||0,this.transform(this._.transform.concat([["t",a,c]])),this)},A.transform=function(b){var d=this._;if(null==b)return d.transform;if(c._extractTransform(this,b),this.clip&&q(this.clip,{transform:this.matrix.invert()}),this.pattern&&s(this),this.node&&q(this.node,{transform:this.matrix}),1!=d.sx||1!=d.sy){var e=this.attrs[a]("stroke-width")?this.attrs["stroke-width"]:1;this.attr({"stroke-width":e})}return this},A.hide=function(){return!this.removed&&this.paper.safari(this.node.style.display="none"),this},A.show=function(){return!this.removed&&this.paper.safari(this.node.style.display=""),this},A.remove=function(){if(!this.removed&&this.node.parentNode){var a=this.paper;a.__set__&&a.__set__.exclude(this),k.unbind("raphael.*.*."+this.id),this.gradient&&a.defs.removeChild(this.gradient),c._tear(this,a),"a"==this.node.parentNode.tagName.toLowerCase()?this.node.parentNode.parentNode.removeChild(this.node.parentNode):this.node.parentNode.removeChild(this.node);for(var b in this)this[b]="function"==typeof this[b]?c._removedFactory(b):null;this.removed=!0}},A._getBBox=function(){if("none"==this.node.style.display){this.show();var a=!0}var b={};try{b=this.node.getBBox()}catch(c){}finally{b=b||{}}return a&&this.hide(),b},A.attr=function(b,d){if(this.removed)return this;if(null==b){var e={};for(var f in this.attrs)this.attrs[a](f)&&(e[f]=this.attrs[f]);return e.gradient&&"none"==e.fill&&(e.fill=e.gradient)&&delete e.gradient,e.transform=this._.transform,e}if(null==d&&c.is(b,"string")){if("fill"==b&&"none"==this.attrs.fill&&this.attrs.gradient)return this.attrs.gradient;if("transform"==b)return this._.transform;for(var g=b.split(j),h={},i=0,l=g.length;l>i;i++)b=g[i],h[b]=b in this.attrs?this.attrs[b]:c.is(this.paper.customAttributes[b],"function")?this.paper.customAttributes[b].def:c._availableAttrs[b];return l-1?h:h[g[0]]}if(null==d&&c.is(b,"array")){for(h={},i=0,l=b.length;l>i;i++)h[b[i]]=this.attr(b[i]);return h}if(null!=d){var m={};m[b]=d}else null!=b&&c.is(b,"object")&&(m=b);for(var n in m)k("raphael.attr."+n+"."+this.id,this,m[n]);for(n in this.paper.customAttributes)if(this.paper.customAttributes[a](n)&&m[a](n)&&c.is(this.paper.customAttributes[n],"function")){var o=this.paper.customAttributes[n].apply(this,[].concat(m[n]));this.attrs[n]=m[n];for(var p in o)o[a](p)&&(m[p]=o[p])}return w(this,m),this},A.toFront=function(){if(this.removed)return this;"a"==this.node.parentNode.tagName.toLowerCase()?this.node.parentNode.parentNode.appendChild(this.node.parentNode):this.node.parentNode.appendChild(this.node);var a=this.paper;return a.top!=this&&c._tofront(this,a),this},A.toBack=function(){if(this.removed)return this;var a=this.node.parentNode;return"a"==a.tagName.toLowerCase()?a.parentNode.insertBefore(this.node.parentNode,this.node.parentNode.parentNode.firstChild):a.firstChild!=this.node&&a.insertBefore(this.node,this.node.parentNode.firstChild),c._toback(this,this.paper),this.paper,this},A.insertAfter=function(a){if(this.removed)return this;var b=a.node||a[a.length-1].node;return b.nextSibling?b.parentNode.insertBefore(this.node,b.nextSibling):b.parentNode.appendChild(this.node),c._insertafter(this,a,this.paper),this},A.insertBefore=function(a){if(this.removed)return this;var b=a.node||a[0].node;return b.parentNode.insertBefore(this.node,b),c._insertbefore(this,a,this.paper),this},A.blur=function(a){var b=this;if(0!==+a){var d=q("filter"),e=q("feGaussianBlur");b.attrs.blur=a,d.id=c.createUUID(),q(e,{stdDeviation:+a||1.5}),d.appendChild(e),b.paper.defs.appendChild(d),b._blur=d,q(b.node,{filter:"url(#"+d.id+")"})}else b._blur&&(b._blur.parentNode.removeChild(b._blur),delete b._blur,delete b.attrs.blur),b.node.removeAttribute("filter");return b},c._engine.circle=function(a,b,c,d){var e=q("circle");a.canvas&&a.canvas.appendChild(e);var f=new z(e,a);return f.attrs={cx:b,cy:c,r:d,fill:"none",stroke:"#000"},f.type="circle",q(e,f.attrs),f},c._engine.rect=function(a,b,c,d,e,f){var g=q("rect");a.canvas&&a.canvas.appendChild(g);var h=new z(g,a);return h.attrs={x:b,y:c,width:d,height:e,r:f||0,rx:f||0,ry:f||0,fill:"none",stroke:"#000"},h.type="rect",q(g,h.attrs),h},c._engine.ellipse=function(a,b,c,d,e){var f=q("ellipse");a.canvas&&a.canvas.appendChild(f);var g=new z(f,a);return g.attrs={cx:b,cy:c,rx:d,ry:e,fill:"none",stroke:"#000"},g.type="ellipse",q(f,g.attrs),g},c._engine.image=function(a,b,c,d,e,f){var g=q("image");q(g,{x:c,y:d,width:e,height:f,preserveAspectRatio:"none"}),g.setAttributeNS(n,"href",b),a.canvas&&a.canvas.appendChild(g);var h=new z(g,a);return h.attrs={x:c,y:d,width:e,height:f,src:b},h.type="image",h},c._engine.text=function(a,b,d,e){var f=q("text");a.canvas&&a.canvas.appendChild(f);var g=new z(f,a);return g.attrs={x:b,y:d,"text-anchor":"middle",text:e,font:c._availableAttrs.font,stroke:"none",fill:"#000"},g.type="text",w(g,g.attrs),g},c._engine.setSize=function(a,b){return this.width=a||this.width,this.height=b||this.height,this.canvas.setAttribute("width",this.width),this.canvas.setAttribute("height",this.height),this._viewBox&&this.setViewBox.apply(this,this._viewBox),this},c._engine.create=function(){var a=c._getContainer.apply(0,arguments),b=a&&a.container,d=a.x,e=a.y,f=a.width,g=a.height;if(!b)throw new Error("SVG container not found.");var h,i=q("svg"),j="overflow:hidden;";return d=d||0,e=e||0,f=f||512,g=g||342,q(i,{height:g,version:1.1,width:f,xmlns:"http://www.w3.org/2000/svg"}),1==b?(i.style.cssText=j+"position:absolute;left:"+d+"px;top:"+e+"px",c._g.doc.body.appendChild(i),h=1):(i.style.cssText=j+"position:relative",b.firstChild?b.insertBefore(i,b.firstChild):b.appendChild(i)),b=new c._Paper,b.width=f,b.height=g,b.canvas=i,b.clear(),b._left=b._top=0,h&&(b.renderfix=function(){}),b.renderfix(),b},c._engine.setViewBox=function(a,b,c,d,e){k("raphael.setViewBox",this,this._viewBox,[a,b,c,d,e]);var f,h,i=g(c/this.width,d/this.height),j=this.top,l=e?"meet":"xMinYMin";for(null==a?(this._vbSize&&(i=1),delete this._vbSize,f="0 0 "+this.width+m+this.height):(this._vbSize=i,f=a+m+b+m+c+m+d),q(this.canvas,{viewBox:f,preserveAspectRatio:l});i&&j;)h="stroke-width"in j.attrs?j.attrs["stroke-width"]:1,j.attr({"stroke-width":h}),j._.dirty=1,j._.dirtyT=1,j=j.prev;return this._viewBox=[a,b,c,d,!!e],this},c.prototype.renderfix=function(){var a,b=this.canvas,c=b.style;try{a=b.getScreenCTM()||b.createSVGMatrix()}catch(d){a=b.createSVGMatrix()}var e=-a.e%1,f=-a.f%1;(e||f)&&(e&&(this._left=(this._left+e)%1,c.left=this._left+"px"),f&&(this._top=(this._top+f)%1,c.top=this._top+"px"))},c.prototype.clear=function(){c.eve("raphael.clear",this);for(var a=this.canvas;a.firstChild;)a.removeChild(a.firstChild);this.bottom=this.top=null,(this.desc=q("desc")).appendChild(c._g.doc.createTextNode("Created with Raphaël "+c.version)),a.appendChild(this.desc),a.appendChild(this.defs=q("defs"))},c.prototype.remove=function(){k("raphael.remove",this),this.canvas.parentNode&&this.canvas.parentNode.removeChild(this.canvas);for(var a in this)this[a]="function"==typeof this[a]?c._removedFactory(a):null};var B=c.st;for(var C in A)A[a](C)&&!B[a](C)&&(B[C]=function(a){return function(){var b=arguments;return this.forEach(function(c){c[a].apply(c,b)})}}(C))}}(),function(){if(c.vml){var a="hasOwnProperty",b=String,d=parseFloat,e=Math,f=e.round,g=e.max,h=e.min,i=e.abs,j="fill",k=/[, ]+/,l=c.eve,m=" progid:DXImageTransform.Microsoft",n=" ",o="",p={M:"m",L:"l",C:"c",Z:"x",m:"t",l:"r",c:"v",z:"x"},q=/([clmz]),?([^clmz]*)/gi,r=/ progid:\S+Blur\([^\)]+\)/g,s=/-?[^,\s-]+/g,t="position:absolute;left:0;top:0;width:1px;height:1px",u=21600,v={path:1,rect:1,image:1},w={circle:1,ellipse:1},x=function(a){var d=/[ahqstv]/gi,e=c._pathToAbsolute;if(b(a).match(d)&&(e=c._path2curve),d=/[clmz]/g,e==c._pathToAbsolute&&!b(a).match(d)){var g=b(a).replace(q,function(a,b,c){var d=[],e="m"==b.toLowerCase(),g=p[b];return c.replace(s,function(a){e&&2==d.length&&(g+=d+p["m"==b?"l":"L"],d=[]),d.push(f(a*u))}),g+d});return g}var h,i,j=e(a);g=[];for(var k=0,l=j.length;l>k;k++){h=j[k],i=j[k][0].toLowerCase(),"z"==i&&(i="x");for(var m=1,r=h.length;r>m;m++)i+=f(h[m]*u)+(m!=r-1?",":o);g.push(i)}return g.join(n)},y=function(a,b,d){var e=c.matrix();return e.rotate(-a,.5,.5),{dx:e.x(b,d),dy:e.y(b,d)}},z=function(a,b,c,d,e,f){var g=a._,h=a.matrix,k=g.fillpos,l=a.node,m=l.style,o=1,p="",q=u/b,r=u/c;if(m.visibility="hidden",b&&c){if(l.coordsize=i(q)+n+i(r),m.rotation=f*(0>b*c?-1:1),f){var s=y(f,d,e);d=s.dx,e=s.dy}if(0>b&&(p+="x"),0>c&&(p+=" y")&&(o=-1),m.flip=p,l.coordorigin=d*-q+n+e*-r,k||g.fillsize){var t=l.getElementsByTagName(j);t=t&&t[0],l.removeChild(t),k&&(s=y(f,h.x(k[0],k[1]),h.y(k[0],k[1])),t.position=s.dx*o+n+s.dy*o),g.fillsize&&(t.size=g.fillsize[0]*i(b)+n+g.fillsize[1]*i(c)),l.appendChild(t)}m.visibility="visible"}};c.toString=function(){return"Your browser doesn’t support SVG. Falling down to VML.\nYou are running Raphaël "+this.version};var A=function(a,c,d){for(var e=b(c).toLowerCase().split("-"),f=d?"end":"start",g=e.length,h="classic",i="medium",j="medium";g--;)switch(e[g]){case"block":case"classic":case"oval":case"diamond":case"open":case"none":h=e[g];break;case"wide":case"narrow":j=e[g];break;case"long":case"short":i=e[g]}var k=a.node.getElementsByTagName("stroke")[0];k[f+"arrow"]=h,k[f+"arrowlength"]=i,k[f+"arrowwidth"]=j},B=function(e,i){e.attrs=e.attrs||{};var l=e.node,m=e.attrs,p=l.style,q=v[e.type]&&(i.x!=m.x||i.y!=m.y||i.width!=m.width||i.height!=m.height||i.cx!=m.cx||i.cy!=m.cy||i.rx!=m.rx||i.ry!=m.ry||i.r!=m.r),r=w[e.type]&&(m.cx!=i.cx||m.cy!=i.cy||m.r!=i.r||m.rx!=i.rx||m.ry!=i.ry),s=e;for(var t in i)i[a](t)&&(m[t]=i[t]);if(q&&(m.path=c._getPath[e.type](e),e._.dirty=1),i.href&&(l.href=i.href),i.title&&(l.title=i.title),i.target&&(l.target=i.target),i.cursor&&(p.cursor=i.cursor),"blur"in i&&e.blur(i.blur),(i.path&&"path"==e.type||q)&&(l.path=x(~b(m.path).toLowerCase().indexOf("r")?c._pathToAbsolute(m.path):m.path),"image"==e.type&&(e._.fillpos=[m.x,m.y],e._.fillsize=[m.width,m.height],z(e,1,1,0,0,0))),"transform"in i&&e.transform(i.transform),r){var y=+m.cx,B=+m.cy,D=+m.rx||+m.r||0,E=+m.ry||+m.r||0;l.path=c.format("ar{0},{1},{2},{3},{4},{1},{4},{1}x",f((y-D)*u),f((B-E)*u),f((y+D)*u),f((B+E)*u),f(y*u)),e._.dirty=1}if("clip-rect"in i){var G=b(i["clip-rect"]).split(k);if(4==G.length){G[2]=+G[2]+ +G[0],G[3]=+G[3]+ +G[1];var H=l.clipRect||c._g.doc.createElement("div"),I=H.style;I.clip=c.format("rect({1}px {2}px {3}px {0}px)",G),l.clipRect||(I.position="absolute",I.top=0,I.left=0,I.width=e.paper.width+"px",I.height=e.paper.height+"px",l.parentNode.insertBefore(H,l),H.appendChild(l),l.clipRect=H)}i["clip-rect"]||l.clipRect&&(l.clipRect.style.clip="auto")}if(e.textpath){var J=e.textpath.style;i.font&&(J.font=i.font),i["font-family"]&&(J.fontFamily='"'+i["font-family"].split(",")[0].replace(/^['"]+|['"]+$/g,o)+'"'),i["font-size"]&&(J.fontSize=i["font-size"]),i["font-weight"]&&(J.fontWeight=i["font-weight"]),i["font-style"]&&(J.fontStyle=i["font-style"])}if("arrow-start"in i&&A(s,i["arrow-start"]),"arrow-end"in i&&A(s,i["arrow-end"],1),null!=i.opacity||null!=i["stroke-width"]||null!=i.fill||null!=i.src||null!=i.stroke||null!=i["stroke-width"]||null!=i["stroke-opacity"]||null!=i["fill-opacity"]||null!=i["stroke-dasharray"]||null!=i["stroke-miterlimit"]||null!=i["stroke-linejoin"]||null!=i["stroke-linecap"]){var K=l.getElementsByTagName(j),L=!1;if(K=K&&K[0],!K&&(L=K=F(j)),"image"==e.type&&i.src&&(K.src=i.src),i.fill&&(K.on=!0),(null==K.on||"none"==i.fill||null===i.fill)&&(K.on=!1),K.on&&i.fill){var M=b(i.fill).match(c._ISURL);if(M){K.parentNode==l&&l.removeChild(K),K.rotate=!0,K.src=M[1],K.type="tile";var N=e.getBBox(1);K.position=N.x+n+N.y,e._.fillpos=[N.x,N.y],c._preload(M[1],function(){e._.fillsize=[this.offsetWidth,this.offsetHeight]})}else K.color=c.getRGB(i.fill).hex,K.src=o,K.type="solid",c.getRGB(i.fill).error&&(s.type in{circle:1,ellipse:1}||"r"!=b(i.fill).charAt())&&C(s,i.fill,K)&&(m.fill="none",m.gradient=i.fill,K.rotate=!1)}if("fill-opacity"in i||"opacity"in i){var O=((+m["fill-opacity"]+1||2)-1)*((+m.opacity+1||2)-1)*((+c.getRGB(i.fill).o+1||2)-1);O=h(g(O,0),1),K.opacity=O,K.src&&(K.color="none")}l.appendChild(K);var P=l.getElementsByTagName("stroke")&&l.getElementsByTagName("stroke")[0],Q=!1;!P&&(Q=P=F("stroke")),(i.stroke&&"none"!=i.stroke||i["stroke-width"]||null!=i["stroke-opacity"]||i["stroke-dasharray"]||i["stroke-miterlimit"]||i["stroke-linejoin"]||i["stroke-linecap"])&&(P.on=!0),("none"==i.stroke||null===i.stroke||null==P.on||0==i.stroke||0==i["stroke-width"])&&(P.on=!1);var R=c.getRGB(i.stroke);P.on&&i.stroke&&(P.color=R.hex),O=((+m["stroke-opacity"]+1||2)-1)*((+m.opacity+1||2)-1)*((+R.o+1||2)-1);var S=.75*(d(i["stroke-width"])||1);if(O=h(g(O,0),1),null==i["stroke-width"]&&(S=m["stroke-width"]),i["stroke-width"]&&(P.weight=S),S&&1>S&&(O*=S)&&(P.weight=1),P.opacity=O,i["stroke-linejoin"]&&(P.joinstyle=i["stroke-linejoin"]||"miter"),P.miterlimit=i["stroke-miterlimit"]||8,i["stroke-linecap"]&&(P.endcap="butt"==i["stroke-linecap"]?"flat":"square"==i["stroke-linecap"]?"square":"round"),i["stroke-dasharray"]){var T={"-":"shortdash",".":"shortdot","-.":"shortdashdot","-..":"shortdashdotdot",". ":"dot","- ":"dash","--":"longdash","- .":"dashdot","--.":"longdashdot","--..":"longdashdotdot"};P.dashstyle=T[a](i["stroke-dasharray"])?T[i["stroke-dasharray"]]:o}Q&&l.appendChild(P)}if("text"==s.type){s.paper.canvas.style.display=o;var U=s.paper.span,V=100,W=m.font&&m.font.match(/\d+(?:\.\d*)?(?=px)/);p=U.style,m.font&&(p.font=m.font),m["font-family"]&&(p.fontFamily=m["font-family"]),m["font-weight"]&&(p.fontWeight=m["font-weight"]),m["font-style"]&&(p.fontStyle=m["font-style"]),W=d(m["font-size"]||W&&W[0])||10,p.fontSize=W*V+"px",s.textpath.string&&(U.innerHTML=b(s.textpath.string).replace(/</g,"&#60;").replace(/&/g,"&#38;").replace(/\n/g,"<br>"));var X=U.getBoundingClientRect();s.W=m.w=(X.right-X.left)/V,s.H=m.h=(X.bottom-X.top)/V,s.X=m.x,s.Y=m.y+s.H/2,("x"in i||"y"in i)&&(s.path.v=c.format("m{0},{1}l{2},{1}",f(m.x*u),f(m.y*u),f(m.x*u)+1));for(var Y=["x","y","text","font","font-family","font-weight","font-style","font-size"],Z=0,$=Y.length;$>Z;Z++)if(Y[Z]in i){s._.dirty=1;break}switch(m["text-anchor"]){case"start":s.textpath.style["v-text-align"]="left",s.bbx=s.W/2;break;case"end":s.textpath.style["v-text-align"]="right",s.bbx=-s.W/2;break;default:s.textpath.style["v-text-align"]="center",s.bbx=0}s.textpath.style["v-text-kern"]=!0}},C=function(a,f,g){a.attrs=a.attrs||{};var h=(a.attrs,Math.pow),i="linear",j=".5 .5";if(a.attrs.gradient=f,f=b(f).replace(c._radial_gradient,function(a,b,c){return i="radial",b&&c&&(b=d(b),c=d(c),h(b-.5,2)+h(c-.5,2)>.25&&(c=e.sqrt(.25-h(b-.5,2))*(2*(c>.5)-1)+.5),j=b+n+c),o}),f=f.split(/\s*\-\s*/),"linear"==i){var k=f.shift();if(k=-d(k),isNaN(k))return null}var l=c._parseDots(f);if(!l)return null;if(a=a.shape||a.node,l.length){a.removeChild(g),g.on=!0,g.method="none",g.color=l[0].color,g.color2=l[l.length-1].color;for(var m=[],p=0,q=l.length;q>p;p++)l[p].offset&&m.push(l[p].offset+n+l[p].color);g.colors=m.length?m.join():"0% "+g.color,"radial"==i?(g.type="gradientTitle",g.focus="100%",g.focussize="0 0",g.focusposition=j,g.angle=0):(g.type="gradient",g.angle=(270-k)%360),a.appendChild(g)}return 1},D=function(a,b){this[0]=this.node=a,a.raphael=!0,this.id=c._oid++,a.raphaelid=this.id,this.X=0,this.Y=0,this.attrs={},this.paper=b,this.matrix=c.matrix(),this._={transform:[],sx:1,sy:1,dx:0,dy:0,deg:0,dirty:1,dirtyT:1},!b.bottom&&(b.bottom=this),this.prev=b.top,b.top&&(b.top.next=this),b.top=this,this.next=null},E=c.el;D.prototype=E,E.constructor=D,E.transform=function(a){if(null==a)return this._.transform;var d,e=this.paper._viewBoxShift,f=e?"s"+[e.scale,e.scale]+"-1-1t"+[e.dx,e.dy]:o;e&&(d=a=b(a).replace(/\.{3}|\u2026/g,this._.transform||o)),c._extractTransform(this,f+a);var g,h=this.matrix.clone(),i=this.skew,j=this.node,k=~b(this.attrs.fill).indexOf("-"),l=!b(this.attrs.fill).indexOf("url(");if(h.translate(1,1),l||k||"image"==this.type)if(i.matrix="1 0 0 1",i.offset="0 0",g=h.split(),k&&g.noRotation||!g.isSimple){j.style.filter=h.toFilter();var m=this.getBBox(),p=this.getBBox(1),q=m.x-p.x,r=m.y-p.y;j.coordorigin=q*-u+n+r*-u,z(this,1,1,q,r,0)}else j.style.filter=o,z(this,g.scalex,g.scaley,g.dx,g.dy,g.rotate);else j.style.filter=o,i.matrix=b(h),i.offset=h.offset();return d&&(this._.transform=d),this},E.rotate=function(a,c,e){if(this.removed)return this;if(null!=a){if(a=b(a).split(k),a.length-1&&(c=d(a[1]),e=d(a[2])),a=d(a[0]),null==e&&(c=e),null==c||null==e){var f=this.getBBox(1);c=f.x+f.width/2,e=f.y+f.height/2}return this._.dirtyT=1,this.transform(this._.transform.concat([["r",a,c,e]])),this}},E.translate=function(a,c){return this.removed?this:(a=b(a).split(k),a.length-1&&(c=d(a[1])),a=d(a[0])||0,c=+c||0,this._.bbox&&(this._.bbox.x+=a,this._.bbox.y+=c),this.transform(this._.transform.concat([["t",a,c]])),this)},E.scale=function(a,c,e,f){if(this.removed)return this;if(a=b(a).split(k),a.length-1&&(c=d(a[1]),e=d(a[2]),f=d(a[3]),isNaN(e)&&(e=null),isNaN(f)&&(f=null)),a=d(a[0]),null==c&&(c=a),null==f&&(e=f),null==e||null==f)var g=this.getBBox(1);return e=null==e?g.x+g.width/2:e,f=null==f?g.y+g.height/2:f,this.transform(this._.transform.concat([["s",a,c,e,f]])),this._.dirtyT=1,this},E.hide=function(){return!this.removed&&(this.node.style.display="none"),this},E.show=function(){return!this.removed&&(this.node.style.display=o),this},E._getBBox=function(){return this.removed?{}:{x:this.X+(this.bbx||0)-this.W/2,y:this.Y-this.H,width:this.W,height:this.H}},E.remove=function(){if(!this.removed&&this.node.parentNode){this.paper.__set__&&this.paper.__set__.exclude(this),c.eve.unbind("raphael.*.*."+this.id),c._tear(this,this.paper),this.node.parentNode.removeChild(this.node),this.shape&&this.shape.parentNode.removeChild(this.shape);for(var a in this)this[a]="function"==typeof this[a]?c._removedFactory(a):null;this.removed=!0}},E.attr=function(b,d){if(this.removed)return this;if(null==b){var e={};for(var f in this.attrs)this.attrs[a](f)&&(e[f]=this.attrs[f]);return e.gradient&&"none"==e.fill&&(e.fill=e.gradient)&&delete e.gradient,e.transform=this._.transform,e}if(null==d&&c.is(b,"string")){if(b==j&&"none"==this.attrs.fill&&this.attrs.gradient)return this.attrs.gradient;for(var g=b.split(k),h={},i=0,m=g.length;m>i;i++)b=g[i],h[b]=b in this.attrs?this.attrs[b]:c.is(this.paper.customAttributes[b],"function")?this.paper.customAttributes[b].def:c._availableAttrs[b];return m-1?h:h[g[0]]}if(this.attrs&&null==d&&c.is(b,"array")){for(h={},i=0,m=b.length;m>i;i++)h[b[i]]=this.attr(b[i]);return h}var n;null!=d&&(n={},n[b]=d),null==d&&c.is(b,"object")&&(n=b);for(var o in n)l("raphael.attr."+o+"."+this.id,this,n[o]);if(n){for(o in this.paper.customAttributes)if(this.paper.customAttributes[a](o)&&n[a](o)&&c.is(this.paper.customAttributes[o],"function")){var p=this.paper.customAttributes[o].apply(this,[].concat(n[o]));this.attrs[o]=n[o];for(var q in p)p[a](q)&&(n[q]=p[q])}n.text&&"text"==this.type&&(this.textpath.string=n.text),B(this,n)}return this},E.toFront=function(){return!this.removed&&this.node.parentNode.appendChild(this.node),this.paper&&this.paper.top!=this&&c._tofront(this,this.paper),this},E.toBack=function(){return this.removed?this:(this.node.parentNode.firstChild!=this.node&&(this.node.parentNode.insertBefore(this.node,this.node.parentNode.firstChild),c._toback(this,this.paper)),this)},E.insertAfter=function(a){return this.removed?this:(a.constructor==c.st.constructor&&(a=a[a.length-1]),a.node.nextSibling?a.node.parentNode.insertBefore(this.node,a.node.nextSibling):a.node.parentNode.appendChild(this.node),c._insertafter(this,a,this.paper),this)},E.insertBefore=function(a){return this.removed?this:(a.constructor==c.st.constructor&&(a=a[0]),a.node.parentNode.insertBefore(this.node,a.node),c._insertbefore(this,a,this.paper),this)},E.blur=function(a){var b=this.node.runtimeStyle,d=b.filter;return d=d.replace(r,o),0!==+a?(this.attrs.blur=a,b.filter=d+n+m+".Blur(pixelradius="+(+a||1.5)+")",b.margin=c.format("-{0}px 0 0 -{0}px",f(+a||1.5))):(b.filter=d,b.margin=0,delete this.attrs.blur),this},c._engine.path=function(a,b){var c=F("shape");c.style.cssText=t,c.coordsize=u+n+u,c.coordorigin=b.coordorigin;var d=new D(c,b),e={fill:"none",stroke:"#000"};a&&(e.path=a),d.type="path",d.path=[],d.Path=o,B(d,e),b.canvas.appendChild(c);var f=F("skew");return f.on=!0,c.appendChild(f),d.skew=f,d.transform(o),d},c._engine.rect=function(a,b,d,e,f,g){var h=c._rectPath(b,d,e,f,g),i=a.path(h),j=i.attrs;return i.X=j.x=b,i.Y=j.y=d,i.W=j.width=e,i.H=j.height=f,j.r=g,j.path=h,i.type="rect",i},c._engine.ellipse=function(a,b,c,d,e){var f=a.path();return f.attrs,f.X=b-d,f.Y=c-e,f.W=2*d,f.H=2*e,f.type="ellipse",B(f,{cx:b,cy:c,rx:d,ry:e}),f},c._engine.circle=function(a,b,c,d){var e=a.path();return e.attrs,e.X=b-d,e.Y=c-d,e.W=e.H=2*d,e.type="circle",B(e,{cx:b,cy:c,r:d}),e},c._engine.image=function(a,b,d,e,f,g){var h=c._rectPath(d,e,f,g),i=a.path(h).attr({stroke:"none"}),k=i.attrs,l=i.node,m=l.getElementsByTagName(j)[0];return k.src=b,i.X=k.x=d,i.Y=k.y=e,i.W=k.width=f,i.H=k.height=g,k.path=h,i.type="image",m.parentNode==l&&l.removeChild(m),m.rotate=!0,m.src=b,m.type="tile",i._.fillpos=[d,e],i._.fillsize=[f,g],l.appendChild(m),z(i,1,1,0,0,0),i},c._engine.text=function(a,d,e,g){var h=F("shape"),i=F("path"),j=F("textpath");d=d||0,e=e||0,g=g||"",i.v=c.format("m{0},{1}l{2},{1}",f(d*u),f(e*u),f(d*u)+1),i.textpathok=!0,j.string=b(g),j.on=!0,h.style.cssText=t,h.coordsize=u+n+u,h.coordorigin="0 0";var k=new D(h,a),l={fill:"#000",stroke:"none",font:c._availableAttrs.font,text:g};k.shape=h,k.path=i,k.textpath=j,k.type="text",k.attrs.text=b(g),k.attrs.x=d,k.attrs.y=e,k.attrs.w=1,k.attrs.h=1,B(k,l),h.appendChild(j),h.appendChild(i),a.canvas.appendChild(h);var m=F("skew");return m.on=!0,h.appendChild(m),k.skew=m,k.transform(o),k},c._engine.setSize=function(a,b){var d=this.canvas.style;return this.width=a,this.height=b,a==+a&&(a+="px"),b==+b&&(b+="px"),d.width=a,d.height=b,d.clip="rect(0 "+a+" "+b+" 0)",this._viewBox&&c._engine.setViewBox.apply(this,this._viewBox),this},c._engine.setViewBox=function(a,b,d,e,f){c.eve("raphael.setViewBox",this,this._viewBox,[a,b,d,e,f]);var h,i,j=this.width,k=this.height,l=1/g(d/j,e/k);return f&&(h=k/e,i=j/d,j>d*h&&(a-=(j-d*h)/2/h),k>e*i&&(b-=(k-e*i)/2/i)),this._viewBox=[a,b,d,e,!!f],this._viewBoxShift={dx:-a,dy:-b,scale:l},this.forEach(function(a){a.transform("...")}),this};var F;c._engine.initWin=function(a){var b=a.document;b.createStyleSheet().addRule(".rvml","behavior:url(#default#VML)");try{!b.namespaces.rvml&&b.namespaces.add("rvml","urn:schemas-microsoft-com:vml"),F=function(a){return b.createElement("<rvml:"+a+' class="rvml">')}}catch(c){F=function(a){return b.createElement("<"+a+' xmlns="urn:schemas-microsoft.com:vml" class="rvml">')}}},c._engine.initWin(c._g.win),c._engine.create=function(){var a=c._getContainer.apply(0,arguments),b=a.container,d=a.height,e=a.width,f=a.x,g=a.y;if(!b)throw new Error("VML container not found.");var h=new c._Paper,i=h.canvas=c._g.doc.createElement("div"),j=i.style;return f=f||0,g=g||0,e=e||512,d=d||342,h.width=e,h.height=d,e==+e&&(e+="px"),d==+d&&(d+="px"),h.coordsize=1e3*u+n+1e3*u,h.coordorigin="0 0",h.span=c._g.doc.createElement("span"),h.span.style.cssText="position:absolute;left:-9999em;top:-9999em;padding:0;margin:0;line-height:1;",i.appendChild(h.span),j.cssText=c.format("top:0;left:0;width:{0};height:{1};display:inline-block;position:relative;clip:rect(0 {0} {1} 0);overflow:hidden",e,d),1==b?(c._g.doc.body.appendChild(i),j.left=f+"px",j.top=g+"px",j.position="absolute"):b.firstChild?b.insertBefore(i,b.firstChild):b.appendChild(i),h.renderfix=function(){},h},c.prototype.clear=function(){c.eve("raphael.clear",this),this.canvas.innerHTML=o,this.span=c._g.doc.createElement("span"),this.span.style.cssText="position:absolute;left:-9999em;top:-9999em;padding:0;margin:0;line-height:1;display:inline;",this.canvas.appendChild(this.span),this.bottom=this.top=null},c.prototype.remove=function(){c.eve("raphael.remove",this),this.canvas.parentNode.removeChild(this.canvas);for(var a in this)this[a]="function"==typeof this[a]?c._removedFactory(a):null;return!0};var G=c.st;for(var H in E)E[a](H)&&!G[a](H)&&(G[H]=function(a){return function(){var b=arguments;return this.forEach(function(c){c[a].apply(c,b)})}}(H))}}(),B.was?A.win.Raphael=c:Raphael=c,c});"use strict";
var Slick = (() => {
  var __defProp = Object.defineProperty;
  var __getOwnPropDesc = Object.getOwnPropertyDescriptor;
  var __getOwnPropNames = Object.getOwnPropertyNames;
  var __hasOwnProp = Object.prototype.hasOwnProperty;
  var __defNormalProp = (obj, key, value) => key in obj ? __defProp(obj, key, { enumerable: !0, configurable: !0, writable: !0, value }) : obj[key] = value;
  var __export = (target, all) => {
    for (var name in all)
      __defProp(target, name, { get: all[name], enumerable: !0 });
  }, __copyProps = (to, from, except, desc) => {
    if (from && typeof from == "object" || typeof from == "function")
      for (let key of __getOwnPropNames(from))
        !__hasOwnProp.call(to, key) && key !== except && __defProp(to, key, { get: () => from[key], enumerable: !(desc = __getOwnPropDesc(from, key)) || desc.enumerable });
    return to;
  };
  var __toCommonJS = (mod) => __copyProps(__defProp({}, "__esModule", { value: !0 }), mod);
  var __publicField = (obj, key, value) => (__defNormalProp(obj, typeof key != "symbol" ? key + "" : key, value), value);

  // src/slick.core.ts
  var slick_core_exports = {};
  __export(slick_core_exports, {
    BindingEventService: () => BindingEventService,
    ColAutosizeMode: () => ColAutosizeMode,
    EditorLock: () => EditorLock,
    Event: () => Event,
    EventData: () => EventData,
    EventHandler: () => EventHandler,
    GlobalEditorLock: () => GlobalEditorLock,
    GridAutosizeColsMode: () => GridAutosizeColsMode,
    Group: () => Group,
    GroupTotals: () => GroupTotals,
    NonDataRow: () => NonDataRow,
    Range: () => Range,
    RegexSanitizer: () => RegexSanitizer,
    RowSelectionMode: () => RowSelectionMode,
    SlickEditorLock: () => SlickEditorLock,
    SlickEvent: () => SlickEvent,
    SlickEventData: () => SlickEventData,
    SlickEventHandler: () => SlickEventHandler,
    SlickGlobalEditorLock: () => SlickGlobalEditorLock,
    SlickGroup: () => SlickGroup,
    SlickGroupTotals: () => SlickGroupTotals,
    SlickNonDataItem: () => SlickNonDataItem,
    SlickRange: () => SlickRange,
    Utils: () => Utils,
    ValueFilterMode: () => ValueFilterMode,
    WidthEvalMode: () => WidthEvalMode,
    keyCode: () => keyCode,
    preClickClassName: () => preClickClassName
  });
  var SlickEventData = class {
    constructor(event, args) {
      this.event = event;
      this.args = args;
      __publicField(this, "_isPropagationStopped", !1);
      __publicField(this, "_isImmediatePropagationStopped", !1);
      __publicField(this, "_isDefaultPrevented", !1);
      __publicField(this, "returnValues", []);
      __publicField(this, "returnValue");
      __publicField(this, "target");
      __publicField(this, "nativeEvent");
      __publicField(this, "arguments_");
      if (this.nativeEvent = event, this.arguments_ = args, event) {
        let eventProps = [
          "altKey",
          "ctrlKey",
          "metaKey",
          "shiftKey",
          "key",
          "keyCode",
          "clientX",
          "clientY",
          "offsetX",
          "offsetY",
          "pageX",
          "pageY",
          "bubbles",
          "type",
          "which",
          "x",
          "y"
        ];
        for (let key of eventProps)
          this[key] = event[key];
      }
      this.target = this.nativeEvent ? this.nativeEvent.target : void 0;
    }
    /**
     * Stops event from propagating up the DOM tree.
     * @method stopPropagation
     */
    stopPropagation() {
      var _a;
      this._isPropagationStopped = !0, (_a = this.nativeEvent) == null || _a.stopPropagation();
    }
    /**
     * Returns whether stopPropagation was called on this event object.
     * @method isPropagationStopped
     * @return {Boolean}
     */
    isPropagationStopped() {
      return this._isPropagationStopped;
    }
    /**
     * Prevents the rest of the handlers from being executed.
     * @method stopImmediatePropagation
     */
    stopImmediatePropagation() {
      this._isImmediatePropagationStopped = !0, this.nativeEvent && this.nativeEvent.stopImmediatePropagation();
    }
    /**
     * Returns whether stopImmediatePropagation was called on this event object.\
     * @method isImmediatePropagationStopped
     * @return {Boolean}
     */
    isImmediatePropagationStopped() {
      return this._isImmediatePropagationStopped;
    }
    getNativeEvent() {
      return this.nativeEvent;
    }
    preventDefault() {
      this.nativeEvent && this.nativeEvent.preventDefault(), this._isDefaultPrevented = !0;
    }
    isDefaultPrevented() {
      return this.nativeEvent ? this.nativeEvent.defaultPrevented : this._isDefaultPrevented;
    }
    addReturnValue(value) {
      this.returnValues.push(value), this.returnValue === void 0 && value !== void 0 && (this.returnValue = value);
    }
    getReturnValue() {
      return this.returnValue;
    }
    getArguments() {
      return this.arguments_;
    }
  }, SlickEvent = class {
    constructor() {
      __publicField(this, "handlers", []);
    }
    /**
     * Adds an event handler to be called when the event is fired.
     * <p>Event handler will receive two arguments - an <code>EventData</code> and the <code>data</code>
     * object the event was fired with.<p>
     * @method subscribe
     * @param fn {Function} Event handler.
     */
    subscribe(fn) {
      this.handlers.push(fn);
    }
    /**
     * Removes an event handler added with <code>subscribe(fn)</code>.
     * @method unsubscribe
     * @param fn {Function} Event handler to be removed.
     */
    unsubscribe(fn) {
      for (let i = this.handlers.length - 1; i >= 0; i--)
        this.handlers[i] === fn && this.handlers.splice(i, 1);
    }
    /**
     * Fires an event notifying all subscribers.
     * @method notify
     * @param args {Object} Additional data object to be passed to all handlers.
     * @param e {EventData}
     *      Optional.
     *      An <code>EventData</code> object to be passed to all handlers.
     *      For DOM events, an existing W3C event object can be passed in.
     * @param scope {Object}
     *      Optional.
     *      The scope ("this") within which the handler will be executed.
     *      If not specified, the scope will be set to the <code>Event</code> instance.
     */
    notify(args, evt, scope) {
      let sed = evt instanceof SlickEventData ? evt : new SlickEventData(evt, args);
      scope = scope || this;
      for (let i = 0; i < this.handlers.length && !(sed.isPropagationStopped() || sed.isImmediatePropagationStopped()); i++) {
        let returnValue = this.handlers[i].call(scope, sed, args);
        sed.addReturnValue(returnValue);
      }
      return sed;
    }
  }, SlickEventHandler = class {
    constructor() {
      __publicField(this, "handlers", []);
    }
    subscribe(event, handler) {
      return this.handlers.push({ event, handler }), event.subscribe(handler), this;
    }
    unsubscribe(event, handler) {
      let i = this.handlers.length;
      for (; i--; )
        if (this.handlers[i].event === event && this.handlers[i].handler === handler) {
          this.handlers.splice(i, 1), event.unsubscribe(handler);
          return;
        }
      return this;
    }
    unsubscribeAll() {
      let i = this.handlers.length;
      for (; i--; )
        this.handlers[i].event.unsubscribe(this.handlers[i].handler);
      return this.handlers = [], this;
    }
  }, SlickRange = class {
    constructor(fromRow, fromCell, toRow, toCell) {
      __publicField(this, "fromRow");
      __publicField(this, "fromCell");
      __publicField(this, "toCell");
      __publicField(this, "toRow");
      toRow === void 0 && toCell === void 0 && (toRow = fromRow, toCell = fromCell), this.fromRow = Math.min(fromRow, toRow), this.fromCell = Math.min(fromCell, toCell), this.toCell = Math.max(fromCell, toCell), this.toRow = Math.max(fromRow, toRow);
    }
    /**
     * Returns whether a range represents a single row.
     * @method isSingleRow
     * @return {Boolean}
     */
    isSingleRow() {
      return this.fromRow === this.toRow;
    }
    /**
     * Returns whether a range represents a single cell.
     * @method isSingleCell
     * @return {Boolean}
     */
    isSingleCell() {
      return this.fromRow === this.toRow && this.fromCell === this.toCell;
    }
    /**
     * Returns whether a range contains a given cell.
     * @method contains
     * @param row {Integer}
     * @param cell {Integer}
     * @return {Boolean}
     */
    contains(row, cell) {
      return row >= this.fromRow && row <= this.toRow && cell >= this.fromCell && cell <= this.toCell;
    }
    /**
     * Returns a readable representation of a range.
     * @method toString
     * @return {String}
     */
    toString() {
      return this.isSingleCell() ? `(${this.fromRow}:${this.fromCell})` : `(${this.fromRow}:${this.fromCell} - ${this.toRow}:${this.toCell})`;
    }
  }, SlickNonDataItem = class {
    constructor() {
      __publicField(this, "__nonDataRow", !0);
    }
  }, SlickGroup = class extends SlickNonDataItem {
    constructor() {
      super();
      __publicField(this, "__group", !0);
      /**
       * Grouping level, starting with 0.
       * @property level
       * @type {Number}
       */
      __publicField(this, "level", 0);
      /**
       * Number of rows in the group.
       * @property count
       * @type {Integer}
       */
      __publicField(this, "count", 0);
      /**
       * Grouping value.
       * @property value
       * @type {Object}
       */
      __publicField(this, "value", null);
      /**
       * Formatted display value of the group.
       * @property title
       * @type {String}
       */
      __publicField(this, "title", null);
      /**
       * Whether a group is collapsed.
       * @property collapsed
       * @type {Boolean}
       */
      __publicField(this, "collapsed", !1);
      /**
       * Whether a group selection checkbox is checked.
       * @property selectChecked
       * @type {Boolean}
       */
      __publicField(this, "selectChecked", !1);
      /**
       * GroupTotals, if any.
       * @property totals
       * @type {GroupTotals}
       */
      __publicField(this, "totals", null);
      /**
       * Rows that are part of the group.
       * @property rows
       * @type {Array}
       */
      __publicField(this, "rows", []);
      /**
       * Sub-groups that are part of the group.
       * @property groups
       * @type {Array}
       */
      __publicField(this, "groups", null);
      /**
       * A unique key used to identify the group.  This key can be used in calls to DataView
       * collapseGroup() or expandGroup().
       * @property groupingKey
       * @type {Object}
       */
      __publicField(this, "groupingKey", null);
    }
    /**
     * Compares two Group instances.
     * @method equals
     * @return {Boolean}
     * @param group {Group} Group instance to compare to.
     */
    equals(group) {
      return this.value === group.value && this.count === group.count && this.collapsed === group.collapsed && this.title === group.title;
    }
  }, SlickGroupTotals = class extends SlickNonDataItem {
    constructor() {
      super();
      __publicField(this, "__groupTotals", !0);
      /**
       * Parent Group.
       * @param group
       * @type {Group}
       */
      __publicField(this, "group", null);
      /**
       * Whether the totals have been fully initialized / calculated.
       * Will be set to false for lazy-calculated group totals.
       * @param initialized
       * @type {Boolean}
       */
      __publicField(this, "initialized", !1);
    }
  }, SlickEditorLock = class {
    constructor() {
      __publicField(this, "activeEditController", null);
    }
    /**
     * Returns true if a specified edit controller is active (has the edit lock).
     * If the parameter is not specified, returns true if any edit controller is active.
     * @method isActive
     * @param editController {EditController}
     * @return {Boolean}
     */
    isActive(editController) {
      return editController ? this.activeEditController === editController : this.activeEditController !== null;
    }
    /**
     * Sets the specified edit controller as the active edit controller (acquire edit lock).
     * If another edit controller is already active, and exception will be throw new Error(.
     * @method activate
     * @param editController {EditController} edit controller acquiring the lock
     */
    activate(editController) {
      if (editController !== this.activeEditController) {
        if (this.activeEditController !== null)
          throw new Error("Slick.EditorLock.activate: an editController is still active, can't activate another editController");
        if (!editController.commitCurrentEdit)
          throw new Error("Slick.EditorLock.activate: editController must implement .commitCurrentEdit()");
        if (!editController.cancelCurrentEdit)
          throw new Error("Slick.EditorLock.activate: editController must implement .cancelCurrentEdit()");
        this.activeEditController = editController;
      }
    }
    /**
     * Unsets the specified edit controller as the active edit controller (release edit lock).
     * If the specified edit controller is not the active one, an exception will be throw new Error(.
     * @method deactivate
     * @param editController {EditController} edit controller releasing the lock
     */
    deactivate(editController) {
      if (this.activeEditController) {
        if (this.activeEditController !== editController)
          throw new Error("Slick.EditorLock.deactivate: specified editController is not the currently active one");
        this.activeEditController = null;
      }
    }
    /**
     * Attempts to commit the current edit by calling "commitCurrentEdit" method on the active edit
     * controller and returns whether the commit attempt was successful (commit may fail due to validation
     * errors, etc.).  Edit controller's "commitCurrentEdit" must return true if the commit has succeeded
     * and false otherwise.  If no edit controller is active, returns true.
     * @method commitCurrentEdit
     * @return {Boolean}
     */
    commitCurrentEdit() {
      return this.activeEditController ? this.activeEditController.commitCurrentEdit() : !0;
    }
    /**
     * Attempts to cancel the current edit by calling "cancelCurrentEdit" method on the active edit
     * controller and returns whether the edit was successfully cancelled.  If no edit controller is
     * active, returns true.
     * @method cancelCurrentEdit
     * @return {Boolean}
     */
    cancelCurrentEdit() {
      return this.activeEditController ? this.activeEditController.cancelCurrentEdit() : !0;
    }
  };
  function regexSanitizer(dirtyHtml) {
    return dirtyHtml.replace(/(\b)(on[a-z]+)(\s*)=|javascript:([^>]*)[^>]*|(<\s*)(\/*)script([<>]*).*(<\s*)(\/*)script(>*)|(&lt;)(\/*)(script|script defer)(.*)(&gt;|&gt;">)/gi, "");
  }
  var BindingEventService = class {
    constructor() {
      __publicField(this, "_boundedEvents", []);
    }
    getBoundedEvents() {
      return this._boundedEvents;
    }
    destroy() {
      this.unbindAll();
    }
    /** Bind an event listener to any element */
    bind(element, eventName, listener, options, groupName = "") {
      element.addEventListener(eventName, listener, options), this._boundedEvents.push({ element, eventName, listener, groupName });
    }
    /** Unbind all will remove every every event handlers that were bounded earlier */
    unbind(element, eventName, listener) {
      element != null && element.removeEventListener && element.removeEventListener(eventName, listener);
    }
    unbindByEventName(element, eventName) {
      let boundedEvent = this._boundedEvents.find((e) => e.element === element && e.eventName === eventName);
      boundedEvent && this.unbind(boundedEvent.element, boundedEvent.eventName, boundedEvent.listener);
    }
    /**
     * Unbind all event listeners that were bounded, optionally provide a group name to unbind all listeners assigned to that specific group only.
     */
    unbindAll(groupName) {
      if (groupName) {
        let groupNames = Array.isArray(groupName) ? groupName : [groupName];
        for (let i = this._boundedEvents.length - 1; i >= 0; --i) {
          let boundedEvent = this._boundedEvents[i];
          if (groupNames.some((g) => g === boundedEvent.groupName)) {
            let { element, eventName, listener } = boundedEvent;
            this.unbind(element, eventName, listener), this._boundedEvents.splice(i, 1);
          }
        }
      } else
        for (; this._boundedEvents.length > 0; ) {
          let boundedEvent = this._boundedEvents.pop(), { element, eventName, listener } = boundedEvent;
          this.unbind(element, eventName, listener);
        }
    }
  }, _Utils = class _Utils {
    static isFunction(obj) {
      return typeof obj == "function" && typeof obj.nodeType != "number" && typeof obj.item != "function";
    }
    static isPlainObject(obj) {
      if (!obj || _Utils.toString.call(obj) !== "[object Object]")
        return !1;
      let proto = _Utils.getProto(obj);
      if (!proto)
        return !0;
      let Ctor = _Utils.hasOwn.call(proto, "constructor") && proto.constructor;
      return typeof Ctor == "function" && _Utils.fnToString.call(Ctor) === _Utils.ObjectFunctionString;
    }
    static calculateAvailableSpace(element) {
      let bottom = 0, top = 0, left = 0, right = 0, windowHeight = window.innerHeight || 0, windowWidth = window.innerWidth || 0, scrollPosition = _Utils.windowScrollPosition(), pageScrollTop = scrollPosition.top, pageScrollLeft = scrollPosition.left, elmOffset = _Utils.offset(element);
      if (elmOffset) {
        let elementOffsetTop = elmOffset.top || 0, elementOffsetLeft = elmOffset.left || 0;
        top = elementOffsetTop - pageScrollTop, bottom = windowHeight - (elementOffsetTop - pageScrollTop), left = elementOffsetLeft - pageScrollLeft, right = windowWidth - (elementOffsetLeft - pageScrollLeft);
      }
      return { top, bottom, left, right };
    }
    static extend(...args) {
      let options, name, src, copy, copyIsArray, clone, target = args[0], i = 1, deep = !1, length = args.length;
      for (typeof target == "boolean" ? (deep = target, target = args[i] || {}, i++) : target = target || {}, typeof target != "object" && !_Utils.isFunction(target) && (target = {}), i === length && (target = this, i--); i < length; i++)
        if (_Utils.isDefined(options = args[i]))
          for (name in options)
            copy = options[name], !(name === "__proto__" || target === copy) && (deep && copy && (_Utils.isPlainObject(copy) || (copyIsArray = Array.isArray(copy))) ? (src = target[name], copyIsArray && !Array.isArray(src) ? clone = [] : !copyIsArray && !_Utils.isPlainObject(src) ? clone = {} : clone = src, copyIsArray = !1, target[name] = _Utils.extend(deep, clone, copy)) : copy !== void 0 && (target[name] = copy));
      return target;
    }
    /**
     * Create a DOM Element with any optional attributes or properties.
     * It will only accept valid DOM element properties that `createElement` would accept.
     * For example: `createDomElement('div', { className: 'my-css-class' })`,
     * for style or dataset you need to use nested object `{ style: { display: 'none' }}
     * The last argument is to optionally append the created element to a parent container element.
     * @param {String} tagName - html tag
     * @param {Object} options - element properties
     * @param {[HTMLElement]} appendToParent - parent element to append to
     */
    static createDomElement(tagName, elementOptions, appendToParent) {
      let elm = document.createElement(tagName);
      return elementOptions && Object.keys(elementOptions).forEach((elmOptionKey) => {
        elmOptionKey === "innerHTML" && console.warn(`[SlickGrid] For better CSP (Content Security Policy) support, do not use "innerHTML" directly in "createDomElement('${tagName}', { innerHTML: 'some html'})", it is better as separate assignment: "const elm = createDomElement('span'); elm.innerHTML = 'some html';"`);
        let elmValue = elementOptions[elmOptionKey];
        typeof elmValue == "object" ? Object.assign(elm[elmOptionKey], elmValue) : elm[elmOptionKey] = elementOptions[elmOptionKey];
      }), appendToParent != null && appendToParent.appendChild && appendToParent.appendChild(elm), elm;
    }
    static emptyElement(element) {
      for (; element != null && element.firstChild; )
        element.removeChild(element.firstChild);
      return element;
    }
    static innerSize(elm, type) {
      let size = 0;
      if (elm) {
        let clientSize = type === "height" ? "clientHeight" : "clientWidth", sides = type === "height" ? ["top", "bottom"] : ["left", "right"];
        size = elm[clientSize];
        for (let side of sides) {
          let sideSize = parseFloat(_Utils.getElementProp(elm, `padding-${side}`) || "") || 0;
          size -= sideSize;
        }
      }
      return size;
    }
    static isDefined(value) {
      return value != null && value !== "";
    }
    static getElementProp(elm, property) {
      return elm != null && elm.getComputedStyle ? window.getComputedStyle(elm, null).getPropertyValue(property) : null;
    }
    static isEmptyObject(obj) {
      return obj == null ? !0 : Object.entries(obj).length === 0;
    }
    static noop() {
    }
    static offset(el) {
      if (!el || !el.getBoundingClientRect)
        return;
      let box = el.getBoundingClientRect(), docElem = document.documentElement;
      return {
        top: box.top + window.pageYOffset - docElem.clientTop,
        left: box.left + window.pageXOffset - docElem.clientLeft
      };
    }
    static windowScrollPosition() {
      return {
        left: window.pageXOffset || document.documentElement.scrollLeft || 0,
        top: window.pageYOffset || document.documentElement.scrollTop || 0
      };
    }
    static width(el, value) {
      if (!(!el || !el.getBoundingClientRect)) {
        if (value === void 0)
          return el.getBoundingClientRect().width;
        _Utils.setStyleSize(el, "width", value);
      }
    }
    static height(el, value) {
      if (el) {
        if (value === void 0)
          return el.getBoundingClientRect().height;
        _Utils.setStyleSize(el, "height", value);
      }
    }
    static setStyleSize(el, style, val) {
      typeof val == "function" ? val = val() : typeof val == "string" ? el.style[style] = val : el.style[style] = val + "px";
    }
    static contains(parent, child) {
      return !parent || !child ? !1 : !_Utils.parents(child).every((p) => parent !== p);
    }
    static isHidden(el) {
      return el.offsetWidth === 0 && el.offsetHeight === 0;
    }
    static parents(el, selector) {
      let parents = [], visible = selector === ":visible", hidden = selector === ":hidden";
      for (; (el = el.parentNode) && el !== document && !(!el || !el.parentNode); )
        hidden ? _Utils.isHidden(el) && parents.push(el) : visible ? _Utils.isHidden(el) || parents.push(el) : (!selector || el.matches(selector)) && parents.push(el);
      return parents;
    }
    static toFloat(value) {
      let x = parseFloat(value);
      return isNaN(x) ? 0 : x;
    }
    static show(el, type = "") {
      Array.isArray(el) ? el.forEach((e) => e.style.display = type) : el.style.display = type;
    }
    static hide(el) {
      Array.isArray(el) ? el.forEach(function(e) {
        e.style.display = "none";
      }) : el.style.display = "none";
    }
    static slideUp(el, callback) {
      return _Utils.slideAnimation(el, "slideUp", callback);
    }
    static slideDown(el, callback) {
      return _Utils.slideAnimation(el, "slideDown", callback);
    }
    static slideAnimation(el, slideDirection, callback) {
      if (window.jQuery !== void 0) {
        window.jQuery(el)[slideDirection]("fast", callback);
        return;
      }
      slideDirection === "slideUp" ? _Utils.hide(el) : _Utils.show(el), callback();
    }
    static applyDefaults(targetObj, srcObj) {
      for (let key in srcObj)
        srcObj.hasOwnProperty(key) && !targetObj.hasOwnProperty(key) && (targetObj[key] = srcObj[key]);
    }
  };
  // jQuery's extend
  __publicField(_Utils, "getProto", Object.getPrototypeOf), __publicField(_Utils, "class2type", {}), __publicField(_Utils, "toString", _Utils.class2type.toString), __publicField(_Utils, "hasOwn", _Utils.class2type.hasOwnProperty), __publicField(_Utils, "fnToString", _Utils.hasOwn.toString), __publicField(_Utils, "ObjectFunctionString", _Utils.fnToString.call(Object)), __publicField(_Utils, "storage", {
    // https://stackoverflow.com/questions/29222027/vanilla-alternative-to-jquery-data-function-any-native-javascript-alternati
    _storage: /* @__PURE__ */ new WeakMap(),
    // eslint-disable-next-line object-shorthand
    put: function(element, key, obj) {
      this._storage.has(element) || this._storage.set(element, /* @__PURE__ */ new Map()), this._storage.get(element).set(key, obj);
    },
    // eslint-disable-next-line object-shorthand
    get: function(element, key) {
      let el = this._storage.get(element);
      return el ? el.get(key) : null;
    },
    // eslint-disable-next-line object-shorthand
    remove: function(element, key) {
      let ret = this._storage.get(element).delete(key);
      return this._storage.get(element).size !== 0 && this._storage.delete(element), ret;
    }
  });
  var Utils = _Utils, SlickGlobalEditorLock = new SlickEditorLock(), SlickCore = {
    Event: SlickEvent,
    EventData: SlickEventData,
    EventHandler: SlickEventHandler,
    Range: SlickRange,
    NonDataRow: SlickNonDataItem,
    Group: SlickGroup,
    GroupTotals: SlickGroupTotals,
    EditorLock: SlickEditorLock,
    RegexSanitizer: regexSanitizer,
    /**
     * A global singleton editor lock.
     * @class GlobalEditorLock
     * @static
     * @constructor
     */
    GlobalEditorLock: SlickGlobalEditorLock,
    keyCode: {
      SPACE: 8,
      BACKSPACE: 8,
      DELETE: 46,
      DOWN: 40,
      END: 35,
      ENTER: 13,
      ESCAPE: 27,
      HOME: 36,
      INSERT: 45,
      LEFT: 37,
      PAGE_DOWN: 34,
      PAGE_UP: 33,
      RIGHT: 39,
      TAB: 9,
      UP: 38,
      A: 65
    },
    preClickClassName: "slick-edit-preclick",
    GridAutosizeColsMode: {
      None: "NOA",
      LegacyOff: "LOF",
      LegacyForceFit: "LFF",
      IgnoreViewport: "IGV",
      FitColsToViewport: "FCV",
      FitViewportToCols: "FVC"
    },
    ColAutosizeMode: {
      Locked: "LCK",
      Guide: "GUI",
      Content: "CON",
      ContentExpandOnly: "CXO",
      ContentIntelligent: "CTI"
    },
    RowSelectionMode: {
      FirstRow: "FS1",
      FirstNRows: "FSN",
      AllRows: "ALL",
      LastRow: "LS1"
    },
    ValueFilterMode: {
      None: "NONE",
      DeDuplicate: "DEDP",
      GetGreatestAndSub: "GR8T",
      GetLongestTextAndSub: "LNSB",
      GetLongestText: "LNSC"
    },
    WidthEvalMode: {
      Auto: "AUTO",
      TextOnly: "CANV",
      HTML: "HTML"
    }
  }, {
    EditorLock,
    Event,
    EventData,
    EventHandler,
    Group,
    GroupTotals,
    NonDataRow,
    Range,
    RegexSanitizer,
    GlobalEditorLock,
    keyCode,
    preClickClassName,
    GridAutosizeColsMode,
    ColAutosizeMode,
    RowSelectionMode,
    ValueFilterMode,
    WidthEvalMode
  } = SlickCore;
  typeof global != "undefined" && window.Slick && (global.Slick = window.Slick);
  return __toCommonJS(slick_core_exports);
})();
//# sourceMappingURL=slick.core.js.map
"use strict";
(() => {
  // src/slick.interactions.ts
  var Utils = Slick.Utils;
  function Draggable(options) {
    let { containerElement } = options, { onDragInit, onDragStart, onDrag, onDragEnd } = options, element, startX, startY, deltaX, deltaY, dragStarted;
    containerElement || (containerElement = document.body);
    let originaldd = {
      dragSource: containerElement,
      dragHandle: null
    };
    function init() {
      containerElement && (containerElement.addEventListener("mousedown", userPressed), containerElement.addEventListener("touchstart", userPressed));
    }
    function executeDragCallbackWhenDefined(callback, evt, dd) {
      typeof callback == "function" && callback(evt, dd);
    }
    function destroy() {
      containerElement && (containerElement.removeEventListener("mousedown", userPressed), containerElement.removeEventListener("touchstart", userPressed));
    }
    function userPressed(event) {
      var _a, _b;
      element = event.target;
      let targetEvent = (_b = (_a = event == null ? void 0 : event.touches) == null ? void 0 : _a[0]) != null ? _b : event, { target } = targetEvent;
      if (!options.allowDragFrom || options.allowDragFrom && element.matches(options.allowDragFrom) || options.allowDragFromClosest && element.closest(options.allowDragFromClosest)) {
        originaldd.dragHandle = element;
        let winScrollPos = Utils.windowScrollPosition();
        startX = winScrollPos.left + targetEvent.clientX, startY = winScrollPos.top + targetEvent.clientY, deltaX = targetEvent.clientX - targetEvent.clientX, deltaY = targetEvent.clientY - targetEvent.clientY, originaldd = Object.assign(originaldd, { deltaX, deltaY, startX, startY, target }), executeDragCallbackWhenDefined(onDragInit, event, originaldd), document.body.addEventListener("mousemove", userMoved), document.body.addEventListener("touchmove", userMoved), document.body.addEventListener("mouseup", userReleased), document.body.addEventListener("touchend", userReleased), document.body.addEventListener("touchcancel", userReleased);
      }
    }
    function userMoved(event) {
      var _a, _b;
      let targetEvent = (_b = (_a = event == null ? void 0 : event.touches) == null ? void 0 : _a[0]) != null ? _b : event;
      deltaX = targetEvent.clientX - startX, deltaY = targetEvent.clientY - startY;
      let { target } = targetEvent;
      dragStarted || (originaldd = Object.assign(originaldd, { deltaX, deltaY, startX, startY, target }), executeDragCallbackWhenDefined(onDragStart, event, originaldd), dragStarted = !0), originaldd = Object.assign(originaldd, { deltaX, deltaY, startX, startY, target }), executeDragCallbackWhenDefined(onDrag, event, originaldd);
    }
    function userReleased(event) {
      if (document.body.removeEventListener("mousemove", userMoved), document.body.removeEventListener("touchmove", userMoved), document.body.removeEventListener("mouseup", userReleased), document.body.removeEventListener("touchend", userReleased), document.body.removeEventListener("touchcancel", userReleased), dragStarted) {
        let { target } = event;
        originaldd = Object.assign(originaldd, { target }), executeDragCallbackWhenDefined(onDragEnd, event, originaldd), dragStarted = !1;
      }
    }
    return init(), { destroy };
  }
  function MouseWheel(options) {
    let { element, onMouseWheel } = options;
    function destroy() {
      element.removeEventListener("wheel", wheelHandler), element.removeEventListener("mousewheel", wheelHandler);
    }
    function init() {
      element.addEventListener("wheel", wheelHandler), element.addEventListener("mousewheel", wheelHandler);
    }
    function wheelHandler(event) {
      let orgEvent = event || window.event, delta = 0, deltaX = 0, deltaY = 0;
      orgEvent.wheelDelta && (delta = orgEvent.wheelDelta / 120), orgEvent.detail && (delta = -orgEvent.detail / 3), deltaY = delta, orgEvent.axis !== void 0 && orgEvent.axis === orgEvent.HORIZONTAL_AXIS && (deltaY = 0, deltaX = -1 * delta), orgEvent.wheelDeltaY !== void 0 && (deltaY = orgEvent.wheelDeltaY / 120), orgEvent.wheelDeltaX !== void 0 && (deltaX = -1 * orgEvent.wheelDeltaX / 120), typeof onMouseWheel == "function" && onMouseWheel(event, delta, deltaX, deltaY);
    }
    return init(), { destroy };
  }
  function Resizable(options) {
    let { resizeableElement, resizeableHandleElement, onResizeStart, onResize, onResizeEnd } = options;
    if (!resizeableHandleElement || typeof resizeableHandleElement.addEventListener != "function")
      throw new Error("[Slick.Resizable] You did not provide a valid html element that will be used for the handle to resize.");
    function init() {
      resizeableHandleElement.addEventListener("mousedown", resizeStartHandler), resizeableHandleElement.addEventListener("touchstart", resizeStartHandler);
    }
    function destroy() {
      typeof (resizeableHandleElement == null ? void 0 : resizeableHandleElement.removeEventListener) == "function" && (resizeableHandleElement.removeEventListener("mousedown", resizeStartHandler), resizeableHandleElement.removeEventListener("touchstart", resizeStartHandler));
    }
    function executeResizeCallbackWhenDefined(callback, e) {
      typeof callback == "function" && callback(e, { resizeableElement, resizeableHandleElement });
    }
    function resizeStartHandler(e) {
      e.preventDefault();
      let event = e.touches ? e.changedTouches[0] : e;
      executeResizeCallbackWhenDefined(onResizeStart, event), document.body.addEventListener("mousemove", resizingHandler), document.body.addEventListener("mouseup", resizeEndHandler), document.body.addEventListener("touchmove", resizingHandler), document.body.addEventListener("touchend", resizeEndHandler);
    }
    function resizingHandler(e) {
      e.preventDefault && e.type !== "touchmove" && e.preventDefault();
      let event = e.touches ? e.changedTouches[0] : e;
      typeof onResize == "function" && onResize(event, { resizeableElement, resizeableHandleElement });
    }
    function resizeEndHandler(e) {
      let event = e.touches ? e.changedTouches[0] : e;
      executeResizeCallbackWhenDefined(onResizeEnd, event), document.body.removeEventListener("mousemove", resizingHandler), document.body.removeEventListener("mouseup", resizeEndHandler), document.body.removeEventListener("touchmove", resizingHandler), document.body.removeEventListener("touchend", resizeEndHandler);
    }
    return init(), { destroy };
  }
  window.Slick && Utils.extend(Slick, {
    Draggable,
    MouseWheel,
    Resizable
  });
})();
//# sourceMappingURL=slick.interactions.js.map
"use strict";
(() => {
	var __defProp = Object.defineProperty;
	var __defNormalProp = (obj, key, value) => key in obj ? __defProp(obj, key, {
		enumerable: !0,
		configurable: !0,
		writable: !0,
		value
	}) : obj[key] = value;
	var __publicField = (obj, key, value) => (__defNormalProp(obj, typeof key != "symbol" ? key + "" : key, value), value);
	var m_intRenderNest = 0; // Mitsukibo

	// src/slick.grid.ts
	var BindingEventService = Slick.BindingEventService,
		ColAutosizeMode = Slick.ColAutosizeMode,
		SlickEvent = Slick.Event,
		SlickEventData = Slick.EventData,
		GlobalEditorLock = Slick.GlobalEditorLock,
		GridAutosizeColsMode = Slick.GridAutosizeColsMode,
		keyCode = Slick.keyCode,
		preClickClassName = Slick.preClickClassName,
		SlickRange = Slick.Range,
		RowSelectionMode = Slick.RowSelectionMode,
		ValueFilterMode = Slick.ValueFilterMode,
		Utils = Slick.Utils,
		WidthEvalMode = Slick.WidthEvalMode,
		Draggable = Slick.Draggable,
		MouseWheel = Slick.MouseWheel,
		Resizable = Slick.Resizable;
	var SlickGrid = class {
		/**
		 * Creates a new instance of the grid.
		 * @class SlickGrid
		 * @constructor
		 * @param {Node} container - Container node to create the grid in.
		 * @param {Array|Object} data - An array of objects for databinding.
		 * @param {Array<C>} columns - An array of column definitions.
		 * @param {Object} [options] - Grid this._options.
		 **/
		constructor(container, data, columns, options) {
			this.container = container;
			this.data = data;
			this.columns = columns;
			this.options = options;
			//////////////////////////////////////////////////////////////////////////////////////////////
			// Public API
			__publicField(this, "slickGridVersion", "5.5.6");
			/** optional grid state clientId */
			__publicField(this, "cid", "");
			// Events
			__publicField(this, "onActiveCellChanged", new SlickEvent());
			__publicField(this, "onActiveCellPositionChanged", new SlickEvent());
			__publicField(this, "onAddNewRow", new SlickEvent());
			__publicField(this, "onAutosizeColumns", new SlickEvent());
			__publicField(this, "onBeforeAppendCell", new SlickEvent());
			__publicField(this, "onBeforeCellEditorDestroy", new SlickEvent());
			__publicField(this, "onBeforeColumnsResize", new SlickEvent());
			__publicField(this, "onBeforeDestroy", new SlickEvent());
			__publicField(this, "onBeforeEditCell", new SlickEvent());
			__publicField(this, "onBeforeFooterRowCellDestroy", new SlickEvent());
			__publicField(this, "onBeforeHeaderCellDestroy", new SlickEvent());
			__publicField(this, "onBeforeHeaderRowCellDestroy", new SlickEvent());
			__publicField(this, "onBeforeSetColumns", new SlickEvent());
			__publicField(this, "onBeforeSort", new SlickEvent());
			__publicField(this, "onBeforeUpdateColumns", new SlickEvent());
			__publicField(this, "onCellChange", new SlickEvent());
			__publicField(this, "onCellCssStylesChanged", new SlickEvent());
			__publicField(this, "onClick", new SlickEvent());
			__publicField(this, "onColumnsReordered", new SlickEvent());
			__publicField(this, "onColumnsDrag", new SlickEvent());
			__publicField(this, "onColumnsResized", new SlickEvent());
			__publicField(this, "onColumnsResizeDblClick", new SlickEvent());
			__publicField(this, "onCompositeEditorChange", new SlickEvent());
			__publicField(this, "onContextMenu", new SlickEvent());
			__publicField(this, "onDeleteRow", new SlickEvent());
			__publicField(this, "onDrag", new SlickEvent());
			__publicField(this, "onDblClick", new SlickEvent());
			__publicField(this, "onDragInit", new SlickEvent());
			__publicField(this, "onDragStart", new SlickEvent());
			__publicField(this, "onDragEnd", new SlickEvent());
			__publicField(this, "onFooterClick", new SlickEvent());
			__publicField(this, "onFooterContextMenu", new SlickEvent());
			__publicField(this, "onFooterRowCellRendered", new SlickEvent());
			__publicField(this, "onHeaderCellRendered", new SlickEvent());
			__publicField(this, "onHeaderClick", new SlickEvent());
			__publicField(this, "onHeaderContextMenu", new SlickEvent());
			__publicField(this, "onHeaderMouseEnter", new SlickEvent());
			__publicField(this, "onHeaderMouseLeave", new SlickEvent());
			__publicField(this, "onHeaderRowCellRendered", new SlickEvent());
			__publicField(this, "onHeaderRowMouseEnter", new SlickEvent());
			__publicField(this, "onHeaderRowMouseLeave", new SlickEvent());
			__publicField(this, "onKeyDown", new SlickEvent());
			__publicField(this, "onMouseEnter", new SlickEvent());
			__publicField(this, "onMouseLeave", new SlickEvent());
			__publicField(this, "onRendered", new SlickEvent());
			__publicField(this, "onScroll", new SlickEvent());
			__publicField(this, "onSelectedRowsChanged", new SlickEvent());
			__publicField(this, "onSetOptions", new SlickEvent());
			__publicField(this, "onActivateChangedOptions", new SlickEvent());
			__publicField(this, "onSort", new SlickEvent());
			__publicField(this, "onValidationError", new SlickEvent());
			__publicField(this, "onViewportChanged", new SlickEvent());
			// ---
			// protected variables
			// shared across all grids on the page
			__publicField(this, "scrollbarDimensions");
			__publicField(this, "maxSupportedCssHeight");
			// browser's breaking point
			__publicField(this, "canvas", null);
			__publicField(this, "canvas_context", null);
			// settings
			__publicField(this, "_options");
			__publicField(this, "_defaults", {
				alwaysShowVerticalScroll: !1,
				alwaysAllowHorizontalScroll: !1,
				explicitInitialization: !1,
				rowHeight: 25,
				defaultColumnWidth: 80,
				enableHtmlRendering: !0,
				enableAddRow: !1,
				leaveSpaceForNewRows: !1,
				editable: !1,
				autoEdit: !0,
				autoEditNewRow: !0,
				autoCommitEdit: !1,
				suppressActiveCellChangeOnEdit: !1,
				enableCellNavigation: !0,
				enableColumnReorder: !0,
				asyncEditorLoading: !1,
				asyncEditorLoadDelay: 100,
				forceFitColumns: !1,
				enableAsyncPostRender: !1,
				asyncPostRenderDelay: 50,
				enableAsyncPostRenderCleanup: !1,
				asyncPostRenderCleanupDelay: 40,
				auto: !1,
				nonce: "",
				editorLock: GlobalEditorLock,
				showColumnHeader: !0,
				showHeaderRow: !1,
				headerRowHeight: 25,
				createFooterRow: !1,
				showFooterRow: !1,
				footerRowHeight: 25,
				createPreHeaderPanel: !1,
				showPreHeaderPanel: !1,
				preHeaderPanelHeight: 25,
				showTopPanel: !1,
				topPanelHeight: 25,
				formatterFactory: null,
				editorFactory: null,
				cellFlashingCssClass: "flashing",
				selectedCellCssClass: "selected",
				multiSelect: !0,
				enableTextSelectionOnCells: !1,
				dataItemColumnValueExtractor: null,
				frozenBottom: !1,
				frozenColumn: -1,
				frozenRow: -1,
				frozenRightViewportMinWidth: 100,
				throwWhenFrozenNotAllViewable: !1,
				fullWidthRows: !1,
				multiColumnSort: !1,
				numberedMultiColumnSort: !1,
				tristateMultiColumnSort: !1,
				sortColNumberInSeparateSpan: !1,
				defaultFormatter: this.defaultFormatter,
				forceSyncScrolling: !1,
				addNewRowCssClass: "new-row",
				preserveCopiedSelectionOnPaste: !1,
				showCellSelection: !0,
				viewportClass: void 0,
				minRowBuffer: 3,
				emulatePagingWhenScrolling: !0,
				// when scrolling off bottom of viewport, place new row at top of viewport
				editorCellNavOnLRKeys: !1,
				enableMouseWheelScrollHandler: !0,
				doPaging: !0,
				autosizeColsMode: GridAutosizeColsMode.LegacyOff,
				autosizeColPaddingPx: 4,
				scrollRenderThrottling: 50,
				autosizeTextAvgToMWidthRatio: 0.75,
				viewportSwitchToScrollModeWidthPercent: void 0,
				viewportMinWidthPx: void 0,
				viewportMaxWidthPx: void 0,
				suppressCssChangesOnHiddenInit: !1,
				ffMaxSupportedCssHeight: 6e6,
				maxSupportedCssHeight: 1e9,
				sanitizer: void 0,
				// sanitize function, built in basic sanitizer is: Slick.RegexSanitizer(dirtyHtml)
				logSanitizedHtml: !1,
				// log to console when sanitised - recommend true for testing of dev and production
				mixinDefaults: !0,
				shadowRoot: void 0
			});
			__publicField(this, "_columnDefaults", {
				name: "",
				resizable: !0,
				sortable: !1,
				minWidth: 30,
				maxWidth: void 0,
				rerenderOnResize: !1,
				headerCssClass: null,
				defaultSortAsc: !0,
				focusable: !0,
				selectable: !0,
				hidden: !1
			});
			__publicField(this, "_columnAutosizeDefaults", {
				ignoreHeaderText: !1,
				colValueArray: void 0,
				allowAddlPercent: void 0,
				formatterOverride: void 0,
				autosizeMode: ColAutosizeMode.ContentIntelligent,
				rowSelectionModeOnInit: void 0,
				rowSelectionMode: RowSelectionMode.FirstNRows,
				rowSelectionCount: 100,
				valueFilterMode: ValueFilterMode.None,
				widthEvalMode: WidthEvalMode.Auto,
				sizeToRemaining: void 0,
				widthPx: void 0,
				contentSizePx: 0,
				headerWidthPx: 0,
				colDataTypeOf: void 0
			});
			// scroller
			__publicField(this, "th");
			// virtual height
			__publicField(this, "h");
			// real scrollable height
			__publicField(this, "ph");
			// page height
			__publicField(this, "n");
			// number of pages
			__publicField(this, "cj");
			// "jumpiness" coefficient
			__publicField(this, "page", 0);
			// current page
			__publicField(this, "offset", 0);
			// current page offset
			__publicField(this, "vScrollDir", 1);
			__publicField(this, "_bindingEventService", new BindingEventService());
			__publicField(this, "initialized", !1);
			__publicField(this, "_container");
			__publicField(this, "uid", `slickgrid_${Math.round(1e6 * Math.random())}`);
			__publicField(this, "_focusSink");
			__publicField(this, "_focusSink2");
			__publicField(this, "_groupHeaders", []);
			__publicField(this, "_headerScroller", []);
			__publicField(this, "_headers", []);
			__publicField(this, "_headerRows");
			__publicField(this, "_headerRowScroller");
			__publicField(this, "_headerRowSpacerL");
			__publicField(this, "_headerRowSpacerR");
			__publicField(this, "_footerRow");
			__publicField(this, "_footerRowScroller");
			__publicField(this, "_footerRowSpacerL");
			__publicField(this, "_footerRowSpacerR");
			__publicField(this, "_preHeaderPanel");
			__publicField(this, "_preHeaderPanelScroller");
			__publicField(this, "_preHeaderPanelSpacer");
			__publicField(this, "_preHeaderPanelR");
			__publicField(this, "_preHeaderPanelScrollerR");
			__publicField(this, "_preHeaderPanelSpacerR");
			__publicField(this, "_topPanelScrollers");
			__publicField(this, "_topPanels");
			__publicField(this, "_viewport");
			__publicField(this, "_canvas");
			__publicField(this, "_style");
			__publicField(this, "_boundAncestors", []);
			__publicField(this, "stylesheet");
			__publicField(this, "columnCssRulesL");
			__publicField(this, "columnCssRulesR");
			__publicField(this, "viewportH", 0);
			__publicField(this, "viewportW", 0);
			__publicField(this, "canvasWidth", 0);
			__publicField(this, "canvasWidthL", 0);
			__publicField(this, "canvasWidthR", 0);
			__publicField(this, "headersWidth", 0);
			__publicField(this, "headersWidthL", 0);
			__publicField(this, "headersWidthR", 0);
			__publicField(this, "viewportHasHScroll", !1);
			__publicField(this, "viewportHasVScroll", !1);
			__publicField(this, "headerColumnWidthDiff", 0);
			__publicField(this, "headerColumnHeightDiff", 0);
			// border+padding
			__publicField(this, "cellWidthDiff", 0);
			__publicField(this, "cellHeightDiff", 0);
			__publicField(this, "absoluteColumnMinWidth");
			__publicField(this, "hasFrozenRows", !1);
			__publicField(this, "frozenRowsHeight", 0);
			__publicField(this, "actualFrozenRow", -1);
			__publicField(this, "paneTopH", 0);
			__publicField(this, "paneBottomH", 0);
			__publicField(this, "viewportTopH", 0);
			__publicField(this, "viewportBottomH", 0);
			__publicField(this, "topPanelH", 0);
			__publicField(this, "headerRowH", 0);
			__publicField(this, "footerRowH", 0);
			__publicField(this, "tabbingDirection", 1);
			__publicField(this, "_activeCanvasNode");
			__publicField(this, "_activeViewportNode");
			__publicField(this, "activePosX");
			__publicField(this, "activeRow");
			__publicField(this, "activeCell");
			__publicField(this, "activeCellNode", null);
			__publicField(this, "currentEditor", null);
			__publicField(this, "serializedEditorValue");
			__publicField(this, "editController");
			__publicField(this, "rowsCache", {});
			__publicField(this, "renderedRows", 0);
			__publicField(this, "numVisibleRows", 0);
			__publicField(this, "prevScrollTop", 0);
			__publicField(this, "scrollTop", 0);
			__publicField(this, "lastRenderedScrollTop", 0);
			__publicField(this, "lastRenderedScrollLeft", 0);
			__publicField(this, "prevScrollLeft", 0);
			__publicField(this, "scrollLeft", 0);
			__publicField(this, "selectionModel");
			__publicField(this, "selectedRows", []);
			__publicField(this, "plugins", []);
			__publicField(this, "cellCssClasses", {});
			__publicField(this, "columnsById", {});
			__publicField(this, "sortColumns", []);
			__publicField(this, "columnPosLeft", []);
			__publicField(this, "columnPosRight", []);
			__publicField(this, "pagingActive", !1);
			__publicField(this, "pagingIsLastPage", !1);
			__publicField(this, "scrollThrottle");
			// async call handles
			__publicField(this, "h_editorLoader", null);
			__publicField(this, "h_render", null);
			__publicField(this, "h_postrender", null);
			__publicField(this, "h_postrenderCleanup", null);
			__publicField(this, "postProcessedRows", {});
			__publicField(this, "postProcessToRow", null);
			__publicField(this, "postProcessFromRow", null);
			__publicField(this, "postProcessedCleanupQueue", []);
			__publicField(this, "postProcessgroupId", 0);
			// perf counters
			__publicField(this, "counter_rows_rendered", 0);
			__publicField(this, "counter_rows_removed", 0);
			__publicField(this, "_paneHeaderL");
			__publicField(this, "_paneHeaderR");
			__publicField(this, "_paneTopL");
			__publicField(this, "_paneTopR");
			__publicField(this, "_paneBottomL");
			__publicField(this, "_paneBottomR");
			__publicField(this, "_headerScrollerL");
			__publicField(this, "_headerScrollerR");
			__publicField(this, "_headerL");
			__publicField(this, "_headerR");
			__publicField(this, "_groupHeadersL");
			__publicField(this, "_groupHeadersR");
			__publicField(this, "_headerRowScrollerL");
			__publicField(this, "_headerRowScrollerR");
			__publicField(this, "_footerRowScrollerL");
			__publicField(this, "_footerRowScrollerR");
			__publicField(this, "_headerRowL");
			__publicField(this, "_headerRowR");
			__publicField(this, "_footerRowL");
			__publicField(this, "_footerRowR");
			__publicField(this, "_topPanelScrollerL");
			__publicField(this, "_topPanelScrollerR");
			__publicField(this, "_topPanelL");
			__publicField(this, "_topPanelR");
			__publicField(this, "_viewportTopL");
			__publicField(this, "_viewportTopR");
			__publicField(this, "_viewportBottomL");
			__publicField(this, "_viewportBottomR");
			__publicField(this, "_canvasTopL");
			__publicField(this, "_canvasTopR");
			__publicField(this, "_canvasBottomL");
			__publicField(this, "_canvasBottomR");
			__publicField(this, "_viewportScrollContainerX");
			__publicField(this, "_viewportScrollContainerY");
			__publicField(this, "_headerScrollContainer");
			__publicField(this, "_headerRowScrollContainer");
			__publicField(this, "_footerRowScrollContainer");
			// store css attributes if display:none is active in container or parent
			__publicField(this, "cssShow", {
				position: "absolute",
				visibility: "hidden",
				display: "block"
			});
			__publicField(this, "_hiddenParents", []);
			__publicField(this, "oldProps", []);
			__publicField(this, "enforceFrozenRowHeightRecalc", !1);
			__publicField(this, "columnResizeDragging", !1);
			__publicField(this, "slickDraggableInstance", null);
			__publicField(this, "slickMouseWheelInstances", []);
			__publicField(this, "slickResizableInstances", []);
			__publicField(this, "sortableSideLeftInstance");
			__publicField(this, "sortableSideRightInstance");
			__publicField(this, "logMessageCount", 0);
			__publicField(this, "logMessageMaxCount", 30);
			this.initialize();
		}
		//////////////////////////////////////////////////////////////////////////////////////////////
		// Initialization
		/** Initializes the grid. */
		init() {
			this.finishInitialization();
		}
		/**
		 * Apply HTML code by 3 different ways depending on what is provided as input and what options are enabled.
		 * 1. value is an HTMLElement or DocumentFragment, then first empty the target and simply append the HTML to the target element.
		 * 2. value is string and `enableHtmlRendering` is enabled, then use `target.innerHTML = value;`
		 * 3. value is string and `enableHtmlRendering` is disabled, then use `target.textContent = value;`
		 * @param target - target element to apply to
		 * @param val - input value can be either a string or an HTMLElement
		 * @param options -
		 *   `emptyTarget`, defaults to true, will empty the target.
		 *   `skipEmptyReassignment`, defaults to true, when enabled it will not try to reapply an empty value when the target is already empty
		 */
		applyHtmlCode(target, val, options) {
			if (target)
				if (val instanceof HTMLElement || val instanceof DocumentFragment)
					(options == null ? void 0 : options.emptyTarget) !== !1 && Utils.emptyElement(target), target.appendChild(val);
				else {
					if ((options == null ? void 0 : options.skipEmptyReassignment) !== !1 && !Utils.isDefined(val) && !target.innerHTML)
						return;
					this._options.enableHtmlRendering && val ? target.innerHTML = this.sanitizeHtmlString(val) : target.textContent = this.sanitizeHtmlString(val);
				}
		}
		initialize() {
			if (typeof this.container == "string" ? this._container = document.querySelector(this.container) : this._container = this.container, !this._container)
				throw new Error(`SlickGrid requires a valid container, ${this.container} does not exist in the DOM.`);
			if (this.options.mixinDefaults ? (this.options || (this.options = {}), Utils.applyDefaults(this.options, this._defaults)) : this._options = Utils.extend(!0, {}, this._defaults, this.options), this.scrollThrottle = this.actionThrottle(this.render.bind(this), this._options.scrollRenderThrottling), this.maxSupportedCssHeight = this.maxSupportedCssHeight || this.getMaxSupportedCssHeight(), this.validateAndEnforceOptions(), this._columnDefaults.width = this._options.defaultColumnWidth, this._options.suppressCssChangesOnHiddenInit || this.cacheCssForHiddenInit(), this.updateColumnProps(), this._options.enableColumnReorder && (!Sortable || !Sortable.create))
				throw new Error("SlickGrid requires Sortable.js module to be loaded");
			this.editController = {
				commitCurrentEdit: this.commitCurrentEdit.bind(this),
				cancelCurrentEdit: this.cancelCurrentEdit.bind(this)
			}, Utils.emptyElement(this._container), this._container.style.overflow = "hidden", this._container.style.outline = String(0), this._container.classList.add(this.uid), this._container.classList.add("ui-widget");
			let containerStyles = window.getComputedStyle(this._container);
			/relative|absolute|fixed/.test(containerStyles.position) || (this._container.style.position = "relative"), this._focusSink = Utils.createDomElement("div", {
				tabIndex: 0,
				style: {
					position: "fixed",
					width: "0px",
					height: "0px",
					top: "0px",
					left: "0px",
					outline: "0px"
				}
			}, this._container), this._paneHeaderL = Utils.createDomElement("div", {
				className: "slick-pane slick-pane-header slick-pane-left",
				tabIndex: 0
			}, this._container), this._paneHeaderR = Utils.createDomElement("div", {
				className: "slick-pane slick-pane-header slick-pane-right",
				tabIndex: 0
			}, this._container), this._paneTopL = Utils.createDomElement("div", {
				className: "slick-pane slick-pane-top slick-pane-left",
				tabIndex: 0
			}, this._container), this._paneTopR = Utils.createDomElement("div", {
				className: "slick-pane slick-pane-top slick-pane-right",
				tabIndex: 0
			}, this._container), this._paneBottomL = Utils.createDomElement("div", {
				className: "slick-pane slick-pane-bottom slick-pane-left",
				tabIndex: 0
			}, this._container), this._paneBottomR = Utils.createDomElement("div", {
				className: "slick-pane slick-pane-bottom slick-pane-right",
				tabIndex: 0
			}, this._container), this._options.createPreHeaderPanel && (this._preHeaderPanelScroller = Utils.createDomElement("div", {
				className: "slick-preheader-panel ui-state-default slick-state-default",
				style: {
					overflow: "hidden",
					position: "relative"
				}
			}, this._paneHeaderL), this._preHeaderPanelScroller.appendChild(document.createElement("div")), this._preHeaderPanel = Utils.createDomElement("div", null, this._preHeaderPanelScroller), this._preHeaderPanelSpacer = Utils.createDomElement("div", {
				style: {
					display: "block",
					height: "1px",
					position: "absolute",
					top: "0px",
					left: "0px"
				}
			}, this._preHeaderPanelScroller), this._preHeaderPanelScrollerR = Utils.createDomElement("div", {
				className: "slick-preheader-panel ui-state-default slick-state-default",
				style: {
					overflow: "hidden",
					position: "relative"
				}
			}, this._paneHeaderR), this._preHeaderPanelR = Utils.createDomElement("div", null, this._preHeaderPanelScrollerR), this._preHeaderPanelSpacerR = Utils.createDomElement("div", {
				style: {
					display: "block",
					height: "1px",
					position: "absolute",
					top: "0px",
					left: "0px"
				}
			}, this._preHeaderPanelScrollerR), this._options.showPreHeaderPanel || (Utils.hide(this._preHeaderPanelScroller), Utils.hide(this._preHeaderPanelScrollerR))), this._headerScrollerL = Utils.createDomElement("div", {
				className: "slick-header ui-state-default slick-state-default slick-header-left"
			}, this._paneHeaderL), this._headerScrollerR = Utils.createDomElement("div", {
				className: "slick-header ui-state-default slick-state-default slick-header-right"
			}, this._paneHeaderR), this._headerScroller.push(this._headerScrollerL), this._headerScroller.push(this._headerScrollerR), this._headerL = Utils.createDomElement("div", {
				className: "slick-header-columns slick-header-columns-left",
				style: {
					left: "-1000px"
				}
			}, this._headerScrollerL), this._headerR = Utils.createDomElement("div", {
				className: "slick-header-columns slick-header-columns-right",
				style: {
					left: "-1000px"
				}
			}, this._headerScrollerR), this._headers = [this._headerL, this._headerR], this._headerRowScrollerL = Utils.createDomElement("div", {
				className: "slick-headerrow ui-state-default slick-state-default"
			}, this._paneTopL), this._headerRowScrollerR = Utils.createDomElement("div", {
				className: "slick-headerrow ui-state-default slick-state-default"
			}, this._paneTopR), this._headerRowScroller = [this._headerRowScrollerL, this._headerRowScrollerR], this._headerRowSpacerL = Utils.createDomElement("div", {
				style: {
					display: "block",
					height: "1px",
					position: "absolute",
					top: "0px",
					left: "0px"
				}
			}, this._headerRowScrollerL), this._headerRowSpacerR = Utils.createDomElement("div", {
				style: {
					display: "block",
					height: "1px",
					position: "absolute",
					top: "0px",
					left: "0px"
				}
			}, this._headerRowScrollerR), this._headerRowL = Utils.createDomElement("div", {
				className: "slick-headerrow-columns slick-headerrow-columns-left"
			}, this._headerRowScrollerL), this._headerRowR = Utils.createDomElement("div", {
				className: "slick-headerrow-columns slick-headerrow-columns-right"
			}, this._headerRowScrollerR), this._headerRows = [this._headerRowL, this._headerRowR], this._topPanelScrollerL = Utils.createDomElement("div", {
				className: "slick-top-panel-scroller ui-state-default slick-state-default"
			}, this._paneTopL), this._topPanelScrollerR = Utils.createDomElement("div", {
				className: "slick-top-panel-scroller ui-state-default slick-state-default"
			}, this._paneTopR), this._topPanelScrollers = [this._topPanelScrollerL, this._topPanelScrollerR], this._topPanelL = Utils.createDomElement("div", {
				className: "slick-top-panel",
				style: {
					width: "10000px"
				}
			}, this._topPanelScrollerL), this._topPanelR = Utils.createDomElement("div", {
				className: "slick-top-panel",
				style: {
					width: "10000px"
				}
			}, this._topPanelScrollerR), this._topPanels = [this._topPanelL, this._topPanelR], this._options.showColumnHeader || this._headerScroller.forEach((el) => {
				Utils.hide(el);
			}), this._options.showTopPanel || this._topPanelScrollers.forEach((scroller) => {
				Utils.hide(scroller);
			}), this._options.showHeaderRow || this._headerRowScroller.forEach((scroller) => {
				Utils.hide(scroller);
			}), this._viewportTopL = Utils.createDomElement("div", {
				className: "slick-viewport slick-viewport-top slick-viewport-left",
				tabIndex: 0
			}, this._paneTopL), this._viewportTopR = Utils.createDomElement("div", {
				className: "slick-viewport slick-viewport-top slick-viewport-right",
				tabIndex: 0
			}, this._paneTopR), this._viewportBottomL = Utils.createDomElement("div", {
				className: "slick-viewport slick-viewport-bottom slick-viewport-left",
				tabIndex: 0
			}, this._paneBottomL), this._viewportBottomR = Utils.createDomElement("div", {
				className: "slick-viewport slick-viewport-bottom slick-viewport-right",
				tabIndex: 0
			}, this._paneBottomR), this._viewport = [this._viewportTopL, this._viewportTopR, this._viewportBottomL, this._viewportBottomR], this._options.viewportClass && this._viewport.forEach((view) => {
				view.classList.add(...(this._options.viewportClass || "").split(" "));
			}), this._activeViewportNode = this._viewportTopL, this._canvasTopL = Utils.createDomElement("div", {
				className: "grid-canvas grid-canvas-top grid-canvas-left",
				tabIndex: 0
			}, this._viewportTopL), this._canvasTopR = Utils.createDomElement("div", {
				className: "grid-canvas grid-canvas-top grid-canvas-right",
				tabIndex: 0
			}, this._viewportTopR), this._canvasBottomL = Utils.createDomElement("div", {
				className: "grid-canvas grid-canvas-bottom grid-canvas-left",
				tabIndex: 0
			}, this._viewportBottomL), this._canvasBottomR = Utils.createDomElement("div", {
				className: "grid-canvas grid-canvas-bottom grid-canvas-right",
				tabIndex: 0
			}, this._viewportBottomR), this._canvas = [this._canvasTopL, this._canvasTopR, this._canvasBottomL, this._canvasBottomR], this.scrollbarDimensions = this.scrollbarDimensions || this.measureScrollbar(), this._activeCanvasNode = this._canvasTopL, this._preHeaderPanelSpacer && Utils.width(this._preHeaderPanelSpacer, this.getCanvasWidth() + this.scrollbarDimensions.width), this._headers.forEach((el) => {
				Utils.width(el, this.getHeadersWidth());
			}), Utils.width(this._headerRowSpacerL, this.getCanvasWidth() + this.scrollbarDimensions.width), Utils.width(this._headerRowSpacerR, this.getCanvasWidth() + this.scrollbarDimensions.width), this._options.createFooterRow && (this._footerRowScrollerR = Utils.createDomElement("div", {
				className: "slick-footerrow ui-state-default slick-state-default"
			}, this._paneTopR), this._footerRowScrollerL = Utils.createDomElement("div", {
				className: "slick-footerrow ui-state-default slick-state-default"
			}, this._paneTopL), this._footerRowScroller = [this._footerRowScrollerL, this._footerRowScrollerR], this._footerRowSpacerL = Utils.createDomElement("div", {
				style: {
					display: "block",
					height: "1px",
					position: "absolute",
					top: "0px",
					left: "0px"
				}
			}, this._footerRowScrollerL), Utils.width(this._footerRowSpacerL, this.getCanvasWidth() + this.scrollbarDimensions.width), this._footerRowSpacerR = Utils.createDomElement("div", {
				style: {
					display: "block",
					height: "1px",
					position: "absolute",
					top: "0px",
					left: "0px"
				}
			}, this._footerRowScrollerR), Utils.width(this._footerRowSpacerR, this.getCanvasWidth() + this.scrollbarDimensions.width), this._footerRowL = Utils.createDomElement("div", {
				className: "slick-footerrow-columns slick-footerrow-columns-left"
			}, this._footerRowScrollerL), this._footerRowR = Utils.createDomElement("div", {
				className: "slick-footerrow-columns slick-footerrow-columns-right"
			}, this._footerRowScrollerR), this._footerRow = [this._footerRowL, this._footerRowR], this._options.showFooterRow || this._footerRowScroller.forEach((scroller) => {
				Utils.hide(scroller);
			})), this._focusSink2 = this._focusSink.cloneNode(!0), this._container.appendChild(this._focusSink2), this._options.explicitInitialization || this.finishInitialization();
		}
		finishInitialization() {
			this.initialized || (this.initialized = !0, this.getViewportWidth(), this.getViewportHeight(), this.measureCellPaddingAndBorder(), this.disableSelection(this._headers), this._options.enableTextSelectionOnCells || this._viewport.forEach((view) => {
				this._bindingEventService.bind(view, "selectstart", (event) => {
					event.target instanceof HTMLInputElement || event.target instanceof HTMLTextAreaElement;
				});
			}), this.setFrozenOptions(), this.setPaneVisibility(), this.setScroller(), this.setOverflow(), this.updateColumnCaches(), this.createColumnHeaders(), this.createColumnFooter(), this.setupColumnSort(), this.createCssRules(), this.resizeCanvas(), this.bindAncestorScrollEvents(), this._bindingEventService.bind(this._container, "resize", this.resizeCanvas.bind(this)), this._viewport.forEach((view) => {
				this._bindingEventService.bind(view, "scroll", this.handleScroll.bind(this));
			}), this._options.enableMouseWheelScrollHandler && this._viewport.forEach((view) => {
				this.slickMouseWheelInstances.push(MouseWheel({
					element: view,
					onMouseWheel: this.handleMouseWheel.bind(this)
				}));
			}), this._headerScroller.forEach((el) => {
				this._bindingEventService.bind(el, "contextmenu", this.handleHeaderContextMenu.bind(this)), this._bindingEventService.bind(el, "click", this.handleHeaderClick.bind(this));
			}), this._headerRowScroller.forEach((scroller) => {
				this._bindingEventService.bind(scroller, "scroll", this.handleHeaderRowScroll.bind(this));
			}), this._options.createFooterRow && (this._footerRow.forEach((footer) => {
				this._bindingEventService.bind(footer, "contextmenu", this.handleFooterContextMenu.bind(this)), this._bindingEventService.bind(footer, "click", this.handleFooterClick.bind(this));
			}), this._footerRowScroller.forEach((scroller) => {
				this._bindingEventService.bind(scroller, "scroll", this.handleFooterRowScroll.bind(this));
			})), this._options.createPreHeaderPanel && this._bindingEventService.bind(this._preHeaderPanelScroller, "scroll", this.handlePreHeaderPanelScroll.bind(this)), this._bindingEventService.bind(this._focusSink, "keydown", this.handleKeyDown.bind(this)), this._bindingEventService.bind(this._focusSink2, "keydown", this.handleKeyDown.bind(this)), this._canvas.forEach((element) => {
				this._bindingEventService.bind(element, "keydown", this.handleKeyDown.bind(this)), this._bindingEventService.bind(element, "click", this.handleClick.bind(this)), this._bindingEventService.bind(element, "dblclick", this.handleDblClick.bind(this)), this._bindingEventService.bind(element, "contextmenu", this.handleContextMenu.bind(this)), this._bindingEventService.bind(element, "mouseover", this.handleCellMouseOver.bind(this)), this._bindingEventService.bind(element, "mouseout", this.handleCellMouseOut.bind(this));
			}), Draggable && (this.slickDraggableInstance = Draggable({
				containerElement: this._container,
				allowDragFrom: "div.slick-cell",
				// the slick cell parent must always contain `.dnd` and/or `.cell-reorder` class to be identified as draggable
				allowDragFromClosest: "div.slick-cell.dnd, div.slick-cell.cell-reorder",
				onDragInit: this.handleDragInit.bind(this),
				onDragStart: this.handleDragStart.bind(this),
				onDrag: this.handleDrag.bind(this),
				onDragEnd: this.handleDragEnd.bind(this)
			})), this._options.suppressCssChangesOnHiddenInit || this.restoreCssFromHiddenInit());
		}
		/** handles "display:none" on container or container parents, related to issue: https://github.com/6pac/SlickGrid/issues/568 */
		cacheCssForHiddenInit() {
			this._hiddenParents = Utils.parents(this._container, ":hidden");
			for (let el of this._hiddenParents) {
				let old = {};
				for (let name in this.cssShow)
					this.cssShow && (old[name] = el.style[name], el.style[name] = this.cssShow[name]);
				this.oldProps.push(old);
			}
		}
		restoreCssFromHiddenInit() {
			let i = 0;
			for (let el of this._hiddenParents) {
				let old = this.oldProps[i++];
				for (let name in this.cssShow)
					this.cssShow && (el.style[name] = old[name]);
			}
		}
		hasFrozenColumns() {
			return this._options.frozenColumn > -1;
		}
		/** Register an external Plugin */
		registerPlugin(plugin) {
			this.plugins.unshift(plugin), plugin.init(this);
		}
		/** Unregister (destroy) an external Plugin */
		unregisterPlugin(plugin) {
			var _a;
			for (let i = this.plugins.length; i >= 0; i--)
				if (this.plugins[i] === plugin) {
					(_a = this.plugins[i]) == null || _a.destroy(), this.plugins.splice(i, 1);
					break;
				}
		}
		/** Get a Plugin (addon) by its name */
		getPluginByName(name) {
			var _a;
			for (let i = this.plugins.length - 1; i >= 0; i--)
				if (((_a = this.plugins[i]) == null ? void 0 : _a.pluginName) === name)
					return this.plugins[i];
		}
		/**
		 * Unregisters a current selection model and registers a new one. See the definition of SelectionModel for more information.
		 * @param {Object} selectionModel A SelectionModel.
		 */
		setSelectionModel(model) {
			this.selectionModel && (this.selectionModel.onSelectedRangesChanged.unsubscribe(this.handleSelectedRangesChanged.bind(this)), this.selectionModel.destroy && this.selectionModel.destroy()), this.selectionModel = model, this.selectionModel && (this.selectionModel.init(this), this.selectionModel.onSelectedRangesChanged.subscribe(this.handleSelectedRangesChanged.bind(this)));
		}
		/** Returns the current SelectionModel. See here for more information about SelectionModels. */
		getSelectionModel() {
			return this.selectionModel;
		}
		/** Get Grid Canvas Node DOM Element */
		getCanvasNode(columnIdOrIdx, rowIndex) {
			return this._getContainerElement(this.getCanvases(), columnIdOrIdx, rowIndex);
		}
		/** Get the canvas DOM element */
		getActiveCanvasNode(e) {
			return e === void 0 ? this._activeCanvasNode : (e instanceof SlickEventData && (e = e.getNativeEvent()), this._activeCanvasNode = e == null ? void 0 : e.target.closest(".grid-canvas"), this._activeCanvasNode);
		}
		/** Get the canvas DOM element */
		getCanvases() {
			return this._canvas;
		}
		/** Get the Viewport DOM node element */
		getViewportNode(columnIdOrIdx, rowIndex) {
			return this._getContainerElement(this.getViewports(), columnIdOrIdx, rowIndex);
		}
		/** Get all the Viewport node elements */
		getViewports() {
			return this._viewport;
		}
		getActiveViewportNode(e) {
			return this.setActiveViewportNode(e), this._activeViewportNode;
		}
		/** Sets an active viewport node */
		setActiveViewportNode(e) {
			return e instanceof SlickEventData && (e = e.getNativeEvent()), this._activeViewportNode = e == null ? void 0 : e.target.closest(".slick-viewport"), this._activeViewportNode;
		}
		_getContainerElement(targetContainers, columnIdOrIdx, rowIndex) {
			if (!targetContainers)
				return;
			columnIdOrIdx || (columnIdOrIdx = 0), rowIndex || (rowIndex = 0);
			let idx = typeof columnIdOrIdx == "number" ? columnIdOrIdx : this.getColumnIndex(columnIdOrIdx),
				isBottomSide = this.hasFrozenRows && rowIndex >= this.actualFrozenRow + (this._options.frozenBottom ? 0 : 1),
				isRightSide = this.hasFrozenColumns() && idx > this._options.frozenColumn;
			return targetContainers[(isBottomSide ? 2 : 0) + (isRightSide ? 1 : 0)];
		}
		measureScrollbar() {
			let className = "";
			this._viewport.forEach((v) => className += v.className);
			let outerdiv = Utils.createDomElement("div", {
					className,
					style: {
						position: "absolute",
						top: "-10000px",
						left: "-10000px",
						overflow: "auto",
						width: "100px",
						height: "100px"
					}
				}, document.body),
				innerdiv = Utils.createDomElement("div", {
					style: {
						width: "200px",
						height: "200px",
						overflow: "auto"
					}
				}, outerdiv),
				dim = {
					width: outerdiv.offsetWidth - outerdiv.clientWidth,
					height: outerdiv.offsetHeight - outerdiv.clientHeight
				};
			return innerdiv.remove(), outerdiv.remove(), dim;
		}
		/** Get the headers width in pixel */
		getHeadersWidth() {
			var _a, _b, _c, _d, _e, _f, _g, _h;
			this.headersWidth = this.headersWidthL = this.headersWidthR = 0;
			let includeScrollbar = !this._options.autoHeight,
				i = 0,
				ii = this.columns.length;
			for (i = 0; i < ii; i++) {
				if (!this.columns[i] || this.columns[i].hidden)
					continue;
				let width = this.columns[i].width;
				this._options.frozenColumn > -1 && i > this._options.frozenColumn ? this.headersWidthR += width || 0 : this.headersWidthL += width || 0;
			}
			return includeScrollbar && (this._options.frozenColumn > -1 && i > this._options.frozenColumn ? this.headersWidthR += (_b = (_a = this.scrollbarDimensions) == null ? void 0 : _a.width) != null ? _b : 0 : this.headersWidthL += (_d = (_c = this.scrollbarDimensions) == null ? void 0 : _c.width) != null ? _d : 0), this.hasFrozenColumns() ? (this.headersWidthL = this.headersWidthL + 1e3, this.headersWidthR = Math.max(this.headersWidthR, this.viewportW) + this.headersWidthL, this.headersWidthR += (_f = (_e = this.scrollbarDimensions) == null ? void 0 : _e.width) != null ? _f : 0) : (this.headersWidthL += (_h = (_g = this.scrollbarDimensions) == null ? void 0 : _g.width) != null ? _h : 0, this.headersWidthL = Math.max(this.headersWidthL, this.viewportW) + 1e3), this.headersWidth = this.headersWidthL + this.headersWidthR, Math.max(this.headersWidth, this.viewportW) + 1e3;
		}
		getHeadersWidthL() {
			var _a, _b;
			return this.headersWidthL = 0, this.columns.forEach((column, i) => {
				column.hidden || this._options.frozenColumn > -1 && i > this._options.frozenColumn || (this.headersWidthL += column.width || 0);
			}), this.hasFrozenColumns() ? this.headersWidthL += 1e3 : (this.headersWidthL += (_b = (_a = this.scrollbarDimensions) == null ? void 0 : _a.width) != null ? _b : 0, this.headersWidthL = Math.max(this.headersWidthL, this.viewportW) + 1e3), this.headersWidthL;
		}
		getHeadersWidthR() {
			var _a, _b;
			return this.headersWidthR = 0, this.columns.forEach((column, i) => {
				column.hidden || this._options.frozenColumn > -1 && i > this._options.frozenColumn && (this.headersWidthR += column.width || 0);
			}), this.hasFrozenColumns() && (this.headersWidthR = Math.max(this.headersWidthR, this.viewportW) + this.getHeadersWidthL(), this.headersWidthR += (_b = (_a = this.scrollbarDimensions) == null ? void 0 : _a.width) != null ? _b : 0), this.headersWidthR;
		}
		/** Get the grid canvas width */
		getCanvasWidth() {
			var _a, _b;
			let availableWidth = this.viewportHasVScroll ? this.viewportW - ((_b = (_a = this.scrollbarDimensions) == null ? void 0 : _a.width) != null ? _b : 0) : this.viewportW,
				i = this.columns.length;
			for (this.canvasWidthL = this.canvasWidthR = 0; i--;)
				!this.columns[i] || this.columns[i].hidden || (this.hasFrozenColumns() && i > this._options.frozenColumn ? this.canvasWidthR += this.columns[i].width || 0 : this.canvasWidthL += this.columns[i].width || 0);
			let totalRowWidth = this.canvasWidthL + this.canvasWidthR;
			if (this._options.fullWidthRows) {
				let extraWidth = Math.max(totalRowWidth, availableWidth) - totalRowWidth;
				extraWidth > 0 && (totalRowWidth += extraWidth, this.hasFrozenColumns() ? this.canvasWidthR += extraWidth : this.canvasWidthL += extraWidth);
			}
			return totalRowWidth;
		}
		updateCanvasWidth(forceColumnWidthsUpdate) {
			var _a, _b, _c, _d, _e, _f, _g, _h, _i, _j;
			let oldCanvasWidth = this.canvasWidth,
				oldCanvasWidthL = this.canvasWidthL,
				oldCanvasWidthR = this.canvasWidthR;
			this.canvasWidth = this.getCanvasWidth();
			let widthChanged = this.canvasWidth !== oldCanvasWidth || this.canvasWidthL !== oldCanvasWidthL || this.canvasWidthR !== oldCanvasWidthR;
			if (widthChanged || this.hasFrozenColumns() || this.hasFrozenRows)
				if (Utils.width(this._canvasTopL, this.canvasWidthL), this.getHeadersWidth(), Utils.width(this._headerL, this.headersWidthL), Utils.width(this._headerR, this.headersWidthR), this.hasFrozenColumns()) {
					let cWidth = Utils.width(this._container) || 0;
					if (cWidth > 0 && this.canvasWidthL > cWidth && this._options.throwWhenFrozenNotAllViewable)
						throw new Error("[SlickGrid] Frozen columns cannot be wider than the actual grid container width. Make sure to have less columns freezed or make your grid container wider");
					Utils.width(this._canvasTopR, this.canvasWidthR), Utils.width(this._paneHeaderL, this.canvasWidthL), Utils.setStyleSize(this._paneHeaderR, "left", this.canvasWidthL), Utils.setStyleSize(this._paneHeaderR, "width", this.viewportW - this.canvasWidthL), Utils.width(this._paneTopL, this.canvasWidthL), Utils.setStyleSize(this._paneTopR, "left", this.canvasWidthL), Utils.width(this._paneTopR, this.viewportW - this.canvasWidthL), Utils.width(this._headerRowScrollerL, this.canvasWidthL), Utils.width(this._headerRowScrollerR, this.viewportW - this.canvasWidthL), Utils.width(this._headerRowL, this.canvasWidthL), Utils.width(this._headerRowR, this.canvasWidthR), this._options.createFooterRow && (Utils.width(this._footerRowScrollerL, this.canvasWidthL), Utils.width(this._footerRowScrollerR, this.viewportW - this.canvasWidthL), Utils.width(this._footerRowL, this.canvasWidthL), Utils.width(this._footerRowR, this.canvasWidthR)), this._options.createPreHeaderPanel && Utils.width(this._preHeaderPanel, this.canvasWidth), Utils.width(this._viewportTopL, this.canvasWidthL), Utils.width(this._viewportTopR, this.viewportW - this.canvasWidthL), this.hasFrozenRows && (Utils.width(this._paneBottomL, this.canvasWidthL), Utils.setStyleSize(this._paneBottomR, "left", this.canvasWidthL), Utils.width(this._viewportBottomL, this.canvasWidthL), Utils.width(this._viewportBottomR, this.viewportW - this.canvasWidthL), Utils.width(this._canvasBottomL, this.canvasWidthL), Utils.width(this._canvasBottomR, this.canvasWidthR));
				} else
					Utils.width(this._paneHeaderL, "100%"), Utils.width(this._paneTopL, "100%"), Utils.width(this._headerRowScrollerL, "100%"), Utils.width(this._headerRowL, this.canvasWidth), this._options.createFooterRow && (Utils.width(this._footerRowScrollerL, "100%"), Utils.width(this._footerRowL, this.canvasWidth)), this._options.createPreHeaderPanel && Utils.width(this._preHeaderPanel, this.canvasWidth), Utils.width(this._viewportTopL, "100%"), this.hasFrozenRows && (Utils.width(this._viewportBottomL, "100%"), Utils.width(this._canvasBottomL, this.canvasWidthL));
			this.viewportHasHScroll = this.canvasWidth >= this.viewportW - ((_b = (_a = this.scrollbarDimensions) == null ? void 0 : _a.width) != null ? _b : 0), Utils.width(this._headerRowSpacerL, this.canvasWidth + (this.viewportHasVScroll && (_d = (_c = this.scrollbarDimensions) == null ? void 0 : _c.width) != null ? _d : 0)), Utils.width(this._headerRowSpacerR, this.canvasWidth + (this.viewportHasVScroll && (_f = (_e = this.scrollbarDimensions) == null ? void 0 : _e.width) != null ? _f : 0)), this._options.createFooterRow && (Utils.width(this._footerRowSpacerL, this.canvasWidth + (this.viewportHasVScroll && (_h = (_g = this.scrollbarDimensions) == null ? void 0 : _g.width) != null ? _h : 0)), Utils.width(this._footerRowSpacerR, this.canvasWidth + (this.viewportHasVScroll && (_j = (_i = this.scrollbarDimensions) == null ? void 0 : _i.width) != null ? _j : 0))), (widthChanged || forceColumnWidthsUpdate) && this.applyColumnWidths();
		}
		disableSelection(target) {
			target.forEach((el) => {
				el.setAttribute("unselectable", "on"), el.style.mozUserSelect = "none", this._bindingEventService.bind(el, "selectstart", () => !1);
			});
		}
		getMaxSupportedCssHeight() {
			let supportedHeight = 1e6,
				testUpTo = navigator.userAgent.toLowerCase().match(/firefox/) ? this._options.ffMaxSupportedCssHeight : this._options.maxSupportedCssHeight,
				div = Utils.createDomElement("div", {
					style: {
						display: "hidden"
					}
				}, document.body);
			for (;;) {
				let test = supportedHeight * 2;
				Utils.height(div, test);
				let height = Utils.height(div);
				if (test > testUpTo || height !== test)
					break;
				supportedHeight = test;
			}
			return div.remove(), supportedHeight;
		}
		/** Get grid unique identifier */
		getUID() {
			return this.uid;
		}
		/** Get Header Column Width Difference in pixel */
		getHeaderColumnWidthDiff() {
			return this.headerColumnWidthDiff;
		}
		/** Get scrollbar dimensions */
		getScrollbarDimensions() {
			return this.scrollbarDimensions;
		}
		/** Get the displayed scrollbar dimensions */
		getDisplayedScrollbarDimensions() {
			var _a, _b, _c, _d;
			return {
				width: this.viewportHasVScroll && (_b = (_a = this.scrollbarDimensions) == null ? void 0 : _a.width) != null ? _b : 0,
				height: this.viewportHasHScroll && (_d = (_c = this.scrollbarDimensions) == null ? void 0 : _c.height) != null ? _d : 0
			};
		}
		/** Get the absolute column minimum width */
		getAbsoluteColumnMinWidth() {
			return this.absoluteColumnMinWidth;
		}
		// TODO:  this is static.  need to handle page mutation.
		bindAncestorScrollEvents() {
			let elem = this.hasFrozenRows && !this._options.frozenBottom ? this._canvasBottomL : this._canvasTopL;
			for (;
				(elem = elem.parentNode) !== document.body && elem;)
				(elem === this._viewportTopL || elem.scrollWidth !== elem.clientWidth || elem.scrollHeight !== elem.clientHeight) && (this._boundAncestors.push(elem), this._bindingEventService.bind(elem, "scroll", this.handleActiveCellPositionChange.bind(this)));
		}
		unbindAncestorScrollEvents() {
			this._boundAncestors.forEach((ancestor) => {
				this._bindingEventService.unbindByEventName(ancestor, "scroll");
			}), this._boundAncestors = [];
		}
		/**
		 * Updates an existing column definition and a corresponding header DOM element with the new title and tooltip.
		 * @param {Number|String} columnId Column id.
		 * @param {String} [title] New column name.
		 * @param {String} [toolTip] New column tooltip.
		 */
		updateColumnHeader(columnId, title, toolTip) {
			if (!this.initialized)
				return;
			let idx = this.getColumnIndex(columnId);
			if (!Utils.isDefined(idx))
				return;
			let columnDef = this.columns[idx],
				header = this.getColumnByIndex(idx);
			header && (title !== void 0 && (this.columns[idx].name = title), toolTip !== void 0 && (this.columns[idx].toolTip = toolTip), this.trigger(this.onBeforeHeaderCellDestroy, {
				node: header,
				column: columnDef,
				grid: this
			}), header.setAttribute("title", toolTip || ""), title !== void 0 && this.applyHtmlCode(header.children[0], title), this.trigger(this.onHeaderCellRendered, {
				node: header,
				column: columnDef,
				grid: this
			}));
		}
		/**
		 * Get the Header DOM element
		 * @param {C} columnDef - column definition
		 */
		getHeader(columnDef) {
			if (!columnDef)
				return this.hasFrozenColumns() ? this._headers : this._headerL;
			let idx = this.getColumnIndex(columnDef.id);
			return this.hasFrozenColumns() ? idx <= this._options.frozenColumn ? this._headerL : this._headerR : this._headerL;
		}
		/**
		 * Get a specific Header Column DOM element by its column Id or index
		 * @param {Number|String} columnIdOrIdx - column Id or index
		 */
		getHeaderColumn(columnIdOrIdx) {
			let idx = typeof columnIdOrIdx == "number" ? columnIdOrIdx : this.getColumnIndex(columnIdOrIdx),
				targetHeader = this.hasFrozenColumns() ? idx <= this._options.frozenColumn ? this._headerL : this._headerR : this._headerL,
				targetIndex = this.hasFrozenColumns() ? idx <= this._options.frozenColumn ? idx : idx - this._options.frozenColumn - 1 : idx;
			return targetHeader.children[targetIndex];
		}
		/** Get the Header Row DOM element */
		getHeaderRow() {
			return this.hasFrozenColumns() ? this._headerRows : this._headerRows[0];
		}
		/** Get the Footer DOM element */
		getFooterRow() {
			return this.hasFrozenColumns() ? this._footerRow : this._footerRow[0];
		}
		/** @alias `getPreHeaderPanelLeft` */
		getPreHeaderPanel() {
			return this._preHeaderPanel;
		}
		/** Get the Pre-Header Panel Left DOM node element */
		getPreHeaderPanelLeft() {
			return this._preHeaderPanel;
		}
		/** Get the Pre-Header Panel Right DOM node element */
		getPreHeaderPanelRight() {
			return this._preHeaderPanelR;
		}
		/**
		 * Get Header Row Column DOM element by its column Id or index
		 * @param {Number|String} columnIdOrIdx - column Id or index
		 */
		getHeaderRowColumn(columnIdOrIdx) {
			let idx = typeof columnIdOrIdx == "number" ? columnIdOrIdx : this.getColumnIndex(columnIdOrIdx),
				headerRowTarget;
			return this.hasFrozenColumns() ? idx <= this._options.frozenColumn ? headerRowTarget = this._headerRowL : (headerRowTarget = this._headerRowR, idx -= this._options.frozenColumn + 1) : headerRowTarget = this._headerRowL, headerRowTarget.children[idx];
		}
		/**
		 * Get the Footer Row Column DOM element by its column Id or index
		 * @param {Number|String} columnIdOrIdx - column Id or index
		 */
		getFooterRowColumn(columnIdOrIdx) {
			let idx = typeof columnIdOrIdx == "number" ? columnIdOrIdx : this.getColumnIndex(columnIdOrIdx),
				footerRowTarget;
			return this.hasFrozenColumns() ? idx <= this._options.frozenColumn ? footerRowTarget = this._footerRowL : (footerRowTarget = this._footerRowR, idx -= this._options.frozenColumn + 1) : footerRowTarget = this._footerRowL, footerRowTarget.children[idx];
		}
		createColumnFooter() {
			if (this._options.createFooterRow) {
				this._footerRow.forEach((footer) => {
					footer.querySelectorAll(".slick-footerrow-column").forEach((column) => {
						let columnDef = Utils.storage.get(column, "column");
						this.trigger(this.onBeforeFooterRowCellDestroy, {
							node: column,
							column: columnDef,
							grid: this
						});
					});
				}), Utils.emptyElement(this._footerRowL), Utils.emptyElement(this._footerRowR);
				for (let i = 0; i < this.columns.length; i++) {
					let m = this.columns[i];
					if (!m || m.hidden)
						continue;
					let footerRowCell = Utils.createDomElement("div", {
							className: `ui-state-default slick-state-default slick-footerrow-column l${i} r${i}`
						}, this.hasFrozenColumns() && i > this._options.frozenColumn ? this._footerRowR : this._footerRowL),
						className = this.hasFrozenColumns() && i <= this._options.frozenColumn ? "frozen" : null;
					className && footerRowCell.classList.add(className), Utils.storage.put(footerRowCell, "column", m), this.trigger(this.onFooterRowCellRendered, {
						node: footerRowCell,
						column: m,
						grid: this
					});
				}
			}
		}
		handleHeaderMouseHoverOn(e) {
			e == null || e.target.classList.add("ui-state-hover", "slick-state-hover");
		}
		handleHeaderMouseHoverOff(e) {
			e == null || e.target.classList.remove("ui-state-hover", "slick-state-hover");
		}
		createColumnHeaders() {
			this._headers.forEach((header) => {
				header.querySelectorAll(".slick-header-column").forEach((column) => {
					let columnDef = Utils.storage.get(column, "column");
					columnDef && this.trigger(this.onBeforeHeaderCellDestroy, {
						node: column,
						column: columnDef,
						grid: this
					});
				});
			}), Utils.emptyElement(this._headerL), Utils.emptyElement(this._headerR), this.getHeadersWidth(), Utils.width(this._headerL, this.headersWidthL), Utils.width(this._headerR, this.headersWidthR), this._headerRows.forEach((row) => {
				row.querySelectorAll(".slick-headerrow-column").forEach((column) => {
					let columnDef = Utils.storage.get(column, "column");
					columnDef && this.trigger(this.onBeforeHeaderRowCellDestroy, {
						node: this,
						column: columnDef,
						grid: this
					});
				});
			}), Utils.emptyElement(this._headerRowL), Utils.emptyElement(this._headerRowR), this._options.createFooterRow && (this._footerRowL.querySelectorAll(".slick-footerrow-column").forEach((column) => {
				let columnDef = Utils.storage.get(column, "column");
				columnDef && this.trigger(this.onBeforeFooterRowCellDestroy, {
					node: this,
					column: columnDef,
					grid: this
				});
			}), Utils.emptyElement(this._footerRowL), this.hasFrozenColumns() && (this._footerRowR.querySelectorAll(".slick-footerrow-column").forEach((column) => {
				let columnDef = Utils.storage.get(column, "column");
				columnDef && this.trigger(this.onBeforeFooterRowCellDestroy, {
					node: this,
					column: columnDef,
					grid: this
				});
			}), Utils.emptyElement(this._footerRowR)));
			for (let i = 0; i < this.columns.length; i++) {
				let m = this.columns[i],
					headerTarget = this.hasFrozenColumns() ? i <= this._options.frozenColumn ? this._headerL : this._headerR : this._headerL,
					headerRowTarget = this.hasFrozenColumns() ? i <= this._options.frozenColumn ? this._headerRowL : this._headerRowR : this._headerRowL,
					header = Utils.createDomElement("div", {
						id: `${this.uid + m.id}`,
						dataset: {
							id: String(m.id)
						},
						className: "ui-state-default slick-state-default slick-header-column",
						title: m.toolTip || ""
					}, headerTarget),
					colNameElm = Utils.createDomElement("span", {
						className: "slick-column-name"
					}, header);
				this.applyHtmlCode(colNameElm, m.name), Utils.width(header, m.width - this.headerColumnWidthDiff);
				let classname = m.headerCssClass || null;
				if (classname && header.classList.add(...classname.split(" ")), classname = this.hasFrozenColumns() && i <= this._options.frozenColumn ? "frozen" : null, classname && header.classList.add(classname), this._bindingEventService.bind(header, "mouseenter", this.handleHeaderMouseEnter.bind(this)), this._bindingEventService.bind(header, "mouseleave", this.handleHeaderMouseLeave.bind(this)), Utils.storage.put(header, "column", m), (this._options.enableColumnReorder || m.sortable) && (this._bindingEventService.bind(header, "mouseenter", this.handleHeaderMouseHoverOn.bind(this)), this._bindingEventService.bind(header, "mouseleave", this.handleHeaderMouseHoverOff.bind(this))), m.hasOwnProperty("headerCellAttrs") && m.headerCellAttrs instanceof Object)
					for (let key in m.headerCellAttrs)
						m.headerCellAttrs.hasOwnProperty(key) && header.setAttribute(key, m.headerCellAttrs[key]);
				if (m.sortable && (header.classList.add("slick-header-sortable"), Utils.createDomElement("div", {
						className: `slick-sort-indicator ${this._options.numberedMultiColumnSort && !this._options.sortColNumberInSeparateSpan ? " slick-sort-indicator-numbered" : ""}`
					}, header), this._options.numberedMultiColumnSort && this._options.sortColNumberInSeparateSpan && Utils.createDomElement("div", {
						className: "slick-sort-indicator-numbered"
					}, header)), this.trigger(this.onHeaderCellRendered, {
						node: header,
						column: m,
						grid: this
					}), this._options.showHeaderRow) {
					let headerRowCell = Utils.createDomElement("div", {
							className: `ui-state-default slick-state-default slick-headerrow-column l${i} r${i}`
						}, headerRowTarget),
						frozenClasses = this.hasFrozenColumns() && i <= this._options.frozenColumn ? "frozen" : null;
					frozenClasses && headerRowCell.classList.add(frozenClasses), this._bindingEventService.bind(headerRowCell, "mouseenter", this.handleHeaderRowMouseEnter.bind(this)), this._bindingEventService.bind(headerRowCell, "mouseleave", this.handleHeaderRowMouseLeave.bind(this)), Utils.storage.put(headerRowCell, "column", m), this.trigger(this.onHeaderRowCellRendered, {
						node: headerRowCell,
						column: m,
						grid: this
					});
				}
				if (this._options.createFooterRow && this._options.showFooterRow) {
					let footerRowTarget = this.hasFrozenColumns() ? i <= this._options.frozenColumn ? this._footerRow[0] : this._footerRow[1] : this._footerRow[0],
						footerRowCell = Utils.createDomElement("div", {
							className: `ui-state-default slick-state-default slick-footerrow-column l${i} r${i}`
						}, footerRowTarget);
					Utils.storage.put(footerRowCell, "column", m), this.trigger(this.onFooterRowCellRendered, {
						node: footerRowCell,
						column: m,
						grid: this
					});
				}
			}
			this.setSortColumns(this.sortColumns), this.setupColumnResize(), this._options.enableColumnReorder && (typeof this._options.enableColumnReorder == "function" ? this._options.enableColumnReorder(this, this._headers, this.headerColumnWidthDiff, this.setColumns, this.setupColumnResize, this.columns, this.getColumnIndex, this.uid, this.trigger) : this.setupColumnReorder());
		}
		setupColumnSort() {
			this._headers.forEach((header) => {
				this._bindingEventService.bind(header, "click", (e) => {
					var _a;
					if (this.columnResizeDragging || e.target.classList.contains("slick-resizable-handle"))
						return;
					let coll = e.target.closest(".slick-header-column");
					if (!coll)
						return;
					let column = Utils.storage.get(coll, "column");
					if (column.sortable) {
						if (!((_a = this.getEditorLock()) != null && _a.commitCurrentEdit()))
							return;
						let previousSortColumns = this.sortColumns.slice(),
							sortColumn = null,
							i = 0;
						for (; i < this.sortColumns.length; i++)
							if (this.sortColumns[i].columnId === column.id) {
								sortColumn = this.sortColumns[i], sortColumn.sortAsc = !sortColumn.sortAsc;
								break;
							}
						let hadSortCol = !!sortColumn;
						this._options.tristateMultiColumnSort ? (sortColumn || (sortColumn = {
							columnId: column.id,
							sortAsc: column.defaultSortAsc,
							sortCol: column
						}), hadSortCol && sortColumn.sortAsc && (this.sortColumns.splice(i, 1), sortColumn = null), this._options.multiColumnSort || (this.sortColumns = []), sortColumn && (!hadSortCol || !this._options.multiColumnSort) && this.sortColumns.push(sortColumn)) : e.metaKey && this._options.multiColumnSort ? sortColumn && this.sortColumns.splice(i, 1) : ((!e.shiftKey && !e.metaKey || !this._options.multiColumnSort) && (this.sortColumns = []), sortColumn ? this.sortColumns.length === 0 && this.sortColumns.push(sortColumn) : (sortColumn = {
							columnId: column.id,
							sortAsc: column.defaultSortAsc,
							sortCol: column
						}, this.sortColumns.push(sortColumn)));
						let onSortArgs;
						this._options.multiColumnSort ? onSortArgs = {
							multiColumnSort: !0,
							previousSortColumns,
							sortCols: this.sortColumns.map((col) => ({
								columnId: this.columns[this.getColumnIndex(col.columnId)].id,
								sortCol: this.columns[this.getColumnIndex(col.columnId)],
								sortAsc: col.sortAsc
							}))
						} : onSortArgs = {
							multiColumnSort: !1,
							previousSortColumns,
							columnId: this.sortColumns.length > 0 ? column.id : null,
							sortCol: this.sortColumns.length > 0 ? column : null,
							sortAsc: this.sortColumns.length > 0 ? this.sortColumns[0].sortAsc : !0
						}, this.trigger(this.onBeforeSort, onSortArgs, e).getReturnValue() !== !1 && (this.setSortColumns(this.sortColumns), this.trigger(this.onSort, onSortArgs, e));
					}
				});
			});
		}
		currentPositionInHeader(id) {
			let currentPosition = 0;
			return this._headers.forEach((header) => {
				header.querySelectorAll(".slick-header-column").forEach((column, i) => {
					column.id === id && (currentPosition = i);
				});
			}), currentPosition;
		}
		remove(arr, elem) {
			let index = arr.lastIndexOf(elem);
			index > -1 && (arr.splice(index, 1), this.remove(arr, elem));
		}
		setupColumnReorder() {
			var _a, _b;
			(_a = this.sortableSideLeftInstance) == null || _a.destroy(), (_b = this.sortableSideRightInstance) == null || _b.destroy();
			let columnScrollTimer = null,
				scrollColumnsRight = () => this._viewportScrollContainerX.scrollLeft = this._viewportScrollContainerX.scrollLeft + 10,
				scrollColumnsLeft = () => this._viewportScrollContainerX.scrollLeft = this._viewportScrollContainerX.scrollLeft - 10,
				canDragScroll, sortableOptions = {
					animation: 50,
					direction: "horizontal",
					chosenClass: "slick-header-column-active",
					ghostClass: "slick-sortable-placeholder",
					draggable: ".slick-header-column",
					dragoverBubble: !1,
					revertClone: !0,
					scroll: !this.hasFrozenColumns(),
					// enable auto-scroll
					onStart: (e) => {
						canDragScroll = !this.hasFrozenColumns() || Utils.offset(e.item).left > Utils.offset(this._viewportScrollContainerX).left, canDragScroll && e.originalEvent.pageX > this._container.clientWidth ? columnScrollTimer || (columnScrollTimer = setInterval(scrollColumnsRight, 100)) : canDragScroll && e.originalEvent.pageX < Utils.offset(this._viewportScrollContainerX).left ? columnScrollTimer || (columnScrollTimer = setInterval(scrollColumnsLeft, 100)) : (clearInterval(columnScrollTimer), columnScrollTimer = null);
					},
					onEnd: (e) => {
						var _a2, _b2, _c, _d, _e;
						clearInterval(columnScrollTimer), columnScrollTimer = null;
						let limit;
						if (!((_a2 = this.getEditorLock()) != null && _a2.commitCurrentEdit()))
							return;
						let reorderedIds = (_c = (_b2 = this.sortableSideLeftInstance) == null ? void 0 : _b2.toArray()) != null ? _c : [];
						reorderedIds = reorderedIds.concat((_e = (_d = this.sortableSideRightInstance) == null ? void 0 : _d.toArray()) != null ? _e : []);
						let reorderedColumns = [];
						for (let i = 0; i < reorderedIds.length; i++)
							reorderedColumns.push(this.columns[this.getColumnIndex(reorderedIds[i])]);
						this.setColumns(reorderedColumns), this.trigger(this.onColumnsReordered, {
							impactedColumns: this.getImpactedColumns(limit)
						}), e.stopPropagation(), this.setupColumnResize(), this.activeCellNode && this.setFocus();
					}
				};
			this.sortableSideLeftInstance = Sortable.create(this._headerL, sortableOptions), this.sortableSideRightInstance = Sortable.create(this._headerR, sortableOptions);
		}
		getHeaderChildren() {
			let a = Array.from(this._headers[0].children),
				b = Array.from(this._headers[1].children);
			return a.concat(b);
		}
		getImpactedColumns(limit) {
			let impactedColumns = [];
			if (limit)
				for (let i = limit.start; i <= limit.end; i++)
					impactedColumns.push(this.columns[i]);
			else
				impactedColumns = this.columns;
			return impactedColumns;
		}
		handleResizeableHandleDoubleClick(evt) {
			let triggeredByColumn = evt.target.parentElement.id.replace(this.uid, "");
			this.trigger(this.onColumnsResizeDblClick, {
				triggeredByColumn
			});
		}
		setupColumnResize() {
			if (typeof Resizable == "undefined")
				throw new Error('Slick.Resizable is undefined, make sure to import "slick.interactions.js"');
			let j, k, c, pageX, minPageX, maxPageX, firstResizable, lastResizable = -1,
				frozenLeftColMaxWidth = 0,
				children = this.getHeaderChildren();
			for (let i = 0; i < children.length; i++)
				children[i].querySelectorAll(".slick-resizable-handle").forEach((handle) => handle.remove()), !(i >= this.columns.length || !this.columns[i] || this.columns[i].hidden) && this.columns[i].resizable && (firstResizable === void 0 && (firstResizable = i), lastResizable = i);
			if (firstResizable !== void 0)
				for (let i = 0; i < children.length; i++) {
					let colElm = children[i];
					if (i >= this.columns.length || !this.columns[i] || this.columns[i].hidden || i < firstResizable || this._options.forceFitColumns && i >= lastResizable)
						continue;
					let resizeableHandle = Utils.createDomElement("div", {
						className: "slick-resizable-handle",
						role: "separator",
						ariaOrientation: "horizontal"
					}, colElm);
					this._bindingEventService.bind(resizeableHandle, "dblclick", this.handleResizeableHandleDoubleClick.bind(this)), this.slickResizableInstances.push(
						Resizable({
							resizeableElement: colElm,
							resizeableHandleElement: resizeableHandle,
							onResizeStart: (e, resizeElms) => {
								var _a;
								let targetEvent = e.touches ? e.changedTouches[0] : e;
								if (!((_a = this.getEditorLock()) != null && _a.commitCurrentEdit()))
									return !1;
								pageX = targetEvent.pageX, frozenLeftColMaxWidth = 0, resizeElms.resizeableElement.classList.add("slick-header-column-active");
								let shrinkLeewayOnRight = null,
									stretchLeewayOnRight = null;
								for (let pw = 0; pw < children.length; pw++)
									pw >= this.columns.length || !this.columns[pw] || this.columns[pw].hidden || (this.columns[pw].previousWidth = children[pw].offsetWidth);
								if (this._options.forceFitColumns)
									for (shrinkLeewayOnRight = 0, stretchLeewayOnRight = 0, j = i + 1; j < this.columns.length; j++)
										c = this.columns[j], c && c.resizable && !c.hidden && (stretchLeewayOnRight !== null && (c.maxWidth ? stretchLeewayOnRight += c.maxWidth - (c.previousWidth || 0) : stretchLeewayOnRight = null), shrinkLeewayOnRight += (c.previousWidth || 0) - Math.max(c.minWidth || 0, this.absoluteColumnMinWidth));
								let shrinkLeewayOnLeft = 0,
									stretchLeewayOnLeft = 0;
								for (j = 0; j <= i; j++)
									c = this.columns[j], c && c.resizable && !c.hidden && (stretchLeewayOnLeft !== null && (c.maxWidth ? stretchLeewayOnLeft += c.maxWidth - (c.previousWidth || 0) : stretchLeewayOnLeft = null), shrinkLeewayOnLeft += (c.previousWidth || 0) - Math.max(c.minWidth || 0, this.absoluteColumnMinWidth));
								shrinkLeewayOnRight === null && (shrinkLeewayOnRight = 1e5), shrinkLeewayOnLeft === null && (shrinkLeewayOnLeft = 1e5), stretchLeewayOnRight === null && (stretchLeewayOnRight = 1e5), stretchLeewayOnLeft === null && (stretchLeewayOnLeft = 1e5), maxPageX = pageX + Math.min(shrinkLeewayOnRight, stretchLeewayOnLeft), minPageX = pageX - Math.min(shrinkLeewayOnLeft, stretchLeewayOnRight);
							},
							onResize: (e, resizeElms) => {
								var _a, _b;
								let targetEvent = e.touches ? e.changedTouches[0] : e;
								this.columnResizeDragging = !0;
								let actualMinWidth, d = Math.min(maxPageX, Math.max(minPageX, targetEvent.pageX)) - pageX,
									x, newCanvasWidthL = 0,
									newCanvasWidthR = 0,
									viewportWidth = this.viewportHasVScroll ? this.viewportW - ((_b = (_a = this.scrollbarDimensions) == null ? void 0 : _a.width) != null ? _b : 0) : this.viewportW;
								if (d < 0) {
									for (x = d, j = i; j >= 0; j--)
										c = this.columns[j], c && c.resizable && !c.hidden && (actualMinWidth = Math.max(c.minWidth || 0, this.absoluteColumnMinWidth), x && (c.previousWidth || 0) + x < actualMinWidth ? (x += (c.previousWidth || 0) - actualMinWidth, c.width = actualMinWidth) : (c.width = (c.previousWidth || 0) + x, x = 0));
									for (k = 0; k <= i; k++)
										c = this.columns[k], !(!c || c.hidden) && (this.hasFrozenColumns() && k > this._options.frozenColumn ? newCanvasWidthR += c.width || 0 : newCanvasWidthL += c.width || 0);
									if (this._options.forceFitColumns)
										for (x = -d, j = i + 1; j < this.columns.length; j++)
											c = this.columns[j], !(!c || c.hidden) && c.resizable && (x && c.maxWidth && c.maxWidth - (c.previousWidth || 0) < x ? (x -= c.maxWidth - (c.previousWidth || 0), c.width = c.maxWidth) : (c.width = (c.previousWidth || 0) + x, x = 0), this.hasFrozenColumns() && j > this._options.frozenColumn ? newCanvasWidthR += c.width || 0 : newCanvasWidthL += c.width || 0);
									else
										for (j = i + 1; j < this.columns.length; j++)
											c = this.columns[j], !(!c || c.hidden) && (this.hasFrozenColumns() && j > this._options.frozenColumn ? newCanvasWidthR += c.width || 0 : newCanvasWidthL += c.width || 0);
									if (this._options.forceFitColumns)
										for (x = -d, j = i + 1; j < this.columns.length; j++)
											c = this.columns[j], !(!c || c.hidden) && c.resizable && (x && c.maxWidth && c.maxWidth - (c.previousWidth || 0) < x ? (x -= c.maxWidth - (c.previousWidth || 0), c.width = c.maxWidth) : (c.width = (c.previousWidth || 0) + x, x = 0));
								} else {
									for (x = d, newCanvasWidthL = 0, newCanvasWidthR = 0, j = i; j >= 0; j--)
										if (c = this.columns[j], !(!c || c.hidden) && c.resizable)
											if (x && c.maxWidth && c.maxWidth - (c.previousWidth || 0) < x)
												x -= c.maxWidth - (c.previousWidth || 0), c.width = c.maxWidth;
											else {
												let newWidth = (c.previousWidth || 0) + x,
													resizedCanvasWidthL = this.canvasWidthL + x;
												this.hasFrozenColumns() && j <= this._options.frozenColumn ? (newWidth > frozenLeftColMaxWidth && resizedCanvasWidthL < viewportWidth - this._options.frozenRightViewportMinWidth && (frozenLeftColMaxWidth = newWidth), c.width = resizedCanvasWidthL + this._options.frozenRightViewportMinWidth > viewportWidth ? frozenLeftColMaxWidth : newWidth) : c.width = newWidth, x = 0;
											}
									for (k = 0; k <= i; k++)
										c = this.columns[k], !(!c || c.hidden) && (this.hasFrozenColumns() && k > this._options.frozenColumn ? newCanvasWidthR += c.width || 0 : newCanvasWidthL += c.width || 0);
									if (this._options.forceFitColumns)
										for (x = -d, j = i + 1; j < this.columns.length; j++)
											c = this.columns[j], !(!c || c.hidden) && c.resizable && (actualMinWidth = Math.max(c.minWidth || 0, this.absoluteColumnMinWidth), x && (c.previousWidth || 0) + x < actualMinWidth ? (x += (c.previousWidth || 0) - actualMinWidth, c.width = actualMinWidth) : (c.width = (c.previousWidth || 0) + x, x = 0), this.hasFrozenColumns() && j > this._options.frozenColumn ? newCanvasWidthR += c.width || 0 : newCanvasWidthL += c.width || 0);
									else
										for (j = i + 1; j < this.columns.length; j++)
											c = this.columns[j], !(!c || c.hidden) && (this.hasFrozenColumns() && j > this._options.frozenColumn ? newCanvasWidthR += c.width || 0 : newCanvasWidthL += c.width || 0);
								}
								this.hasFrozenColumns() && newCanvasWidthL !== this.canvasWidthL && (Utils.width(this._headerL, newCanvasWidthL + 1e3), Utils.setStyleSize(this._paneHeaderR, "left", newCanvasWidthL)), this.applyColumnHeaderWidths(), this._options.syncColumnCellResize && this.applyColumnWidths(), this.trigger(this.onColumnsDrag, {
									triggeredByColumn: resizeElms.resizeableElement,
									resizeHandle: resizeElms.resizeableHandleElement
								});
							},
							onResizeEnd: (_e, resizeElms) => {
								resizeElms.resizeableElement.classList.remove("slick-header-column-active");
								let triggeredByColumn = resizeElms.resizeableElement.id.replace(this.uid, "");
								this.trigger(this.onBeforeColumnsResize, {
									triggeredByColumn
								}).getReturnValue() === !0 && this.applyColumnHeaderWidths();
								let newWidth;
								for (j = 0; j < this.columns.length; j++)
									c = this.columns[j], !(!c || c.hidden) && (newWidth = children[j].offsetWidth, c.previousWidth !== newWidth && c.rerenderOnResize && this.invalidateAllRows());
								this.updateCanvasWidth(!0), this.render(), this.trigger(this.onColumnsResized, {
									triggeredByColumn
								}), setTimeout(() => {
									this.columnResizeDragging = !1;
								}, 300);
							}
						})
					);
				}
		}
		getVBoxDelta(el) {
			let p = ["borderTopWidth", "borderBottomWidth", "paddingTop", "paddingBottom"],
				styles = getComputedStyle(el),
				delta = 0;
			return p.forEach((val) => delta += Utils.toFloat(styles[val])), delta;
		}
		setFrozenOptions() {
			if (this._options.frozenColumn = this._options.frozenColumn >= 0 && this._options.frozenColumn < this.columns.length ? parseInt(this._options.frozenColumn, 10) : -1, this._options.frozenRow > -1) {
				this.hasFrozenRows = !0, this.frozenRowsHeight = this._options.frozenRow * this._options.rowHeight;
				let dataLength = this.getDataLength();
				this.actualFrozenRow = this._options.frozenBottom ? dataLength - this._options.frozenRow : this._options.frozenRow;
			} else
				this.hasFrozenRows = !1;
		}
		setPaneVisibility() {
			this.hasFrozenColumns() ? (Utils.show(this._paneHeaderR), Utils.show(this._paneTopR), this.hasFrozenRows ? (Utils.show(this._paneBottomL), Utils.show(this._paneBottomR)) : (Utils.hide(this._paneBottomR), Utils.hide(this._paneBottomL))) : (Utils.hide(this._paneHeaderR), Utils.hide(this._paneTopR), Utils.hide(this._paneBottomR), this.hasFrozenRows ? Utils.show(this._paneBottomL) : (Utils.hide(this._paneBottomR), Utils.hide(this._paneBottomL)));
		}
		setOverflow() {
			this._viewportTopL.style.overflowX = this.hasFrozenColumns() ? this.hasFrozenRows && !this._options.alwaysAllowHorizontalScroll ? "hidden" : "scroll" : this.hasFrozenRows && !this._options.alwaysAllowHorizontalScroll ? "hidden" : "auto", this._viewportTopL.style.overflowY = !this.hasFrozenColumns() && this._options.alwaysShowVerticalScroll ? "scroll" : this.hasFrozenColumns() ? (this.hasFrozenRows, "hidden") : this.hasFrozenRows ? "scroll" : "auto", this._viewportTopR.style.overflowX = this.hasFrozenColumns() ? this.hasFrozenRows && !this._options.alwaysAllowHorizontalScroll ? "hidden" : "scroll" : this.hasFrozenRows && !this._options.alwaysAllowHorizontalScroll ? "hidden" : "auto", this._viewportTopR.style.overflowY = this._options.alwaysShowVerticalScroll ? "scroll" : this.hasFrozenColumns() ? this.hasFrozenRows ? "scroll" : "auto" : this.hasFrozenRows ? "scroll" : "auto", this._viewportBottomL.style.overflowX = this.hasFrozenColumns() ? this.hasFrozenRows && !this._options.alwaysAllowHorizontalScroll ? "scroll" : "auto" : (this.hasFrozenRows && !this._options.alwaysAllowHorizontalScroll, "auto"), this._viewportBottomL.style.overflowY = !this.hasFrozenColumns() && this._options.alwaysShowVerticalScroll ? "scroll" : this.hasFrozenColumns() ? (this.hasFrozenRows, "hidden") : this.hasFrozenRows ? "scroll" : "auto", this._viewportBottomR.style.overflowX = this.hasFrozenColumns() ? this.hasFrozenRows && !this._options.alwaysAllowHorizontalScroll ? "scroll" : "auto" : (this.hasFrozenRows && !this._options.alwaysAllowHorizontalScroll, "auto"), this._viewportBottomR.style.overflowY = this._options.alwaysShowVerticalScroll ? "scroll" : this.hasFrozenColumns() ? (this.hasFrozenRows, "auto") : (this.hasFrozenRows, "auto"), this._options.viewportClass && (this._viewportTopL.classList.add(...this._options.viewportClass.split(" ")), this._viewportTopR.classList.add(...this._options.viewportClass.split(" ")), this._viewportBottomL.classList.add(...this._options.viewportClass.split(" ")), this._viewportBottomR.classList.add(...this._options.viewportClass.split(" ")));
		}
		setScroller() {
			this.hasFrozenColumns() ? (this._headerScrollContainer = this._headerScrollerR, this._headerRowScrollContainer = this._headerRowScrollerR, this._footerRowScrollContainer = this._footerRowScrollerR, this.hasFrozenRows ? this._options.frozenBottom ? (this._viewportScrollContainerX = this._viewportBottomR, this._viewportScrollContainerY = this._viewportTopR) : this._viewportScrollContainerX = this._viewportScrollContainerY = this._viewportBottomR : this._viewportScrollContainerX = this._viewportScrollContainerY = this._viewportTopR) : (this._headerScrollContainer = this._headerScrollerL, this._headerRowScrollContainer = this._headerRowScrollerL, this._footerRowScrollContainer = this._footerRowScrollerL, this.hasFrozenRows ? this._options.frozenBottom ? (this._viewportScrollContainerX = this._viewportBottomL, this._viewportScrollContainerY = this._viewportTopL) : this._viewportScrollContainerX = this._viewportScrollContainerY = this._viewportBottomL : this._viewportScrollContainerX = this._viewportScrollContainerY = this._viewportTopL);
		}
		measureCellPaddingAndBorder() {
			let h = ["borderLeftWidth", "borderRightWidth", "paddingLeft", "paddingRight"],
				v = ["borderTopWidth", "borderBottomWidth", "paddingTop", "paddingBottom"],
				header = this._headers[0];
			this.headerColumnWidthDiff = this.headerColumnHeightDiff = 0, this.cellWidthDiff = this.cellHeightDiff = 0;
			let el = Utils.createDomElement("div", {
					className: "ui-state-default slick-state-default slick-header-column",
					style: {
						visibility: "hidden"
					},
					textContent: "-"
				}, header),
				style = getComputedStyle(el);
			style.boxSizing !== "border-box" && (h.forEach((val) => this.headerColumnWidthDiff += Utils.toFloat(style[val])), v.forEach((val) => this.headerColumnHeightDiff += Utils.toFloat(style[val]))), el.remove();
			let r = Utils.createDomElement("div", {
				className: "slick-row"
			}, this._canvas[0]);
			el = Utils.createDomElement("div", {
				className: "slick-cell",
				id: "",
				style: {
					visibility: "hidden"
				},
				textContent: "-"
			}, r), style = getComputedStyle(el), style.boxSizing !== "border-box" && (h.forEach((val) => this.cellWidthDiff += Utils.toFloat(style[val])), v.forEach((val) => this.cellHeightDiff += Utils.toFloat(style[val]))), r.remove(), this.absoluteColumnMinWidth = Math.max(this.headerColumnWidthDiff, this.cellWidthDiff);
		}
		createCssRules() {
			this._style = document.createElement("style"), this._style.nonce = this._options.nonce || "", (this._options.shadowRoot || document.head).appendChild(this._style);
			let rowHeight = this._options.rowHeight - this.cellHeightDiff,
				rules = [
					`.${this.uid} .slick-group-header-column { left: 1000px; }`,
					`.${this.uid} .slick-header-column { left: 1000px; }`,
					`.${this.uid} .slick-top-panel { height: ${this._options.topPanelHeight}px; }`,
					`.${this.uid} .slick-preheader-panel { height: ${this._options.preHeaderPanelHeight}px; }`,
					`.${this.uid} .slick-headerrow-columns { height: ${this._options.headerRowHeight}px; }`,
					`.${this.uid} .slick-footerrow-columns { height: ${this._options.footerRowHeight}px; }`,
					`.${this.uid} .slick-cell { height: ${rowHeight}px; }`,
					`.${this.uid} .slick-row { height: ${this._options.rowHeight}px; }`
				],
				sheet = this._style.sheet;
			if (sheet) {
				for (let rule of rules)
					sheet.insertRule(rule);
				for (let i = 0; i < this.columns.length; i++)
					!this.columns[i] || this.columns[i].hidden || (sheet.insertRule(`.${this.uid} .l${i} { }`), sheet.insertRule(`.${this.uid} .r${i} { }`));
			} else
				this.createCssRulesAlternative(rules);
		}
		/** Create CSS rules via template in case the first approach with createElement('style') doesn't work */
		createCssRulesAlternative(rules) {
			let template = document.createElement("template");
			template.innerHTML = '<style type="text/css" rel="stylesheet" />', this._style = template.content.firstChild, (this._options.shadowRoot || document.head).appendChild(this._style);
			for (let i = 0; i < this.columns.length; i++)
				!this.columns[i] || this.columns[i].hidden || (rules.push(`.${this.uid} .l${i} { }`), rules.push(`.${this.uid} .r${i} { }`));
			this._style.styleSheet ? this._style.styleSheet.cssText = rules.join(" ") : this._style.appendChild(document.createTextNode(rules.join(" ")));
		}
		getColumnCssRules(idx) {
			let i;
			if (!this.stylesheet) {
				let sheets = (this._options.shadowRoot || document).styleSheets;
				for (i = 0; i < sheets.length; i++)
					if ((sheets[i].ownerNode || sheets[i].owningElement) === this._style) {
						this.stylesheet = sheets[i];
						break;
					}
				if (!this.stylesheet)
					throw new Error("SlickGrid Cannot find stylesheet.");
				this.columnCssRulesL = [], this.columnCssRulesR = [];
				let cssRules = this.stylesheet.cssRules || this.stylesheet.rules,
					matches, columnIdx;
				for (i = 0; i < cssRules.length; i++) {
					let selector = cssRules[i].selectorText;
					(matches = /\.l\d+/.exec(selector)) ? (columnIdx = parseInt(matches[0].substr(2, matches[0].length - 2), 10), this.columnCssRulesL[columnIdx] = cssRules[i]) : (matches = /\.r\d+/.exec(selector)) && (columnIdx = parseInt(matches[0].substr(2, matches[0].length - 2), 10), this.columnCssRulesR[columnIdx] = cssRules[i]);
				}
			}
			return {
				left: this.columnCssRulesL[idx],
				right: this.columnCssRulesR[idx]
			};
		}
		removeCssRules() {
			var _a;
			(_a = this._style) == null || _a.remove(), this.stylesheet = null;
		}
		/**
		 * Destroy (dispose) of SlickGrid
		 * @param {boolean} shouldDestroyAllElements - do we want to destroy (nullify) all DOM elements as well? This help in avoiding mem leaks
		 */
		destroy(shouldDestroyAllElements) {
			var _a, _b, _c, _d;
			this._bindingEventService.unbindAll(), this.slickDraggableInstance = this.destroyAllInstances(this.slickDraggableInstance), this.slickMouseWheelInstances = this.destroyAllInstances(this.slickMouseWheelInstances), this.slickResizableInstances = this.destroyAllInstances(this.slickResizableInstances), (_a = this.getEditorLock()) == null || _a.cancelCurrentEdit(), this.trigger(this.onBeforeDestroy, {});
			let i = this.plugins.length;
			for (; i--;)
				this.unregisterPlugin(this.plugins[i]);
			this._options.enableColumnReorder && typeof((_b = this.sortableSideLeftInstance) == null ? void 0 : _b.destroy) == "function" && ((_c = this.sortableSideLeftInstance) == null || _c.destroy(), (_d = this.sortableSideRightInstance) == null || _d.destroy()), this.unbindAncestorScrollEvents(), this._bindingEventService.unbindByEventName(this._container, "resize"), this.removeCssRules(), this._canvas.forEach((element) => {
				this._bindingEventService.unbindByEventName(element, "keydown"), this._bindingEventService.unbindByEventName(element, "click"), this._bindingEventService.unbindByEventName(element, "dblclick"), this._bindingEventService.unbindByEventName(element, "contextmenu"), this._bindingEventService.unbindByEventName(element, "mouseover"), this._bindingEventService.unbindByEventName(element, "mouseout");
			}), this._viewport.forEach((view) => {
				this._bindingEventService.unbindByEventName(view, "scroll");
			}), this._headerScroller.forEach((el) => {
				this._bindingEventService.unbindByEventName(el, "contextmenu"), this._bindingEventService.unbindByEventName(el, "click");
			}), this._headerRowScroller.forEach((scroller) => {
				this._bindingEventService.unbindByEventName(scroller, "scroll");
			}), this._footerRow && this._footerRow.forEach((footer) => {
				this._bindingEventService.unbindByEventName(footer, "contextmenu"), this._bindingEventService.unbindByEventName(footer, "click");
			}), this._footerRowScroller && this._footerRowScroller.forEach((scroller) => {
				this._bindingEventService.unbindByEventName(scroller, "scroll");
			}), this._preHeaderPanelScroller && this._bindingEventService.unbindByEventName(this._preHeaderPanelScroller, "scroll"), this._bindingEventService.unbindByEventName(this._focusSink, "keydown"), this._bindingEventService.unbindByEventName(this._focusSink2, "keydown");
			let resizeHandles = this._container.querySelectorAll(".slick-resizable-handle");
			[].forEach.call(resizeHandles, (handle) => {
				this._bindingEventService.unbindByEventName(handle, "dblclick");
			});
			let headerColumns = this._container.querySelectorAll(".slick-header-column");
			[].forEach.call(headerColumns, (column) => {
				this._bindingEventService.unbindByEventName(column, "mouseenter"), this._bindingEventService.unbindByEventName(column, "mouseleave"), this._bindingEventService.unbindByEventName(column, "mouseenter"), this._bindingEventService.unbindByEventName(column, "mouseleave");
			}), Utils.emptyElement(this._container), this._container.classList.remove(this.uid), shouldDestroyAllElements && this.destroyAllElements();
		}
		/**
		 * call destroy method, when exists, on all the instance(s) it found
		 * @params instances - can be a single instance or a an array of instances
		 */
		destroyAllInstances(inputInstances) {
			if (inputInstances) {
				let instances = Array.isArray(inputInstances) ? inputInstances : [inputInstances],
					instance;
				for (; Utils.isDefined(instance = instances.pop());)
					instance && typeof instance.destroy == "function" && instance.destroy();
			}
			return inputInstances = Array.isArray(inputInstances) ? [] : null, inputInstances;
		}
		destroyAllElements() {
			this._activeCanvasNode = null, this._activeViewportNode = null, this._boundAncestors = null, this._canvas = null, this._canvasTopL = null, this._canvasTopR = null, this._canvasBottomL = null, this._canvasBottomR = null, this._container = null, this._focusSink = null, this._focusSink2 = null, this._groupHeaders = null, this._groupHeadersL = null, this._groupHeadersR = null, this._headerL = null, this._headerR = null, this._headers = null, this._headerRows = null, this._headerRowL = null, this._headerRowR = null, this._headerRowSpacerL = null, this._headerRowSpacerR = null, this._headerRowScrollContainer = null, this._headerRowScroller = null, this._headerRowScrollerL = null, this._headerRowScrollerR = null, this._headerScrollContainer = null, this._headerScroller = null, this._headerScrollerL = null, this._headerScrollerR = null, this._hiddenParents = null, this._footerRow = null, this._footerRowL = null, this._footerRowR = null, this._footerRowSpacerL = null, this._footerRowSpacerR = null, this._footerRowScroller = null, this._footerRowScrollerL = null, this._footerRowScrollerR = null, this._footerRowScrollContainer = null, this._preHeaderPanel = null, this._preHeaderPanelR = null, this._preHeaderPanelScroller = null, this._preHeaderPanelScrollerR = null, this._preHeaderPanelSpacer = null, this._preHeaderPanelSpacerR = null, this._topPanels = null, this._topPanelScrollers = null, this._style = null, this._topPanelScrollerL = null, this._topPanelScrollerR = null, this._topPanelL = null, this._topPanelR = null, this._paneHeaderL = null, this._paneHeaderR = null, this._paneTopL = null, this._paneTopR = null, this._paneBottomL = null, this._paneBottomR = null, this._viewport = null, this._viewportTopL = null, this._viewportTopR = null, this._viewportBottomL = null, this._viewportBottomR = null, this._viewportScrollContainerX = null, this._viewportScrollContainerY = null;
		}
		//////////////////////////////////////////////////////////////////////////////////////////////
		// Column Autosizing
		//////////////////////////////////////////////////////////////////////////////////////////////
		/** Proportionally resize a specific column by its name, index or Id */
		autosizeColumn(columnOrIndexOrId, isInit) {
			let colDef = null,
				colIndex = -1;
			if (typeof columnOrIndexOrId == "number")
				colDef = this.columns[columnOrIndexOrId], colIndex = columnOrIndexOrId;
			else if (typeof columnOrIndexOrId == "string")
				for (let i = 0; i < this.columns.length; i++)
					this.columns[i].id === columnOrIndexOrId && (colDef = this.columns[i], colIndex = i);
			if (!colDef)
				return;
			let gridCanvas = this.getCanvasNode(0, 0);
			this.getColAutosizeWidth(colDef, colIndex, gridCanvas, isInit || !1, colIndex);
		}
		treatAsLocked(autoSize = {}) {
			var _a;
			return !autoSize.ignoreHeaderText && !autoSize.sizeToRemaining && autoSize.contentSizePx === autoSize.headerWidthPx && ((_a = autoSize.widthPx) != null ? _a : 0) < 100;
		}
		/** Proportionately resizes all columns to fill available horizontal space. This does not take the cell contents into consideration. */
		autosizeColumns(autosizeMode, isInit) {
			this.cacheCssForHiddenInit(), this.internalAutosizeColumns(autosizeMode, isInit), this.restoreCssFromHiddenInit();
		}
		internalAutosizeColumns(autosizeMode, isInit) {
			var _a, _b, _c, _d, _e, _f, _g, _h, _i, _j, _k, _l, _m, _n, _o, _p, _q, _r, _s, _t, _u, _v;
			if (autosizeMode = autosizeMode || this._options.autosizeColsMode, autosizeMode === GridAutosizeColsMode.LegacyForceFit || autosizeMode === GridAutosizeColsMode.LegacyOff) {
				this.legacyAutosizeColumns();
				return;
			}
			if (autosizeMode === GridAutosizeColsMode.None)
				return;
			this.canvas = document.createElement("canvas"), (_a = this.canvas) != null && _a.getContext && (this.canvas_context = this.canvas.getContext("2d"));
			let gridCanvas = this.getCanvasNode(0, 0),
				viewportWidth = this.viewportHasVScroll ? this.viewportW - ((_c = (_b = this.scrollbarDimensions) == null ? void 0 : _b.width) != null ? _c : 0) : this.viewportW,
				i, c, colWidth, reRender = !1,
				totalWidth = 0,
				totalWidthLessSTR = 0,
				strColsMinWidth = 0,
				totalMinWidth = 0,
				totalLockedColWidth = 0;
			for (i = 0; i < this.columns.length; i++)
				c = this.columns[i], this.getColAutosizeWidth(c, i, gridCanvas, isInit || !1, i), totalLockedColWidth += ((_d = c.autoSize) == null ? void 0 : _d.autosizeMode) === ColAutosizeMode.Locked ? c.width || 0 : this.treatAsLocked(c.autoSize) && ((_e = c.autoSize) == null ? void 0 : _e.widthPx) || 0, totalMinWidth += ((_f = c.autoSize) == null ? void 0 : _f.autosizeMode) === ColAutosizeMode.Locked ? c.width || 0 : this.treatAsLocked(c.autoSize) ? ((_g = c.autoSize) == null ? void 0 : _g.widthPx) || 0 : c.minWidth || 0, totalWidth += ((_h = c.autoSize) == null ? void 0 : _h.widthPx) || 0, totalWidthLessSTR += (_i = c.autoSize) != null && _i.sizeToRemaining ? 0 : ((_j = c.autoSize) == null ? void 0 : _j.widthPx) || 0, strColsMinWidth += (_k = c.autoSize) != null && _k.sizeToRemaining && c.minWidth || 0;
			let strColTotalGuideWidth = totalWidth - totalWidthLessSTR;
			if (autosizeMode === GridAutosizeColsMode.FitViewportToCols) {
				let setWidth = totalWidth + ((_m = (_l = this.scrollbarDimensions) == null ? void 0 : _l.width) != null ? _m : 0);
				autosizeMode = GridAutosizeColsMode.IgnoreViewport, this._options.viewportMaxWidthPx && setWidth > this._options.viewportMaxWidthPx ? (setWidth = this._options.viewportMaxWidthPx, autosizeMode = GridAutosizeColsMode.FitColsToViewport) : this._options.viewportMinWidthPx && setWidth < this._options.viewportMinWidthPx && (setWidth = this._options.viewportMinWidthPx, autosizeMode = GridAutosizeColsMode.FitColsToViewport), Utils.width(this._container, setWidth);
			}
			if (autosizeMode === GridAutosizeColsMode.FitColsToViewport)
				if (strColTotalGuideWidth > 0 && totalWidthLessSTR < viewportWidth - strColsMinWidth)
					for (i = 0; i < this.columns.length; i++) {
						if (c = this.columns[i], !c || c.hidden)
							continue;
						let totalSTRViewportWidth = viewportWidth - totalWidthLessSTR;
						(_n = c.autoSize) != null && _n.sizeToRemaining ? colWidth = totalSTRViewportWidth * (((_o = c.autoSize) == null ? void 0 : _o.widthPx) || 0) / strColTotalGuideWidth : colWidth = ((_p = c.autoSize) == null ? void 0 : _p.widthPx) || 0, c.rerenderOnResize && (c.width || 0) !== colWidth && (reRender = !0), c.width = colWidth;
					}
			else if (this._options.viewportSwitchToScrollModeWidthPercent && totalWidthLessSTR + strColsMinWidth > viewportWidth * this._options.viewportSwitchToScrollModeWidthPercent / 100 || totalMinWidth > viewportWidth)
				autosizeMode = GridAutosizeColsMode.IgnoreViewport;
			else {
				let unallocatedColWidth = totalWidthLessSTR - totalLockedColWidth,
					unallocatedViewportWidth = viewportWidth - totalLockedColWidth - strColsMinWidth;
				for (i = 0; i < this.columns.length; i++)
					c = this.columns[i], !(!c || c.hidden) && (colWidth = c.width || 0, ((_q = c.autoSize) == null ? void 0 : _q.autosizeMode) !== ColAutosizeMode.Locked && !this.treatAsLocked(c.autoSize) && ((_r = c.autoSize) != null && _r.sizeToRemaining ? colWidth = c.minWidth || 0 : (colWidth = unallocatedViewportWidth / unallocatedColWidth * (((_s = c.autoSize) == null ? void 0 : _s.widthPx) || 0) - 1, colWidth < (c.minWidth || 0) && (colWidth = c.minWidth || 0), unallocatedColWidth -= ((_t = c.autoSize) == null ? void 0 : _t.widthPx) || 0, unallocatedViewportWidth -= colWidth)), this.treatAsLocked(c.autoSize) && (colWidth = ((_u = c.autoSize) == null ? void 0 : _u.widthPx) || 0, colWidth < (c.minWidth || 0) && (colWidth = c.minWidth || 0)), c.rerenderOnResize && c.width !== colWidth && (reRender = !0), c.width = colWidth);
			}
			if (autosizeMode === GridAutosizeColsMode.IgnoreViewport)
				for (i = 0; i < this.columns.length; i++)
					!this.columns[i] || this.columns[i].hidden || (colWidth = ((_v = this.columns[i].autoSize) == null ? void 0 : _v.widthPx) || 0, this.columns[i].rerenderOnResize && this.columns[i].width !== colWidth && (reRender = !0), this.columns[i].width = colWidth);
			this.reRenderColumns(reRender);
		}
		LogColWidths() {
			let s = "Col Widths:";
			for (let i = 0; i < this.columns.length; i++)
				s += " " + (this.columns[i].hidden ? "H" : this.columns[i].width);
			console.log(s);
		}
		getColAutosizeWidth(columnDef, colIndex, gridCanvas, isInit, colArrayIndex) {
			var _a;
			let autoSize = columnDef.autoSize;
			if (autoSize.widthPx = columnDef.width, autoSize.autosizeMode === ColAutosizeMode.Locked || autoSize.autosizeMode === ColAutosizeMode.Guide)
				return;
			let dl = this.getDataLength(),
				isoDateRegExp = new RegExp(/\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}(\.\d{3})?Z/);
			if (autoSize.autosizeMode === ColAutosizeMode.ContentIntelligent) {
				let colDataTypeOf = autoSize.colDataTypeOf,
					colDataItem;
				if (dl > 0) {
					let tempRow = this.getDataItem(0);
					tempRow && (colDataItem = tempRow[columnDef.field], isoDateRegExp.test(colDataItem) && (colDataItem = Date.parse(colDataItem)), colDataTypeOf = typeof colDataItem, colDataTypeOf === "object" && (colDataItem instanceof Date && (colDataTypeOf = "date"), typeof moment != "undefined" && colDataItem instanceof moment && (colDataTypeOf = "moment")));
				}
				colDataTypeOf === "boolean" && (autoSize.colValueArray = [!0, !1]), colDataTypeOf === "number" && (autoSize.valueFilterMode = ValueFilterMode.GetGreatestAndSub, autoSize.rowSelectionMode = RowSelectionMode.AllRows), colDataTypeOf === "string" && (autoSize.valueFilterMode = ValueFilterMode.GetLongestText, autoSize.rowSelectionMode = RowSelectionMode.AllRows, autoSize.allowAddlPercent = 5), colDataTypeOf === "date" && (autoSize.colValueArray = [new Date(2009, 8, 30, 12, 20, 20)]), colDataTypeOf === "moment" && typeof moment != "undefined" && (autoSize.colValueArray = [moment([2009, 8, 30, 12, 20, 20])]);
			}
			let colWidth = autoSize.contentSizePx = this.getColContentSize(columnDef, colIndex, gridCanvas, isInit, colArrayIndex);
			colWidth === 0 && (colWidth = autoSize.widthPx || 0);
			let addlPercentMultiplier = autoSize.allowAddlPercent ? 1 + autoSize.allowAddlPercent / 100 : 1;
			colWidth = colWidth * addlPercentMultiplier + (this._options.autosizeColPaddingPx || 0), columnDef.minWidth && colWidth < columnDef.minWidth && (colWidth = columnDef.minWidth), columnDef.maxWidth && colWidth > columnDef.maxWidth && (colWidth = columnDef.maxWidth), (autoSize.autosizeMode === ColAutosizeMode.ContentExpandOnly || (_a = columnDef == null ? void 0 : columnDef.editor) != null && _a.ControlFillsColumn) && colWidth < (columnDef.width || 0) && (colWidth = columnDef.width || 0), autoSize.widthPx = colWidth;
		}
		getColContentSize(columnDef, colIndex, gridCanvas, isInit, colArrayIndex) {
			let autoSize = columnDef.autoSize,
				widthAdjustRatio = 1,
				i, tempVal, maxLen = 0,
				maxColWidth = 0;
			if (autoSize.headerWidthPx = 0, autoSize.ignoreHeaderText || (autoSize.headerWidthPx = this.getColHeaderWidth(columnDef)), autoSize.headerWidthPx === 0 && (autoSize.headerWidthPx = columnDef.width ? columnDef.width : columnDef.maxWidth ? columnDef.maxWidth : columnDef.minWidth ? columnDef.minWidth : 20), autoSize.colValueArray)
				return maxColWidth = this.getColWidth(columnDef, gridCanvas, autoSize.colValueArray), Math.max(autoSize.headerWidthPx, maxColWidth);
			let rowInfo = {};
			rowInfo.colIndex = colIndex, rowInfo.rowCount = this.getDataLength(), rowInfo.startIndex = 0, rowInfo.endIndex = rowInfo.rowCount - 1, rowInfo.valueArr = null, rowInfo.getRowVal = (j) => this.getDataItem(j)[columnDef.field];
			let rowSelectionMode = (isInit ? autoSize.rowSelectionModeOnInit : void 0) || autoSize.rowSelectionMode;
			if (rowSelectionMode === RowSelectionMode.FirstRow && (rowInfo.endIndex = 0), rowSelectionMode === RowSelectionMode.LastRow && (rowInfo.endIndex = rowInfo.startIndex = rowInfo.rowCount - 1), rowSelectionMode === RowSelectionMode.FirstNRows && (rowInfo.endIndex = Math.min(autoSize.rowSelectionCount || 0, rowInfo.rowCount) - 1), autoSize.valueFilterMode === ValueFilterMode.DeDuplicate) {
				let rowsDict = {};
				for (i = rowInfo.startIndex; i <= rowInfo.endIndex; i++)
					rowsDict[rowInfo.getRowVal(i)] = !0;
				if (Object.keys)
					rowInfo.valueArr = Object.keys(rowsDict);
				else {
					rowInfo.valueArr = [];
					for (let v in rowsDict)
						rowsDict && rowInfo.valueArr.push(v);
				}
				rowInfo.startIndex = 0, rowInfo.endIndex = rowInfo.length - 1;
			}
			if (autoSize.valueFilterMode === ValueFilterMode.GetGreatestAndSub) {
				let maxVal, maxAbsVal = 0;
				for (i = rowInfo.startIndex; i <= rowInfo.endIndex; i++)
					tempVal = rowInfo.getRowVal(i), Math.abs(tempVal) > maxAbsVal && (maxVal = tempVal, maxAbsVal = Math.abs(tempVal));
				maxVal = "" + maxVal, maxVal = Array(maxVal.length + 1).join("9"), maxVal = +maxVal, rowInfo.valueArr = [maxVal], rowInfo.startIndex = rowInfo.endIndex = 0;
			}
			if (autoSize.valueFilterMode === ValueFilterMode.GetLongestTextAndSub) {
				for (i = rowInfo.startIndex; i <= rowInfo.endIndex; i++)
					tempVal = rowInfo.getRowVal(i), (tempVal || "").length > maxLen && (maxLen = tempVal.length);
				tempVal = Array(maxLen + 1).join("m"), widthAdjustRatio = this._options.autosizeTextAvgToMWidthRatio || 0, rowInfo.maxLen = maxLen, rowInfo.valueArr = [tempVal], rowInfo.startIndex = rowInfo.endIndex = 0;
			}
			if (autoSize.valueFilterMode === ValueFilterMode.GetLongestText) {
				maxLen = 0;
				let maxIndex = 0;
				for (i = rowInfo.startIndex; i <= rowInfo.endIndex; i++)
					tempVal = rowInfo.getRowVal(i), (tempVal || "").length > maxLen && (maxLen = tempVal.length, maxIndex = i);
				tempVal = rowInfo.getRowVal(maxIndex), rowInfo.maxLen = maxLen, rowInfo.valueArr = [tempVal], rowInfo.startIndex = rowInfo.endIndex = 0;
			}
			return rowInfo.maxLen && rowInfo.maxLen > 30 && colArrayIndex > 1 && (autoSize.sizeToRemaining = !0), maxColWidth = this.getColWidth(columnDef, gridCanvas, rowInfo) * widthAdjustRatio, Math.max(autoSize.headerWidthPx, maxColWidth);
		}
		getColWidth(columnDef, gridCanvas, rowInfo) {
			var _a, _b, _c;
			let rowEl = Utils.createDomElement("div", {
					className: "slick-row ui-widget-content"
				}, gridCanvas),
				cellEl = Utils.createDomElement("div", {
					className: "slick-cell"
				}, rowEl);
			cellEl.style.position = "absolute", cellEl.style.visibility = "hidden", cellEl.style.textOverflow = "initial", cellEl.style.whiteSpace = "nowrap";
			let i, len, max = 0,
				maxText = "",
				formatterResult, val, useCanvas = columnDef.autoSize.widthEvalMode === WidthEvalMode.TextOnly;
			if (((_a = columnDef.autoSize) == null ? void 0 : _a.widthEvalMode) === WidthEvalMode.Auto) {
				let noFormatter = !columnDef.formatterOverride && !columnDef.formatter,
					formatterIsText = ((_b = columnDef == null ? void 0 : columnDef.formatterOverride) == null ? void 0 : _b.ReturnsTextOnly) || !columnDef.formatterOverride && ((_c = columnDef.formatter) == null ? void 0 : _c.ReturnsTextOnly);
				useCanvas = noFormatter || formatterIsText;
			}
			if (this.canvas_context && useCanvas) {
				let style = getComputedStyle(cellEl);
				for (this.canvas_context.font = style.fontSize + " " + style.fontFamily, i = rowInfo.startIndex; i <= rowInfo.endIndex; i++)
					val = rowInfo.valueArr ? rowInfo.valueArr[i] : rowInfo.getRowVal(i), columnDef.formatterOverride ? formatterResult = columnDef.formatterOverride(i, rowInfo.colIndex, val, columnDef, this.getDataItem(i), this) : columnDef.formatter ? formatterResult = columnDef.formatter(i, rowInfo.colIndex, val, columnDef, this.getDataItem(i), this) : formatterResult = "" + val, len = formatterResult ? this.canvas_context.measureText(formatterResult).width : 0, len > max && (max = len, maxText = formatterResult);
				return cellEl.textContent = maxText, len = cellEl.offsetWidth, rowEl.remove(), len;
			}
			for (i = rowInfo.startIndex; i <= rowInfo.endIndex; i++)
				val = rowInfo.valueArr ? rowInfo.valueArr[i] : rowInfo.getRowVal(i), columnDef.formatterOverride ? formatterResult = columnDef.formatterOverride(i, rowInfo.colIndex, val, columnDef, this.getDataItem(i), this) : columnDef.formatter ? formatterResult = columnDef.formatter(i, rowInfo.colIndex, val, columnDef, this.getDataItem(i), this) : formatterResult = "" + val, this.applyFormatResultToCellNode(formatterResult, cellEl), len = cellEl.offsetWidth, len > max && (max = len);
			return rowEl.remove(), max;
		}
		getColHeaderWidth(columnDef) {
			let width = 0,
				headerColElId = this.getUID() + columnDef.id,
				headerColEl = document.getElementById(headerColElId),
				dummyHeaderColElId = `${headerColElId}_`,
				clone = headerColEl.cloneNode(!0);
			if (headerColEl)
				clone.id = dummyHeaderColElId, clone.style.cssText = "position: absolute; visibility: hidden;right: auto;text-overflow: initial;white-space: nowrap;", headerColEl.parentNode.insertBefore(clone, headerColEl), width = clone.offsetWidth, clone.parentNode.removeChild(clone);
			else {
				let header = this.getHeader(columnDef);
				headerColEl = Utils.createDomElement("div", {
					id: dummyHeaderColElId,
					className: "ui-state-default slick-state-default slick-header-column"
				}, header);
				let colNameElm = Utils.createDomElement("span", {
					className: "slick-column-name"
				}, headerColEl);
				this.applyHtmlCode(colNameElm, columnDef.name), clone.style.cssText = "position: absolute; visibility: hidden;right: auto;text-overflow: initial;white-space: nowrap;", columnDef.headerCssClass && headerColEl.classList.add(...(columnDef.headerCssClass || "").split(" ")), width = headerColEl.offsetWidth, header.removeChild(headerColEl);
			}
			return width;
		}
		legacyAutosizeColumns() {
			var _a, _b;
			let i, c, shrinkLeeway = 0,
				total = 0,
				prevTotal = 0,
				widths = [],
				availWidth = this.viewportHasVScroll ? this.viewportW - ((_b = (_a = this.scrollbarDimensions) == null ? void 0 : _a.width) != null ? _b : 0) : this.viewportW;
			for (i = 0; i < this.columns.length; i++)
				c = this.columns[i], !(!c || c.hidden) && (widths.push(c.width || 0), total += c.width || 0, c.resizable && (shrinkLeeway += (c.width || 0) - Math.max(c.minWidth || 0, this.absoluteColumnMinWidth)));
			for (prevTotal = total; total > availWidth && shrinkLeeway;) {
				let shrinkProportion = (total - availWidth) / shrinkLeeway;
				for (i = 0; i < this.columns.length && total > availWidth; i++) {
					if (c = this.columns[i], !c || c.hidden)
						continue;
					let width = widths[i];
					if (!c.resizable || width <= c.minWidth || width <= this.absoluteColumnMinWidth)
						continue;
					let absMinWidth = Math.max(c.minWidth, this.absoluteColumnMinWidth),
						shrinkSize = Math.floor(shrinkProportion * (width - absMinWidth)) || 1;
					shrinkSize = Math.min(shrinkSize, width - absMinWidth), total -= shrinkSize, shrinkLeeway -= shrinkSize, widths[i] -= shrinkSize;
				}
				if (prevTotal <= total)
					break;
				prevTotal = total;
			}
			for (prevTotal = total; total < availWidth;) {
				let growProportion = availWidth / total;
				for (i = 0; i < this.columns.length && total < availWidth; i++) {
					if (c = this.columns[i], !c || c.hidden)
						continue;
					let currentWidth = widths[i],
						growSize;
					!c.resizable || c.maxWidth <= currentWidth ? growSize = 0 : growSize = Math.min(Math.floor(growProportion * currentWidth) - currentWidth, c.maxWidth - currentWidth || 1e6) || 1, total += growSize, widths[i] += total <= availWidth ? growSize : 0;
				}
				if (prevTotal >= total)
					break;
				prevTotal = total;
			}
			let reRender = !1;
			for (i = 0; i < this.columns.length; i++)
				!c || c.hidden || (this.columns[i].rerenderOnResize && this.columns[i].width !== widths[i] && (reRender = !0), this.columns[i].width = widths[i]);
			this.reRenderColumns(reRender);
		}
		/**
		 * Apply Columns Widths in the UI and optionally invalidate & re-render the columns when specified
		 * @param {Boolean} shouldReRender - should we invalidate and re-render the grid?
		 */
		reRenderColumns(reRender) {
			this.applyColumnHeaderWidths(), this.updateCanvasWidth(!0), this.trigger(this.onAutosizeColumns, {
				columns: this.columns
			}), reRender && (this.invalidateAllRows(), this.render());
		}
		getVisibleColumns() {
			return this.columns.filter((c) => !c.hidden);
		}
		//////////////////////////////////////////////////////////////////////////////////////////////
		// General
		//////////////////////////////////////////////////////////////////////////////////////////////
		trigger(evt, args, e) {
			let event = e || new SlickEventData(e, args),
				eventArgs = args || {};
			return eventArgs.grid = this, evt.notify(eventArgs, event, this);
		}
		/** Get Editor lock */
		getEditorLock() {
			return this._options.editorLock;
		}
		/** Get Editor Controller */
		getEditController() {
			return this.editController;
		}
		/**
		 * Returns the index of a column with a given id. Since columns can be reordered by the user, this can be used to get the column definition independent of the order:
		 * @param {String | Number} id A column id.
		 */
		getColumnIndex(id) {
			return this.columnsById[id];
		}
		applyColumnHeaderWidths() {
			if (!this.initialized)
				return;
			let columnIndex = 0,
				vc = this.getVisibleColumns();
			this._headers.forEach((header) => {
				for (let i = 0; i < header.children.length; i++, columnIndex++) {
					let h = header.children[i],
						width = ((vc[columnIndex] || {}).width || 0) - this.headerColumnWidthDiff;
					Utils.width(h) !== width && Utils.width(h, width);
				}
			}), this.updateColumnCaches();
		}
		applyColumnWidths() {
			var _a;
			let x = 0,
				w = 0,
				rule;
			for (let i = 0; i < this.columns.length; i++)
				(_a = this.columns[i]) != null && _a.hidden || (w = this.columns[i].width || 0, rule = this.getColumnCssRules(i), rule.left.style.left = `${x}px`, rule.right.style.right = (this._options.frozenColumn !== -1 && i > this._options.frozenColumn ? this.canvasWidthR : this.canvasWidthL) - x - w + "px", this._options.frozenColumn !== i && (x += this.columns[i].width)), this._options.frozenColumn === i && (x = 0);
		}
		/**
		 * Accepts a columnId string and an ascending boolean. Applies a sort glyph in either ascending or descending form to the header of the column. Note that this does not actually sort the column. It only adds the sort glyph to the header.
		 * @param {String | Number} columnId
		 * @param {Boolean} ascending
		 */
		setSortColumn(columnId, ascending) {
			this.setSortColumns([{
				columnId,
				sortAsc: ascending
			}]);
		}
		/**
		 * Get column by index
		 * @param {Number} id - column index
		 * @returns
		 */
		getColumnByIndex(id) {
			let result;
			return this._headers.every((header) => {
				let length = header.children.length;
				return id < length ? (result = header.children[id], !1) : (id -= length, !0);
			}), result;
		}
		/**
		 * Accepts an array of objects in the form [ { columnId: [string], sortAsc: [boolean] }, ... ]. When called, this will apply a sort glyph in either ascending or descending form to the header of each column specified in the array. Note that this does not actually sort the column. It only adds the sort glyph to the header
		 * @param {ColumnSort[]} cols - column sort
		 */
		setSortColumns(cols) {
			this.sortColumns = cols;
			let numberCols = this._options.numberedMultiColumnSort && this.sortColumns.length > 1;
			this._headers.forEach((header) => {
				let indicators = header.querySelectorAll(".slick-header-column-sorted");
				indicators.forEach((indicator) => {
					indicator.classList.remove("slick-header-column-sorted");
				}), indicators = header.querySelectorAll(".slick-sort-indicator"), indicators.forEach((indicator) => {
					indicator.classList.remove("slick-sort-indicator-asc"), indicator.classList.remove("slick-sort-indicator-desc");
				}), indicators = header.querySelectorAll(".slick-sort-indicator-numbered"), indicators.forEach((el) => {
					el.textContent = "";
				});
			});
			let i = 1;
			this.sortColumns.forEach((col) => {
				Utils.isDefined(col.sortAsc) || (col.sortAsc = !0);
				let columnIndex = this.getColumnIndex(col.columnId);
				if (Utils.isDefined(columnIndex)) {
					let column = this.getColumnByIndex(columnIndex);
					if (column) {
						column.classList.add("slick-header-column-sorted");
						let indicator = column.querySelector(".slick-sort-indicator");
						indicator == null || indicator.classList.add(col.sortAsc ? "slick-sort-indicator-asc" : "slick-sort-indicator-desc"), numberCols && (indicator = column.querySelector(".slick-sort-indicator-numbered"), indicator && (indicator.textContent = String(i)));
					}
				}
				i++;
			});
		}
		/** Get sorted columns **/
		getSortColumns() {
			return this.sortColumns;
		}
		handleSelectedRangesChanged(e, ranges) {
			var _a, _b;
			let ne = e.getNativeEvent(),
				previousSelectedRows = this.selectedRows.slice(0);
			this.selectedRows = [];
			let hash = {};
			for (let i = 0; i < ranges.length; i++)
				for (let j = ranges[i].fromRow; j <= ranges[i].toRow; j++) {
					hash[j] || (this.selectedRows.push(j), hash[j] = {});
					for (let k = ranges[i].fromCell; k <= ranges[i].toCell; k++)
						this.canCellBeSelected(j, k) && (hash[j][this.columns[k].id] = this._options.selectedCellCssClass);
				}
			if (this.setCellCssStyles(this._options.selectedCellCssClass || "", hash), this.simpleArrayEquals(previousSelectedRows, this.selectedRows)) {
				let caller = (_b = (_a = ne == null ? void 0 : ne.detail) == null ? void 0 : _a.caller) != null ? _b : "click",
					newSelectedAdditions = this.getSelectedRows().filter((i) => previousSelectedRows.indexOf(i) < 0),
					newSelectedDeletions = previousSelectedRows.filter((i) => this.getSelectedRows().indexOf(i) < 0);
				this.trigger(this.onSelectedRowsChanged, {
					rows: this.getSelectedRows(),
					previousSelectedRows,
					caller,
					changedSelectedRows: newSelectedAdditions,
					changedUnselectedRows: newSelectedDeletions
				}, e);
			}
		}
		// compare 2 simple arrays (integers or strings only, do not use to compare object arrays)
		simpleArrayEquals(arr1, arr2) {
			return Array.isArray(arr1) && Array.isArray(arr2) && arr2.sort().toString() !== arr1.sort().toString();
		}
		/** Returns an array of column definitions. */
		getColumns() {
			return this.columns;
		}
		updateColumnCaches() {
			this.columnPosLeft = [], this.columnPosRight = [];
			let x = 0;
			for (let i = 0, ii = this.columns.length; i < ii; i++)
				!this.columns[i] || this.columns[i].hidden || (this.columnPosLeft[i] = x, this.columnPosRight[i] = x + (this.columns[i].width || 0), this._options.frozenColumn === i ? x = 0 : x += this.columns[i].width || 0);
		}
		updateColumnProps() {
			this.columnsById = {};
			for (let i = 0; i < this.columns.length; i++) {
				let m = this.columns[i];
				m.width && (m.widthRequest = m.width), this.options.mixinDefaults ? (Utils.applyDefaults(m, this._columnDefaults), m.autoSize || (m.autoSize = {}), Utils.applyDefaults(m.autoSize, this._columnAutosizeDefaults)) : (m = this.columns[i] = Utils.extend({}, this._columnDefaults, m), m.autoSize = Utils.extend({}, this._columnAutosizeDefaults, m.autoSize)), this.columnsById[m.id] = i, m.minWidth && (m.width || 0) < m.minWidth && (m.width = m.minWidth), m.maxWidth && (m.width || 0) > m.maxWidth && (m.width = m.maxWidth);
			}
		}
		/**
		 * Sets grid columns. Column headers will be recreated and all rendered rows will be removed. To rerender the grid (if necessary), call render().
		 * @param {Column[]} columnDefinitions An array of column definitions.
		 */
		setColumns(columnDefinitions) {
			this.trigger(this.onBeforeSetColumns, {
				previousColumns: this.columns,
				newColumns: columnDefinitions,
				grid: this
			}), this.columns = columnDefinitions, this.updateColumnsInternal();
		}
		updateColumns() {
			this.trigger(this.onBeforeUpdateColumns, {
				columns: this.columns,
				grid: this
			}), this.updateColumnsInternal();
		}
		updateColumnsInternal() {
			var _a;
			this.updateColumnProps(), this.updateColumnCaches(), this.initialized && (this.setPaneVisibility(), this.setOverflow(), this.invalidateAllRows(), this.createColumnHeaders(), this.createColumnFooter(), this.removeCssRules(), this.createCssRules(), this.resizeCanvas(), this.updateCanvasWidth(), this.applyColumnHeaderWidths(), this.applyColumnWidths(), this.handleScroll(), (_a = this.getSelectionModel()) == null || _a.refreshSelections());
		}
		/** Returns an object containing all of the Grid options set on the grid. See a list of Grid Options here.  */
		getOptions() {
			return this._options;
		}
		/**
		 * Extends grid options with a given hash. If an there is an active edit, the grid will attempt to commit the changes and only continue if the attempt succeeds.
		 * @param {Object} options - an object with configuration options.
		 * @param {Boolean} [suppressRender] - do we want to supress the grid re-rendering? (defaults to false)
		 * @param {Boolean} [suppressColumnSet] - do we want to supress the columns set, via "setColumns()" method? (defaults to false)
		 * @param {Boolean} [suppressSetOverflow] - do we want to suppress the call to `setOverflow`
		 */
		setOptions(args, suppressRender, suppressColumnSet, suppressSetOverflow) {
			this.prepareForOptionsChange(), this._options.enableAddRow !== args.enableAddRow && this.invalidateRow(this.getDataLength()), args.frozenColumn && (this.getViewports().forEach((vp) => vp.scrollLeft = 0), this.handleScroll());
			let originalOptions = Utils.extend(!0, {}, this._options);
			this._options = Utils.extend(this._options, args), this.trigger(this.onSetOptions, {
				optionsBefore: originalOptions,
				optionsAfter: this._options
			}), this.internal_setOptions(suppressRender, suppressColumnSet, suppressSetOverflow);
		}
		/**
		 * If option.mixinDefaults is true then external code maintains a reference to the options object. In this case there is no need
		 * to call setOptions() - changes can be made directly to the object. However setOptions() also performs some recalibration of the
		 * grid in reaction to changed options. activateChangedOptions call the same recalibration routines as setOptions() would have.
		 * @param {Boolean} [suppressRender] - do we want to supress the grid re-rendering? (defaults to false)
		 * @param {Boolean} [suppressColumnSet] - do we want to supress the columns set, via "setColumns()" method? (defaults to false)
		 * @param {Boolean} [suppressSetOverflow] - do we want to suppress the call to `setOverflow`
		 */
		activateChangedOptions(suppressRender, suppressColumnSet, suppressSetOverflow) {
			this.prepareForOptionsChange(), this.invalidateRow(this.getDataLength()), this.trigger(this.onActivateChangedOptions, {
				options: this._options
			}), this.internal_setOptions(suppressRender, suppressColumnSet, suppressSetOverflow);
		}
		prepareForOptionsChange() {
			this.getEditorLock().commitCurrentEdit() && this.makeActiveCellNormal();
		}
		internal_setOptions(suppressRender, suppressColumnSet, suppressSetOverflow) {
			this._options.showColumnHeader !== void 0 && this.setColumnHeaderVisibility(this._options.showColumnHeader), this.validateAndEnforceOptions(), this.setFrozenOptions(), this._options.frozenBottom !== void 0 && (this.enforceFrozenRowHeightRecalc = !0), this._viewport.forEach((view) => {
				view.style.overflowY = this._options.autoHeight ? "hidden" : "auto";
			}), suppressRender || this.render(), this.setScroller(), suppressSetOverflow || this.setOverflow(), suppressColumnSet || this.setColumns(this.columns), this._options.enableMouseWheelScrollHandler && this._viewport && (!this.slickMouseWheelInstances || this.slickMouseWheelInstances.length === 0) ? this._viewport.forEach((view) => {
				this.slickMouseWheelInstances.push(MouseWheel({
					element: view,
					onMouseWheel: this.handleMouseWheel.bind(this)
				}));
			}) : this._options.enableMouseWheelScrollHandler === !1 && this.destroyAllInstances(this.slickMouseWheelInstances);
		}
		validateAndEnforceOptions() {
			this._options.autoHeight && (this._options.leaveSpaceForNewRows = !1), this._options.forceFitColumns && (this._options.autosizeColsMode = GridAutosizeColsMode.LegacyForceFit, console.log("forceFitColumns option is deprecated - use autosizeColsMode"));
		}
		/**
		 * Sets a new source for databinding and removes all rendered rows. Note that this doesn't render the new rows - you can follow it with a call to render() to do that.
		 * @param {CustomDataView|Array<*>} newData New databinding source using a regular JavaScript array.. or a custom object exposing getItem(index) and getLength() functions.
		 * @param {Number} [scrollToTop] If true, the grid will reset the vertical scroll position to the top of the grid.
		 */
		setData(newData, scrollToTop) {
			this.data = newData, this.invalidateAllRows(), this.updateRowCount(), scrollToTop && this.scrollTo(0);
		}
		/** Returns an array of every data object, unless you're using DataView in which case it returns a DataView object. */
		getData() {
			return this.data;
		}
		/** Returns the size of the databinding source. */
		getDataLength() {
			var _a, _b;
			return this.data.getLength ? this.data.getLength() : (_b = (_a = this.data) == null ? void 0 : _a.length) != null ? _b : 0;
		}
		getDataLengthIncludingAddNew() {
			return this.getDataLength() + (this._options.enableAddRow && (!this.pagingActive || this.pagingIsLastPage) ? 1 : 0);
		}
		/**
		 * Returns the databinding item at a given position.
		 * @param {Number} index Item row index.
		 */
		getDataItem(i) {
			return this.data.getItem ? this.data.getItem(i) : this.data[i];
		}
		/** Get Top Panel DOM element */
		getTopPanel() {
			return this._topPanels[0];
		}
		/** Get Top Panels (left/right) DOM element */
		getTopPanels() {
			return this._topPanels;
		}
		/** Are we using a DataView? */
		hasDataView() {
			return !Array.isArray(this.data);
		}
		togglePanelVisibility(option, container, visible, animate) {
			let animated = animate !== !1;
			if (this._options[option] !== visible)
				if (this._options[option] = visible, visible) {
					if (animated) {
						Utils.slideDown(container, this.resizeCanvas.bind(this));
						return;
					}
					Utils.show(container), this.resizeCanvas();
				} else {
					if (animated) {
						Utils.slideUp(container, this.resizeCanvas.bind(this));
						return;
					}
					Utils.hide(container), this.resizeCanvas();
				}
		}
		/**
		 * Set the Top Panel Visibility and optionally enable/disable animation (enabled by default)
		 * @param {Boolean} [visible] - optionally set if top panel is visible or not
		 * @param {Boolean} [animate] - optionally enable an animation while toggling the panel
		 */
		setTopPanelVisibility(visible, animate) {
			this.togglePanelVisibility("showTopPanel", this._topPanelScrollers, visible, animate);
		}
		/**
		 * Set the Header Row Visibility and optionally enable/disable animation (enabled by default)
		 * @param {Boolean} [visible] - optionally set if header row panel is visible or not
		 * @param {Boolean} [animate] - optionally enable an animation while toggling the panel
		 */
		setHeaderRowVisibility(visible, animate) {
			this.togglePanelVisibility("showHeaderRow", this._headerRowScroller, visible, animate);
		}
		/**
		 * Set the Column Header Visibility and optionally enable/disable animation (enabled by default)
		 * @param {Boolean} [visible] - optionally set if column header is visible or not
		 * @param {Boolean} [animate] - optionally enable an animation while toggling the panel
		 */
		setColumnHeaderVisibility(visible, animate) {
			this.togglePanelVisibility("showColumnHeader", this._headerScroller, visible, animate);
		}
		/**
		 * Set the Footer Visibility and optionally enable/disable animation (enabled by default)
		 * @param {Boolean} [visible] - optionally set if footer row panel is visible or not
		 * @param {Boolean} [animate] - optionally enable an animation while toggling the panel
		 */
		setFooterRowVisibility(visible, animate) {
			this.togglePanelVisibility("showFooterRow", this._footerRowScroller, visible, animate);
		}
		/**
		 * Set the Pre-Header Visibility and optionally enable/disable animation (enabled by default)
		 * @param {Boolean} [visible] - optionally set if pre-header panel is visible or not
		 * @param {Boolean} [animate] - optionally enable an animation while toggling the panel
		 */
		setPreHeaderPanelVisibility(visible, animate) {
			this.togglePanelVisibility("showPreHeaderPanel", [this._preHeaderPanelScroller, this._preHeaderPanelScrollerR], visible, animate);
		}
		/** Get Grid Canvas Node DOM Element */
		getContainerNode() {
			return this._container;
		}
		//////////////////////////////////////////////////////////////////////////////////////////////
		// Rendering / Scrolling
		getRowTop(row) {
			return this._options.rowHeight * row - this.offset;
		}
		getRowFromPosition(y) {
			return Math.floor((y + this.offset) / this._options.rowHeight);
		}
		/**
		 * Scroll to an Y position in the grid
		 * @param {Number} y
		 */
		scrollTo(y) {
			var _a, _b;
			y = Math.max(y, 0), y = Math.min(y, (this.th || 0) - Utils.height(this._viewportScrollContainerY) + ((this.viewportHasHScroll || this.hasFrozenColumns()) && (_b = (_a = this.scrollbarDimensions) == null ? void 0 : _a.height) != null ? _b : 0));
			let oldOffset = this.offset;
			this.offset = Math.round(this.page * (this.cj || 0)), this.page = Math.min((this.n || 0) - 1, Math.floor(y / (this.ph || 0)));
			let newScrollTop = y - this.offset;
			if (this.offset !== oldOffset) {
				let range = this.getVisibleRange(newScrollTop);
				this.cleanupRows(range), this.updateRowPositions();
			}
			this.prevScrollTop !== newScrollTop && (this.vScrollDir = this.prevScrollTop + oldOffset < newScrollTop + this.offset ? 1 : -1, this.lastRenderedScrollTop = this.scrollTop = this.prevScrollTop = newScrollTop, this.hasFrozenColumns() && (this._viewportTopL.scrollTop = newScrollTop), this.hasFrozenRows && (this._viewportBottomL.scrollTop = this._viewportBottomR.scrollTop = newScrollTop), this._viewportScrollContainerY && (this._viewportScrollContainerY.scrollTop = newScrollTop), this.trigger(this.onViewportChanged, {}));
		}
		defaultFormatter(_row, _cell, value) {
			return Utils.isDefined(value) ? (value + "").replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;") : "";
		}
		getFormatter(row, column) {
			var _a, _b, _c;
			let rowMetadata = (_b = (_a = this.data) == null ? void 0 : _a.getItemMetadata) == null ? void 0 : _b.call(_a, row),
				columnOverrides = (rowMetadata == null ? void 0 : rowMetadata.columns) && (rowMetadata.columns[column.id] || rowMetadata.columns[this.getColumnIndex(column.id)]);
			return (columnOverrides == null ? void 0 : columnOverrides.formatter) || (rowMetadata == null ? void 0 : rowMetadata.formatter) || column.formatter || ((_c = this._options.formatterFactory) == null ? void 0 : _c.getFormatter(column)) || this._options.defaultFormatter;
		}
		getEditor(row, cell) {
			var _a, _b, _c, _d, _e, _f;
			let column = this.columns[cell],
				rowMetadata = (_b = (_a = this.data) == null ? void 0 : _a.getItemMetadata) == null ? void 0 : _b.call(_a, row),
				columnMetadata = rowMetadata == null ? void 0 : rowMetadata.columns;
			return ((_c = columnMetadata == null ? void 0 : columnMetadata[column.id]) == null ? void 0 : _c.editor) !== void 0 ? columnMetadata[column.id].editor : ((_d = columnMetadata == null ? void 0 : columnMetadata[cell]) == null ? void 0 : _d.editor) !== void 0 ? columnMetadata[cell].editor : column.editor || ((_f = (_e = this._options) == null ? void 0 : _e.editorFactory) == null ? void 0 : _f.getEditor(column));
		}
		getDataItemValueForColumn(item, columnDef) {
			return this._options.dataItemColumnValueExtractor ? this._options.dataItemColumnValueExtractor(item, columnDef) : item[columnDef.field];
		}
		appendRowHtml(divArrayL, divArrayR, row, range, dataLength) {
			var _a, _b;
			let d = this.getDataItem(row),
				dataLoading = row < dataLength && !d,
				rowCss = "slick-row" + (this.hasFrozenRows && row <= this._options.frozenRow ? " frozen" : "") + (dataLoading ? " loading" : "") + (row === this.activeRow && this._options.showCellSelection ? " active" : "") + (row % 2 === 1 ? " odd" : " even");
			d || (rowCss += " " + this._options.addNewRowCssClass);
			let metadata = (_b = (_a = this.data) == null ? void 0 : _a.getItemMetadata) == null ? void 0 : _b.call(_a, row);
			metadata != null && metadata.cssClasses && (rowCss += " " + metadata.cssClasses);
			let frozenRowOffset = this.getFrozenRowOffset(row),
				rowDiv = Utils.createDomElement("div", {
					className: `ui-widget-content ${rowCss}`,
					style: {
						top: `${this.getRowTop(row) - frozenRowOffset}px`
					}
				}),
				rowDivR;
			divArrayL.push(rowDiv), this.hasFrozenColumns() && (rowDivR = rowDiv.cloneNode(!0), divArrayR.push(rowDivR));
			let colspan, m;
			for (let i = 0, ii = this.columns.length; i < ii; i++)
				if (m = this.columns[i], !(!m || m.hidden)) {
					if (colspan = 1, metadata != null && metadata.columns) {
						let columnData = metadata.columns[m.id] || metadata.columns[i];
						colspan = (columnData == null ? void 0 : columnData.colspan) || 1, colspan === "*" && (colspan = ii - i);
					}
					if (this.columnPosRight[Math.min(ii - 1, i + colspan - 1)] > range.leftPx) {
						if (!m.alwaysRenderColumn && this.columnPosLeft[i] > range.rightPx)
							break;
						this.hasFrozenColumns() && i > this._options.frozenColumn ? this.appendCellHtml(rowDivR, row, i, colspan, d) : this.appendCellHtml(rowDiv, row, i, colspan, d);
					} else
						(m.alwaysRenderColumn || this.hasFrozenColumns() && i <= this._options.frozenColumn) && this.appendCellHtml(rowDiv, row, i, colspan, d);
					colspan > 1 && (i += colspan - 1);
				}
		}
		appendCellHtml(divRow, row, cell, colspan, item) {
			var _a;
			let m = this.columns[cell],
				cellCss = "slick-cell l" + cell + " r" + Math.min(this.columns.length - 1, cell + colspan - 1) + (m.cssClass ? " " + m.cssClass : "");
			this.hasFrozenColumns() && cell <= this._options.frozenColumn && (cellCss += " frozen"), row === this.activeRow && cell === this.activeCell && this._options.showCellSelection && (cellCss += " active");
			for (let key in this.cellCssClasses)
				(_a = this.cellCssClasses[key][row]) != null && _a[m.id] && (cellCss += " " + this.cellCssClasses[key][row][m.id]);
			let value = null,
				formatterResult = "";
			item && (value = this.getDataItemValueForColumn(item, m), formatterResult = this.getFormatter(row, m)(row, cell, value, m, item, this), formatterResult == null && (formatterResult = ""));
			let appendCellResult = this.trigger(this.onBeforeAppendCell, {
					row,
					cell,
					value,
					dataContext: item
				}).getReturnValue(),
				addlCssClasses = typeof appendCellResult == "string" ? appendCellResult : "";
			formatterResult != null && formatterResult.addClasses && (addlCssClasses += (addlCssClasses ? " " : "") + formatterResult.addClasses);
			let toolTipText = formatterResult != null && formatterResult.toolTip ? `${formatterResult.toolTip}` : "",
				cellDiv = document.createElement("div");
			if (cellDiv.className = `${cellCss} ${addlCssClasses || ""}`.trim(), cellDiv.setAttribute("title", toolTipText), m.hasOwnProperty("cellAttrs") && m.cellAttrs instanceof Object)
				for (let key in m.cellAttrs)
					m.cellAttrs.hasOwnProperty(key) && cellDiv.setAttribute(key, m.cellAttrs[key]);
			if (item) {
				let cellResult = Object.prototype.toString.call(formatterResult) !== "[object Object]" ? formatterResult : formatterResult.html || formatterResult.text;
				this.applyHtmlCode(cellDiv, cellResult);
			}
			divRow.appendChild(cellDiv), this.rowsCache[row].cellRenderQueue.push(cell), this.rowsCache[row].cellColSpans[cell] = colspan;
		}
		cleanupRows(rangeToKeep) {
			for (let rowId in this.rowsCache)
				if (this.rowsCache) {
					let i = +rowId,
						removeFrozenRow = !0;
					this.hasFrozenRows && (this._options.frozenBottom && i >= this.actualFrozenRow || !this._options.frozenBottom && i <= this.actualFrozenRow) && (removeFrozenRow = !1), (i = parseInt(rowId, 10)) !== this.activeRow && (i < rangeToKeep.top || i > rangeToKeep.bottom) && removeFrozenRow && this.removeRowFromCache(i);
				}
			this._options.enableAsyncPostRenderCleanup && this.startPostProcessingCleanup();
		}
		/** Invalidate all grid rows and re-render the grid rows */
		invalidate() {
			this.updateRowCount(), this.invalidateAllRows(), this.render();
		}
		/** Invalidate all grid rows */
		invalidateAllRows() {
			this.currentEditor && this.makeActiveCellNormal();
			for (let row in this.rowsCache)
				this.rowsCache && this.removeRowFromCache(+row);
			this._options.enableAsyncPostRenderCleanup && this.startPostProcessingCleanup();
		}
		/**
		 * Invalidate a specific set of row numbers
		 * @param {Number[]} rows
		 */
		invalidateRows(rows) {
			if (!rows || !rows.length)
				return;
			this.vScrollDir = 0;
			let rl = rows.length;
			for (let i = 0; i < rl; i++)
				this.currentEditor && this.activeRow === rows[i] && this.makeActiveCellNormal(), this.rowsCache[rows[i]] && this.removeRowFromCache(rows[i]);
			this._options.enableAsyncPostRenderCleanup && this.startPostProcessingCleanup();
		}
		/**
		 * Invalidate a specific row number
		 * @param {Number} row
		 */
		invalidateRow(row) {
			!row && row !== 0 || this.invalidateRows([row]);
		}
		queuePostProcessedRowForCleanup(cacheEntry, postProcessedRow, rowIdx) {
			var _a;
			this.postProcessgroupId++;
			for (let columnIdx in postProcessedRow)
				postProcessedRow.hasOwnProperty(columnIdx) && this.postProcessedCleanupQueue.push({
					actionType: "C",
					groupId: this.postProcessgroupId,
					node: cacheEntry.cellNodesByColumnIdx[+columnIdx],
					columnIdx: +columnIdx,
					rowIdx
				});
			cacheEntry.rowNode || (cacheEntry.rowNode = []), this.postProcessedCleanupQueue.push({
				actionType: "R",
				groupId: this.postProcessgroupId,
				node: cacheEntry.rowNode
			}), (_a = cacheEntry.rowNode) == null || _a.forEach((node) => node.remove());
		}
		queuePostProcessedCellForCleanup(cellnode, columnIdx, rowIdx) {
			this.postProcessedCleanupQueue.push({
				actionType: "C",
				groupId: this.postProcessgroupId,
				node: cellnode,
				columnIdx,
				rowIdx
			}), cellnode.remove();
		}
		removeRowFromCache(row) {
			var _a;
			let cacheEntry = this.rowsCache[row];
			!cacheEntry || !cacheEntry.rowNode || (this._options.enableAsyncPostRenderCleanup && this.postProcessedRows[row] ? this.queuePostProcessedRowForCleanup(cacheEntry, this.postProcessedRows[row], row) : (_a = cacheEntry.rowNode) == null || _a.forEach((node) => {
				var _a2;
				return (_a2 = node.parentElement) == null ? void 0 : _a2.removeChild(node);
			}), delete this.rowsCache[row], delete this.postProcessedRows[row], this.renderedRows--, this.counter_rows_removed++);
		}
		/** Apply a Formatter Result to a Cell DOM Node */
		applyFormatResultToCellNode(formatterResult, cellNode, suppressRemove) {
			if (formatterResult == null && (formatterResult = ""), Object.prototype.toString.call(formatterResult) !== "[object Object]") {
				this.applyHtmlCode(cellNode, formatterResult);
				return;
			}
			let formatterVal = formatterResult.html || formatterResult.text;
			this.applyHtmlCode(cellNode, formatterVal), formatterResult.removeClasses && !suppressRemove && formatterResult.removeClasses.split(" ").forEach((c) => cellNode.classList.remove(c)), formatterResult.addClasses && formatterResult.addClasses.split(" ").forEach((c) => cellNode.classList.add(c)), formatterResult.toolTip && cellNode.setAttribute("title", formatterResult.toolTip);
		}
		/**
		 * Update a specific cell by its row and column index
		 * @param {Number} row - grid row number
		 * @param {Number} cell - grid cell column number
		 */
		updateCell(row, cell) {
			let cellNode = this.getCellNode(row, cell);
			if (!cellNode)
				return;
			let m = this.columns[cell],
				d = this.getDataItem(row);
			if (this.currentEditor && this.activeRow === row && this.activeCell === cell)
				this.currentEditor.loadValue(d);
			else {
				let formatterResult = d ? this.getFormatter(row, m)(row, cell, this.getDataItemValueForColumn(d, m), m, d, this) : "";
				this.applyFormatResultToCellNode(formatterResult, cellNode), this.invalidatePostProcessingResults(row);
			}
		}
		/**
		 * Update a specific row by its row index
		 * @param {Number} row - grid row number
		 */
		updateRow(row) {
			let cacheEntry = this.rowsCache[row];
			if (!cacheEntry)
				return;
			this.ensureCellNodesInRowsCache(row);
			let formatterResult, d = this.getDataItem(row);
			for (let colIdx in cacheEntry.cellNodesByColumnIdx) {
				if (!cacheEntry.cellNodesByColumnIdx.hasOwnProperty(colIdx))
					continue;
				let columnIdx = +colIdx,
					m = this.columns[columnIdx],
					node = cacheEntry.cellNodesByColumnIdx[columnIdx];
				row === this.activeRow && columnIdx === this.activeCell && this.currentEditor ? this.currentEditor.loadValue(d) : d ? (formatterResult = this.getFormatter(row, m)(row, columnIdx, this.getDataItemValueForColumn(d, m), m, d, this), this.applyFormatResultToCellNode(formatterResult, node)) : Utils.emptyElement(node);
			}
			this.invalidatePostProcessingResults(row);
		}
		/**
		 * Get the number of rows displayed in the viewport
		 * Note that the row count is an approximation because it is a calculated value using this formula (viewport / rowHeight = rowCount),
		 * the viewport must also be displayed for this calculation to work.
		 * @return {Number} rowCount
		 */
		getViewportRowCount() {
			var _a, _b;
			let vh = this.getViewportHeight(),
				scrollbarHeight = (_b = (_a = this.getScrollbarDimensions()) == null ? void 0 : _a.height) != null ? _b : 0;
			return Math.floor((vh - scrollbarHeight) / this._options.rowHeight);
		}
		getViewportHeight() {
			var _a, _b;
			if ((!this._options.autoHeight || this._options.frozenColumn !== -1) && (this.topPanelH = this._options.showTopPanel ? this._options.topPanelHeight + this.getVBoxDelta(this._topPanelScrollers[0]) : 0, this.headerRowH = this._options.showHeaderRow ? this._options.headerRowHeight + this.getVBoxDelta(this._headerRowScroller[0]) : 0, this.footerRowH = this._options.showFooterRow ? this._options.footerRowHeight + this.getVBoxDelta(this._footerRowScroller[0]) : 0), this._options.autoHeight) {
				let fullHeight = this._paneHeaderL.offsetHeight;
				fullHeight += this._options.showHeaderRow ? this._options.headerRowHeight + this.getVBoxDelta(this._headerRowScroller[0]) : 0, fullHeight += this._options.showFooterRow ? this._options.footerRowHeight + this.getVBoxDelta(this._footerRowScroller[0]) : 0, fullHeight += this.getCanvasWidth() > this.viewportW && (_b = (_a = this.scrollbarDimensions) == null ? void 0 : _a.height) != null ? _b : 0, this.viewportH = this._options.rowHeight * this.getDataLengthIncludingAddNew() + (this._options.frozenColumn === -1 ? fullHeight : 0);
			} else {
				let columnNamesH = this._options.showColumnHeader ? Utils.toFloat(Utils.height(this._headerScroller[0])) + this.getVBoxDelta(this._headerScroller[0]) : 0,
					preHeaderH = this._options.createPreHeaderPanel && this._options.showPreHeaderPanel ? this._options.preHeaderPanelHeight + this.getVBoxDelta(this._preHeaderPanelScroller) : 0,
					style = getComputedStyle(this._container);
				this.viewportH = Utils.toFloat(style.height) - Utils.toFloat(style.paddingTop) - Utils.toFloat(style.paddingBottom) - columnNamesH - this.topPanelH - this.headerRowH - this.footerRowH - preHeaderH;
			}
			return this.numVisibleRows = Math.ceil(this.viewportH / this._options.rowHeight), this.viewportH;
		}
		getViewportWidth() {
			return this.viewportW = parseFloat(Utils.innerSize(this._container, "width")), this.viewportW;
		}
		/** Execute a Resize of the Grid Canvas */
		resizeCanvas() {
			var _a, _b, _c, _d, _e, _f;
			if (!this.initialized)
				return;
			if (this.paneTopH = 0, this.paneBottomH = 0, this.viewportTopH = 0, this.viewportBottomH = 0, this.getViewportWidth(), this.getViewportHeight(), this.hasFrozenRows ? this._options.frozenBottom ? (this.paneTopH = this.viewportH - this.frozenRowsHeight - ((_b = (_a = this.scrollbarDimensions) == null ? void 0 : _a.height) != null ? _b : 0), this.paneBottomH = this.frozenRowsHeight + ((_d = (_c = this.scrollbarDimensions) == null ? void 0 : _c.height) != null ? _d : 0)) : (this.paneTopH = this.frozenRowsHeight, this.paneBottomH = this.viewportH - this.frozenRowsHeight) : this.paneTopH = this.viewportH, this.paneTopH += this.topPanelH + this.headerRowH + this.footerRowH, this.hasFrozenColumns() && this._options.autoHeight && (this.paneTopH += (_f = (_e = this.scrollbarDimensions) == null ? void 0 : _e.height) != null ? _f : 0), this.viewportTopH = this.paneTopH - this.topPanelH - this.headerRowH - this.footerRowH, this._options.autoHeight) {
				if (this.hasFrozenColumns()) {
					let style = getComputedStyle(this._headerScrollerL);
					Utils.height(this._container, this.paneTopH + Utils.toFloat(style.height));
				}
				this._paneTopL.style.position = "relative";
			}
			Utils.setStyleSize(this._paneTopL, "top", Utils.height(this._paneHeaderL) || (this._options.showHeaderRow ? this._options.headerRowHeight : 0) + (this._options.showPreHeaderPanel ? this._options.preHeaderPanelHeight : 0)), Utils.height(this._paneTopL, this.paneTopH);
			let paneBottomTop = this._paneTopL.offsetTop + this.paneTopH;
			this._options.autoHeight || Utils.height(this._viewportTopL, this.viewportTopH), this.hasFrozenColumns() ? (Utils.setStyleSize(this._paneTopR, "top", Utils.height(this._paneHeaderL)), Utils.height(this._paneTopR, this.paneTopH), Utils.height(this._viewportTopR, this.viewportTopH), this.hasFrozenRows && (Utils.setStyleSize(this._paneBottomL, "top", paneBottomTop), Utils.height(this._paneBottomL, this.paneBottomH), Utils.setStyleSize(this._paneBottomR, "top", paneBottomTop), Utils.height(this._paneBottomR, this.paneBottomH), Utils.height(this._viewportBottomR, this.paneBottomH))) : this.hasFrozenRows && (Utils.width(this._paneBottomL, "100%"), Utils.height(this._paneBottomL, this.paneBottomH), Utils.setStyleSize(this._paneBottomL, "top", paneBottomTop)), this.hasFrozenRows ? (Utils.height(this._viewportBottomL, this.paneBottomH), this._options.frozenBottom ? (Utils.height(this._canvasBottomL, this.frozenRowsHeight), this.hasFrozenColumns() && Utils.height(this._canvasBottomR, this.frozenRowsHeight)) : (Utils.height(this._canvasTopL, this.frozenRowsHeight), this.hasFrozenColumns() && Utils.height(this._canvasTopR, this.frozenRowsHeight))) : Utils.height(this._viewportTopR, this.viewportTopH), (!this.scrollbarDimensions || !this.scrollbarDimensions.width) && (this.scrollbarDimensions = this.measureScrollbar()), this._options.autosizeColsMode === GridAutosizeColsMode.LegacyForceFit && this.autosizeColumns(), this.updateRowCount(), this.handleScroll(), this.lastRenderedScrollLeft = -1, this.render();
		}
		/**
		 * Update paging information status from the View
		 * @param {PagingInfo} pagingInfo
		 */
		updatePagingStatusFromView(pagingInfo) {
			this.pagingActive = pagingInfo.pageSize !== 0, this.pagingIsLastPage = pagingInfo.pageNum === pagingInfo.totalPages - 1;
		}
		/** Update the dataset row count */
		updateRowCount() {
			var _a, _b, _c, _d;
			if (!this.initialized)
				return;
			let dataLength = this.getDataLength(),
				dataLengthIncludingAddNew = this.getDataLengthIncludingAddNew(),
				numberOfRows = 0,
				oldH = this.hasFrozenRows && !this._options.frozenBottom ? Utils.height(this._canvasBottomL) : Utils.height(this._canvasTopL);
			this.hasFrozenRows ? numberOfRows = this.getDataLength() - this._options.frozenRow : numberOfRows = dataLengthIncludingAddNew + (this._options.leaveSpaceForNewRows ? this.numVisibleRows - 1 : 0);
			let tempViewportH = Utils.height(this._viewportScrollContainerY),
				oldViewportHasVScroll = this.viewportHasVScroll;
			this.viewportHasVScroll = this._options.alwaysShowVerticalScroll || !this._options.autoHeight && numberOfRows * this._options.rowHeight > tempViewportH, this.makeActiveCellNormal();
			let r1 = dataLength - 1;
			for (let i in this.rowsCache)
				Number(i) > r1 && this.removeRowFromCache(+i);
			this._options.enableAsyncPostRenderCleanup && this.startPostProcessingCleanup(), this.activeCellNode && this.activeRow > r1 && this.resetActiveCell(), oldH = this.h, this._options.autoHeight ? this.h = this._options.rowHeight * numberOfRows : (this.th = Math.max(this._options.rowHeight * numberOfRows, tempViewportH - ((_b = (_a = this.scrollbarDimensions) == null ? void 0 : _a.height) != null ? _b : 0)), this.th < this.maxSupportedCssHeight ? (this.h = this.ph = this.th, this.n = 1, this.cj = 0) : (this.h = this.maxSupportedCssHeight, this.ph = this.h / 100, this.n = Math.floor(this.th / this.ph), this.cj = (this.th - this.h) / (this.n - 1))), (this.h !== oldH || this.enforceFrozenRowHeightRecalc) && (this.hasFrozenRows && !this._options.frozenBottom ? (Utils.height(this._canvasBottomL, this.h), this.hasFrozenColumns() && Utils.height(this._canvasBottomR, this.h)) : (Utils.height(this._canvasTopL, this.h), Utils.height(this._canvasTopR, this.h)), this.scrollTop = this._viewportScrollContainerY.scrollTop, this.enforceFrozenRowHeightRecalc = !1);
			let oldScrollTopInRange = this.scrollTop + this.offset <= this.th - tempViewportH;
			this.th === 0 || this.scrollTop === 0 ? this.page = this.offset = 0 : oldScrollTopInRange ? this.scrollTo(this.scrollTop + this.offset) : this.scrollTo(this.th - tempViewportH + ((_d = (_c = this.scrollbarDimensions) == null ? void 0 : _c.height) != null ? _d : 0)), this.h !== oldH && this._options.autoHeight && this.resizeCanvas(), this._options.autosizeColsMode === GridAutosizeColsMode.LegacyForceFit && oldViewportHasVScroll !== this.viewportHasVScroll && this.autosizeColumns(), this.updateCanvasWidth(!1);
		}
		/** @alias `getVisibleRange` */
		getViewport(viewportTop, viewportLeft) {
			return this.getVisibleRange(viewportTop, viewportLeft);
		}
		getVisibleRange(viewportTop, viewportLeft) {
			return viewportTop != null || (viewportTop = this.scrollTop), viewportLeft != null || (viewportLeft = this.scrollLeft), {
				top: this.getRowFromPosition(viewportTop),
				bottom: this.getRowFromPosition(viewportTop + this.viewportH) + 1,
				leftPx: viewportLeft,
				rightPx: viewportLeft + this.viewportW
			};
		}
		/** Get rendered range */
		getRenderedRange(viewportTop, viewportLeft) {
			let range = this.getVisibleRange(viewportTop, viewportLeft),
				buffer = Math.round(this.viewportH / this._options.rowHeight),
				minBuffer = this._options.minRowBuffer;
			return this.vScrollDir === -1 ? (range.top -= buffer, range.bottom += minBuffer) : this.vScrollDir === 1 ? (range.top -= minBuffer, range.bottom += buffer) : (range.top -= minBuffer, range.bottom += minBuffer), range.top = Math.max(0, range.top), range.bottom = Math.min(this.getDataLengthIncludingAddNew() - 1, range.bottom), range.leftPx -= this.viewportW, range.rightPx += this.viewportW, range.leftPx = Math.max(0, range.leftPx), range.rightPx = Math.min(this.canvasWidth, range.rightPx), range;
		}
		ensureCellNodesInRowsCache(row) {
			var _a;
			let cacheEntry = this.rowsCache[row];
			if (cacheEntry != null && cacheEntry.cellRenderQueue.length && ((_a = cacheEntry.rowNode) != null && _a.length)) {
				let rowNode = cacheEntry.rowNode,
					children = Array.from(rowNode[0].children);
				rowNode.length > 1 && (children = children.concat(Array.from(rowNode[1].children)));
				let i = children.length - 1;
				for (; cacheEntry.cellRenderQueue.length;) {
					let columnIdx = cacheEntry.cellRenderQueue.pop();
					cacheEntry.cellNodesByColumnIdx[columnIdx] = children[i--];
				}
			}
		}
		cleanUpCells(range, row) {
			var _a, _b;
			if (this.hasFrozenRows && (this._options.frozenBottom && row > this.actualFrozenRow || row <= this.actualFrozenRow))
				return;
			let totalCellsRemoved = 0,
				cacheEntry = this.rowsCache[row],
				cellsToRemove = [];
			for (let cellNodeIdx in cacheEntry.cellNodesByColumnIdx) {
				if (!cacheEntry.cellNodesByColumnIdx.hasOwnProperty(cellNodeIdx))
					continue;
				let i = +cellNodeIdx;
				if (i <= this._options.frozenColumn || Array.isArray(this.columns) && this.columns[i] && this.columns[i].alwaysRenderColumn)
					continue;
				let colspan = cacheEntry.cellColSpans[i];
				(this.columnPosLeft[i] > range.rightPx || this.columnPosRight[Math.min(this.columns.length - 1, (i || 0) + colspan - 1)] < range.leftPx) && (row === this.activeRow && Number(i) === this.activeCell || cellsToRemove.push(i));
			}
			let cellToRemove, cellNode;
			for (; Utils.isDefined(cellToRemove = cellsToRemove.pop());)
				cellNode = cacheEntry.cellNodesByColumnIdx[cellToRemove], this._options.enableAsyncPostRenderCleanup && ((_a = this.postProcessedRows[row]) != null && _a[cellToRemove]) ? this.queuePostProcessedCellForCleanup(cellNode, cellToRemove, row) : (_b = cellNode.parentElement) == null || _b.removeChild(cellNode), delete cacheEntry.cellColSpans[cellToRemove], delete cacheEntry.cellNodesByColumnIdx[cellToRemove], this.postProcessedRows[row] && delete this.postProcessedRows[row][cellToRemove], totalCellsRemoved++;
		}
		cleanUpAndRenderCells(range) {
			var _a, _b, _c, _d;
			let cacheEntry, divRow = document.createElement("div"),
				processedRows = [],
				cellsAdded, totalCellsAdded = 0,
				colspan;
			for (let row = range.top, btm = range.bottom; row <= btm; row++) {
				if (cacheEntry = this.rowsCache[row], !cacheEntry)
					continue;
				this.ensureCellNodesInRowsCache(row), this.cleanUpCells(range, row), cellsAdded = 0;
				let metadata = (_c = (_b = (_a = this.data) == null ? void 0 : _a.getItemMetadata) == null ? void 0 : _b.call(_a, row)) != null ? _c : {};
				metadata = metadata == null ? void 0 : metadata.columns;
				let d = this.getDataItem(row);
				for (let i = 0, ii = this.columns.length; i < ii; i++) {
					if (!this.columns[i] || this.columns[i].hidden)
						continue;
					if (this.columnPosLeft[i] > range.rightPx)
						break;
					if (Utils.isDefined(colspan = cacheEntry.cellColSpans[i])) {
						i += colspan > 1 ? colspan - 1 : 0;
						continue;
					}
					if (colspan = 1, metadata) {
						let columnData = metadata[this.columns[i].id] || metadata[i];
						colspan = (_d = columnData == null ? void 0 : columnData.colspan) != null ? _d : 1, colspan === "*" && (colspan = ii - i);
					}
					let colspanNb = colspan;
					this.columnPosRight[Math.min(ii - 1, i + colspanNb - 1)] > range.leftPx && (this.appendCellHtml(divRow, row, i, colspanNb, d), cellsAdded++), i += colspanNb > 1 ? colspanNb - 1 : 0;
				}
				cellsAdded && (totalCellsAdded += cellsAdded, processedRows.push(row));
			}
			if (!divRow.children.length)
				return;
			let processedRow, node;
			for (; Utils.isDefined(processedRow = processedRows.pop());) {
				cacheEntry = this.rowsCache[processedRow];
				let columnIdx;
				for (; Utils.isDefined(columnIdx = cacheEntry.cellRenderQueue.pop());)
					node = divRow.lastChild, node && (this.hasFrozenColumns() && columnIdx > this._options.frozenColumn ? cacheEntry.rowNode[1].appendChild(node) : cacheEntry.rowNode[0].appendChild(node), cacheEntry.cellNodesByColumnIdx[columnIdx] = node);
			}
		}
		renderRows(range) {
			var _a, _b, _c, _d;
			let divArrayL = [],
				divArrayR = [],
				rows = [],
				needToReselectCell = !1,
				dataLength = this.getDataLength();
			for (let i = range.top, ii = range.bottom; i <= ii; i++)
				this.rowsCache[i] || this.hasFrozenRows && this._options.frozenBottom && i === this.getDataLength() || (this.renderedRows++, rows.push(i), this.rowsCache[i] = {
					rowNode: null,
					// ColSpans of rendered cells (by column idx).
					// Can also be used for checking whether a cell has been rendered.
					cellColSpans: [],
					// Cell nodes (by column idx).  Lazy-populated by ensureCellNodesInRowsCache().
					cellNodesByColumnIdx: [],
					// Column indices of cell nodes that have been rendered, but not yet indexed in
					// cellNodesByColumnIdx.  These are in the same order as cell nodes added at the
					// end of the row.
					cellRenderQueue: []
				}, this.appendRowHtml(divArrayL, divArrayR, i, range, dataLength), this.activeCellNode && this.activeRow === i && (needToReselectCell = !0), this.counter_rows_rendered++);
			if (!rows.length)
				return;
			let x = document.createElement("div"),
				xRight = document.createElement("div");
			divArrayL.forEach((elm) => x.appendChild(elm)), divArrayR.forEach((elm) => xRight.appendChild(elm));
			for (let i = 0, ii = rows.length; i < ii; i++)
				this.hasFrozenRows && rows[i] >= this.actualFrozenRow ? this.hasFrozenColumns() ? (_a = this.rowsCache) != null && _a.hasOwnProperty(rows[i]) && x.firstChild && xRight.firstChild && (this.rowsCache[rows[i]].rowNode = [x.firstChild, xRight.firstChild], this._canvasBottomL.appendChild(x.firstChild), this._canvasBottomR.appendChild(xRight.firstChild)) : (_b = this.rowsCache) != null && _b.hasOwnProperty(rows[i]) && x.firstChild && (this.rowsCache[rows[i]].rowNode = [x.firstChild], this._canvasBottomL.appendChild(x.firstChild)) : this.hasFrozenColumns() ? (_c = this.rowsCache) != null && _c.hasOwnProperty(rows[i]) && x.firstChild && xRight.firstChild && (this.rowsCache[rows[i]].rowNode = [x.firstChild, xRight.firstChild], this._canvasTopL.appendChild(x.firstChild), this._canvasTopR.appendChild(xRight.firstChild)) : (_d = this.rowsCache) != null && _d.hasOwnProperty(rows[i]) && x.firstChild && (this.rowsCache[rows[i]].rowNode = [x.firstChild], this._canvasTopL.appendChild(x.firstChild));
			needToReselectCell && (this.activeCellNode = this.getCellNode(this.activeRow, this.activeCell));
		}
		startPostProcessing() {
			this._options.enableAsyncPostRender && (clearTimeout(this.h_postrender), this.h_postrender = setTimeout(this.asyncPostProcessRows.bind(this), this._options.asyncPostRenderDelay));
		}
		startPostProcessingCleanup() {
			this._options.enableAsyncPostRenderCleanup && (clearTimeout(this.h_postrenderCleanup), this.h_postrenderCleanup = setTimeout(this.asyncPostProcessCleanupRows.bind(this), this._options.asyncPostRenderCleanupDelay));
		}
		invalidatePostProcessingResults(row) {
			for (let columnIdx in this.postProcessedRows[row])
				this.postProcessedRows[row].hasOwnProperty(columnIdx) && (this.postProcessedRows[row][columnIdx] = "C");
			this.postProcessFromRow = Math.min(this.postProcessFromRow, row), this.postProcessToRow = Math.max(this.postProcessToRow, row), this.startPostProcessing();
		}
		updateRowPositions() {
			for (let row in this.rowsCache)
				if (this.rowsCache) {
					let rowNumber = row ? parseInt(row, 10) : 0;
					Utils.setStyleSize(this.rowsCache[rowNumber].rowNode[0], "top", this.getRowTop(rowNumber));
				}
		}
		/** (re)Render the grid */
		render() {
			if (!this.initialized)
				return;

			var currentCell; // Mitsukibo
			var currentRow; // Mitsukibo
			var currentEditor; // Mitsukibo

			if (m_intRenderNest === 0)
			{
				currentCell = this.activeCell; // Mitsukibo
				currentRow = this.activeRow; // Mitsukibo
				currentEditor = this.currentEditor; // Mitsukibo
			}
			//console.log("A:" + m_intRenderNest + ":" + currentCell);
			m_intRenderNest++; // Mitsukibo

			this.scrollThrottle.dequeue();
			let visible = this.getVisibleRange(),
				rendered = this.getRenderedRange();

			if (this.cleanupRows(rendered), this.lastRenderedScrollLeft !== this.scrollLeft) {
				if (this.hasFrozenRows) {
					let renderedFrozenRows = Utils.extend(!0, {}, rendered);
					this._options.frozenBottom ? (renderedFrozenRows.top = this.actualFrozenRow, renderedFrozenRows.bottom = this.getDataLength()) : (renderedFrozenRows.top = 0, renderedFrozenRows.bottom = this._options.frozenRow), this.cleanUpAndRenderCells(renderedFrozenRows);
				}
				this.cleanUpAndRenderCells(rendered);
			}
			this.renderRows(rendered), this.hasFrozenRows && (this._options.frozenBottom ? this.renderRows({
				top: this.actualFrozenRow,
				bottom: this.getDataLength() - 1,
				leftPx: rendered.leftPx,
				rightPx: rendered.rightPx
			}) : this.renderRows({
				top: 0,
				bottom: this._options.frozenRow - 1,
				leftPx: rendered.leftPx,
				rightPx: rendered.rightPx
			})), this.postProcessFromRow = visible.top, this.postProcessToRow = Math.min(this.getDataLengthIncludingAddNew() - 1, visible.bottom), this.startPostProcessing(), this.lastRenderedScrollTop = this.scrollTop, this.lastRenderedScrollLeft = this.scrollLeft, this.h_render = null, this.trigger(this.onRendered, {
				startRow: visible.top,
				endRow: visible.bottom,
				grid: this
			});

			// Mitsukibo: put grid back into edit mode if it was already
			// re: https://stackoverflow.com/questions/38188605/manually-trigger-slickgrid-events
			if ((currentEditor !== undefined) && (currentRow !== undefined) && (currentCell !== undefined))
			{
				//console.log("B:" + m_intRenderNest + ":" + currentCell);
				if (m_intRenderNest === 1)
				{
					//currentCell++;
				//console.log("C:" + m_intRenderNest + ":" + currentCell);
				}
				try
				{
					this.gotoCell(currentRow, currentCell);
					this.onClick.notify({row:currentRow, cell:currentCell}, new SlickEvent());
				}
				catch(err)
				{
					console.log(err);
				}

				//console.log("D:" + m_intRenderNest + ":" + currentCell);
			}
			m_intRenderNest--;
			//console.log("E:" + m_intRenderNest + ":" + currentCell);
		}
		handleHeaderRowScroll() {
			let scrollLeft = this._headerRowScrollContainer.scrollLeft;
			scrollLeft !== this._viewportScrollContainerX.scrollLeft && (this._viewportScrollContainerX.scrollLeft = scrollLeft);
		}
		handleFooterRowScroll() {
			let scrollLeft = this._footerRowScrollContainer.scrollLeft;
			scrollLeft !== this._viewportScrollContainerX.scrollLeft && (this._viewportScrollContainerX.scrollLeft = scrollLeft);
		}
		handlePreHeaderPanelScroll() {
			this.handleElementScroll(this._preHeaderPanelScroller);
		}
		handleElementScroll(element) {
			let scrollLeft = element.scrollLeft;
			scrollLeft !== this._viewportScrollContainerX.scrollLeft && (this._viewportScrollContainerX.scrollLeft = scrollLeft);
		}
		handleScroll() {
			return this.scrollTop = this._viewportScrollContainerY.scrollTop, this.scrollLeft = this._viewportScrollContainerX.scrollLeft, this._handleScroll(!1);
		}
		_handleScroll(isMouseWheel) {
			let maxScrollDistanceY = this._viewportScrollContainerY.scrollHeight - this._viewportScrollContainerY.clientHeight,
				maxScrollDistanceX = this._viewportScrollContainerY.scrollWidth - this._viewportScrollContainerY.clientWidth;
			maxScrollDistanceY = Math.max(0, maxScrollDistanceY), maxScrollDistanceX = Math.max(0, maxScrollDistanceX), this.scrollTop > maxScrollDistanceY && (this.scrollTop = maxScrollDistanceY), this.scrollLeft > maxScrollDistanceX && (this.scrollLeft = maxScrollDistanceX);
			let vScrollDist = Math.abs(this.scrollTop - this.prevScrollTop),
				hScrollDist = Math.abs(this.scrollLeft - this.prevScrollLeft);
			if (hScrollDist && (this.prevScrollLeft = this.scrollLeft, this._viewportScrollContainerX.scrollLeft = this.scrollLeft, this._headerScrollContainer.scrollLeft = this.scrollLeft, this._topPanelScrollers[0].scrollLeft = this.scrollLeft, this._options.createFooterRow && (this._footerRowScrollContainer.scrollLeft = this.scrollLeft), this._options.createPreHeaderPanel && (this.hasFrozenColumns() ? this._preHeaderPanelScrollerR.scrollLeft = this.scrollLeft : this._preHeaderPanelScroller.scrollLeft = this.scrollLeft), this.hasFrozenColumns() ? (this.hasFrozenRows && (this._viewportTopR.scrollLeft = this.scrollLeft), this._headerRowScrollerR.scrollLeft = this.scrollLeft) : (this.hasFrozenRows && (this._viewportTopL.scrollLeft = this.scrollLeft), this._headerRowScrollerL.scrollLeft = this.scrollLeft)), vScrollDist && !this._options.autoHeight)
				if (this.vScrollDir = this.prevScrollTop < this.scrollTop ? 1 : -1, this.prevScrollTop = this.scrollTop, isMouseWheel && (this._viewportScrollContainerY.scrollTop = this.scrollTop), this.hasFrozenColumns() && (this.hasFrozenRows && !this._options.frozenBottom ? this._viewportBottomL.scrollTop = this.scrollTop : this._viewportTopL.scrollTop = this.scrollTop), vScrollDist < this.viewportH)
					this.scrollTo(this.scrollTop + this.offset);
				else {
					let oldOffset = this.offset;
					this.h === this.viewportH ? this.page = 0 : this.page = Math.min(this.n - 1, Math.floor(this.scrollTop * ((this.th - this.viewportH) / (this.h - this.viewportH)) * (1 / this.ph))), this.offset = Math.round(this.page * this.cj), oldOffset !== this.offset && this.invalidateAllRows();
				}
			if (hScrollDist || vScrollDist) {
				let dx = Math.abs(this.lastRenderedScrollLeft - this.scrollLeft),
					dy = Math.abs(this.lastRenderedScrollTop - this.scrollTop);
				(dx > 20 || dy > 20) && (this._options.forceSyncScrolling || dy < this.viewportH && dx < this.viewportW ? this.render() : this.scrollThrottle.enqueue(), this.trigger(this.onViewportChanged, {}));
			}
			return this.trigger(this.onScroll, {
				scrollLeft: this.scrollLeft,
				scrollTop: this.scrollTop
			}), !!(hScrollDist || vScrollDist);
		}
		/**
		 * limits the frequency at which the provided action is executed.
		 * call enqueue to execute the action - it will execute either immediately or, if it was executed less than minPeriod_ms in the past, as soon as minPeriod_ms has expired.
		 * call dequeue to cancel any pending action.
		 */
		actionThrottle(action, minPeriod_ms) {
			let blocked = !1,
				queued = !1,
				enqueue = () => {
					blocked ? queued = !0 : blockAndExecute();
				},
				dequeue = () => {
					queued = !1;
				},
				blockAndExecute = () => {
					blocked = !0, setTimeout(unblock, minPeriod_ms), action.call(this);
				},
				unblock = () => {
					queued ? (dequeue(), blockAndExecute()) : blocked = !1;
				};
			return {
				enqueue: enqueue.bind(this),
				dequeue: dequeue.bind(this)
			};
		}
		asyncPostProcessRows() {
			let dataLength = this.getDataLength();
			for (; this.postProcessFromRow <= this.postProcessToRow;) {
				let row = this.vScrollDir >= 0 ? this.postProcessFromRow++ : this.postProcessToRow--,
					cacheEntry = this.rowsCache[row];
				if (!(!cacheEntry || row >= dataLength)) {
					this.postProcessedRows[row] || (this.postProcessedRows[row] = {}), this.ensureCellNodesInRowsCache(row);
					for (let colIdx in cacheEntry.cellNodesByColumnIdx) {
						if (!cacheEntry.cellNodesByColumnIdx.hasOwnProperty(colIdx))
							continue;
						let columnIdx = +colIdx,
							m = this.columns[columnIdx],
							processedStatus = this.postProcessedRows[row][columnIdx];
						if (m.asyncPostRender && processedStatus !== "R") {
							let node = cacheEntry.cellNodesByColumnIdx[columnIdx];
							node && m.asyncPostRender(node, row, this.getDataItem(row), m, processedStatus === "C"), this.postProcessedRows[row][columnIdx] = "R";
						}
					}
					this.h_postrender = setTimeout(this.asyncPostProcessRows.bind(this), this._options.asyncPostRenderDelay);
					return;
				}
			}
		}
		asyncPostProcessCleanupRows() {
			if (this.postProcessedCleanupQueue.length > 0) {
				let groupId = this.postProcessedCleanupQueue[0].groupId;
				for (; this.postProcessedCleanupQueue.length > 0 && this.postProcessedCleanupQueue[0].groupId === groupId;) {
					let entry = this.postProcessedCleanupQueue.shift();
					if ((entry == null ? void 0 : entry.actionType) === "R" && entry.node.forEach((node) => {
							node.remove();
						}), (entry == null ? void 0 : entry.actionType) === "C") {
						let column = this.columns[entry.columnIdx];
						column.asyncPostRenderCleanup && entry.node && column.asyncPostRenderCleanup(entry.node, entry.rowIdx, column);
					}
				}
				this.h_postrenderCleanup = setTimeout(this.asyncPostProcessCleanupRows.bind(this), this._options.asyncPostRenderCleanupDelay);
			}
		}
		updateCellCssStylesOnRenderedRows(addedHash, removedHash) {
			let node, columnId, addedRowHash, removedRowHash;
			for (let row in this.rowsCache)
				if (this.rowsCache) {
					if (removedRowHash = removedHash == null ? void 0 : removedHash[row], addedRowHash = addedHash == null ? void 0 : addedHash[row], removedRowHash)
						for (columnId in removedRowHash)
							(!addedRowHash || removedRowHash[columnId] !== addedRowHash[columnId]) && (node = this.getCellNode(+row, this.getColumnIndex(columnId)), node && node.classList.remove(removedRowHash[columnId]));
					if (addedRowHash)
						for (columnId in addedRowHash)
							(!removedRowHash || removedRowHash[columnId] !== addedRowHash[columnId]) && (node = this.getCellNode(+row, this.getColumnIndex(columnId)), node && node.classList.add(addedRowHash[columnId]));
				}
		}
		/**
		 * Adds an "overlay" of CSS classes to cell DOM elements. SlickGrid can have many such overlays associated with different keys and they are frequently used by plugins. For example, SlickGrid uses this method internally to decorate selected cells with selectedCellCssClass (see options).
		 * @param {String} key A unique key you can use in calls to setCellCssStyles and removeCellCssStyles. If a hash with that key has already been set, an exception will be thrown.
		 * @param {CssStyleHash} hash A hash of additional cell CSS classes keyed by row number and then by column id. Multiple CSS classes can be specified and separated by space.
		 * @example
		 * `{
		 * 	 0: { number_column: SlickEvent; title_column: SlickEvent;	},
		 * 	 4: { percent_column: SlickEvent; }
		 * }`
		 */
		addCellCssStyles(key, hash) {
			if (this.cellCssClasses[key])
				throw new Error(`SlickGrid addCellCssStyles: cell CSS hash with key "${key}" already exists.`);
			this.cellCssClasses[key] = hash, this.updateCellCssStylesOnRenderedRows(hash, null), this.trigger(this.onCellCssStylesChanged, {
				key,
				hash,
				grid: this
			});
		}
		/**
		 * Removes an "overlay" of CSS classes from cell DOM elements. See setCellCssStyles for more.
		 * @param {String} key A string key.
		 */
		removeCellCssStyles(key) {
			this.cellCssClasses[key] && (this.updateCellCssStylesOnRenderedRows(null, this.cellCssClasses[key]), delete this.cellCssClasses[key], this.trigger(this.onCellCssStylesChanged, {
				key,
				hash: null,
				grid: this
			}));
		}
		/**
		 * Sets CSS classes to specific grid cells by calling removeCellCssStyles(key) followed by addCellCssStyles(key, hash). key is name for this set of styles so you can reference it later - to modify it or remove it, for example. hash is a per-row-index, per-column-name nested hash of CSS classes to apply.
		 * Suppose you have a grid with columns:
		 * ["login", "name", "birthday", "age", "likes_icecream", "favorite_cake"]
		 * ...and you'd like to highlight the "birthday" and "age" columns for people whose birthday is today, in this case, rows at index 0 and 9. (The first and tenth row in the grid).
		 * @param {String} key A string key. Will overwrite any data already associated with this key.
		 * @param {Object} hash A hash of additional cell CSS classes keyed by row number and then by column id. Multiple CSS classes can be specified and separated by space.
		 */
		setCellCssStyles(key, hash) {
			let prevHash = this.cellCssClasses[key];
			this.cellCssClasses[key] = hash, this.updateCellCssStylesOnRenderedRows(hash, prevHash), this.trigger(this.onCellCssStylesChanged, {
				key,
				hash,
				grid: this
			});
		}
		/**
		 * Accepts a key name, returns the group of CSS styles defined under that name. See setCellCssStyles for more info.
		 * @param {String} key A string.
		 */
		getCellCssStyles(key) {
			return this.cellCssClasses[key];
		}
		/**
		 * Flashes the cell twice by toggling the CSS class 4 times.
		 * @param {Number} row A row index.
		 * @param {Number} cell A column index.
		 * @param {Number} [speed] (optional) - The milliseconds delay between the toggling calls. Defaults to 100 ms.
		 */
		flashCell(row, cell, speed) {
			speed = speed || 250;
			let toggleCellClass = (cellNode, times) => {
				times < 1 || setTimeout(() => {
					times % 2 === 0 ? cellNode.classList.add(this._options.cellFlashingCssClass || "") : cellNode.classList.remove(this._options.cellFlashingCssClass || ""), toggleCellClass(cellNode, times - 1);
				}, speed);
			};
			if (this.rowsCache[row]) {
				let cellNode = this.getCellNode(row, cell);
				cellNode && toggleCellClass(cellNode, 5);
			}
		}
		//////////////////////////////////////////////////////////////////////////////////////////////
		// Interactivity
		handleMouseWheel(e, _delta, deltaX, deltaY) {
			this.scrollTop = Math.max(0, this._viewportScrollContainerY.scrollTop - deltaY * this._options.rowHeight), this.scrollLeft = this._viewportScrollContainerX.scrollLeft + deltaX * 10, this._handleScroll(!0) && e.preventDefault();
		}
		handleDragInit(e, dd) {
			let cell = this.getCellFromEvent(e);
			if (!cell || !this.cellExists(cell.row, cell.cell))
				return !1;
			let retval = this.trigger(this.onDragInit, dd, e);
			return retval.isImmediatePropagationStopped() ? retval.getReturnValue() : !1;
		}
		handleDragStart(e, dd) {
			let cell = this.getCellFromEvent(e);
			if (!cell || !this.cellExists(cell.row, cell.cell))
				return !1;
			let retval = this.trigger(this.onDragStart, dd, e);
			return retval.isImmediatePropagationStopped() ? retval.getReturnValue() : !1;
		}
		handleDrag(e, dd) {
			return this.trigger(this.onDrag, dd, e).getReturnValue();
		}
		handleDragEnd(e, dd) {
			this.trigger(this.onDragEnd, dd, e);
		}
		handleKeyDown(e) 
		{
			var _a, _b, _c, _d;
			let handled = this.trigger(this.onKeyDown, {
				row: this.activeRow,
				cell: this.activeCell
			}, e).isImmediatePropagationStopped();
			
			if (!handled && !e.shiftKey && !e.altKey) 
			{
				if (this._options.editable && ((_a = this.currentEditor) != null && _a.keyCaptureList) && this.currentEditor.keyCaptureList.indexOf(String(e.which)) > -1)
				{
					return;
				}
				e.which === keyCode.HOME ? handled = e.ctrlKey ? this.navigateTop() : this.navigateRowStart() : e.which === keyCode.END && (handled = e.ctrlKey ? this.navigateBottom() : this.navigateRowEnd());
			}
			
			if (!handled)
			{
				if (!e.shiftKey && !e.altKey && !e.ctrlKey) 
				{
					if (this._options.editable && ((_b = this.currentEditor) != null && _b.keyCaptureList) && this.currentEditor.keyCaptureList.indexOf(String(e.which)) > -1)
					{
						return;
					}

					if (e.which === keyCode.ESCAPE) 
					{
						if (!((_c = this.getEditorLock()) != null && _c.isActive()))
						{
							return;
						}

						this.cancelEditAndSetFocus();
					} 
					else 
					{
						// Mitsukibo allow shift+enter to also tab forward (instead of down also for enter)
						if (e.which === keyCode.PAGE_DOWN)
						{
							this.navigatePageDown();
							handled = !0;
						}

						if (e.which === keyCode.PAGE_UP)
						{
							this.navigatePageUp();
							handled = !0;
						}

						if (e.which === keyCode.LEFT)
						{
							handled = this.navigateLeft();
						}

						if (e.which === keyCode.RIGHT)
						{
							handled = this.navigateRight();
						}

						if (e.which === keyCode.UP)
						{
							handled = this.navigateUp();
							//handled = !0;
						}

						if (e.which === keyCode.DOWN)
						{
							handled = this.navigateDown();
							//handled = !0;
						}

						if (e.which === keyCode.TAB)
						{
							handled = this.navigateNext();
						}

						if (e.which === keyCode.ENTER)
						{
							if (!this._options.editable || !this.currentEditor)
							{
								handled = this.navigateNext();
							}
							else
							{
								if (this._options.editable && this.currentEditor)
								{
									if (this.activeRow === this.getDataLength())
									{
										handled = this.navigateNext();
									}
									else if ((_d = this.getEditorLock()) != null)
									{
										_d.commitCurrentEdit();
										this.makeActiveCellEditable(undefined, undefined, e);
										//handled = !0;
										//handled = this.navigateNext();
										//this.commitEditAndSetFocus();
									}
								}
							}
						}
					}
				} 
				else 
				{
					if (this._options.editable && ((_b = this.currentEditor) != null && _b.keyCaptureList) && this.currentEditor.keyCaptureList.indexOf(String(e.which)) > -1)
					{
						return;
					}

					if (e.which === keyCode.ESCAPE) 
					{
						if (!((_c = this.getEditorLock()) != null && _c.isActive()))
						{
							return;
						}

						this.cancelEditAndSetFocus();
					} 
					else 
					{
						// Mitsukibo allow shift+enter to also tab back
						if (e.which === keyCode.TAB && (e.shiftKey && !e.ctrlKey && !e.altKey))
						{
							handled = this.navigatePrev();
						}
						else if (e.which === keyCode.ENTER && (e.shiftKey && !e.ctrlKey && !e.altKey)) 
						{
							handled = this.navigatePrev();
						}
						//handled = !0;
					}
				}
			}

			if (handled) 
			{
				e.stopPropagation();
				e.preventDefault();
				try 
				{
					e.originalEvent.keyCode = 0;
				} 
				catch (error) 
				{
				}
			}
		}
		handleClick(evt) {
			var _a, _b, _c;
			let e = evt instanceof SlickEventData ? evt.getNativeEvent() : evt;
			if (!this.currentEditor && (e.target !== document.activeElement || e.target.classList.contains("slick-cell"))) {
				let selection = this.getTextSelection();
				this.setFocus(), this.setTextSelection(selection);
			}
			let cell = this.getCellFromEvent(e);
			if (!(!cell || this.currentEditor !== null && this.activeRow === cell.row && this.activeCell === cell.cell) && (evt = this.trigger(this.onClick, {
					row: cell.row,
					cell: cell.cell
				}, evt || e), !evt.isImmediatePropagationStopped() && this.canCellBeActive(cell.row, cell.cell) && (!((_a = this.getEditorLock()) != null && _a.isActive()) || (_b = this.getEditorLock()) != null && _b.commitCurrentEdit()))) {
				this.scrollRowIntoView(cell.row, !1);
				let preClickModeOn = ((_c = e.target) == null ? void 0 : _c.className) === preClickClassName,
					column = this.columns[cell.cell],
					suppressActiveCellChangedEvent = !!(this._options.editable && (column != null && column.editor) && this._options.suppressActiveCellChangeOnEdit);
				this.setActiveCellInternal(this.getCellNode(cell.row, cell.cell), null, preClickModeOn, suppressActiveCellChangedEvent, e);
			}
		}
		handleContextMenu(e) {
			let cell = e.target.closest(".slick-cell");
			cell && (this.activeCellNode === cell && this.currentEditor !== null || this.trigger(this.onContextMenu, {}, e));
		}
		handleDblClick(e) {
			let cell = this.getCellFromEvent(e);
			!cell || this.currentEditor !== null && this.activeRow === cell.row && this.activeCell === cell.cell || (this.trigger(this.onDblClick, {
				row: cell.row,
				cell: cell.cell
			}, e), !e.defaultPrevented && this._options.editable && this.gotoCell(cell.row, cell.cell, !0, e));
		}
		handleHeaderMouseEnter(e) {
			let c = Utils.storage.get(e.target.closest(".slick-header-column"), "column");
			c && this.trigger(this.onHeaderMouseEnter, {
				column: c,
				grid: this
			}, e);
		}
		handleHeaderMouseLeave(e) {
			let c = Utils.storage.get(e.target.closest(".slick-header-column"), "column");
			c && this.trigger(this.onHeaderMouseLeave, {
				column: c,
				grid: this
			}, e);
		}
		handleHeaderRowMouseEnter(e) {
			let c = Utils.storage.get(e.target.closest(".slick-headerrow-column"), "column");
			c && this.trigger(this.onHeaderRowMouseEnter, {
				column: c,
				grid: this
			}, e);
		}
		handleHeaderRowMouseLeave(e) {
			let c = Utils.storage.get(e.target.closest(".slick-headerrow-column"), "column");
			c && this.trigger(this.onHeaderRowMouseLeave, {
				column: c,
				grid: this
			}, e);
		}
		handleHeaderContextMenu(e) {
			let header = e.target.closest(".slick-header-column"),
				column = header && Utils.storage.get(header, "column");
			this.trigger(this.onHeaderContextMenu, {
				column
			}, e);
		}
		handleHeaderClick(e) {
			if (this.columnResizeDragging)
				return;
			let header = e.target.closest(".slick-header-column"),
				column = header && Utils.storage.get(header, "column");
			column && this.trigger(this.onHeaderClick, {
				column
			}, e);
		}
		handleFooterContextMenu(e) {
			let footer = e.target.closest(".slick-footerrow-column"),
				column = footer && Utils.storage.get(footer, "column");
			this.trigger(this.onFooterContextMenu, {
				column
			}, e);
		}
		handleFooterClick(e) {
			let footer = e.target.closest(".slick-footerrow-column"),
				column = footer && Utils.storage.get(footer, "column");
			this.trigger(this.onFooterClick, {
				column
			}, e);
		}
		handleCellMouseOver(e) {
			this.trigger(this.onMouseEnter, {}, e);
		}
		handleCellMouseOut(e) {
			this.trigger(this.onMouseLeave, {}, e);
		}
		cellExists(row, cell) {
			return !(row < 0 || row >= this.getDataLength() || cell < 0 || cell >= this.columns.length);
		}
		/**
		 * Returns a hash containing row and cell indexes. Coordinates are relative to the top left corner of the grid beginning with the first row (not including the column headers).
		 * @param x An x coordinate.
		 * @param y A y coordinate.
		 */
		getCellFromPoint(x, y) {
			let row = this.getRowFromPosition(y),
				cell = 0,
				w = 0;
			for (let i = 0; i < this.columns.length && w < x; i++)
				!this.columns[i] || this.columns[i].hidden || (w += this.columns[i].width, cell++);
			return cell < 0 && (cell = 0), {
				row,
				cell: cell - 1
			};
		}
		getCellFromNode(cellNode) {
			let cls = /l\d+/.exec(cellNode.className);
			if (!cls)
				throw new Error(`SlickGrid getCellFromNode: cannot get cell - ${cellNode.className}`);
			return parseInt(cls[0].substr(1, cls[0].length - 1), 10);
		}
		getRowFromNode(rowNode) {
			var _a;
			for (let row in this.rowsCache)
				if (this.rowsCache) {
					for (let i in this.rowsCache[row].rowNode)
						if (((_a = this.rowsCache[row].rowNode) == null ? void 0 : _a[+i]) === rowNode)
							return row ? parseInt(row, 10) : 0;
				}
			return null;
		}
		/**
		 * Get frozen (pinned) row offset
		 * @param {Number} row - grid row number
		 */
		getFrozenRowOffset(row) {
			let offset = 0;
			return this.hasFrozenRows ? this._options.frozenBottom ? row >= this.actualFrozenRow ? this.h < this.viewportTopH ? offset = this.actualFrozenRow * this._options.rowHeight : offset = this.h : offset = 0 : row >= this.actualFrozenRow ? offset = this.frozenRowsHeight : offset = 0 : offset = 0, offset;
		}
		/**
		 * Returns a hash containing row and cell indexes from a standard W3C event.
		 * @param {*} event A standard W3C event.
		 */
		getCellFromEvent(evt) {
			let e = evt instanceof SlickEventData ? evt.getNativeEvent() : evt,
				targetEvent = e.touches ? e.touches[0] : e,
				cellNode = e.target.closest(".slick-cell");
			if (!cellNode)
				return null;
			let row = this.getRowFromNode(cellNode.parentNode);
			if (this.hasFrozenRows) {
				let rowOffset = 0,
					c = Utils.offset(Utils.parents(cellNode, ".grid-canvas")[0]);
				Utils.parents(cellNode, ".grid-canvas-bottom").length && (rowOffset = this._options.frozenBottom ? Utils.height(this._canvasTopL) : this.frozenRowsHeight), row = this.getCellFromPoint(targetEvent.clientX - c.left, targetEvent.clientY - c.top + rowOffset + document.documentElement.scrollTop).row;
			}
			let cell = this.getCellFromNode(cellNode);
			return !Utils.isDefined(row) || !Utils.isDefined(cell) ? null : {
				row,
				cell
			};
		}
		/**
		 * Returns an object representing information about a cell's position. All coordinates are absolute and take into consideration the visibility and scrolling position of all ancestors.
		 * @param {Number} row - A row number.
		 * @param {Number} cell - A column number.
		 */
		getCellNodeBox(row, cell) {
			var _a;
			if (!this.cellExists(row, cell))
				return null;
			let frozenRowOffset = this.getFrozenRowOffset(row),
				y1 = this.getRowTop(row) - frozenRowOffset,
				y2 = y1 + this._options.rowHeight - 1,
				x1 = 0;
			for (let i = 0; i < cell; i++)
				!this.columns[i] || this.columns[i].hidden || (x1 += this.columns[i].width || 0, this._options.frozenColumn === i && (x1 = 0));
			let x2 = x1 + (((_a = this.columns[cell]) == null ? void 0 : _a.width) || 0);
			return {
				top: y1,
				left: x1,
				bottom: y2,
				right: x2
			};
		}
		//////////////////////////////////////////////////////////////////////////////////////////////
		// Cell switching
		/**  Resets active cell. */
		resetActiveCell() {
			this.setActiveCellInternal(null, !1);
		}
		/** @alias `setFocus` */
		focus() {
			this.setFocus();
		}
		setFocus() {
			this.tabbingDirection === -1 ? this._focusSink.focus() : this._focusSink2.focus();
		}
		/** Scroll to a specific cell and make it into the view */
		scrollCellIntoView(row, cell, doPaging) {
			if (this.scrollRowIntoView(row, doPaging), cell <= this._options.frozenColumn)
				return;
			let colspan = this.getColspan(row, cell);
			this.internalScrollColumnIntoView(this.columnPosLeft[cell], this.columnPosRight[cell + (colspan > 1 ? colspan - 1 : 0)]);
		}
		internalScrollColumnIntoView(left, right) {
			var _a, _b;
			let scrollRight = this.scrollLeft + Utils.width(this._viewportScrollContainerX) - (this.viewportHasVScroll && (_b = (_a = this.scrollbarDimensions) == null ? void 0 : _a.width) != null ? _b : 0);
			left < this.scrollLeft ? (this._viewportScrollContainerX.scrollLeft = left, this.handleScroll(), this.render()) : right > scrollRight && (this._viewportScrollContainerX.scrollLeft = Math.min(left, right - this._viewportScrollContainerX.clientWidth), this.handleScroll(), this.render());
		}
		/**
		 * Scroll to a specific column and show it into the viewport
		 * @param {Number} cell - cell column number
		 */
		scrollColumnIntoView(cell) {
			this.internalScrollColumnIntoView(this.columnPosLeft[cell], this.columnPosRight[cell]);
		}
		setActiveCellInternal(newCell, opt_editMode, preClickModeOn, suppressActiveCellChangedEvent, e) {
			var _a, _b, _c, _d;
			if (Utils.isDefined(this.activeCellNode) && (this.makeActiveCellNormal(), this.activeCellNode.classList.remove("active"), (_b = (_a = this.rowsCache[this.activeRow]) == null ? void 0 : _a.rowNode) == null || _b.forEach((node) => node.classList.remove("active"))), this.activeCellNode = newCell, Utils.isDefined(this.activeCellNode)) {
				let activeCellOffset = Utils.offset(this.activeCellNode),
					rowOffset = Math.floor(Utils.offset(Utils.parents(this.activeCellNode, ".grid-canvas")[0]).top),
					isBottom = Utils.parents(this.activeCellNode, ".grid-canvas-bottom").length;
				this.hasFrozenRows && isBottom && (rowOffset -= this._options.frozenBottom ? Utils.height(this._canvasTopL) : this.frozenRowsHeight);
				let cell = this.getCellFromPoint(activeCellOffset.left, Math.ceil(activeCellOffset.top) - rowOffset);
				this.activeRow = cell.row, this.activeCell = this.activePosX = this.activeCell = this.activePosX = this.getCellFromNode(this.activeCellNode), !Utils.isDefined(opt_editMode) && this._options.autoEditNewRow && (opt_editMode = this.activeRow === this.getDataLength() || this._options.autoEdit), this._options.showCellSelection && (this.activeCellNode.classList.add("active"), (_d = (_c = this.rowsCache[this.activeRow]) == null ? void 0 : _c.rowNode) == null || _d.forEach((node) => node.classList.add("active"))), this._options.editable && opt_editMode && this.isCellPotentiallyEditable(this.activeRow, this.activeCell) && (clearTimeout(this.h_editorLoader), this._options.asyncEditorLoading ? this.h_editorLoader = setTimeout(() => {
					this.makeActiveCellEditable(void 0, preClickModeOn, e);
				}, this._options.asyncEditorLoadDelay) : this.makeActiveCellEditable(void 0, preClickModeOn, e));
			} else
				this.activeRow = this.activeCell = null;
			suppressActiveCellChangedEvent || this.trigger(this.onActiveCellChanged, this.getActiveCell());
		}
		clearTextSelection() {
			var _a;
			if ((_a = document.selection) != null && _a.empty)
				try {
					document.selection.empty();
				} catch (e) {}
			else if (window.getSelection) {
				let sel = window.getSelection();
				sel != null && sel.removeAllRanges && sel.removeAllRanges();
			}
		}
		isCellPotentiallyEditable(row, cell) {
			let dataLength = this.getDataLength();
			return !(row < dataLength && !this.getDataItem(row) || this.columns[cell].cannotTriggerInsert && row >= dataLength || !this.columns[cell] || this.columns[cell].hidden || !this.getEditor(row, cell));
		}
		/**
		 * Make the cell normal again (for example after destroying cell editor),
		 * we can also optionally refocus on the current active cell (again possibly after closing cell editor)
		 * @param {Boolean} [refocusActiveCell]
		 */
		makeActiveCellNormal(refocusActiveCell = !1) {
			var _a;
			if (this.currentEditor) {
				if (this.trigger(this.onBeforeCellEditorDestroy, {
						editor: this.currentEditor
					}), this.currentEditor.destroy(), this.currentEditor = null, this.activeCellNode) {
					let d = this.getDataItem(this.activeRow);
					if (this.activeCellNode.classList.remove("editable"), this.activeCellNode.classList.remove("invalid"), d) {
						let column = this.columns[this.activeCell],
							formatterResult = this.getFormatter(this.activeRow, column)(this.activeRow, this.activeCell, this.getDataItemValueForColumn(d, column), column, d, this);
						this.applyFormatResultToCellNode(formatterResult, this.activeCellNode), this.invalidatePostProcessingResults(this.activeRow);
					}
					refocusActiveCell && this.setFocus();
				}
				navigator.userAgent.toLowerCase().match(/msie/) && this.clearTextSelection(), (_a = this.getEditorLock()) == null || _a.deactivate(this.editController);
			}
		}
		editActiveCell(editor, preClickModeOn, e) {
			this.makeActiveCellEditable(editor, preClickModeOn, e);
		}
		makeActiveCellEditable(editor, preClickModeOn, e) {
			var _a, _b, _c, _d, _e, _f;
			if (!this.activeCellNode)
				return;
			if (!this._options.editable)
				throw new Error("SlickGrid makeActiveCellEditable : should never get called when this._options.editable is false");
			if (clearTimeout(this.h_editorLoader), !this.isCellPotentiallyEditable(this.activeRow, this.activeCell))
				return;
			let columnDef = this.columns[this.activeCell],
				item = this.getDataItem(this.activeRow);

			// Mitsukibo: moved addnewrow here so that it is guaranteed to exist when editing
			if (this.activeRow < this.getDataLength())
			{
			}
			else
			{
				let newItem = {};
				this.trigger(this.onAddNewRow, {
					item: newItem,
					columnDef
				});
			}

			if (this.trigger(this.onBeforeEditCell, {
					row: this.activeRow,
					cell: this.activeCell,
					item,
					column: columnDef,
					target: "grid"
				}).getReturnValue() === !1) {
				this.setFocus();
				return;
			}
			(_a = this.getEditorLock()) == null || _a.activate(this.editController), this.activeCellNode.classList.add("editable");
			let useEditor = editor || this.getEditor(this.activeRow, this.activeCell);
			!editor && !useEditor.suppressClearOnEdit && Utils.emptyElement(this.activeCellNode);
			let metadata = (_c = (_b = this.data) == null ? void 0 : _b.getItemMetadata) == null ? void 0 : _c.call(_b, this.activeRow);
			metadata = metadata == null ? void 0 : metadata.columns;
			let columnMetaData = metadata && (metadata[columnDef.id] || metadata[this.activeCell]);
			this.currentEditor = new useEditor({
				grid: this,
				gridPosition: this.absBox(this._container),
				position: this.absBox(this.activeCellNode),
				container: this.activeCellNode,
				column: columnDef,
				columnMetaData,
				item: item || {},
				event: e,
				commitChanges: this.commitEditAndSetFocus.bind(this),
				cancelChanges: this.cancelEditAndSetFocus.bind(this)
			}), item && this.currentEditor && (this.currentEditor.loadValue(item), preClickModeOn && ((_d = this.currentEditor) != null && _d.preClick) && this.currentEditor.preClick()), this.serializedEditorValue = (_e = this.currentEditor) == null ? void 0 : _e.serializeValue(), (_f = this.currentEditor) != null && _f.position && this.handleActiveCellPositionChange();
		}
		commitEditAndSetFocus() {
			var _a;
			// Mitsukibo changed navigateDown to navigateNext
			(_a = this.getEditorLock()) != null && 
			_a.commitCurrentEdit() && 
			(this.setFocus(), this._options.autoEdit && !this._options.autoCommitEdit && this.navigateNext());
		}
		cancelEditAndSetFocus() {
			var _a;
			(_a = this.getEditorLock()) != null && _a.cancelCurrentEdit() && this.setFocus();
		}
		absBox(elem) {
			let box = {
				top: elem.offsetTop,
				left: elem.offsetLeft,
				bottom: 0,
				right: 0,
				width: elem.offsetWidth,
				height: elem.offsetWidth,
				visible: !0
			};
			box.bottom = box.top + box.height, box.right = box.left + box.width;
			let offsetParent = elem.offsetParent;
			for (;
				(elem = elem.parentNode) !== document.body && !(!elem || !elem.parentNode);) {
				let styles = getComputedStyle(elem);
				box.visible && elem.scrollHeight !== elem.offsetHeight && styles.overflowY !== "visible" && (box.visible = box.bottom > elem.scrollTop && box.top < elem.scrollTop + elem.clientHeight), box.visible && elem.scrollWidth !== elem.offsetWidth && styles.overflowX !== "visible" && (box.visible = box.right > elem.scrollLeft && box.left < elem.scrollLeft + elem.clientWidth), box.left -= elem.scrollLeft, box.top -= elem.scrollTop, elem === offsetParent && (box.left += elem.offsetLeft, box.top += elem.offsetTop, offsetParent = elem.offsetParent), box.bottom = box.top + box.height, box.right = box.left + box.width;
			}
			return box;
		}
		/** Returns an object representing information about the active cell's position. All coordinates are absolute and take into consideration the visibility and scrolling position of all ancestors. */
		getActiveCellPosition() {
			return this.absBox(this.activeCellNode);
		}
		/** Get the Grid Position */
		getGridPosition() {
			return this.absBox(this._container);
		}
		handleActiveCellPositionChange() {
			if (this.activeCellNode && (this.trigger(this.onActiveCellPositionChanged, {}), this.currentEditor)) {
				let cellBox = this.getActiveCellPosition();
				this.currentEditor.show && this.currentEditor.hide && (cellBox.visible ? this.currentEditor.show() : this.currentEditor.hide()), this.currentEditor.position && this.currentEditor.position(cellBox);
			}
		}
		/** Returns the active cell editor. If there is no actively edited cell, null is returned.   */
		getCellEditor() {
			return this.currentEditor;
		}
		/**
		 * Returns an object representing the coordinates of the currently active cell:
		 * @example	`{ row: activeRow, cell: activeCell }`
		 */
		getActiveCell() {
			return this.activeCellNode ? {
				row: this.activeRow,
				cell: this.activeCell
			} : null;
		}
		/** Returns the DOM element containing the currently active cell. If no cell is active, null is returned. */
		getActiveCellNode() {
			return this.activeCellNode;
		}
		// This get/set methods are used for keeping text-selection. These don't consider IE because they don't loose text-selection.
		// Fix for firefox selection. See https://github.com/mleibman/SlickGrid/pull/746/files
		getTextSelection() {
			var _a;
			let textSelection = null;
			if (window.getSelection) {
				let selection = window.getSelection();
				((_a = selection == null ? void 0 : selection.rangeCount) != null ? _a : 0) > 0 && (textSelection = selection.getRangeAt(0));
			}
			return textSelection;
		}
		setTextSelection(selection) {
			if (window.getSelection && selection) {
				let target = window.getSelection();
				target && (target.removeAllRanges(), target.addRange(selection));
			}
		}
		/**
		 * Scroll to a specific row and make it into the view
		 * @param {Number} row - grid row number
		 * @param {Boolean} doPaging - scroll when pagination is enabled
		 */
		scrollRowIntoView(row, doPaging) {
			var _a, _b;
			if (!this.hasFrozenRows || !this._options.frozenBottom && row > this.actualFrozenRow - 1 || this._options.frozenBottom && row < this.actualFrozenRow - 1) {
				let viewportScrollH = Utils.height(this._viewportScrollContainerY),
					rowNumber = this.hasFrozenRows && !this._options.frozenBottom ? row - this._options.frozenRow : row,
					rowAtTop = rowNumber * this._options.rowHeight,
					rowAtBottom = (rowNumber + 1) * this._options.rowHeight - viewportScrollH + (this.viewportHasHScroll && (_b = (_a = this.scrollbarDimensions) == null ? void 0 : _a.height) != null ? _b : 0);
				(rowNumber + 1) * this._options.rowHeight > this.scrollTop + viewportScrollH + this.offset ? (this.scrollTo(doPaging ? rowAtTop : rowAtBottom), this.render()) : rowNumber * this._options.rowHeight < this.scrollTop + this.offset && (this.scrollTo(doPaging ? rowAtBottom : rowAtTop), this.render());
			}
		}
		/**
		 * Scroll to the top row and make it into the view
		 * @param {Number} row - grid row number
		 */
		scrollRowToTop(row) {
			this.scrollTo(row * this._options.rowHeight), this.render();
		}
		scrollPage(dir) {
			let deltaRows = dir * this.numVisibleRows,
				bottomOfTopmostFullyVisibleRow = this.scrollTop + this._options.rowHeight - 1;
			if (this.scrollTo((this.getRowFromPosition(bottomOfTopmostFullyVisibleRow) + deltaRows) * this._options.rowHeight), this.render(), this._options.enableCellNavigation && Utils.isDefined(this.activeRow)) {
				let row = this.activeRow + deltaRows,
					dataLengthIncludingAddNew = this.getDataLengthIncludingAddNew();
				row >= dataLengthIncludingAddNew && (row = dataLengthIncludingAddNew - 1), row < 0 && (row = 0);
				let cell = 0,
					prevCell = null,
					prevActivePosX = this.activePosX;
				for (; cell <= this.activePosX;)
					this.canCellBeActive(row, cell) && (prevCell = cell), cell += this.getColspan(row, cell);
				prevCell !== null ? (this.setActiveCellInternal(this.getCellNode(row, prevCell)), this.activePosX = prevActivePosX) : this.resetActiveCell();
			}
		}
		/** Navigate (scroll) by a page down */
		navigatePageDown() {
			// Mitsukibo only scroll if not in autoHeight mode
			if (this._options.autoHeight)
			{
				this.navigateBottom();
			}
			else
			{
				this.scrollPage(1);
			}
		}
		/** Navigate (scroll) by a page up */
		navigatePageUp() {
			// Mitsukibo only scroll if not in autoHeight mode
			if (this._options.autoHeight)
			{
				this.navigateTop();
			}
			else
			{
				this.scrollPage(-1);
			}
		}
		/** Navigate to the top of the grid */
		navigateTop() {
			this.navigateToRow(0);
		}
		/** Navigate to the bottom of the grid */
		navigateBottom() {
			this.navigateToRow(this.getDataLength() - 1);
		}
		navigateToRow(row) {
			let num_rows = this.getDataLength();
			if (!num_rows)
				return !0;
			if (row < 0 ? row = 0 : row >= num_rows && (row = num_rows - 1), this.scrollCellIntoView(row, 0, !0), this._options.enableCellNavigation && Utils.isDefined(this.activeRow)) {
				let cell = 0,
					prevCell = null,
					prevActivePosX = this.activePosX;
				for (; cell <= this.activePosX;)
					this.canCellBeActive(row, cell) && (prevCell = cell), cell += this.getColspan(row, cell);
				prevCell !== null ? (this.setActiveCellInternal(this.getCellNode(row, prevCell)), this.activePosX = prevActivePosX) : this.resetActiveCell();
			}
			return !0;
		}
		getColspan(row, cell) {
			var _a, _b;
			let metadata = (_b = (_a = this.data) == null ? void 0 : _a.getItemMetadata) == null ? void 0 : _b.call(_a, row);
			if (!metadata || !metadata.columns)
				return 1;
			let columnData = metadata.columns[this.columns[cell].id] || metadata.columns[cell],
				colspan = columnData == null ? void 0 : columnData.colspan;
			return colspan === "*" ? colspan = this.columns.length - cell : colspan = colspan || 1, colspan;
		}
		findFirstFocusableCell(row) {
			let cell = 0;
			for (; cell < this.columns.length;) {
				if (this.canCellBeActive(row, cell))
					return cell;
				cell += this.getColspan(row, cell);
			}
			return null;
		}
		findLastFocusableCell(row) {
			let cell = 0,
				lastFocusableCell = null;
			for (; cell < this.columns.length;)
				this.canCellBeActive(row, cell) && (lastFocusableCell = cell), cell += this.getColspan(row, cell);
			return lastFocusableCell;
		}
		// eslint-disable-next-line @typescript-eslint/no-unused-vars
		gotoRight(row, cell, _posX) {
			if (cell >= this.columns.length)
				return null;
			do
				cell += this.getColspan(row, cell);
			while (cell < this.columns.length && !this.canCellBeActive(row, cell));
			return cell < this.columns.length ? {
				row,
				cell,
				posX: cell
			} : null;
		}
		// eslint-disable-next-line @typescript-eslint/no-unused-vars
		gotoLeft(row, cell, _posX) {
			if (cell <= 0)
				return null;
			let firstFocusableCell = this.findFirstFocusableCell(row);
			if (firstFocusableCell === null || firstFocusableCell >= cell)
				return null;
			let prev = {
					row,
					cell: firstFocusableCell,
					posX: firstFocusableCell
				},
				pos;
			for (;;) {
				if (pos = this.gotoRight(prev.row, prev.cell, prev.posX), !pos)
					return null;
				if (pos.cell >= cell)
					return prev;
				prev = pos;
			}
		}
		gotoDown(row, cell, posX) {
			let prevCell, dataLengthIncludingAddNew = this.getDataLengthIncludingAddNew();
			for (;;) {
				if (++row >= dataLengthIncludingAddNew)
					return null;
				for (prevCell = cell = 0; cell <= posX;)
					prevCell = cell, cell += this.getColspan(row, cell);
				if (this.canCellBeActive(row, prevCell))
					return {
						row,
						cell: prevCell,
						posX
					};
			}
		}
		gotoUp(row, cell, posX) {
			let prevCell;
			for (;;) {
				if (--row < 0)
					return null;
				for (prevCell = cell = 0; cell <= posX;)
					prevCell = cell, cell += this.getColspan(row, cell);
				if (this.canCellBeActive(row, prevCell))
					return {
						row,
						cell: prevCell,
						posX
					};
			}
		}
		gotoNext(row, cell, posX) {
			if (!Utils.isDefined(row) && !Utils.isDefined(cell) && (row = cell = posX = 0, this.canCellBeActive(row, cell)))
				return {
					row,
					cell,
					posX: cell
				};
			let pos = this.gotoRight(row, cell, posX);
			if (pos)
				return pos;
			let firstFocusableCell = null,
				dataLengthIncludingAddNew = this.getDataLengthIncludingAddNew();
			for (row === dataLengthIncludingAddNew - 1 && row--; ++row < dataLengthIncludingAddNew;)
				if (firstFocusableCell = this.findFirstFocusableCell(row), firstFocusableCell !== null)
					return {
						row,
						cell: firstFocusableCell,
						posX: firstFocusableCell
					};
			return null;
		}
		gotoPrev(row, cell, posX) {
			if (!Utils.isDefined(row) && !Utils.isDefined(cell) && (row = this.getDataLengthIncludingAddNew() - 1, cell = posX = this.columns.length - 1, this.canCellBeActive(row, cell)))
				return {
					row,
					cell,
					posX: cell
				};
			let pos, lastSelectableCell;
			for (; !pos && (pos = this.gotoLeft(row, cell, posX), !pos);) {
				if (--row < 0)
					return null;
				cell = 0, lastSelectableCell = this.findLastFocusableCell(row), lastSelectableCell !== null && (pos = {
					row,
					cell: lastSelectableCell,
					posX: lastSelectableCell
				});
			}
			return pos;
		}
		// eslint-disable-next-line @typescript-eslint/no-unused-vars
		gotoRowStart(row, _cell, _posX) {
			let newCell = this.findFirstFocusableCell(row);
			return newCell === null ? null : {
				row,
				cell: newCell,
				posX: newCell
			};
		}
		// eslint-disable-next-line @typescript-eslint/no-unused-vars
		gotoRowEnd(row, _cell, _posX) {
			let newCell = this.findLastFocusableCell(row);
			return newCell === null ? null : {
				row,
				cell: newCell,
				posX: newCell
			};
		}
		/** Switches the active cell one cell right skipping unselectable cells. Unline navigateNext, navigateRight stops at the last cell of the row. Returns a boolean saying whether it was able to complete or not. */
		navigateRight() {
			return this.navigate("right");
		}
		/** Switches the active cell one cell left skipping unselectable cells. Unline navigatePrev, navigateLeft stops at the first cell of the row. Returns a boolean saying whether it was able to complete or not. */
		navigateLeft() {
			return this.navigate("left");
		}
		/** Switches the active cell one row down skipping unselectable cells. Returns a boolean saying whether it was able to complete or not. */
		navigateDown() {
			return this.navigate("down");
		}
		/** Switches the active cell one row up skipping unselectable cells. Returns a boolean saying whether it was able to complete or not. */
		navigateUp() {
			return this.navigate("up");
		}
		/** Tabs over active cell to the next selectable cell. Returns a boolean saying whether it was able to complete or not. */
		navigateNext() {
			return this.navigate("next");
		}
		/** Tabs over active cell to the previous selectable cell. Returns a boolean saying whether it was able to complete or not. */
		navigatePrev() {
			return this.navigate("prev");
		}
		/** Navigate to the start row in the grid */
		navigateRowStart() {
			return this.navigate("home");
		}
		/** Navigate to the end row in the grid */
		navigateRowEnd() {
			return this.navigate("end");
		}
		/**
		 * @param {string} dir Navigation direction.
		 * @return {boolean} Whether navigation resulted in a change of active cell.
		 */
		navigate(dir) {
			var _a;
			if (!this._options.enableCellNavigation || !this.activeCellNode && dir !== "prev" && dir !== "next")
				return !1;
			if (!((_a = this.getEditorLock()) != null && _a.commitCurrentEdit()))
				return !0;
			this.setFocus();
			let tabbingDirections = {
				up: -1,
				down: 1,
				left: -1,
				right: 1,
				prev: -1,
				next: 1,
				home: -1,
				end: 1
			};
			this.tabbingDirection = tabbingDirections[dir];
			let pos = {
				up: this.gotoUp,
				down: this.gotoDown,
				left: this.gotoLeft,
				right: this.gotoRight,
				prev: this.gotoPrev,
				next: this.gotoNext,
				home: this.gotoRowStart,
				end: this.gotoRowEnd
			} [dir].call(this, this.activeRow, this.activeCell, this.activePosX);
			if (pos) {
				if (this.hasFrozenRows && this._options.frozenBottom && pos.row === this.getDataLength())
					return;
				let isAddNewRow = pos.row === this.getDataLength();
				return (!this._options.frozenBottom && pos.row >= this.actualFrozenRow || this._options.frozenBottom && pos.row < this.actualFrozenRow) && this.scrollCellIntoView(pos.row, pos.cell, !isAddNewRow && this._options.emulatePagingWhenScrolling), this.setActiveCellInternal(this.getCellNode(pos.row, pos.cell)), this.activePosX = pos.posX, !0;
			} else
				return this.setActiveCellInternal(this.getCellNode(this.activeRow, this.activeCell)), !1;
		}
		/**
		 * Returns a DOM element containing a cell at a given row and cell.
		 * @param row A row index.
		 * @param cell A column index.
		 */
		getCellNode(row, cell) {
			if (this.rowsCache[row]) {
				this.ensureCellNodesInRowsCache(row);
				try {
					return this.rowsCache[row].cellNodesByColumnIdx.length > cell ? this.rowsCache[row].cellNodesByColumnIdx[cell] : null;
				} catch (e) {
					return this.rowsCache[row].cellNodesByColumnIdx[cell];
				}
			}
			return null;
		}
		/**
		 * Sets an active cell.
		 * @param {number} row - A row index.
		 * @param {number} cell - A column index.
		 * @param {boolean} [optionEditMode] Option Edit Mode is Auto-Edit?
		 * @param {boolean} [preClickModeOn] Pre-Click Mode is Enabled?
		 * @param {boolean} [suppressActiveCellChangedEvent] Are we suppressing Active Cell Changed Event (defaults to false)
		 */
		setActiveCell(row, cell, opt_editMode, preClickModeOn, suppressActiveCellChangedEvent) {
			this.initialized && (row > this.getDataLength() || row < 0 || cell >= this.columns.length || cell < 0 || this._options.enableCellNavigation && (this.scrollCellIntoView(row, cell, !1), this.setActiveCellInternal(this.getCellNode(row, cell), opt_editMode, preClickModeOn, suppressActiveCellChangedEvent)));
		}
		/**
		 * Sets an active cell.
		 * @param {number} row - A row index.
		 * @param {number} cell - A column index.
		 * @param {boolean} [suppressScrollIntoView] - optionally suppress the ScrollIntoView that happens by default (defaults to false)
		 */
		setActiveRow(row, cell, suppressScrollIntoView) {
			this.initialized && (row > this.getDataLength() || row < 0 || (cell != null ? cell : 0) >= this.columns.length || (cell != null ? cell : 0) < 0 || (this.activeRow = row, suppressScrollIntoView || this.scrollCellIntoView(row, cell || 0, !1)));
		}
		/**
		 * Returns true if you can click on a given cell and make it the active focus.
		 * @param {number} row A row index.
		 * @param {number} col A column index.
		 */
		canCellBeActive(row, cell) {
			var _a, _b, _c, _d;
			if (!this.options.enableCellNavigation || row >= this.getDataLengthIncludingAddNew() || row < 0 || cell >= this.columns.length || cell < 0 || !this.columns[cell] || this.columns[cell].hidden)
				return !1;
			let rowMetadata = (_b = (_a = this.data) == null ? void 0 : _a.getItemMetadata) == null ? void 0 : _b.call(_a, row);
			if ((rowMetadata == null ? void 0 : rowMetadata.focusable) !== void 0)
				return !!rowMetadata.focusable;
			let columnMetadata = rowMetadata == null ? void 0 : rowMetadata.columns;
			return ((_c = columnMetadata == null ? void 0 : columnMetadata[this.columns[cell].id]) == null ? void 0 : _c.focusable) !== void 0 ? !!columnMetadata[this.columns[cell].id].focusable : ((_d = columnMetadata == null ? void 0 : columnMetadata[cell]) == null ? void 0 : _d.focusable) !== void 0 ? !!columnMetadata[cell].focusable : !!this.columns[cell].focusable;
		}
		/**
		 * Returns true if selecting the row causes this particular cell to have the selectedCellCssClass applied to it. A cell can be selected if it exists and if it isn't on an empty / "Add New" row and if it is not marked as "unselectable" in the column definition.
		 * @param {number} row A row index.
		 * @param {number} col A column index.
		 */
		canCellBeSelected(row, cell) {
			var _a, _b;
			if (row >= this.getDataLength() || row < 0 || cell >= this.columns.length || cell < 0 || !this.columns[cell] || this.columns[cell].hidden)
				return !1;
			let rowMetadata = (_b = (_a = this.data) == null ? void 0 : _a.getItemMetadata) == null ? void 0 : _b.call(_a, row);
			if ((rowMetadata == null ? void 0 : rowMetadata.selectable) !== void 0)
				return !!rowMetadata.selectable;
			let columnMetadata = (rowMetadata == null ? void 0 : rowMetadata.columns) && (rowMetadata.columns[this.columns[cell].id] || rowMetadata.columns[cell]);
			return (columnMetadata == null ? void 0 : columnMetadata.selectable) !== void 0 ? !!columnMetadata.selectable : !!this.columns[cell].selectable;
		}
		/**
		 * Accepts a row integer and a cell integer, scrolling the view to the row where row is its row index, and cell is its cell index. Optionally accepts a forceEdit boolean which, if true, will attempt to initiate the edit dialogue for the field in the specified cell.
		 * Unlike setActiveCell, this scrolls the row into the viewport and sets the keyboard focus.
		 * @param {Number} row A row index.
		 * @param {Number} cell A column index.
		 * @param {Boolean} [forceEdit] If true, will attempt to initiate the edit dialogue for the field in the specified cell.
		 */
		gotoCell(row, cell, forceEdit, e) {
			var _a;
			if (!this.initialized || !this.canCellBeActive(row, cell) || !((_a = this.getEditorLock()) != null && _a.commitCurrentEdit()))
				return;
			this.scrollCellIntoView(row, cell, !1);
			let newCell = this.getCellNode(row, cell),
				column = this.columns[cell],
				suppressActiveCellChangedEvent = !!(this._options.editable && (column != null && column.editor) && this._options.suppressActiveCellChangeOnEdit);
			this.setActiveCellInternal(newCell, forceEdit || row === this.getDataLength() || this._options.autoEdit, null, suppressActiveCellChangedEvent, e), this.currentEditor || this.setFocus();
		}
		//////////////////////////////////////////////////////////////////////////////////////////////
		// IEditor implementation for the editor lock
		commitCurrentEdit() {
			var _a;
			let self = this,
				item = self.getDataItem(self.activeRow),
				column = self.columns[self.activeCell];
			if (self.currentEditor) {
				if (self.currentEditor.isValueChanged()) {
					let validationResults = self.currentEditor.validate();
					if (validationResults.valid) {
						let row = self.activeRow,
							cell = self.activeCell,
							editor = self.currentEditor,
							serializedValue = self.currentEditor.serializeValue(),
							prevSerializedValue = self.serializedEditorValue;
						if (self.activeRow < self.getDataLength()) {
							let editCommand = {
								row,
								cell,
								editor,
								serializedValue,
								prevSerializedValue,
								execute: () => {
									editor.applyValue(item, serializedValue), self.updateRow(row), self.trigger(self.onCellChange, {
										command: "execute",
										row,
										cell,
										item,
										column
									});
								},
								undo: () => {
									editor.applyValue(item, prevSerializedValue), self.updateRow(row), self.trigger(self.onCellChange, {
										command: "undo",
										row,
										cell,
										item,
										column
									});
								}
							};
							self.options.editCommandHandler ? (self.makeActiveCellNormal(!0), self.options.editCommandHandler(item, column, editCommand)) : (editCommand.execute(), self.makeActiveCellNormal(!0));
						} else {
							// Mitsukibo: moved addnewrow above so that it is guaranteed to exist when editing
							// let newItem = {};
							// self.currentEditor.applyValue(newItem, self.currentEditor.serializeValue()), self.makeActiveCellNormal(!0), self.trigger(self.onAddNewRow, {
								// item: newItem,
								// column
							// });
						}
						return !((_a = self.getEditorLock()) != null && _a.isActive());
					} else
						return self.activeCellNode && (self.activeCellNode.classList.remove("invalid"), Utils.width(self.activeCellNode), self.activeCellNode.classList.add("invalid")), self.trigger(self.onValidationError, {
							editor: self.currentEditor,
							cellNode: self.activeCellNode,
							validationResults,
							row: self.activeRow,
							cell: self.activeCell,
							column
						}), self.currentEditor.focus(), !1;
				}
				self.makeActiveCellNormal(!0);
			}
			return !0;
		}
		cancelCurrentEdit() {
			return this.makeActiveCellNormal(), !0;
		}
		rowsToRanges(rows) {
			let ranges = [],
				lastCell = this.columns.length - 1;
			for (let i = 0; i < rows.length; i++)
				ranges.push(new SlickRange(rows[i], 0, rows[i], lastCell));
			return ranges;
		}
		/** Returns an array of row indices corresponding to the currently selected rows. */
		getSelectedRows() {
			if (!this.selectionModel)
				throw new Error("SlickGrid Selection model is not set");
			return this.selectedRows.slice(0);
		}
		/**
		 * Accepts an array of row indices and applies the current selectedCellCssClass to the cells in the row, respecting whether cells have been flagged as selectable.
		 * @param {Array<number>} rowsArray - an array of row numbers.
		 * @param {String} [caller] - an optional string to identify who called the method
		 */
		setSelectedRows(rows, caller) {
			var _a;
			if (!this.selectionModel)
				throw new Error("SlickGrid Selection model is not set");
			this && this.getEditorLock && !((_a = this.getEditorLock()) != null && _a.isActive()) && this.selectionModel.setSelectedRanges(this.rowsToRanges(rows), caller || "SlickGrid.setSelectedRows");
		}
		/** html sanitizer to avoid scripting attack */
		sanitizeHtmlString(dirtyHtml, suppressLogging) {
			if (!this._options.sanitizer || typeof dirtyHtml != "string")
				return dirtyHtml;
			let cleanHtml = this._options.sanitizer(dirtyHtml);
			return !suppressLogging && this._options.logSanitizedHtml && this.logMessageCount <= this.logMessageMaxCount && cleanHtml !== dirtyHtml && (console.log(`sanitizer altered html: ${dirtyHtml} --> ${cleanHtml}`), this.logMessageCount === this.logMessageMaxCount && console.log(`sanitizer: silencing messages after first ${this.logMessageMaxCount}`), this.logMessageCount++), cleanHtml;
		}
	};
	window.Slick && Utils.extend(Slick, {
		Grid: SlickGrid
	});
})();
/**
 * @license
 * (c) 2009-present Michael Leibman
 * michael{dot}leibman{at}gmail{dot}com
 * http://github.com/mleibman/slickgrid
 *
 * Distributed under MIT license.
 * All rights reserved.
 *
 * SlickGrid v5.5.6
 *
 * NOTES:
 *     Cell/row DOM manipulations are done directly bypassing JS DOM manipulation methods.
 *     This increases the speed dramatically, but can only be done safely because there are no event handlers
 *     or data associated with any cell/row DOM nodes.  Cell editors must make sure they implement .destroy()
 *     and do proper cleanup.
 */
//# sourceMappingURL=slick.grid.js.map"use strict";
(() => {
  var __defProp = Object.defineProperty;
  var __defNormalProp = (obj, key, value) => key in obj ? __defProp(obj, key, { enumerable: !0, configurable: !0, writable: !0, value }) : obj[key] = value;
  var __publicField = (obj, key, value) => (__defNormalProp(obj, typeof key != "symbol" ? key + "" : key, value), value);

  var m_strClassName;
  var m_strFieldName;
  var m_objGridEventHandler;
  var m_objRendererEventHandler;
  var m_objActiveCell;
  var m_objActiveColumn;
 
  // src/slick.editors.ts
  var keyCode = Slick.keyCode, Utils = Slick.Utils, TextEditor = class {
    constructor(args) {
      this.args = args;
      __publicField(this, "input");
      __publicField(this, "defaultValue");
      __publicField(this, "navOnLR");
      this.init();
    }
    init() {
      this.navOnLR = this.args.grid.getOptions().editorCellNavOnLRKeys;

	  // Mitsukibo: added support for predictive text
	  if (this.args.column.fieldname)
	  {
		  // type="text"
		  // style="width:98%;"
		  // class="xform-control d_list d_predictivetext SOMEFORMS SOMEFORMStds ui-autocomplete-input" 
		  // targetdatasourceid="datasource-sender" 
		  // name="SOMEFORMS" 
		  // required="" 
		  // value="" 
		  // autocomplete="off">
		  
		  m_strFieldName = this.args.column.fieldname;
		  m_strClassName = "editor-text xform-control d_list d_predictivetext " + m_strFieldName + " " + m_strFieldName + "tds ui-autocomplete-input";
		  
		  this.input = Utils.createDomElement("input", 
		  { 
			type: "text", 
			className: m_strClassName, 
			style: "width:98%;", 
			targetdatasourceid: "datasource-sender",
			name: m_strFieldName 
		  }, this.args.container);
	  }
	  else
	  {
		  this.input = Utils.createDomElement("input", { type: "text", className: "editor-text" }, this.args.container);
	  }
	  
	  this.input.addEventListener("keydown", this.navOnLR ? handleKeydownLRNav : handleKeydownLRNoNav), 
	  this.input.focus(), 
	  this.input.select(), 
	  this.args.compositeEditorOptions && this.input.addEventListener("change", this.onChange.bind(this));
	  
	  if (this.args.column.fieldname)
	  {
		  this.args.column.finder.bind(finderBinder_onEvent, this.args.column);
	  }
	  
	  m_objGridEventHandler = this.args.column.grideventhandler;// Mitsukibo
	  m_objRendererEventHandler = this.args.column.renderereventhandler;// Mitsukibo
	  m_objActiveColumn = this.args.column;// Mitsukibo
    }
    onChange() {
      var _a, _b;
      let activeCell = this.args.grid.getActiveCell();
	  m_objActiveCell = activeCell;// Mitsukibo
      this.validate().valid && this.applyValue(this.args.item, this.serializeValue()), this.applyValue(this.args.compositeEditorOptions.formValues, this.serializeValue()), this.args.grid.onCompositeEditorChange.notify({
        row: (_a = activeCell == null ? void 0 : activeCell.row) != null ? _a : 0,
        cell: (_b = activeCell == null ? void 0 : activeCell.cell) != null ? _b : 0,
        item: this.args.item,
        column: this.args.column,
        formValues: this.args.compositeEditorOptions.formValues,
        grid: this.args.grid,
        editors: this.args.compositeEditorOptions.editors
      });
    }
    destroy() {
      this.input.removeEventListener("keydown", this.navOnLR ? handleKeydownLRNav : handleKeydownLRNoNav), this.input.removeEventListener("change", this.onChange.bind(this)), this.input.remove();
    }
    focus() {

	  // Mitsukibo: added grid handler callbackf or predictive events
	  if ($.isFunction(m_objGridEventHandler))
	  {
		  m_objActiveCell = this.args.grid.getActiveCell();
		  m_objGridEventHandler(m_objActiveCell, this.args.column, m_strFieldName, this.input.value, 'focus');
	  }

      this.input.focus();
    }
    getValue() {

	  // Mitsukibo: added grid handler callbackf or predictive events
	  if ($.isFunction(m_objGridEventHandler))
	  {
		  m_objActiveCell = this.args.grid.getActiveCell();
		  m_objGridEventHandler(m_objActiveCell, this.args.column, m_strFieldName, this.input.value, 'getValue');
	  }

      return this.input.value;
    }
    setValue(val) {

	  // Mitsukibo: added grid handler callbackf or predictive events
	  if ($.isFunction(m_objGridEventHandler))
	  {
		  m_objActiveCell = this.args.grid.getActiveCell();
		  m_objGridEventHandler(m_objActiveCell, this.args.column, m_strFieldName, this.input.value, 'setValue');
	  }

      this.input.value = val;
    }
    loadValue(item) {
      var _a, _b;
      this.defaultValue = item[this.args.column.field] || "", this.input.value = String((_a = this.defaultValue) != null ? _a : ""), this.input.defaultValue = String((_b = this.defaultValue) != null ? _b : ""), this.input.select();
    }
    serializeValue() {

	  // Mitsukibo: added grid handler callbackf or predictive events
	  if ($.isFunction(m_objGridEventHandler))
	  {
		  m_objActiveCell = this.args.grid.getActiveCell();
		  m_objGridEventHandler(m_objActiveCell, this.args.column, m_strFieldName, this.input.value, 'serializeValue');
	  }

      return this.input.value;
    }
    applyValue(item, state) {

	  // Mitsukibo: added grid handler callbackf or predictive events
	  if ($.isFunction(m_objGridEventHandler))
	  {
		  m_objActiveCell = this.args.grid.getActiveCell();
		  m_objGridEventHandler(m_objActiveCell, this.args.column, m_strFieldName, this.input.value, 'applyValue');
	  }

      item[this.args.column.field] = state;
    }
    isValueChanged() {
      return !(this.input.value === "" && !Utils.isDefined(this.defaultValue)) && this.input.value !== this.defaultValue;
    }
    validate() {
      if (this.args.column.validator) {
        let validationResults = this.args.column.validator(this.input.value, this.args);
        if (!validationResults.valid)
          return validationResults;
      }
      return {
        valid: !0,
        msg: null
      };
    }
  }, IntegerEditor = class {
    constructor(args) {
      this.args = args;
      __publicField(this, "input");
      __publicField(this, "defaultValue");
      __publicField(this, "navOnLR");
      this.init();
    }
    init() {
      this.navOnLR = this.args.grid.getOptions().editorCellNavOnLRKeys, 
	  this.input = Utils.createDomElement("input", { type: "text", className: "editor-text" }, this.args.container), 
	  this.input.addEventListener("keydown", this.navOnLR ? handleKeydownLRNav : handleKeydownLRNoNav), 
	  this.input.focus(), 
	  this.input.select(), 
	  this.args.compositeEditorOptions && this.input.addEventListener("change", this.onChange.bind(this));
    }
    onChange() {
      var _a, _b;
      let activeCell = this.args.grid.getActiveCell();
	  m_objActiveCell = activeCell;// Mitsukibo
      this.validate().valid && this.applyValue(this.args.item, this.serializeValue()), this.applyValue(this.args.compositeEditorOptions.formValues, this.serializeValue()), this.args.grid.onCompositeEditorChange.notify({
        row: (_a = activeCell == null ? void 0 : activeCell.row) != null ? _a : 0,
        cell: (_b = activeCell == null ? void 0 : activeCell.cell) != null ? _b : 0,
        item: this.args.item,
        column: this.args.column,
        formValues: this.args.compositeEditorOptions.formValues,
        grid: this.args.grid,
        editors: this.args.compositeEditorOptions.editors
      });
    }
    destroy() {
      this.input.removeEventListener("keydown", this.navOnLR ? handleKeydownLRNav : handleKeydownLRNoNav), this.input.removeEventListener("change", this.onChange.bind(this)), this.input.remove();
    }
    focus() {
      this.input.focus();
    }
    loadValue(item) {
      var _a, _b;
      this.defaultValue = item[this.args.column.field], this.input.value = String((_a = this.defaultValue) != null ? _a : ""), this.input.defaultValue = String((_b = this.defaultValue) != null ? _b : ""), this.input.select();
    }
    serializeValue() {
      return parseInt(this.input.value, 10) || 0;
    }
    applyValue(item, state) {
      item[this.args.column.field] = state;
    }
    isValueChanged() {
      return !(this.input.value === "" && !Utils.isDefined(this.defaultValue)) && this.input.value !== this.defaultValue;
    }
    validate() {
      if (isNaN(this.input.value))
        return {
          valid: !1,
          msg: "Please enter a valid integer"
        };
      if (this.args.column.validator) {
        let validationResults = this.args.column.validator(this.input.value, this.args);
        if (!validationResults.valid)
          return validationResults;
      }
      return {
        valid: !0,
        msg: null
      };
    }
  }, _FloatEditor = class _FloatEditor {
    constructor(args) {
      this.args = args;
      __publicField(this, "input");
      __publicField(this, "defaultValue");
      __publicField(this, "navOnLR");
      this.init();
    }
    init() {
      this.navOnLR = this.args.grid.getOptions().editorCellNavOnLRKeys, 
	  this.input = Utils.createDomElement("input", { type: "text", className: "editor-text" }, 
	  this.args.container), 
	  this.input.addEventListener("keydown", this.navOnLR ? handleKeydownLRNav : handleKeydownLRNoNav), 
	  this.input.focus(), 
	  this.input.select(), 
	  this.args.compositeEditorOptions && this.input.addEventListener("change", this.onChange.bind(this));
    }
    onChange() {
      var _a, _b;
      let activeCell = this.args.grid.getActiveCell();
	  m_objActiveCell = activeCell;// Mitsukibo
      this.validate().valid && this.applyValue(this.args.item, this.serializeValue()), this.applyValue(this.args.compositeEditorOptions.formValues, this.serializeValue()), this.args.grid.onCompositeEditorChange.notify({
        row: (_a = activeCell == null ? void 0 : activeCell.row) != null ? _a : 0,
        cell: (_b = activeCell == null ? void 0 : activeCell.cell) != null ? _b : 0,
        item: this.args.item,
        column: this.args.column,
        formValues: this.args.compositeEditorOptions.formValues,
        grid: this.args.grid,
        editors: this.args.compositeEditorOptions.editors
      });
    }
    destroy() {
      this.input.removeEventListener("keydown", this.navOnLR ? handleKeydownLRNav : handleKeydownLRNoNav), this.input.removeEventListener("change", this.onChange.bind(this)), this.input.remove();
    }
    focus() {
      this.input.focus();
    }
    getDecimalPlaces() {
      let rtn = this.args.column.editorFixedDecimalPlaces;
      return Utils.isDefined(rtn) || (rtn = _FloatEditor.DefaultDecimalPlaces), !rtn && rtn !== 0 ? null : rtn;
    }
    loadValue(item) {
      var _a, _b, _c;
      this.defaultValue = item[this.args.column.field];
      let decPlaces = this.getDecimalPlaces();
      decPlaces !== null && (this.defaultValue || this.defaultValue === 0) && ((_a = this.defaultValue) != null && _a.toFixed) && (this.defaultValue = this.defaultValue.toFixed(decPlaces)), this.input.value = String((_b = this.defaultValue) != null ? _b : ""), this.input.defaultValue = String((_c = this.defaultValue) != null ? _c : ""), this.input.select();
    }
    serializeValue() {
      let rtn = parseFloat(this.input.value);
      _FloatEditor.AllowEmptyValue ? !rtn && rtn !== 0 && (rtn = void 0) : rtn = rtn || 0;
      let decPlaces = this.getDecimalPlaces();
      return decPlaces !== null && (rtn || rtn === 0) && rtn.toFixed && (rtn = parseFloat(rtn.toFixed(decPlaces))), rtn;
    }
    applyValue(item, state) {
      item[this.args.column.field] = state;
    }
    isValueChanged() {
      return !(this.input.value === "" && !Utils.isDefined(this.defaultValue)) && this.input.value !== this.defaultValue;
    }
    validate() {
      if (isNaN(this.input.value))
        return {
          valid: !1,
          msg: "Please enter a valid number"
        };
      if (this.args.column.validator) {
        let validationResults = this.args.column.validator(this.input.value, this.args);
        if (!validationResults.valid)
          return validationResults;
      }
      return {
        valid: !0,
        msg: null
      };
    }
  };
  
  /** Default number of decimal places to use with FloatEditor */
  __publicField(_FloatEditor, "DefaultDecimalPlaces"), /** Should we allow empty value when using FloatEditor */
  __publicField(_FloatEditor, "AllowEmptyValue", !1);
  var FloatEditor = _FloatEditor, FlatpickrEditor = class {
    constructor(args) {
      this.args = args;
      __publicField(this, "input");
      __publicField(this, "defaultValue");
      __publicField(this, "flatpickrInstance");
      if (this.init(), typeof flatpickr == "undefined")
        throw new Error("Flatpickr not loaded but required in SlickGrid.Editors, refer to Flatpickr documentation: https://flatpickr.js.org/getting-started/");
    }
    init() {
      this.input = Utils.createDomElement("input", { type: "text", className: "editor-text" }, this.args.container), this.input.focus(), this.input.select(), this.flatpickrInstance = flatpickr(this.input, {
        closeOnSelect: !0,
        allowInput: !0,
        altInput: !0,
        altFormat: "m/d/Y",
        dateFormat: "m/d/Y",
        onChange: () => {
          var _a, _b;
          if (this.args.compositeEditorOptions) {
            let activeCell = this.args.grid.getActiveCell();
			m_objActiveCell = activeCell;// Mitsukibo
            this.validate().valid && this.applyValue(this.args.item, this.serializeValue()), this.applyValue(this.args.compositeEditorOptions.formValues, this.serializeValue()), this.args.grid.onCompositeEditorChange.notify({
              row: (_a = activeCell == null ? void 0 : activeCell.row) != null ? _a : 0,
              cell: (_b = activeCell == null ? void 0 : activeCell.cell) != null ? _b : 0,
              item: this.args.item,
              column: this.args.column,
              formValues: this.args.compositeEditorOptions.formValues,
              grid: this.args.grid,
              editors: this.args.compositeEditorOptions.editors
            });
          }
        }
      }), this.args.compositeEditorOptions || setTimeout(() => {
        this.show(), this.focus();
      }, 50), Utils.width(this.input, Utils.width(this.input) - (this.args.compositeEditorOptions ? 28 : 18));
    }
    destroy() {
      this.hide(), this.flatpickrInstance && this.flatpickrInstance.destroy(), this.input.remove();
    }
    show() {
      !this.args.compositeEditorOptions && this.flatpickrInstance && this.flatpickrInstance.open();
    }
    hide() {
      !this.args.compositeEditorOptions && this.flatpickrInstance && this.flatpickrInstance.close();
    }
    focus() {
      this.input.focus();
    }
    loadValue(item) {
      var _a, _b;
      this.defaultValue = item[this.args.column.field], this.input.value = String((_a = this.defaultValue) != null ? _a : ""), this.input.defaultValue = String((_b = this.defaultValue) != null ? _b : ""), this.input.select(), this.flatpickrInstance && this.flatpickrInstance.setDate(this.defaultValue);
    }
    serializeValue() {
      return this.input.value;
    }
    applyValue(item, state) {
      item[this.args.column.field] = state;
    }
    isValueChanged() {
      return !(this.input.value === "" && !Utils.isDefined(this.defaultValue)) && this.input.value !== this.defaultValue;
    }
    validate() {
      if (this.args.column.validator) {
        let validationResults = this.args.column.validator(this.input.value, this.args);
        if (!validationResults.valid)
          return validationResults;
      }
      return {
        valid: !0,
        msg: null
      };
    }
  }, YesNoSelectEditor = class {
    constructor(args) {
      this.args = args;
      __publicField(this, "select");
      __publicField(this, "defaultValue");
      this.init();
    }
    init() {
      this.select = Utils.createDomElement("select", { tabIndex: 0, className: "editor-yesno" }, this.args.container), Utils.createDomElement("option", { value: "yes", textContent: "Yes" }, this.select), Utils.createDomElement("option", { value: "no", textContent: "No" }, this.select), this.select.focus(), this.args.compositeEditorOptions && this.select.addEventListener("change", this.onChange.bind(this));
    }
    onChange() {
      var _a, _b;
      let activeCell = this.args.grid.getActiveCell();
	  m_objActiveCell = activeCell;// Mitsukibo
      this.validate().valid && this.applyValue(this.args.item, this.serializeValue()), this.applyValue(this.args.compositeEditorOptions.formValues, this.serializeValue()), this.args.grid.onCompositeEditorChange.notify({
        row: (_a = activeCell == null ? void 0 : activeCell.row) != null ? _a : 0,
        cell: (_b = activeCell == null ? void 0 : activeCell.cell) != null ? _b : 0,
        item: this.args.item,
        column: this.args.column,
        formValues: this.args.compositeEditorOptions.formValues,
        grid: this.args.grid,
        editors: this.args.compositeEditorOptions.editors
      });
    }
    destroy() {
      this.select.removeEventListener("change", this.onChange.bind(this)), this.select.remove();
    }
    focus() {
      this.select.focus();
    }
    loadValue(item) {
      this.select.value = (this.defaultValue = item[this.args.column.field]) ? "yes" : "no";
    }
    serializeValue() {
      return this.select.value === "yes";
    }
	
	// Mitsukibo: bug fix as suggested on the net
    applyValue(item, state) {
		//item[this.args.column.field] = state;  <-- remove that line, add lines below
		if (state == 'checked') {
			item[this.args.column.field] = true;
		} else {
			item[this.args.column.field] = false;  
		}
    }
    isValueChanged() {
      return this.select.value !== this.defaultValue;
    }
    validate() {
      return {
        valid: !0,
        msg: null
      };
    }
  }, DeleteButtonEditor = class {		// Mitsukibo: new delete button editor
    constructor(args) {
      this.args = args;
      __publicField(this, "select");
	  __publicField(this, "defaultValue");
      this.init();
    }
    init() {
	  // this actually handles the on-click too, so no need to bind another 
      this.select = Utils.createDomElement("div", { tabIndex: 0, className: "btn btn-primary gb-button gs-red-background-colour btn-danger glyphicon glyphicon-trash", style:{ align:"center", width:"100%", height:"100%", margin:"0 0 0 0", padding:"0 0 0 0" }}, this.args.container),
	  this.select.focus(), 
	  this.select.addEventListener("click", this.onClick.bind(this));

	  // setInterval(function()
	  // {
		  // this.args.grid.trigger(this.onClick, {});
	  // }, 100);
    }
    onClick(e) {
      let activeCell = this.args.grid.getActiveCell();
	  m_objActiveCell = activeCell;// Mitsukibo
	  
	  //alert('Trigger:' + activeCell.row);
	  var _a, _b;
	  var intRow = (_a = activeCell == null ? void 0 : activeCell.row);
	  var intCell = (_b = activeCell == null ? void 0 : activeCell.cell);
	  this.args.grid.trigger(this.args.grid.onDeleteRow, {
		row: intRow != null ? _a : 0,
		cell: intCell != null ? _b : 0
	  });

	  e.preventDefault();
	}
    destroy() {
      this.select.removeEventListener("click", this.onClick.bind(this)), this.select.remove();
    }
	// blur()
	// {
		// alert('blur');
      // this.cancel();
	  // this.select.blur();
	// }
    focus() {
      this.select.focus();
    }
    loadValue(item) {
    }
    serializeValue() {
      return true;
    }
	
	// Mitsukibo: bug fix as suggested on the net
    applyValue(item, state) {
		item[this.args.column.field] = state;
    }
    isValueChanged() {
      return this.defaultValue;
    }
    validate() {
      return {
        valid: !0,
        msg: null
      };
    }
  }, CheckboxEditor = class {
    constructor(args) {
      this.args = args;
      __publicField(this, "input");
      __publicField(this, "defaultValue");
      this.init();
    }
    init() {
      this.input = Utils.createDomElement("input", { className: "editor-checkbox", type: "checkbox", value: "true" }, this.args.container), this.input.focus(), this.args.compositeEditorOptions && this.input.addEventListener("change", this.onChange.bind(this));
    }
    onChange() {
      var _a, _b;
      let activeCell = this.args.grid.getActiveCell();
	  m_objActiveCell = activeCell;// Mitsukibo
      this.validate().valid && this.applyValue(this.args.item, this.serializeValue()), this.applyValue(this.args.compositeEditorOptions.formValues, this.serializeValue()), this.args.grid.onCompositeEditorChange.notify({
        row: (_a = activeCell == null ? void 0 : activeCell.row) != null ? _a : 0,
        cell: (_b = activeCell == null ? void 0 : activeCell.cell) != null ? _b : 0,
        item: this.args.item,
        column: this.args.column,
        formValues: this.args.compositeEditorOptions.formValues,
        grid: this.args.grid,
        editors: this.args.compositeEditorOptions.editors
      });
    }
    destroy() {
      this.input.removeEventListener("change", this.onChange.bind(this)), this.input.remove();
    }
    focus() {
      this.input.focus();
    }
    loadValue(item) {
      this.defaultValue = !!item[this.args.column.field], this.defaultValue ? this.input.checked = !0 : this.input.checked = !1;
    }
    serializeValue() {
      return this.input.checked;
    }
    applyValue(item, state) {
      item[this.args.column.field] = state;
    }
    isValueChanged() {
      return this.serializeValue() !== this.defaultValue;
    }
    validate() {
      return {
        valid: !0,
        msg: null
      };
    }
  }, PercentCompleteEditor = class {
    constructor(args) {
      this.args = args;
      __publicField(this, "input");
      __publicField(this, "defaultValue");
      __publicField(this, "picker");
      __publicField(this, "slider");
      this.init();
    }
    sliderInputHandler(e) {
      this.input.value = e.target.value;
    }
    sliderChangeHandler() {
      var _a, _b;
      if (this.args.compositeEditorOptions) {
        let activeCell = this.args.grid.getActiveCell();
		m_objActiveCell = activeCell;// Mitsukibo
        this.validate().valid && this.applyValue(this.args.item, this.serializeValue()), this.applyValue(this.args.compositeEditorOptions.formValues, this.serializeValue()), this.args.grid.onCompositeEditorChange.notify({
          row: (_a = activeCell == null ? void 0 : activeCell.row) != null ? _a : 0,
          cell: (_b = activeCell == null ? void 0 : activeCell.cell) != null ? _b : 0,
          item: this.args.item,
          column: this.args.column,
          formValues: this.args.compositeEditorOptions.formValues,
          grid: this.args.grid,
          editors: this.args.compositeEditorOptions.editors
        });
      }
    }
    init() {
      var _a;
      this.input = Utils.createDomElement("input", { className: "editor-percentcomplete", type: "text" }, this.args.container), Utils.width(this.input, this.args.container.clientWidth - 25), this.picker = Utils.createDomElement("div", { className: "editor-percentcomplete-picker" }, this.args.container), Utils.createDomElement("span", { className: "editor-percentcomplete-picker-icon" }, this.picker);
      let containerHelper = Utils.createDomElement("div", { className: "editor-percentcomplete-helper" }, this.picker), containerWrapper = Utils.createDomElement("div", { className: "editor-percentcomplete-wrapper" }, containerHelper);
      Utils.createDomElement("div", { className: "editor-percentcomplete-slider" }, containerWrapper), this.slider = Utils.createDomElement("input", { className: "editor-percentcomplete-slider", type: "range", value: String((_a = this.defaultValue) != null ? _a : "") }, containerWrapper);
      let containerButtons = Utils.createDomElement("div", { className: "editor-percentcomplete-buttons" }, containerWrapper);
      Utils.createDomElement("button", { value: "0", className: "slick-btn slick-btn-default", textContent: "Not started" }, containerButtons), containerButtons.appendChild(document.createElement("br")), Utils.createDomElement("button", { value: "50", className: "slick-btn slick-btn-default", textContent: "In Progress" }, containerButtons), containerButtons.appendChild(document.createElement("br")), Utils.createDomElement("button", { value: "100", className: "slick-btn slick-btn-default", textContent: "Complete" }, containerButtons), this.input.focus(), this.input.select(), this.slider.addEventListener("input", this.sliderInputHandler.bind(this)), this.slider.addEventListener("change", this.sliderChangeHandler.bind(this));
      let buttons = this.picker.querySelectorAll(".editor-percentcomplete-buttons button");
      [].forEach.call(buttons, (button) => {
        button.addEventListener("click", this.onClick.bind(this));
      });
    }
    onClick(e) {
      var _a, _b;
      this.input.value = String((_a = e.target.value) != null ? _a : ""), this.slider.value = String((_b = e.target.value) != null ? _b : "");
    }
    destroy() {
      var _a, _b;
      (_a = this.slider) == null || _a.removeEventListener("input", this.sliderInputHandler.bind(this)), (_b = this.slider) == null || _b.removeEventListener("change", this.sliderChangeHandler.bind(this)), this.picker.querySelectorAll(".editor-percentcomplete-buttons button").forEach((button) => button.removeEventListener("click", this.onClick.bind(this))), this.input.remove(), this.picker.remove();
    }
    focus() {
      this.input.focus();
    }
    loadValue(item) {
      var _a;
      this.defaultValue = item[this.args.column.field], this.slider.value = String((_a = this.defaultValue) != null ? _a : ""), this.input.value = String(this.defaultValue), this.input.select();
    }
    serializeValue() {
      return parseInt(this.input.value, 10) || 0;
    }
    applyValue(item, state) {
      item[this.args.column.field] = state;
    }
    isValueChanged() {
      return !(this.input.value === "" && !Utils.isDefined(this.defaultValue)) && (parseInt(this.input.value, 10) || 0) !== this.defaultValue;
    }
    validate() {
      return isNaN(parseInt(this.input.value, 10)) ? {
        valid: !1,
        msg: "Please enter a valid positive number"
      } : {
        valid: !0,
        msg: null
      };
    }
  }, LongTextEditor = class {
    constructor(args) {
      this.args = args;
      __publicField(this, "input");
      __publicField(this, "wrapper");
      __publicField(this, "defaultValue");
      __publicField(this, "selectionStart", 0);
      this.init();
    }
    init() {
      let compositeEditorOptions = this.args.compositeEditorOptions;
      this.args.grid.getOptions().editorCellNavOnLRKeys;
      let container = compositeEditorOptions ? this.args.container : document.body;
      if (this.wrapper = Utils.createDomElement("div", { className: "slick-large-editor-text" }, container), compositeEditorOptions ? (this.wrapper.style.position = "relative", Utils.setStyleSize(this.wrapper, "padding", 0), Utils.setStyleSize(this.wrapper, "border", 0)) : this.wrapper.style.position = "absolute", this.input = Utils.createDomElement("textarea", { rows: 5, style: { background: "white", width: "250px", height: "80px", border: "0", outline: "0" } }, this.wrapper), compositeEditorOptions)
        this.input.addEventListener("change", this.onChange.bind(this));
      else {
        let btnContainer = Utils.createDomElement("div", { style: "text-align:right" }, this.wrapper);
        Utils.createDomElement("button", { id: "save", className: "slick-btn slick-btn-primary", textContent: "Save" }, btnContainer), Utils.createDomElement("button", { id: "cancel", className: "slick-btn slick-btn-default", textContent: "Cancel" }, btnContainer), this.wrapper.querySelector("#save").addEventListener("click", this.save.bind(this)), this.wrapper.querySelector("#cancel").addEventListener("click", this.cancel.bind(this)), this.input.addEventListener("keydown", this.handleKeyDown.bind(this)), this.position(this.args.position);
      }
      this.input.focus(), this.input.select();
    }
    onChange() {
      var _a, _b;
      let activeCell = this.args.grid.getActiveCell();
	  m_objActiveCell = activeCell;// Mitsukibo
      this.validate().valid && this.applyValue(this.args.item, this.serializeValue()), this.applyValue(this.args.compositeEditorOptions.formValues, this.serializeValue()), this.args.grid.onCompositeEditorChange.notify({
        row: (_a = activeCell == null ? void 0 : activeCell.row) != null ? _a : 0,
        cell: (_b = activeCell == null ? void 0 : activeCell.cell) != null ? _b : 0,
        item: this.args.item,
        column: this.args.column,
        formValues: this.args.compositeEditorOptions.formValues,
        grid: this.args.grid,
        editors: this.args.compositeEditorOptions.editors
      });
    }
    handleKeyDown(e) {
      if (e.which === keyCode.ENTER && e.ctrlKey)
        this.save();
      else if (e.which === keyCode.ESCAPE)
        e.preventDefault(), this.cancel();
      else if (e.which === keyCode.TAB && e.shiftKey)
        e.preventDefault(), this.args.grid.navigatePrev();
      else if (e.which === keyCode.TAB)
        e.preventDefault(), this.args.grid.navigateNext();
      else if ((e.which === keyCode.LEFT || e.which === keyCode.RIGHT) && this.args.grid.getOptions().editorCellNavOnLRKeys) {
        let cursorPosition = this.selectionStart, textLength = e.target.value.length;
        e.keyCode === keyCode.LEFT && cursorPosition === 0 && this.args.grid.navigatePrev(), e.keyCode === keyCode.RIGHT && cursorPosition >= textLength - 1 && this.args.grid.navigateNext();
      }
    }
    save() {
      (this.args.grid.getOptions() || {}).autoCommitEdit ? this.args.grid.getEditorLock().commitCurrentEdit() : this.args.commitChanges();
    }
    cancel() {
      var _a;
      this.input.value = String((_a = this.defaultValue) != null ? _a : ""), this.args.cancelChanges();
    }
    hide() {
      Utils.hide(this.wrapper);
    }
    show() {
      Utils.show(this.wrapper);
    }
    position(position) {
      Utils.setStyleSize(this.wrapper, "top", (position.top || 0) - 5), Utils.setStyleSize(this.wrapper, "left", (position.left || 0) - 2);
    }
    destroy() {
      this.args.compositeEditorOptions ? this.input.removeEventListener("change", this.onChange.bind(this)) : (this.wrapper.querySelector("#save").removeEventListener("click", this.save.bind(this)), this.wrapper.querySelector("#cancel").removeEventListener("click", this.cancel.bind(this)), this.input.removeEventListener("keydown", this.handleKeyDown.bind(this))), this.wrapper.remove();
    }
    focus() {
      this.input.focus();
    }
    loadValue(item) {
      this.input.value = this.defaultValue = item[this.args.column.field], this.input.select();
    }
    serializeValue() {
      return this.input.value;
    }
    applyValue(item, state) {
      item[this.args.column.field] = state;
    }
    isValueChanged() {
      return !(this.input.value === "" && !Utils.isDefined(this.defaultValue)) && this.input.value !== this.defaultValue;
    }
    validate() {
      if (this.args.column.validator) {
        let validationResults = this.args.column.validator(this.input.value, this.args);
        if (!validationResults.valid)
          return validationResults;
      }
      return {
        valid: !0,
        msg: null
      };
    }
  };
  function handleKeydownLRNav(e) {
    let cursorPosition = e.selectionStart, textLength = e.target.value.length;
    (e.keyCode === keyCode.LEFT && cursorPosition > 0 || e.keyCode === keyCode.RIGHT && cursorPosition < textLength - 1) && e.stopImmediatePropagation();
  }
  function handleKeydownLRNoNav(e) {
    (e.keyCode === keyCode.LEFT || e.keyCode === keyCode.RIGHT) && e.stopImmediatePropagation();
  }
  
  // Mitsukibo: binder passthrough event (it becomes an onCellChange event)
  this.finderBinder_onEvent = function(strType_a, strFormID_a, strLocator_a, strEventName_a, strID_a, strValue_a)
  {
	  if ($.isFunction(m_objRendererEventHandler))
	  {
		m_objRendererEventHandler(strType_a, strFormID_a, strLocator_a, 'onCellChange', m_objActiveCell, m_objActiveColumn, m_strFieldName, strID_a, strValue_a);
	  }
  };
  
  var Editors = {
    Text: TextEditor,
    Integer: IntegerEditor,
    Float: FloatEditor,
    Flatpickr: FlatpickrEditor,
    YesNoSelect: YesNoSelectEditor,
	DeleteButton: DeleteButtonEditor,
    Checkbox: CheckboxEditor,
    PercentComplete: PercentCompleteEditor,
    LongText: LongTextEditor
  };
  window.Slick && Utils.extend(Slick, {
    Editors
  });
})();
//# sourceMappingURL=slick.editors.js.map
"use strict";
(() => {
  // src/slick.formatters.ts
  var Utils = Slick.Utils, PercentCompleteFormatter = (_row, _cell, value) => !Utils.isDefined(value) || value === "" ? "-" : value < 50 ? `<span style="color:red;font-weight:bold;">${value}%</span>` : `<span style="color:green">${value}%</span>`, PercentCompleteBarFormatter = (_row, _cell, value) => {
    if (!Utils.isDefined(value) || value === "")
      return "";
    let color;
    return value < 30 ? color = "red" : value < 70 ? color = "silver" : color = "green", `<span class="percent-complete-bar" style="background:${color};width:${value}%" title="${value}%"></span>`;
  }, DeleteButtonFormatter = (_row, _cell, value) => '<button class="btn btn-primary gb-button glyphicon glyphicon-trash" style="text-align:center; width:100%; height:100%; margin:0 0 0 0; padding:0 0 0 0;"></button>', YesNoFormatter = (_row, _cell, value) => value ? "Yes" : "No", CheckboxFormatter = (_row, _cell, value) => `<span class="sgi sgi-checkbox-${value ? "intermediate" : "blank-outline"}"></span>`, CheckmarkFormatter = (_row, _cell, value) => value ? '<span class="sgi sgi-check"></span>' : "", Formatters = {
    PercentComplete: PercentCompleteFormatter,
    PercentCompleteBar: PercentCompleteBarFormatter,
    YesNo: YesNoFormatter,
	DeleteButton: DeleteButtonFormatter,	// Mitsukibo: new delete button editor
    Checkmark: CheckmarkFormatter,
    Checkbox: CheckboxFormatter
  };
  window.Slick && Utils.extend(Slick, {
    Formatters
  });
})();
//# sourceMappingURL=slick.formatters.js.map
"use strict";
(() => {
  // src/slick.compositeeditor.ts
  var Utils = Slick.Utils;
  function SlickCompositeEditor(columns, containers, options) {
    let defaultOptions = {
      modalType: "edit",
      // available type (create, edit, mass)
      validationFailedMsg: "Some of the fields have failed validation",
      validationMsgPrefix: null,
      show: null,
      hide: null,
      position: null,
      destroy: null,
      formValues: {},
      editors: {}
    }, noop = function() {
    }, firstInvalidEditor = null;
    options = Slick.Utils.extend({}, defaultOptions, options);
    function getContainerBox(i) {
      var _a, _b, _c, _d;
      let c = containers[i], offset = Slick.Utils.offset(c), w = Slick.Utils.width(c), h = Slick.Utils.height(c);
      return {
        top: (_a = offset == null ? void 0 : offset.top) != null ? _a : 0,
        left: (_b = offset == null ? void 0 : offset.left) != null ? _b : 0,
        bottom: ((_c = offset == null ? void 0 : offset.top) != null ? _c : 0) + (h || 0),
        right: ((_d = offset == null ? void 0 : offset.left) != null ? _d : 0) + (w || 0),
        width: w,
        height: h,
        visible: !0
      };
    }
    function editor(args) {
      let context = this, editors = [];
      function init() {
        let newArgs = {}, idx = 0;
        for (; idx < columns.length; ) {
          if (columns[idx].editor) {
            let column = columns[idx];
            newArgs = Slick.Utils.extend(!1, {}, args), newArgs.container = containers[idx], newArgs.column = column, newArgs.position = getContainerBox(idx), newArgs.commitChanges = noop, newArgs.cancelChanges = noop, newArgs.compositeEditorOptions = options, newArgs.formValues = {};
            let currentEditor = new column.editor(newArgs);
            options.editors[column.id] = currentEditor, editors.push(currentEditor);
          }
          idx++;
        }
        setTimeout(function() {
          Array.isArray(editors) && editors.length > 0 && typeof editors[0].focus == "function" && editors[0].focus();
        }, 0);
      }
      context.destroy = () => {
        var _a;
        let idx = 0;
        for (; idx < editors.length; )
          editors[idx].destroy(), idx++;
        (_a = options.destroy) == null || _a.call(options), editors = [];
      }, context.focus = () => {
        (firstInvalidEditor || editors[0]).focus();
      }, context.isValueChanged = () => {
        let idx = 0;
        for (; idx < editors.length; ) {
          if (editors[idx].isValueChanged())
            return !0;
          idx++;
        }
        return !1;
      }, context.serializeValue = () => {
        let serializedValue = [], idx = 0;
        for (; idx < editors.length; )
          serializedValue[idx] = editors[idx].serializeValue(), idx++;
        return serializedValue;
      }, context.applyValue = (item, state) => {
        let idx = 0;
        for (; idx < editors.length; )
          editors[idx].applyValue(item, state[idx]), idx++;
      }, context.loadValue = (item) => {
        let idx = 0;
        for (; idx < editors.length; )
          editors[idx].loadValue(item), idx++;
      }, context.validate = (target) => {
        var _a, _b;
        let validationResults, errors = [], targetElm = target || null;
        firstInvalidEditor = null;
        let idx = 0;
        for (; idx < editors.length; ) {
          let columnDef = (_b = (_a = editors[idx].args) == null ? void 0 : _a.column) != null ? _b : {};
          if (columnDef) {
            let validationElm = document.querySelector(`.item-details-validation.editor-${columnDef.id}`), labelElm = document.querySelector(`.item-details-label.editor-${columnDef.id}`), editorElm = document.querySelector(`[data-editorid=${columnDef.id}]`), validationMsgPrefix = (options == null ? void 0 : options.validationMsgPrefix) || "";
            (!targetElm || Slick.Utils.contains(editorElm, targetElm)) && (validationResults = editors[idx].validate(), validationResults.valid ? validationElm && (validationElm.textContent = "", editorElm == null || editorElm.classList.remove("invalid"), labelElm == null || labelElm.classList.remove("invalid")) : (firstInvalidEditor = editors[idx], errors.push({
              index: idx,
              editor: editors[idx],
              container: containers[idx],
              msg: validationResults.msg
            }), validationElm && (validationElm.textContent = validationMsgPrefix + validationResults.msg, labelElm == null || labelElm.classList.add("invalid"), editorElm == null || editorElm.classList.add("invalid")))), validationElm = null, labelElm = null, editorElm = null;
          }
          idx++;
        }
        return targetElm = null, errors.length ? {
          valid: !1,
          msg: options.validationFailedMsg,
          errors
        } : {
          valid: !0,
          msg: ""
        };
      }, context.hide = () => {
        var _a, _b, _c;
        let idx = 0;
        for (; idx < editors.length; )
          (_b = (_a = editors[idx]) == null ? void 0 : _a.hide) == null || _b.call(_a), idx++;
        (_c = options == null ? void 0 : options.hide) == null || _c.call(options);
      }, context.show = () => {
        var _a, _b, _c;
        let idx = 0;
        for (; idx < editors.length; )
          (_b = (_a = editors[idx]) == null ? void 0 : _a.show) == null || _b.call(_a), idx++;
        (_c = options == null ? void 0 : options.show) == null || _c.call(options);
      }, context.position = (box) => {
        var _a;
        (_a = options == null ? void 0 : options.position) == null || _a.call(options, box);
      }, init();
    }
    return editor.prototype = this, editor;
  }
  window.Slick && Utils.extend(Slick, {
    CompositeEditor: SlickCompositeEditor
  });
})();
//# sourceMappingURL=slick.compositeeditor.js.map
"use strict";
(() => {
  var __defProp = Object.defineProperty;
  var __defNormalProp = (obj, key, value) => key in obj ? __defProp(obj, key, { enumerable: !0, configurable: !0, writable: !0, value }) : obj[key] = value;
  var __publicField = (obj, key, value) => (__defNormalProp(obj, typeof key != "symbol" ? key + "" : key, value), value);

  // src/slick.dataview.ts
  var SlickEvent = Slick.Event, SlickEventData = Slick.EventData, SlickGroup = Slick.Group, SlickGroupTotals = Slick.GroupTotals, Utils = Slick.Utils, _a, _b, SlickGroupItemMetadataProvider = (_b = (_a = Slick.Data) == null ? void 0 : _a.GroupItemMetadataProvider) != null ? _b : {}, SlickDataView = class {
    constructor(options) {
      __publicField(this, "defaults", {
        groupItemMetadataProvider: null,
        inlineFilters: !1,
        useCSPSafeFilter: !1
      });
      // private
      __publicField(this, "idProperty", "id");
      // property holding a unique row id
      __publicField(this, "items", []);
      // data by index
      __publicField(this, "rows", []);
      // data by row
      __publicField(this, "idxById", /* @__PURE__ */ new Map());
      // indexes by id
      __publicField(this, "rowsById");
      // rows by id; lazy-calculated
      __publicField(this, "filter", null);
      // filter function
      __publicField(this, "filterCSPSafe", null);
      // filter function
      __publicField(this, "updated", null);
      // updated item ids
      __publicField(this, "suspend", !1);
      // suspends the recalculation
      __publicField(this, "isBulkSuspend", !1);
      // delays protectedious operations like the
      // index update and delete to efficient
      // versions at endUpdate
      __publicField(this, "bulkDeleteIds", /* @__PURE__ */ new Map());
      __publicField(this, "sortAsc", !0);
      __publicField(this, "fastSortField");
      __publicField(this, "sortComparer");
      __publicField(this, "refreshHints", {});
      __publicField(this, "prevRefreshHints", {});
      __publicField(this, "filterArgs");
      __publicField(this, "filteredItems", []);
      __publicField(this, "compiledFilter");
      __publicField(this, "compiledFilterCSPSafe");
      __publicField(this, "compiledFilterWithCaching");
      __publicField(this, "compiledFilterWithCachingCSPSafe");
      __publicField(this, "filterCache", []);
      __publicField(this, "_grid");
      // grid object will be defined only after using "syncGridSelection()" method"
      // grouping
      __publicField(this, "groupingInfoDefaults", {
        getter: void 0,
        formatter: void 0,
        comparer: (a, b) => a.value === b.value ? 0 : a.value > b.value ? 1 : -1,
        predefinedValues: [],
        aggregators: [],
        aggregateEmpty: !1,
        aggregateCollapsed: !1,
        aggregateChildGroups: !1,
        collapsed: !1,
        displayTotalsRow: !0,
        lazyTotalsCalculation: !1
      });
      __publicField(this, "groupingInfos", []);
      __publicField(this, "groups", []);
      __publicField(this, "toggledGroupsByLevel", []);
      __publicField(this, "groupingDelimiter", ":|:");
      __publicField(this, "selectedRowIds", []);
      __publicField(this, "preSelectedRowIdsChangeFn");
      __publicField(this, "pagesize", 0);
      __publicField(this, "pagenum", 0);
      __publicField(this, "totalRows", 0);
      __publicField(this, "_options");
      // public events
      __publicField(this, "onBeforePagingInfoChanged", new SlickEvent());
      __publicField(this, "onGroupExpanded", new SlickEvent());
      __publicField(this, "onGroupCollapsed", new SlickEvent());
      __publicField(this, "onPagingInfoChanged", new SlickEvent());
      __publicField(this, "onRowCountChanged", new SlickEvent());
      __publicField(this, "onRowsChanged", new SlickEvent());
      __publicField(this, "onRowsOrCountChanged", new SlickEvent());
      __publicField(this, "onSelectedRowIdsChanged", new SlickEvent());
      __publicField(this, "onSetItemsCalled", new SlickEvent());
      this._options = Utils.extend(!0, {}, this.defaults, options);
    }
    /**
     * Begins a bached update of the items in the data view.
     * including deletes and the related events are postponed to the endUpdate call.
     * As certain operations are postponed during this update, some methods might not
     * deliver fully consistent information.
     * @param {Boolean} [bulkUpdate] - if set to true, most data view modifications
     */
    beginUpdate(bulkUpdate) {
      this.suspend = !0, this.isBulkSuspend = bulkUpdate === !0;
    }
    endUpdate() {
      let wasBulkSuspend = this.isBulkSuspend;
      this.isBulkSuspend = !1, this.suspend = !1, wasBulkSuspend && (this.processBulkDelete(), this.ensureIdUniqueness()), this.refresh();
    }
    destroy() {
      this.items = [], this.idxById = null, this.rowsById = null, this.filter = null, this.filterCSPSafe = null, this.updated = null, this.sortComparer = null, this.filterCache = [], this.filteredItems = [], this.compiledFilter = null, this.compiledFilterCSPSafe = null, this.compiledFilterWithCaching = null, this.compiledFilterWithCachingCSPSafe = null, this._grid && this._grid.onSelectedRowsChanged && this._grid.onCellCssStylesChanged && (this._grid.onSelectedRowsChanged.unsubscribe(), this._grid.onCellCssStylesChanged.unsubscribe()), this.onRowsOrCountChanged && this.onRowsOrCountChanged.unsubscribe();
    }
    setRefreshHints(hints) {
      this.refreshHints = hints;
    }
    setFilterArgs(args) {
      this.filterArgs = args;
    }
    /**
     * Processes all delete requests placed during bulk update
     * by recomputing the items and idxById members.
     */
    processBulkDelete() {
      if (!this.idxById)
        return;
      let id, item, newIdx = 0;
      for (let i = 0, l = this.items.length; i < l; i++) {
        if (item = this.items[i], id = item[this.idProperty], id === void 0)
          throw new Error("[SlickGrid DataView] Each data element must implement a unique 'id' property");
        this.bulkDeleteIds.has(id) ? this.idxById.delete(id) : (this.items[newIdx] = item, this.idxById.set(id, newIdx), ++newIdx);
      }
      this.items.length = newIdx, this.bulkDeleteIds = /* @__PURE__ */ new Map();
    }
    updateIdxById(startingIndex) {
      if (this.isBulkSuspend || !this.idxById)
        return;
      startingIndex = startingIndex || 0;
      let id;
      for (let i = startingIndex, l = this.items.length; i < l; i++) {
        if (id = this.items[i][this.idProperty], id === void 0)
          throw new Error("[SlickGrid DataView] Each data element must implement a unique 'id' property");
        this.idxById.set(id, i);
      }
    }
    ensureIdUniqueness() {
      if (this.isBulkSuspend || !this.idxById)
        return;
      let id;
      for (let i = 0, l = this.items.length; i < l; i++)
        if (id = this.items[i][this.idProperty], id === void 0 || this.idxById.get(id) !== i)
          throw new Error("[SlickGrid DataView] Each data element must implement a unique 'id' property");
    }
    /** Get all DataView Items */
    getItems() {
      return this.items;
    }
    /** Get the DataView Id property name to use (defaults to "Id" but could be customized to something else when instantiating the DataView) */
    getIdPropertyName() {
      return this.idProperty;
    }
    /**
     * Set the Items with a new Dataset and optionally pass a different Id property name
     * @param {Array<*>} data - array of data
     * @param {String} [objectIdProperty] - optional id property to use as primary id
     */
    setItems(data, objectIdProperty) {
      objectIdProperty !== void 0 && (this.idProperty = objectIdProperty), this.items = this.filteredItems = data, this.onSetItemsCalled.notify({ idProperty: this.idProperty, itemCount: this.items.length }, null, this), this.idxById = /* @__PURE__ */ new Map(), this.updateIdxById(), this.ensureIdUniqueness(), this.refresh();
    }
    /** Set Paging Options */
    setPagingOptions(args) {
      this.onBeforePagingInfoChanged.notify(this.getPagingInfo(), null, this).getReturnValue() !== !1 && (Utils.isDefined(args.pageSize) && (this.pagesize = args.pageSize, this.pagenum = this.pagesize ? Math.min(this.pagenum, Math.max(0, Math.ceil(this.totalRows / this.pagesize) - 1)) : 0), Utils.isDefined(args.pageNum) && (this.pagenum = Math.min(args.pageNum, Math.max(0, Math.ceil(this.totalRows / this.pagesize) - 1))), this.onPagingInfoChanged.notify(this.getPagingInfo(), null, this), this.refresh());
    }
    /** Get Paging Options */
    getPagingInfo() {
      let totalPages = this.pagesize ? Math.max(1, Math.ceil(this.totalRows / this.pagesize)) : 1;
      return { pageSize: this.pagesize, pageNum: this.pagenum, totalRows: this.totalRows, totalPages, dataView: this };
    }
    /** Sort Method to use by the DataView */
    sort(comparer, ascending) {
      this.sortAsc = ascending, this.sortComparer = comparer, this.fastSortField = null, ascending === !1 && this.items.reverse(), this.items.sort(comparer), ascending === !1 && this.items.reverse(), this.idxById = /* @__PURE__ */ new Map(), this.updateIdxById(), this.refresh();
    }
    /**
     * Provides a workaround for the extremely slow sorting in IE.
     * Does a [lexicographic] sort on a give column by temporarily overriding Object.prototype.toString
     * to return the value of that field and then doing a native Array.sort().
     */
    fastSort(field, ascending) {
      this.sortAsc = ascending, this.fastSortField = field, this.sortComparer = null;
      let oldToString = Object.prototype.toString;
      Object.prototype.toString = typeof field == "function" ? field : function() {
        return this[field];
      }, ascending === !1 && this.items.reverse(), this.items.sort(), Object.prototype.toString = oldToString, ascending === !1 && this.items.reverse(), this.idxById = /* @__PURE__ */ new Map(), this.updateIdxById(), this.refresh();
    }
    /** Re-Sort the dataset */
    reSort() {
      this.sortComparer ? this.sort(this.sortComparer, this.sortAsc) : this.fastSortField && this.fastSort(this.fastSortField, this.sortAsc);
    }
    /** Get only the DataView filtered items */
    getFilteredItems() {
      return this.filteredItems;
    }
    /** Get the array length (count) of only the DataView filtered items */
    getFilteredItemCount() {
      return this.filteredItems.length;
    }
    /** Get current Filter used by the DataView */
    getFilter() {
      return this._options.useCSPSafeFilter ? this.filterCSPSafe : this.filter;
    }
    /**
     * Set a Filter that will be used by the DataView
     * @param {Function} fn - filter callback function
     */
    setFilter(filterFn) {
      this.filterCSPSafe = filterFn, this.filter = filterFn, this._options.inlineFilters && (this.compiledFilterCSPSafe = this.compileFilterCSPSafe, this.compiledFilterWithCachingCSPSafe = this.compileFilterWithCachingCSPSafe, this.compiledFilter = this.compileFilter(this._options.useCSPSafeFilter), this.compiledFilterWithCaching = this.compileFilterWithCaching(this._options.useCSPSafeFilter)), this.refresh();
    }
    /** Get current Grouping info */
    getGrouping() {
      return this.groupingInfos;
    }
    /** Set some Grouping */
    setGrouping(groupingInfo) {
      this._options.groupItemMetadataProvider || (this._options.groupItemMetadataProvider = new SlickGroupItemMetadataProvider()), this.groups = [], this.toggledGroupsByLevel = [], groupingInfo = groupingInfo || [], this.groupingInfos = groupingInfo instanceof Array ? groupingInfo : [groupingInfo];
      for (let i = 0; i < this.groupingInfos.length; i++) {
        let gi = this.groupingInfos[i] = Utils.extend(!0, {}, this.groupingInfoDefaults, this.groupingInfos[i]);
        gi.getterIsAFn = typeof gi.getter == "function", gi.compiledAccumulators = [];
        let idx = gi.aggregators.length;
        for (; idx--; )
          gi.compiledAccumulators[idx] = this.compileAccumulatorLoop(gi.aggregators[idx]);
        this.toggledGroupsByLevel[i] = {};
      }
      this.refresh();
    }
    /** Get an item in the DataView by its row index */
    getItemByIdx(i) {
      return this.items[i];
    }
    /** Get row index in the DataView by its Id */
    getIdxById(id) {
      var _a2;
      return (_a2 = this.idxById) == null ? void 0 : _a2.get(id);
    }
    ensureRowsByIdCache() {
      if (!this.rowsById) {
        this.rowsById = {};
        for (let i = 0, l = this.rows.length; i < l; i++)
          this.rowsById[this.rows[i][this.idProperty]] = i;
      }
    }
    /** Get row number in the grid by its item object */
    getRowByItem(item) {
      var _a2;
      return this.ensureRowsByIdCache(), (_a2 = this.rowsById) == null ? void 0 : _a2[item[this.idProperty]];
    }
    /** Get row number in the grid by its Id */
    getRowById(id) {
      var _a2;
      return this.ensureRowsByIdCache(), (_a2 = this.rowsById) == null ? void 0 : _a2[id];
    }
    /** Get an item in the DataView by its Id */
    getItemById(id) {
      return this.items[this.idxById.get(id)];
    }
    /** From the items array provided, return the mapped rows */
    mapItemsToRows(itemArray) {
      var _a2;
      let rows = [];
      this.ensureRowsByIdCache();
      for (let i = 0, l = itemArray.length; i < l; i++) {
        let row = (_a2 = this.rowsById) == null ? void 0 : _a2[itemArray[i][this.idProperty]];
        Utils.isDefined(row) && (rows[rows.length] = row);
      }
      return rows;
    }
    /** From the Ids array provided, return the mapped rows */
    mapIdsToRows(idArray) {
      var _a2;
      let rows = [];
      this.ensureRowsByIdCache();
      for (let i = 0, l = idArray.length; i < l; i++) {
        let row = (_a2 = this.rowsById) == null ? void 0 : _a2[idArray[i]];
        Utils.isDefined(row) && (rows[rows.length] = row);
      }
      return rows;
    }
    /** From the rows array provided, return the mapped Ids */
    mapRowsToIds(rowArray) {
      let ids = [];
      for (let i = 0, l = rowArray.length; i < l; i++)
        if (rowArray[i] < this.rows.length) {
          let rowItem = this.rows[rowArray[i]];
          ids[ids.length] = rowItem[this.idProperty];
        }
      return ids;
    }
    /**
     * Performs the update operations of a single item by id without
     * triggering any events or refresh operations.
     * @param id The new id of the item.
     * @param item The item which should be the new value for the given id.
     */
    updateSingleItem(id, item) {
      var _a2;
      if (this.idxById) {
        if (!this.idxById.has(id))
          throw new Error("[SlickGrid DataView] Invalid id");
        if (id !== item[this.idProperty]) {
          let newId = item[this.idProperty];
          if (!Utils.isDefined(newId))
            throw new Error("[SlickGrid DataView] Cannot update item to associate with a null id");
          if (this.idxById.has(newId))
            throw new Error("[SlickGrid DataView] Cannot update item to associate with a non-unique id");
          this.idxById.set(newId, this.idxById.get(id)), this.idxById.delete(id), (_a2 = this.updated) != null && _a2[id] && delete this.updated[id], id = newId;
        }
        this.items[this.idxById.get(id)] = item, this.updated || (this.updated = {}), this.updated[id] = !0;
      }
    }
    /**
     * Updates a single item in the data view given the id and new value.
     * @param id The new id of the item.
     * @param item The item which should be the new value for the given id.
     */
    updateItem(id, item) {
      this.updateSingleItem(id, item), this.refresh();
    }
    /**
     * Updates multiple items in the data view given the new ids and new values.
     * @param id {Array} The array of new ids which is in the same order as the items.
     * @param newItems {Array} The new items that should be set in the data view for the given ids.
     */
    updateItems(ids, newItems) {
      if (ids.length !== newItems.length)
        throw new Error("[SlickGrid DataView] Mismatch on the length of ids and items provided to update");
      for (let i = 0, l = newItems.length; i < l; i++)
        this.updateSingleItem(ids[i], newItems[i]);
      this.refresh();
    }
    /**
     * Inserts a single item into the data view at the given position.
     * @param insertBefore {Number} The 0-based index before which the item should be inserted.
     * @param item The item to insert.
     */
    insertItem(insertBefore, item) {
      this.items.splice(insertBefore, 0, item), this.updateIdxById(insertBefore), this.refresh();
    }
    /**
     * Inserts multiple items into the data view at the given position.
     * @param insertBefore {Number} The 0-based index before which the items should be inserted.
     * @param newItems {Array}  The items to insert.
     */
    insertItems(insertBefore, newItems) {
      Array.prototype.splice.apply(this.items, [insertBefore, 0].concat(newItems)), this.updateIdxById(insertBefore), this.refresh();
    }
    /**
     * Adds a single item at the end of the data view.
     * @param item The item to add at the end.
     */
    addItem(item) {
      this.items.push(item), this.updateIdxById(this.items.length - 1), this.refresh();
    }
    /**
     * Adds multiple items at the end of the data view.
     * @param {Array} newItems The items to add at the end.
     */
    addItems(newItems) {
      this.items = this.items.concat(newItems), this.updateIdxById(this.items.length - newItems.length), this.refresh();
    }
    /**
     * Deletes a single item identified by the given id from the data view.
     * @param {String|Number} id The id identifying the object to delete.
     */
    deleteItem(id) {
      if (this.idxById)
        if (this.isBulkSuspend)
          this.bulkDeleteIds.set(id, !0);
        else {
          let idx = this.idxById.get(id);
          if (idx === void 0)
            throw new Error("[SlickGrid DataView] Invalid id");
          this.idxById.delete(id), this.items.splice(idx, 1), this.updateIdxById(idx);
		  try
		  {
			this.refresh();
		  }
		  catch(err)
		  {
			  // do nothing
		  }
        }
    }
    /**
     * Deletes multiple item identified by the given ids from the data view.
     * @param {Array} ids The ids of the items to delete.
     */
    deleteItems(ids) {
      if (!(ids.length === 0 || !this.idxById))
        if (this.isBulkSuspend)
          for (let i = 0, l = ids.length; i < l; i++) {
            let id = ids[i];
            if (this.idxById.get(id) === void 0)
              throw new Error("[SlickGrid DataView] Invalid id");
            this.bulkDeleteIds.set(id, !0);
          }
        else {
          let indexesToDelete = [];
          for (let i = 0, l = ids.length; i < l; i++) {
            let id = ids[i], idx = this.idxById.get(id);
            if (idx === void 0)
              throw new Error("[SlickGrid DataView] Invalid id");
            this.idxById.delete(id), indexesToDelete.push(idx);
          }
          indexesToDelete.sort();
          for (let i = indexesToDelete.length - 1; i >= 0; --i)
            this.items.splice(indexesToDelete[i], 1);
          this.updateIdxById(indexesToDelete[0]), this.refresh();
        }
    }
    /** Add an item in a sorted dataset (a Sort function must be defined) */
    sortedAddItem(item) {
      if (!this.sortComparer)
        throw new Error("[SlickGrid DataView] sortedAddItem() requires a sort comparer, use sort()");
      this.insertItem(this.sortedIndex(item), item);
    }
    /** Update an item in a sorted dataset (a Sort function must be defined) */
    sortedUpdateItem(id, item) {
      if (!this.idxById)
        return;
      if (!this.idxById.has(id) || id !== item[this.idProperty])
        throw new Error("[SlickGrid DataView] Invalid or non-matching id " + this.idxById.get(id));
      if (!this.sortComparer)
        throw new Error("[SlickGrid DataView] sortedUpdateItem() requires a sort comparer, use sort()");
      let oldItem = this.getItemById(id);
      this.sortComparer(oldItem, item) !== 0 ? (this.deleteItem(id), this.sortedAddItem(item)) : this.updateItem(id, item);
    }
    sortedIndex(searchItem) {
      let low = 0, high = this.items.length;
      for (; low < high; ) {
        let mid = low + high >>> 1;
        this.sortComparer(this.items[mid], searchItem) === -1 ? low = mid + 1 : high = mid;
      }
      return low;
    }
    /** Get item count, that is the full dataset lenght of the DataView */
    getItemCount() {
      return this.items.length;
    }
    /** Get row count (rows displayed in current page) */
    getLength() {
      return this.rows.length;
    }
    /** Retrieve an item from the DataView at specific index */
    getItem(i) {
      var _a2;
      let item = this.rows[i];
      if (item != null && item.__group && item.totals && !((_a2 = item.totals) != null && _a2.initialized)) {
        let gi = this.groupingInfos[item.level];
        gi.displayTotalsRow || (this.calculateTotals(item.totals), item.title = gi.formatter ? gi.formatter(item) : item.value);
      } else
        item != null && item.__groupTotals && !item.initialized && this.calculateTotals(item);
      return item;
    }
    getItemMetadata(i) {
      let item = this.rows[i];
      return item === void 0 ? null : item.__group ? this._options.groupItemMetadataProvider.getGroupRowMetadata(item) : item.__groupTotals ? this._options.groupItemMetadataProvider.getTotalsRowMetadata(item) : null;
    }
    expandCollapseAllGroups(level, collapse) {
      if (Utils.isDefined(level))
        this.toggledGroupsByLevel[level] = {}, this.groupingInfos[level].collapsed = collapse, collapse === !0 ? this.onGroupCollapsed.notify({ level, groupingKey: null }) : this.onGroupExpanded.notify({ level, groupingKey: null });
      else
        for (let i = 0; i < this.groupingInfos.length; i++)
          this.toggledGroupsByLevel[i] = {}, this.groupingInfos[i].collapsed = collapse, collapse === !0 ? this.onGroupCollapsed.notify({ level: i, groupingKey: null }) : this.onGroupExpanded.notify({ level: i, groupingKey: null });
      this.refresh();
    }
    /**
     * @param {Number} [level] Optional level to collapse.  If not specified, applies to all levels.
     */
    collapseAllGroups(level) {
      this.expandCollapseAllGroups(level, !0);
    }
    /**
     * @param {Number} [level] Optional level to expand.  If not specified, applies to all levels.
     */
    expandAllGroups(level) {
      this.expandCollapseAllGroups(level, !1);
    }
    expandCollapseGroup(level, groupingKey, collapse) {
      this.toggledGroupsByLevel[level][groupingKey] = this.groupingInfos[level].collapsed ^ collapse, this.refresh();
    }
    /**
     * @param varArgs Either a Slick.Group's "groupingKey" property, or a
     *     variable argument list of grouping values denoting a unique path to the row.  For
     *     example, calling collapseGroup('high', '10%') will collapse the '10%' subgroup of
     *     the 'high' group.
     */
    collapseGroup(...args) {
      let arg0 = Array.prototype.slice.call(args)[0], groupingKey, level;
      args.length === 1 && arg0.indexOf(this.groupingDelimiter) !== -1 ? (groupingKey = arg0, level = arg0.split(this.groupingDelimiter).length - 1) : (groupingKey = args.join(this.groupingDelimiter), level = args.length - 1), this.expandCollapseGroup(level, groupingKey, !0), this.onGroupCollapsed.notify({ level, groupingKey });
    }
    /**
     * @param varArgs Either a Slick.Group's "groupingKey" property, or a
     *     variable argument list of grouping values denoting a unique path to the row.  For
     *     example, calling expandGroup('high', '10%') will expand the '10%' subgroup of
     *     the 'high' group.
     */
    expandGroup(...args) {
      let arg0 = Array.prototype.slice.call(args)[0], groupingKey, level;
      args.length === 1 && arg0.indexOf(this.groupingDelimiter) !== -1 ? (level = arg0.split(this.groupingDelimiter).length - 1, groupingKey = arg0) : (level = args.length - 1, groupingKey = args.join(this.groupingDelimiter)), this.expandCollapseGroup(level, groupingKey, !1), this.onGroupExpanded.notify({ level, groupingKey });
    }
    getGroups() {
      return this.groups;
    }
    extractGroups(rows, parentGroup) {
      var _a2, _b2, _c;
      let group, val, groups = [], groupsByVal = {}, r, level = parentGroup ? parentGroup.level + 1 : 0, gi = this.groupingInfos[level];
      for (let i = 0, l = (_b2 = (_a2 = gi.predefinedValues) == null ? void 0 : _a2.length) != null ? _b2 : 0; i < l; i++)
        val = (_c = gi.predefinedValues) == null ? void 0 : _c[i], group = groupsByVal[val], group || (group = new SlickGroup(), group.value = val, group.level = level, group.groupingKey = (parentGroup ? parentGroup.groupingKey + this.groupingDelimiter : "") + val, groups[groups.length] = group, groupsByVal[val] = group);
      for (let i = 0, l = rows.length; i < l; i++)
        r = rows[i], val = gi.getterIsAFn ? gi.getter(r) : r[gi.getter], group = groupsByVal[val], group || (group = new SlickGroup(), group.value = val, group.level = level, group.groupingKey = (parentGroup ? parentGroup.groupingKey + this.groupingDelimiter : "") + val, groups[groups.length] = group, groupsByVal[val] = group), group.rows[group.count++] = r;
      if (level < this.groupingInfos.length - 1)
        for (let i = 0; i < groups.length; i++)
          group = groups[i], group.groups = this.extractGroups(group.rows, group);
      return groups.length && this.addTotals(groups, level), groups.sort(this.groupingInfos[level].comparer), groups;
    }
    calculateTotals(totals) {
      var _a2, _b2, _c;
      let group = totals.group, gi = this.groupingInfos[(_a2 = group.level) != null ? _a2 : 0], isLeafLevel = group.level === this.groupingInfos.length, agg, idx = gi.aggregators.length;
      if (!isLeafLevel && gi.aggregateChildGroups) {
        let i = (_c = (_b2 = group.groups) == null ? void 0 : _b2.length) != null ? _c : 0;
        for (; i--; )
          group.groups[i].totals.initialized || this.calculateTotals(group.groups[i].totals);
      }
      for (; idx--; )
        agg = gi.aggregators[idx], agg.init(), !isLeafLevel && gi.aggregateChildGroups ? gi.compiledAccumulators[idx].call(agg, group.groups) : gi.compiledAccumulators[idx].call(agg, group.rows), agg.storeResult(totals);
      totals.initialized = !0;
    }
    addGroupTotals(group) {
      let gi = this.groupingInfos[group.level], totals = new SlickGroupTotals();
      totals.group = group, group.totals = totals, gi.lazyTotalsCalculation || this.calculateTotals(totals);
    }
    addTotals(groups, level) {
      var _a2, _b2;
      level = level || 0;
      let gi = this.groupingInfos[level], groupCollapsed = gi.collapsed, toggledGroups = this.toggledGroupsByLevel[level], idx = groups.length, g;
      for (; idx--; )
        g = groups[idx], !(g.collapsed && !gi.aggregateCollapsed) && (g.groups && this.addTotals(g.groups, level + 1), (_a2 = gi.aggregators) != null && _a2.length && (gi.aggregateEmpty || g.rows.length || (_b2 = g.groups) != null && _b2.length) && this.addGroupTotals(g), g.collapsed = groupCollapsed ^ toggledGroups[g.groupingKey], g.title = gi.formatter ? gi.formatter(g) : g.value);
    }
    flattenGroupedRows(groups, level) {
      level = level || 0;
      let gi = this.groupingInfos[level], groupedRows = [], rows, gl = 0, g;
      for (let i = 0, l = groups.length; i < l; i++) {
        if (g = groups[i], groupedRows[gl++] = g, !g.collapsed) {
          rows = g.groups ? this.flattenGroupedRows(g.groups, level + 1) : g.rows;
          for (let j = 0, jj = rows.length; j < jj; j++)
            groupedRows[gl++] = rows[j];
        }
        g.totals && gi.displayTotalsRow && (!g.collapsed || gi.aggregateCollapsed) && (groupedRows[gl++] = g.totals);
      }
      return groupedRows;
    }
    getFunctionInfo(fn) {
      let fnRegex = fn.toString().indexOf("function") >= 0 ? /^function[^(]*\(([^)]*)\)\s*{([\s\S]*)}$/ : /^[^(]*\(([^)]*)\)\s*{([\s\S]*)}$/, matches = fn.toString().match(fnRegex) || [];
      return {
        params: matches[1].split(","),
        body: matches[2]
      };
    }
    compileAccumulatorLoop(aggregator) {
      if (aggregator.accumulate) {
        let accumulatorInfo = this.getFunctionInfo(aggregator.accumulate), fn = new Function(
          "_items",
          "for (var " + accumulatorInfo.params[0] + ", _i=0, _il=_items.length; _i<_il; _i++) {" + accumulatorInfo.params[0] + " = _items[_i]; " + accumulatorInfo.body + "}"
        ), fnName = "compiledAccumulatorLoop";
        return fn.displayName = fnName, fn.name = this.setFunctionName(fn, fnName), fn;
      } else
        return function() {
        };
    }
    compileFilterCSPSafe(items, args) {
      if (typeof this.filterCSPSafe != "function")
        return [];
      let _retval = [], _il = items.length;
      for (let _i = 0; _i < _il; _i++)
        this.filterCSPSafe(items[_i], args) && _retval.push(items[_i]);
      return _retval;
    }
    compileFilter(stopRunningIfCSPSafeIsActive = !1) {
      if (stopRunningIfCSPSafeIsActive)
        return null;
      let filterInfo = this.getFunctionInfo(this.filter), filterPath1 = "{ continue _coreloop; }$1", filterPath2 = "{ _retval[_idx++] = $item$; continue _coreloop; }$1", filterBody = filterInfo.body.replace(/return false\s*([;}]|\}|$)/gi, filterPath1).replace(/return!1([;}]|\}|$)/gi, filterPath1).replace(/return true\s*([;}]|\}|$)/gi, filterPath2).replace(/return!0([;}]|\}|$)/gi, filterPath2).replace(
        /return ([^;}]+?)\s*([;}]|$)/gi,
        "{ if ($1) { _retval[_idx++] = $item$; }; continue _coreloop; }$2"
      ), tpl = [
        // 'function(_items, _args) { ',
        "var _retval = [], _idx = 0; ",
        "var $item$, $args$ = _args; ",
        "_coreloop: ",
        "for (var _i = 0, _il = _items.length; _i < _il; _i++) { ",
        "$item$ = _items[_i]; ",
        "$filter$; ",
        "} ",
        "return _retval; "
        // '}'
      ].join("");
      tpl = tpl.replace(/\$filter\$/gi, filterBody), tpl = tpl.replace(/\$item\$/gi, filterInfo.params[0]), tpl = tpl.replace(/\$args\$/gi, filterInfo.params[1]);
      let fn = new Function("_items,_args", tpl), fnName = "compiledFilter";
      return fn.displayName = fnName, fn.name = this.setFunctionName(fn, fnName), fn;
    }
    compileFilterWithCaching(stopRunningIfCSPSafeIsActive = !1) {
      if (stopRunningIfCSPSafeIsActive)
        return null;
      let filterInfo = this.getFunctionInfo(this.filter), filterPath1 = "{ continue _coreloop; }$1", filterPath2 = "{ _cache[_i] = true;_retval[_idx++] = $item$; continue _coreloop; }$1", filterBody = filterInfo.body.replace(/return false\s*([;}]|\}|$)/gi, filterPath1).replace(/return!1([;}]|\}|$)/gi, filterPath1).replace(/return true\s*([;}]|\}|$)/gi, filterPath2).replace(/return!0([;}]|\}|$)/gi, filterPath2).replace(
        /return ([^;}]+?)\s*([;}]|$)/gi,
        "{ if ((_cache[_i] = $1)) { _retval[_idx++] = $item$; }; continue _coreloop; }$2"
      ), tpl = [
        // 'function(_items, _args, _cache) { ',
        "var _retval = [], _idx = 0; ",
        "var $item$, $args$ = _args; ",
        "_coreloop: ",
        "for (var _i = 0, _il = _items.length; _i < _il; _i++) { ",
        "$item$ = _items[_i]; ",
        "if (_cache[_i]) { ",
        "_retval[_idx++] = $item$; ",
        "continue _coreloop; ",
        "} ",
        "$filter$; ",
        "} ",
        "return _retval; "
        // '}'
      ].join("");
      tpl = tpl.replace(/\$filter\$/gi, filterBody), tpl = tpl.replace(/\$item\$/gi, filterInfo.params[0]), tpl = tpl.replace(/\$args\$/gi, filterInfo.params[1]);
      let fn = new Function("_items,_args,_cache", tpl), fnName = "compiledFilterWithCaching";
      return fn.displayName = fnName, fn.name = this.setFunctionName(fn, fnName), fn;
    }
    compileFilterWithCachingCSPSafe(items, args, filterCache) {
      if (typeof this.filterCSPSafe != "function")
        return [];
      let retval = [], il = items.length;
      for (let _i = 0; _i < il; _i++)
        (filterCache[_i] || this.filterCSPSafe(items[_i], args)) && retval.push(items[_i]);
      return retval;
    }
    /**
     * In ES5 we could set the function name on the fly but in ES6 this is forbidden and we need to set it through differently
     * We can use Object.defineProperty and set it the property to writable, see MDN for reference
     * https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Object/defineProperty
     * @param {*} fn
     * @param {string} fnName
     */
    setFunctionName(fn, fnName) {
      try {
        Object.defineProperty(fn, "name", {
          writable: !0,
          value: fnName
        });
      } catch (err) {
        fn.name = fnName;
      }
    }
    uncompiledFilter(items, args) {
      var _a2;
      let retval = [], idx = 0;
      for (let i = 0, ii = items.length; i < ii; i++)
        (_a2 = this.filter) != null && _a2.call(this, items[i], args) && (retval[idx++] = items[i]);
      return retval;
    }
    uncompiledFilterWithCaching(items, args, cache) {
      var _a2;
      let retval = [], idx = 0, item;
      for (let i = 0, ii = items.length; i < ii; i++)
        item = items[i], cache[i] ? retval[idx++] = item : (_a2 = this.filter) != null && _a2.call(this, item, args) && (retval[idx++] = item, cache[i] = !0);
      return retval;
    }
    getFilteredAndPagedItems(items) {
      if (this._options.useCSPSafeFilter ? this.filterCSPSafe : this.filter) {
        let batchFilter, batchFilterWithCaching;
        this._options.useCSPSafeFilter ? (batchFilter = this._options.inlineFilters ? this.compiledFilterCSPSafe : this.uncompiledFilter, batchFilterWithCaching = this._options.inlineFilters ? this.compiledFilterWithCachingCSPSafe : this.uncompiledFilterWithCaching) : (batchFilter = this._options.inlineFilters ? this.compiledFilter : this.uncompiledFilter, batchFilterWithCaching = this._options.inlineFilters ? this.compiledFilterWithCaching : this.uncompiledFilterWithCaching), this.refreshHints.isFilterNarrowing ? this.filteredItems = batchFilter.call(this, this.filteredItems, this.filterArgs) : this.refreshHints.isFilterExpanding ? this.filteredItems = batchFilterWithCaching.call(this, items, this.filterArgs, this.filterCache) : this.refreshHints.isFilterUnchanged || (this.filteredItems = batchFilter.call(this, items, this.filterArgs));
      } else
        this.filteredItems = this.pagesize ? items : items.concat();
      let paged;
      return this.pagesize ? (this.filteredItems.length <= this.pagenum * this.pagesize && (this.filteredItems.length === 0 ? this.pagenum = 0 : this.pagenum = Math.floor((this.filteredItems.length - 1) / this.pagesize)), paged = this.filteredItems.slice(this.pagesize * this.pagenum, this.pagesize * this.pagenum + this.pagesize)) : paged = this.filteredItems, { totalRows: this.filteredItems.length, rows: paged };
    }
    getRowDiffs(rows, newRows) {
      var _a2, _b2, _c;
      let item, r, eitherIsNonData, diff = [], from = 0, to = Math.max(newRows.length, rows.length);
      (_a2 = this.refreshHints) != null && _a2.ignoreDiffsBefore && (from = Math.max(
        0,
        Math.min(newRows.length, this.refreshHints.ignoreDiffsBefore)
      )), (_b2 = this.refreshHints) != null && _b2.ignoreDiffsAfter && (to = Math.min(
        newRows.length,
        Math.max(0, this.refreshHints.ignoreDiffsAfter)
      ));
      for (let i = from, rl = rows.length; i < to; i++)
        i >= rl ? diff[diff.length] = i : (item = newRows[i], r = rows[i], (!item || this.groupingInfos.length && (eitherIsNonData = item.__nonDataRow || r.__nonDataRow) && item.__group !== r.__group || item.__group && !item.equals(r) || eitherIsNonData && // no good way to compare totals since they are arbitrary DTOs
        // deep object comparison is pretty expensive
        // always considering them 'dirty' seems easier for the time being
        (item.__groupTotals || r.__groupTotals) || item[this.idProperty] !== r[this.idProperty] || (_c = this.updated) != null && _c[item[this.idProperty]]) && (diff[diff.length] = i));
      return diff;
    }
    recalc(_items) {
      this.rowsById = void 0, (this.refreshHints.isFilterNarrowing !== this.prevRefreshHints.isFilterNarrowing || this.refreshHints.isFilterExpanding !== this.prevRefreshHints.isFilterExpanding) && (this.filterCache = []);
      let filteredItems = this.getFilteredAndPagedItems(_items);
      this.totalRows = filteredItems.totalRows;
      let newRows = filteredItems.rows;
      this.groups = [], this.groupingInfos.length && (this.groups = this.extractGroups(newRows), this.groups.length && (newRows = this.flattenGroupedRows(this.groups)));
      let diff = this.getRowDiffs(this.rows, newRows);
      return this.rows = newRows, diff;
    }
    refresh() {
      if (this.suspend)
        return;
      let previousPagingInfo = Utils.extend(!0, {}, this.getPagingInfo()), countBefore = this.rows.length, totalRowsBefore = this.totalRows, diff = this.recalc(this.items);
      this.pagesize && this.totalRows < this.pagenum * this.pagesize && (this.pagenum = Math.max(0, Math.ceil(this.totalRows / this.pagesize) - 1), diff = this.recalc(this.items)), this.updated = null, this.prevRefreshHints = this.refreshHints, this.refreshHints = {}, totalRowsBefore !== this.totalRows && this.onBeforePagingInfoChanged.notify(previousPagingInfo, null, this).getReturnValue() !== !1 && this.onPagingInfoChanged.notify(this.getPagingInfo(), null, this), countBefore !== this.rows.length && this.onRowCountChanged.notify({ previous: countBefore, current: this.rows.length, itemCount: this.items.length, dataView: this, callingOnRowsChanged: diff.length > 0 }, null, this), diff.length > 0 && this.onRowsChanged.notify({ rows: diff, itemCount: this.items.length, dataView: this, calledOnRowCountChanged: countBefore !== this.rows.length }, null, this), (countBefore !== this.rows.length || diff.length > 0) && this.onRowsOrCountChanged.notify({
        rowsDiff: diff,
        previousRowCount: countBefore,
        currentRowCount: this.rows.length,
        itemCount: this.items.length,
        rowCountChanged: countBefore !== this.rows.length,
        rowsChanged: diff.length > 0,
        dataView: this
      }, null, this);
    }
    /**
     * Wires the grid and the DataView together to keep row selection tied to item ids.
     * This is useful since, without it, the grid only knows about rows, so if the items
     * move around, the same rows stay selected instead of the selection moving along
     * with the items.
     *
     * NOTE:  This doesn't work with cell selection model.
     *
     * @param {SlickGrid} grid - The grid to sync selection with.
     * @param {Boolean} preserveHidden - Whether to keep selected items that go out of the
     *     view due to them getting filtered out.
     * @param {Boolean} [preserveHiddenOnSelectionChange] - Whether to keep selected items
     *     that are currently out of the view (see preserveHidden) as selected when selection
     *     changes.
     * @return {Event} An event that notifies when an internal list of selected row ids
     *     changes.  This is useful since, in combination with the above two options, it allows
     *     access to the full list selected row ids, and not just the ones visible to the grid.
     * @method syncGridSelection
     */
    syncGridSelection(grid, preserveHidden, preserveHiddenOnSelectionChange) {
      this._grid = grid;
      let inHandler;
      this.selectedRowIds = this.mapRowsToIds(grid.getSelectedRows());
      let setSelectedRowIds = (rowIds) => {
        rowIds === !1 ? this.selectedRowIds = [] : this.selectedRowIds.sort().join(",") !== rowIds.sort().join(",") && (this.selectedRowIds = rowIds);
      }, update = () => {
        if ((this.selectedRowIds || []).length > 0 && !inHandler) {
          inHandler = !0;
          let selectedRows = this.mapIdsToRows(this.selectedRowIds || []);
          if (!preserveHidden) {
            let selectedRowsChangedArgs = {
              grid: this._grid,
              ids: this.mapRowsToIds(selectedRows),
              rows: selectedRows,
              dataView: this
            };
            this.preSelectedRowIdsChangeFn(selectedRowsChangedArgs), this.onSelectedRowIdsChanged.notify(Object.assign(selectedRowsChangedArgs, {
              selectedRowIds: this.selectedRowIds,
              filteredIds: this.getAllSelectedFilteredIds()
            }), new SlickEventData(), this);
          }
          grid.setSelectedRows(selectedRows), inHandler = !1;
        }
      };
      return grid.onSelectedRowsChanged.subscribe((_e, args) => {
        if (!inHandler) {
          let newSelectedRowIds = this.mapRowsToIds(args.rows), selectedRowsChangedArgs = {
            grid: this._grid,
            ids: newSelectedRowIds,
            rows: args.rows,
            added: !0,
            dataView: this
          };
          this.preSelectedRowIdsChangeFn(selectedRowsChangedArgs), this.onSelectedRowIdsChanged.notify(Object.assign(selectedRowsChangedArgs, {
            selectedRowIds: this.selectedRowIds,
            filteredIds: this.getAllSelectedFilteredIds()
          }), new SlickEventData(), this);
        }
      }), this.preSelectedRowIdsChangeFn = (args) => {
        var _a2;
        if (!inHandler) {
          if (inHandler = !0, typeof args.added == "undefined")
            setSelectedRowIds(args.ids);
          else {
            let rowIds;
            args.added ? preserveHiddenOnSelectionChange && grid.getOptions().multiSelect ? rowIds = ((_a2 = this.selectedRowIds) == null ? void 0 : _a2.filter((id) => this.getRowById(id) === void 0)).concat(args.ids) : rowIds = args.ids : preserveHiddenOnSelectionChange && grid.getOptions().multiSelect ? rowIds = this.selectedRowIds.filter((id) => args.ids.indexOf(id) === -1) : rowIds = [], setSelectedRowIds(rowIds);
          }
          inHandler = !1;
        }
      }, this.onRowsOrCountChanged.subscribe(update.bind(this)), this.onSelectedRowIdsChanged;
    }
    /**
     * Get all selected IDs
     * Note: when using Pagination it will also include hidden selections assuming `preserveHiddenOnSelectionChange` is set to true.
     */
    getAllSelectedIds() {
      return this.selectedRowIds;
    }
    /**
     * Get all selected filtered IDs (similar to "getAllSelectedIds" but only return filtered data)
     * Note: when using Pagination it will also include hidden selections assuming `preserveHiddenOnSelectionChange` is set to true.
     */
    getAllSelectedFilteredIds() {
      return this.getAllSelectedFilteredItems().map((item) => item[this.idProperty]);
    }
    /**
     * Set current row selected IDs array (regardless of Pagination)
     * NOTE: This will NOT change the selection in the grid, if you need to do that then you still need to call
     * "grid.setSelectedRows(rows)"
     * @param {Array} selectedIds - list of IDs which have been selected for this action
     * @param {Object} options
     *  - `isRowBeingAdded`: defaults to true, are the new selected IDs being added (or removed) as new row selections
     *  - `shouldTriggerEvent`: defaults to true, should we trigger `onSelectedRowIdsChanged` event
     *  - `applyRowSelectionToGrid`: defaults to true, should we apply the row selections to the grid in the UI
     */
    setSelectedIds(selectedIds, options) {
      var _a2;
      let isRowBeingAdded = options == null ? void 0 : options.isRowBeingAdded, shouldTriggerEvent = options == null ? void 0 : options.shouldTriggerEvent, applyRowSelectionToGrid = options == null ? void 0 : options.applyRowSelectionToGrid;
      isRowBeingAdded !== !1 && (isRowBeingAdded = !0);
      let selectedRows = this.mapIdsToRows(selectedIds), selectedRowsChangedArgs = {
        grid: this._grid,
        ids: selectedIds,
        rows: selectedRows,
        added: isRowBeingAdded,
        dataView: this
      };
      (_a2 = this.preSelectedRowIdsChangeFn) == null || _a2.call(this, selectedRowsChangedArgs), shouldTriggerEvent !== !1 && this.onSelectedRowIdsChanged.notify(Object.assign(selectedRowsChangedArgs, {
        selectedRowIds: this.selectedRowIds,
        filteredIds: this.getAllSelectedFilteredIds()
      }), new SlickEventData(), this), applyRowSelectionToGrid !== !1 && this._grid && this._grid.setSelectedRows(selectedRows);
    }
    /**
     * Get all selected dataContext items
     * Note: when using Pagination it will also include hidden selections assuming `preserveHiddenOnSelectionChange` is set to true.
     */
    getAllSelectedItems() {
      let selectedData = [];
      return this.getAllSelectedIds().forEach((id) => {
        selectedData.push(this.getItemById(id));
      }), selectedData;
    }
    /**
    * Get all selected filtered dataContext items (similar to "getAllSelectedItems" but only return filtered data)
    * Note: when using Pagination it will also include hidden selections assuming `preserveHiddenOnSelectionChange` is set to true.
    */
    getAllSelectedFilteredItems() {
      return Array.isArray(this.selectedRowIds) ? this.filteredItems.filter((a) => this.selectedRowIds.some((b) => a[this.idProperty] === b)) || [] : [];
    }
    syncGridCellCssStyles(grid, key) {
      let hashById, inHandler, storeCellCssStyles = (hash) => {
        hashById = {};
        for (let row in hash)
          if (hash) {
            let id = this.rows[row][this.idProperty];
            hashById[id] = hash[row];
          }
      };
      storeCellCssStyles(grid.getCellCssStyles(key));
      let update = () => {
        var _a2;
        if (hashById) {
          inHandler = !0, this.ensureRowsByIdCache();
          let newHash = {};
          for (let id in hashById)
            if (hashById) {
              let row = (_a2 = this.rowsById) == null ? void 0 : _a2[id];
              Utils.isDefined(row) && (newHash[row] = hashById[id]);
            }
          grid.setCellCssStyles(key, newHash), inHandler = !1;
        }
      };
      grid.onCellCssStylesChanged.subscribe((_e, args) => {
        inHandler || key === args.key && (args.hash ? storeCellCssStyles(args.hash) : (grid.onCellCssStylesChanged.unsubscribe(), this.onRowsOrCountChanged.unsubscribe(update)));
      }), this.onRowsOrCountChanged.subscribe(update.bind(this));
    }
  }, AvgAggregator = class {
    constructor(field) {
      __publicField(this, "_nonNullCount", 0);
      __publicField(this, "_sum", 0);
      __publicField(this, "_field");
      __publicField(this, "_type", "avg");
      this._field = field;
    }
    get field() {
      return this._field;
    }
    get type() {
      return this._type;
    }
    init() {
      this._nonNullCount = 0, this._sum = 0;
    }
    accumulate(item) {
      let val = item != null && item.hasOwnProperty(this._field) ? item[this._field] : null;
      val !== null && val !== "" && !isNaN(val) && (this._nonNullCount++, this._sum += parseFloat(val));
    }
    storeResult(groupTotals) {
      (!groupTotals || groupTotals[this._type] === void 0) && (groupTotals[this._type] = {}), this._nonNullCount !== 0 && (groupTotals[this._type][this._field] = this._sum / this._nonNullCount);
    }
  }, MinAggregator = class {
    constructor(field) {
      __publicField(this, "_min", null);
      __publicField(this, "_field");
      __publicField(this, "_type", "min");
      this._field = field;
    }
    get field() {
      return this._field;
    }
    get type() {
      return this._type;
    }
    init() {
      this._min = null;
    }
    accumulate(item) {
      let val = item != null && item.hasOwnProperty(this._field) ? item[this._field] : null;
      val !== null && val !== "" && !isNaN(val) && (this._min === null || val < this._min) && (this._min = parseFloat(val));
    }
    storeResult(groupTotals) {
      (!groupTotals || groupTotals[this._type] === void 0) && (groupTotals[this._type] = {}), groupTotals[this._type][this._field] = this._min;
    }
  }, MaxAggregator = class {
    constructor(field) {
      __publicField(this, "_max", null);
      __publicField(this, "_field");
      __publicField(this, "_type", "max");
      this._field = field;
    }
    get field() {
      return this._field;
    }
    get type() {
      return this._type;
    }
    init() {
      this._max = null;
    }
    accumulate(item) {
      let val = item != null && item.hasOwnProperty(this._field) ? item[this._field] : null;
      val !== null && val !== "" && !isNaN(val) && (this._max === null || val > this._max) && (this._max = parseFloat(val));
    }
    storeResult(groupTotals) {
      (!groupTotals || groupTotals[this._type] === void 0) && (groupTotals[this._type] = {}), groupTotals[this._type][this._field] = this._max;
    }
  }, SumAggregator = class {
    constructor(field) {
      __publicField(this, "_sum", 0);
      __publicField(this, "_field");
      __publicField(this, "_type", "sum");
      this._field = field;
    }
    get field() {
      return this._field;
    }
    get type() {
      return this._type;
    }
    init() {
      this._sum = 0;
    }
    accumulate(item) {
      let val = item != null && item.hasOwnProperty(this._field) ? item[this._field] : null;
      val !== null && val !== "" && !isNaN(val) && (this._sum += parseFloat(val));
    }
    storeResult(groupTotals) {
      (!groupTotals || groupTotals[this._type] === void 0) && (groupTotals[this._type] = {}), groupTotals[this._type][this._field] = this._sum;
    }
  }, CountAggregator = class {
    constructor(field) {
      __publicField(this, "_field");
      __publicField(this, "_type", "count");
      this._field = field;
    }
    get field() {
      return this._field;
    }
    get type() {
      return this._type;
    }
    init() {
    }
    storeResult(groupTotals) {
      (!groupTotals || groupTotals[this._type] === void 0) && (groupTotals[this._type] = {}), groupTotals[this._type][this._field] = groupTotals.group.rows.length;
    }
  }, Aggregators = {
    Avg: AvgAggregator,
    Min: MinAggregator,
    Max: MaxAggregator,
    Sum: SumAggregator,
    Count: CountAggregator
  };
  window.Slick && (window.Slick.Data = window.Slick.Data || {}, window.Slick.Data.DataView = SlickDataView, window.Slick.Data.Aggregators = Aggregators);
})();
//# sourceMappingURL=slick.dataview.js.map
"use strict";
(() => {
  var __defProp = Object.defineProperty;
  var __defNormalProp = (obj, key, value) => key in obj ? __defProp(obj, key, { enumerable: !0, configurable: !0, writable: !0, value }) : obj[key] = value;
  var __publicField = (obj, key, value) => (__defNormalProp(obj, typeof key != "symbol" ? key + "" : key, value), value);

  // src/slick.groupitemmetadataprovider.ts
  var keyCode = Slick.keyCode, SlickGroup = Slick.Group, Utils = Slick.Utils, SlickGroupItemMetadataProvider = class {
    constructor(inputOptions) {
      __publicField(this, "_grid");
      __publicField(this, "_options");
      __publicField(this, "_defaults", {
        checkboxSelect: !1,
        checkboxSelectCssClass: "slick-group-select-checkbox",
        checkboxSelectPlugin: null,
        groupCssClass: "slick-group",
        groupTitleCssClass: "slick-group-title",
        totalsCssClass: "slick-group-totals",
        groupFocusable: !0,
        totalsFocusable: !1,
        toggleCssClass: "slick-group-toggle",
        toggleExpandedCssClass: "expanded",
        toggleCollapsedCssClass: "collapsed",
        enableExpandCollapse: !0,
        groupFormatter: this.defaultGroupCellFormatter.bind(this),
        totalsFormatter: this.defaultTotalsCellFormatter.bind(this),
        includeHeaderTotals: !1
      });
      this._options = Utils.extend(!0, {}, this._defaults, inputOptions);
    }
    /** Getter of SlickGrid DataView object */
    get dataView() {
      var _a, _b, _c;
      return (_c = (_b = (_a = this._grid) == null ? void 0 : _a.getData) == null ? void 0 : _b.call(_a)) != null ? _c : {};
    }
    getOptions() {
      return this._options;
    }
    setOptions(inputOptions) {
      Utils.extend(!0, this._options, inputOptions);
    }
    defaultGroupCellFormatter(_row, _cell, _value, _columnDef, item) {
      var _a;
      if (!this._options.enableExpandCollapse)
        return item.title;
      let indentation = `${item.level * 15}px`, toggleClass = item.collapsed ? this._options.toggleCollapsedCssClass : this._options.toggleExpandedCssClass, containerElm = document.createDocumentFragment();
      this._options.checkboxSelect && containerElm.appendChild(Utils.createDomElement("span", { className: `${this._options.checkboxSelectCssClass} ${item.selectChecked ? "checked" : "unchecked"}` })), containerElm.appendChild(Utils.createDomElement("span", {
        className: `${this._options.toggleCssClass} ${toggleClass}`,
        ariaExpanded: String(!item.collapsed),
        style: { marginLeft: indentation }
      }));
      let groupTitleElm = Utils.createDomElement("span", { className: this._options.groupTitleCssClass || "" });
      return groupTitleElm.setAttribute("level", item.level), item.title instanceof HTMLElement ? groupTitleElm.appendChild(item.title) : this._grid.applyHtmlCode(groupTitleElm, (_a = item.title) != null ? _a : ""), containerElm.appendChild(groupTitleElm), containerElm;
    }
    defaultTotalsCellFormatter(_row, _cell, _value, columnDef, item, grid) {
      var _a, _b;
      return (_b = (_a = columnDef == null ? void 0 : columnDef.groupTotalsFormatter) == null ? void 0 : _a.call(columnDef, item, columnDef, grid)) != null ? _b : "";
    }
    init(grid) {
      this._grid = grid, this._grid.onClick.subscribe(this.handleGridClick.bind(this)), this._grid.onKeyDown.subscribe(this.handleGridKeyDown.bind(this));
    }
    destroy() {
      this._grid && (this._grid.onClick.unsubscribe(this.handleGridClick.bind(this)), this._grid.onKeyDown.unsubscribe(this.handleGridKeyDown.bind(this)));
    }
    handleGridClick(e, args) {
      let target = e.target, item = this._grid.getDataItem(args.row);
      if (item && item instanceof SlickGroup && target.classList.contains(this._options.toggleCssClass || "") && (this.handleDataViewExpandOrCollapse(item), e.stopImmediatePropagation(), e.preventDefault()), item && item instanceof SlickGroup && target.classList.contains(this._options.checkboxSelectCssClass || "")) {
        item.selectChecked = !item.selectChecked, target.classList.remove(item.selectChecked ? "unchecked" : "checked"), target.classList.add(item.selectChecked ? "checked" : "unchecked");
        let rowIndexes = this.dataView.mapItemsToRows(item.rows);
        (item.selectChecked ? this._options.checkboxSelectPlugin.selectRows : this._options.checkboxSelectPlugin.deSelectRows)(rowIndexes);
      }
    }
    // TODO:  add -/+ handling
    handleGridKeyDown(e) {
      if (this._options.enableExpandCollapse && e.which === keyCode.SPACE) {
        let activeCell = this._grid.getActiveCell();
        if (activeCell) {
          let item = this._grid.getDataItem(activeCell.row);
          item && item instanceof SlickGroup && (this.handleDataViewExpandOrCollapse(item), e.stopImmediatePropagation(), e.preventDefault());
        }
      }
    }
    handleDataViewExpandOrCollapse(item) {
      let range = this._grid.getRenderedRange();
      this.dataView.setRefreshHints({
        ignoreDiffsBefore: range.top,
        ignoreDiffsAfter: range.bottom + 1
      }), item.collapsed ? this.dataView.expandGroup(item.groupingKey) : this.dataView.collapseGroup(item.groupingKey);
    }
    getGroupRowMetadata(item) {
      let groupLevel = item == null ? void 0 : item.level;
      return {
        selectable: !1,
        focusable: this._options.groupFocusable,
        cssClasses: `${this._options.groupCssClass} slick-group-level-${groupLevel}`,
        formatter: this._options.includeHeaderTotals && this._options.totalsFormatter || void 0,
        columns: {
          0: {
            colspan: this._options.includeHeaderTotals ? "1" : "*",
            formatter: this._options.groupFormatter,
            editor: null
          }
        }
      };
    }
    getTotalsRowMetadata(item) {
      var _a;
      let groupLevel = (_a = item == null ? void 0 : item.group) == null ? void 0 : _a.level;
      return {
        selectable: !1,
        focusable: this._options.totalsFocusable,
        cssClasses: `${this._options.totalsCssClass} slick-group-level-${groupLevel}`,
        formatter: this._options.totalsFormatter,
        editor: null
      };
    }
  };
  window.Slick && (window.Slick.Data = window.Slick.Data || {}, window.Slick.Data.GroupItemMetadataProvider = SlickGroupItemMetadataProvider);
})();
//# sourceMappingURL=slick.groupitemmetadataprovider.js.map
"use strict";
(() => {
  var __defProp = Object.defineProperty;
  var __defNormalProp = (obj, key, value) => key in obj ? __defProp(obj, key, { enumerable: !0, configurable: !0, writable: !0, value }) : obj[key] = value;
  var __publicField = (obj, key, value) => (__defNormalProp(obj, typeof key != "symbol" ? key + "" : key, value), value);

  // src/slick.remotemodel.ts
  var SlickRemoteModel = class {
    constructor() {
      // private
      __publicField(this, "PAGESIZE", 50);
      __publicField(this, "data", { length: 0 });
      __publicField(this, "searchstr", "");
      __publicField(this, "sortcol", null);
      __publicField(this, "sortdir", 1);
      __publicField(this, "h_request", null);
      __publicField(this, "req", null);
      // ajax request
      // events
      __publicField(this, "onDataLoading", new Slick.Event());
      __publicField(this, "onDataLoaded", new Slick.Event());
      if (!(window.$ || window.jQuery) || !window.$.jsonp)
        throw new Error("SlickRemoteModel requires both jQuery and jQuery jsonp library to be loaded.");
      this.init();
    }
    init() {
    }
    isDataLoaded(from, to) {
      for (let i = from; i <= to; i++)
        if (this.data[i] === void 0 || this.data[i] === null)
          return !1;
      return !0;
    }
    clear() {
      for (let key in this.data)
        delete this.data[key];
      this.data.length = 0;
    }
    ensureData(from, to) {
      if (this.req) {
        this.req.abort();
        for (let i = this.req.fromPage; i <= this.req.toPage; i++)
          this.data[i * this.PAGESIZE] = void 0;
      }
      from < 0 && (from = 0), this.data.length > 0 && (to = Math.min(to, this.data.length - 1));
      let fromPage = Math.floor(from / this.PAGESIZE), toPage = Math.floor(to / this.PAGESIZE);
      for (; this.data[fromPage * this.PAGESIZE] !== void 0 && fromPage < toPage; )
        fromPage++;
      for (; this.data[toPage * this.PAGESIZE] !== void 0 && fromPage < toPage; )
        toPage--;
      if (fromPage > toPage || fromPage === toPage && this.data[fromPage * this.PAGESIZE] !== void 0) {
        this.onDataLoaded.notify({ from, to });
        return;
      }
      let url = "http://octopart.com/api/v3/parts/search?apikey=68b25f31&include[]=short_description&show[]=uid&show[]=manufacturer&show[]=mpn&show[]=brand&show[]=octopart_url&show[]=short_description&q=" + this.searchstr + "&start=" + fromPage * this.PAGESIZE + "&limit=" + ((toPage - fromPage) * this.PAGESIZE + this.PAGESIZE);
      this.sortcol !== null && (url += "&sortby=" + this.sortcol + (this.sortdir > 0 ? "+asc" : "+desc")), this.h_request !== null && clearTimeout(this.h_request), this.h_request = setTimeout(() => {
        for (let i = fromPage; i <= toPage; i++)
          this.data[i * this.PAGESIZE] = null;
        this.onDataLoading.notify({ from, to }), this.req = window.$.jsonp({
          url,
          callbackParameter: "callback",
          cache: !0,
          success: this.onSuccess,
          error: () => this.onError(fromPage, toPage)
        }), this.req.fromPage = fromPage, this.req.toPage = toPage;
      }, 50);
    }
    onError(fromPage, toPage) {
      alert("error loading pages " + fromPage + " to " + toPage);
    }
    onSuccess(resp) {
      let from = resp.request.start, to = from + resp.results.length;
      this.data.length = Math.min(parseInt(resp.hits), 1e3);
      for (let i = 0; i < resp.results.length; i++) {
        let item = resp.results[i].item;
        this.data[from + i] = item, this.data[from + i].index = from + i;
      }
      this.req = null, this.onDataLoaded.notify({ from, to });
    }
    reloadData(from, to) {
      for (let i = from; i <= to; i++)
        delete this.data[i];
      this.ensureData(from, to);
    }
    setSort(column, dir) {
      this.sortcol = column, this.sortdir = dir, this.clear();
    }
    setSearch(str) {
      this.searchstr = str, this.clear();
    }
  };
  window.Slick && (window.Slick.Data = window.Slick.Data || {}, window.Slick.Data.RemoteModel = SlickRemoteModel);
})();
//# sourceMappingURL=slick.remotemodel.js.map
"use strict";
(() => {
  var __defProp = Object.defineProperty;
  var __defNormalProp = (obj, key, value) => key in obj ? __defProp(obj, key, { enumerable: !0, configurable: !0, writable: !0, value }) : obj[key] = value;
  var __publicField = (obj, key, value) => (__defNormalProp(obj, typeof key != "symbol" ? key + "" : key, value), value);

  // src/slick.remotemodel-yahoo.ts
  var SlickRemoteModelYahoo = class {
    constructor() {
      // protected
      __publicField(this, "PAGESIZE", 10);
      __publicField(this, "data", { length: 0 });
      __publicField(this, "h_request", null);
      __publicField(this, "req", null);
      // ajax request
      // events
      __publicField(this, "onDataLoading", new Slick.Event());
      __publicField(this, "onDataLoaded", new Slick.Event());
      if (!(window.$ || window.jQuery) || !window.$.jsonp)
        throw new Error("SlickRemoteModel requires both jQuery and jQuery jsonp library to be loaded.");
      this.init();
    }
    init() {
    }
    isDataLoaded(from, to) {
      for (let i = from; i <= to; i++)
        if (this.data[i] === void 0 || this.data[i] === null)
          return !1;
      return !0;
    }
    clear() {
      for (let key in this.data)
        delete this.data[key];
      this.data.length = 0;
    }
    ensureData(from, to) {
      if (this.req) {
        this.req.abort();
        for (let i = this.req.fromPage; i <= this.req.toPage; i++)
          this.data[i * this.PAGESIZE] = void 0;
      }
      from < 0 && (from = 0), this.data.length > 0 && (to = Math.min(to, this.data.length - 1));
      let fromPage = Math.floor(from / this.PAGESIZE), toPage = Math.floor(to / this.PAGESIZE);
      for (; this.data[fromPage * this.PAGESIZE] !== void 0 && fromPage < toPage; )
        fromPage++;
      for (; this.data[toPage * this.PAGESIZE] !== void 0 && fromPage < toPage; )
        toPage--;
      if (fromPage > toPage || fromPage === toPage && this.data[fromPage * this.PAGESIZE] !== void 0) {
        this.onDataLoaded.notify({ from, to });
        return;
      }
      let recStart = fromPage * this.PAGESIZE, recCount = (toPage - fromPage) * this.PAGESIZE + this.PAGESIZE, url = "https://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20rss(" + recStart + "%2C" + recCount + ")%20where%20url%3D%22http%3A%2F%2Frss.news.yahoo.com%2Frss%2Ftopstories%22&format=json";
      this.h_request !== null && clearTimeout(this.h_request), this.h_request = setTimeout(() => {
        for (let i = fromPage; i <= toPage; i++)
          this.data[i * this.PAGESIZE] = null;
        this.onDataLoading.notify({ from, to }), this.req = window.$.jsonp({
          url,
          callbackParameter: "callback",
          cache: !0,
          success: (json) => {
            this.onSuccess(json, recStart);
          },
          error: () => {
            this.onError(fromPage, toPage);
          }
        }), this.req.fromPage = fromPage, this.req.toPage = toPage;
      }, 50);
    }
    onError(fromPage, toPage) {
      alert("error loading pages " + fromPage + " to " + toPage);
    }
    // SAMPLE DATA
    //    {
    //      "query": {
    //        "count": 40,
    //        "created": "2015-03-03T00:34:00Z",
    //        "lang": "en-US",
    //        "results": {
    //          "item": [
    //            {
    //              "title": "Netanyahu assails Iran deal, touts US-Israel ties",
    //              "description": "<p><a href=\"http://news.yahoo.com/netanyahu-us-officials-face-off-iran-133539021--politics.html\"><img src=\"http://l2.yimg.com/bt/api/res/1.2/4eoBxbJStrbGAKbmBYOJfg--/YXBwaWQ9eW5ld3M7Zmk9ZmlsbDtoPTg2O3E9NzU7dz0xMzA-/http://media.zenfs.com/en_us/News/ap_webfeeds/2f3a20c2d46d9f096f0f6a706700d430.jpg\" width=\"130\" height=\"86\" alt=\"Israeli Prime Minister Benjamin Netanyahu addresses the 2015 American Israel Public Affairs Committee (AIPAC) Policy Conference in Washington, Monday, March 2, 2015. (AP Photo/Cliff Owen)\" align=\"left\" title=\"Israeli Prime Minister Benjamin Netanyahu addresses the 2015 American Israel Public Affairs Committee (AIPAC) Policy Conference in Washington, Monday, March 2, 2015. (AP Photo/Cliff Owen)\" border=\"0\" /></a>WASHINGTON (AP) — Seeking to lower tensions, Benjamin Netanyahu and U.S. officials cast their dispute over Iran as a family squabble on Monday, even as the Israeli leader claimed President Barack Obama did not — and could not — fully understand his nation&#039;s vital security concerns.</p><br clear=\"all\"/>",
    //              "link": "http://news.yahoo.com/netanyahu-us-officials-face-off-iran-133539021--politics.html",
    //              "pubDate": "Mon, 02 Mar 2015 19:17:36 -0500",
    //              "source": {
    //                "url": "http://www.ap.org/",
    //                "content": "Associated Press"
    //              },
    //              "guid": {
    //                "isPermaLink": "false",
    //                "content": "netanyahu-us-officials-face-off-iran-133539021--politics"
    //              },
    //              "content": {
    //                "height": "86",
    //                "type": "image/jpeg",
    //                "url": "http://l2.yimg.com/bt/api/res/1.2/4eoBxbJStrbGAKbmBYOJfg--/YXBwaWQ9eW5ld3M7Zmk9ZmlsbDtoPTg2O3E9NzU7dz0xMzA-/http://media.zenfs.com/en_us/News/ap_webfeeds/2f3a20c2d46d9f096f0f6a706700d430.jpg",
    //                "width": "130"
    //              },
    //              "text": {
    //                "type": "html",
    //                "content": "<p><a href=\"http://news.yahoo.com/netanyahu-us-officials-face-off-iran-133539021--politics.html\"><img src=\"http://l2.yimg.com/bt/api/res/1.2/4eoBxbJStrbGAKbmBYOJfg--/YXBwaWQ9eW5ld3M7Zmk9ZmlsbDtoPTg2O3E9NzU7dz0xMzA-/http://media.zenfs.com/en_us/News/ap_webfeeds/2f3a20c2d46d9f096f0f6a706700d430.jpg\" width=\"130\" height=\"86\" alt=\"Israeli Prime Minister Benjamin Netanyahu addresses the 2015 American Israel Public Affairs Committee (AIPAC) Policy Conference in Washington, Monday, March 2, 2015. (AP Photo/Cliff Owen)\" align=\"left\" title=\"Israeli Prime Minister Benjamin Netanyahu addresses the 2015 American Israel Public Affairs Committee (AIPAC) Policy Conference in Washington, Monday, March 2, 2015. (AP Photo/Cliff Owen)\" border=\"0\" /></a>WASHINGTON (AP) — Seeking to lower tensions, Benjamin Netanyahu and U.S. officials cast their dispute over Iran as a family squabble on Monday, even as the Israeli leader claimed President Barack Obama did not — and could not — fully understand his nation&#039;s vital security concerns.</p><br clear=\"all\"/>"
    //              },
    //              "credit": {
    //                "role": "publishing company"
    //              }
    //            },
    //            {... },
    //            {... },
    //          ]
    //        }
    //      }
    //    }
    onSuccess(json, recStart) {
      let recEnd = recStart;
      if (json.query.count > 0) {
        let results = json.query.results.item;
        recEnd = recStart + results.length, this.data.length = 100;
        for (let i = 0; i < results.length; i++) {
          let item = results[i];
          item.pubDate = new Date(item.pubDate), this.data[recStart + i] = { index: recStart + i }, this.data[recStart + i].pubDate = item.pubDate, this.data[recStart + i].title = item.title, this.data[recStart + i].url = item.link, this.data[recStart + i].text = item.description;
        }
      }
      this.req = null, this.onDataLoaded.notify({ from: recStart, to: recEnd });
    }
    reloadData(from, to) {
      for (let i = from; i <= to; i++)
        delete this.data[i];
      this.ensureData(from, to);
    }
    // return {
    //   // properties
    //   "data": data,
    //   // methods
    //   "clear": clear,
    //   "isDataLoaded": isDataLoaded,
    //   "ensureData": ensureData,
    //   "reloadData": reloadData,
    //   // events
    //   "onDataLoading": onDataLoading,
    //   "onDataLoaded": onDataLoaded
    // };
  };
  window.Slick && (window.Slick.Data = window.Slick.Data || {}, window.Slick.Data.RemoteModelYahoo = SlickRemoteModelYahoo);
})();
//# sourceMappingURL=slick.remotemodel-yahoo.js.map
"use strict";
(() => {
  var __defProp = Object.defineProperty;
  var __defNormalProp = (obj, key, value) => key in obj ? __defProp(obj, key, { enumerable: !0, configurable: !0, writable: !0, value }) : obj[key] = value;
  var __publicField = (obj, key, value) => (__defNormalProp(obj, typeof key != "symbol" ? key + "" : key, value), value);

  // src/plugins/slick.checkboxselectcolumn.ts
  var BindingEventService = Slick.BindingEventService, SlickEventHandler = Slick.EventHandler, Utils = Slick.Utils, SlickCheckboxSelectColumn = class {
    constructor(options) {
      // --
      // public API
      __publicField(this, "pluginName", "CheckboxSelectColumn");
      // --
      // protected props
      __publicField(this, "_dataView");
      __publicField(this, "_grid");
      __publicField(this, "_isUsingDataView", !1);
      __publicField(this, "_selectableOverride", null);
      __publicField(this, "_headerRowNode");
      __publicField(this, "_selectAll_UID");
      __publicField(this, "_handler", new SlickEventHandler());
      __publicField(this, "_selectedRowsLookup", {});
      __publicField(this, "_checkboxColumnCellIndex", null);
      __publicField(this, "_options");
      __publicField(this, "_defaults", {
        columnId: "_checkbox_selector",
        cssClass: void 0,
        hideSelectAllCheckbox: !1,
        toolTip: "Select/Deselect All",
        width: 30,
        applySelectOnAllPages: !1,
        // defaults to false, when that is enabled the "Select All" will be applied to all pages (when using Pagination)
        hideInColumnTitleRow: !1,
        hideInFilterHeaderRow: !0
      });
      __publicField(this, "_isSelectAllChecked", !1);
      __publicField(this, "_bindingEventService");
      this._bindingEventService = new BindingEventService(), this._options = Utils.extend(!0, {}, this._defaults, options), this._selectAll_UID = this.createUID(), typeof this._options.selectableOverride == "function" && this.selectableOverride(this._options.selectableOverride);
    }
    init(grid) {
      this._grid = grid, this._isUsingDataView = !Array.isArray(grid.getData()), this._isUsingDataView && (this._dataView = grid.getData()), this._handler.subscribe(this._grid.onSelectedRowsChanged, this.handleSelectedRowsChanged.bind(this)).subscribe(this._grid.onClick, this.handleClick.bind(this)).subscribe(this._grid.onKeyDown, this.handleKeyDown.bind(this)), this._isUsingDataView && this._dataView && this._options.applySelectOnAllPages && this._handler.subscribe(this._dataView.onSelectedRowIdsChanged, this.handleDataViewSelectedIdsChanged.bind(this)).subscribe(this._dataView.onPagingInfoChanged, this.handleDataViewSelectedIdsChanged.bind(this)), this._options.hideInFilterHeaderRow || this.addCheckboxToFilterHeaderRow(grid), this._options.hideInColumnTitleRow || this._handler.subscribe(this._grid.onHeaderClick, this.handleHeaderClick.bind(this));
    }
    destroy() {
      this._handler.unsubscribeAll(), this._bindingEventService.unbindAll();
    }
    getOptions() {
      return this._options;
    }
    setOptions(options) {
      var _a;
      if (this._options = Utils.extend(!0, {}, this._options, options), this._options.hideSelectAllCheckbox)
        this.hideSelectAllFromColumnHeaderTitleRow(), this.hideSelectAllFromColumnHeaderFilterRow();
      else if (this._options.hideInColumnTitleRow ? this.hideSelectAllFromColumnHeaderTitleRow() : (this.renderSelectAllCheckbox(this._isSelectAllChecked), this._handler.subscribe(this._grid.onHeaderClick, this.handleHeaderClick.bind(this))), this._options.hideInFilterHeaderRow)
        this.hideSelectAllFromColumnHeaderFilterRow();
      else {
        let selectAllContainerElm = (_a = this._headerRowNode) == null ? void 0 : _a.querySelector("#filter-checkbox-selectall-container");
        if (selectAllContainerElm) {
          selectAllContainerElm.style.display = "flex";
          let selectAllInputElm = selectAllContainerElm.querySelector('input[type="checkbox"]');
          selectAllInputElm && (selectAllInputElm.checked = this._isSelectAllChecked);
        }
      }
    }
    hideSelectAllFromColumnHeaderTitleRow() {
      this._grid.updateColumnHeader(this._options.columnId || "", "", "");
    }
    hideSelectAllFromColumnHeaderFilterRow() {
      var _a;
      let selectAllContainerElm = (_a = this._headerRowNode) == null ? void 0 : _a.querySelector("#filter-checkbox-selectall-container");
      selectAllContainerElm && (selectAllContainerElm.style.display = "none");
    }
    handleSelectedRowsChanged() {
      var _a, _b;
      let selectedRows = this._grid.getSelectedRows(), lookup = {}, row = 0, i = 0, k = 0, disabledCount = 0;
      if (typeof this._selectableOverride == "function")
        for (k = 0; k < this._grid.getDataLength(); k++) {
          let dataItem = this._grid.getDataItem(k);
          this.checkSelectableOverride(i, dataItem, this._grid) || disabledCount++;
        }
      let removeList = [];
      for (i = 0; i < selectedRows.length; i++) {
        row = selectedRows[i];
        let rowItem = this._grid.getDataItem(row);
        this.checkSelectableOverride(i, rowItem, this._grid) ? (lookup[row] = !0, lookup[row] !== this._selectedRowsLookup[row] && (this._grid.invalidateRow(row), delete this._selectedRowsLookup[row])) : removeList.push(row);
      }
      for (let selectedRow in this._selectedRowsLookup)
        this._grid.invalidateRow(+selectedRow);
      if (this._selectedRowsLookup = lookup, this._grid.render(), this._isSelectAllChecked = ((_a = selectedRows == null ? void 0 : selectedRows.length) != null ? _a : 0) + disabledCount >= this._grid.getDataLength(), (!this._isUsingDataView || !this._options.applySelectOnAllPages) && (!this._options.hideInColumnTitleRow && !this._options.hideSelectAllCheckbox && this.renderSelectAllCheckbox(this._isSelectAllChecked), !this._options.hideInFilterHeaderRow)) {
        let selectAllElm = (_b = this._headerRowNode) == null ? void 0 : _b.querySelector(`#header-filter-selector${this._selectAll_UID}`);
        selectAllElm && (selectAllElm.checked = this._isSelectAllChecked);
      }
      if (removeList.length > 0) {
        for (i = 0; i < removeList.length; i++) {
          let remIdx = selectedRows.indexOf(removeList[i]);
          selectedRows.splice(remIdx, 1);
        }
        this._grid.setSelectedRows(selectedRows, "click.cleanup");
      }
    }
    handleDataViewSelectedIdsChanged() {
      var _a;
      let selectedIds = this._dataView.getAllSelectedFilteredIds(), filteredItems = this._dataView.getFilteredItems(), disabledCount = 0;
      if (typeof this._selectableOverride == "function" && selectedIds.length > 0)
        for (let k = 0; k < this._dataView.getItemCount(); k++) {
          let dataItem = this._dataView.getItemByIdx(k), idProperty = this._dataView.getIdPropertyName(), dataItemId = dataItem[idProperty];
          filteredItems.findIndex(function(item) {
            return item[idProperty] === dataItemId;
          }) >= 0 && !this.checkSelectableOverride(k, dataItem, this._grid) && disabledCount++;
        }
      if (this._isSelectAllChecked = (selectedIds && selectedIds.length) + disabledCount >= filteredItems.length, !this._options.hideInColumnTitleRow && !this._options.hideSelectAllCheckbox && this.renderSelectAllCheckbox(this._isSelectAllChecked), !this._options.hideInFilterHeaderRow) {
        let selectAllElm = (_a = this._headerRowNode) == null ? void 0 : _a.querySelector(`#header-filter-selector${this._selectAll_UID}`);
        selectAllElm && (selectAllElm.checked = this._isSelectAllChecked);
      }
    }
    handleKeyDown(e, args) {
      e.which === 32 && this._grid.getColumns()[args.cell].id === this._options.columnId && ((!this._grid.getEditorLock().isActive() || this._grid.getEditorLock().commitCurrentEdit()) && this.toggleRowSelection(args.row), e.preventDefault(), e.stopImmediatePropagation());
    }
    handleClick(e, args) {
      if (this._grid.getColumns()[args.cell].id === this._options.columnId && e.target.type === "checkbox") {
        if (this._grid.getEditorLock().isActive() && !this._grid.getEditorLock().commitCurrentEdit()) {
          e.preventDefault(), e.stopImmediatePropagation();
          return;
        }
        this.toggleRowSelection(args.row), e.stopPropagation(), e.stopImmediatePropagation();
      }
    }
    toggleRowSelection(row) {
      let dataContext = this._grid.getDataItem(row);
      if (this.checkSelectableOverride(row, dataContext, this._grid)) {
        if (this._selectedRowsLookup[row]) {
          let newSelectedRows = this._grid.getSelectedRows().filter((n) => n !== row);
          this._grid.setSelectedRows(newSelectedRows, "click.toggle");
        } else
          this._grid.setSelectedRows(this._grid.getSelectedRows().concat(row), "click.toggle");
        this._grid.setActiveCell(row, this.getCheckboxColumnCellIndex());
      }
    }
    selectRows(rowArray) {
      let addRows = [];
      for (let i = 0, l = rowArray.length; i < l; i++)
        this._selectedRowsLookup[rowArray[i]] || (addRows[addRows.length] = rowArray[i]);
      this._grid.setSelectedRows(this._grid.getSelectedRows().concat(addRows), "SlickCheckboxSelectColumn.selectRows");
    }
    deSelectRows(rowArray) {
      let removeRows = [];
      for (let i = 0, l = rowArray.length; i < l; i++)
        this._selectedRowsLookup[rowArray[i]] && (removeRows[removeRows.length] = rowArray[i]);
      this._grid.setSelectedRows(this._grid.getSelectedRows().filter((n) => removeRows.indexOf(n) < 0), "SlickCheckboxSelectColumn.deSelectRows");
    }
    handleHeaderClick(e, args) {
      if (args.column.id === this._options.columnId && e.target.type === "checkbox") {
        if (this._grid.getEditorLock().isActive() && !this._grid.getEditorLock().commitCurrentEdit()) {
          e.preventDefault(), e.stopImmediatePropagation();
          return;
        }
        let isAllSelected = e.target.checked, caller = isAllSelected ? "click.selectAll" : "click.unselectAll", rows = [];
        if (isAllSelected) {
          for (let i = 0; i < this._grid.getDataLength(); i++) {
            let rowItem = this._grid.getDataItem(i);
            !rowItem.__group && !rowItem.__groupTotals && this.checkSelectableOverride(i, rowItem, this._grid) && rows.push(i);
          }
          isAllSelected = !0;
        }
        if (this._isUsingDataView && this._dataView && this._options.applySelectOnAllPages) {
          let ids = [], filteredItems = this._dataView.getFilteredItems();
          for (let j = 0; j < filteredItems.length; j++) {
            let dataviewRowItem = filteredItems[j];
            this.checkSelectableOverride(j, dataviewRowItem, this._grid) && ids.push(dataviewRowItem[this._dataView.getIdPropertyName()]);
          }
          this._dataView.setSelectedIds(ids, { isRowBeingAdded: isAllSelected });
        }
        this._grid.setSelectedRows(rows, caller), e.stopPropagation(), e.stopImmediatePropagation();
      }
    }
    getCheckboxColumnCellIndex() {
      if (this._checkboxColumnCellIndex === null) {
        this._checkboxColumnCellIndex = 0;
        let colArr = this._grid.getColumns();
        for (let i = 0; i < colArr.length; i++)
          colArr[i].id === this._options.columnId && (this._checkboxColumnCellIndex = i);
      }
      return this._checkboxColumnCellIndex;
    }
    getColumnDefinition() {
      var _a, _b, _c;
      return {
        id: this._options.columnId,
        name: this._options.hideSelectAllCheckbox || this._options.hideInColumnTitleRow ? this._options.name : `<input id="header-selector${this._selectAll_UID}" type="checkbox"><label for="header-selector${this._selectAll_UID}"></label>`,
        toolTip: this._options.hideSelectAllCheckbox || this._options.hideInColumnTitleRow ? "" : this._options.toolTip,
        field: "sel",
        width: this._options.width,
        resizable: !1,
        sortable: !1,
        cssClass: this._options.cssClass,
        hideSelectAllCheckbox: this._options.hideSelectAllCheckbox,
        formatter: this.checkboxSelectionFormatter.bind(this),
        // exclude from all menus, defaults to true unless the option is provided differently by the user
        excludeFromColumnPicker: (_a = this._options.excludeFromColumnPicker) != null ? _a : !0,
        excludeFromGridMenu: (_b = this._options.excludeFromGridMenu) != null ? _b : !0,
        excludeFromHeaderMenu: (_c = this._options.excludeFromHeaderMenu) != null ? _c : !0
      };
    }
    addCheckboxToFilterHeaderRow(grid) {
      this._handler.subscribe(grid.onHeaderRowCellRendered, (_e, args) => {
        if (args.column.field === "sel") {
          Utils.emptyElement(args.node);
          let spanElm = document.createElement("span");
          spanElm.id = "filter-checkbox-selectall-container";
          let inputElm = document.createElement("input");
          inputElm.type = "checkbox", inputElm.id = `header-filter-selector${this._selectAll_UID}`;
          let labelElm = document.createElement("label");
          labelElm.htmlFor = `header-filter-selector${this._selectAll_UID}`, spanElm.appendChild(inputElm), spanElm.appendChild(labelElm), args.node.appendChild(spanElm), this._headerRowNode = args.node, this._bindingEventService.bind(spanElm, "click", (e) => this.handleHeaderClick(e, args));
        }
      });
    }
    createUID() {
      return Math.round(1e7 * Math.random());
    }
    checkboxSelectionFormatter(row, _cell, _val, _columnDef, dataContext, grid) {
      let UID = this.createUID() + row;
      return dataContext && this.checkSelectableOverride(row, dataContext, grid) ? this._selectedRowsLookup[row] ? `<input id="selector${UID}" type="checkbox" checked="checked"><label for="selector${UID}"></label>` : `<input id="selector${UID}" type="checkbox"><label for="selector${UID}"></label>` : null;
    }
    checkSelectableOverride(row, dataContext, grid) {
      return typeof this._selectableOverride == "function" ? this._selectableOverride(row, dataContext, grid) : !0;
    }
    renderSelectAllCheckbox(isSelectAllChecked) {
      isSelectAllChecked ? this._grid.updateColumnHeader(this._options.columnId || "", `<input id="header-selector${this._selectAll_UID}" type="checkbox" checked="checked"><label for="header-selector${this._selectAll_UID}"></label>`, this._options.toolTip) : this._grid.updateColumnHeader(this._options.columnId || "", `<input id="header-selector${this._selectAll_UID}" type="checkbox"><label for="header-selector${this._selectAll_UID}"></label>`, this._options.toolTip);
    }
    /**
     * Method that user can pass to override the default behavior or making every row a selectable row.
     * In order word, user can choose which rows to be selectable or not by providing his own logic.
     * @param overrideFn: override function callback
     */
    selectableOverride(overrideFn) {
      this._selectableOverride = overrideFn;
    }
    // Utils.extend(this, {
    //     "init": init,
    //     "destroy": destroy,
    //     "deSelectRows": deSelectRows,
    //     "selectRows": selectRows,
    //     "getColumnDefinition": getColumnDefinition,
    //     "getOptions": getOptions,
    //     "selectableOverride": selectableOverride,
    //     "setOptions": setOptions,
    //   });
  };
  window.Slick && Utils.extend(!0, window, {
    Slick: {
      CheckboxSelectColumn: SlickCheckboxSelectColumn
    }
  });
})();
//# sourceMappingURL=slick.checkboxselectcolumn.js.map
"use strict";
(() => {
  var __defProp = Object.defineProperty;
  var __defNormalProp = (obj, key, value) => key in obj ? __defProp(obj, key, { enumerable: !0, configurable: !0, writable: !0, value }) : obj[key] = value;
  var __publicField = (obj, key, value) => (__defNormalProp(obj, typeof key != "symbol" ? key + "" : key, value), value);

  // src/plugins/slick.cellrangedecorator.ts
  var Utils = Slick.Utils, SlickCellRangeDecorator = class {
    constructor(grid, options) {
      this.grid = grid;
      // --
      // public API
      __publicField(this, "pluginName", "CellRangeDecorator");
      // --
      // protected props
      __publicField(this, "_options");
      __publicField(this, "_elem");
      __publicField(this, "_defaults", {
        selectionCssClass: "slick-range-decorator",
        selectionCss: {
          zIndex: "9999",
          border: "2px dashed red"
        },
        offset: { top: -1, left: -1, height: -2, width: -2 }
      });
      this._options = Utils.extend(!0, {}, this._defaults, options);
    }
    destroy() {
      this.hide();
    }
    init() {
    }
    hide() {
      var _a;
      (_a = this._elem) == null || _a.remove(), this._elem = null;
    }
    show(range) {
      var _a;
      if (!this._elem) {
        this._elem = document.createElement("div"), this._elem.className = this._options.selectionCssClass, Object.keys(this._options.selectionCss).forEach((cssStyleKey) => {
          this._elem.style[cssStyleKey] = this._options.selectionCss[cssStyleKey];
        }), this._elem.style.position = "absolute";
        let canvasNode = this.grid.getActiveCanvasNode();
        canvasNode && canvasNode.appendChild(this._elem);
      }
      let from = this.grid.getCellNodeBox(range.fromRow, range.fromCell), to = this.grid.getCellNodeBox(range.toRow, range.toCell);
      return from && to && ((_a = this._options) != null && _a.offset) && (this._elem.style.top = `${from.top + this._options.offset.top}px`, this._elem.style.left = `${from.left + this._options.offset.left}px`, this._elem.style.height = `${to.bottom - from.top + this._options.offset.height}px`, this._elem.style.width = `${to.right - from.left + this._options.offset.width}px`), this._elem;
    }
  };
  window.Slick && Utils.extend(!0, window, {
    Slick: {
      CellRangeDecorator: SlickCellRangeDecorator
    }
  });
})();
//# sourceMappingURL=slick.cellrangedecorator.js.map
"use strict";
(() => {
  var __defProp = Object.defineProperty;
  var __defNormalProp = (obj, key, value) => key in obj ? __defProp(obj, key, { enumerable: !0, configurable: !0, writable: !0, value }) : obj[key] = value;
  var __publicField = (obj, key, value) => (__defNormalProp(obj, typeof key != "symbol" ? key + "" : key, value), value);

  // src/plugins/slick.cellrangeselector.ts
  var SlickEvent = Slick.Event, SlickEventHandler = Slick.EventHandler, SlickRange = Slick.Range, Draggable = Slick.Draggable, SlickCellRangeDecorator = Slick.CellRangeDecorator, Utils = Slick.Utils, SlickCellRangeSelector = class {
    constructor(options) {
      // --
      // public API
      __publicField(this, "pluginName", "CellRangeSelector");
      __publicField(this, "onBeforeCellRangeSelected", new SlickEvent());
      __publicField(this, "onCellRangeSelected", new SlickEvent());
      __publicField(this, "onCellRangeSelecting", new SlickEvent());
      // --
      // protected props
      __publicField(this, "_grid");
      __publicField(this, "_currentlySelectedRange", null);
      __publicField(this, "_canvas", null);
      __publicField(this, "_decorator");
      __publicField(this, "_gridOptions");
      __publicField(this, "_activeCanvas");
      __publicField(this, "_dragging", !1);
      __publicField(this, "_handler", new SlickEventHandler());
      __publicField(this, "_options");
      __publicField(this, "_defaults", {
        autoScroll: !0,
        minIntervalToShowNextCell: 30,
        maxIntervalToShowNextCell: 600,
        // better to a multiple of minIntervalToShowNextCell
        accelerateInterval: 5,
        // increase 5ms when cursor 1px outside the viewport.
        selectionCss: {
          border: "2px dashed blue"
        }
      });
      // Frozen row & column variables
      __publicField(this, "_rowOffset", 0);
      __publicField(this, "_columnOffset", 0);
      __publicField(this, "_isRightCanvas", !1);
      __publicField(this, "_isBottomCanvas", !1);
      // autoScroll related constiables
      __publicField(this, "_activeViewport");
      __publicField(this, "_autoScrollTimerId");
      __publicField(this, "_draggingMouseOffset");
      __publicField(this, "_moveDistanceForOneCell");
      __publicField(this, "_xDelayForNextCell", 0);
      __publicField(this, "_yDelayForNextCell", 0);
      __publicField(this, "_viewportHeight", 0);
      __publicField(this, "_viewportWidth", 0);
      __publicField(this, "_isRowMoveRegistered", !1);
      // Scrollings
      __publicField(this, "_scrollLeft", 0);
      __publicField(this, "_scrollTop", 0);
      this._options = Utils.extend(!0, {}, this._defaults, options);
    }
    init(grid) {
      if (Draggable === void 0)
        throw new Error('Slick.Draggable is undefined, make sure to import "slick.interactions.js"');
      this._decorator = this._options.cellDecorator || new SlickCellRangeDecorator(grid, this._options), this._grid = grid, this._canvas = this._grid.getCanvasNode(), this._gridOptions = this._grid.getOptions(), this._handler.subscribe(this._grid.onScroll, this.handleScroll.bind(this)).subscribe(this._grid.onDragInit, this.handleDragInit.bind(this)).subscribe(this._grid.onDragStart, this.handleDragStart.bind(this)).subscribe(this._grid.onDrag, this.handleDrag.bind(this)).subscribe(this._grid.onDragEnd, this.handleDragEnd.bind(this));
    }
    destroy() {
      var _a;
      this._handler.unsubscribeAll(), this._activeCanvas = null, this._activeViewport = null, this._canvas = null, (_a = this._decorator) == null || _a.destroy();
    }
    getCellDecorator() {
      return this._decorator;
    }
    handleScroll(_e, args) {
      this._scrollTop = args.scrollTop, this._scrollLeft = args.scrollLeft;
    }
    handleDragInit(e) {
      this._activeCanvas = this._grid.getActiveCanvasNode(e), this._activeViewport = this._grid.getActiveViewportNode(e);
      let scrollbarDimensions = this._grid.getDisplayedScrollbarDimensions();
      if (this._viewportWidth = this._activeViewport.offsetWidth - scrollbarDimensions.width, this._viewportHeight = this._activeViewport.offsetHeight - scrollbarDimensions.height, this._moveDistanceForOneCell = {
        x: this._grid.getAbsoluteColumnMinWidth() / 2,
        y: this._grid.getOptions().rowHeight / 2
      }, this._isRowMoveRegistered = this.hasRowMoveManager(), this._rowOffset = 0, this._columnOffset = 0, this._isBottomCanvas = this._activeCanvas.classList.contains("grid-canvas-bottom"), this._gridOptions.frozenRow > -1 && this._isBottomCanvas) {
        let canvasSelector = `.${this._grid.getUID()} .grid-canvas-${this._gridOptions.frozenBottom ? "bottom" : "top"}`, canvasElm = document.querySelector(canvasSelector);
        canvasElm && (this._rowOffset = canvasElm.clientHeight || 0);
      }
      if (this._isRightCanvas = this._activeCanvas.classList.contains("grid-canvas-right"), this._gridOptions.frozenColumn > -1 && this._isRightCanvas) {
        let canvasLeftElm = document.querySelector(`.${this._grid.getUID()} .grid-canvas-left`);
        canvasLeftElm && (this._columnOffset = canvasLeftElm.clientWidth || 0);
      }
      e.stopImmediatePropagation(), e.preventDefault();
    }
    handleDragStart(e, dd) {
      var _a, _b;
      let cell = this._grid.getCellFromEvent(e);
      if (cell && this.onBeforeCellRangeSelected.notify(cell).getReturnValue() !== !1 && this._grid.canCellBeSelected(cell.row, cell.cell) && (this._dragging = !0, e.stopImmediatePropagation()), !this._dragging)
        return;
      this._grid.focus();
      let canvasOffset = Utils.offset(this._canvas), startX = dd.startX - ((_a = canvasOffset == null ? void 0 : canvasOffset.left) != null ? _a : 0);
      this._gridOptions.frozenColumn >= 0 && this._isRightCanvas && (startX += this._scrollLeft);
      let startY = dd.startY - ((_b = canvasOffset == null ? void 0 : canvasOffset.top) != null ? _b : 0);
      this._gridOptions.frozenRow >= 0 && this._isBottomCanvas && (startY += this._scrollTop);
      let start = this._grid.getCellFromPoint(startX, startY);
      return dd.range = { start, end: {} }, this._currentlySelectedRange = dd.range, this._decorator.show(new SlickRange(start.row, start.cell));
    }
    handleDrag(evt, dd) {
      if (!this._dragging && !this._isRowMoveRegistered)
        return;
      this._isRowMoveRegistered || evt.stopImmediatePropagation();
      let e = evt.getNativeEvent();
      if (this._options.autoScroll && (this._draggingMouseOffset = this.getMouseOffsetViewport(e, dd), this._draggingMouseOffset.isOutsideViewport))
        return this.handleDragOutsideViewport();
      this.stopIntervalTimer(), this.handleDragTo(e, dd);
    }
    getMouseOffsetViewport(e, dd) {
      var _a, _b, _c, _d;
      let targetEvent = (_b = (_a = e == null ? void 0 : e.touches) == null ? void 0 : _a[0]) != null ? _b : e, viewportLeft = this._activeViewport.scrollLeft, viewportTop = this._activeViewport.scrollTop, viewportRight = viewportLeft + this._viewportWidth, viewportBottom = viewportTop + this._viewportHeight, viewportOffset = Utils.offset(this._activeViewport), viewportOffsetLeft = (_c = viewportOffset == null ? void 0 : viewportOffset.left) != null ? _c : 0, viewportOffsetTop = (_d = viewportOffset == null ? void 0 : viewportOffset.top) != null ? _d : 0, viewportOffsetRight = viewportOffsetLeft + this._viewportWidth, viewportOffsetBottom = viewportOffsetTop + this._viewportHeight, result = {
        e,
        dd,
        viewport: {
          left: viewportLeft,
          top: viewportTop,
          right: viewportRight,
          bottom: viewportBottom,
          offset: {
            left: viewportOffsetLeft,
            top: viewportOffsetTop,
            right: viewportOffsetRight,
            bottom: viewportOffsetBottom
          }
        },
        // Consider the viewport as the origin, the `offset` is based on the coordinate system:
        // the cursor is on the viewport's left/bottom when it is less than 0, and on the right/top when greater than 0.
        offset: {
          x: 0,
          y: 0
        },
        isOutsideViewport: !1
      };
      return targetEvent.pageX < viewportOffsetLeft ? result.offset.x = targetEvent.pageX - viewportOffsetLeft : targetEvent.pageX > viewportOffsetRight && (result.offset.x = targetEvent.pageX - viewportOffsetRight), targetEvent.pageY < viewportOffsetTop ? result.offset.y = viewportOffsetTop - targetEvent.pageY : targetEvent.pageY > viewportOffsetBottom && (result.offset.y = viewportOffsetBottom - targetEvent.pageY), result.isOutsideViewport = !!result.offset.x || !!result.offset.y, result;
    }
    handleDragOutsideViewport() {
      if (this._xDelayForNextCell = this._options.maxIntervalToShowNextCell - Math.abs(this._draggingMouseOffset.offset.x) * this._options.accelerateInterval, this._yDelayForNextCell = this._options.maxIntervalToShowNextCell - Math.abs(this._draggingMouseOffset.offset.y) * this._options.accelerateInterval, !this._autoScrollTimerId) {
        let xTotalDelay = 0, yTotalDelay = 0;
        this._autoScrollTimerId = setInterval(() => {
          let xNeedUpdate = !1, yNeedUpdate = !1;
          this._draggingMouseOffset.offset.x ? (xTotalDelay += this._options.minIntervalToShowNextCell, xNeedUpdate = xTotalDelay >= this._xDelayForNextCell) : xTotalDelay = 0, this._draggingMouseOffset.offset.y ? (yTotalDelay += this._options.minIntervalToShowNextCell, yNeedUpdate = yTotalDelay >= this._yDelayForNextCell) : yTotalDelay = 0, (xNeedUpdate || yNeedUpdate) && (xNeedUpdate && (xTotalDelay = 0), yNeedUpdate && (yTotalDelay = 0), this.handleDragToNewPosition(xNeedUpdate, yNeedUpdate));
        }, this._options.minIntervalToShowNextCell);
      }
    }
    handleDragToNewPosition(xNeedUpdate, yNeedUpdate) {
      let pageX = this._draggingMouseOffset.e.pageX, pageY = this._draggingMouseOffset.e.pageY, mouseOffsetX = this._draggingMouseOffset.offset.x, mouseOffsetY = this._draggingMouseOffset.offset.y, viewportOffset = this._draggingMouseOffset.viewport.offset;
      xNeedUpdate && mouseOffsetX && (mouseOffsetX > 0 ? pageX = viewportOffset.right + this._moveDistanceForOneCell.x : pageX = viewportOffset.left - this._moveDistanceForOneCell.x), yNeedUpdate && mouseOffsetY && (mouseOffsetY > 0 ? pageY = viewportOffset.top - this._moveDistanceForOneCell.y : pageY = viewportOffset.bottom + this._moveDistanceForOneCell.y), this.handleDragTo({ pageX, pageY }, this._draggingMouseOffset.dd);
    }
    stopIntervalTimer() {
      this._autoScrollTimerId && (clearInterval(this._autoScrollTimerId), this._autoScrollTimerId = void 0);
    }
    handleDragTo(e, dd) {
      var _a, _b, _c, _d, _e, _f;
      let targetEvent = (_b = (_a = e == null ? void 0 : e.touches) == null ? void 0 : _a[0]) != null ? _b : e, canvasOffset = Utils.offset(this._activeCanvas), end = this._grid.getCellFromPoint(
        targetEvent.pageX - ((_c = canvasOffset == null ? void 0 : canvasOffset.left) != null ? _c : 0) + this._columnOffset,
        targetEvent.pageY - ((_d = canvasOffset == null ? void 0 : canvasOffset.top) != null ? _d : 0) + this._rowOffset
      );
      if (!(this._gridOptions.frozenColumn >= 0 && !this._isRightCanvas && end.cell > this._gridOptions.frozenColumn || this._isRightCanvas && end.cell <= this._gridOptions.frozenColumn) && !(this._gridOptions.frozenRow >= 0 && !this._isBottomCanvas && end.row >= this._gridOptions.frozenRow || this._isBottomCanvas && end.row < this._gridOptions.frozenRow)) {
        if (this._options.autoScroll && this._draggingMouseOffset) {
          let endCellBox = this._grid.getCellNodeBox(end.row, end.cell);
          if (!endCellBox)
            return;
          let viewport = this._draggingMouseOffset.viewport;
          (endCellBox.left < viewport.left || endCellBox.right > viewport.right || endCellBox.top < viewport.top || endCellBox.bottom > viewport.bottom) && this._grid.scrollCellIntoView(end.row, end.cell);
        }
        if (this._grid.canCellBeSelected(end.row, end.cell) && dd != null && dd.range) {
          dd.range.end = end;
          let range = new SlickRange((_e = dd.range.start.row) != null ? _e : 0, (_f = dd.range.start.cell) != null ? _f : 0, end.row, end.cell);
          this._decorator.show(range), this.onCellRangeSelecting.notify({
            range
          });
        }
      }
    }
    hasRowMoveManager() {
      return !!(this._grid.getPluginByName("RowMoveManager") || this._grid.getPluginByName("CrossGridRowMoveManager"));
    }
    handleDragEnd(e, dd) {
      var _a, _b;
      this._decorator.hide(), this._dragging && (this._dragging = !1, e.stopImmediatePropagation(), this.stopIntervalTimer(), this.onCellRangeSelected.notify({
        range: new SlickRange(
          (_a = dd.range.start.row) != null ? _a : 0,
          (_b = dd.range.start.cell) != null ? _b : 0,
          dd.range.end.row,
          dd.range.end.cell
        )
      }));
    }
    getCurrentRange() {
      return this._currentlySelectedRange;
    }
  };
  window.Slick && Utils.extend(Slick, {
    CellRangeSelector: SlickCellRangeSelector
  });
})();
//# sourceMappingURL=slick.cellrangeselector.js.map
"use strict";
(() => {
  var __defProp = Object.defineProperty;
  var __defNormalProp = (obj, key, value) => key in obj ? __defProp(obj, key, { enumerable: !0, configurable: !0, writable: !0, value }) : obj[key] = value;
  var __publicField = (obj, key, value) => (__defNormalProp(obj, typeof key != "symbol" ? key + "" : key, value), value);

  // src/plugins/slick.cellselectionmodel.ts
  var SlickEvent = Slick.Event, SlickEventData = Slick.EventData, SlickRange = Slick.Range, SlickCellRangeSelector = Slick.CellRangeSelector, Utils = Slick.Utils, SlickCellSelectionModel = class {
    constructor(options) {
      // --
      // public API
      __publicField(this, "pluginName", "CellSelectionModel");
      __publicField(this, "onSelectedRangesChanged", new SlickEvent());
      // --
      // protected props
      __publicField(this, "_cachedPageRowCount", 0);
      __publicField(this, "_dataView");
      __publicField(this, "_grid");
      __publicField(this, "_prevSelectedRow");
      __publicField(this, "_prevKeyDown", "");
      __publicField(this, "_ranges", []);
      __publicField(this, "_selector");
      __publicField(this, "_options");
      __publicField(this, "_defaults", {
        selectActiveCell: !0
      });
      options === void 0 || options.cellRangeSelector === void 0 ? this._selector = new SlickCellRangeSelector({ selectionCss: { border: "2px solid black" } }) : this._selector = options.cellRangeSelector;
    }
    init(grid) {
      this._options = Utils.extend(!0, {}, this._defaults, this._options), this._grid = grid, grid.hasDataView() && (this._dataView = grid.getData()), this._grid.onActiveCellChanged.subscribe(this.handleActiveCellChange.bind(this)), this._grid.onKeyDown.subscribe(this.handleKeyDown.bind(this)), grid.registerPlugin(this._selector), this._selector.onCellRangeSelected.subscribe(this.handleCellRangeSelected.bind(this)), this._selector.onBeforeCellRangeSelected.subscribe(this.handleBeforeCellRangeSelected.bind(this));
    }
    destroy() {
      var _a;
      this._grid.onActiveCellChanged.unsubscribe(this.handleActiveCellChange.bind(this)), this._grid.onKeyDown.unsubscribe(this.handleKeyDown.bind(this)), this._selector.onCellRangeSelected.unsubscribe(this.handleCellRangeSelected.bind(this)), this._selector.onBeforeCellRangeSelected.unsubscribe(this.handleBeforeCellRangeSelected.bind(this)), this._grid.unregisterPlugin(this._selector), (_a = this._selector) == null || _a.destroy();
    }
    removeInvalidRanges(ranges) {
      let result = [];
      for (let i = 0; i < ranges.length; i++) {
        let r = ranges[i];
        this._grid.canCellBeSelected(r.fromRow, r.fromCell) && this._grid.canCellBeSelected(r.toRow, r.toCell) && result.push(r);
      }
      return result;
    }
    rangesAreEqual(range1, range2) {
      let areDifferent = range1.length !== range2.length;
      if (!areDifferent) {
        for (let i = 0; i < range1.length; i++)
          if (range1[i].fromCell !== range2[i].fromCell || range1[i].fromRow !== range2[i].fromRow || range1[i].toCell !== range2[i].toCell || range1[i].toRow !== range2[i].toRow) {
            areDifferent = !0;
            break;
          }
      }
      return !areDifferent;
    }
    /** Provide a way to force a recalculation of page row count (for example on grid resize) */
    resetPageRowCount() {
      this._cachedPageRowCount = 0;
    }
    setSelectedRanges(ranges, caller = "SlickCellSelectionModel.setSelectedRanges") {
      if ((!this._ranges || this._ranges.length === 0) && (!ranges || ranges.length === 0))
        return;
      let rangeHasChanged = !this.rangesAreEqual(this._ranges, ranges);
      if (this._ranges = this.removeInvalidRanges(ranges), rangeHasChanged) {
        let eventData = new SlickEventData(null, this._ranges);
        Object.defineProperty(eventData, "detail", { writable: !0, configurable: !0, value: { caller: caller || "SlickCellSelectionModel.setSelectedRanges" } }), this.onSelectedRangesChanged.notify(this._ranges, eventData);
      }
    }
    getSelectedRanges() {
      return this._ranges;
    }
    refreshSelections() {
      this.setSelectedRanges(this.getSelectedRanges());
    }
    handleBeforeCellRangeSelected(e) {
      if (this._grid.getEditorLock().isActive())
        return e.stopPropagation(), !1;
    }
    handleCellRangeSelected(_e, args) {
      this._grid.setActiveCell(args.range.fromRow, args.range.fromCell, !1, !1, !0), this.setSelectedRanges([args.range]);
    }
    handleActiveCellChange(_e, args) {
      var _a, _b;
      this._prevSelectedRow = void 0;
      let isCellDefined = Utils.isDefined(args.cell), isRowDefined = Utils.isDefined(args.row);
      (_a = this._options) != null && _a.selectActiveCell && isRowDefined && isCellDefined ? this.setSelectedRanges([new SlickRange(args.row, args.cell)]) : (!((_b = this._options) != null && _b.selectActiveCell) || !isRowDefined && !isCellDefined) && this.setSelectedRanges([]);
    }
    isKeyAllowed(key) {
      return ["ArrowLeft", "ArrowRight", "ArrowUp", "ArrowDown", "PageDown", "PageUp", "Home", "End"].some((k) => k === key);
    }
    handleKeyDown(e) {
      var _a;
      let ranges, last, colLn = this._grid.getColumns().length, active = this._grid.getActiveCell(), dataLn = 0;
      if (this._dataView ? dataLn = ((_a = this._dataView) == null ? void 0 : _a.getPagingInfo().pageSize) || this._dataView.getLength() : dataLn = this._grid.getDataLength(), active && (e.shiftKey || e.ctrlKey) && !e.altKey && this.isKeyAllowed(e.key)) {
        ranges = this.getSelectedRanges().slice(), ranges.length || ranges.push(new SlickRange(active.row, active.cell)), last = ranges.pop(), last.contains(active.row, active.cell) || (last = new SlickRange(active.row, active.cell));
        let dRow = last.toRow - last.fromRow, dCell = last.toCell - last.fromCell, dirRow = active.row === last.fromRow ? 1 : -1, dirCell = active.cell === last.fromCell ? 1 : -1, isSingleKeyMove = e.key.startsWith("Arrow"), toCell, toRow = 0;
        isSingleKeyMove && !e.ctrlKey ? (e.key === "ArrowLeft" ? dCell -= dirCell : e.key === "ArrowRight" ? dCell += dirCell : e.key === "ArrowUp" ? dRow -= dirRow : e.key === "ArrowDown" && (dRow += dirRow), toRow = active.row + dirRow * dRow) : (this._cachedPageRowCount < 1 && (this._cachedPageRowCount = this._grid.getViewportRowCount()), this._prevSelectedRow === void 0 && (this._prevSelectedRow = active.row), e.shiftKey && !e.ctrlKey && e.key === "Home" ? (toCell = 0, toRow = active.row) : e.shiftKey && !e.ctrlKey && e.key === "End" ? (toCell = colLn - 1, toRow = active.row) : e.ctrlKey && e.shiftKey && e.key === "Home" ? (toCell = 0, toRow = 0) : e.ctrlKey && e.shiftKey && e.key === "End" ? (toCell = colLn - 1, toRow = dataLn - 1) : e.key === "PageUp" ? (this._prevSelectedRow >= 0 && (toRow = this._prevSelectedRow - this._cachedPageRowCount), toRow < 0 && (toRow = 0)) : e.key === "PageDown" && (this._prevSelectedRow <= dataLn - 1 && (toRow = this._prevSelectedRow + this._cachedPageRowCount), toRow > dataLn - 1 && (toRow = dataLn - 1)), this._prevSelectedRow = toRow), toCell != null || (toCell = active.cell + dirCell * dCell);
        let new_last = new SlickRange(active.row, active.cell, toRow, toCell);
        if (this.removeInvalidRanges([new_last]).length) {
          ranges.push(new_last);
          let viewRow = dirRow > 0 ? new_last.toRow : new_last.fromRow, viewCell = dirCell > 0 ? new_last.toCell : new_last.fromCell;
          isSingleKeyMove ? (this._grid.scrollRowIntoView(viewRow), this._grid.scrollCellIntoView(viewRow, viewCell)) : (this._grid.scrollRowIntoView(toRow), this._grid.scrollCellIntoView(toRow, viewCell));
        } else
          ranges.push(last);
        this.setSelectedRanges(ranges), e.preventDefault(), e.stopPropagation(), this._prevKeyDown = e.key;
      }
    }
  };
  window.Slick && Utils.extend(!0, window, {
    Slick: {
      CellSelectionModel: SlickCellSelectionModel
    }
  });
})();
//# sourceMappingURL=slick.cellselectionmodel.js.map
"use strict";
(() => {
  var __defProp = Object.defineProperty;
  var __defNormalProp = (obj, key, value) => key in obj ? __defProp(obj, key, { enumerable: !0, configurable: !0, writable: !0, value }) : obj[key] = value;
  var __publicField = (obj, key, value) => (__defNormalProp(obj, typeof key != "symbol" ? key + "" : key, value), value);

  // src/plugins/slick.rowselectionmodel.ts
  var Draggable = Slick.Draggable, keyCode = Slick.keyCode, SlickCellRangeDecorator = Slick.CellRangeDecorator, SlickCellRangeSelector = Slick.CellRangeSelector, SlickEvent = Slick.Event, SlickEventData = Slick.EventData, SlickEventHandler = Slick.EventHandler, SlickRange = Slick.Range, Utils = Slick.Utils, SlickRowSelectionModel = class {
    constructor(options) {
      // --
      // public API
      __publicField(this, "pluginName", "RowSelectionModel");
      __publicField(this, "onSelectedRangesChanged", new SlickEvent());
      // _handler, _inHandler, _isRowMoveManagerHandler, _options, wrapHandler
      // --
      // protected props
      __publicField(this, "_grid");
      __publicField(this, "_ranges", []);
      __publicField(this, "_eventHandler", new SlickEventHandler());
      __publicField(this, "_inHandler", !1);
      __publicField(this, "_selector");
      __publicField(this, "_isRowMoveManagerHandler");
      __publicField(this, "_options");
      __publicField(this, "_defaults", {
        selectActiveRow: !0,
        dragToSelect: !1,
        autoScrollWhenDrag: !0,
        cellRangeSelector: void 0
      });
      this._options = Utils.extend(!0, {}, this._defaults, options);
    }
    init(grid) {
      if (Draggable === void 0)
        throw new Error('Slick.Draggable is undefined, make sure to import "slick.interactions.js"');
      if (this._selector = this._options.cellRangeSelector, this._grid = grid, !this._selector && this._options.dragToSelect) {
        if (!SlickCellRangeDecorator)
          throw new Error("Slick.CellRangeDecorator is required when option dragToSelect set to true");
        this._selector = new SlickCellRangeSelector({
          selectionCss: { border: "none" },
          autoScroll: this._options.autoScrollWhenDrag
        });
      }
      this._eventHandler.subscribe(this._grid.onActiveCellChanged, this.wrapHandler(this.handleActiveCellChange).bind(this)), this._eventHandler.subscribe(this._grid.onKeyDown, this.wrapHandler(this.handleKeyDown).bind(this)), this._eventHandler.subscribe(this._grid.onClick, this.wrapHandler(this.handleClick).bind(this)), this._selector && (grid.registerPlugin(this._selector), this._selector.onCellRangeSelecting.subscribe(this.handleCellRangeSelected.bind(this)), this._selector.onCellRangeSelected.subscribe(this.handleCellRangeSelected.bind(this)), this._selector.onBeforeCellRangeSelected.subscribe(this.handleBeforeCellRangeSelected.bind(this)));
    }
    destroy() {
      this._eventHandler.unsubscribeAll(), this._selector && (this._selector.onCellRangeSelecting.unsubscribe(this.handleCellRangeSelected.bind(this)), this._selector.onCellRangeSelected.unsubscribe(this.handleCellRangeSelected.bind(this)), this._selector.onBeforeCellRangeSelected.unsubscribe(this.handleBeforeCellRangeSelected.bind(this)), this._grid.unregisterPlugin(this._selector), this._selector.destroy && this._selector.destroy());
    }
    wrapHandler(handler) {
      return (...args) => {
        this._inHandler || (this._inHandler = !0, handler.apply(this, args), this._inHandler = !1);
      };
    }
    rangesToRows(ranges) {
      let rows = [];
      for (let i = 0; i < ranges.length; i++)
        for (let j = ranges[i].fromRow; j <= ranges[i].toRow; j++)
          rows.push(j);
      return rows;
    }
    rowsToRanges(rows) {
      let ranges = [], lastCell = this._grid.getColumns().length - 1;
      for (let i = 0; i < rows.length; i++)
        ranges.push(new SlickRange(rows[i], 0, rows[i], lastCell));
      return ranges;
    }
    getRowsRange(from, to) {
      let i, rows = [];
      for (i = from; i <= to; i++)
        rows.push(i);
      for (i = to; i < from; i++)
        rows.push(i);
      return rows;
    }
    getSelectedRows() {
      return this.rangesToRows(this._ranges);
    }
    setSelectedRows(rows) {
      this.setSelectedRanges(this.rowsToRanges(rows), "SlickRowSelectionModel.setSelectedRows");
    }
    setSelectedRanges(ranges, caller = "SlickRowSelectionModel.setSelectedRanges") {
      if ((!this._ranges || this._ranges.length === 0) && (!ranges || ranges.length === 0))
        return;
      this._ranges = ranges;
      let eventData = new SlickEventData(null, this._ranges);
      Object.defineProperty(eventData, "detail", { writable: !0, configurable: !0, value: { caller: caller || "SlickRowSelectionModel.setSelectedRanges" } }), this.onSelectedRangesChanged.notify(this._ranges, eventData);
    }
    getSelectedRanges() {
      return this._ranges;
    }
    refreshSelections() {
      this.setSelectedRows(this.getSelectedRows());
    }
    handleActiveCellChange(_e, args) {
      this._options.selectActiveRow && Utils.isDefined(args.row) && this.setSelectedRanges([new SlickRange(args.row, 0, args.row, this._grid.getColumns().length - 1)]);
    }
    handleKeyDown(e) {
      let activeRow = this._grid.getActiveCell();
      if (this._grid.getOptions().multiSelect && activeRow && e.shiftKey && !e.ctrlKey && !e.altKey && !e.metaKey && (e.which === keyCode.UP || e.which === keyCode.DOWN)) {
        let selectedRows = this.getSelectedRows();
        selectedRows.sort(function(x, y) {
          return x - y;
        }), selectedRows.length || (selectedRows = [activeRow.row]);
        let top = selectedRows[0], bottom = selectedRows[selectedRows.length - 1], active;
        if (e.which === keyCode.DOWN ? active = activeRow.row < bottom || top === bottom ? ++bottom : ++top : active = activeRow.row < bottom ? --bottom : --top, active >= 0 && active < this._grid.getDataLength()) {
          this._grid.scrollRowIntoView(active);
          let tempRanges = this.rowsToRanges(this.getRowsRange(top, bottom));
          this.setSelectedRanges(tempRanges);
        }
        e.preventDefault(), e.stopPropagation();
      }
    }
    handleClick(e) {
      let cell = this._grid.getCellFromEvent(e);
      if (!cell || !this._grid.canCellBeActive(cell.row, cell.cell) || !this._grid.getOptions().multiSelect || !e.ctrlKey && !e.shiftKey && !e.metaKey)
        return !1;
      let selection = this.rangesToRows(this._ranges), idx = selection.indexOf(cell.row);
      if (idx === -1 && (e.ctrlKey || e.metaKey))
        selection.push(cell.row), this._grid.setActiveCell(cell.row, cell.cell);
      else if (idx !== -1 && (e.ctrlKey || e.metaKey))
        selection = selection.filter((o) => o !== cell.row), this._grid.setActiveCell(cell.row, cell.cell);
      else if (selection.length && e.shiftKey) {
        let last = selection.pop(), from = Math.min(cell.row, last), to = Math.max(cell.row, last);
        selection = [];
        for (let i = from; i <= to; i++)
          i !== last && selection.push(i);
        selection.push(last), this._grid.setActiveCell(cell.row, cell.cell);
      }
      let tempRanges = this.rowsToRanges(selection);
      return this.setSelectedRanges(tempRanges), e.stopImmediatePropagation(), !0;
    }
    handleBeforeCellRangeSelected(e, cell) {
      if (!this._isRowMoveManagerHandler) {
        let rowMoveManager = this._grid.getPluginByName("RowMoveManager") || this._grid.getPluginByName("CrossGridRowMoveManager");
        this._isRowMoveManagerHandler = rowMoveManager ? rowMoveManager.isHandlerColumn : Utils.noop;
      }
      if (this._grid.getEditorLock().isActive() || this._isRowMoveManagerHandler(cell.cell))
        return e.stopPropagation(), !1;
      this._grid.setActiveCell(cell.row, cell.cell);
    }
    handleCellRangeSelected(_e, args) {
      if (!this._grid.getOptions().multiSelect || !this._options.selectActiveRow)
        return !1;
      this.setSelectedRanges([new SlickRange(args.range.fromRow, 0, args.range.toRow, this._grid.getColumns().length - 1)]);
    }
  };
  window.Slick && Utils.extend(!0, window, {
    Slick: {
      RowSelectionModel: SlickRowSelectionModel
    }
  });
})();
//# sourceMappingURL=slick.rowselectionmodel.js.map
"use strict";
(() => {
  var __defProp = Object.defineProperty;
  var __defNormalProp = (obj, key, value) => key in obj ? __defProp(obj, key, { enumerable: !0, configurable: !0, writable: !0, value }) : obj[key] = value;
  var __publicField = (obj, key, value) => (__defNormalProp(obj, typeof key != "symbol" ? key + "" : key, value), value);

  // src/controls/slick.columnmenu.ts
  var BindingEventService = Slick.BindingEventService, SlickEvent = Slick.Event, Utils = Slick.Utils, SlickColumnMenu = class {
    constructor(columns, grid, options) {
      this.columns = columns;
      this.grid = grid;
      // --
      // public API
      __publicField(this, "onColumnsChanged", new SlickEvent());
      // --
      // protected props
      __publicField(this, "_gridUid");
      __publicField(this, "_columnTitleElm");
      __publicField(this, "_listElm");
      __publicField(this, "_menuElm");
      __publicField(this, "_columnCheckboxes", []);
      __publicField(this, "_bindingEventService", new BindingEventService());
      __publicField(this, "_options");
      __publicField(this, "_defaults", {
        fadeSpeed: 250,
        // the last 2 checkboxes titles
        hideForceFitButton: !1,
        hideSyncResizeButton: !1,
        forceFitTitle: "Force fit columns",
        syncResizeTitle: "Synchronous resize",
        headerColumnValueExtractor: (columnDef) => columnDef.name instanceof HTMLElement ? columnDef.name.innerHTML : columnDef.name || ""
      });
      this._gridUid = grid.getUID(), this._options = Utils.extend({}, this._defaults, options), this.init(this.grid);
    }
    init(grid) {
      var _a, _b;
      grid.onHeaderContextMenu.subscribe(this.handleHeaderContextMenu.bind(this)), grid.onColumnsReordered.subscribe(this.updateColumnOrder.bind(this)), this._menuElm = document.createElement("div"), this._menuElm.className = `slick-columnpicker ${this._gridUid}`, this._menuElm.style.display = "none", document.body.appendChild(this._menuElm);
      let buttonElm = document.createElement("button");
      buttonElm.type = "button", buttonElm.className = "close", buttonElm.dataset.dismiss = "slick-columnpicker", buttonElm.ariaLabel = "Close";
      let spanCloseElm = document.createElement("span");
      if (spanCloseElm.className = "close", spanCloseElm.ariaHidden = "true", spanCloseElm.textContent = "\xD7", buttonElm.appendChild(spanCloseElm), this._menuElm.appendChild(buttonElm), this._options.columnPickerTitle || (_a = this._options.columnPicker) != null && _a.columnTitle) {
        let columnTitle = this._options.columnPickerTitle || ((_b = this._options.columnPicker) == null ? void 0 : _b.columnTitle);
        this._columnTitleElm = document.createElement("div"), this._columnTitleElm.className = "slick-gridmenu-custom", this._columnTitleElm.textContent = columnTitle || "", this._menuElm.appendChild(this._columnTitleElm);
      }
      this._bindingEventService.bind(this._menuElm, "click", this.updateColumn.bind(this)), this._listElm = document.createElement("span"), this._listElm.className = "slick-columnpicker-list", this._bindingEventService.bind(document.body, "mousedown", this.handleBodyMouseDown.bind(this)), this._bindingEventService.bind(document.body, "beforeunload", this.destroy.bind(this));
    }
    destroy() {
      var _a, _b;
      this.grid.onHeaderContextMenu.unsubscribe(this.handleHeaderContextMenu.bind(this)), this.grid.onColumnsReordered.unsubscribe(this.updateColumnOrder.bind(this)), this._bindingEventService.unbindAll(), (_a = this._listElm) == null || _a.remove(), (_b = this._menuElm) == null || _b.remove();
    }
    handleBodyMouseDown(e) {
      (this._menuElm !== e.target && !(this._menuElm && this._menuElm.contains(e.target)) || e.target.className === "close") && (this._menuElm.setAttribute("aria-expanded", "false"), this._menuElm.style.display = "none");
    }
    handleHeaderContextMenu(e) {
      var _a, _b, _c, _d, _e, _f;
      e.preventDefault(), Utils.emptyElement(this._listElm), this.updateColumnOrder(), this._columnCheckboxes = [];
      let columnId, columnLabel, excludeCssClass;
      for (let i = 0; i < this.columns.length; i++) {
        columnId = this.columns[i].id;
        let colName = this.columns[i].name instanceof HTMLElement ? this.columns[i].name.innerHTML : this.columns[i].name || "";
        excludeCssClass = this.columns[i].excludeFromColumnPicker ? "hidden" : "";
        let liElm = document.createElement("li");
        liElm.className = excludeCssClass, liElm.ariaLabel = colName;
        let checkboxElm = document.createElement("input");
        checkboxElm.type = "checkbox", checkboxElm.id = `${this._gridUid}colpicker-${columnId}`, checkboxElm.dataset.columnid = String(this.columns[i].id), liElm.appendChild(checkboxElm), this._columnCheckboxes.push(checkboxElm), Utils.isDefined(this.grid.getColumnIndex(columnId)) && !this.columns[i].hidden && (checkboxElm.checked = !0), columnLabel = (_b = (_a = this._options) == null ? void 0 : _a.columnPicker) != null && _b.headerColumnValueExtractor ? this._options.columnPicker.headerColumnValueExtractor(this.columns[i], this._options) : this._defaults.headerColumnValueExtractor(this.columns[i], this._options);
        let labelElm = document.createElement("label");
        labelElm.htmlFor = `${this._gridUid}colpicker-${columnId}`, this.grid.applyHtmlCode(labelElm, columnLabel), liElm.appendChild(labelElm), this._listElm.appendChild(liElm);
      }
      if (this._options.columnPicker && (!this._options.columnPicker.hideForceFitButton || !this._options.columnPicker.hideSyncResizeButton) && this._listElm.appendChild(document.createElement("hr")), !((_c = this._options.columnPicker) != null && _c.hideForceFitButton)) {
        let forceFitTitle = ((_d = this._options.columnPicker) == null ? void 0 : _d.forceFitTitle) || this._options.forceFitTitle, liElm = document.createElement("li");
        liElm.ariaLabel = forceFitTitle || "", this._listElm.appendChild(liElm);
        let forceFitCheckboxElm = document.createElement("input");
        forceFitCheckboxElm.type = "checkbox", forceFitCheckboxElm.id = `${this._gridUid}colpicker-forcefit`, forceFitCheckboxElm.dataset.option = "autoresize", liElm.appendChild(forceFitCheckboxElm);
        let labelElm = document.createElement("label");
        labelElm.htmlFor = `${this._gridUid}colpicker-forcefit`, labelElm.textContent = forceFitTitle || "", liElm.appendChild(labelElm), this.grid.getOptions().forceFitColumns && (forceFitCheckboxElm.checked = !0);
      }
      if (!((_e = this._options.columnPicker) != null && _e.hideSyncResizeButton)) {
        let syncResizeTitle = ((_f = this._options.columnPicker) == null ? void 0 : _f.syncResizeTitle) || this._options.syncResizeTitle, liElm = document.createElement("li");
        liElm.ariaLabel = syncResizeTitle || "", this._listElm.appendChild(liElm);
        let syncResizeCheckboxElm = document.createElement("input");
        syncResizeCheckboxElm.type = "checkbox", syncResizeCheckboxElm.id = `${this._gridUid}colpicker-syncresize`, syncResizeCheckboxElm.dataset.option = "syncresize", liElm.appendChild(syncResizeCheckboxElm);
        let labelElm = document.createElement("label");
        labelElm.htmlFor = `${this._gridUid}colpicker-syncresize`, labelElm.textContent = syncResizeTitle || "", liElm.appendChild(labelElm), this.grid.getOptions().syncColumnCellResize && (syncResizeCheckboxElm.checked = !0);
      }
      this.repositionMenu(e);
    }
    repositionMenu(event) {
      var _a;
      let targetEvent = ((_a = event == null ? void 0 : event.touches) == null ? void 0 : _a[0]) || event;
      this._menuElm.style.top = `${targetEvent.pageY - 10}px`, this._menuElm.style.left = `${targetEvent.pageX - 10}px`, this._menuElm.style.maxHeight = `${window.innerHeight - targetEvent.clientY}px`, this._menuElm.style.display = "block", this._menuElm.setAttribute("aria-expanded", "true"), this._menuElm.appendChild(this._listElm);
    }
    updateColumnOrder() {
      let current = this.grid.getColumns().slice(0), ordered = new Array(this.columns.length);
      for (let i = 0; i < ordered.length; i++)
        this.grid.getColumnIndex(this.columns[i].id) === void 0 ? ordered[i] = this.columns[i] : ordered[i] = current.shift();
      this.columns = ordered;
    }
    /** Update the Titles of each sections (command, customTitle, ...) */
    updateAllTitles(pickerOptions) {
      this.grid.applyHtmlCode(this._columnTitleElm, pickerOptions.columnTitle);
    }
    updateColumn(e) {
      if (e.target.dataset.option === "autoresize") {
        let previousVisibleColumns = this.getVisibleColumns(), isChecked = e.target.checked;
        this.grid.setOptions({ forceFitColumns: isChecked }), this.grid.setColumns(previousVisibleColumns);
        return;
      }
      if (e.target.dataset.option === "syncresize") {
        e.target.checked ? this.grid.setOptions({ syncColumnCellResize: !0 }) : this.grid.setOptions({ syncColumnCellResize: !1 });
        return;
      }
      if (e.target.type === "checkbox") {
        let isChecked = e.target.checked, columnId = e.target.dataset.columnid || "", visibleColumns = [];
        if (this._columnCheckboxes.forEach((columnCheckbox, idx) => {
          this.columns[idx].hidden !== void 0 && (this.columns[idx].hidden = !columnCheckbox.checked), columnCheckbox.checked && visibleColumns.push(this.columns[idx]);
        }), !visibleColumns.length) {
          e.target.checked = !0;
          return;
        }
        this.grid.setColumns(visibleColumns), this.onColumnsChanged.notify({ columnId, showing: isChecked, allColumns: this.columns, columns: this.columns, visibleColumns, grid: this.grid });
      }
    }
    setColumnVisibiliy(idxOrId, show) {
      let idx = typeof idxOrId == "number" ? idxOrId : this.getColumnIndexbyId(idxOrId), visibleColumns = this.getVisibleColumns(), col = this.columns[idx];
      if (show)
        col.hidden = !1, visibleColumns.splice(idx, 0, col);
      else {
        let newVisibleColumns = [];
        for (let i = 0; i < visibleColumns.length; i++)
          visibleColumns[i].id !== col.id && newVisibleColumns.push(visibleColumns[i]);
        visibleColumns = newVisibleColumns;
      }
      this.grid.setColumns(visibleColumns), this.onColumnsChanged.notify({ columnId: col.id, showing: show, allColumns: this.columns, columns: this.columns, visibleColumns, grid: this.grid });
    }
    getAllColumns() {
      return this.columns;
    }
    getColumnbyId(id) {
      for (let i = 0; i < this.columns.length; i++)
        if (this.columns[i].id === id)
          return this.columns[i];
      return null;
    }
    getColumnIndexbyId(id) {
      for (let i = 0; i < this.columns.length; i++)
        if (this.columns[i].id === id)
          return i;
      return -1;
    }
    /** visible columns, we can simply get them directly from the grid */
    getVisibleColumns() {
      return this.grid.getColumns();
    }
  };
  window.Slick && (window.Slick.Controls = window.Slick.Controls || {}, window.Slick.Controls.ColumnPicker = SlickColumnMenu);
})();
//# sourceMappingURL=slick.columnmenu.js.map
"use strict";
(() => {
  var __defProp = Object.defineProperty;
  var __defNormalProp = (obj, key, value) => key in obj ? __defProp(obj, key, { enumerable: !0, configurable: !0, writable: !0, value }) : obj[key] = value;
  var __publicField = (obj, key, value) => (__defNormalProp(obj, typeof key != "symbol" ? key + "" : key, value), value);

  // src/controls/slick.columnpicker.ts
  var BindingEventService = Slick.BindingEventService, SlickEvent = Slick.Event, Utils = Slick.Utils, SlickColumnPicker = class {
    constructor(columns, grid, gridOptions) {
      this.columns = columns;
      this.grid = grid;
      // --
      // public API
      __publicField(this, "onColumnsChanged", new SlickEvent());
      // --
      // protected props
      __publicField(this, "_gridUid");
      __publicField(this, "_columnTitleElm");
      __publicField(this, "_listElm");
      __publicField(this, "_menuElm");
      __publicField(this, "_columnCheckboxes", []);
      __publicField(this, "_bindingEventService", new BindingEventService());
      __publicField(this, "_gridOptions");
      __publicField(this, "_defaults", {
        fadeSpeed: 250,
        // the last 2 checkboxes titles
        hideForceFitButton: !1,
        hideSyncResizeButton: !1,
        forceFitTitle: "Force fit columns",
        syncResizeTitle: "Synchronous resize",
        headerColumnValueExtractor: (columnDef) => columnDef.name instanceof HTMLElement ? columnDef.name.innerHTML : columnDef.name || ""
      });
      this._gridUid = grid.getUID(), this._gridOptions = Utils.extend({}, this._defaults, gridOptions), this.init(this.grid);
    }
    init(grid) {
      var _a, _b;
      grid.onHeaderContextMenu.subscribe(this.handleHeaderContextMenu.bind(this)), grid.onColumnsReordered.subscribe(this.updateColumnOrder.bind(this)), this._menuElm = document.createElement("div"), this._menuElm.className = `slick-columnpicker ${this._gridUid}`, this._menuElm.style.display = "none", document.body.appendChild(this._menuElm);
      let buttonElm = document.createElement("button");
      buttonElm.type = "button", buttonElm.className = "close", buttonElm.dataset.dismiss = "slick-columnpicker", buttonElm.ariaLabel = "Close";
      let spanCloseElm = document.createElement("span");
      if (spanCloseElm.className = "close", spanCloseElm.ariaHidden = "true", spanCloseElm.textContent = "\xD7", buttonElm.appendChild(spanCloseElm), this._menuElm.appendChild(buttonElm), this._gridOptions.columnPickerTitle || (_a = this._gridOptions.columnPicker) != null && _a.columnTitle) {
        let columnTitle = this._gridOptions.columnPickerTitle || ((_b = this._gridOptions.columnPicker) == null ? void 0 : _b.columnTitle);
        this._columnTitleElm = document.createElement("div"), this._columnTitleElm.className = "slick-gridmenu-custom", this._columnTitleElm.textContent = columnTitle || "", this._menuElm.appendChild(this._columnTitleElm);
      }
      this._bindingEventService.bind(this._menuElm, "click", this.updateColumn.bind(this)), this._listElm = document.createElement("span"), this._listElm.className = "slick-columnpicker-list", this._bindingEventService.bind(document.body, "mousedown", this.handleBodyMouseDown.bind(this)), this._bindingEventService.bind(document.body, "beforeunload", this.destroy.bind(this));
    }
    destroy() {
      var _a, _b;
      this.grid.onHeaderContextMenu.unsubscribe(this.handleHeaderContextMenu.bind(this)), this.grid.onColumnsReordered.unsubscribe(this.updateColumnOrder.bind(this)), this._bindingEventService.unbindAll(), (_a = this._listElm) == null || _a.remove(), (_b = this._menuElm) == null || _b.remove();
    }
    handleBodyMouseDown(e) {
      var _a;
      (this._menuElm !== e.target && !((_a = this._menuElm) != null && _a.contains(e.target)) || e.target.className === "close") && (this._menuElm.setAttribute("aria-expanded", "false"), this._menuElm.style.display = "none");
    }
    handleHeaderContextMenu(e) {
      var _a, _b, _c, _d, _e, _f;
      e.preventDefault(), Utils.emptyElement(this._listElm), this.updateColumnOrder(), this._columnCheckboxes = [];
      let columnId, columnLabel, excludeCssClass;
      for (let i = 0; i < this.columns.length; i++) {
        columnId = this.columns[i].id;
        let colName = this.columns[i].name instanceof HTMLElement ? this.columns[i].name.innerHTML : this.columns[i].name || "";
        excludeCssClass = this.columns[i].excludeFromColumnPicker ? "hidden" : "";
        let liElm = document.createElement("li");
        liElm.className = excludeCssClass, liElm.ariaLabel = colName;
        let checkboxElm = document.createElement("input");
        checkboxElm.type = "checkbox", checkboxElm.id = `${this._gridUid}colpicker-${columnId}`, checkboxElm.dataset.columnid = String(this.columns[i].id), liElm.appendChild(checkboxElm), this._columnCheckboxes.push(checkboxElm), Utils.isDefined(this.grid.getColumnIndex(columnId)) && !this.columns[i].hidden && (checkboxElm.checked = !0), columnLabel = (_b = (_a = this._gridOptions) == null ? void 0 : _a.columnPicker) != null && _b.headerColumnValueExtractor ? this._gridOptions.columnPicker.headerColumnValueExtractor(this.columns[i], this._gridOptions) : this._defaults.headerColumnValueExtractor(this.columns[i], this._gridOptions);
        let labelElm = document.createElement("label");
        labelElm.htmlFor = `${this._gridUid}colpicker-${columnId}`, this.grid.applyHtmlCode(labelElm, columnLabel), liElm.appendChild(labelElm), this._listElm.appendChild(liElm);
      }
      if (this._gridOptions.columnPicker && (!this._gridOptions.columnPicker.hideForceFitButton || !this._gridOptions.columnPicker.hideSyncResizeButton) && this._listElm.appendChild(document.createElement("hr")), !((_c = this._gridOptions.columnPicker) != null && _c.hideForceFitButton)) {
        let forceFitTitle = ((_d = this._gridOptions.columnPicker) == null ? void 0 : _d.forceFitTitle) || this._gridOptions.forceFitTitle, liElm = document.createElement("li");
        liElm.ariaLabel = forceFitTitle || "", this._listElm.appendChild(liElm);
        let forceFitCheckboxElm = document.createElement("input");
        forceFitCheckboxElm.type = "checkbox", forceFitCheckboxElm.id = `${this._gridUid}colpicker-forcefit`, forceFitCheckboxElm.dataset.option = "autoresize", liElm.appendChild(forceFitCheckboxElm);
        let labelElm = document.createElement("label");
        labelElm.htmlFor = `${this._gridUid}colpicker-forcefit`, labelElm.textContent = forceFitTitle || "", liElm.appendChild(labelElm), this.grid.getOptions().forceFitColumns && (forceFitCheckboxElm.checked = !0);
      }
      if (!((_e = this._gridOptions.columnPicker) != null && _e.hideSyncResizeButton)) {
        let syncResizeTitle = ((_f = this._gridOptions.columnPicker) == null ? void 0 : _f.syncResizeTitle) || this._gridOptions.syncResizeTitle, liElm = document.createElement("li");
        liElm.ariaLabel = syncResizeTitle || "", this._listElm.appendChild(liElm);
        let syncResizeCheckboxElm = document.createElement("input");
        syncResizeCheckboxElm.type = "checkbox", syncResizeCheckboxElm.id = `${this._gridUid}colpicker-syncresize`, syncResizeCheckboxElm.dataset.option = "syncresize", liElm.appendChild(syncResizeCheckboxElm);
        let labelElm = document.createElement("label");
        labelElm.htmlFor = `${this._gridUid}colpicker-syncresize`, labelElm.textContent = syncResizeTitle || "", liElm.appendChild(labelElm), this.grid.getOptions().syncColumnCellResize && (syncResizeCheckboxElm.checked = !0);
      }
      this.repositionMenu(e);
    }
    repositionMenu(event) {
      var _a, _b;
      let targetEvent = (_b = (_a = event == null ? void 0 : event.touches) == null ? void 0 : _a[0]) != null ? _b : event;
      this._menuElm.style.top = `${targetEvent.pageY - 10}px`, this._menuElm.style.left = `${targetEvent.pageX - 10}px`, this._menuElm.style.maxHeight = `${window.innerHeight - targetEvent.clientY}px`, this._menuElm.style.display = "block", this._menuElm.setAttribute("aria-expanded", "true"), this._menuElm.appendChild(this._listElm);
    }
    updateColumnOrder() {
      let current = this.grid.getColumns().slice(0), ordered = new Array(this.columns.length);
      for (let i = 0; i < ordered.length; i++)
        this.grid.getColumnIndex(this.columns[i].id) === void 0 ? ordered[i] = this.columns[i] : ordered[i] = current.shift();
      this.columns = ordered;
    }
    /** Update the Titles of each sections (command, customTitle, ...) */
    updateAllTitles(pickerOptions) {
      this.grid.applyHtmlCode(this._columnTitleElm, pickerOptions.columnTitle);
    }
    updateColumn(e) {
      if (e.target.dataset.option === "autoresize") {
        let previousVisibleColumns = this.getVisibleColumns(), isChecked = e.target.checked || !1;
        this.grid.setOptions({ forceFitColumns: isChecked }), this.grid.setColumns(previousVisibleColumns);
        return;
      }
      if (e.target.dataset.option === "syncresize") {
        e.target.checked ? this.grid.setOptions({ syncColumnCellResize: !0 }) : this.grid.setOptions({ syncColumnCellResize: !1 });
        return;
      }
      if (e.target.type === "checkbox") {
        let isChecked = e.target.checked, columnId = e.target.dataset.columnid || "", visibleColumns = [];
        if (this._columnCheckboxes.forEach((columnCheckbox, idx) => {
          this.columns[idx].hidden !== void 0 && (this.columns[idx].hidden = !columnCheckbox.checked), columnCheckbox.checked && visibleColumns.push(this.columns[idx]);
        }), !visibleColumns.length) {
          e.target.checked = !0;
          return;
        }
        this.grid.setColumns(visibleColumns), this.onColumnsChanged.notify({ columnId, showing: isChecked, allColumns: this.columns, columns: this.columns, visibleColumns, grid: this.grid });
      }
    }
    setColumnVisibiliy(idxOrId, show) {
      let idx = typeof idxOrId == "number" ? idxOrId : this.getColumnIndexbyId(idxOrId), visibleColumns = this.getVisibleColumns(), col = this.columns[idx];
      if (show)
        col.hidden = !1, visibleColumns.splice(idx, 0, col);
      else {
        let newVisibleColumns = [];
        for (let i = 0; i < visibleColumns.length; i++)
          visibleColumns[i].id !== col.id && newVisibleColumns.push(visibleColumns[i]);
        visibleColumns = newVisibleColumns;
      }
      this.grid.setColumns(visibleColumns), this.onColumnsChanged.notify({ columnId: col.id, showing: show, allColumns: this.columns, columns: this.columns, visibleColumns, grid: this.grid });
    }
    getAllColumns() {
      return this.columns;
    }
    getColumnbyId(id) {
      for (let i = 0; i < this.columns.length; i++)
        if (this.columns[i].id === id)
          return this.columns[i];
      return null;
    }
    getColumnIndexbyId(id) {
      for (let i = 0; i < this.columns.length; i++)
        if (this.columns[i].id === id)
          return i;
      return -1;
    }
    /** visible columns, we can simply get them directly from the grid */
    getVisibleColumns() {
      return this.grid.getColumns();
    }
  };
  window.Slick && (window.Slick.Controls = window.Slick.Controls || {}, window.Slick.Controls.ColumnPicker = SlickColumnPicker);
})();
//# sourceMappingURL=slick.columnpicker.js.map
"use strict";
(() => {
  var __defProp = Object.defineProperty;
  var __defNormalProp = (obj, key, value) => key in obj ? __defProp(obj, key, { enumerable: !0, configurable: !0, writable: !0, value }) : obj[key] = value;
  var __publicField = (obj, key, value) => (__defNormalProp(obj, typeof key != "symbol" ? key + "" : key, value), value);

  // src/controls/slick.gridmenu.ts
  var BindingEventService = Slick.BindingEventService, SlickEvent = Slick.Event, Utils = Slick.Utils, SlickGridMenu = class {
    constructor(columns, grid, gridOptions) {
      this.columns = columns;
      this.grid = grid;
      // --
      // public API
      __publicField(this, "onAfterMenuShow", new SlickEvent());
      __publicField(this, "onBeforeMenuShow", new SlickEvent());
      __publicField(this, "onMenuClose", new SlickEvent());
      __publicField(this, "onCommand", new SlickEvent());
      __publicField(this, "onColumnsChanged", new SlickEvent());
      // --
      // protected props
      __publicField(this, "_bindingEventService");
      __publicField(this, "_gridOptions");
      __publicField(this, "_gridUid");
      __publicField(this, "_isMenuOpen", !1);
      __publicField(this, "_columnCheckboxes", []);
      __publicField(this, "_columnTitleElm");
      __publicField(this, "_commandTitleElm");
      __publicField(this, "_commandListElm");
      __publicField(this, "_headerElm", null);
      __publicField(this, "_listElm");
      __publicField(this, "_buttonElm");
      __publicField(this, "_menuElm");
      __publicField(this, "_subMenuParentId", "");
      __publicField(this, "_gridMenuOptions", null);
      __publicField(this, "_defaults", {
        showButton: !0,
        hideForceFitButton: !1,
        hideSyncResizeButton: !1,
        forceFitTitle: "Force fit columns",
        marginBottom: 15,
        menuWidth: 18,
        contentMinWidth: 0,
        resizeOnShowHeaderRow: !1,
        subMenuOpenByEvent: "mouseover",
        syncResizeTitle: "Synchronous resize",
        useClickToRepositionMenu: !0,
        headerColumnValueExtractor: (columnDef) => columnDef.name instanceof HTMLElement ? columnDef.name.innerHTML : columnDef.name || ""
      });
      this._gridUid = grid.getUID(), this._gridOptions = gridOptions, this._gridMenuOptions = Utils.extend({}, this._defaults, gridOptions.gridMenu), this._bindingEventService = new BindingEventService(), grid.onSetOptions.subscribe((_e, args) => {
        if (args && args.optionsBefore && args.optionsAfter) {
          let switchedFromRegularToFrozen = args.optionsBefore.frozenColumn >= 0 && args.optionsAfter.frozenColumn === -1, switchedFromFrozenToRegular = args.optionsBefore.frozenColumn === -1 && args.optionsAfter.frozenColumn >= 0;
          (switchedFromRegularToFrozen || switchedFromFrozenToRegular) && this.recreateGridMenu();
        }
      }), this.init(this.grid);
    }
    init(grid) {
      var _a, _b;
      this._gridOptions = grid.getOptions(), this.createGridMenu(), ((_a = this._gridMenuOptions) != null && _a.customItems || (_b = this._gridMenuOptions) != null && _b.customTitle) && console.warn('[SlickGrid] Grid Menu "customItems" and "customTitle" were deprecated to align with other Menu plugins, please use "commandItems" and "commandTitle" instead.'), grid.onBeforeDestroy.subscribe(this.destroy.bind(this));
    }
    setOptions(newOptions) {
      this._gridMenuOptions = Utils.extend({}, this._gridMenuOptions, newOptions);
    }
    createGridMenu() {
      var _a, _b, _c, _d, _e;
      let gridMenuWidth = ((_a = this._gridMenuOptions) == null ? void 0 : _a.menuWidth) || this._defaults.menuWidth;
      if (this._gridOptions && this._gridOptions.hasOwnProperty("frozenColumn") && this._gridOptions.frozenColumn >= 0 ? this._headerElm = document.querySelector(`.${this._gridUid} .slick-header-right`) : this._headerElm = document.querySelector(`.${this._gridUid} .slick-header-left`), this._headerElm.style.width = `calc(100% - ${gridMenuWidth}px)`, (Utils.isDefined((_b = this._gridMenuOptions) == null ? void 0 : _b.resizeOnShowHeaderRow) ? this._gridMenuOptions.resizeOnShowHeaderRow : this._defaults.resizeOnShowHeaderRow) && this._gridOptions.showHeaderRow) {
        let headerRow = document.querySelector(`.${this._gridUid}.slick-headerrow`);
        headerRow && (headerRow.style.width = `calc(100% - ${gridMenuWidth}px)`);
      }
      if (((_c = this._gridMenuOptions) == null ? void 0 : _c.showButton) !== void 0 ? this._gridMenuOptions.showButton : this._defaults.showButton) {
        if (this._buttonElm = document.createElement("button"), this._buttonElm.className = "slick-gridmenu-button", this._buttonElm.ariaLabel = "Grid Menu", (_d = this._gridMenuOptions) != null && _d.iconCssClass)
          this._buttonElm.classList.add(...this._gridMenuOptions.iconCssClass.split(" "));
        else {
          let iconImageElm = document.createElement("img");
          iconImageElm.src = (_e = this._gridMenuOptions) != null && _e.iconImage ? this._gridMenuOptions.iconImage : "../images/drag-handle.png", this._buttonElm.appendChild(iconImageElm);
        }
        this._headerElm.parentElement.insertBefore(this._buttonElm, this._headerElm.parentElement.firstChild), this._bindingEventService.bind(this._buttonElm, "click", this.showGridMenu.bind(this));
      }
      this._menuElm = this.createMenu(0), this.populateColumnPicker(), document.body.appendChild(this._menuElm), this._bindingEventService.bind(document.body, "mousedown", this.handleBodyMouseDown.bind(this)), this._bindingEventService.bind(document.body, "beforeunload", this.destroy.bind(this));
    }
    /** Create the menu or sub-menu(s) but without the column picker which is a separate single process */
    createMenu(level = 0, item) {
      var _a, _b, _c, _d, _e, _f, _g, _h, _i, _j, _k, _l, _m, _n;
      let maxHeight = isNaN((_a = this._gridMenuOptions) == null ? void 0 : _a.maxHeight) ? (_b = this._gridMenuOptions) == null ? void 0 : _b.maxHeight : `${(_d = (_c = this._gridMenuOptions) == null ? void 0 : _c.maxHeight) != null ? _d : 0}px`, width = isNaN((_e = this._gridMenuOptions) == null ? void 0 : _e.width) ? (_f = this._gridMenuOptions) == null ? void 0 : _f.width : `${(_h = (_g = this._gridMenuOptions) == null ? void 0 : _g.maxWidth) != null ? _h : 0}px`, subMenuCommand = item == null ? void 0 : item.command, subMenuId = level === 1 && subMenuCommand ? subMenuCommand.replaceAll(" ", "") : "";
      subMenuId && (this._subMenuParentId = subMenuId), level > 1 && (subMenuId = this._subMenuParentId);
      let menuClasses = `slick-gridmenu slick-menu-level-${level} ${this._gridUid}`, bodyMenuElm = document.body.querySelector(`.slick-gridmenu.slick-menu-level-${level}${this.getGridUidSelector()}`);
      if (bodyMenuElm) {
        if (bodyMenuElm.dataset.subMenuParent === subMenuId)
          return bodyMenuElm;
        this.destroySubMenus();
      }
      let menuElm = document.createElement("div");
      menuElm.role = "menu", menuElm.className = menuClasses, level > 0 && (menuElm.classList.add("slick-submenu"), subMenuId && (menuElm.dataset.subMenuParent = subMenuId)), menuElm.ariaLabel = level > 1 ? "SubMenu" : "Grid Menu", width && (menuElm.style.width = width), maxHeight && (menuElm.style.maxHeight = maxHeight), menuElm.style.display = "none";
      let closeButtonElm = null;
      if (level === 0) {
        closeButtonElm = document.createElement("button"), closeButtonElm.type = "button", closeButtonElm.className = "close", closeButtonElm.dataset.dismiss = "slick-gridmenu", closeButtonElm.ariaLabel = "Close";
        let spanCloseElm = document.createElement("span");
        spanCloseElm.className = "close", spanCloseElm.ariaHidden = "true", spanCloseElm.textContent = "\xD7", closeButtonElm.appendChild(spanCloseElm), menuElm.appendChild(closeButtonElm);
      }
      this._commandListElm = document.createElement("div"), this._commandListElm.className = `slick-gridmenu-custom slick-gridmenu-command-list slick-menu-level-${level}`, this._commandListElm.role = "menu", menuElm.appendChild(this._commandListElm);
      let commandItems = (_n = (_m = (_k = (_i = item == null ? void 0 : item.commandItems) != null ? _i : item == null ? void 0 : item.customItems) != null ? _k : (_j = this._gridMenuOptions) == null ? void 0 : _j.commandItems) != null ? _m : (_l = this._gridMenuOptions) == null ? void 0 : _l.customItems) != null ? _n : [];
      return commandItems.length > 0 && item && level > 0 && this.addSubMenuTitleWhenExists(item, this._commandListElm), this.populateCommandsMenu(commandItems, this._commandListElm, { grid: this.grid, level }), level++, menuElm;
    }
    /** Destroy the plugin by unsubscribing every events & also delete the menu DOM elements */
    destroy() {
      var _a;
      this.onAfterMenuShow.unsubscribe(), this.onBeforeMenuShow.unsubscribe(), this.onMenuClose.unsubscribe(), this.onCommand.unsubscribe(), this.onColumnsChanged.unsubscribe(), this.grid.onColumnsReordered.unsubscribe(this.updateColumnOrder.bind(this)), this.grid.onBeforeDestroy.unsubscribe(), this.grid.onSetOptions.unsubscribe(), this._bindingEventService.unbindAll(), (_a = this._menuElm) == null || _a.remove(), this.deleteMenu();
    }
    /** Delete the menu DOM element but without unsubscribing any events */
    deleteMenu() {
      var _a, _b;
      this._bindingEventService.unbindAll();
      let gridMenuElm = document.querySelector(`div.slick-gridmenu.${this._gridUid}`);
      gridMenuElm && (gridMenuElm.style.display = "none"), this._headerElm && (this._headerElm.style.width = "100%"), (_a = this._buttonElm) == null || _a.remove(), (_b = this._menuElm) == null || _b.remove();
    }
    /** Close and destroy all previously opened sub-menus */
    destroySubMenus() {
      this._bindingEventService.unbindAll("sub-menu"), document.querySelectorAll(`.slick-gridmenu.slick-submenu${this.getGridUidSelector()}`).forEach((subElm) => subElm.remove());
    }
    /** Construct the custom command menu items. */
    populateCommandsMenu(commandItems, commandListElm, args) {
      var _a, _b, _c, _d;
      let level = (args == null ? void 0 : args.level) || 0, isSubMenu = level > 0;
      !isSubMenu && ((_a = this._gridMenuOptions) != null && _a.commandTitle || (_b = this._gridMenuOptions) != null && _b.customTitle) && (this._commandTitleElm = document.createElement("div"), this._commandTitleElm.className = "title", this.grid.applyHtmlCode(this._commandTitleElm, this.grid.sanitizeHtmlString(this._gridMenuOptions.commandTitle || this._gridMenuOptions.customTitle)), commandListElm.appendChild(this._commandTitleElm));
      for (let i = 0, ln = commandItems.length; i < ln; i++) {
        let addClickListener = !0, item = commandItems[i], callbackArgs = {
          grid: this.grid,
          menu: this._menuElm,
          columns: this.columns,
          visibleColumns: this.getVisibleColumns()
        }, isItemVisible = this.runOverrideFunctionWhenExists(item.itemVisibilityOverride, callbackArgs), isItemUsable = this.runOverrideFunctionWhenExists(item.itemUsabilityOverride, callbackArgs);
        if (!isItemVisible)
          continue;
        Object.prototype.hasOwnProperty.call(item, "itemUsabilityOverride") && (item.disabled = !isItemUsable);
        let liElm = document.createElement("div");
        liElm.className = "slick-gridmenu-item", liElm.role = "menuitem", (item.divider || item === "divider") && (liElm.classList.add("slick-gridmenu-item-divider"), addClickListener = !1), item.disabled && liElm.classList.add("slick-gridmenu-item-disabled"), item.hidden && liElm.classList.add("slick-gridmenu-item-hidden"), item.cssClass && liElm.classList.add(...item.cssClass.split(" ")), item.tooltip && (liElm.title = item.tooltip || "");
        let iconElm = document.createElement("div");
        iconElm.className = "slick-gridmenu-icon", liElm.appendChild(iconElm), item.iconCssClass && iconElm.classList.add(...item.iconCssClass.split(" ")), item.iconImage && (iconElm.style.backgroundImage = `url(${item.iconImage})`);
        let textElm = document.createElement("span");
        if (textElm.className = "slick-gridmenu-content", this.grid.applyHtmlCode(textElm, this.grid.sanitizeHtmlString(item.title || "")), liElm.appendChild(textElm), item.textCssClass && textElm.classList.add(...item.textCssClass.split(" ")), commandListElm.appendChild(liElm), addClickListener) {
          let eventGroup = isSubMenu ? "sub-menu" : "parent-menu";
          this._bindingEventService.bind(liElm, "click", this.handleMenuItemClick.bind(this, item, level), void 0, eventGroup);
        }
        if (((_c = this._gridMenuOptions) == null ? void 0 : _c.subMenuOpenByEvent) === "mouseover" && this._bindingEventService.bind(liElm, "mouseover", (e) => {
          item.commandItems || item.customItems ? this.repositionSubMenu(item, level, e) : isSubMenu || this.destroySubMenus();
        }), item.commandItems || item.customItems) {
          let chevronElm = document.createElement("span");
          chevronElm.className = "sub-item-chevron", (_d = this._gridMenuOptions) != null && _d.subItemChevronClass ? chevronElm.classList.add(...this._gridMenuOptions.subItemChevronClass.split(" ")) : chevronElm.textContent = "\u2B9E", liElm.classList.add("slick-submenu-item"), liElm.appendChild(chevronElm);
          continue;
        }
      }
    }
    /** Build the column picker, the code comes almost untouched from the file "slick.columnpicker.js" */
    populateColumnPicker() {
      var _a;
      this.grid.onColumnsReordered.subscribe(this.updateColumnOrder.bind(this)), (_a = this._gridMenuOptions) != null && _a.columnTitle && (this._columnTitleElm = document.createElement("div"), this._columnTitleElm.className = "title", this.grid.applyHtmlCode(this._columnTitleElm, this.grid.sanitizeHtmlString(this._gridMenuOptions.columnTitle)), this._menuElm.appendChild(this._columnTitleElm)), this._bindingEventService.bind(this._menuElm, "click", this.updateColumn.bind(this)), this._listElm = document.createElement("span"), this._listElm.className = "slick-gridmenu-list", this._listElm.role = "menu";
    }
    /** Delete and then Recreate the Grid Menu (for example when we switch from regular to a frozen grid) */
    recreateGridMenu() {
      this.deleteMenu(), this.init(this.grid);
    }
    showGridMenu(e) {
      var _a, _b, _c, _d, _e, _f, _g, _h, _i, _j, _k;
      let targetEvent = e.touches ? e.touches[0] : e;
      e.preventDefault(), Utils.emptyElement(this._listElm), Utils.emptyElement(this._commandListElm);
      let commandItems = (_d = (_c = (_a = this._gridMenuOptions) == null ? void 0 : _a.commandItems) != null ? _c : (_b = this._gridMenuOptions) == null ? void 0 : _b.customItems) != null ? _d : [];
      this.populateCommandsMenu(commandItems, this._commandListElm, { grid: this.grid, level: 0 }), this.updateColumnOrder(), this._columnCheckboxes = [];
      let callbackArgs = {
        grid: this.grid,
        menu: this._menuElm,
        allColumns: this.columns,
        visibleColumns: this.getVisibleColumns()
      };
      if (this._gridMenuOptions && !this.runOverrideFunctionWhenExists(this._gridMenuOptions.menuUsabilityOverride, callbackArgs) || typeof e.stopPropagation == "function" && this.onBeforeMenuShow.notify(callbackArgs, e, this).getReturnValue() === !1)
        return;
      let columnId, columnLabel, excludeCssClass;
      for (let i = 0; i < this.columns.length; i++) {
        columnId = this.columns[i].id, excludeCssClass = this.columns[i].excludeFromGridMenu ? "hidden" : "";
        let colName = this.columns[i].name instanceof HTMLElement ? this.columns[i].name.innerHTML : this.columns[i].name || "", liElm = document.createElement("li");
        liElm.className = excludeCssClass, liElm.ariaLabel = colName;
        let checkboxElm = document.createElement("input");
        checkboxElm.type = "checkbox", checkboxElm.id = `${this._gridUid}-gridmenu-colpicker-${columnId}`, checkboxElm.dataset.columnid = String(this.columns[i].id), liElm.appendChild(checkboxElm), Utils.isDefined(this.grid.getColumnIndex(this.columns[i].id)) && !this.columns[i].hidden && (checkboxElm.checked = !0), this._columnCheckboxes.push(checkboxElm), columnLabel = (_e = this._gridMenuOptions) != null && _e.headerColumnValueExtractor ? this._gridMenuOptions.headerColumnValueExtractor(this.columns[i], this._gridOptions) : this._defaults.headerColumnValueExtractor(this.columns[i]);
        let labelElm = document.createElement("label");
        labelElm.htmlFor = `${this._gridUid}-gridmenu-colpicker-${columnId}`, this.grid.applyHtmlCode(labelElm, this.grid.sanitizeHtmlString((columnLabel instanceof HTMLElement ? columnLabel.innerHTML : columnLabel) || "")), liElm.appendChild(labelElm), this._listElm.appendChild(liElm);
      }
      if (this._gridMenuOptions && (!this._gridMenuOptions.hideForceFitButton || !this._gridMenuOptions.hideSyncResizeButton) && this._listElm.appendChild(document.createElement("hr")), !((_f = this._gridMenuOptions) != null && _f.hideForceFitButton)) {
        let forceFitTitle = ((_g = this._gridMenuOptions) == null ? void 0 : _g.forceFitTitle) || this._defaults.forceFitTitle, liElm = document.createElement("li");
        liElm.ariaLabel = forceFitTitle, liElm.role = "menuitem", this._listElm.appendChild(liElm);
        let forceFitCheckboxElm = document.createElement("input");
        forceFitCheckboxElm.type = "checkbox", forceFitCheckboxElm.id = `${this._gridUid}-gridmenu-colpicker-forcefit`, forceFitCheckboxElm.dataset.option = "autoresize", liElm.appendChild(forceFitCheckboxElm);
        let labelElm = document.createElement("label");
        labelElm.htmlFor = `${this._gridUid}-gridmenu-colpicker-forcefit`, labelElm.textContent = forceFitTitle, liElm.appendChild(labelElm), this.grid.getOptions().forceFitColumns && (forceFitCheckboxElm.checked = !0);
      }
      if (!((_h = this._gridMenuOptions) != null && _h.hideSyncResizeButton)) {
        let syncResizeTitle = ((_i = this._gridMenuOptions) == null ? void 0 : _i.syncResizeTitle) || this._defaults.syncResizeTitle, liElm = document.createElement("li");
        liElm.ariaLabel = syncResizeTitle, this._listElm.appendChild(liElm);
        let syncResizeCheckboxElm = document.createElement("input");
        syncResizeCheckboxElm.type = "checkbox", syncResizeCheckboxElm.id = `${this._gridUid}-gridmenu-colpicker-syncresize`, syncResizeCheckboxElm.dataset.option = "syncresize", liElm.appendChild(syncResizeCheckboxElm);
        let labelElm = document.createElement("label");
        labelElm.htmlFor = `${this._gridUid}-gridmenu-colpicker-syncresize`, labelElm.textContent = syncResizeTitle, liElm.appendChild(labelElm), this.grid.getOptions().syncColumnCellResize && (syncResizeCheckboxElm.checked = !0);
      }
      let buttonElm = e.target.nodeName === "BUTTON" ? e.target : e.target.querySelector("button");
      buttonElm || (buttonElm = e.target.parentElement), this._menuElm.style.display = "block", this._menuElm.style.opacity = "0", this.repositionMenu(e, this._menuElm, buttonElm);
      let menuMarginBottom = ((_j = this._gridMenuOptions) == null ? void 0 : _j.marginBottom) !== void 0 ? this._gridMenuOptions.marginBottom : this._defaults.marginBottom;
      ((_k = this._gridMenuOptions) == null ? void 0 : _k.height) !== void 0 ? this._menuElm.style.height = `${this._gridMenuOptions.height}px` : this._menuElm.style.maxHeight = `${window.innerHeight - targetEvent.clientY - menuMarginBottom}px`, this._menuElm.style.display = "block", this._menuElm.style.opacity = "1", this._menuElm.appendChild(this._listElm), this._isMenuOpen = !0, typeof e.stopPropagation == "function" && this.onAfterMenuShow.notify(callbackArgs, e, this).getReturnValue();
    }
    getGridUidSelector() {
      let gridUid = this.grid.getUID() || "";
      return gridUid ? `.${gridUid}` : "";
    }
    handleBodyMouseDown(e) {
      var _a;
      let isMenuClicked = !1;
      (_a = this._menuElm) != null && _a.contains(e.target) && (isMenuClicked = !0), isMenuClicked || document.querySelectorAll(`.slick-gridmenu.slick-submenu${this.getGridUidSelector()}`).forEach((subElm) => {
        subElm.contains(e.target) && (isMenuClicked = !0);
      }), (this._menuElm !== e.target && !isMenuClicked && !e.defaultPrevented && this._isMenuOpen || e.target.className === "close") && this.hideMenu(e);
    }
    handleMenuItemClick(item, level = 0, e) {
      var _a;
      if (item !== "divider" && !item.disabled && !item.divider) {
        let command = item.command || "";
        if (Utils.isDefined(command) && !item.commandItems && !item.customItems) {
          let callbackArgs = {
            grid: this.grid,
            command,
            item,
            allColumns: this.columns,
            visibleColumns: this.getVisibleColumns()
          };
          this.onCommand.notify(callbackArgs, e, this), typeof item.action == "function" && item.action.call(this, e, callbackArgs), !!!((_a = this._gridMenuOptions) != null && _a.leaveOpen) && !e.defaultPrevented && this.hideMenu(e), e.preventDefault(), e.stopPropagation();
        } else
          item.commandItems || item.customItems ? this.repositionSubMenu(item, level, e) : this.destroySubMenus();
      }
    }
    hideMenu(e) {
      if (this._menuElm) {
        let callbackArgs = {
          grid: this.grid,
          menu: this._menuElm,
          allColumns: this.columns,
          visibleColumns: this.getVisibleColumns()
        };
        if (this._isMenuOpen && this.onMenuClose.notify(callbackArgs, e, this).getReturnValue() === !1)
          return;
        this._isMenuOpen = !1, Utils.hide(this._menuElm);
      }
      this.destroySubMenus();
    }
    /** Update the Titles of each sections (command, commandTitle, ...) */
    updateAllTitles(gridMenuOptions) {
      this._commandTitleElm && this.grid.applyHtmlCode(this._commandTitleElm, this.grid.sanitizeHtmlString(gridMenuOptions.commandTitle || gridMenuOptions.customTitle || "")), this._columnTitleElm && this.grid.applyHtmlCode(this._columnTitleElm, this.grid.sanitizeHtmlString(gridMenuOptions.columnTitle || ""));
    }
    addSubMenuTitleWhenExists(item, commandOrOptionMenu) {
      if (item !== "divider" && (item != null && item.subMenuTitle)) {
        let subMenuTitleElm = document.createElement("div");
        subMenuTitleElm.className = "slick-menu-title", subMenuTitleElm.textContent = item.subMenuTitle;
        let subMenuTitleClass = item.subMenuTitleCssClass;
        subMenuTitleClass && subMenuTitleElm.classList.add(...subMenuTitleClass.split(" ")), commandOrOptionMenu.appendChild(subMenuTitleElm);
      }
    }
    repositionSubMenu(item, level, e) {
      e.target.classList.contains("slick-cell") && this.destroySubMenus();
      let subMenuElm = this.createMenu(level + 1, item);
      subMenuElm.style.display = "block", document.body.appendChild(subMenuElm), this.repositionMenu(e, subMenuElm);
    }
    /**
     * Reposition the menu drop (up/down) and the side (left/right)
     * @param {*} event
     */
    repositionMenu(e, menuElm, buttonElm) {
      var _a, _b, _c, _d;
      let targetEvent = e.touches ? e.touches[0] : e, isSubMenu = menuElm.classList.contains("slick-submenu"), parentElm = isSubMenu ? e.target.closest(".slick-gridmenu-item") : targetEvent.target, menuIconOffset = Utils.offset(buttonElm || this._buttonElm), menuWidth = menuElm.offsetWidth, useClickToRepositionMenu = ((_a = this._gridMenuOptions) == null ? void 0 : _a.useClickToRepositionMenu) !== void 0 ? this._gridMenuOptions.useClickToRepositionMenu : this._defaults.useClickToRepositionMenu, contentMinWidth = (_b = this._gridMenuOptions) != null && _b.contentMinWidth ? this._gridMenuOptions.contentMinWidth : this._defaults.contentMinWidth, currentMenuWidth = contentMinWidth > menuWidth ? contentMinWidth : menuWidth + 5, menuOffsetTop = useClickToRepositionMenu && targetEvent.pageY > 0 ? targetEvent.pageY : menuIconOffset.top + 10, menuOffsetLeft = useClickToRepositionMenu && targetEvent.pageX > 0 ? targetEvent.pageX : menuIconOffset.left + 10;
      if (isSubMenu && parentElm) {
        let parentOffset = Utils.offset(parentElm);
        menuOffsetLeft = (_c = parentOffset == null ? void 0 : parentOffset.left) != null ? _c : 0, menuOffsetTop = (_d = parentOffset == null ? void 0 : parentOffset.top) != null ? _d : 0;
        let gridPos = this.grid.getGridPosition(), subMenuPosCalc = menuOffsetLeft + Number(menuWidth);
        isSubMenu && (subMenuPosCalc += parentElm.clientWidth);
        let browserWidth = document.documentElement.clientWidth;
        (subMenuPosCalc >= gridPos.width || subMenuPosCalc >= browserWidth ? "left" : "right") === "left" ? (menuElm.classList.remove("dropright"), menuElm.classList.add("dropleft"), menuOffsetLeft -= menuWidth) : (menuElm.classList.remove("dropleft"), menuElm.classList.add("dropright"), isSubMenu && (menuOffsetLeft += parentElm.offsetWidth));
      } else
        menuOffsetTop += 10, menuOffsetLeft = menuOffsetLeft - currentMenuWidth + 10;
      menuElm.style.top = `${menuOffsetTop}px`, menuElm.style.left = `${menuOffsetLeft}px`, contentMinWidth > 0 && (this._menuElm.style.minWidth = `${contentMinWidth}px`);
    }
    updateColumnOrder() {
      let current = this.grid.getColumns().slice(0), ordered = new Array(this.columns.length);
      for (let i = 0; i < ordered.length; i++)
        this.grid.getColumnIndex(this.columns[i].id) === void 0 ? ordered[i] = this.columns[i] : ordered[i] = current.shift();
      this.columns = ordered;
    }
    updateColumn(e) {
      if (e.target.dataset.option === "autoresize") {
        let previousVisibleColumns = this.getVisibleColumns(), isChecked = e.target.checked;
        this.grid.setOptions({ forceFitColumns: isChecked }), this.grid.setColumns(previousVisibleColumns);
        return;
      }
      if (e.target.dataset.option === "syncresize") {
        this.grid.setOptions({ syncColumnCellResize: !!e.target.checked });
        return;
      }
      if (e.target.type === "checkbox") {
        let isChecked = e.target.checked, columnId = e.target.dataset.columnid || "", visibleColumns = [];
        if (this._columnCheckboxes.forEach((columnCheckbox, idx) => {
          columnCheckbox.checked && (this.columns[idx].hidden && (this.columns[idx].hidden = !1), visibleColumns.push(this.columns[idx]));
        }), !visibleColumns.length) {
          e.target.checked = !0;
          return;
        }
        let callbackArgs = {
          columnId,
          showing: isChecked,
          grid: this.grid,
          allColumns: this.columns,
          columns: visibleColumns,
          visibleColumns: this.getVisibleColumns()
        };
        this.grid.setColumns(visibleColumns), this.onColumnsChanged.notify(callbackArgs, e, this);
      }
    }
    getAllColumns() {
      return this.columns;
    }
    /** visible columns, we can simply get them directly from the grid */
    getVisibleColumns() {
      return this.grid.getColumns();
    }
    /**
     * Method that user can pass to override the default behavior.
     * In order word, user can choose or an item is (usable/visible/enable) by providing his own logic.
     * @param overrideFn: override function callback
     * @param args: multiple arguments provided to the override (cell, row, columnDef, dataContext, grid)
     */
    runOverrideFunctionWhenExists(overrideFn, args) {
      return typeof overrideFn == "function" ? overrideFn.call(this, args) : !0;
    }
  };
  window.Slick && (window.Slick.Controls = window.Slick.Controls || {}, window.Slick.Controls.GridMenu = SlickGridMenu);
})();
//# sourceMappingURL=slick.gridmenu.js.map
"use strict";
(() => {
  var __defProp = Object.defineProperty;
  var __defNormalProp = (obj, key, value) => key in obj ? __defProp(obj, key, { enumerable: !0, configurable: !0, writable: !0, value }) : obj[key] = value;
  var __publicField = (obj, key, value) => (__defNormalProp(obj, typeof key != "symbol" ? key + "" : key, value), value);

  // src/controls/slick.pager.ts
  var BindingEventService = Slick.BindingEventService, SlickGlobalEditorLock = Slick.GlobalEditorLock, Utils = Slick.Utils, SlickGridPager = class {
    constructor(dataView, grid, selectorOrElm, options) {
      this.dataView = dataView;
      this.grid = grid;
      // --
      // public API
      // --
      // protected props
      __publicField(this, "_container");
      // the container might be a string, a jQuery object or a native element
      __publicField(this, "_statusElm");
      __publicField(this, "_bindingEventService");
      __publicField(this, "_options");
      __publicField(this, "_defaults", {
        showAllText: "Showing all {rowCount} rows",
        showPageText: "Showing page {pageNum} of {pageCount}",
        showCountText: "From {countBegin} to {countEnd} of {rowCount} rows",
        showCount: !1,
        pagingOptions: [
          { data: 0, name: "All", ariaLabel: "Show All Pages" },
          { data: -1, name: "Auto", ariaLabel: "Auto Page Size" },
          { data: 25, name: "25", ariaLabel: "Show 25 rows per page" },
          { data: 50, name: "50", ariaLabel: "Show 50 rows per page" },
          { data: 100, name: "100", ariaLabel: "Show 100 rows per page" }
        ],
        showPageSizes: !1
      });
      this._container = this.getContainerElement(selectorOrElm), this._options = Utils.extend(!0, {}, this._defaults, options), this._bindingEventService = new BindingEventService(), this.init();
    }
    init() {
      this.constructPagerUI(), this.updatePager(this.dataView.getPagingInfo()), this.dataView.onPagingInfoChanged.subscribe((_e, pagingInfo) => {
        this.updatePager(pagingInfo);
      });
    }
    /** Destroy function when element is destroyed */
    destroy() {
      this.setPageSize(0), this._bindingEventService.unbindAll(), Utils.emptyElement(this._container);
    }
    getNavState() {
      let cannotLeaveEditMode = !SlickGlobalEditorLock.commitCurrentEdit(), pagingInfo = this.dataView.getPagingInfo(), lastPage = pagingInfo.totalPages - 1;
      return {
        canGotoFirst: !cannotLeaveEditMode && pagingInfo.pageSize !== 0 && pagingInfo.pageNum > 0,
        canGotoLast: !cannotLeaveEditMode && pagingInfo.pageSize !== 0 && pagingInfo.pageNum !== lastPage,
        canGotoPrev: !cannotLeaveEditMode && pagingInfo.pageSize !== 0 && pagingInfo.pageNum > 0,
        canGotoNext: !cannotLeaveEditMode && pagingInfo.pageSize !== 0 && pagingInfo.pageNum < lastPage,
        pagingInfo
      };
    }
    setPageSize(n) {
      this.dataView.setRefreshHints({
        isFilterUnchanged: !0
      }), this.dataView.setPagingOptions({ pageSize: n });
    }
    gotoFirst() {
      this.getNavState().canGotoFirst && this.dataView.setPagingOptions({ pageNum: 0 });
    }
    gotoLast() {
      let state = this.getNavState();
      state.canGotoLast && this.dataView.setPagingOptions({ pageNum: state.pagingInfo.totalPages - 1 });
    }
    gotoPrev() {
      let state = this.getNavState();
      state.canGotoPrev && this.dataView.setPagingOptions({ pageNum: state.pagingInfo.pageNum - 1 });
    }
    gotoNext() {
      let state = this.getNavState();
      state.canGotoNext && this.dataView.setPagingOptions({ pageNum: state.pagingInfo.pageNum + 1 });
    }
    getContainerElement(selectorOrElm) {
      return typeof selectorOrElm == "string" ? document.querySelector(selectorOrElm) : typeof selectorOrElm == "object" && selectorOrElm[0] ? selectorOrElm[0] : selectorOrElm;
    }
    constructPagerUI() {
      let container = this.getContainerElement(this._container);
      if (!container || container.jquery && !container[0])
        return;
      let navElm = document.createElement("span");
      navElm.className = "slick-pager-nav";
      let settingsElm = document.createElement("span");
      settingsElm.className = "slick-pager-settings", this._statusElm = document.createElement("span"), this._statusElm.className = "slick-pager-status";
      let pagerSettingsElm = document.createElement("span");
      pagerSettingsElm.className = "slick-pager-settings-expanded", pagerSettingsElm.textContent = "Show: ";
      for (let o = 0; o < this._options.pagingOptions.length; o++) {
        let p = this._options.pagingOptions[o], anchorElm = document.createElement("a");
        anchorElm.textContent = p.name, anchorElm.ariaLabel = p.ariaLabel, anchorElm.dataset.val = String(p.data), pagerSettingsElm.appendChild(anchorElm), this._bindingEventService.bind(anchorElm, "click", (e) => {
          let pagesize = e.target.dataset.val;
          if (pagesize !== void 0)
            if (Number(pagesize) === -1) {
              let vp = this.grid.getViewport();
              this.setPageSize(vp.bottom - vp.top);
            } else
              this.setPageSize(parseInt(pagesize));
        });
      }
      pagerSettingsElm.style.display = this._options.showPageSizes ? "block" : "none", settingsElm.appendChild(pagerSettingsElm);
      let displayPaginationContainer = document.createElement("span"), displayIconElm = document.createElement("span");
      displayPaginationContainer.className = "sgi-container", displayIconElm.ariaLabel = "Show Pagination Options", displayIconElm.role = "button", displayIconElm.className = "sgi sgi-lightbulb", displayPaginationContainer.appendChild(displayIconElm), this._bindingEventService.bind(displayIconElm, "click", () => {
        let styleDisplay = pagerSettingsElm.style.display;
        pagerSettingsElm.style.display = styleDisplay === "none" ? "inline-flex" : "none";
      }), settingsElm.appendChild(displayPaginationContainer), [
        { key: "start", ariaLabel: "First Page", callback: this.gotoFirst },
        { key: "left", ariaLabel: "Previous Page", callback: this.gotoPrev },
        { key: "right", ariaLabel: "Next Page", callback: this.gotoNext },
        { key: "end", ariaLabel: "Last Page", callback: this.gotoLast }
      ].forEach((pageBtn) => {
        let iconElm = document.createElement("span");
        iconElm.className = "sgi-container";
        let innerIconElm = document.createElement("span");
        innerIconElm.role = "button", innerIconElm.ariaLabel = pageBtn.ariaLabel, innerIconElm.className = `sgi sgi-chevron-${pageBtn.key}`, this._bindingEventService.bind(innerIconElm, "click", pageBtn.callback.bind(this)), iconElm.appendChild(innerIconElm), navElm.appendChild(iconElm);
      });
      let slickPagerElm = document.createElement("div");
      slickPagerElm.className = "slick-pager", slickPagerElm.appendChild(navElm), slickPagerElm.appendChild(this._statusElm), slickPagerElm.appendChild(settingsElm), container.appendChild(slickPagerElm);
    }
    updatePager(pagingInfo) {
      if (!this._container || this._container.jquery && !this._container[0])
        return;
      let state = this.getNavState();
      if (this._container.querySelectorAll(".slick-pager-nav span").forEach((pagerIcon) => pagerIcon.classList.remove("sgi-state-disabled")), state.canGotoFirst || this._container.querySelector(".sgi-chevron-start").classList.add("sgi-state-disabled"), state.canGotoLast || this._container.querySelector(".sgi-chevron-end").classList.add("sgi-state-disabled"), state.canGotoNext || this._container.querySelector(".sgi-chevron-right").classList.add("sgi-state-disabled"), state.canGotoPrev || this._container.querySelector(".sgi-chevron-left").classList.add("sgi-state-disabled"), pagingInfo.pageSize === 0 ? this._statusElm.textContent = this._options.showAllText.replace("{rowCount}", pagingInfo.totalRows + "").replace("{pageCount}", pagingInfo.totalPages + "") : this._statusElm.textContent = this._options.showPageText.replace("{pageNum}", pagingInfo.pageNum + 1 + "").replace("{pageCount}", pagingInfo.totalPages + ""), this._options.showCount && pagingInfo.pageSize !== 0) {
        let pageBegin = pagingInfo.pageNum * pagingInfo.pageSize, currentText = this._statusElm.textContent;
        currentText && (currentText += " - "), this._statusElm.textContent = currentText + this._options.showCountText.replace("{rowCount}", String(pagingInfo.totalRows)).replace("{countBegin}", String(pageBegin + 1)).replace("{countEnd}", String(Math.min(pageBegin + pagingInfo.pageSize, pagingInfo.totalRows)));
      }
    }
  };
  window.Slick && (window.Slick.Controls = window.Slick.Controls || {}, window.Slick.Controls.Pager = SlickGridPager);
})();
//# sourceMappingURL=slick.pager.js.map
