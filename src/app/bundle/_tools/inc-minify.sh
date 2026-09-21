#!/bin/bash
# inc-minify.sh

# mitsukibo components
# minifying files

echo minifying $1 

# -f - force copy (no prompt) /Y in batch
# -v verbose - explain what is being done.
cp -fv $1 temp1.js

java -jar compiler.jar --js=temp1.js --js_output_file=temp2.js

. inc-appendbundle.sh copyright-3p.txt $2
. inc-appendbundle.sh temp2.js $2

rm temp1.js
rm temp2.js
