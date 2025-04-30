<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:output method="html" indent="yes"/>

  <xsl:template match="/tmp_preview.xml">
    <html>
    <head>
      <style>
        body { font-family: Arial; }
        .recipe {
          border: 1px solid #ccc;
          margin: 10px 0;
          padding: 10px;
          background: #f9f9f9;
        }
      </style>
    </head>
    <body>
      <h2>Recipe Preview</h2>
      <xsl:for-each select="recipes/recipe">
        <div class="recipe">
          <h3><xsl:value-of select="title"/></h3>
          <p><strong>Category:</strong> <xsl:value-of select="category"/></p>
          <p><strong>Prep Time:</strong> <xsl:value-of select="prepTime"/></p>

          <p><strong>Ingredients:</strong></p>
          <ul>
            <xsl:for-each select="ingredients/item">
              <li><xsl:value-of select="."/></li>
            </xsl:for-each>
          </ul>

          <p><strong>Instructions:</strong></p>
          <ol>
            <xsl:for-each select="instructions/step">
              <li><xsl:value-of select="."/></li>
            </xsl:for-each>
          </ol>

          <xsl:if test="image">
            <img src="{image}" alt="Recipe Image" width="150"/>
          </xsl:if>
        </div>
      </xsl:for-each>
    </body>
    </html>
  </xsl:template>

</xsl:stylesheet>
