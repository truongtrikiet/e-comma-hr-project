function initSummernote(selector = '.summernote') {
  if (typeof window.$ === 'undefined' || typeof window.$.fn === 'undefined' || typeof window.$.fn.summernote === 'undefined') {
    return;
  }

  window.$(selector).each(function () {
    const el = window.$(this);
    if (el.data('_summerInited')) return;
    const height = parseInt(el.data('height')) || 200;
    el.summernote({
      height: height,
    });
    el.data('_summerInited', true);
  });
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', () => initSummernote());
} else {
  initSummernote();
}

export { initSummernote };
