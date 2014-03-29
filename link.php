<?php

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

