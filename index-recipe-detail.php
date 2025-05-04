<?php
if (isset($_GET['recipe'])) {
    $recipeId = $_GET['recipe'];

    $xml = new DOMDocument;
    $xml->load('featured_recipes.xml');

    $xsl = new DOMDocument;
    $xsl->load('recipe-detail.xsl');

    $proc = new XSLTProcessor;
    $proc->importStyleSheet($xsl);
    $proc->setParameter('', 'recipeId', $recipeId);

    echo $proc->transformToXML($xml);
}
?>
