/**
 * Bundled by jsDelivr using Rollup v2.79.2 and Terser v5.39.0.
 * Original file: /npm/express-prettify@0.1.2/index.js
 *
 * Do NOT use SRI with dynamically generated files! More information: https://www.jsdelivr.com/using-sri-with-dynamic-files
 */
var n=function(n){var t=(n=n||{}).always||!1,e=n.query,o=n.spaces||2;return function(n,r,a){!0!==t&&void 0===n.query[e]||(r.json=function(n){return r.get("Content-Type")||r.set("Content-Type","application/json"),r.send(JSON.stringify(n,null,o))}),a()}};export{n as default};
