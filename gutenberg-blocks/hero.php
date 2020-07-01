<?php

/**
 * Hero Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'hero-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'hero';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}

// Load values and assign defaults.
$header = get_field('header') ?: 'The page header..';
$subheader = get_field('subheader') ?: 'The subheader..';
$description = get_field('description') ?: '';
$image = get_field('image') ?: 1024;

?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">

    <h1 class="hero-header"><?php echo $header; ?></h1>
    <h2 class="hero-subheader"><?php echo $subheader; ?></h2>

    <?php if( $description ): ?>

        <p class="hero-description"><?php echo $description; ?></p>
    
    <?php endif; ?>    

    <div class="hero-image">
    <img src="<?php echo $image; ?>" />
        <?php echo wp_get_attachment_image( $image, 'full' ); ?>
    </div>   

</div>