<?php 
date_default_timezone_set("Asia/Manila");

echo date('F j, Y g:i:a ');




date('F j, Y g:i:a', strtotime($post['created_at'])); 
?>  