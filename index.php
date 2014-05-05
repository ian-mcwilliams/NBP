<?php

    require 'newsPreview.php';
    
    if (isset($_ENV['DOCUMENT_ROOT'])) {
        require '../HTPML/HTPML.php';
        $level = 0;
    } else {
        require '../html_builder/HTPML.php';
        $level = -1;
    }
    
    error_reporting($level);
    
    $nP = new NewsPreview();

    echo $nP->getBuffer();
	
	
	
	