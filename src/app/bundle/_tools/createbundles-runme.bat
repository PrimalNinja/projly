ECHO OFF

REM mitsukibo components
REM no mitsukibo components found

REM 3rd party tools CSS with their own copyrights

CALL inc-deletebundle.bat ..\3p.css
CALL inc-appendbundle.bat copyright-3p.txt ..\3p.css

CALL inc-appendbundle.bat ..\_resources\css\html5-doctor-reset-stylesheet.min.css ..\3p.css
CALL inc-appendbundle.bat ..\_resources\css\introjs.css ..\3p.css
CALL inc-appendbundle.bat ..\_resources\css\normalize.min.css ..\3p.css

REM CALL inc-appendbundle.bat ..\_resources\css-datatables\jquery.dataTables.min.css ..\3p.css
CALL inc-appendbundle.bat ..\_resources\css-datatables\datatables.min.css ..\3p.css

CALL inc-appendbundle.bat ..\_resources\css\jquery.fileupload-ui.min.css ..\3p.css
CALL inc-appendbundle.bat ..\_resources\css\jquery.qtip.min.css ..\3p.css
CALL inc-appendbundle.bat ..\_resources\css\jquery.inputlimiter.1.0.min.css ..\3p.css

CALL inc-appendbundle.bat ..\_resources\css-jquery\autocomplete.css ..\3p.css
CALL inc-appendbundle.bat ..\_resources\css-jquery\jquery-ui.min.css ..\3p.css

CALL inc-appendbundle.bat ..\_resources\css-slickgrid\slick-default-theme.css ..\3p.css
CALL inc-appendbundle.bat ..\_resources\css-slickgrid\slick-icons.css ..\3p.css
CALL inc-appendbundle.bat ..\_resources\css-slickgrid\slick.columnpicker.css ..\3p.css
CALL inc-appendbundle.bat ..\_resources\css-slickgrid\slick.grid.css ..\3p.css

REM 3rd party tools JS (3p-bootstrap) with their own copyrights

CALL inc-deletebundle.bat ..\3p-bootstrap.js
CALL inc-appendbundle.bat copyright-3p.txt ..\3p-bootstrap.js
REM CALL inc-appendbundle.bat ..\_resources\js-jquery\jquery-1.11.2.min.js ..\3p-bootstrap.js
REM CALL inc-appendbundle.bat ..\_resources\js-jquery\jquery-migrate-1.2.1.min.js ..\3p-bootstrap.js

CALL inc-appendbundle.bat ..\_resources\js\d3.js ..\3p-bootstrap.js
CALL inc-appendbundle.bat ..\_resources\js\d3.layout.cloud.js ..\3p-bootstrap.js

REM CALL inc-appendbundle.bat ..\_resources\js\browserdetect.js ..\3p-bootstrap.js
CALL inc-appendbundle.bat ..\_resources\js\jsdiff.js ..\3p-bootstrap.js
CALL inc-appendbundle.bat ..\_resources\js\respond.js ..\3p-bootstrap.js
CALL inc-appendbundle.bat ..\_resources\js\shortcut.js ..\3p-bootstrap.js

REM 3rd party tools JS (login form) with their own copyrights

CALL inc-deletebundle.bat ..\3p.js
CALL inc-appendbundle.bat copyright-3p.txt ..\3p.js
CALL inc-appendbundle.bat ..\_resources\js-jquery\jquery.ui.touch-punch.js ..\3p.js

REM CALL inc-appendbundle.bat ..\_resources\js\browserdetect.js ..\3p.js
CALL inc-appendbundle.bat ..\_resources\js\cyborgWiki.js ..\3p.js
CALL inc-appendbundle.bat ..\_resources\js\intro.js ..\3p.js
CALL inc-appendbundle.bat ..\_resources\js\jquery.ba-hashchange.min.js ..\3p.js
REM CALL inc-appendbundle.bat ..\_resources\js\jquery.blockUI.js ..\3p.js
CALL inc-appendbundle.bat ..\_resources\js\jquery.caret.js ..\3p.js
CALL inc-appendbundle.bat ..\_resources\js\jquery.form.min.js ..\3p.js
CALL inc-appendbundle.bat ..\_resources\js\jquery.inputlimiter.1.3.1.js ..\3p.js
CALL inc-appendbundle.bat ..\_resources\js\jquery.qtip.js ..\3p.js
CALL inc-appendbundle.bat ..\_resources\js\jquery.timer.js ..\3p.js
CALL inc-appendbundle.bat ..\_resources\js\json2.js ..\3p.js
REM CALL inc-appendbundle.bat ..\_resources\js\vue.js ..\3p.js

REM CALL inc-appendbundle.bat ..\_resources\js-datatables\jquery.dataTables.min.js ..\3p.js
CALL inc-appendbundle.bat ..\_resources\js-datatables\datatables.min.js ..\3p.js

CALL inc-appendbundle.bat ..\_resources\js-bootstraplimit\bootstrap-limit.js ..\3p.js

REM 3rd party tools JS (application extras) with their own copyrights

CALL inc-deletebundle.bat ..\3p-app.js
CALL inc-appendbundle.bat copyright-3p.txt ..\3p-app.js

CALL inc-appendbundle.bat ..\_resources\js-fileupload\jquery.iframe-transport.js ..\3p-app.js
CALL inc-appendbundle.bat ..\_resources\js-fileupload\jquery.fileupload.js ..\3p-app.js
CALL inc-appendbundle.bat ..\_resources\js-fileupload\jquery.fileupload-process.js ..\3p-app.js
CALL inc-appendbundle.bat ..\_resources\js-fileupload\jquery.fileupload-audio.js ..\3p-app.js
CALL inc-appendbundle.bat ..\_resources\js-fileupload\jquery.fileupload-video.js ..\3p-app.js
CALL inc-appendbundle.bat ..\_resources\js-fileupload\jquery.fileupload-validate.js ..\3p-app.js
CALL inc-appendbundle.bat ..\_resources\js-fileupload\jquery.fileupload-ui.js ..\3p-app.js

