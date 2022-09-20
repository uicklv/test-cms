<?php
$text = '...';

// create a new DomDocument object
$doc = new DOMDocument();

// load the HTML into the DomDocument object (this would be your source HTML)
$doc->loadHTML('<?xml encoding="UTF-8">' . $text);

removeElementsByTagName('script', $doc);
removeElementsByTagName('style', $doc);
removeElementsByTagName('link', $doc);
removeElementsByTagName('p', $doc, true);

// output cleaned html
$text = $doc->saveHtml();





function removeElementsByTagName($tagName, $document, $empty = false) {
    if (!$empty) {
        $nodeList = $document->getElementsByTagName($tagName);
        for ($nodeIdx = $nodeList->length; --$nodeIdx >= 0;) {
            $node = $nodeList->item($nodeIdx);
            $node->parentNode->removeChild($node);
        }
    } else {
        $nodeList = $document->getElementsByTagName($tagName);
        for ($nodeIdx = $nodeList->length; --$nodeIdx >= 0;) {
            $node = $nodeList->item($nodeIdx);
            if ($node->nodeValue == '&nbsp;' || $node->nodeValue == '') {
                $node->parentNode->removeChild($node);
            }
        }
    }
}