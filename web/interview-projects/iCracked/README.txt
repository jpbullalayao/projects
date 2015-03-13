Author: Jourdan Bul-lalayao
Time Spent on Solution: ~7 Hours
Web technologies used:
- JavaScript
- AJAX
- PHP
- HTML5/CSS3

Classes Written:
1) index.php (Home Page)
2) world.php
3) fixer.php
4) breaker.php
5) broken_location.php
6) time.php
7) main.css (Stylesheet)


index.php
---------

This class is the home page for the solution, and is the only PHP file that contains HTML. The purpose of this class is to visualize everything that the back-end is generating. This includes the 20 x 20 grid, the time, the locations of all the broken items, the locations of all 20 Fixers, and the locations of all 200 Breakers. This class uses JavaScript and AJAX to constantly make calls to the back end in order to update the HTML as necessary. It also makes calls to PHP functions in order to visualize all the data that the back-end generated.

As for the Grid, there are four background colors: white, red, cyan, and magenta. White means that there is no breaker or fixer in that cell. Red means there is a broken item in that cell. Cyan means there is a fixer in that cell. Magenta means that there is both a fixer and a breaker in that cell. Each cell also contains its corresponding index in the grid.


world.php
---------

This class is the "Driver" for the entire back-end functionality. It holds all the necessary data structures and makes calls to the functions in other classes such as fixer.php and breaker.php. This class contains functions that creates the HTML Grid, as well as the Fixers and Breakers. This class also generates the necessary data that Fixer.php and Breaker.php must contain, such as their home locations, and other data members. It also contains functions that prints out the necessary components of the home page, such as the Grid, the locations of the Fixers and Breakers, and the locations of the broken items. Index.php calls these functions in order to visualize them.

Linked lists were used in order to keep track of the Fixers, Breakers, and locations of the broken items. The following linked lists are contained in world.php:

private $headFixer;           // Linked list of Fixers
private $headBreaker;         // Linked list of Breakers
private $headBrokenLocation;  // Linked list of Broken Locations

As for the Grid, it is a 2D array (array(array)). The dimensions for it at 20 x 20.


fixer.php
---------

This is a class that acts as a data structure for Fixers. It holds all the necessary data that Fixers need to keep track of. It is also used for the Fixer linked list in world.php. It also contains stubs to the following functions: move(), repair(), and returnHome(). 


breaker.php
-----------

This is a class that acts as a data structure for Breakers. It holds all the necessary data that Breakers need to keep track of. It is also used for the Breaker linked list in world.php.


broken_location.php
-------------------

This is a class that acts as a data structure for broken locations. It holds the x and y positions of the broken items, so that we can display on the home page where all the broken locations are. It is also used for the Broken Locations linked list in world.php.


time.php
--------

This is a very small script that gets the current time and prints it out on the page. This is used in index.php, where index.php uses AJAX in order to constantly make calls to this script every second in order to update and display the time on the home page every second.


main.css
--------

This is a stylesheet that handles all the styling for the home page.

