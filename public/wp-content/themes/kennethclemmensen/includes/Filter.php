<?php
/**
 * The Filter enum defines the filters
 */
enum Filter: string {
	case CommentTextRss = 'comment_text_rss';
	case ExcerptLength = 'excerpt_length';
	case ExcerptMore = 'excerpt_more';
	case ScriptLoaderSrc = 'script_loader_src';
	case ScriptLoaderTag = 'script_loader_tag';
	case StyleLoaderSrc = 'style_loader_src';
	case TheContentFeed = 'the_content_feed';
	case WpMail = 'wp_mail';
}