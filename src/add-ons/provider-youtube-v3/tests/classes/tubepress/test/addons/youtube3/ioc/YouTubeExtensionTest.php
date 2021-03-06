<?php
/*
 * Copyright 2006 - 2018 TubePress LLC (http://tubepress.com)
 *
 * This file is part of TubePress (http://tubepress.com)
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

/**
 * @covers tubepress_youtube3_ioc_YouTubeExtension<extended>
 */
class tubepress_test_youtube3_ioc_YouTubeExtensionTest extends tubepress_api_test_ioc_AbstractContainerExtensionTest
{
    protected function buildSut()
    {
        return new tubepress_youtube3_ioc_YouTubeExtension();
    }

    protected function prepareForLoad()
    {
        $this->_expectEmbedded();
        $this->_expectListeners();
        $this->_expectMediaProvider();
        $this->_expectOptions();
        $this->_expectOptionsUi();
        $this->_expectPlayer();
    }

    private function _expectEmbedded()
    {
        $this->expectRegistration(
            'tubepress_youtube3_impl_embedded_YouTubeEmbeddedProvider',
            'tubepress_youtube3_impl_embedded_YouTubeEmbeddedProvider'

        )->withArgument(new tubepress_api_ioc_Reference(tubepress_api_options_ContextInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_api_util_LangUtilsInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_api_url_UrlFactoryInterface::_))
            ->withTag('tubepress_spi_embedded_EmbeddedProviderInterface')
            ->withTag('tubepress_spi_template_PathProviderInterface');
    }

    private function _expectListeners()
    {
        $this->expectRegistration(
            'tubepress_youtube3_impl_listeners_media_HttpItemListener',
            'tubepress_youtube3_impl_listeners_media_HttpItemListener'
        )->withArgument(new tubepress_api_ioc_Reference(tubepress_api_media_AttributeFormatterInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_api_util_TimeUtilsInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_api_options_ContextInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference('tubepress_youtube3_impl_ApiUtility'))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_api_url_UrlFactoryInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_api_array_ArrayReaderInterface::_))
            ->withTag(tubepress_api_ioc_ServiceTags::EVENT_LISTENER, array(
                'event'    => tubepress_api_event_Events::MEDIA_ITEM_HTTP_NEW . '.youtube_v3',
                'method'   => 'onHttpItem',
                'priority' => 100000,
            ));

        $this->expectRegistration(
            'tubepress_youtube3_impl_listeners_options_YouTubeOptionListener',
            'tubepress_youtube3_impl_listeners_options_YouTubeOptionListener'
        )->withArgument(new tubepress_api_ioc_Reference(tubepress_api_url_UrlFactoryInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_api_util_StringUtilsInterface::_))
            ->withTag(tubepress_api_ioc_ServiceTags::EVENT_LISTENER, array(
                'event'    => tubepress_api_event_Events::OPTION_SET . '.' . tubepress_youtube3_api_Constants::OPTION_YOUTUBE_PLAYLIST_VALUE,
                'method'   => 'onPlaylistValue',
                'priority' => 100000, ))
            ->withTag(tubepress_api_ioc_ServiceTags::EVENT_LISTENER, array(
                'event'    => tubepress_api_event_Events::OPTION_SET . '.' . tubepress_youtube3_api_Constants::OPTION_YOUTUBE_USER_VALUE,
                'method'   => 'onUserOrFavoritesValue',
                'priority' => 100000, ))
            ->withTag(tubepress_api_ioc_ServiceTags::EVENT_LISTENER, array(
                'event'    => tubepress_api_event_Events::OPTION_SET . '.' . tubepress_youtube3_api_Constants::OPTION_YOUTUBE_FAVORITES_VALUE,
                'method'   => 'onUserOrFavoritesValue',
                'priority' => 100000, ))
            ->withTag(tubepress_api_ioc_ServiceTags::EVENT_LISTENER, array(
                'event'    => tubepress_api_event_Events::OPTION_SET . '.' . tubepress_youtube3_api_Constants::OPTION_YOUTUBE_RELATED_VALUE,
                'method'   => 'onRelatedToValue',
                'priority' => 100000, ))
            ->withTag(tubepress_api_ioc_ServiceTags::EVENT_LISTENER, array(
                'event'    => tubepress_api_event_Events::OPTION_SET . '.' . tubepress_youtube3_api_Constants::OPTION_YOUTUBE_LIST_VALUE,
                'method'   => 'onListValue',
                'priority' => 100000, ));

