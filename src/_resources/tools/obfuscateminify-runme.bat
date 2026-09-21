ECHO OFF

REM mitsukibo OS components
CALL inc-obfuscateminify ..\..\index.js ..\..\index.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\extend.js ..\..\extend.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\showform.js ..\..\showform.z.js copyright-projly.txt

CALL inc-obfuscateminify ..\..\app\inc-nav.js ..\..\app\inc-nav.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\inc-navmap-client.js ..\..\app\inc-navmap-client.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\inc-navmap-public.js ..\..\app\inc-navmap-public.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\inc-navmap-developer.js ..\..\app\inc-navmap-developer.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\inc-navmap-sysadmin.js ..\..\app\inc-navmap-sysadmin.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\inc-navmap-sysowner.js ..\..\app\inc-navmap-sysowner.z.js copyright-projly.txt

CALL inc-obfuscateminify ..\..\app\inc-osutils.js ..\..\app\inc-osutils.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\inc-osutils-classes.js ..\..\app\inc-osutils-classes.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\inc-osutils-jcalendar.js ..\..\app\inc-osutils-jcalendar.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\inc-osutils-jcanvas.js ..\..\app\inc-osutils-jcanvas.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\inc-osutils-jcodeeditor.js ..\..\app\inc-osutils-jcodeeditor.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\inc-osutils-jdock.js ..\..\app\inc-osutils-jdock.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\inc-osutils-jeditor.js ..\..\app\inc-osutils-jeditor.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\inc-osutils-jformrenderer.js ..\..\app\inc-osutils-jformrenderer.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\inc-osutils-jgrids.js ..\..\app\inc-osutils-jgrids.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\inc-os.js ..\..\app\inc-os.z.js copyright-projly.txt

REM mitsukibo core components
CALL inc-obfuscateminify ..\..\app\modules\core\frmChangePassword.js ..\..\app\modules\core\frmChangePassword.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\modules\core\frmCustom.js ..\..\app\modules\core\frmCustom.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\modules\core\frmGallery.js ..\..\app\modules\core\frmGallery.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\modules\core\frmHealth.js ..\..\app\modules\core\frmHealth.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\modules\core\frmImageViewer.js ..\..\app\modules\core\frmImageViewer.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\modules\core\frmLicenceChecker.js ..\..\app\modules\core\frmLicenceChecker.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\modules\core\frmLicensing.js ..\..\app\modules\core\frmLicensing.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\modules\core\frmLister.js ..\..\app\modules\core\frmLister.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\modules\core\frmLogin.js ..\..\app\modules\core\frmLogin.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\modules\core\frmMenu.js ..\..\app\modules\core\frmMenu.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\modules\core\frmPasswordConfirmation.js ..\..\app\modules\core\frmPasswordConfirmation.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\modules\core\frmPasswordFailed.js ..\..\app\modules\core\frmPasswordFailed.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\modules\core\frmPayment.js ..\..\app\modules\core\frmPayment.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\modules\core\frmPrintPreview.js ..\..\app\modules\core\frmPrintPreview.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\modules\core\frmPublic.js ..\..\app\modules\core\frmPublic.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\modules\core\frmRecordChooser.js ..\..\app\modules\core\frmRecordChooser.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\modules\core\frmRegister.js ..\..\app\modules\core\frmRegister.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\modules\core\frmRegisterConfirmation.js ..\..\app\modules\core\frmRegisterConfirmation.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\modules\core\frmRegisterFailed.js ..\..\app\modules\core\frmRegisterFailed.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\modules\core\frmRegisterVerification.js ..\..\app\modules\core\frmRegisterVerification.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\modules\core\frmTaskbar.js ..\..\app\modules\core\frmTaskbar.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\modules\core\frmTaskbarExtend.js ..\..\app\modules\core\frmTaskbarExtend.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\modules\core\frmThemes.js ..\..\app\modules\core\frmThemes.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\modules\core\frmUpload.js ..\..\app\modules\core\frmUpload.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\modules\core\frmVerified.js ..\..\app\modules\core\frmVerified.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\modules\core\frmVerifiedFailed.js ..\..\app\modules\core\frmVerifiedFailed.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\modules\core\frmVideo.js ..\..\app\modules\core\frmVideo.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\modules\core\frmWelcome.js ..\..\app\modules\core\frmWelcome.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\modules\core\frmWikiForm.js ..\..\app\modules\core\frmWikiForm.z.js copyright-projly.txt

