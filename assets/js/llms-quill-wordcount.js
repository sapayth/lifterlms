(()=>{var n={850:function(n){n.exports=(()=>{"use strict";var n={314:(n,e,t)=>{t.r(e),t.d(e,{default:()=>u,wordsCount:()=>l,wordsSplit:()=>a,wordsDetect:()=>i});var r=[",","，",".","。",":","：",";","；","[","]","【","]","】","{","｛","}","｝","(","（",")","）","<","《",">","》","$","￥","!","！","?","？","~","～","'","’",'"',"“","”","*","/","\\","&","%","@","#","^","、","、","、","、"],o={words:[],count:0},i=function(n){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{};if(!n)return o;var t=String(n);if(""===t.trim())return o;var i=e.punctuationAsBreaker?" ":"",l=e.disableDefaultPunctuation?[]:r,a=e.punctuation||[];l.concat(a).forEach((function(n){var e=new RegExp("\\"+n,"g");t=t.replace(e,i)})),t=(t=(t=(t=t.replace(/[\uFF00-\uFFEF\u2000-\u206F]/g,"")).replace(/\s+/," ")).split(" ")).filter((function(n){return n.trim()}));var u=new RegExp("(\\d+)|[a-zA-ZÀ-ÿĀ-ſƀ-ɏɐ-ʯḀ-ỿЀ-ӿԀ-ԯഀ-ൿ]+|[⺀-⻿⼀-⿟　-〿㇀-㇯㈀-㋿㌀-㏿㐀-㿿䀀-䶿一-俿倀-忿怀-濿瀀-翿耀-迿退-鿿豈-﫿぀-ゟ゠-ヿㇰ-ㇿ㆐-㆟ᄀ-ᇿ㄰-㆏ꥠ-꥿가-꿿뀀-뿿쀀-쿿퀀-힯ힰ-퟿]","g"),c=[];return t.forEach((function(n){var e,t=[];do{(e=u.exec(n))&&t.push(e[0])}while(e);0===t.length?c.push(n):c=c.concat(t)})),{words:c,count:c.length}},l=function(n){return i(n,arguments.length>1&&void 0!==arguments[1]?arguments[1]:{}).count},a=function(n){return i(n,arguments.length>1&&void 0!==arguments[1]?arguments[1]:{}).words};const u=l}},e={};function t(r){if(e[r])return e[r].exports;var o=e[r]={exports:{}};return n[r](o,o.exports,t),o.exports}return t.d=(n,e)=>{for(var r in e)t.o(e,r)&&!t.o(n,r)&&Object.defineProperty(n,r,{enumerable:!0,get:e[r]})},t.o=(n,e)=>Object.prototype.hasOwnProperty.call(n,e),t.r=n=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(n,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(n,"__esModule",{value:!0})},t(314)})()}},e={};function t(r){var o=e[r];if(void 0!==o)return o.exports;var i=e[r]={exports:{}};return n[r].call(i.exports,i,i.exports,t),i.exports}t.n=n=>{var e=n&&n.__esModule?()=>n.default:()=>n;return t.d(e,{a:e}),e},t.d=(n,e)=>{for(var r in e)t.o(e,r)&&!t.o(n,r)&&Object.defineProperty(n,r,{enumerable:!0,get:e[r]})},t.o=(n,e)=>Object.prototype.hasOwnProperty.call(n,e),(()=>{"use strict";var n=t(850),e=t.n(n);function r(n){return(new Intl.NumberFormat).format(n)}function o(n,e,t){const o=document.createElement("i");return o.className=`ql-wordcount-${n}`,o.style.opacity="0.5",o.style.marginRight="10px",o.innerHTML=`${e}: ${r(t)}`,o}function i(n){const{l10n:e,min:t,max:r}=n,i=document.createElement("div");return i.className="ql-wordcount ql-toolbar ql-snow",i.style.marginTop="-1px",i.style.fontSize="85%",t&&i.appendChild(o("min",e.min,t)),r&&i.appendChild(o("max",e.max,r)),i}function l(n,e){const{min:t,max:r,colorWarning:o,colorError:i}=e;let l="initial";return t&&n<t||r&&n>r?l=i:r&&n>=.9*r&&(l=o),l}function a(){let n=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{};return n={min:null,max:null,colorWarning:"#ff922b",colorError:"#e5554e",onChange:()=>{},l10n:{},...n},n.l10n={singular:"word",plural:"words",min:"Minimum",max:"Maximum",...n.l10n},n}function u(n){let t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{};t=a(t);const o=i(t),u=document.createElement("span");u.className="ql-wordcount-counter",u.style.float="right",o.appendChild(u);const c=()=>{const o=e()(n.getText());u.style.color=l(o,t);const i=1===o?t.l10n.singular:t.l10n.plural;u.innerHTML=r(o)+" "+i,t.onChange(n,t,o)};c(),n.container.parentNode.insertBefore(o,n.container.nextSibling),n.on("text-change",c)}!function(){const{Quill:n}=window;void 0!==n&&n.register("modules/wordcount",u)}()})()})();