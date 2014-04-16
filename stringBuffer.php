<?php
    function getHtmlBuilderLocation() {
        if (isset($_ENV['DOCUMENT_ROOT'])) {
            return '';
        } else {
            return '../html_builder/scriptHtml.php';
        }
    }
    require getHtmlBuilderLocation();

    class StringBuffer {
        private $buffer;
        private $sH;

        public function StringBuffer() {
            $this->buffer = '';
            $this->sH = new ScriptHtml();
        }

        public function getBuffer() {
                return $this->buffer;
        }

        public function addToBuffer($str) {
            $this->buffer .= $str;
        }
        
        public function addForm($mainText, $linkObjs, $imgText, $img1, $img2, $linkCount) {
            $sH = new ScriptHtml();
            $mainTextHtml = $this->addHeader($mainText);
            $linkNo = 0;
            $linkHtmlArr = array();
            foreach($linkObjs as $linkObj) {
                $linkHtmlArr[] = $this->addLink($linkNo, $linkObj->getHref(), $linkObj->getText());
                $linkNo++;
            }
            $imagesHtml = $this->addImages($imgText, $img1, $img2, $linkCount);
            
            $formChildren = array_merge(
                array($mainTextHtml),
                $linkHtmlArr,
                $imagesHtml
            );
            $formDiv = $sH->create('div', array('class'=>'formPanel'), $formChildren);
            $form = $sH->create('form', array('name'=>'linkform', 'action'=>'index.php', 'method'=>'post'), $formDiv);
            return $form;
        }
        
        public function addHtml($mainText, $linkObjs, $imgText, $img1, $img2, $linkCount) {
            $sH = new ScriptHtml();
            $formHtml = $this->addForm($mainText, $linkObjs, $imgText, $img1, $img2, $linkCount);
            $outputHtml = $this->addOutput($mainText, $linkObjs, $imgText, $img1, $img2);
            
            $bodyChildren = array_merge(array($formHtml), $outputHtml);
            
            $cssLink = $sH->create('link', array('href'=>'main.css'));
            $head = $sH->create('head', array(), $cssLink);
            $body = $sH->create('body', array(), $bodyChildren);
            $html = $sH->create('html', array(), array($head, $body));
            $sH->add($html);
            $this->addToBuffer($sH->render());
        }
        
        public function addHeader($mainText) {
            $sH = new scriptHtml();
            
            //textDiv
            //labelDiv
            $label = $sH->create('text', 'Main Text:');
            $labelDiv = $sH->create('div', array('class'=>'label'), $label);
            
            //textareaDiv
            $text = $sH->create('text', $mainText);
            $textarea = $sH->create('textarea', array('name'=>'maintext', 'rows'=>20, 'cols'=>68), $text);
            $textareaDiv = $sH->create('div', array('class'=>'textbox'), $textarea);
            
            $textDiv = $sH->create('div', array('class'=>'groupPanel'), array($labelDiv, $textareaDiv));
            
            return $textDiv;
        }

        public function addLink($linkno, $href, $text) {
            $sH = new ScriptHtml();
            
            //textDiv
            $textLabel = $sH->create('text', array('Text:'));
            $textLabelDiv = $sH->create('div', array('class'=>'label'), $textLabel);
            
            $textText = $sH->create('text', $text);
            $textTextarea = $sH->create('textarea', array('name'=>'linktext'.$linkno, 'rows'=>3, 'cols'=>68), $textText);
            $textTextboxDiv = $sH->create('div', array('class'=>'textbox'), $textTextarea);
            
            $textDiv = $sH->create('div', array('class'=>'inputPanel'), array($textLabelDiv, $textTextboxDiv));
            
            //hrefDiv
            $hrefLabel = $sH->create('text', array('Href:'));
            $hrefLabelDiv = $sH->create('div', array('class'=>'label'), $hrefLabel);
            
            $hrefInput = $sH->create('inputText', array('name'=>'href'.$linkno, 'value'=>$href, 'style'=>'width:500px'));
            $hrefTextboxDiv = $sH->create('div', array('class'=>'textbox'), $hrefInput);
            
            $hrefDiv = $sH->create('div', array('class'=>'inputPanel'), array($hrefLabelDiv, $hrefTextboxDiv));
            
            //linkActionsDiv            
            $removeLinkInput = $sH->create('inputSubmit', array('name'=>'submit', 'value'=>'remove link '.$linkno));
            $moveUpInput = $sH->create('inputSubmit', array('name'=>'submit', 'value'=>'move up '.$linkno));
            $moveDownInput = $sH->create('inputSubmit', array('name'=>'submit', 'value'=>'move down '.$linkno));
            
            $linkActionsDiv = $sH->create('div', array('class'=>'buttons'), array($removeLinkInput, $moveUpInput, $moveDownInput));
            
            //linkPanelDiv
            $linkPanelDiv = $sH->create('div', array('class'=>'groupPanel'), array($textDiv, $hrefDiv, $linkActionsDiv));
            
            return $linkPanelDiv;
        }

        public function addImages($imgText, $img1, $img2, $linkCount) {
            $sH = new ScriptHtml();
            
            $textLabel = $sH->create('text', 'Text:');
            $textLabelDiv = $sH->create('div', array('class'=>'label'), $textLabel);
            $textInput = $sH->create('inputText', array('name'=>'kickoffText', 'value'=>htmlentities($imgText)));
            $textInputDiv = $sH->create('div', array('class'=>'textbox'), $textInput);
            $textDiv = $sH->create('div', array('class'=>'groupPanel'), array($textLabelDiv, $textInputDiv));
            
            $image1Label = $sH->create('text', 'Image 1:');
            $image1LabelDiv = $sH->create('div', array('class'=>'label'), $image1Label);
            $image1Input = $sH->create('inputText', array('name'=>'kickoffFormImg', 'value'=>htmlentities($img1)));
            $image1InputDiv = $sH->create('div', array('class'=>'textbox'), $image1Input);
            $image1Div = $sH->create('div', array('class'=>'groupPanel'), array($image1LabelDiv, $image1InputDiv));
            
            $image2Label = $sH->create('text', 'Image 2:');
            $image2LabelDiv = $sH->create('div', array('class'=>'label'), $image2Label);
            $image2Input = $sH->create('inputText', array('name'=>'kickoffOddsImg', 'value'=>htmlentities($img2)));
            $image2InputDiv = $sH->create('div', array('class'=>'textbox'), $image2Input);
            $image2Div = $sH->create('div', array('class'=>'groupPanel'), array($image2LabelDiv, $image2InputDiv));
            
            $linkcountInput = $sH->create('inputHidden', array('name'=>'linkcount', 'value'=>$linkCount));
            $displayInput = $sH->create('inputHidden', array('name'=>'displayResult', 'value'=>'y'));
            $submitInput = $sH->create('inputSubmit', array('name'=>'submit', 'value'=>'submit'));
            $nbsp = $sH->create('text', '&nbsp;');
            $addLinksInput = $sH->create('inputSubmit', array('name'=>'submit', 'value'=>'add link(s)'));
            $addLinksLabel = $sH->create('text', 'click to add one, or specify:');
            $specifyLinksInput = $sH->create('inputText', array('name'=>'linksToAdd', 'style'=>'width:20px'));
            $buttonsDiv = $sH->create('div', array('class'=>'endButtons'), array(
                $linkcountInput,
                $displayInput,
                $submitInput,
                $nbsp,
                $nbsp,
                $addLinksInput,
                $addLinksLabel,
                $specifyLinksInput
                    ));
            
            return array($textDiv, $image1Div, $image2Div, $buttonsDiv);
        }
        
        public function addOutput($mainText, $linkObjs, $imgText, $img1, $img2) {
            $sH = new ScriptHtml();
//            
            $br = $sH->getBr();
            $resultHeader = $sH->create('text', 'Result:');
            
            $mainTextHtml = $sH->create('text', '&lt;div style=&quot;padding-left:30px;'
                    .'width:750px;font-family:consolas&quot;&gt;'
                    .$mainText."\n\n\n");
            
            
            $linkHtmlArr = array();
            foreach ($linkObjs as $linkObj) {
                $linkHtmlTxt1 = '&lt;div style=&quot;padding:10px;background-color:#EEEEEE;'
                        . 'border:1px solid white;width:550px;word-wrap:break-word'
                        . '&quot;&gt;&lt;a style=&quot;display:block;text-decoration:none;'
                        . 'color:#FF0000&quot; href=&quot;';
                $linkHtmlTxt2 = '&quot;&gt;';
                $linkHtmlTxt3 = ':&lt;br /&gt;&lt;br /&gt;';
                $linkHtmlTxt4 = '&lt;/a&gt;&lt;/div&gt;'."\n\n";
                $sHLinkHtml = $sH->create('text', array(
                    $linkHtmlTxt1,
                    $linkObj->getHref(),
                    $linkHtmlTxt2,
                    $linkObj->getText(),
                    $linkHtmlTxt3,
                    $linkObj->getHref(),
                    $linkHtmlTxt4
                        ));
                //array_push($linkHtmlArr, $sHLinkHtml);
                $linkHtmlArr[] = $sHLinkHtml;

            }
            
            $imgHtmlTxt1 = "\n\n".'&lt;img src=&quot;';
            $imgHtmlTxt2 = '&quot;&nbsp;/&gt;'."\n\n".'&lt;img src=&quot;';
            $imgHtmlTxt3 = '&quot;&nbsp;/&gt'."\n\n".'&lt;/div&gt;';
            $resultTextareaArr = array_merge(
                array($mainTextHtml),
                $linkHtmlArr,
                array($imgText, $imgHtmlTxt1, $img1, $imgHtmlTxt2, $img2, $imgHtmlTxt3)
            );
            $resultTextarea = $sH->create('textarea', array('rows'=>50, 'cols'=>136), $resultTextareaArr);
            
            $resultDiv = $sH->create('div', array('class'=>'result'), $resultTextarea);
            
            return array(
                $br,
                $resultHeader,
                $br,
                $br,
                $resultDiv
            );
        }
        
        
    }

