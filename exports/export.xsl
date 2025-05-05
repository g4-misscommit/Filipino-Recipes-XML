<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template match="/">
    <html>
      <head>
        <title>Exported Filipino Recipes</title>
        <style>
          body { font-family: Arial, sans-serif; margin: 20px; }
          .recipe { border: 1px solid #ccc; padding: 15px; margin-bottom: 20px; }
          h2 { color: #b33; }
          img { max-width: 200px; margin-top: 10px; }
        </style>
      </head>
      <body>
        <h1>Filipino Recipe Export</h1>
        <xsl:for-each select="recipes/recipe">
          <div class="recipe">
            <h2><xsl:value-of select="title" /></h2>
            <p><strong>Category:</strong> <xsl:value-of select="category" /></p>
            <p><strong>Preparation Time:</strong> <xsl:value-of select="prepTime" /></p>

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