        $fixedValues = array(
            tubepress_youtube3_api_Constants::OPTION_AUTOHIDE => array(
                tubepress_youtube3_api_Constants::AUTOHIDE_HIDE_BAR_SHOW_CONTROLS => 'Fade progress bar only',
                tubepress_youtube3_api_Constants::AUTOHIDE_HIDE_BOTH              => 'Fade progress bar and video controls',
                tubepress_youtube3_api_Constants::AUTOHIDE_SHOW_BOTH              => 'Disable fading - always show both',
            ),
            tubepress_youtube3_api_Constants::OPTION_SHOW_CONTROLS => array(
                tubepress_youtube3_api_Constants::CONTROLS_SHOW_IMMEDIATE_FLASH => 'Show controls - load Flash player immediately',
                tubepress_youtube3_api_Constants::CONTROLS_SHOW_DELAYED_FLASH   => 'Show controls - load Flash player when playback begins',
                tubepress_youtube3_api_Constants::CONTROLS_HIDE                 => 'Hide controls',
            ),
            tubepress_youtube3_api_Constants::OPTION_THEME => array(
                tubepress_youtube3_api_Constants::PLAYER_THEME_DARK  => 'Dark',
                tubepress_youtube3_api_Constants::PLAYER_THEME_LIGHT => 'Light',
            ),
            tubepress_youtube3_api_Constants::OPTION_FILTER => array(
                tubepress_youtube3_api_Constants::SAFESEARCH_NONE     => 'none',
                tubepress_youtube3_api_Constants::SAFESEARCH_MODERATE => 'moderate',
                tubepress_youtube3_api_Constants::SAFESEARCH_STRICT   => 'strict',
            ),
        );

        foreach ($fixedValues as $optionName => $values) {

            $this->expectRegistration(
                "fixed_values.$optionName",
                'tubepress_api_options_listeners_FixedValuesListener'
            )->withArgument($values)
                ->withTag(tubepress_api_ioc_ServiceTags::EVENT_LISTENER, array(
                    'event'    => tubepress_api_event_Events::OPTION_ACCEPTABLE_VALUES . ".$optionName",
                    'priority' => 100000,
                    'method'   => 'onAcceptableValues',
                ));
        }

        $validators = array(
            tubepress_api_options_listeners_RegexValidatingListener::TYPE_STRING_YOUTUBE_VIDEO_ID => array(
                tubepress_youtube3_api_Constants::OPTION_YOUTUBE_RELATED_VALUE,
            ),
        );

        foreach ($validators as $type => $optionNames) {
            foreach ($optionNames as $optionName) {

                $this->expectRegistration(
                    "regex_validation.$optionName",
                    'tubepress_api_options_listeners_RegexValidatingListener'
                )->withArgument($type)
                    ->withArgument(new tubepress_api_ioc_Reference(tubepress_api_options_ReferenceInterface::_))
                    ->withArgument(new tubepress_api_ioc_Reference(tubepress_api_translation_TranslatorInterface::_))
                    ->withTag(tubepress_api_ioc_ServiceTags::EVENT_LISTENER, array(
                        'event'    => tubepress_api_event_Events::OPTION_SET . ".$optionName",
                        'priority' => 100000,
                        'method'   => 'onOption',
                    ));
            }
        }

        $oneOrMoreWordCharGroups = '/^[\w-]+(?:\s+\+\s+[\w-]+)*$/';

        $validators = array(

            tubepress_youtube3_api_Constants::OPTION_API_KEY                 => '/^[\w-]*$/',
            tubepress_youtube3_api_Constants::OPTION_YOUTUBE_PLAYLIST_VALUE  => $oneOrMoreWordCharGroups,
            tubepress_youtube3_api_Constants::OPTION_YOUTUBE_FAVORITES_VALUE => $oneOrMoreWordCharGroups,
            tubepress_youtube3_api_Constants::OPTION_YOUTUBE_USER_VALUE      => $oneOrMoreWordCharGroups,
        );

