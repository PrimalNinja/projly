<h1 align="center">FMS-DEV URL Rewriter</h1>
<p align="center">Matching URLs</p>
<hr />
<!--
<center>
<a href="/fms-dev/seo/gadgets/iphone/index.html">Gadgets - Iphone</a><br />
<a href="/fms-dev/seo/gadgets/ps4/index.html">Gadgets - PS4</a><br />
<a href="/fms-dev/seo/gadgets/hero5/index.html">Gadgets - Hero 5</a><br />
</center>
-->



<strong>REWRITE URLS</strong>
<center>
<a href="/fms-dev/seo/category/gadgets/product/iphone/index.html">Gadgets - Iphone</a><br />
<a href="/fms-dev/seo/category/gadgets/product/ps4/index.html">Gadgets - PS4</a><br />
<a href="/fms-dev/seo/category/gadgets/product/hero5/index.html">Gadgets - Hero 5</a><br />
</center>
<p>Params</p>
<?php
if(!isset($urlVars))
{
    $urlVars = array();
}
?>
<p><pre><?php print_r($urlVars); ?></pre></p>

<hr />
<strong>QUERYSTRING URLS</strong>
<center>
<a href="/fms-dev/seo/index.php?category=gadgets&product=iphone">Gadgets - Iphone</a><br />
<a href="/fms-dev/seo/index.php?category=gadgets&product=ps4">Gadgets - PS4</a><br />
<a href="/fms-dev/seo/index.php?category=gadgets&product=hero5">Gadgets - Hero 5</a><br />
</center>
<p><pre><?php print_r($_GET); ?></pre></p>

