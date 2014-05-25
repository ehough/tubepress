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
?>

<div class="tubepress_single_video">

    <?php if (${tubepress_core_api_const_template_Variable::META_SHOULD_SHOW}[tubepress_core_api_const_options_Names::TITLE]): ?>
        <div class="tubepress_embedded_title"><?php echo htmlspecialchars($video->getTitle(), ENT_QUOTES, "UTF-8"); ?></div>
    <?php endif; ?>

    <?php echo ${tubepress_core_api_const_template_Variable::EMBEDDED_SOURCE}; ?>

    <dl class="tubepress_meta_group" style="width: <?php echo ${tubepress_core_api_const_template_Variable::EMBEDDED_WIDTH}; ?>px">

        <?php if (${tubepress_core_api_const_template_Variable::META_SHOULD_SHOW}[tubepress_core_api_const_options_Names::LENGTH]): ?>

            <dt class="tubepress_meta tubepress_meta_runtime"><?php echo ${tubepress_core_api_const_template_Variable::META_LABELS}[tubepress_core_api_const_options_Names::LENGTH]; ?></dt><dd class="tubepress_meta tubepress_meta_runtime"><?php echo $video->getDuration(); ?></dd>
        <?php endif; ?>

        <?php if (${tubepress_core_api_const_template_Variable::META_SHOULD_SHOW}[tubepress_core_api_const_options_Names::AUTHOR]): ?>

            <dt class="tubepress_meta tubepress_meta_author"><?php echo ${tubepress_core_api_const_template_Variable::META_LABELS}[tubepress_core_api_const_options_Names::AUTHOR]; ?></dt><dd class="tubepress_meta tubepress_meta_author"><a rel="external nofollow" href="http://www.youtube.com/user/<?php echo $video->getAuthorUid(); ?>"><?php echo $video->getAuthorDisplayName(); ?></a></dd>
        <?php endif; ?>

        <?php if (${tubepress_core_api_const_template_Variable::META_SHOULD_SHOW}[tubepress_core_api_const_options_Names::KEYWORDS]): ?>

            <dt class="tubepress_meta tubepress_meta_keywords"><?php echo ${tubepress_core_api_const_template_Variable::META_LABELS}[tubepress_core_api_const_options_Names::KEYWORDS]; ?></dt><dd class="tubepress_meta tubepress_meta_keywords"><?php echo $raw = htmlspecialchars(implode(" ", $video->getKeywords()), ENT_QUOTES, "UTF-8"); ?></a></dd>
        <?php endif; ?>

        <?php if (${tubepress_core_api_const_template_Variable::META_SHOULD_SHOW}[tubepress_core_api_const_options_Names::URL]): ?>

            <dt class="tubepress_meta tubepress_meta_url"><?php echo ${tubepress_core_api_const_template_Variable::META_LABELS}[tubepress_core_api_const_options_Names::URL]; ?></dt><dd class="tubepress_meta tubepress_meta_url"><a rel="external nofollow" href="<?php echo $video->getHomeUrl(); ?>"><?php echo ${tubepress_core_api_const_template_Variable::META_LABELS}[tubepress_core_api_const_options_Names::URL]; ?></a></dd>
        <?php endif; ?>

        <?php if (${tubepress_core_api_const_template_Variable::META_SHOULD_SHOW}[tubepress_core_api_const_options_Names::CATEGORY] &&
            $video->getCategory() != ""):
            ?>

            <dt class="tubepress_meta tubepress_meta_category"><?php echo ${tubepress_core_api_const_template_Variable::META_LABELS}[tubepress_core_api_const_options_Names::CATEGORY]; ?></dt><dd class="tubepress_meta tubepress_meta_category"><?php echo htmlspecialchars($video->getCategory(), ENT_QUOTES, "UTF-8"); ?></dd>
        <?php endif; ?>

        <?php if (isset(${tubepress_core_api_const_template_Variable::META_SHOULD_SHOW}[tubepress_youtube_api_const_options_Names::RATINGS]) && ${tubepress_core_api_const_template_Variable::META_SHOULD_SHOW}[tubepress_youtube_api_const_options_Names::RATINGS] &&
            $video->getRatingCount() != ""):
            ?>

            <dt class="tubepress_meta tubepress_meta_ratings"><?php echo ${tubepress_core_api_const_template_Variable::META_LABELS}[tubepress_youtube_api_const_options_Names::RATINGS]; ?></dt><dd class="tubepress_meta tubepress_meta_ratings"><?php echo $video->getRatingCount(); ?></dd>
        <?php endif; ?>

        <?php if (isset(${tubepress_core_api_const_template_Variable::META_SHOULD_SHOW}[tubepress_vimeo_api_const_options_Names::LIKES]) && ${tubepress_core_api_const_template_Variable::META_SHOULD_SHOW}[tubepress_vimeo_api_const_options_Names::LIKES] &&
            $video->getLikesCount() != ""):
            ?>

            <dt class="tubepress_meta tubepress_meta_likes"><?php echo ${tubepress_core_api_const_template_Variable::META_LABELS}[tubepress_vimeo_api_const_options_Names::LIKES]; ?></dt><dd class="tubepress_meta tubepress_meta_likes"><?php echo $video->getLikesCount(); ?></dd>
        <?php endif; ?>

        <?php if (isset(${tubepress_core_api_const_template_Variable::META_SHOULD_SHOW}[tubepress_youtube_api_const_options_Names::RATING]) && ${tubepress_core_api_const_template_Variable::META_SHOULD_SHOW}[tubepress_youtube_api_const_options_Names::RATING] &&
            $video->getRatingAverage() != ""):
            ?>

            <dt class="tubepress_meta tubepress_meta_rating"><?php echo ${tubepress_core_api_const_template_Variable::META_LABELS}[tubepress_youtube_api_const_options_Names::RATING]; ?></dt><dd class="tubepress_meta tubepress_meta_rating"><?php echo $video->getRatingAverage(); ?></dd>
        <?php endif; ?>

        <?php if (${tubepress_core_api_const_template_Variable::META_SHOULD_SHOW}[tubepress_core_api_const_options_Names::ID]): ?>

            <dt class="tubepress_meta tubepress_meta_id"><?php echo ${tubepress_core_api_const_template_Variable::META_LABELS}[tubepress_core_api_const_options_Names::ID]; ?></dt><dd class="tubepress_meta tubepress_meta_id"><?php echo $video->getId(); ?></dd>
        <?php endif; ?>

        <?php if (${tubepress_core_api_const_template_Variable::META_SHOULD_SHOW}[tubepress_core_api_const_options_Names::VIEWS]): ?>

            <dt class="tubepress_meta tubepress_meta_views"><?php echo ${tubepress_core_api_const_template_Variable::META_LABELS}[tubepress_core_api_const_options_Names::VIEWS]; ?></dt><dd class="tubepress_meta tubepress_meta_views"><?php echo $video->getViewCount(); ?></dd>
        <?php endif; ?>

        <?php if (${tubepress_core_api_const_template_Variable::META_SHOULD_SHOW}[tubepress_core_api_const_options_Names::UPLOADED]): ?>

            <dt class="tubepress_meta tubepress_meta_uploaddate"><?php echo ${tubepress_core_api_const_template_Variable::META_LABELS}[tubepress_core_api_const_options_Names::UPLOADED]; ?></dt><dd class="tubepress_meta tubepress_meta_uploaddate"><?php echo $video->getTimePublished(); ?></dd>
        <?php endif; ?>

        <?php if (${tubepress_core_api_const_template_Variable::META_SHOULD_SHOW}[tubepress_core_api_const_options_Names::DESCRIPTION]): ?>

            <dt class="tubepress_meta tubepress_meta_description"><?php echo ${tubepress_core_api_const_template_Variable::META_LABELS}[tubepress_core_api_const_options_Names::DESCRIPTION]; ?></dt><dd class="tubepress_meta tubepress_meta_description"><?php echo $video->getDescription(); ?></dd>
        <?php endif; ?>

    </dl>

</div>