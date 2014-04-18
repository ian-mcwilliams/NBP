<?php

    class StringBuffer {
        private $buffer;
        private $sH;

        public function StringBuffer() {
            $this->buffer = '';
            require $this->getHtmlBuilderLocation();
            $this->sH = new ScriptHtml();
        }
        
        private function getHtmlBuilderLocation() {
            if (isset($_ENV['DOCUMENT_ROOT'])) {
                return '../html_builder/scriptHtml.php';
            } else {
                return '../html_builder/scriptHtml.php';
            }
        }

        public function getBuffer() {
            return $this->buffer;
        }

        public function addToBuffer($str) {
            $this->buffer .= $str;
        }
        
        public function genHtml($mainText, $linkObjs, $imgText, $img1, $img2, $linkCount) {
            $html = $this->sH->create('html', array(), array(
                $this->genHead('main.css'),
                $this->genBody($mainText, $linkObjs, $imgText, $img1, $img2, $linkCount)
            ));
            $this->sH->add($html);
            $this->addToBuffer($this->sH->render());
        }
        
        private function genHead($cssHref) {
            $cssLink = $this->sH->create('link', array('href'=>$cssHref));
            return $this->sH->create('head', array(), $cssLink);
        }
        
        private function genBody($mainText, $linkObjs, $imgText, $img1, $img2, $linkCount) {
            $formHtml = $this->genForm($mainText, $linkObjs, $imgText, $img1, $img2, $linkCount);
            $outputHtml = $this->genOutput($mainText, $linkObjs, $imgText, $img1, $img2);
            
            $bodyChildren = array_merge(array($formHtml), $outputHtml);
            
            return $this->sH->create('body', array(), $bodyChildren);
        }
        
        private function genForm($mainText, $linkObjs, $imgText, $img1, $img2, $linkCount) {
            $formChildren = array_merge(
                $this->genMainTextDiv($mainText),
                $this->genLinkHtmlArr($linkObjs),
                $this->genImages($imgText, $img1, $img2, $linkCount)
            );
            $formDiv = $this->sH->create('div', array('class'=>'formPanel'), $formChildren);
            return $this->sH->create('form', array('name'=>'linkform', 'action'=>'index.php', 'method'=>'post'), $formDiv);
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
            $label = $this->sH->create('text', 'Main Text:');
            $labelDiv = $this->sH->create('div', array('class'=>'label'), $label);
            $text = $this->sH->create('text', $mainText);
            $textarea = $this->sH->create('textarea', array('name'=>'maintext', 'rows'=>20, 'cols'=>68), $text);
            $textareaDiv = $this->sH->create('div', array('class'=>'textbox'), $textarea);
            $textDiv = $this->sH->create('div', array('class'=>'groupPanel'), array($labelDiv, $textareaDiv));
            return array($textDiv);
        }

        private function genLink($linkno, $href, $text) {
            return $this->sH->create('div', array('class'=>'groupPanel'), array(
                $this->genLinkTextDiv($linkno, $text),
                $this->genLinkHrefDiv($linkno, $href),
                $this->genLinkActionsDiv($linkno)
            ));
        }
        
        private function genLinkTextDiv($linkno, $text) {
            $textLabel = $this->sH->create('text', array('Text:'));
            $textLabelDiv = $this->sH->create('div', array('class'=>'label'), $textLabel);
            $textText = $this->sH->create('text', $text);
            $textTextarea = $this->sH->create('textarea', array('name'=>'linktext'.$linkno, 'rows'=>3, 'cols'=>68), $textText);
            $textTextboxDiv = $this->sH->create('div', array('class'=>'textbox'), $textTextarea);
            return $this->sH->create('div', array('class'=>'inputPanel'), array($textLabelDiv, $textTextboxDiv));
        }
        
        private function genLinkHrefDiv($linkno, $href) {
            $hrefLabel = $this->sH->create('text', array('Href:'));
            $hrefLabelDiv = $this->sH->create('div', array('class'=>'label'), $hrefLabel);
            $hrefInput = $this->sH->create('inputText', array('name'=>'href'.$linkno, 'value'=>$href, 'style'=>'width:500px'));
            $hrefTextboxDiv = $this->sH->create('div', array('class'=>'textbox'), $hrefInput);
            return $this->sH->create('div', array('class'=>'inputPanel'), array($hrefLabelDiv, $hrefTextboxDiv));
        }
        
        private function genLinkActionsDiv($linkno) {
            $removeLinkInput = $this->sH->create('inputSubmit', array('name'=>'submit', 'value'=>'remove link '.$linkno));
            $moveUpInput = $this->sH->create('inputSubmit', array('name'=>'submit', 'value'=>'move up '.$linkno));
            $moveDownInput = $this->sH->create('inputSubmit', array('name'=>'submit', 'value'=>'move down '.$linkno));
            return $this->sH->create('div', array('class'=>'buttons'), array($removeLinkInput, $moveUpInput, $moveDownInput));
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
            $textLabel = $this->sH->create('text', 'Text:');
            $textLabelDiv = $this->sH->create('div', array('class'=>'label'), $textLabel);
            $textInput = $this->sH->create('inputText', array('name'=>'kickoffText', 'value'=>htmlentities($imgText)));
            $textInputDiv = $this->sH->create('div', array('class'=>'textbox'), $textInput);
            return $this->sH->create('div', array('class'=>'groupPanel'), array($textLabelDiv, $textInputDiv));
        }
        
        private function genImage1Div($img1) {
            $image1Label = $this->sH->create('text', 'Image 1:');
            $image1LabelDiv = $this->sH->create('div', array('class'=>'label'), $image1Label);
            $image1Input = $this->sH->create('inputText', array('name'=>'kickoffFormImg', 'value'=>htmlentities($img1)));
            $image1InputDiv = $this->sH->create('div', array('class'=>'textbox'), $image1Input);
            return $this->sH->create('div', array('class'=>'groupPanel'), array($image1LabelDiv, $image1InputDiv));
        }
        
        private function genImage2Div($img2) {
            $image2Label = $this->sH->create('text', 'Image 2:');
            $image2LabelDiv = $this->sH->create('div', array('class'=>'label'), $image2Label);
            $image2Input = $this->sH->create('inputText', array('name'=>'kickoffOddsImg', 'value'=>htmlentities($img2)));
            $image2InputDiv = $this->sH->create('div', array('class'=>'textbox'), $image2Input);
            return $this->sH->create('div', array('class'=>'groupPanel'), array($image2LabelDiv, $image2InputDiv));
        }
        
        private function genButtonsDiv($linkCount) {
            $linkcountInput = $this->sH->create('inputHidden', array('name'=>'linkcount', 'value'=>$linkCount));
            $displayInput = $this->sH->create('inputHidden', array('name'=>'displayResult', 'value'=>'y'));
            $submitInput = $this->sH->create('inputSubmit', array('name'=>'submit', 'value'=>'submit'));
            $nbsp = $this->sH->create('text', '&nbsp;');
            $addLinksInput = $this->sH->create('inputSubmit', array('name'=>'submit', 'value'=>'add link(s)'));
            $addLinksLabel = $this->sH->create('text', 'click to add one, or specify:');
            $specifyLinksInput = $this->sH->create('inputText', array('name'=>'linksToAdd', 'style'=>'width:20px'));
            return $this->sH->create('div', array('class'=>'endButtons'), array(
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
        
        private function genOutput($mainText, $linkObjs, $imgText, $img1, $img2) {
            return array(
                $this->sH->getBr(),
                $this->genResultHeader(),
                $this->sH->getBr(),
                $this->sH->getBr(),
                $this->genResultDiv($mainText, $linkObjs, $imgText, $img1, $img2)
            );
        }
        
        private function genResultHeader() {
            return $this->sH->create('text', 'Result:');
        }
        
        private function genResultDiv($mainText, $linkObjs, $imgText, $img1, $img2) {
            $resultTextareaArr = array_merge(
                $this->genMainTextHtmlArr($mainText),
                $this->genLinkHtmlOutputArr($linkObjs),
                $this->genImgTextArr($imgText, $img1, $img2)
            );
            $resultTextarea = $this->sH->create('textarea', array('rows'=>50, 'cols'=>136), $resultTextareaArr);
            
            return $this->sH->create('div', array('class'=>'result'), $resultTextarea);
        }
        
        private function genMainTextHtmlArr($mainText) {
            return array($this->sH->create('text', '&lt;div style=&quot;padding-left:30px;'
                    .'width:750px;font-family:courier&quot;&gt;'
                    .$mainText."\n\n\n"));
        }
        
        private function genLinkHtmlOutputArr($linkObjs) {
            $linkHtmlArr = array();
            foreach ($linkObjs as $linkObj) {
                $linkHtmlTxtArr = $this->genLinkTextHtmlArr();
                $sHLinkHtml = $this->sH->create('text', array(
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
                '&lt;div style=&quot;padding:10px;background-color:#EEEEEE;'
                    . 'border:1px solid white;width:550px;word-wrap:break-word'
                    . '&quot;&gt;&lt;a style=&quot;display:block;text-decoration:none;'
                    . 'color:#FF0000&quot; href=&quot;',
                '&quot;&gt;',
                ':&lt;br /&gt;&lt;br /&gt;',
                '&lt;/a&gt;&lt;/div&gt;'."\n\n"
            );
        }
        
        private function genImgTextArr($imgText, $img1, $img2) {
            $imgHtmlTxt1 = "\n\n".'&lt;img src=&quot;';
            $imgHtmlTxt2 = '&quot;&nbsp;/&gt;'."\n\n".'&lt;img src=&quot;';
            $imgHtmlTxt3 = '&quot;&nbsp;/&gt'."\n\n".'&lt;/div&gt;';
            return array($imgText, $imgHtmlTxt1, $img1, $imgHtmlTxt2, $img2, $imgHtmlTxt3);
        }
        
        
    }

