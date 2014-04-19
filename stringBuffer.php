<?php

    class StringBuffer {
        private $buffer;
        private $hP;

        public function StringBuffer() {
            $this->buffer = '';
            require $this->getHtmlBuilderLocation();
            $this->hP = new HTPML();
        }
        
        private function getHtmlBuilderLocation() {
            if (isset($_ENV['DOCUMENT_ROOT'])) {
                return '../html_builder/HTPML.php';
            } else {
                return '../html_builder/HTPML.php';
            }
        }

        public function getBuffer() {
            return $this->buffer;
        }

        public function addToBuffer($str) {
            $this->buffer .= $str;
        }
        
        public function genHtml($mainText, $linkObjs, $imgText, $img1, $img2, $uploadImg, $linkCount) {
            $html = $this->hP->create('html', NULL, array(
                $this->genHead('main.css'),
                $this->genBody($mainText, $linkObjs, $imgText, $img1, $img2, $uploadImg, $linkCount)
            ));
            $this->hP->add($html);
            $this->addToBuffer($this->hP->render());
        }
        
        private function genHead($cssHref) {
            $cssLink = $this->hP->create('link', "href=>$cssHref");
            return $this->hP->create('head', NULL, $cssLink);
        }
        
        private function genBody($mainText, $linkObjs, $imgText, $img1, $img2, $uploadImg, $linkCount) {
            $formHtml = $this->genForm($mainText, $linkObjs, $imgText, $img1, $img2, $linkCount);
            $outputHtml = $this->genOutput($mainText, $linkObjs, $imgText, $img1, $img2, $uploadImg);
            
            $bodyChildren = array_merge(array($formHtml), $outputHtml);
            
            return $this->hP->create('body', NULL, $bodyChildren);
        }
        
        private function genForm($mainText, $linkObjs, $imgText, $img1, $img2, $linkCount) {
            $formChildren = array_merge(
                $this->genMainTextDiv($mainText),
                $this->genLinkHtmlArr($linkObjs),
                $this->genImages($imgText, $img1, $img2, $linkCount)
            );
            $formDiv = $this->hP->create('div', 'class=>formPanel', $formChildren);
            return $this->hP->create('form', 'name=>linkform&&action=>index.php'
                    . '&&method=>post&&enctype=>multipart/form-data',
                $formDiv);
        }
        
        private function genLinkHtmlArr($linkObjs) {
            $linkNo = 0;
            $linkHtmlArr = array();
            foreach($linkObjs as $linkObj) {
                $linkHtmlArr[] = $this->genLink($linkNo, $linkObj->getHref(), $linkObj->getText());
                $linkNo++;
            }
            return $linkHtmlArr;
        }
        
        private function genMainTextDiv($mainText) {
            $label = $this->hP->create('text', 'Main Text:');
            $labelDiv = $this->hP->create('div', 'class=>label', $label);
            $text = $this->hP->create('text', $mainText);
            $textarea = $this->hP->create('textarea', 'name=>maintext&&rows=>20&&cols=>68', $text);
            $textareaDiv = $this->hP->create('div', 'class=>textbox', $textarea);
            $textDiv = $this->hP->create('div', 'class=>groupPanel', array($labelDiv, $textareaDiv));
            return array($textDiv);
        }

        private function genLink($linkno, $href, $text) {
            return $this->hP->create('div', 'class=>groupPanel', array(
                $this->genLinkTextDiv($linkno, $text),
                $this->genLinkHrefDiv($linkno, $href),
                $this->genLinkActionsDiv($linkno)
            ));
        }
        
        private function genLinkTextDiv($linkno, $text) {
            $textLabel = $this->hP->create('text', 'Text:');
            $textLabelDiv = $this->hP->create('div', 'class=>label', $textLabel);
            $textText = $this->hP->create('text', $text);
            $textTextarea = $this->hP->create('textarea', "name=>linktext$linkno&&rows=>3&&cols=>68", $textText);
            $textTextboxDiv = $this->hP->create('div', 'class=>textbox', $textTextarea);
            return $this->hP->create('div', 'class=>inputPanel', array($textLabelDiv, $textTextboxDiv));
        }
        
        private function genLinkHrefDiv($linkno, $href) {
            $hrefLabel = $this->hP->create('text', 'Href:');
            $hrefLabelDiv = $this->hP->create('div', 'class=>label', $hrefLabel);
            $hrefInput = $this->hP->create('inputText', "name=>href$linkno&&value=>$href&&style=>width:500px");
            $hrefTextboxDiv = $this->hP->create('div', 'class=>textbox', $hrefInput);
            return $this->hP->create('div', 'class=>inputPanel', array($hrefLabelDiv, $hrefTextboxDiv));
        }
        
        private function genLinkActionsDiv($linkno) {
            $removeLinkInput = $this->hP->create('inputSubmit', "name=>submit&&value=>remove link $linkno");
            $moveUpInput = $this->hP->create('inputSubmit', "name=>submit&&value=>move up $linkno");
            $moveDownInput = $this->hP->create('inputSubmit', "name=>submit&&value=>move down $linkno");
            return $this->hP->create('div', 'class=>buttons', array($removeLinkInput, $moveUpInput, $moveDownInput));
        }

        private function genImages($imgText, $img1, $img2, $linkCount) {
            return array(
                $this->genImageTextDiv($imgText),
                $this->genImage1Div($img1),
                $this->genImage2Div($img2),
                $this->genButtonsDiv($linkCount)
            );
        }
        
        private function genImageTextDiv($imgText) {
            $textLabel = $this->hP->create('text', 'Text:');
            $textLabelDiv = $this->hP->create('div', 'class=>label', $textLabel);
            $textInput = $this->hP->create('inputText', 'name=>imgText&&value=>'.htmlentities($imgText));
            $textInputDiv = $this->hP->create('div', 'class=>textbox', $textInput);
            return $this->hP->create('div', 'class=>groupPanel', array($textLabelDiv, $textInputDiv));
        }
        
        private function genImage1Div($img1) {
            $image1Label = $this->hP->create('text', 'Image 1:');
            $image1LabelDiv = $this->hP->create('div', 'class=>label', $image1Label);
            $image1Input = $this->hP->create('inputText', 'name=>img1&&value=>'.htmlentities($img1));
            $image1InputDiv = $this->hP->create('div', 'class=>textbox', $image1Input);
            return $this->hP->create('div', 'class=>groupPanel', array($image1LabelDiv, $image1InputDiv));
        }
        
        private function genImage2Div($img2) {
            $image2Label = $this->hP->create('text', 'Image 2:');
            $image2LabelDiv = $this->hP->create('div', 'class=>label', $image2Label);
            $image2Input = $this->hP->create('inputText', 'name=>img2&&value=>'.htmlentities($img2));
            $image2InputDiv = $this->hP->create('div', 'class=>textbox', $image2Input);
            return $this->hP->create('div', 'class=>groupPanel', array($image2LabelDiv, $image2InputDiv));
        }
        
        private function genUploadImageDiv($uploadImg) {
            $uploadImageLabel = $this->hP->create('text', 'Upload Image:');
            $uploadImageLabelDiv = $this->hP->create('div', 'class=>label', $uploadImageLabel);
            $uploadImageInput = $this->hP->create('inputText', 'name=>uploadImg&&value=>'.htmlentities($uploadImg));
            $uploadImageInputDiv = $this->hP->create('div', 'class=>textbox', $uploadImageInput);
            return $this->hP->create('div', 'class=>groupPanel', array($uploadImageLabelDiv, $uploadImageInputDiv));
            
        }
        
        private function genButtonsDiv($linkCount) {
            $linkcountInput = $this->hP->create('inputHidden', "name=>linkcount&&value=>$linkCount");
            $displayInput = $this->hP->create('inputHidden', 'name=>displayResult&&value=>y');
            $submitInput = $this->hP->create('inputSubmit', 'name=>submit&&value=>submit');
            $nbsp = $this->hP->create('text', '&nbsp;');
            $addLinksInput = $this->hP->create('inputSubmit', 'name=>submit&&value=>add link(s)');
            $addLinksLabel = $this->hP->create('text', 'click to add one, or specify:');
            $specifyLinksInput = $this->hP->create('inputText', 'name=>linksToAdd&&style=>width:20px');
            return $this->hP->create('div', 'class=>endButtons', array(
                $linkcountInput,
                $displayInput,
                $submitInput,
                $nbsp,
                $nbsp,
                $addLinksInput,
                $addLinksLabel,
                $specifyLinksInput
            ));
        }
        
        private function genOutput($mainText, $linkObjs, $imgText, $img1, $img2, $uploadImg) {
            return array(
                $this->hP->getBr(),
                $this->genResultHeader(),
                $this->hP->getBr(),
                $this->hP->getBr(),
                $this->genResultDiv($mainText, $linkObjs, $imgText, $img1, $img2, $uploadImg)
            );
        }
        
        private function genResultHeader() {
            return $this->hP->create('text', 'Result:');
        }
        
        private function genResultDiv($mainText, $linkObjs, $imgText, $img1, $img2, $uploadImg) {
            $resultTextareaArr = array_merge(
                $this->genMainTextHtmlArr($mainText),
                $this->genLinkHtmlOutputArr($linkObjs),
                $this->genImgTextArr($imgText, $img1, $img2, $uploadImg)
            );
            $resultTextarea = $this->hP->create('textarea', 'rows=>50&&cols=>136', $resultTextareaArr);
            
            return $this->hP->create('div', 'class=>result', $resultTextarea);
        }
        
        private function genMainTextHtmlArr($mainText) {
            return array($this->hP->create('text', htmlentities('<div style="'
                    . 'padding-left:30px;width:750px;font-family:courier">')
                    ."$mainText\n\n\n"));
        }
        
        private function genLinkHtmlOutputArr($linkObjs) {
            $linkHtmlArr = array();
            foreach ($linkObjs as $linkObj) {
                $linkHtmlTxtArr = $this->genLinkTextHtmlArr();
                $sHLinkHtml = $this->hP->create('text', array(
                    $linkHtmlTxtArr[0],
                    $linkObj->getHref(),
                    $linkHtmlTxtArr[1],
                    $linkObj->getText(),
                    $linkHtmlTxtArr[2],
                    $linkObj->getHref(),
                    $linkHtmlTxtArr[3]
                ));
                $linkHtmlArr[] = $sHLinkHtml;
            }
            return $linkHtmlArr;
        }
        
        private function genLinkTextHtmlArr() {
            return array(
                htmlentities('<div style="padding:10px;background-color:#EEEEEE;'
                        . 'border:1px solid white;width:550px;word-wrap:break-word'
                        . '"><a style="display:block;text-decoration:none;'
                        . 'color:#FF0000" href="'),
                htmlentities('">'),
                htmlentities(':<br /><br />'),
                htmlentities('</a></div>')."\n\n"
            );
        }
        
        private function genImgTextArr($imgText, $img1, $img2, $uploadImg) {
            $imgHtmlTxt1 = "\n\n".htmlentities('<img src="');
            $imgHtmlTxt2 = htmlentities('" />')."\n\n".htmlentities('<img src="');
            $imgHtmlTxt3 = htmlentities('" />')."\n\n".htmlentities('</div>');
            return array(
                $imgText,
                $imgHtmlTxt1,
                $img1,
                $imgHtmlTxt2,
                $img2,
                $imgHtmlTxt2,
                $uploadImg,
                $imgHtmlTxt3
            );
        }
        
        
    }

