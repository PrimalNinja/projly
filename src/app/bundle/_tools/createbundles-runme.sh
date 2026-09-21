#!/bin/bash
# createbundles-runme.sh

# mitsukibo components
# no mitsukibo components found

# 3rd party tools with their own copyrights

P3BOOTSTRAPJS=../3p-bootstrapsample.js
P3JS=../3psample.js
P3APPJS=../3p-appsample.js

P3BOOTSTRAPZJS=../3p-bootstrapsample.z.js
P3ZJS=../3psample.z.js
P3APPZJS=../3p-appsample.z.js

# 3rd party tools JS (3p-bootstrap) with their own copyrights

. inc-deletebundle.sh
. inc-appendbundle.sh copyright-3p.txt
. inc-appendbundle.sh ../_resources/js/d3.js
. inc-appendbundle.sh ../_resources/js/d3.layout.cloud.js
. inc-appendbundle.sh ../_resources/js/jsdiff.js
. inc-appendbundle.sh ../_resources/js/respond.js
. inc-appendbundle.sh ../_resources/js/shortcut.js


. inc-deletebundle.sh
. inc-appendbundle.sh copyright-3p.txt
. inc-appendbundle.sh ../_resources/js-jquery/jquery-ui.min.js
. inc-appendbundle.sh ../_resources/js-jquery/jquery.ui.touch-punch.js

. inc-appendbundle.sh ../_resources/js/intro.js
. inc-appendbundle.sh ../_resources/js/cyborgWiki.js
. inc-appendbundle.sh ../_resources/js/jquery.ba-hashchange.min.js
. inc-appendbundle.sh ../_resources/js/jquery.blockUI.js
. inc-appendbundle.sh ../_resources/js/jquery.caret.js
. inc-appendbundle.sh ../_resources/js/jquery.form.min.js
. inc-appendbundle.sh ../_resources/js/jquery.inputlimiter.1.3.1.js
. inc-appendbundle.sh ../_resources/js/jquery.qtip.js
. inc-appendbundle.sh ../_resources/js/jquery.timer.js
. inc-appendbundle.sh ../_resources/js/json2.js

. inc-appendbundle.sh ../_resources\js-datatables\datatables.min.js

. inc-appendbundle.sh ../_resources/js-bootstraplimit/bootstrap-limit.js


# 3rd party tools JS (application extras) with their own copyrights

. inc-deletebundle.sh
. inc-appendbundle.sh copyright-3p.txt

. inc-appendbundle.sh ../_resources/js-fileupload/jquery.iframe-transport.js
. inc-appendbundle.sh ../_resources/js-fileupload/jquery.fileupload.js
. inc-appendbundle.sh ../_resources/js-fileupload/jquery.fileupload-process.js
. inc-appendbundle.sh ../_resources/js-fileupload/jquery.fileupload-audio.js
. inc-appendbundle.sh ../_resources/js-fileupload/jquery.fileupload-video.js
. inc-appendbundle.sh ../_resources/js-fileupload/jquery.fileupload-validate.js
. inc-appendbundle.sh ../_resources/js-fileupload/jquery.fileupload-ui.js

. inc-appendbundle.sh ../_resources/js-slickgrid/lib/jquery.event.drag-2.0.min.js

. inc-appendbundle.sh ../_resources/js-slickgrid/slick.core.js
. inc-appendbundle.sh ../_resources/js-slickgrid/slick.interactions.js
. inc-appendbundle.sh ../_resources/js-slickgrid/slick.grid.js
. inc-appendbundle.sh ../_resources/js-slickgrid/slick.editors.js
. inc-appendbundle.sh ../_resources/js-slickgrid/slick.formatters.js
. inc-appendbundle.sh ../_resources/js-slickgrid/slick.compositeeditor.js
. inc-appendbundle.sh ../_resources/js-slickgrid/slick.dataview.js
. inc-appendbundle.sh ../_resources/js-slickgrid/slick.groupitemmetadataprovider.js
. inc-appendbundle.sh ../_resources/js-slickgrid/slick.remotemodel.js
. inc-appendbundle.sh ../_resources/js-slickgrid/slick.remotemodel-yahoo.js
. inc-appendbundle.sh ../_resources/js-slickgrid/plugins/slick.checkboxselectcolumn.js
. inc-appendbundle.sh ../_resources/js-slickgrid/plugins/slick.cellrangedecorator.js
. inc-appendbundle.sh ../_resources/js-slickgrid/plugins/slick.cellrangeselector.js
. inc-appendbundle.sh ../_resources/js-slickgrid/plugins/slick.cellselectionmodel.js
. inc-appendbundle.sh ../_resources/js-slickgrid/plugins/slick.rowselectionmodel.js
. inc-appendbundle.sh ../_resources/js-slickgrid/controls/slick.columnmenu.js
. inc-appendbundle.sh ../_resources/js-slickgrid/controls/slick.columnpicker.js
. inc-appendbundle.sh ../_resources/js-slickgrid/controls/slick.gridmenu.js
. inc-appendbundle.sh ../_resources/js-slickgrid/controls/slick.pager.js

. inc-appendbundle.sh ../_resources/js/canvas-to-blob.min.js
. inc-appendbundle.sh ../_resources/js/jquery.fileDownload.js
. inc-appendbundle.sh ../_resources/js/jquery.lazyload.min.js
. inc-appendbundle.sh ../_resources/js/jquery.scrollstop.min.js
. inc-appendbundle.sh ../_resources/js/idle-timer.js
. inc-appendbundle.sh ../_resources/js/raphael-min.js


# minified versions

. inc-deletebundle.sh
. inc-minify.sh

. inc-deletebundle.sh
. inc-minify.sh

. inc-deletebundle.sh
. inc-minify.sh

