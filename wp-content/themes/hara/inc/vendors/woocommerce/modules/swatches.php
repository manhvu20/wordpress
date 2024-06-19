<?php
if ( !hara_woocommerce_activated() ) return;


if (! function_exists('hara_has_swatch')) {
    function hara_has_swatch($id, $attr_name, $value)
    {
        $swatches = array();

        $color = $image = $button = '';
        
        $term = get_term_by('slug', $value, $attr_name);
        if (is_object($term)) {
            $color      =   sanitize_hex_color(get_term_meta($term->term_id, 'product_attribute_color', true));
            $image      =   get_term_meta($term->term_id, 'product_attribute_image', true);
            $button      =   $term->name;
        }

        if( $color != '' ) {
            $swatches['color']  = $color;
            $swatches['type']   = 'color';
        } elseif( $image != '' ) {
            $swatches['image']  = $image;
            $swatches['type']   = 'image';
        } else {
            $swatches['type']   = 'button';
        }

        $swatches['name']   = $button;

        return $swatches;
    }
}


if (! function_exists('hara_get_option_variations')) {
    function hara_get_option_variations($attribute_name, $available_variations, $option = false, $product_id = false)
    {
        $swatches_to_show = array();
        foreach ($available_variations as $key => $variation) {
            $option_variation = array();
            $attr_key = 'attribute_' . $attribute_name;
            if (! isset($variation['attributes'][$attr_key])) {
                return;
            }

            $val = $variation['attributes'][$attr_key]; // red green black ..

            if (! empty($variation['image']['thumb_src'])) {
                $option_variation = array(
                    'variation_id' => $variation['variation_id'],
                    'image_src' => $variation['image']['thumb_src'],
                    'image_srcset' => $variation['image']['srcset'],
                    'image_sizes' => $variation['image']['sizes'],
                    'is_in_stock' => $variation['is_in_stock'],
                );
            }

            // Get only one variation by attribute option value
            if ($option) {
                if ($val != $option) {
                    continue;
                } else {
                    return $option_variation;
                }
            } else {
                // Or get all variations with swatches to show by attribute name
                
                $swatch = hara_has_swatch($product_id, $attribute_name, $val);
                $swatches_to_show[$val] = array_merge($swatch, $option_variation);
            }
        }

        return $swatches_to_show;
    }
}

/**
 * ------------------------------------------------------------------------------------------------
 * Show attribute swatches list
 * ------------------------------------------------------------------------------------------------
 */
if (! function_exists('hara_swatches_list')) {
    function hara_swatches_list($attribute_name = false)
    {
        global $product;

        $id = $product->get_id();

        if (empty($id) || ! $product->is_type('variable')) {
            return false;
        }
        
        if (! $attribute_name) {
            $attribute_name = hara_get_swatches_attribute();
        }


        
        if (empty($attribute_name)) {
            return false;
        }

        $available_variations = $product->get_available_variations();

        if (empty($available_variations)) {
            return false;
        }

        $swatches_to_show = hara_get_option_variations($attribute_name, $available_variations, false, $id);


        if (empty($swatches_to_show)) {
            return false;
        }
 
        $terms = wc_get_product_terms($product->get_id(), $attribute_name, array( 'fields' => 'slugs' ));

        $swatches_to_show_tmp = $swatches_to_show;

        $swatches_to_show = array();

        foreach ($terms as $id => $slug) {
            if (!empty($swatches_to_show_tmp[$slug])) {
                $swatches_to_show[$slug] = $swatches_to_show_tmp[$slug];
            }
        }


        $out = '';
        $out .=  '<div class="tbay-swatches-wrapper"><ul data-attribute_name="attribute_'. $attribute_name .'">';

        foreach ($swatches_to_show as $key => $swatch) {
            $style = $class = '';

            $style .= '';

            $data = '';

            if (isset($swatch['image_src'])) {
                $class .= 'swatch-has-image';
                $data .= 'data-image-src="' . $swatch['image_src'] . '"';
                $data .= ' data-image-srcset="' . $swatch['image_srcset'] . '"';
                $data .= ' data-image-sizes="' . $swatch['image_sizes'] . '"';

                if (! $swatch['is_in_stock']) {
                    $class .= ' variation-out-of-stock';
                }
            }
            

            $term = get_term_by('slug', $key, $attribute_name);
            $slug   = $term->slug;

            $name = '';

            $name   = $swatch['name'];
            switch ($swatch['type']) {
                case 'color':
                    $style  = 'background-color:' .  $swatch['color'];
                    $class .= ' variable-item-span-color';
                    break;

                case 'image':
                    $img    = wp_get_attachment_image_src( $swatch['image'], 'woocommerce_thumbnail' );
                    $style  = 'background-image: url(' . $img['0'] . ')';
                    $class .= ' variable-item-span-image';
                    break;
                
                case 'button':
                    $class .= ' variable-item-span-label';
                    break;
                
                default:
                    break;
            } 

            $out .= '<li class="swatch-item variable-item-'. esc_attr($swatch['type']) .'"><div class="variable-item-contents">';
                $out .= '<a href="javascript:void(0)" class="swatch-item-tbay '. esc_attr($class) .' swatch swatch-'. strtolower($slug) .'" style="' . esc_attr($style) .'" ' . trim($data) . ' title="'. esc_attr($name) .'">' . trim($name) . '</a>';
            $out .= '</div></li>';
        }

        $out .=  '</ul>';
        $out .=  '</div>';

        return $out;
    }
}

if (! function_exists('hara_get_swatches_attribute')) {
    function hara_get_swatches_attribute()
    {
        $custom = get_post_meta(get_the_ID(), '_hara_attribute_select', true);

        return empty($custom) ? hara_tbay_get_config('variation_swatch') : $custom;
    }
}