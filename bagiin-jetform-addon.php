<?php

/**
 * Plugin Name:         Bagiin Jetform Add-on
 * Plugin URI:          https://github.com/susantohenri/bagiin-jetform-addon
 * Description:         Jetform add on for bagiin to automate media deletion
 * Version:             1.0.0
 * Author:              henrisusanto
 * Author URI:          https://github.com/susantohenri/
 * Text Domain:         bagiin-jetform-addon
 * License:             GPL-3.0+
 * License URI:         http://www.gnu.org/licenses/gpl-3.0.txt
 * Requires at least:    6.1
 */

add_action('init', 'bagiin_jetform_addon_delete_media');
function bagiin_jetform_addon_delete_media()
{
    $post = $_POST;
    if (isset($post['post_id']) && isset($post['_jet_engine_booking_form_id'])) {
        $stored_gallery_ids = array_map(function ($gallery) {
            return $gallery['id'];
        }, get_post_meta($post['post_id'], '_photo_gallery', true));
        $stored_gallery_ids = array_values($stored_gallery_ids);

        if (isset($post['_photo_gallery'])) {
            $submitted_images_ids = array_map(function ($gallery) {
                $gallery = stripslashes($gallery);
                $gallery = json_decode($gallery);
                return $gallery->id;
            }, $post['_photo_gallery']);
        } else $submitted_images_ids = [];

        $to_delete = [];
        foreach ($stored_gallery_ids as $stored) {
            if (!in_array($stored, $submitted_images_ids)) {
                $to_delete[] = $stored;
                wp_delete_attachment($stored);
            }
        }

        // die(json_encode([
        //     'stored' => $stored_gallery_ids,
        //     'submitted' => $submitted_images_ids,
        //     'to_delete' => $to_delete
        // ]));
    }
}
