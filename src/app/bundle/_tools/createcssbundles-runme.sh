#!/bin/bash
# createcssbundles-runme.sh

# mitsukibo components
# no mitsukibo components found

# 3rd party tools CSS with their own copyrights

#3P is invalid variable.
P3_CSS=../3p.css
THEME_CSS=theme.css

CSS_FILES="
../_resources/css/html5-doctor-reset-stylesheet.min.css
../_resources/css/introjs.css
../_resources/css/normalize.min.css
../_resources/css-datatables\datatables.min.css
../_resources/css/jquery.fileupload-ui.min.css
../_resources/css/jquery.qtip.min.css
../_resources/css/jquery.inputlimiter.1.0.min.css
../_resources/css-jquery/autocomplete.css
../_resources/css-jquery/jquery-ui.min.css
../_resources/css-slickgrid/slick-default-theme.css
../_resources/css-slickgrid/slick-icons.css
../_resources/css-slickgrid/slick.columnpicker.css
../_resources/css-slickgrid/slick.grid.css
"

THEMES="default"

. inc-deletebundle.sh $P3_CSS
. inc-appendbundle.sh copyright-3p.txt $P3_CSS

for APPEND_CSS in $CSS_FILES
do
    . inc-appendbundle.sh $APPEND_CSS $P3_CSS
done

# create theme bundles

echo creating theme bundles...

for THEME in $THEMES
do
    . inc-deletebundle.sh ../../css/themes/$THEME/$THEME_CSS
    . inc-appendbundle.sh ../../css/themes/$THEME/behaviours.css ../../css/themes/$THEME/$THEME_CSS
    . inc-appendbundle.sh ../../css/themes/$THEME/dom.css ../../css/themes/$THEME/$THEME_CSS
    . inc-appendbundle.sh ../../css/themes/$THEME/images.css ../../css/themes/$THEME/$THEME_CSS
    . inc-appendbundle.sh ../../css/themes/$THEME/styles.css ../../css/themes/$THEME/$THEME_CSS
    . inc-appendbundle.sh ../../css/themes/$THEME/3rdparty.css ../../css/themes/$THEME/$THEME_CSS
    . inc-appendbundle.sh ../../css/themes/$THEME/todeprecate.css ../../css/themes/$THEME/$THEME_CSS
	. inc-appendbundle.sh ../../css/themes/$THEME/menu.css ../../css/themes/$THEME/$THEME_CSS
done

echo Finished creating bundle files