CALL inc-compresshtml ..\..\app\modules\core\frmChangePassword.htm ..\..\app\modules\core\frmChangePassword.z.htm
CALL inc-compresshtml ..\..\app\modules\core\frmCustom.htm ..\..\app\modules\core\frmCustom.z.htm
CALL inc-compresshtml ..\..\app\modules\core\frmGallery.htm ..\..\app\modules\core\frmGallery.z.htm
CALL inc-compresshtml ..\..\app\modules\core\frmHealth.htm ..\..\app\modules\core\frmHealth.z.htm
CALL inc-compresshtml ..\..\app\modules\core\frmImageViewer.htm ..\..\app\modules\core\frmImageViewer.z.htm
CALL inc-compresshtml ..\..\app\modules\core\frmLicenceChecker.htm ..\..\app\modules\core\frmLicenceChecker.z.htm
CALL inc-compresshtml ..\..\app\modules\core\frmLicensing.htm ..\..\app\modules\core\frmLicensing.z.htm
CALL inc-compresshtml ..\..\app\modules\core\frmLister.htm ..\..\app\modules\core\frmLister.z.htm
CALL inc-compresshtml ..\..\app\modules\core\frmLogin.htm ..\..\app\modules\core\frmLogin.z.htm
CALL inc-compresshtml ..\..\app\modules\core\frmMenu.htm ..\..\app\modules\core\frmMenu.z.htm
CALL inc-compresshtml ..\..\app\modules\core\frmPasswordConfirmation.htm ..\..\app\modules\core\frmPasswordConfirmation.z.htm
CALL inc-compresshtml ..\..\app\modules\core\frmPasswordFailed.htm ..\..\app\modules\core\frmPasswordFailed.z.htm
CALL inc-compresshtml ..\..\app\modules\core\frmPayment.htm ..\..\app\modules\core\frmPayment.z.htm
CALL inc-compresshtml ..\..\app\modules\core\frmPrintPreview.htm ..\..\app\modules\core\frmPrintPreview.z.htm
CALL inc-compresshtml ..\..\app\modules\core\frmPublic.htm ..\..\app\modules\core\frmPublic.z.htm
CALL inc-compresshtml ..\..\app\modules\core\frmRecordChooser.htm ..\..\app\modules\core\frmRecordChooser.z.htm
CALL inc-compresshtml ..\..\app\modules\core\frmRegister.htm ..\..\app\modules\core\frmRegister.z.htm
CALL inc-compresshtml ..\..\app\modules\core\frmRegisterConfirmation.htm ..\..\app\modules\core\frmRegisterConfirmation.z.htm
CALL inc-compresshtml ..\..\app\modules\core\frmRegisterFailed.htm ..\..\app\modules\core\frmRegisterFailed.z.htm
CALL inc-compresshtml ..\..\app\modules\core\frmRegisterVerification.htm ..\..\app\modules\core\frmRegisterVerification.z.htm
CALL inc-compresshtml ..\..\app\modules\core\frmTaskbar.htm ..\..\app\modules\core\frmTaskbar.z.htm
CALL inc-compresshtml ..\..\app\modules\core\frmTaskbarExtend.htm ..\..\app\modules\core\frmTaskbarExtend.z.htm
CALL inc-compresshtml ..\..\app\modules\core\frmThemes.htm ..\..\app\modules\core\frmThemes.z.htm
CALL inc-compresshtml ..\..\app\modules\core\frmUpload.htm ..\..\app\modules\core\frmUpload.z.htm
CALL inc-compresshtml ..\..\app\modules\core\frmVerified.htm ..\..\app\modules\core\frmVerified.z.htm
CALL inc-compresshtml ..\..\app\modules\core\frmVerifiedFailed.htm ..\..\app\modules\core\frmVerifiedFailed.z.htm
CALL inc-compresshtml ..\..\app\modules\core\frmVideo.htm ..\..\app\modules\core\frmVideo.z.htm
CALL inc-compresshtml ..\..\app\modules\core\frmWelcome.htm ..\..\app\modules\core\frmWelcome.z.htm
CALL inc-compresshtml ..\..\app\modules\core\frmWikiForm.htm ..\..\app\modules\core\frmWikiForm.z.htm

REM mitsukibo dash components
CALL inc-obfuscateminify ..\..\app\modules\dash\frmProject.js ..\..\app\modules\dash\frmProject.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\modules\dash\frmSecurity.js ..\..\app\modules\dash\frmSecurity.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\modules\dash\frmWelcome.js ..\..\app\modules\dash\frmWelcome.z.js copyright-projly.txt

CALL inc-compresshtml ..\..\app\modules\dash\frmProject.htm ..\..\app\modules\dash\frmProject.z.htm copyright-projly.txt
CALL inc-compresshtml ..\..\app\modules\dash\frmSecurity.htm ..\..\app\modules\dash\frmSecurity.z.htm copyright-projly.txt
CALL inc-compresshtml ..\..\app\modules\dash\frmWelcome.htm ..\..\app\modules\dash\frmWelcome.z.htm copyright-projly.txt

REM mitsukibo entity components
CALL inc-obfuscateminify ..\..\app\modules\entity\frmEntityChooser.js ..\..\app\modules\entity\frmEntityChooser.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\modules\entity\frmForm.js ..\..\app\modules\entity\frmForm.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\modules\entity\frmHTMLForm.js ..\..\app\modules\entity\frmHTMLForm.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\modules\entity\frmLister.js ..\..\app\modules\entity\frmLister.z.js copyright-projly.txt

