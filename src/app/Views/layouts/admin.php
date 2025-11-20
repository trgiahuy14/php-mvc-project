<?php
#HEADER
admin('header', ['headerData' => $headerData]);

#SIDEBAR
admin('sidebar');

echo $content;

#FOOTER
admin('footer');