        foreach ($validators as $optionName => $pattern) {

            $this->expectRegistration(

                "pattern_validator_$optionName",
                'tubepress_api_options_listeners_PatternValidatingListener'
            )->withArgument($pattern)
                ->withArgument('Invalid value supplied for "%s".')
                ->withArgument(new tubepress_api_ioc_Reference(tubepress_api_options_ReferenceInterface::_))
                ->withArgument(new tubepress_api_ioc_Reference(tubepress_api_translation_TranslatorInterface::_))
                ->withTag(tubepress_api_ioc_ServiceTags::EVENT_LISTENER, array(
                    'event'    => tubepress_api_event_Events::OPTION_SET . ".$optionName",
                    'priority' => 100000,
                    'method'   => 'onOptionValidation',
                ));
        }
    }

    private function _expectMediaProvider()
    {
        $this->expectRegistration(
            'tubepress_youtube3_impl_ApiUtility',
            'tubepress_youtube3_impl_ApiUtility'
        )->withArgument(new tubepress_api_ioc_Reference(tubepress_api_log_LoggerInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_api_options_ContextInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_api_http_HttpClientInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_api_array_ArrayReaderInterface::_));

        $this->expectRegistration(
            'tubepress_youtube3_impl_media_FeedHandler',
            'tubepress_youtube3_impl_media_FeedHandler'
        )->withArgument(new tubepress_api_ioc_Reference(tubepress_api_log_LoggerInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_api_options_ContextInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_api_url_UrlFactoryInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_api_array_ArrayReaderInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference('tubepress_youtube3_impl_ApiUtility'));

        $this->expectRegistration(
            'tubepress_youtube3_impl_media_MediaProvider',
            'tubepress_youtube3_impl_media_MediaProvider'
        )->withArgument(new tubepress_api_ioc_Reference(tubepress_api_media_HttpCollectorInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference('tubepress_youtube3_impl_media_FeedHandler'))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_api_environment_EnvironmentInterface::_))
            ->withTag(tubepress_spi_media_MediaProviderInterface::__);
    }

    private function _expectOptions()
    {
        $this->expectRegistration(
            'tubepress_api_options_Reference__youtube',
            'tubepress_api_options_Reference'
        )->withTag(tubepress_api_options_ReferenceInterface::_)
         ->withArgument(array(

                tubepress_api_options_Reference::PROPERTY_DEFAULT_VALUE => array(
                    tubepress_youtube3_api_Constants::OPTION_AUTOHIDE                   => tubepress_youtube3_api_Constants::AUTOHIDE_HIDE_BAR_SHOW_CONTROLS,
                    tubepress_youtube3_api_Constants::OPTION_CLOSED_CAPTIONS            => false,
                    tubepress_youtube3_api_Constants::OPTION_DISABLE_KEYBOARD           => false,
                    tubepress_youtube3_api_Constants::OPTION_FULLSCREEN                 => true,
                    tubepress_youtube3_api_Constants::OPTION_MODEST_BRANDING            => true,
                    tubepress_youtube3_api_Constants::OPTION_SHOW_ANNOTATIONS           => false,
                    tubepress_youtube3_api_Constants::OPTION_SHOW_CONTROLS              => tubepress_youtube3_api_Constants::CONTROLS_SHOW_IMMEDIATE_FLASH,
                    tubepress_youtube3_api_Constants::OPTION_SHOW_RELATED               => true,
                    tubepress_youtube3_api_Constants::OPTION_THEME                      => tubepress_youtube3_api_Constants::PLAYER_THEME_DARK,
                    tubepress_youtube3_api_Constants::OPTION_API_KEY                    => '',
                    tubepress_youtube3_api_Constants::OPTION_EMBEDDABLE_ONLY            => true,
                    tubepress_youtube3_api_Constants::OPTION_FILTER                     => tubepress_youtube3_api_Constants::SAFESEARCH_NONE,
                    tubepress_youtube3_api_Constants::OPTION_YOUTUBE_RELATED_VALUE      => 'P9M__yYbsZ4',
                    tubepress_youtube3_api_Constants::OPTION_YOUTUBE_PLAYLIST_VALUE     => 'F679CB240DD4C112',
                    tubepress_youtube3_api_Constants::OPTION_YOUTUBE_FAVORITES_VALUE    => 'techcrunch',
                    tubepress_youtube3_api_Constants::OPTION_YOUTUBE_TAG_VALUE          => 'iphone ios',
                    tubepress_youtube3_api_Constants::OPTION_YOUTUBE_USER_VALUE         => 'PenWeddings',
                    tubepress_youtube3_api_Constants::OPTION_YOUTUBE_LIST_VALUE         => '9bZkp7q19f0, txqiwrbYGrs',
                    tubepress_youtube3_api_Constants::OPTION_META_COUNT_COMMENTS        => false,
                    tubepress_youtube3_api_Constants::OPTION_META_COUNT_DISLIKES        => false,
                    tubepress_youtube3_api_Constants::OPTION_META_COUNT_LIKES           => false,
                    tubepress_youtube3_api_Constants::OPTION_META_COUNT_FAVORITES       => false,
                    tubepress_youtube3_api_Constants::OPTION_YOUTUBE_MOST_POPULAR_VALUE => '',
                    tubepress_youtube3_api_Constants::OPTION_RATING                     => false,
                    tubepress_youtube3_api_Constants::OPTION_RATINGS                    => false,
                ),

                tubepress_api_options_Reference::PROPERTY_UNTRANSLATED_LABEL => array(
                    tubepress_youtube3_api_Constants::OPTION_AUTOHIDE                => 'Fade progress bar and video controls',
                    tubepress_youtube3_api_Constants::OPTION_CLOSED_CAPTIONS         => 'Show closed captions by default',
                    tubepress_youtube3_api_Constants::OPTION_DISABLE_KEYBOARD        => 'Disable keyboard controls',
                    tubepress_youtube3_api_Constants::OPTION_FULLSCREEN              => 'Allow fullscreen playback.',
                    tubepress_youtube3_api_Constants::OPTION_MODEST_BRANDING         => '"Modest" branding',
                    tubepress_youtube3_api_Constants::OPTION_SHOW_ANNOTATIONS        => 'Show video annotations by default',
                    tubepress_youtube3_api_Constants::OPTION_SHOW_CONTROLS           => 'Show or hide video controls',
                    tubepress_youtube3_api_Constants::OPTION_SHOW_RELATED            => 'Show related videos',
                    tubepress_youtube3_api_Constants::OPTION_THEME                   => 'YouTube player theme',
                    tubepress_youtube3_api_Constants::OPTION_API_KEY                 => 'Google API key',
                    tubepress_youtube3_api_Constants::OPTION_EMBEDDABLE_ONLY         => 'Only retrieve embeddable videos',
                    tubepress_youtube3_api_Constants::OPTION_FILTER                  => 'Filter "racy" content',
                    tubepress_youtube3_api_Constants::OPTION_YOUTUBE_RELATED_VALUE   => 'Videos related to this YouTube video',
                    tubepress_youtube3_api_Constants::OPTION_YOUTUBE_PLAYLIST_VALUE  => 'This YouTube playlist',
                    tubepress_youtube3_api_Constants::OPTION_YOUTUBE_FAVORITES_VALUE => 'Favorite videos from this YouTube user or channel',
                    tubepress_youtube3_api_Constants::OPTION_YOUTUBE_TAG_VALUE       => 'YouTube search for',
                    tubepress_youtube3_api_Constants::OPTION_YOUTUBE_USER_VALUE      => 'Videos from this YouTube user or channel',
                    tubepress_youtube3_api_Constants::OPTION_YOUTUBE_LIST_VALUE      => 'This list of YouTube videos',
                    tubepress_youtube3_api_Constants::OPTION_META_COUNT_COMMENTS     => 'Comment count',
                    tubepress_youtube3_api_Constants::OPTION_META_COUNT_FAVORITES    => 'Number of times favorited',
                    tubepress_youtube3_api_Constants::OPTION_META_COUNT_LIKES        => 'Number of likes',
                    tubepress_youtube3_api_Constants::OPTION_META_COUNT_DISLIKES     => 'Number of dislikes',
                ),

                tubepress_api_options_Reference::PROPERTY_UNTRANSLATED_DESCRIPTION => array(
                    tubepress_youtube3_api_Constants::OPTION_AUTOHIDE        => 'After video playback begins, choose which elements (if any) of the embedded video player to automatically hide.',
                    tubepress_youtube3_api_Constants::OPTION_MODEST_BRANDING => 'Hide the YouTube logo from the control area.',
                    tubepress_youtube3_api_Constants::OPTION_SHOW_RELATED    => 'Toggles the display of related videos after a video finishes.',
                    tubepress_youtube3_api_Constants::OPTION_API_KEY         => sprintf('YouTube will use this API key for logging and quota purposes. You can register a new API key <a href="%s" target="_blank">here</a>.',
                        'https://developers.google.com/youtube/registering_an_application'),
                    tubepress_youtube3_api_Constants::OPTION_EMBEDDABLE_ONLY        => 'Some videos have embedding disabled. Checking this option will exclude these videos from your galleries.',
                    tubepress_youtube3_api_Constants::OPTION_FILTER                 => 'Don\'t show videos that may not be suitable for minors.',
                    tubepress_youtube3_api_Constants::OPTION_YOUTUBE_PLAYLIST_VALUE => sprintf('The URL to any YouTube playlist (e.g. <a href="%s" target="_blank">%s</a>) or just the playlist identifier (e.g. %s).',
                        'http://youtube.com/playlist?list=48A83AD3506C9D36', 'http://youtube.com/playlist?list=48A83AD3506C9D36', '48A83AD3506C9D36'),
                    tubepress_youtube3_api_Constants::OPTION_YOUTUBE_TAG_VALUE => sprintf('You can use the NOT (<code>-</code>) and OR (<code>|</code>) operators to exclude videos or to find videos that are associated with one of several search terms. For example, to search for videos matching either "%s" or "%s", set this field to <code>%s|%s</code>. Similarly, to search for videos matching either "%s" or "%s" but not "%s", set this field to <code>%s|%s -%s</code>.',
                        'boating', 'sailing', 'boating', 'sailing', 'boating', 'sailing', 'fishing', 'boating', 'sailing', 'fishing'),
                    tubepress_youtube3_api_Constants::OPTION_YOUTUBE_USER_VALUE => sprintf('You can supply either a YouTube username (e.g. <code>%s</code>) or a YouTube channel ID (e.g. <code>%s</code>).',
                        'smosh', 'UCY30JRSgfhYXA6i6xX1erWg'),
                    tubepress_youtube3_api_Constants::OPTION_YOUTUBE_FAVORITES_VALUE => sprintf('You can supply either a YouTube username (e.g. <code>%s</code>) or a YouTube channel ID (e.g. <code>%s</code>). Ensure that the favorites <a href="%s" target="_blank">playlist\'s privacy</a> is set to "Public".',
                        'smosh', 'UCY30JRSgfhYXA6i6xX1erWg', 'https://support.google.com/youtube/answer/3127309'),
                    tubepress_youtube3_api_Constants::OPTION_YOUTUBE_LIST_VALUE => 'A comma-separated list of YouTube video IDs in the order that you would like them to appear.',

                ), ))
        ->withArgument(array(

                tubepress_api_options_Reference::PROPERTY_PRO_ONLY => array(

                    tubepress_youtube3_api_Constants::OPTION_CLOSED_CAPTIONS,
                    tubepress_youtube3_api_Constants::OPTION_DISABLE_KEYBOARD,
                    tubepress_youtube3_api_Constants::OPTION_SHOW_ANNOTATIONS,
                    tubepress_youtube3_api_Constants::OPTION_SHOW_CONTROLS,
                    tubepress_youtube3_api_Constants::OPTION_THEME,
                ),
            ));
    }

    private function _expectOptionsUi()
    {
        $fieldIndex = 0;

        $this->expectRegistration(

            'youtube_options_field_' . $fieldIndex++,
            'tubepress_api_options_ui_FieldInterface'
        )->withFactoryService(tubepress_api_options_ui_FieldBuilderInterface::_)
            ->withFactoryMethod('newInstance')
            ->withArgument(tubepress_youtube3_api_Constants::OPTION_API_KEY)
            ->withArgument('text')
            ->withArgument(array('size' => 40));

        $gallerySourceMap = array(

            array(
                tubepress_youtube3_api_Constants::GALLERYSOURCE_YOUTUBE_SEARCH,
                'multiSourceText',
                tubepress_youtube3_api_Constants::OPTION_YOUTUBE_TAG_VALUE, ),

            array(tubepress_youtube3_api_Constants::GALLERYSOURCE_YOUTUBE_USER,
                'multiSourceText',
                tubepress_youtube3_api_Constants::OPTION_YOUTUBE_USER_VALUE, ),

            array(tubepress_youtube3_api_Constants::GALLERYSOURCE_YOUTUBE_PLAYLIST,
                'multiSourceText',
                tubepress_youtube3_api_Constants::OPTION_YOUTUBE_PLAYLIST_VALUE, ),

            array(tubepress_youtube3_api_Constants::GALLERYSOURCE_YOUTUBE_FAVORITES,
                'multiSourceText',
                tubepress_youtube3_api_Constants::OPTION_YOUTUBE_FAVORITES_VALUE, ),

            array(tubepress_youtube3_api_Constants::GALLERYSOURCE_YOUTUBE_RELATED,
                'multiSourceText',
                tubepress_youtube3_api_Constants::OPTION_YOUTUBE_RELATED_VALUE, ),
            array(tubepress_youtube3_api_Constants::GALLERYSOURCE_YOUTUBE_LIST,
                'multiSourceTextArea',
                tubepress_youtube3_api_Constants::OPTION_YOUTUBE_LIST_VALUE, ),
        );

        foreach ($gallerySourceMap as $gallerySourceFieldArray) {

            $this->expectRegistration(

                'youtube_options_subfield_' . $fieldIndex,
                'tubepress_api_options_ui_FieldInterface'
            )->withFactoryService(tubepress_api_options_ui_FieldBuilderInterface::_)
                ->withFactoryMethod('newInstance')
                ->withArgument($gallerySourceFieldArray[2])
                ->withArgument($gallerySourceFieldArray[1]);

            $this->expectRegistration(

                'youtube_options_field_' . $fieldIndex,
                'tubepress_api_options_ui_FieldInterface'
            )->withFactoryService(tubepress_api_options_ui_FieldBuilderInterface::_)
                ->withFactoryMethod('newInstance')
                ->withArgument($gallerySourceFieldArray[0])
                ->withArgument('gallerySourceRadio')
                ->withArgument(array('additionalField' => new tubepress_api_ioc_Reference('youtube_options_subfield_' . $fieldIndex++)));
        }

        $fieldMap = array(

            tubepress_youtube3_api_Constants::OPTION_AUTOHIDE         => 'dropdown',
            tubepress_youtube3_api_Constants::OPTION_CLOSED_CAPTIONS  => 'bool',
            tubepress_youtube3_api_Constants::OPTION_DISABLE_KEYBOARD => 'bool',
            tubepress_youtube3_api_Constants::OPTION_FULLSCREEN       => 'bool',
            tubepress_youtube3_api_Constants::OPTION_MODEST_BRANDING  => 'bool',
            tubepress_youtube3_api_Constants::OPTION_SHOW_ANNOTATIONS => 'bool',
            tubepress_youtube3_api_Constants::OPTION_SHOW_RELATED     => 'bool',
            tubepress_youtube3_api_Constants::OPTION_THEME            => 'dropdown',
            tubepress_youtube3_api_Constants::OPTION_SHOW_CONTROLS    => 'dropdown',

            //Feed fields
            tubepress_youtube3_api_Constants::OPTION_FILTER          => 'multiSourceDropdown',
            tubepress_youtube3_api_Constants::OPTION_EMBEDDABLE_ONLY => 'bool',
        );

        foreach ($fieldMap as $id => $class) {

            $this->expectRegistration(

                'youtube_options_field_' . $fieldIndex++,
                'tubepress_api_options_ui_FieldInterface'
            )->withFactoryService(tubepress_api_options_ui_FieldBuilderInterface::_)
                ->withFactoryMethod('newInstance')
                ->withArgument($id)
                ->withArgument($class);
        }

        $fieldReferences = array();

        for ($x = 0; $x < $fieldIndex; ++$x) {

            $fieldReferences[] = new tubepress_api_ioc_Reference('youtube_options_field_' . $x);
        }

        $this->expectRegistration(

            'tubepress_youtube3_impl_options_ui_FieldProvider',
            'tubepress_youtube3_impl_options_ui_FieldProvider'

        )->withArgument($fieldReferences)
            ->withTag('tubepress_spi_options_ui_FieldProviderInterface');

    }

    private function _expectPlayer()
    {
        $this->expectRegistration(
            'tubepress_youtube3_impl_player_YouTubePlayerLocation',
            'tubepress_youtube3_impl_player_YouTubePlayerLocation'
        )->withTag('tubepress_spi_player_PlayerLocationInterface');
    }

    protected function getExpectedExternalServicesMap()
    {
        $mockField        = $this->mock('tubepress_api_options_ui_FieldInterface');
        $mockFieldBuilder = $this->mock(tubepress_api_options_ui_FieldBuilderInterface::_);
        $mockFieldBuilder->shouldReceive('newInstance')->atLeast(1)->andReturn($mockField);

        $mockBaseUrl = $this->mock('tubepress_api_url_UrlInterface');
        $environment = $this->mock(tubepress_api_environment_EnvironmentInterface::_);
        $environment->shouldReceive('getBaseUrl')->once()->andReturn($mockBaseUrl);
        $mockBaseUrl->shouldReceive('getClone')->once()->andReturn($mockBaseUrl);
        $mockBaseUrl->shouldReceive('addPath')->once()->with('/src/add-ons/provider-youtube-v3/web/images/icons/youtube-icon-34w_x_34h.png')->andReturn($mockBaseUrl);
        $mockBaseUrl->shouldReceive('toString')->once()->andReturn('icon-url');

        return array(

            tubepress_api_options_ContextInterface::_          => tubepress_api_options_ContextInterface::_,
            tubepress_api_util_LangUtilsInterface::_           => tubepress_api_util_LangUtilsInterface::_,
            tubepress_api_util_StringUtilsInterface::_         => tubepress_api_util_StringUtilsInterface::_,
            tubepress_api_url_UrlFactoryInterface::_           => tubepress_api_url_UrlFactoryInterface::_,
            tubepress_api_util_TimeUtilsInterface::_           => tubepress_api_util_TimeUtilsInterface::_,
            tubepress_api_log_LoggerInterface::_               => tubepress_api_log_LoggerInterface::_,
            tubepress_api_event_EventDispatcherInterface::_    => tubepress_api_event_EventDispatcherInterface::_,
            tubepress_api_options_ui_FieldBuilderInterface::_  => $mockFieldBuilder,
            tubepress_api_translation_TranslatorInterface::_   => tubepress_api_translation_TranslatorInterface::_,
            tubepress_api_media_AttributeFormatterInterface::_ => tubepress_api_media_AttributeFormatterInterface::_,
            tubepress_api_media_HttpCollectorInterface::_      => tubepress_api_media_HttpCollectorInterface::_,
            tubepress_api_options_ReferenceInterface::_        => tubepress_api_options_ReferenceInterface::_,
            tubepress_api_http_HttpClientInterface::_          => tubepress_api_http_HttpClientInterface::_,
            tubepress_api_array_ArrayReaderInterface::_        => tubepress_api_array_ArrayReaderInterface::_,
            tubepress_api_environment_EnvironmentInterface::_  => $environment,
        );
    }
}
