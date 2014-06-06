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
 * @covers tubepress_core_html_gallery_ioc_GalleryExtension
 */
class tubepress_test_core_media_gallery_ioc_GalleryExtensionTest extends tubepress_test_core_ioc_AbstractIocContainerExtensionTest
{

    /**
     * @return tubepress_api_ioc_ContainerExtensionInterface
     */
    protected function buildSut()
    {
        return new tubepress_core_html_gallery_ioc_GalleryExtension();
    }

    protected function prepareForLoad()
    {
        $this->expectRegistration(

            'tubepress_core_html_gallery_impl_listeners_html_AsyncJsInjector',
            'tubepress_core_html_gallery_impl_listeners_html_AsyncJsInjector'
        )->withArgument(new tubepress_api_ioc_Reference(tubepress_core_options_api_ContextInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_core_event_api_EventDispatcherInterface::_))
            ->withTag(tubepress_core_ioc_api_Constants::TAG_EVENT_LISTENER, array(
                'event'    => tubepress_core_html_gallery_api_Constants::EVENT_HTML_THUMBNAIL_GALLERY,
                'method'   => 'onGalleryHtml',
                'priority' => 10000
            ));

        $this->expectRegistration(
            'tubepress_core_html_gallery_impl_listeners_html_GalleryInitJsBaseParams',
            'tubepress_core_html_gallery_impl_listeners_html_GalleryInitJsBaseParams'
        )->withArgument(new tubepress_api_ioc_Reference(tubepress_core_options_api_ContextInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_core_options_api_ReferenceInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_core_environment_api_EnvironmentInterface::_))
            ->withTag(tubepress_core_ioc_api_Constants::TAG_EVENT_LISTENER, array(
                    'event'    => tubepress_core_html_gallery_api_Constants::EVENT_GALLERY_INIT_JS,
                    'method'   => 'onGalleryInitJs',
                    'priority' => 10000)
            )->withTag(tubepress_core_ioc_api_Constants::TAG_TAGGED_SERVICES_CONSUMER, array(
                'tag'    => tubepress_core_player_api_PlayerLocationInterface::_,
                'method' => 'setPlayerLocations'
            ));

        $this->expectRegistration(
            'tubepress_core_html_gallery_impl_listeners_html_GalleryMaker',
            'tubepress_core_html_gallery_impl_listeners_html_GalleryMaker'
        )->withArgument(new tubepress_api_ioc_Reference(tubepress_api_log_LoggerInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_core_options_api_ContextInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_core_event_api_EventDispatcherInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_core_media_provider_api_CollectorInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_core_http_api_RequestParametersInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_core_template_api_TemplateFactoryInterface::_))
            ->withTag(tubepress_core_ioc_api_Constants::TAG_EVENT_LISTENER, array(
                'event'    => tubepress_core_html_api_Constants::EVENT_PRIMARY_HTML,
                'method'   => 'onHtmlGeneration',
                'priority' => 4000
            ));

        $this->expectRegistration(
            'tubepress_core_html_gallery_impl_listeners_html_NoRobotsListener',
            'tubepress_core_html_gallery_impl_listeners_html_NoRobotsListener'
        )->withArgument(new tubepress_api_ioc_Reference(tubepress_core_http_api_RequestParametersInterface::_))
            ->withTag(tubepress_core_ioc_api_Constants::TAG_EVENT_LISTENER, array(
                'event'    => tubepress_core_html_api_Constants::EVENT_STYLESHEETS_PRE,
                'method'   => 'onBeforeCssHtml',
                'priority' => 10000
            ));

        $this->expectRegistration(
            'tubepress_core_html_gallery_impl_listeners_template_CoreVariables',
            'tubepress_core_html_gallery_impl_listeners_template_CoreVariables'
        )->withArgument(new tubepress_api_ioc_Reference(tubepress_core_options_api_ContextInterface::_))
            ->withTag(tubepress_core_ioc_api_Constants::TAG_EVENT_LISTENER, array(
                'event'    => tubepress_core_html_gallery_api_Constants::EVENT_TEMPLATE_THUMBNAIL_GALLERY,
                'method'   => 'onGalleryTemplate',
                'priority' => 10400
            ));

        $this->expectRegistration(
            'tubepress_core_html_gallery_impl_listeners_template_EmbeddedVars',
            'tubepress_core_html_gallery_impl_listeners_template_EmbeddedVars'
        )->withArgument(new tubepress_api_ioc_Reference(tubepress_core_options_api_ContextInterface::_))
            ->withTag(tubepress_core_ioc_api_Constants::TAG_EVENT_LISTENER, array(
                'event'    => tubepress_core_html_gallery_api_Constants::EVENT_TEMPLATE_THUMBNAIL_GALLERY,
                'method'   => 'onGalleryTemplate',
                'priority' => 10300
            ));

        $this->expectRegistration(
            'tubepress_core_html_gallery_impl_listeners_template_Pagination',
            'tubepress_core_html_gallery_impl_listeners_template_Pagination'
        )->withArgument(new tubepress_api_ioc_Reference(tubepress_core_options_api_ContextInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_core_url_api_UrlFactoryInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_core_translation_api_TranslatorInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_core_event_api_EventDispatcherInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_core_http_api_RequestParametersInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_core_template_api_TemplateFactoryInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_core_util_api_UrlUtilsInterface ::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_core_theme_api_ThemeLibraryInterface::_))
            ->withTag(tubepress_core_ioc_api_Constants::TAG_EVENT_LISTENER, array(
                'event'    => tubepress_core_html_gallery_api_Constants::EVENT_TEMPLATE_THUMBNAIL_GALLERY,
                'method'   => 'onGalleryTemplate',
                'priority' => 10200
            ));

        $this->expectRegistration(
            'tubepress_core_html_gallery_impl_listeners_template_PlayerLocation',
            'tubepress_core_html_gallery_impl_listeners_template_PlayerLocation'
        )->withArgument(new tubepress_api_ioc_Reference(tubepress_core_options_api_ContextInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_core_player_api_PlayerHtmlInterface::_))
            ->withTag(tubepress_core_ioc_api_Constants::TAG_EVENT_LISTENER, array(
                'event'    => tubepress_core_html_gallery_api_Constants::EVENT_TEMPLATE_THUMBNAIL_GALLERY,
                'method'   => 'onGalleryTemplate',
                'priority' => 10100
            ));

        $this->expectRegistration(
            'tubepress_core_html_gallery_impl_listeners_template_VideoMeta',
            'tubepress_core_html_gallery_impl_listeners_template_VideoMeta'
        )->withArgument(new tubepress_api_ioc_Reference(tubepress_core_options_api_ContextInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_core_translation_api_TranslatorInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_core_options_api_ReferenceInterface::_))
            ->withTag(tubepress_core_ioc_api_Constants::TAG_EVENT_LISTENER, array(
                'event'    => tubepress_core_html_gallery_api_Constants::EVENT_TEMPLATE_THUMBNAIL_GALLERY,
                'method'   => 'onGalleryTemplate',
                'priority' => 10000
            ))->withTag(tubepress_core_ioc_api_Constants::TAG_TAGGED_SERVICES_CONSUMER, array(

                'tag'    => tubepress_core_media_provider_api_MediaProviderInterface::_,
                'method' => 'setMediaProviders'
            ));

        $this->expectParameter(tubepress_core_options_api_Constants::IOC_PARAM_EASY_REFERENCE, array(

            'defaultValues' => array(
                tubepress_core_html_gallery_api_Constants::OPTION_AJAX_PAGINATION => false,
                tubepress_core_html_gallery_api_Constants::OPTION_AUTONEXT        => true,
                tubepress_core_html_gallery_api_Constants::OPTION_GALLERY_SOURCE  => tubepress_youtube_api_Constants::GALLERYSOURCE_YOUTUBE_MOST_POPULAR,
                tubepress_core_html_gallery_api_Constants::OPTION_FLUID_THUMBS    => true,
                tubepress_core_html_gallery_api_Constants::OPTION_HQ_THUMBS       => false,
                tubepress_core_html_gallery_api_Constants::OPTION_PAGINATE_ABOVE  => true,
                tubepress_core_html_gallery_api_Constants::OPTION_PAGINATE_BELOW  => true,
                tubepress_core_html_gallery_api_Constants::OPTION_RANDOM_THUMBS   => true,
                tubepress_core_html_gallery_api_Constants::OPTION_SEQUENCE        => null,
                tubepress_core_html_gallery_api_Constants::OPTION_THUMB_HEIGHT    => 90,
                tubepress_core_html_gallery_api_Constants::OPTION_THUMB_WIDTH     => 120,
            ),

            'labels' => array(
                tubepress_core_html_gallery_api_Constants::OPTION_AJAX_PAGINATION  => sprintf('<a href="%s" target="_blank">Ajax</a>-enabled pagination', "http://wikipedia.org/wiki/Ajax_(programming)"),  //>(translatable)<
                tubepress_core_html_gallery_api_Constants::OPTION_AUTONEXT         => 'Play videos sequentially without user intervention', //>(translatable)<
                tubepress_core_html_gallery_api_Constants::OPTION_FLUID_THUMBS     => 'Use "fluid" thumbnails',             //>(translatable)<
                tubepress_core_html_gallery_api_Constants::OPTION_HQ_THUMBS        => 'Use high-quality thumbnails',        //>(translatable)<
                tubepress_core_html_gallery_api_Constants::OPTION_PAGINATE_ABOVE   => 'Show pagination above thumbnails',   //>(translatable)<
                tubepress_core_html_gallery_api_Constants::OPTION_PAGINATE_BELOW   => 'Show pagination below thumbnails',   //>(translatable)<
                tubepress_core_html_gallery_api_Constants::OPTION_RANDOM_THUMBS    => 'Randomize thumbnail images',         //>(translatable)<
                tubepress_core_html_gallery_api_Constants::OPTION_THUMB_HEIGHT     => 'Height (px) of thumbs',              //>(translatable)<
                tubepress_core_html_gallery_api_Constants::OPTION_THUMB_WIDTH      => 'Width (px) of thumbs',               //>(translatable)<
            ),

            'descriptions' => array(
                tubepress_core_html_gallery_api_Constants::OPTION_AJAX_PAGINATION  => sprintf('<a href="%s" target="_blank">Ajax</a>-enabled pagination', "http://wikipedia.org/wiki/Ajax_(programming)"),  //>(translatable)<
                tubepress_core_html_gallery_api_Constants::OPTION_AUTONEXT          => 'When a video finishes, this will start playing the next video in the gallery.',  //>(translatable)<
                tubepress_core_html_gallery_api_Constants::OPTION_FLUID_THUMBS     => 'Dynamically set thumbnail spacing based on the width of their container.', //>(translatable)<
                tubepress_core_html_gallery_api_Constants::OPTION_HQ_THUMBS        => 'Note: this option cannot be used with the "randomize thumbnails" feature.', //>(translatable)<
                tubepress_core_html_gallery_api_Constants::OPTION_PAGINATE_ABOVE   => 'Only applies to galleries that span multiple pages.', //>(translatable)<
                tubepress_core_html_gallery_api_Constants::OPTION_PAGINATE_BELOW   => 'Only applies to galleries that span multiple pages.', //>(translatable)<
                tubepress_core_html_gallery_api_Constants::OPTION_RANDOM_THUMBS    => 'Most videos come with several thumbnails. By selecting this option, each time someone views your gallery they will see the same videos with each video\'s thumbnail randomized. Note: this option cannot be used with the "high quality thumbnails" feature.', //>(translatable)<
                tubepress_core_html_gallery_api_Constants::OPTION_THUMB_HEIGHT     => sprintf('Default is %s.', 90),   //>(translatable)<
                tubepress_core_html_gallery_api_Constants::OPTION_THUMB_WIDTH      => sprintf('Default is %s.', 120),  //>(translatable)<
            ),

            'noPersistNames' => array(
                tubepress_core_html_gallery_api_Constants::OPTION_SEQUENCE,
            ),

            'proNames' => array(
                tubepress_core_html_gallery_api_Constants::OPTION_AJAX_PAGINATION,
                tubepress_core_html_gallery_api_Constants::OPTION_AUTONEXT,
                tubepress_core_html_gallery_api_Constants::OPTION_HQ_THUMBS,
            )
        ));

        $this->expectParameter(tubepress_core_options_api_Constants::IOC_PARAM_EASY_VALIDATION, array(

            'priority' => 30000,
            'map'      => array(
                'positiveInteger' => array(
                    tubepress_core_html_gallery_api_Constants::OPTION_THUMB_HEIGHT,
                    tubepress_core_html_gallery_api_Constants::OPTION_THUMB_WIDTH,
                )
            )
        ));
    }

