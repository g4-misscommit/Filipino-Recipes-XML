<?php
// Load the uploaded XML file
$xmlFile = 'tmp_preview.xml';
$xslFile = 'preview.xsl';

// Check if the XML file exists
if (!file_exists($xmlFile)) {
    http_response_code(404);
    echo "<h1>Not Found</h1><p>XML file not found.</p>";
    exit;
}

// Load XML
$xml = new DOMDocument();
$xml->load($xmlFile);

// Load XSL
$xsl = new DOMDocument();
$xsl->load($xslFile);

// Apply XSLT transformation
$xslt = new XSLTProcessor();
$xslt->importStylesheet($xsl);

// Output the transformed HTML
echo $xslt->transformToXML($xml);
?>
