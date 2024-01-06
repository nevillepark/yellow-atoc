<?php
// Advanced Table of Contents extension

class YellowAtoc {
    const VERSION = "0.9";
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
            // Note: this doesn't play nice when headings have the same title.
            // Also note: to allow <h1> headings in ToC, change "2-$atocLevel" 
            // to "1-$atocLevel" (but you shouldn't)
            preg_match_all("/<h([2-$atocLevel]) id=\"(.*?)\">(.*?)<\/h\d>/i", $rawData, $matches, PREG_PATTERN_ORDER);

            // Variables
            // Get list type from system settings
            // 0 = unordered list (<ul>), 1 = ordered list (<ol>)
            if ($this->yellow->system->get("atocNumbering")) {$listType = "ol";} else {$listType = "ul";}
            // Just to make things easier
            $level = $matches[1];
            $anchor = $matches[2];
            $title = $matches[3];
            // Counting variables
            $min = min($level);
            $count = count($level);
            $prev = $min - 1;
            $counter = 0;

            // Start table of contents
            $output = "<nav class=\"atoc\">"; // Nav version
            // $output = "<!--AToC-->\n" . "<details id=\"atoc\">\n<summary>Table of contents</summary>\n"; // Collapsible version
            // Start loop 
            for ( $a = $counter; $a < $count; $a++ ) {
                // Variables
                $current = $level[$a];
                $diff = $current - $prev;
                $entry = "<a href=\"$location#$anchor[$a]\">$title[$a]</a>";
                // If current heading is below previous,
                if ($current > $prev) {
                    // start appropriate number of sub-lists 
                    for ($i = 1; $i <= $diff; $i++) {$output .= "\n<$listType><li>";}
                // If current heading is the same level as previous,
                } elseif ($current == $prev) {
                    // close previous entry and start new entry,
                    $output .= "</li>\n<li>";
                } elseif ( $current < $prev ) {
                    // close sub-lists,
                    for ($i = -1; $i >= $diff; $i--) {$output .= "</li></$listType>\n";}
                    // and close parent entry
                    $output .= "</li>\n<li>";
                }
                // Print entry
                $output .= "$entry";
                // If last entry
                if ($a == $count - 1) {
                 for ($i = 1; $i <= $diff; $i++) {$output .= "</li></$listType>\n";}
                    // close entry
                    $output .= "</li>";
                }
                // Before next loop
                $prev = $current;
            } // end loop
            // End list
            $output .= "</$listType>\n"
            $output .= "</nav>"; // Nav version
            // $output .= "</details>\n<!--/AToC-->"; // Collapsible version
            return $output;
        }; // end callback
        return preg_replace_callback("/<p>\[atoc\]<\/p>\n/i", $callback, $text);
    } // end OnParseContentHtml function
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
