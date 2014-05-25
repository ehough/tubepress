<?php
/**
 * Copyright 2006 - 2014 TubePress LLC (http://tubepress.com)
 * 
 * This file is part of TubePress (http://tubepress.com)
 * 
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

/**
 *
 */
class tubepress_core_impl_template_contemplate_TemplateFactory implements tubepress_core_api_template_TemplateFactoryInterface
{
    /**
     * @var tubepress_api_util_LangUtilsInterface
     */
    private $_langUtils;

    /**
     * @var tubepress_api_log_LoggerInterface
     */
    private $_logger;

    /**
     * @var tubepress_core_api_theme_ThemeLibraryInterface
     */
    private $_themeLibrary;

    /**
     * @var
     */
    private $_filesystem;

    /**
     * @var bool
     */
    private $_logEnabled;

    public function __construct(

        tubepress_api_log_LoggerInterface              $logger,
        tubepress_api_util_LangUtilsInterface          $langUtils,
        tubepress_core_api_theme_ThemeLibraryInterface $themeLibrary,
        ehough_filesystem_FilesystemInterface          $filesystem)
    {
        $this->_logger       = $logger;
        $this->_langUtils    = $langUtils;
        $this->_logEnabled   = $this->_logger->isEnabled();
        $this->_themeLibrary = $themeLibrary;
        $this->_filesystem   = $filesystem;
    }
    
    /**
     * Loads a new template instance by path.
     *
     * @param string[] $paths An array of filesystem paths to search, in order of priority. The first path
     *                        with an existing file will be used. Each path can either be absolute or relative.
     *                        If absolute, the absolute path will be used. If relative, assume path is
     *                        relative to the root of the current TubePress theme.
     *
     * @return tubepress_core_api_template_TemplateInterface|null A template instance, or null if the template cannot be found.
     */
    public function fromFilesystem(array $paths)
    {
        $index     = 1;
        $pathCount = count($paths);

        if ($this->_logEnabled) {

            $this->_logger->debug(sprintf('Attempting to load template from %d possible path(s)', $pathCount));
        }

        foreach ($paths as $path) {

            if (!is_string($path)) {

                continue;
            }

            if ($this->_logEnabled) {

                $this->_logger->debug(sprintf('Attempting to load template from %s (%d of %d possible locations)',
                    $path, $index, $pathCount));
            }

            $template = $this->_loadTemplate($path);

            if ($template) {

                if ($this->_logEnabled) {

                    $this->_logger->debug(sprintf('Able to load template from %s (%d of %d possible locations)',
                        $path, $index, $pathCount));
                }

                return $template;
            }

            if ($this->_logEnabled) {

                $this->_logger->debug(sprintf('Unable to load template from %s (%d of %d possible locations)',
                    $path, $index++, $pathCount));
            }
        }

        if ($this->_logEnabled) {

            $this->_logger->debug(sprintf('Attempting to load template from any of %d possible locations',
                $pathCount));
        }

        return null;
    }

    private function _loadTemplate($path)
    {
        if (is_file($path) && is_readable($path) && $this->_filesystem->isAbsolutePath($path)) {

            return $this->_newTemplateInstance($path);
        }

        return $this->_loadTemplateFromRelativePath($path);
    }

    private function _loadTemplateFromRelativePath($path)
    {
        $pathToTemplate = ltrim($path, DIRECTORY_SEPARATOR);

        if ($this->_logEnabled) {

            $this->_logger->debug(sprintf('Attempting to load theme template from "%s"', $pathToTemplate));
        }

        $filePath = $this->_themeLibrary->getAbsolutePathToTemplate($path);

        if ($filePath === null) {

            return null;
        }

        if ($this->_logEnabled) {

            $this->_logger->debug(sprintf('Candidate absolute path is "%s"', $pathToTemplate));
        }

        return $this->_newTemplateInstance($filePath);
    }

    private function _newTemplateInstance($fullPath)
    {
        $contemplateTemplate = new ehough_contemplate_impl_SimpleTemplate();
        $contemplateTemplate->setPath($fullPath);

        return new tubepress_core_impl_template_contemplate_Template(

            $contemplateTemplate,
            $this->_langUtils
        );
    }
}