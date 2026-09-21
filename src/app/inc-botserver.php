<?php

	function serveBotHTML($strAncor_a)
	{
		$strFormID = '';
		
		if (strlen($strAncor_a) > 0)
		{
			$strAncor = str_replace('#', '', $strAncor_a);
			$arrAncor = explode('.', $strAncor);
			$strModuleName = $arrAncor[0];
			$strFormName = $arrAncor[1];
			$strFormID = $strModuleName . '_' . $strFormName;
			$strFormHTMLPath = '';

			if (DEBUG_SOURCE)
			{
				$strFormHTMLPath = DYNAMIC_APP_DIR_PHP . 'modules/' . $strModuleName . '/' . $strFormName . '.htm';
			}
			else
			{
				$strFormHTMLPath = DYNAMIC_APP_DIR_PHP . 'modules/' . $strModuleName . '/' . $strFormName . '.z.htm';
				// note: if this fails, then it should revert to non-debug source
			}

			if(PHPQUERY_ENABLED === 'TRUE')
            {
                require_once('php/phpQuery/phpQuery.php');	// note: seems scope isn't supported but we only loaded one document into our doc anyhow

                // fetch the document & put it into phpquery
                $strFormHTML = file_get_contents($strFormHTMLPath);
            
                // make the form not a template and visible
                $strFormHTML = str_replace($strFormID . 'Template', $strFormID, $strFormHTML);
                
                $doc = phpQuery::newDocumentHTML($strFormHTML);
                phpQuery::selectDocument($doc);

                // make the form visible
                pq('#' . $strFormID)->removeClass('gb-hidden');
                
                // fix dynamic images
                $images = pq('img');
                foreach($images as $image) 
                {
                    $strSrc = pq($image)->attr('dynamicsrc');
                    if (strlen($strSrc) > 0)
                    {
                        $strSrc = str_replace('%DYNAMIC_APP_DIR_URL%', DYNAMIC_APP_DIR_URL, $strSrc);
                        pq($image)->attr('src', $strSrc);
                    }
                };

                // manipulate the document
                if (file_exists(DYNAMIC_APP_DIR_PHP . 'modules/' . $strModuleName . '/' . $strFormName . '.php'))
                {
                    require_once(DYNAMIC_APP_DIR_PHP . 'modules/' . $strModuleName . '/' . $strFormName . '.php');
                }
                
                // output the document with it's JS (if loaded)
                $strHTML = $doc->htmlOuter();
                $strHTML = str_replace("\xEF\xBB\xBF", '', $strHTML); 
            }
            else
            {
                $strHTML = "";
            }
            
			echo($strHTML);
		}
		
		return $strFormID;
	}

	function serveBotJS($strAncor_a)
	{
		if (strlen($strAncor_a) > 0)
		{
			$arrAncor = explode('.', $strAncor_a);
			$strModuleName = $arrAncor[0];
			$strFormName = $arrAncor[1];
			$strFormID = $strModuleName . '_' . $strFormName;
			$strFormJSPath = '';

			if (DEBUG_SOURCE)
			{
				$strFormJSPath = DYNAMIC_APP_DIR_URL . 'modules/' . $strModuleName . '/' . $strFormName . '.js';
			}
			else
			{
				$strFormJSPath = DYNAMIC_APP_DIR_URL . 'modules/' . $strModuleName . '/' . $strFormName . '.z.js';
				// note: if this fails, then it should revert to non-debug source
			}

			$strFormJS = '<script type="text/javascript" src="' . $strFormJSPath . '"></script>';

			echo($strFormJS);
		}
	}
	
?>
