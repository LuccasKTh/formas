<?php

spl_autoload_register(function ($class) {
   include "../../../Class/$class.class.php"; 
});