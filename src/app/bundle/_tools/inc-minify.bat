ECHO ON

ECHO %1 

ECHO OFF

copy /Y %1 temp1.js
java -jar compiler.jar --js=temp1.js --js_output_file=temp2.js
ECHO OFF

CALL inc-appendbundle.bat copyright-3p.txt %2
CALL inc-appendbundle.bat temp2.js %2

del temp1.js
del temp2.js
