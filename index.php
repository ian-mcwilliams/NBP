<?php

    require_once 'link.php';
    require 'newsPreview.php';
    
    $nP = new NewsPreview();

    echo $nP->getBuffer();
	
	
	
	