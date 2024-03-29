<?php
// Advanced Table of Contents extension

class YellowAtoc {
    const VERSION = "0.8.2";
    public $yellow;     // access to API

    // Initialization
    public function onLoad($yellow) {
        $this->yellow = $yellow;
        $this->yellow->system->setDefault("atocNumbering", "0");
        $this->yellow->system->setDefault("atocLevel", "4");
    }

    // Handle page content in HTML format
    public function onParseContentHtml($page, $text) {
        $callback = function ($matches) use ($page) {
            $location = $page->getPage("main")->getLocation(true);
            $rawData = $page->getPage("main")->parserData;
            $atocLevel = $this->yellow->system->get("atocLevel");

            preg_match_all("/<h([1-$atocLevel]) id=\"(.*?)\">(.*?)<\/h\d>/i", $rawData, $matches, PREG_PATTERN_ORDER);

            // Variables
            if ($this->yellow->system->get("atocNumbering")) {$listType = "ol";} else {$listType = "ul";}
            $level = $matches[1];
            $anchor = $matches[2];
            $title = $matches[3];
            $min = min($level);
            $count = count($level);
            $prev = $min - 1;
            $counter = 0;

            // Start list
            $output = "<!-- AToC -->" . "<nav class=\"atoc\">";
            for ( $a = $counter; $a < $count; $a++ ) {
                // Variables
                $current = $level[$a];
                $diff = $current - $prev;
                $entry = "<a href=\"$location#$anchor[$a]\">$title[$a]</a>";

                if ($current > $prev) {
                    for ($i = 1; $i <= $diff; $i++) {$output .= "\n<$listType><li>";}
                } elseif ($current == $prev) {
                    $output .= "</li>\n<li>";
                } elseif ( $current < $prev ) {
                    for ($i = -1; $i >= $diff; $i--) {$output .= "</li></$listType>\n";}
                    $output .= "</li>\n<li>";
                }

                $output .= "$entry";

                if ($a == $count - 1) {
                 for ($i = 1; $i <= $diff; $i++) {$output .= "</li></$listType>\n";}
                    $output .= "</li>";
                }
                $prev = $current;
            }
            
            // Close list
            $output .= "</$listType>\n";
            $output .= "</nav>" . "<!-- /AToC -->"; 
            return $output;
        }; 
        return preg_replace_callback("/<p>\[atoc\]<\/p>\n/i", $callback, $text);
    } 
    // Handle page extra data
    public function onParsePageExtra($page, $name) {
        $output = null;
        // Add stylesheet
        if ($name=="header") {
            $extensionLocation = $this->yellow->system->get("coreServerBase").$this->yellow->system->get("coreExtensionLocation");
            $output = "<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"{$extensionLocation}atoc.css\" />\n";
        }
        return $output;
        return $name=="atoc" ? $this->onParseContentHtml($page, "<p>[atoc]</p>\n") : null;
    }
}
