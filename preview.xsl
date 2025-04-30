<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template match="/">
    <html>
      <head>
        <title>Recipe Preview</title>
        <style>
          body { font-family: sans-serif; padding: 10px; }
          .recipe { border: 1px solid #ccc; margin: 10px 0; padding: 10px; }
          img { max-width: 200px; display: block; margin: 10px 0; }
        </style>
      </head>
      <body>
        <h1>Recipe Preview</h1>
        <xsl:for-each select="recipes/recipe">
          <div class="recipe">
            <h2><xsl:value-of select="title" /></h2>
            <p><strong>Category:</strong> <xsl:value-of select="category" /></p>
            <p><strong>Prep Time:</strong> <xsl:value-of select="prepTime" /></p>
            <xsl:if test="image">
              <img>
                <xsl:attribute name="src"><xsl:value-of select="image" /></xsl:attribute>
              </img>
            </xsl:if>
            <h3>Ingredients:</h3>
            <ul>
              <xsl:for-each select="ingredients/item">
                <li><xsl:value-of select="." /></li>
              </xsl:for-each>
            </ul>
            <h3>Instructions:</h3>
            <ol>
              <xsl:for-each select="instructions/step">
                <li><xsl:value-of select="." /></li>
              </xsl:for-each>
            </ol>
          </div>
        </xsl:for-each>
      </body>
    </html>
  </xsl:template>
</xsl:stylesheet>
