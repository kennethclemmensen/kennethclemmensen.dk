<?php
namespace KC\Data;

use KC\Core\Constant;
use KC\Core\FieldName;
use KC\Core\ImageSize;
use KC\Core\PostType;
use KC\Core\TaxonomyName;
use KC\Security\Security;
use KC\Utils\PluginHelper;
use \WP_Query;

/**
 * The DataManager class contains functionality to manage data
 */
class DataManager {

    /**
     * Get the pages
     * 
     * @return array the pages
     */
    public function getPages() : array {
        $pages = [];
        $args = [
            'order' => Constant::ASC,
            'orderby' => 'menu_order',
            'posts_per_page' => -1,
            'post_type' => [PostType::PAGE]
        ];
        $wpQuery = new WP_Query($args);
        while($wpQuery->have_posts()) {
            $wpQuery->the_post();
            $relativeLink = wp_make_link_relative(get_permalink(get_the_ID()));
            $url = PluginHelper::removeLastCharacter($relativeLink);
            $pages[$url] = get_the_title();
        }
        return $pages;
    }

    /**
     * Get the pages by title
     *
     * @param string $title the title to get the pages from
     * @return array the pages
     */
    public function getPagesByTitle(string $title) : array {
        $pages = [];
        $args = [
            'order' => Constant::ASC,
            'orderby' => 'menu_order',
            'posts_per_page' => -1,
            'post_type' => [PostType::PAGE],
            's' => $title
        ];
        $wpQuery = new WP_Query($args);
        while($wpQuery->have_posts()) {
            $wpQuery->the_post();
            $pages[] = [
                'title' => get_the_title(),
                'link' => get_permalink(get_the_ID()),
                'excerpt' => html_entity_decode(get_the_excerpt())
            ];
        }
        return $pages;
    }

    /**
     * Get the slides
     * 
     * @return array the slides
     */
    public function getSlides() : array {
        $slides = [];
        $args = [
            'post_type' => PostType::SLIDES,
            'posts_per_page' => -1,
            'order' => Constant::ASC,
            'orderby' => 'menu_order'
        ];
        $wpQuery = new WP_Query($args);
        while($wpQuery->have_posts()) {
            $wpQuery->the_post();
            $slides[] = ['image' => PluginHelper::getImageUrl(get_the_ID(), ImageSize::KC_SLIDES)];
        }
        return $slides;
    }

    /**
     * Get the galleries
     *
     * @return array the galleries
     */
    public function getGalleries() : array {
        $galleries = [];
        $args = [
            'post_type' => PostType::GALLERY,
            'posts_per_page' => -1,
            'order' => Constant::ASC
        ];
        $wpQuery = new WP_Query($args);
        while($wpQuery->have_posts()) {
            $wpQuery->the_post();
            $galleries[] = [
                'title' => get_the_title(),
                'link' => get_permalink(get_the_ID()),
                'image' => PluginHelper::getImageUrl(get_the_ID(), ImageSize::KC_GALLERY_IMAGE)
            ];
        }
        return $galleries;
    }

    /**
     * Get the images from a gallery
     * 
     * @param int $galleryId the gallery id
     * @return array the images
     */
    public function getImages(int $galleryId) : array {
        $images = [];
        $args = [
            'post_type' => PostType::IMAGE,
            'posts_per_page' => -1,
            'orderby' => Constant::TITLE,
            'order' => Constant::ASC,
            'meta_key' => FieldName::IMAGE_GALLERY,
            'meta_value' => $galleryId
        ];
        $wpQuery = new WP_Query($args);
        while($wpQuery->have_posts()) {
            $wpQuery->the_post();
            $id = get_the_ID();
            $url = PluginHelper::getImageUrl($id);
            $imageInfo = wp_get_attachment_image_src(attachment_url_to_postid($url));
            $images[] = [
                'title' => get_the_title(),
                'url' => PluginHelper::getImageUrl($id, ImageSize::LARGE),
                'thumbnail' => PluginHelper::getImageUrl($id, ImageSize::THUMBNAIL),
                'gallery' => $galleryId,
                'width' => $imageInfo[1].'px',
                'height' => $imageInfo[2].'px'
            ];
        }
        return $images;
    }

    /**
     * Update the download counter for a file
     *
     * @param int $fileID the id of the file
     */
    public function updateFileDownloadCounter(int $fileID) : void {
        $downloads = $this->getFileDownloads($fileID);
        $downloads++;
        PluginHelper::setFieldValue($downloads, FieldName::FILE_DOWNLOADS, $fileID);
    }

    /**
     * Get the files based on the file types
     * 
     * @param array $fileTypes the file types
     * @return array the files
     */
    public function getFiles(array $fileTypes) : array {
        $files = [];
        $args = [
            'post_type' => PostType::FILE,
            'posts_per_page' => -1,
            'order' => Constant::ASC,
            'tax_query' => [
                [
                    'taxonomy' => TaxonomyName::FILE_TYPE,
                    'terms' => $fileTypes
                ]
            ]
        ];
        $wpQuery = new WP_Query($args);
        while($wpQuery->have_posts()) {
            $wpQuery->the_post();
            $id = get_the_ID();
            $files[] = [
                'id' => $id,
                'fileName' => $this->getFileName($id), 
                'url' => $this->getFileUrl($id),
                'description' => $this->getFileDescription($id),
                'downloads' => $this->getFileDownloads($id)
            ];
        }
        return $files;
    }

    /**
     * Get the file url
     *
     * @param int $fileID the id of the file
     * @return string the file url
     */
    private function getFileUrl(int $fileID) : string {
        $attachmentID = PluginHelper::getFieldValue(FieldName::FILE, $fileID);
        return Security::escapeUrl(wp_get_attachment_url($attachmentID));
    }

    /**
     * Get the file name
     *
     * @param int $fileID the id of the file
     * @return string the file name
     */
    private function getFileName(int $fileID) : string {
        $attachmentID = PluginHelper::getFieldValue(FieldName::FILE, $fileID);
        return basename(get_attached_file($attachmentID));
    }

    /**
     * Get the file description
     *
     * @param int $fileID the id of the file
     * @return string the file description
     */
    private function getFileDescription(int $fileID) : string {
        return PluginHelper::getFieldValue(FieldName::FILE_DESCRIPTION, $fileID);
    }

    /**
     * Get the number of file downloads for a file
     *
     * @param int $fileID the id of the file
     * @return int the number of file downloads
     */
    private function getFileDownloads(int $fileID) : int {
        return PluginHelper::getFieldValue(FieldName::FILE_DOWNLOADS, $fileID);
    }
}