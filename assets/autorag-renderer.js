/* global marked, DOMPurify */
window.autoragRenderMarkdown = function (raw = '') {
  const dirty = marked.parse(raw, { mangle:false, headerIds:false });
  return DOMPurify.sanitize(dirty, {
    ALLOWED_TAGS : [
      'p','br','ul','ol','li','strong','em','code','pre','blockquote','a'
    ],
    ALLOWED_ATTR : [ 'href','target','rel','class' ]
  });
};