CALL inc-compresshtml ..\..\app\modules\entity\frmEntityChooser.htm ..\..\app\modules\entity\frmEntityChooser.z.htm
CALL inc-compresshtml ..\..\app\modules\entity\frmForm.htm ..\..\app\modules\entity\frmForm.z.htm
CALL inc-compresshtml ..\..\app\modules\entity\frmHTMLForm.htm ..\..\app\modules\entity\frmHTMLForm.z.htm
CALL inc-compresshtml ..\..\app\modules\entity\frmLister.htm ..\..\app\modules\entity\frmLister.z.htm

REM mitsukibo entitylogic components
CALL inc-obfuscateminify ..\..\app\modules\entitylogic\server.js ..\..\app\modules\entitylogic\server.z.js copyright-projly.txt

REM mitsukibo leaflet components
CALL inc-obfuscateminify ..\..\app\modules\leaflet\frmMap.js ..\..\app\modules\leaflet\frmMap.z.js copyright-projly.txt

CALL inc-compresshtml ..\..\app\modules\leaflet\frmMap.htm ..\..\app\modules\leaflet\frmMap.z.htm

REM mitsukibo msg components
CALL inc-obfuscateminify ..\..\app\modules\msg\frmMyComposeMessage.js ..\..\app\modules\msg\frmMyComposeMessage.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\modules\msg\frmMyMessage.js ..\..\app\modules\msg\frmMyMessage.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\modules\msg\frmMyMessages.js ..\..\app\modules\msg\frmMyMessages.z.js copyright-projly.txt

CALL inc-compresshtml ..\..\app\modules\msg\frmMyComposeMessage.htm ..\..\app\modules\msg\frmMyComposeMessage.z.htm
CALL inc-compresshtml ..\..\app\modules\msg\frmMyMessage.htm ..\..\app\modules\msg\frmMyMessage.z.htm
CALL inc-compresshtml ..\..\app\modules\msg\frmMyMessages.htm ..\..\app\modules\msg\frmMyMessages.z.htm

REM mitsukibo widgets
CALL inc-obfuscateminify ..\..\app\modules\widgetcalculator\wgtCalculator.js ..\..\app\modules\widgetcalculator\wgtCalculator.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\modules\widgetclock\wgtClock.js ..\..\app\modules\widgetclock\wgtClock.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\modules\widgetdesktopsizer\wgtDesktopSizer.js ..\..\app\modules\widgetdesktopsizer\wgtDesktopSizer.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\modules\widgetdock\wgtDock.js ..\..\app\modules\widgetdock\wgtDock.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\modules\widgetformbuilder\wgtFormBuilder.js ..\..\app\modules\widgetformbuilder\wgtFormBuilder.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\modules\widgetpanel\wgtContent.js ..\..\app\modules\widgetpanel\wgtContent.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\modules\widgetpanel\wgtPanel.js ..\..\app\modules\widgetpanel\wgtPanel.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\modules\widgetprintertray\wgtPrinter.js ..\..\app\modules\widgetprintertray\wgtPrinter.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\modules\widgetspeech\wgtSpeech.js ..\..\app\modules\widgetspeech\wgtSpeech.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\modules\widgetterm\wgtTerm.js ..\..\app\modules\widgetterm\wgtTerm.z.js copyright-projly.txt
CALL inc-obfuscateminify ..\..\app\modules\widgettube\wgtTube.js ..\..\app\modules\widgettube\wgtTube.z.js copyright-projly.txt

CALL inc-compresshtml ..\..\app\modules\widgetcalculator\wgtCalculator.htm ..\..\app\modules\widgetcalculator\wgtCalculator.z.htm
CALL inc-compresshtml ..\..\app\modules\widgetclock\wgtClock.htm ..\..\app\modules\widgetclock\wgtClock.z.htm
CALL inc-compresshtml ..\..\app\modules\widgetdesktopsizer\wgtDesktopSizer.htm ..\..\app\modules\widgetdesktopsizer\wgtDesktopSizer.z.htm
CALL inc-compresshtml ..\..\app\modules\widgetdock\wgtDock.htm ..\..\app\modules\widgetdock\wgtDock.z.htm
CALL inc-compresshtml ..\..\app\modules\widgetformbuilder\wgtFormBuilder.htm ..\..\app\modules\widgetformbuilder\wgtFormBuilder.z.htm
CALL inc-compresshtml ..\..\app\modules\widgetpanel\wgtContent.htm ..\..\app\modules\widgetpanel\wgtContent.z.htm
CALL inc-compresshtml ..\..\app\modules\widgetpanel\wgtPanel.htm ..\..\app\modules\widgetpanel\wgtPanel.z.htm
CALL inc-compresshtml ..\..\app\modules\widgetprintertray\wgtPrinter.htm ..\..\app\modules\widgetprintertray\wgtPrinter.z.htm
CALL inc-compresshtml ..\..\app\modules\widgetspeech\wgtSpeech.htm ..\..\app\modules\widgetspeech\wgtSpeech.z.htm
CALL inc-compresshtml ..\..\app\modules\widgetterm\wgtTerm.htm ..\..\app\modules\widgetterm\wgtTerm.z.htm
CALL inc-compresshtml ..\..\app\modules\widgettube\wgtTube.htm ..\..\app\modules\widgettube\wgtTube.z.htm

ECHO ON

ECHO Finished obfuscating and minifying JS files