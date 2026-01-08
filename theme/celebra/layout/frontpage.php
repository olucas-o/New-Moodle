<?php
defined('MOODLE_INTERNAL') || die();

echo $OUTPUT->doctype();
?>

<html <?php echo $OUTPUT->htmlattributes(); ?>>
<head>
    <title><?php echo $SITE->fullname; ?></title>
    <?php echo $OUTPUT->standard_head_html(); ?>
</head>

<body <?php echo $OUTPUT->body_attributes(['celebra-frontpage-layout']); ?>>

<?php echo $OUTPUT->standard_top_of_body_html(); ?>

<div id="page" class="container-fluid">
    <main id="page-content">
        <?php echo $OUTPUT->main_content(); ?>
    </main>
</div>

<?php echo $OUTPUT->standard_end_of_body_html(); ?>
</body>
</html>
