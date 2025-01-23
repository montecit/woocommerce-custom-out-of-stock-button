<?php
/**
 * Plugin Name: WooCommerce Custom Out of Stock Button
 * Description: Stokta olmayan ürünlerde "Sepete Ekle" yerine "Stok Sorunuz" butonu ekler. Buton WhatsApp mesajına yönlendirir.
 * Version: 1.6
 * Author: makeitdigi digital agency - makeitdigi.com
 * Text Domain: custom-out-of-stock-button
 */

if (!defined('ABSPATH')) {
    exit; // Do not allow direct access
}

// Sepete Ekle butonunu gizleyip yeni butonu ekleme
add_action('woocommerce_single_product_summary', 'custom_replace_add_to_cart_button', 31);

function custom_replace_add_to_cart_button() {
    global $product;

    // Eğer ürün stokta yoksa
    if (!$product->is_in_stock()) {
        // WhatsApp mesajı için ürün adı ve linki alalım
        $product_name = urlencode($product->get_name());
        $product_link = urlencode(get_permalink($product->get_id()));

        // WhatsApp linki oluştur
        $whatsapp_number = "905000000000"; // Buraya WhatsApp numaranızı girin (90 Türkiye alan kodu için)
        $whatsapp_message = "Merhaba, ürünle ilgili stok durumunu öğrenmek istiyorum: $product_name ($product_link)";
$whatsapp_url = "https://wa.me/$whatsapp_number?text=$whatsapp_message";

        // Yeni butonu yazdır
        echo '<a href="' . esc_url($whatsapp_url) . '" class="button alt" style="background-color: #25D366; color: #fff; border: none; padding: 10px 20px; text-transform: uppercase;">Stok Sorunuz</a>';
    }
}

// Elementor ile uyumlu hale getirme
add_filter('elementor/widget/render_content', 'custom_elementor_out_of_stock_button', 10, 2);

function custom_elementor_out_of_stock_button($content, $widget) {
    if ($widget->get_name() === 'woocommerce-product-add-to-cart' && is_product()) {
        global $product;

        if (!$product->is_in_stock()) {
            $product_name = urlencode($product->get_name());
            $product_link = urlencode(get_permalink($product->get_id()));
            $whatsapp_number = "905000000000"; // WhatsApp numaranızı buraya girin
            $whatsapp_message = "Merhaba, ürünle ilgili stok durumunu öğrenmek istiyorum: $product_name ($product_link)";
$whatsapp_url = "https://wa.me/$whatsapp_number?text=$whatsapp_message";

            // Yeni buton
            $button_html = '<a href="' . esc_url($whatsapp_url) . '" class="button alt" style="background-color: #25D366; color: #fff; border: none; padding: 10px 20px; text-transform: uppercase;">Stok Sorunuz</a>';
            return $button_html;
        }
    }
    return $content;
}
