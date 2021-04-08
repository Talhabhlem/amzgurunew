<?php
move_uploaded_file($_FILES['upcfile']['tmp_name'], dirname(__FILE__).'/'.$_FILES['upcfile']['name']);
?>