<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:param name="recipeId"/>

  <xsl:template match="/">
    <xsl:apply-templates select="recipes/recipe[@id=$recipeId]" />
  </xsl:template>

  <xsl:template match="recipe">
    <style>
      body { font-family: sans-serif; }
      .recipe { padding: 10px; }
      .recipe img { max-width: 100%; display: block; margin: 10px 0; border-radius: 8px; }
      .recipe h2 { margin-top: 0; }
      .recipe ul, .recipe ol { margin-left: 20px; }
    </style>

    <div class="modal-header">
      <h5 class="modal-title"><xsl:value-of select="title" /></h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <div class="modal-body recipe">
      <p><strong>Category:</strong> <xsl:value-of select="category" /></p>
      <p><strong>Prep Time:</strong> <xsl:value-of select="prepTime" /></p>

      <xsl:if test="image">
        <img>
          <xsl:attribute name="src"><xsl:value-of select="image" /></xsl:attribute>
        </img>
      </xsl:if>

      <h6>Ingredients:</h6>
      <ul>
        <xsl:for-each select="ingredients/item">
          <li><xsl:value-of select="." /></li>
        </xsl:for-each>
      </ul>

      <h6>Instructions:</h6>
      <ol>
        <xsl:for-each select="instructions/step">
          <li><xsl:value-of select="." /></li>
        </xsl:for-each>
      </ol>
    </div>
  </xsl:template>

</xsl:stylesheet>
