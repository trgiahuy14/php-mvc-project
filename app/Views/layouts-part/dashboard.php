<?php
#HEADER
$this->renderView('parts/header');
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