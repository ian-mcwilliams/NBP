<?php

    require 'link.php';
    require 'stringBuffer.php';

    class NewsPreview {
        private $mainText;
        private $imgText;
        private $img1;
        private $img2;
        private $linkObjs = array();
        private $linkCount;
        private $linkNo = 0;
        private $buffer;
        private $displayResult;
        private $submit;


        public function NewsPreview() {
            $this->buffer = new StringBuffer();
            $this->setLinkCount(filter_input(INPUT_POST, 'linkcount', FILTER_VALIDATE_INT) ?: 1);
            $this->setDisplayResult(filter_input(INPUT_POST, 'displayResult') ?: 'n');
            $this->setMainText(stripslashes(filter_input(INPUT_POST, 'maintext')) ?: '');
            $this->setImgText(stripslashes(filter_input(INPUT_POST, 'kickoffText')) ?: '');
            $this->setImg1(stripslashes(filter_input(INPUT_POST, 'kickoffFormImg')) ?: '');
            $this->setImg2(stripslashes(filter_input(INPUT_POST, 'kickoffOddsImg')) ?: '');
            $this->setSubmit(filter_input(INPUT_POST, 'submit'));
            $this->checkAddLinks();
            $this->buildLinkObjs();
            $this->checkMoveLinkObjs();
            $this->buffer->genHtml($this->getMainText(), $this->getLinkObjs(), $this->getImgText(), $this->getImg1(), $this->getImg2(), $this->getLinkCount());
        }
        
        private function checkAddLinks() {
            if ($this->getSubmit() == 'add link(s)') {
                $this->incrLinkCount(filter_input(INPUT_POST, 'linksToAdd') ?: 1);
            }
        }

        private function buildLinkObjs() {
            $linkObjs = array();
            for ($links=0;$links<$this->getLinkCount();$links++) {
                $href = stripslashes(filter_input(INPUT_POST, 'href'.$links)) ?: '';
                $text = stripslashes(filter_input(INPUT_POST, 'linktext'.$links)) ?: '';
                $linkObj = new Link($href, $text);
                array_push($linkObjs, $linkObj);
            }
            $this->setLinkObjs($linkObjs);
        }
        
        private function checkMoveLinkObjs() {
            $move = strpos($this->getSubmit(), 'move');
            if ($move !== false) {
                $remove = strpos($this->getSubmit(), 'remove');
                if ($remove !== false) {
                    $this->removeFromLinkObjs();
                } else {
                    $this->reorderLinkObjs();
                }
            }
        }
        
        private function setLinkObjs($linkObjs) {
            $this->linkObjs = $linkObjs;
        }
        
        private function removeFromLinkObjs() {
            $linkObjs = $this->getLinkObjs();
            array_splice($linkObjs, substr($this->getSubmit(), -1), 1);
            $this->setLinkObjs($linkObjs);
            $this->decrLinkCount();
        }
        
        private function reorderLinkObjs() {
            $linkObjs = $this->getLinkObjs();
            $info = substr($this->getSubmit(), 5);
            $key = substr($info, -1);
            $direction = substr($info, 0, strlen($info)-2);
            if ($key == 0 && $direction == 'up') {
                return;
            } elseif ($key == count($linkObjs)-1 && $direction == 'down') {
                return;
            }
            $mover = $linkObjs[$key];
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
            $swapper = $linkObjs[$swapKey];
            $linkObjs[$swapKey] = $mover;
            $linkObjs[$key] = $swapper;
            $this->setLinkObjs($linkObjs);
        }
        
        public function getSubmit() {
            return $this->submit;
        }

        public function setSubmit($submit) {
            $this->submit = $submit;
        }
        
        public function getDisplayResult() {
            return $this->displayResult;
        }

        public function setDisplayResult($displayResult) {
            $this->displayResult = $displayResult;
        }
        
        public function getLinkNo() {
            return $this->linkNo;
        }

        public function incrLinkNo() {
            $this->linkNo++;            
        }
        
        public function getBuffer() {
            return $this->buffer->getBuffer();
        }
        
        public function addToBuffer($str) {
            $this->buffer->addToBuffer($str);
        }

        public function getLinkCount() {
            return $this->linkCount;
        }
        
        public function incrLinkCount($int=1) {
            $this->linkCount += $int;
        }
        
        public function decrLinkCount() {
            $this->linkCount--;
        }

        public function setLinkCount($linkCount) {
            $this->linkCount = $linkCount;
        }

        public function getMainText() {
            return $this->mainText;
        }

        public function getImgText() {
            return $this->imgText;
        }

        public function getImg1() {
            return $this->img1;
        }

        public function getImg2() {
            return $this->img2;
        }

        public function getLinkObjs() {
            return $this->linkObjs;
        }

        public function setMainText($mainText) {
            $this->mainText = $mainText;
        }

        public function setImgText($imgText) {
            $this->imgText = $imgText;
        }

        public function setImg1($img1) {
            $this->img1 = $img1;
        }

        public function setImg2($img2) {
            $this->img2 = $img2;
        }


        
    }

