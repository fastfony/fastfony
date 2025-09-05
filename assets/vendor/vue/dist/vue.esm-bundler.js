/**
 * Bundled by jsDelivr using Rollup v2.79.2 and Terser v5.39.0.
 * Original file: /npm/vue@3.5.21/dist/vue.esm-bundler.js
 *
 * Do NOT use SRI with dynamically generated files! More information: https://www.jsdelivr.com/using-sri-with-dynamic-files
 */
import*as e from"@vue/runtime-dom";import{registerRuntimeCompiler as o}from"@vue/runtime-dom";export*from"@vue/runtime-dom";import{compile as n}from"@vue/compiler-dom";import{isString as t,NOOP as m,genCacheKey as r,extend as u}from"@vue/shared";
/**
* vue v3.5.21
* (c) 2018-present Yuxi (Evan) You and Vue contributors
* @license MIT
**/const i=Object.create(null);function s(o,s){if(!t(o)){if(!o.nodeType)return m;o=o.innerHTML}const c=r(o,s),p=i[c];if(p)return p;if("#"===o[0]){const e=document.querySelector(o);o=e?e.innerHTML:""}const f=u({hoistStatic:!0,onError:void 0,onWarn:m},s);f.isCustomElement||"undefined"==typeof customElements||(f.isCustomElement=e=>!!customElements.get(e));const{code:d}=n(o,f),l=new Function("Vue",d)(e);return l._rc=!0,i[c]=l}o(s);export{s as compile};export default null;