    protected function getExpectedExternalServicesMap()
    {
        return array(

            tubepress_api_log_LoggerInterface::_ => tubepress_api_log_LoggerInterface::_,
            tubepress_core_options_api_ContextInterface::_ => tubepress_core_options_api_ContextInterface::_,
            tubepress_core_event_api_EventDispatcherInterface::_ => tubepress_core_event_api_EventDispatcherInterface::_,
            tubepress_core_options_api_ReferenceInterface::_ => tubepress_core_options_api_ReferenceInterface::_,
            tubepress_core_environment_api_EnvironmentInterface::_ => tubepress_core_environment_api_EnvironmentInterface::_,
            tubepress_core_media_provider_api_CollectorInterface::_ => tubepress_core_media_provider_api_CollectorInterface::_,
            tubepress_core_http_api_RequestParametersInterface::_ => tubepress_core_http_api_RequestParametersInterface::_,
            tubepress_core_template_api_TemplateFactoryInterface::_ => tubepress_core_template_api_TemplateFactoryInterface::_,
            tubepress_core_url_api_UrlFactoryInterface::_ => tubepress_core_url_api_UrlFactoryInterface::_,
            tubepress_core_translation_api_TranslatorInterface::_ => tubepress_core_translation_api_TranslatorInterface::_,
            tubepress_core_util_api_UrlUtilsInterface::_ => tubepress_core_util_api_UrlUtilsInterface::_,
            tubepress_core_theme_api_ThemeLibraryInterface::_ => tubepress_core_theme_api_ThemeLibraryInterface::_,
            tubepress_core_player_api_PlayerHtmlInterface::_ => tubepress_core_player_api_PlayerHtmlInterface::_,
        );
    }
}