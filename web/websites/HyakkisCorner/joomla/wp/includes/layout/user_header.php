<?php
function head($title) {
    echo "<!DOCTYPE html>";
    echo "<html lang=\"en\">";
        echo "<head>";
            echo "<meta charset=\"utf-8\" />";
    
            echo "<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame";
            echo "Remove this if you use the .htaccess -->";
            echo "<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge,chrome=1\" />";
    
            echo "<title>{$title}</title>";
            echo "<meta name=\"description\" content=\"\" />";
            echo "<meta name=\"author\" content=\"Jourdan\" />";
    
            echo "<meta name=\"viewport\" content=\"width=device-width; initial-scale=1.0\" />";
    
            echo "<!-- Replace favicon.ico & apple-touch-icon.png in the root of your domain and delete these references -->";
            echo "<link rel=\"shortcut icon\" href=\"/favicon.ico\" />";
            echo "<link rel=\"apple-touch-icon\" href=\"/apple-touch-icon.png\" />";
            echo "<link rel=\"stylesheet\" href=\"../stylesheets/stylesheet.css\" />";
            echo "<link rel=\"icon\" href=\"images/favicon.png\" type=\"image/x-icon\">";
            echo "<script src=\"//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js\"></script>";
        echo "</head>";
        echo "<body>";
            echo "<div id=\"wrapper\"> <!-- Wrapper -->";
    }
?>