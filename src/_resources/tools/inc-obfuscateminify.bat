ECHO ON

ECHO %1 

ECHO OFF

copy /Y %1 temp1.js
java -jar obfuscator.jar Obfuscator temp1.js temp2.js
java -jar compiler.jar --js=temp2.js --js_output_file=temp3.js
ECHO OFF

copy /Y %3 temp4.js
type temp3.js >>temp4.js

copy /Y temp4.js %2

del temp1.js
del temp2.js
del temp3.js
del temp4.js
