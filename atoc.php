<?php
// Advanced Table of Contents extension

class YellowAtoc {
    const VERSION = "0.8.1";
    public $yellow;

    public function onLoad($yellow) {
        $this->yellow = $yellow;
        $this->yellow->system->setDefault("atocLevel", "3");
        $this->yellow->system->setDefault("atocNumbering", "1");
    }

    public function onParseContentHtml($page, $text) {
        $callback = function ($matches) use ($page) {
            $location = $page->getPage("main")->getLocation(true);
            $rawData = $page->getPage("main")->parserData;
            preg_match_all("/<h(\d) id=\"([^\"]+)\">(.*?)<\/h\d>/i", $rawData, $matches, PREG_SET_ORDER);
            if ( $this->yellow->system->get("atocNumbering")) {
                $listType = "ol";
            } else {
                $listType = "ul";
            }
            $output = "<$listType class=\"atoc\">";
            $prevLevel = $nestedList = 0;
            foreach ($matches as $match) {
                if ($match[1] < $prevLevel) {
                    $nestedList = 0;
                    $output .= "</$listType>";
                } elseif ($prevLevel != 0 && $match[1] > $prevLevel) {
                    ++$nestedList;
                    $output .= "<$listType>";
                }
                $output .= "<li><a href=\"$location#$match[2]\">$match[3]</a></li>\n";
                $prevLevel = $match[1];
            }
            for ($i = 0; $i < $nestedList; $i++) {
                $output .= "</$listType>\n";
            }
            $output .= "</$listType>\n";
            return $output;
        };
        return preg_replace_callback("/<p>\[atoc\]<\/p>\n/i", $callback, $text);
    }

    public function onParsePageExtra($page, $name) {
        $output = null;
        if ($name=="header") {
            $extensionLocation = $this->yellow->system->get("coreServerBase").$this->yellow->system->get("coreExtensionLocation");
            $output = "<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"{$extensionLocation}atoc.css\" />\n";
        }
        return $output;
        return $name=="atoc" ? $this->onParseContentHtml($page, "<p>[atoc]</p>\n") : null;
    }
}
