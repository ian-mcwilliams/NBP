<?php

    require '../html_builder/scriptHtml.php';

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
            $str = '
            <div class="groupPanel">
                <div class="inputPanel">
                    <div class="label">Text:</div>
                    <div class="textbox">
                        <textarea name="linktext'.$linkno.'" rows=3 cols=68>'.$text.'</textarea>
                    </div>
                </div>
                <div class="inputPanel">
                    <div class="label">Href:</div>
                    <div class="textbox">
                        <input type="text" name="href'.$linkno.'" value="'.$href.'" style="width:500px" />
                    </div>
                </div>
                <div class="buttons">
                    <input type="submit" name="submit" value="remove link '.$linkno.'" />
                    <input type="submit" name="submit" value="move up '.$linkno.'" />
                    <input type="submit" name="submit" value="move down '.$linkno.'" />
                </div>
            </div>'."\n";
            $this->addToBuffer($str);
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

