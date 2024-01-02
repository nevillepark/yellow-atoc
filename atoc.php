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
            preg_match_all("/<h([2-$atocLevel]) id=\"(.*?)\">(.*?)<\/h\d>/i", $rawData, $matches, PREG_SET_ORDER);

            // Variables
            // Get list type from system settings; 0 = unordered list (<ul>), 1 = ordered list (<ol>)
            if ($this->yellow->system->get("atocNumbering")) {$listType = "ol";} else {$listType = "ul";}
            $prev = 1;
            $counter = 1;

            // Start list
            $output = "<!-- AToC -->\n" . "<nav class=\"atoc\">"; // Full version
            // $output = "<!--AToC-->\n" . "<details id=\"atoc\">\n<summary>Table of contents</summary>\n"; // Collapsible version
			// Loop through array of matches
            foreach ($matches as $match) {
                // Variables
                $current = $match[1];
                $diff = $current - $prev;
                $entry = "<a href=\"$location#$match[2]\">$match[3]</a>";

                // If current heading is below previous,
                if ($current > $prev) {
                    // start appropriate number of sub-lists
                    for ($i = 1; $i <= $diff; $i++) {$output .= "\n<$listType><li>";}
                // If current heading is the same level as previous,
                } elseif ($current == $prev) {
                    // just close previous entry and start new entry
                    $output .= "</li>\n<li>";
                // If current heading is above previous,
                } elseif ($current < $prev) {
                    // close sub-lists
                    for ($i = -1; $i >= $diff; $i--) {$output .= "</li></$listType>\n";}
                    // close parent entry
                    $output .= "</li>\n<li>";
                }

                // Add new entry
                $output .= "$entry";

                // If last entry,
                if ( count($matches) === $counter ) {
                    // close sub-lists if necessary
                    for ($i = 1; $i <= $diff; $i++) {$output .= "</li></$listType>\n";}
                    // close entry
                    $output .= "</li>";
                }

                // Before running loop again
                $prev = $current;
                $counter++;
            }
            // Close list
            $output .= "</$listType>\n"; 
			$output .= "</nav>\n<!--/AToC-->"; // Full version
			// $output .= "</details>\n<!--/AToC-->"; // Collapsible version
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