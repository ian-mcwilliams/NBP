<?php

    class StringBuffer {
        private $buffer;
        private $hP;

        public function StringBuffer() {
            $this->buffer = '';
            $this->hP = new HTPML();
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
            
            return $this->hP->gen('body', NULL, array($formHtml, $outputHtml));
        }
        
        private function genForm($mainText, $linkObjs, $imgText, $img1, $img2, $linkCount) {
            $formDiv = $this->hP->gen('div', 'class=>formPanel', array(
                $this->genMainTextDiv($mainText),
                $this->genLinkHtmlArr($linkObjs),
                $this->genImages($imgText, $img1, $img2, $linkCount)
            ));
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
            $labelDiv = $this->hP->gen('div', 'class=>label', 'Main Text:');
            $textarea = $this->hP->gen('textarea', 'name=>maintext&&rows=>20&&cols=>68', $mainText);
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
            $textLabelDiv = $this->hP->gen('div', 'class=>label', 'Text:');
            $textTextarea = $this->hP->gen('textarea', "name=>linktext$linkno&&rows=>3&&cols=>68", $text);
            $textTextboxDiv = $this->hP->gen('div', 'class=>textbox', $textTextarea);
            return $this->hP->gen('div', 'class=>inputPanel', array($textLabelDiv, $textTextboxDiv));
        }
        
        private function genLinkHrefDiv($linkno, $href) {
            $hrefLabelDiv = $this->hP->gen('div', 'class=>label', 'Href:');
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
            $textLabelDiv = $this->hP->gen('div', 'class=>label', 'Text:');
            $textInput = $this->hP->gen('input', 'type=>text&&name=>imgText&&value=>'.htmlentities($imgText));
            $textInputDiv = $this->hP->gen('div', 'class=>textbox', $textInput);
            return $this->hP->gen('div', 'class=>groupPanel', array($textLabelDiv, $textInputDiv));
        }
        
        private function genImage1Div($img1) {
            $image1LabelDiv = $this->hP->gen('div', 'class=>label', 'Image 1:');
            $image1Input = $this->hP->gen('input', 'type=>text&&name=>img1&&value=>'.htmlentities($img1));
            $image1InputDiv = $this->hP->gen('div', 'class=>textbox', $image1Input);
            return $this->hP->gen('div', 'class=>groupPanel', array($image1LabelDiv, $image1InputDiv));
        }
        
        private function genImage2Div($img2) {
            $image2LabelDiv = $this->hP->gen('div', 'class=>label', 'Image 2:');
            $image2Input = $this->hP->gen('input', 'type=>text&&name=>img2&&value=>'.htmlentities($img2));
            $image2InputDiv = $this->hP->gen('div', 'class=>textbox', $image2Input);
            return $this->hP->gen('div', 'class=>groupPanel', array($image2LabelDiv, $image2InputDiv));
        }
        
        private function genUploadImageDiv($uploadImg) {
            $uploadImageLabelDiv = $this->hP->gen('div', 'class=>label', 'Upload Image:');
            $uploadImageInput = $this->hP->gen('input', 'type=>text&&name=>uploadImg&&value=>'.htmlentities($uploadImg));
            $uploadImageInputDiv = $this->hP->gen('div', 'class=>textbox', $uploadImageInput);
            return $this->hP->gen('div', 'class=>groupPanel', array($uploadImageLabelDiv, $uploadImageInputDiv));
            
        }
        
        private function genButtonsDiv($linkCount) {
            $linkcountInput = $this->hP->gen('input', "type=>hidden&&name=>linkcount&&value=>$linkCount");
            $displayInput = $this->hP->gen('input', 'type=>hidden&&name=>displayResult&&value=>y');
            $submitInput = $this->hP->gen('input', 'type=>submit&&name=>submit&&value=>submit');
            $addLinksInput = $this->hP->gen('input', 'type=>submit&&name=>submit&&value=>add link(s)');
            $specifyLinksInput = $this->hP->gen('input', 'type=>text&&name=>linksToAdd&&style=>width:20px');
            return $this->hP->gen('div', 'class=>endButtons', array(
                $linkcountInput,
                $displayInput,
                $submitInput,
                '&nbsp;',
                '&nbsp;',
                $addLinksInput,
                'click to add one, or specify:',
                $specifyLinksInput
            ));
        }
        
        private function genOutput($mainText, $linkObjs, $imgText, $img1, $img2, $uploadImg) {
            return array(
                $this->hP->getBr(),
                'Result:',
                $this->hP->getBr(),
                $this->hP->getBr(),
                $this->genResultDiv($mainText, $linkObjs, $imgText, $img1, $img2, $uploadImg)
            );
        }
        
        private function genResultDiv($mainText, $linkObjs, $imgText, $img1, $img2, $uploadImg) {
            $resultTextarea = $this->hP->gen('textarea', 'rows=>50&&cols=>136', array(
                $this->genMainTextHtmlArr($mainText),
                $this->genLinkHtmlOutputArr($linkObjs),
                $this->genImgTextArr($imgText, $img1, $img2, $uploadImg)
            ));
            return $this->hP->gen('div', 'class=>result', $resultTextarea);
        }
        
        private function genMainTextHtmlArr($mainText) {
            return array(htmlentities('<div style="'
                    . 'padding-left:30px;width:750px;font-family:courier">')
                    ."$mainText\n\n\n");
        }
        
        private function genLinkHtmlOutputArr($linkObjs) {
            $linkHtmlArr = array();
            foreach ($linkObjs as $linkObj) {
                $sHLinkHtml = array(
                    $linkHtmlArr[] = htmlentities('<div '
                        . 'style="padding:10px;background-color:#EEEEEE;'
                        . 'border:1px solid white;width:550px;word-wrap:break-word'
                        . '"><a style="display:block;text-decoration:none;'
                        . 'color:#FF0000" href="'),
                    $linkHtmlArr[] = $linkObj->getHref(),
                    $linkHtmlArr[] = htmlentities('">'),
                    $linkHtmlArr[] = $linkObj->getText(),
                    $linkHtmlArr[] = htmlentities(':<br /><br />'),
                    $linkHtmlArr[] = $linkObj->getHref(),
                    $linkHtmlArr[] = htmlentities('</a></div>')."\n\n"
                );
                
            }
            return $linkHtmlArr;
        }
        
        private function genImgTextArr($imgText, $img1, $img2, $uploadImg) {
            return array(
                $imgText,
                "\n\n".htmlentities('<img src="'),
                $img1,
                htmlentities('" />')."\n\n".htmlentities('<img src="'),
                $img2,
                htmlentities('" />')."\n\n".htmlentities('<img src="'),
                $uploadImg,
                htmlentities('" />')."\n\n".htmlentities('</div>')
            );
        }
        
        
    }

