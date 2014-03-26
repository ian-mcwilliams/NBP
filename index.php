<?php

    class StringBuffer {
        private $buffer;

        public function StringBuffer() {
            $this->buffer = '';
        }

        public function addToBuffer($str) {
            $this->buffer .= $str;
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

        public function getBuffer() {
                return $this->buffer;
        }
    }

    class Link {
        private $href;
        private $text;

        public function Link($href, $text) {
            $this->href = $href;
            $this->text = $text;
        }

        public function getHref() {
            return $this->href;
        }

        public function getText() {
            return $this->text;
        }
    }

    function reorderArray($array, $key, $direction) {
        if ($key == 0 && $direction == 'up') {
            return $array;
        } elseif ($key == count($array)-1 && $direction == 'down') {
            return $array;
        }
        $mover = $array[$key];
        switch ($direction) {
            case 'up':
                $swapKey = $key-1;
                break;
            case 'down':
                $swapKey = $key+1;
                break;
            default:
                $swapKey = $key;
                break;
        }
        $swapper = $array[$swapKey];
        $array[$swapKey] = $mover;
        $array[$key] = $swapper;
        return $array;
    }

    $b = new StringBuffer;

    $linkcount = $_POST['linkcount'] ?: 1;
    $displayResult = $_POST['displayResult'] ?: 'n';
    $maintext = stripslashes($_POST['maintext']) ?: '';
    $kickoffText = stripslashes($_POST['kickoffText']) ?: '';
    $kickoffFormImg = stripslashes($_POST['kickoffFormImg']) ?: '';
    $kickoffOddsImg = stripslashes($_POST['kickoffOddsImg']) ?: '';
    $submit = $_POST['submit'];

    if ($submit == 'add link(s)') {
        $linksToAdd = $_POST['linksToAdd'] ?: 1;
        $linkcount = $linkcount+$linksToAdd;
    }

    $linkno = 0;
    $linkObjs = Array();
    $linkHrefs = Array();
    $linkTexts = Array();
    for ($links=0;$links<$linkcount;$links++) {
        $href = stripslashes($_POST['href'.$links]) ?: '';
        $text = stripslashes($_POST['linktext'.$links]) ?: '';
        $linkObj = new Link($href, $text);
        array_push($linkObjs, $linkObj);
    }

    $move = strpos($submit, 'move');
    if ($move !== false) {
        $remove = strpos($submit, 'remove');
        if ($remove !== false) {
            $key = substr($submit, -1);
            array_splice($linkObjs, $key, 1);
            $linkcount--;
        } else {
            $info = substr($submit, 5);
            $key = substr($info, -1);
            $direction = substr($info, 0, strlen($info)-2);
            $linkObjs = reorderArray($linkObjs, $key, $direction);
        }
    }


    $str = '

    <html><head>
    <style>
        body {
            font-size: 14;
            font-family: consolas;
        }
        .label {
            float: left;
            width: 150px;
        }
        .textbox {
            float: left;
            width: 550px;
        }
        .textbox input {
            width: 500px;
        }
        textarea {
            resize: none;
        }
        .buttons {
            float: left;
        }
        .linkPanel {

        }
        .groupPanel {
            clear: both;
            float: left;
            padding: 20px;
            border: 3px solid white;
            background-color: #EEEEEE;
        }
        .endButtons {
            clear: both;
            padding: 20px;
        }
        .result {
            clear: both;
            padding: 20px;
            border: 3px solid white;
            background-color: #EEEEEE;
        }
    </style>
    </head>
    <body>

    <form name="linkform" action="index.php" method="post">
        <div class="formPanel">
            <div class="groupPanel">
                <div class="label">Main text:</div>
                <div class="textbox">
                    <textarea name="maintext" rows=20 cols=68>'.$maintext.'</textarea>
                </div>
            </div>'."\n";
            $b->addToBuffer($str);

            while ($linkno < $linkcount) {
                $b->addLink($linkno, $linkObjs[$linkno]->getHref(), $linkObjs[$linkno]->getText());
                $linkno++;
            }

            $str = '
            <div class="groupPanel">
                <div class="label">Kickoff Text:</div>
                <div class="textbox">
                        <input type="text" name="kickoffText" value="'.$kickoffText.'" />
                </div>
            </div>
            <div class="groupPanel">
                <div class="label">Kickoff Form Image:</div>
                <div class="textbox">
                    <input type="text" name="kickoffFormImg" value="'.$kickoffFormImg.'" />
                </div>
            </div>
            <div class="groupPanel">
                <div class="label">Kickoff Odds Image:</div>
                <div class="textbox">
                    <input type="text" name="kickoffOddsImg" value="'.$kickoffOddsImg.'" />
                </div>
            </div>
            <div class="endButtons">
                <input name="linkcount" type="hidden" value ="'.$linkcount.'" />
                <input name="displayResult" type="hidden" value="y" />
                <input type="submit" name="submit" value="submit" />
                &nbsp;&nbsp;
                <input type="submit" name="submit" value="add link(s)" />
                click to add one, or specify: 
                <input type="text" name="linksToAdd" style="width:20px" />
            </div>
        </div>
    </form>'."\n";
    $b->addToBuffer($str);

    if ($displayResult == 'y') {

        $str = '
        <br />Result:<br /><br />
        <div class="result">
        <textarea rows=50 cols=136>
        &lt;div style=&quot;padding-left:30px;width:750px;font-family:consolas&quot;&gt;
        '.$maintext."\n\n\n";
        $b->addToBuffer($str);

        foreach ($linkObjs as $linkObj) {
            $str = '&lt;div style=&quot;padding:10px;background-color:#EEEEEE;border:1px solid white;width:550px;word-wrap:break-word&quot;&gt;&lt;a style=&quot;display:block;text-decoration:none;color:#FF0000&quot; href=&quot;'.$linkObj->getHref().'&quot;&gt;'.$linkObj->getText().':&lt;br /&gt;&lt;br /&gt;'.$linkObj->getHref().'&lt;/a&gt;&lt;/div&gt;'."\n\n";
            $b->addToBuffer($str);

        }

        $str = $kickoffText."\n\n".'&lt;img src=&quot;'.$kickoffFormImg.'&quot;&nbsp;/&gt;'."\n\n".'&lt;img src=&quot;'.$kickoffOddsImg.'&quot;&nbsp;/&gt'."\n\n".'&lt;/div&gt;
        </textarea>
        </div>'."\n";

        $b->addToBuffer($str);
    }

    $b->addToBuffer('</body></html>');

    echo $b->getBuffer();
	
	
	
	