CALL inc-appendbundle.bat ..\_resources\js-slickgrid\lib\jquery.event.drag-2.0.min.js ..\3p-app.js

CALL inc-appendbundle.bat ..\_resources\js\jquery.fileDownload.js ..\3p-app.js
CALL inc-appendbundle.bat ..\_resources\js\jquery.scrollstop.min.js ..\3p-app.js
CALL inc-appendbundle.bat ..\_resources\js\idle-timer.js ..\3p-app.js

REM minified versions

CALL inc-deletebundle.bat ..\3p-bootstrap.z.js
CALL inc-minify ..\3p-bootstrap.js ..\3p-bootstrap.z.js

CALL inc-deletebundle.bat ..\3p.z.js
CALL inc-minify ..\3p.js ..\3p.z.js

CALL inc-deletebundle.bat ..\3p-app.z.js
CALL inc-minify ..\3p-app.js ..\3p-app.z.js

REM cannot minify

CALL inc-deletebundle.bat ..\3p-nomin.js
CALL inc-appendbundle.bat ..\_resources\js-jquery\jquery-ui.min.js ..\3p-nomin.js
CALL inc-appendbundle.bat ..\_resources\js\canvas-to-blob.min.js ..\3p-nomin.js
REM CALL inc-appendbundle.bat ..\_resources\js\jquery.lazyload.min.js ..\3p-nomin.js
CALL inc-appendbundle.bat ..\_resources\js\raphael-min.js ..\3p-nomin.js
REM CALL inc-appendbundle.bat ..\_resources\js\base64v1_0.js ..\3p-nomin.js
REM CALL inc-appendbundle.bat ..\_resources\js\jpeg_encoder_basic.js ..\3p-nomin.js

CALL inc-appendbundle.bat ..\_resources\js-slickgrid\slick.core.js ..\3p-nomin.js
CALL inc-appendbundle.bat ..\_resources\js-slickgrid\slick.interactions.js ..\3p-nomin.js
CALL inc-appendbundle.bat ..\_resources\js-slickgrid\slick.grid.js ..\3p-nomin.js
CALL inc-appendbundle.bat ..\_resources\js-slickgrid\slick.editors.js ..\3p-nomin.js
CALL inc-appendbundle.bat ..\_resources\js-slickgrid\slick.formatters.js ..\3p-nomin.js
CALL inc-appendbundle.bat ..\_resources\js-slickgrid\slick.compositeeditor.js ..\3p-nomin.js
CALL inc-appendbundle.bat ..\_resources\js-slickgrid\slick.dataview.js ..\3p-nomin.js
CALL inc-appendbundle.bat ..\_resources\js-slickgrid\slick.groupitemmetadataprovider.js ..\3p-nomin.js
CALL inc-appendbundle.bat ..\_resources\js-slickgrid\slick.remotemodel.js ..\3p-nomin.js
CALL inc-appendbundle.bat ..\_resources\js-slickgrid\slick.remotemodel-yahoo.js ..\3p-nomin.js
CALL inc-appendbundle.bat ..\_resources\js-slickgrid\plugins\slick.checkboxselectcolumn.js ..\3p-nomin.js
CALL inc-appendbundle.bat ..\_resources\js-slickgrid\plugins\slick.cellrangedecorator.js ..\3p-nomin.js
CALL inc-appendbundle.bat ..\_resources\js-slickgrid\plugins\slick.cellrangeselector.js ..\3p-nomin.js
CALL inc-appendbundle.bat ..\_resources\js-slickgrid\plugins\slick.cellselectionmodel.js ..\3p-nomin.js
CALL inc-appendbundle.bat ..\_resources\js-slickgrid\plugins\slick.rowselectionmodel.js ..\3p-nomin.js
CALL inc-appendbundle.bat ..\_resources\js-slickgrid\controls\slick.columnmenu.js ..\3p-nomin.js
CALL inc-appendbundle.bat ..\_resources\js-slickgrid\controls\slick.columnpicker.js ..\3p-nomin.js
CALL inc-appendbundle.bat ..\_resources\js-slickgrid\controls\slick.gridmenu.js ..\3p-nomin.js
CALL inc-appendbundle.bat ..\_resources\js-slickgrid\controls\slick.pager.js ..\3p-nomin.js


REM create theme bundles

CALL inc-deletebundle.bat ..\..\css\themes\default\theme.css
CALL inc-appendbundle.bat ..\..\css\themes\default\behaviours.css ..\..\css\themes\default\theme.css
CALL inc-appendbundle.bat ..\..\css\themes\default\dom.css ..\..\css\themes\default\theme.css
CALL inc-appendbundle.bat ..\..\css\themes\default\images.css ..\..\css\themes\default\theme.css
CALL inc-appendbundle.bat ..\..\css\themes\default\styles.css ..\..\css\themes\default\theme.css
CALL inc-appendbundle.bat ..\..\css\themes\default\3rdparty.css ..\..\css\themes\default\theme.css
CALL inc-appendbundle.bat ..\..\css\themes\default\todeprecate.css ..\..\css\themes\default\theme.css
CALL inc-appendbundle.bat ..\..\css\themes\default\menu.css ..\..\css\themes\default\theme.css



ECHO ON

ECHO Finished creating bundle files

