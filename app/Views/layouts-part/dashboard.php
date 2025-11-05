<?php
#HEADER
$this->renderView('parts/header', $getInfo);
?>

<?php
#SIDEBAR
$this->renderView('parts/sidebar');
?>

<?php
#CONTENT
echo $content;
?>

<?php
#FOOTER
$this->renderView('parts/footer');
?>