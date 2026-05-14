(function () {
    'use strict';

    var pathname = window.location.pathname || '';
    var isHelpdeskPage = /(^|\/)Helpdesk($|[/?#])/.test(pathname)
        || pathname.indexOf('/front/helpdesk') !== -1
        || (document.body && document.body.classList.contains('helpdesk'));

    if (!isHelpdeskPage) {
        return;
    }

    if (document.querySelector('script[type="importmap"][data-monplugin-helpdesk-importmap]')) {
        return;
    }

    function detectRootDoc() {
        var markers = ['/front/', '/plugins/', '/ajax/', '/Helpdesk'];
        for (var i = 0; i < markers.length; i++) {
            var marker = markers[i];
            var index = pathname.indexOf(marker);
            if (index > -1) {
                return pathname.slice(0, index);
            }
        }
        return '';
    }

    var root = detectRootDoc();
    var jsBase = (root || '') + '/js/';

    var importMap = document.createElement('script');
    importMap.type = 'importmap';
    importMap.dataset.monpluginHelpdeskImportmap = '1';
    importMap.textContent = JSON.stringify({
        imports: {
            'js/': jsBase,
            'js/modules/': jsBase + 'modules/',
            'js/modules/Helpdesk/IndexController.js': jsBase + 'modules/Helpdesk/IndexController.js'
        }
    });

    document.head.prepend(importMap);
})();
