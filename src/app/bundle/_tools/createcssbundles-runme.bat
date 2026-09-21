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

