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
            $html = $this->hP->gen('html', NULL, array(
                $this->genHead('main.css'),
                $this->genBody($mainText, $linkObjs, $imgText, $img1, $img2, $uploadImg, $linkCount)
            ));
            $this->hP->add($html);
            $this->addToBuffer($this->hP->render());
        }
        
        private function genHead($cssHref) {
            $cssLink = $this->hP->gen('link', "href=>$cssHref");
            return $this->hP->gen('head', NULL, $cssLink);
        }
        
        private function genBody($mainText, $linkObjs, $imgText, $img1, $img2, $uploadImg, $linkCount) {
            $formHtml = $this->genForm($mainText, $linkObjs, $imgText, $img1, $img2, $linkCount);
            $outputHtml = $this->genOutput($mainText, $linkObjs, $imgText, $img1, $img2, $uploadImg);
            
            $bodyChildren = array_merge(array($formHtml), $outputHtml);
            
            return $this->hP->gen('body', NULL, $bodyChildren);
        }
        
        private function genForm($mainText, $linkObjs, $imgText, $img1, $img2, $linkCount) {
            $formChildren = array_merge(
                $this->genMainTextDiv($mainText),
                $this->genLinkHtmlArr($linkObjs),
                $this->genImages($imgText, $img1, $img2, $linkCount)
            );
            $formDiv = $this->hP->gen('div', 'class=>formPanel', $formChildren);
            return $this->hP->gen('form', 'name=>linkform&&action=>index.php'
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
            $label = $this->hP->gen('text', 'Main Text:');
            $labelDiv = $this->hP->gen('div', 'class=>label', $label);
            $text = $this->hP->gen('text', $mainText);
            $textarea = $this->hP->gen('textarea', 'name=>maintext&&rows=>20&&cols=>68', $text);
            $textareaDiv = $this->hP->gen('div', 'class=>textbox', $textarea);
            $textDiv = $this->hP->gen('div', 'class=>groupPanel', array($labelDiv, $textareaDiv));
            return array($textDiv);
        }

        private function genLink($linkno, $href, $text) {
            return $this->hP->gen('div', 'class=>groupPanel', array(
                $this->genLinkTextDiv($linkno, $text),
                $this->genLinkHrefDiv($linkno, $href),
                $this->genLinkActionsDiv($linkno)
            ));
        }
        
        private function genLinkTextDiv($linkno, $text) {
            $textLabel = $this->hP->gen('text', 'Text:');
            $textLabelDiv = $this->hP->gen('div', 'class=>label', $textLabel);
            $textText = $this->hP->gen('text', $text);
            $textTextarea = $this->hP->gen('textarea', "name=>linktext$linkno&&rows=>3&&cols=>68", $textText);
            $textTextboxDiv = $this->hP->gen('div', 'class=>textbox', $textTextarea);
            return $this->hP->gen('div', 'class=>inputPanel', array($textLabelDiv, $textTextboxDiv));
        }
        
        private function genLinkHrefDiv($linkno, $href) {
            $hrefLabel = $this->hP->gen('text', 'Href:');
            $hrefLabelDiv = $this->hP->gen('div', 'class=>label', $hrefLabel);
            $hrefInput = $this->hP->gen('input', "type=>text&&name=>href$linkno&&value=>$href&&style=>width:500px");
            $hrefTextboxDiv = $this->hP->gen('div', 'class=>textbox', $hrefInput);
            return $this->hP->gen('div', 'class=>inputPanel', array($hrefLabelDiv, $hrefTextboxDiv));
        }
        
        private function genLinkActionsDiv($linkno) {
            $removeLinkInput = $this->hP->gen('input', "type=>submit&&name=>submit&&value=>remove link $linkno");
            $moveUpInput = $this->hP->gen('input', "type=>submit&&name=>submit&&value=>move up $linkno");
            $moveDownInput = $this->hP->gen('input', "type=>submit&&name=>submit&&value=>move down $linkno");
            return $this->hP->gen('div', 'class=>buttons', array($removeLinkInput, $moveUpInput, $moveDownInput));
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
            $textLabel = $this->hP->gen('text', 'Text:');
            $textLabelDiv = $this->hP->gen('div', 'class=>label', $textLabel);
            $textInput = $this->hP->gen('input', 'type=>text&&name=>imgText&&value=>'.htmlentities($imgText));
            $textInputDiv = $this->hP->gen('div', 'class=>textbox', $textInput);
            return $this->hP->gen('div', 'class=>groupPanel', array($textLabelDiv, $textInputDiv));
        }
        
        private function genImage1Div($img1) {
            $image1Label = $this->hP->gen('text', 'Image 1:');
            $image1LabelDiv = $this->hP->gen('div', 'class=>label', $image1Label);
            $image1Input = $this->hP->gen('input', 'type=>text&&name=>img1&&value=>'.htmlentities($img1));
            $image1InputDiv = $this->hP->gen('div', 'class=>textbox', $image1Input);
            return $this->hP->gen('div', 'class=>groupPanel', array($image1LabelDiv, $image1InputDiv));
        }
        
        private function genImage2Div($img2) {
            $image2Label = $this->hP->gen('text', 'Image 2:');
            $image2LabelDiv = $this->hP->gen('div', 'class=>label', $image2Label);
            $image2Input = $this->hP->gen('input', 'type=>text&&name=>img2&&value=>'.htmlentities($img2));
            $image2InputDiv = $this->hP->gen('div', 'class=>textbox', $image2Input);
            return $this->hP->gen('div', 'class=>groupPanel', array($image2LabelDiv, $image2InputDiv));
        }
        
        private function genUploadImageDiv($uploadImg) {
            $uploadImageLabel = $this->hP->gen('text', 'Upload Image:');
            $uploadImageLabelDiv = $this->hP->gen('div', 'class=>label', $uploadImageLabel);
            $uploadImageInput = $this->hP->gen('input', 'type=>text&&name=>uploadImg&&value=>'.htmlentities($uploadImg));
            $uploadImageInputDiv = $this->hP->gen('div', 'class=>textbox', $uploadImageInput);
            return $this->hP->gen('div', 'class=>groupPanel', array($uploadImageLabelDiv, $uploadImageInputDiv));
            
        }
        
        private function genButtonsDiv($linkCount) {
            $linkcountInput = $this->hP->gen('input', "type=>hidden&&name=>linkcount&&value=>$linkCount");
            $displayInput = $this->hP->gen('input', 'type=>hidden&&name=>displayResult&&value=>y');
            $submitInput = $this->hP->gen('input', 'type=>submit&&name=>submit&&value=>submit');
            $nbsp = $this->hP->gen('text', '&nbsp;');
            $addLinksInput = $this->hP->gen('input', 'type=>submit&&name=>submit&&value=>add link(s)');
            $addLinksLabel = $this->hP->gen('text', 'click to add one, or specify:');
            $specifyLinksInput = $this->hP->gen('input', 'type=>text&&name=>linksToAdd&&style=>width:20px');
            return $this->hP->gen('div', 'class=>endButtons', array(
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
            return $this->hP->gen('text', 'Result:');
        }
        
        private function genResultDiv($mainText, $linkObjs, $imgText, $img1, $img2, $uploadImg) {
            $resultTextareaArr = array_merge(
                $this->genMainTextHtmlArr($mainText),
                $this->genLinkHtmlOutputArr($linkObjs),
                $this->genImgTextArr($imgText, $img1, $img2, $uploadImg)
            );
            $resultTextarea = $this->hP->gen('textarea', 'rows=>50&&cols=>136', $resultTextareaArr);
            
            return $this->hP->gen('div', 'class=>result', $resultTextarea);
        }
        
        private function genMainTextHtmlArr($mainText) {
            return array($this->hP->gen('text', htmlentities('<div style="'
                    . 'padding-left:30px;width:750px;font-family:courier">')
                    ."$mainText\n\n\n"));
        }
        
        private function genLinkHtmlOutputArr($linkObjs) {
            $linkHtmlArr = array();
            foreach ($linkObjs as $linkObj) {
                $sHLinkHtml = array(
                    $linkHtmlArr[] = $this->hP->gen('text', htmlentities('<div '
                        . 'style="padding:10px;background-color:#EEEEEE;'
                        . 'border:1px solid white;width:550px;word-wrap:break-word'
                        . '"><a style="display:block;text-decoration:none;'
                        . 'color:#FF0000" href="')),
                    $linkHtmlArr[] = $this->hP->gen('text', $linkObj->getHref()),
                    $linkHtmlArr[] = $this->hP->gen('text', htmlentities('">')),
                    $linkHtmlArr[] = $this->hP->gen('text', $linkObj->getText()),
                    $linkHtmlArr[] = $this->hP->gen('text', htmlentities(':<br /><br />')),
                    $linkHtmlArr[] = $this->hP->gen('text', $linkObj->getHref()),
                    $linkHtmlArr[] = $this->hP->gen('text', htmlentities('</a></div>')."\n\n")
                );
                
            }
            return $linkHtmlArr;
        }
        
        private function genImgTextArr($imgText, $img1, $img2, $uploadImg) {
            return array(
                $this->hP->gen('text', $imgText),
                $this->hP->gen('text', "\n\n".htmlentities('<img src="')),
                $this->hP->gen('text', $img1),
                $this->hP->gen('text', htmlentities('" />')."\n\n".htmlentities('<img src="')),
                $this->hP->gen('text', $img2),
                $this->hP->gen('text', htmlentities('" />')."\n\n".htmlentities('<img src="')),
                $this->hP->gen('text', $uploadImg),
                $this->hP->gen('text', htmlentities('" />')."\n\n".htmlentities('</div>'))
            );
        }
        
        
    }

