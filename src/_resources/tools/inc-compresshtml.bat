ECHO ON

ECHO %1 

ECHO OFF

copy /Y %1 temp1.html
java -jar htmlcompressor.jar --type html -o temp2.html temp1.html
ECHO OFF

copy /Y temp2.html %2

del temp1.html
del temp2.html
