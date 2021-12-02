var updateMeta = function (event) {
  var metatags = event.data.meta;
  if (metatags) {
    document.querySelectorAll('head')[0].insertAdjacentHTML('afterbegin', metatags);
    enforceWPIcon();
  }
};

var enforceWPIcon = function () {
  var wpIcon = document.querySelectorAll('.wp_favicon')[0]
  if (wpIcon) {
    var funnelIcon = document.querySelectorAll('[rel="icon"]')[0];
    funnelIcon.parentNode.removeChild(funnelIcon);
  }
}

if (window.addEventListener) {
    window.addEventListener("message", updateMeta, false);
} else if (window.attachEvent) {
    window.attachEvent("onmessage", updateMeta);
}
