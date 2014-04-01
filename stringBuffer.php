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

        public function StringBuffer() {
            $this->buffer = '';
        }

        public function getBuffer() {
                return $this->buffer;
        }

        public function addToBuffer($str) {
            $this->buffer .= $str;
        }
        
        public function addHeader($mainText) {
            $str = '
                <html><head>
                <link rel="stylesheet" type="text/css" href="main.css" />
                </head>
                <body>

                <form name="linkform" action="index.php" method="post">
                    <div class="formPanel">
                        <div class="groupPanel">
                            <div class="label">Main text:</div>
                            <div class="textbox">
                                <textarea name="maintext" rows=20 cols=68>'.$mainText.'</textarea>
                            </div>
                        </div>'."\n";
            $this->addToBuffer($str);
        }

        public function addLink($linkno, $href, $text) {
            $sH = new ScriptHtml();
            
            $linkPanelDiv = $sH->createElement('div', array('class'=>'groupPanel'));
            
            $textDiv = $sH->createElement('div', array('class'=>'inputPanel'));
            
            $textLabelDiv = $sH->createElement('div', array('class'=>'label'));
            $textLabel = $sH->createElement('text', array('Text:'));
            $textLabelDiv->addChildren($textLabel);
            $textDiv->addChildren($textLabelDiv);
            
            $textText = $sH->createElement('text', $text);
            $textTextarea = $sH->createElement('textarea', array('name'=>'linktext'.$linkno, 'rows'=>3, 'cols'=>68));
            $textTextarea->addChildren($textText);
            $textTextboxDiv = $sH->createElement('div', array('class'=>'textbox'));
            $textTextboxDiv->addChildren($textTextarea);
            $textDiv->addChildren($textTextboxDiv);
            
            $linkPanelDiv->addChildren($textDiv);
            
            $hrefDiv = $sH->createElement('div', array('class'=>'inputPanel'));
            
            $hrefLabelDiv = $sH->createElement('div', array('class'=>'label'));
            $hrefLabel = $sH->createElement('text', array('Href:'));
            $hrefLabelDiv->addChildren($hrefLabel);
            $hrefDiv->addChildren($hrefLabelDiv);
            
            $hrefTextboxDiv = $sH->createElement('div', array('class'=>'textbox'));
            $hrefInput = $sH->createElement('inputText', array('name'=>'href'.$linkno, 'value'=>$href, 'style'=>'width:500px'));
            $hrefTextboxDiv->addChildren($hrefInput);
            $hrefDiv->addChildren($hrefTextboxDiv);
            
            $linkPanelDiv->addChildren($hrefDiv);
            
            $linkActionsDiv = $sH->createElement('div', array('class'=>'buttons'));
            
            $removeLinkInput = $sH->createElement('inputSubmit', array('name'=>'submit', 'value'=>'remove link '.$linkno));
            $moveUpInput = $sH->createElement('inputSubmit', array('name'=>'submit', 'value'=>'move up '.$linkno));
            $moveDownInput = $sH->createElement('inputSubmit', array('name'=>'submit', 'value'=>'move down '.$linkno));
            $linkActionsDiv->addChildren(array($removeLinkInput, $moveUpInput, $moveDownInput));
            
            $linkPanelDiv->addChildren($linkActionsDiv);
            
            $sH->addChildren($linkPanelDiv);
            
            $this->addToBuffer($sH->render());
        }

        public function addImages($imgText, $img1, $img2, $linkCount) {
            $str = '
                    <div class="groupPanel">
                        <div class="label">Text:</div>
                        <div class="textbox">
                            <input type="text" name="kickoffText" value="'.htmlentities($imgText).'" />
                        </div>
                    </div>
                    <div class="groupPanel">
                        <div class="label">Image 1:</div>
                        <div class="textbox">
                            <input type="text" name="kickoffFormImg" value="'.$img1.'" />
                        </div>
                    </div>
                    <div class="groupPanel">
                        <div class="label">Image 2:</div>
                        <div class="textbox">
                            <input type="text" name="kickoffOddsImg" value="'.$img2.'" />
                        </div>
                    </div>
                    <div class="endButtons">
                        <input name="linkcount" type="hidden" value ="'.$linkCount.'" />
                        <input name="displayResult" type="hidden" value="y" />
                        <input type="submit" name="submit" value="submit" />
                        &nbsp;&nbsp;
                        <input type="submit" name="submit" value="add link(s)" />
                        click to add one, or specify: 
                        <input type="text" name="linksToAdd" style="width:20px" />
                    </div>
                </div>
            </form>'."\n";
            $this->addToBuffer($str);
        }
        
        public function addOutput($mainText, $linkObjs, $imgText, $img1, $img2) {
            $str = '
            <br />Result:<br /><br />
            <div class="result">
            <textarea rows=50 cols=136>
            &lt;div style=&quot;padding-left:30px;width:750px;font-family:consolas&quot;&gt;
            '.$mainText."\n\n\n";
            $this->addToBuffer($str);

            foreach ($linkObjs as $linkObj) {
                $str = '&lt;div style=&quot;padding:10px;background-color:#EEEEEE;border:1px solid white;width:550px;word-wrap:break-word&quot;&gt;&lt;a style=&quot;display:block;text-decoration:none;color:#FF0000&quot; href=&quot;'.$linkObj->getHref().'&quot;&gt;'.$linkObj->getText().':&lt;br /&gt;&lt;br /&gt;'.$linkObj->getHref().'&lt;/a&gt;&lt;/div&gt;'."\n\n";
                $this->addToBuffer($str);

            }

            $str = $imgText."\n\n".'&lt;img src=&quot;'.$img1.'&quot;&nbsp;/&gt;'."\n\n".'&lt;img src=&quot;'.$img2.'&quot;&nbsp;/&gt'."\n\n".'&lt;/div&gt;
            </textarea>
            </div>'."\n";

            $this->addToBuffer($str);
        }
        
        public function addFooter() {
            $str = '
    </body>
</html>';
            $this->addToBuffer($str);
        }
        
        
    }

