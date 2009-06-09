﻿<?xml version="1.0" encoding="UTF-8"?>

<!-- 
    Most of this stuff was yanked from the springframework build
 -->

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version='1.0'>

	<xsl:import href="file:///opt/local/share/xsl/docbook-xsl/html/docbook.xsl" />

    <!--  Extensions -->
	<xsl:param name="use.extensions">1</xsl:param>
    <xsl:param name="tablecolumns.extension">0</xsl:param>
    <xsl:param name="callout.extensions">1</xsl:param>
    
    <!-- Graphics -->
    <xsl:param name="callout.graphics" select="1" />
    <xsl:param name="callout.defaultcolumn">100</xsl:param>
    <xsl:param name="callout.graphics.path">images/callouts/</xsl:param>
    <xsl:param name="callout.graphics.extension">.gif</xsl:param>
    
    <xsl:param name="table.borders.with.css" select="1"/>
    <xsl:param name="html.stylesheet">css/stylesheet.css</xsl:param>
    <xsl:param name="html.stylesheet.type">text/css</xsl:param>
    <xsl:param name="generate.toc">book toc,title</xsl:param>

    <!-- Label Chapters and Sections (numbering) -->
    <xsl:param name="chapter.autolabel" select="1"/>
    <xsl:param name="section.autolabel" select="1"/>
    <xsl:param name="section.autolabel.max.depth" select="3"/>

    <xsl:param name="section.label.includes.component.label" select="1"/>
    <xsl:param name="table.footnote.number.format" select="'1'"/>
    
    <!-- Show only Sections up to level 3 in the TOCs -->
    <xsl:param name="toc.section.depth">3</xsl:param>

</xsl:stylesheet>