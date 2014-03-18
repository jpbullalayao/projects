README for Pong Video Game

Technologies: C++, Visual Studio, OGRE

Basic Pong - 2/17/2014
=============================

1) I created all the models (the paddles, the pong ball, the top and bottom walls, and a halfcourt line), and placed them into the correct
   coordinates.
2) The user is the right paddle. The input for the user paddle is handled with the up and down arrow keys. The paddle only moves along
   the y-axis at the moment.
3) It compiles and runs perfectly.
4) The game logic has been handled, such as the physics, and the scoring.
5) The AI has been developed, and it is unbeatable.



Extreme Pong - 2/26/2014
========================

I have added a number of new concepts to my version of Extreme Pong. These include:

1) Developed a random number generator so that at the start of the game, the ball has 4 different ways to be served. It will either be served towards the User
   and towards the top wall, the user and the bottom wall, the AI and the top wall, or the AI and the bottom wall. The random number generator determines 
   which angle the ball is served at. You will see this upon the start of the game/program. Therefore, in order to see this feature, you must run my program
   several times and you will be able to see that the ball is served randomly at the start of each game.

2) Used this same random number generator in order to determine which angle the ball is served upon the AI scoring or the user scoring. My game logic is that
   if the user scores, the ball is served towards the user (to give the AI the advantage). And vice-versa, if the AI scores, the ball is served towards the AI
   (to give the user the advantage). However, the RNG comes into play with this logic because it determines which angle the ball is served upon score. For example,
   if the AI scores, the RNG determines whether or not it will serve the ball towards the AI using the top wall, or the bottom wall. Vice-versa, if the user scores,
   the RNG again determines whether or not it will serve the ball towards the user using the top wall or bottom wall.

3) Implemented a start menu that allows the user to choose how difficult the AI is (press E for easy, H for hard). This is done with the use of three overlays, and
   several lines of code with conditional variables and statements inside the World::Think() method. The AIManager class also has to consider running its code ONLY
   after the user has pressed a button; otherwise, the program will crash. That being said, the AI is adjusted to what difficulty the user chose. 'Easy' AI is easier
   than 'Hard' AI.

4) Implemented a way for the ball's speed to increase if no player has scored for a while. In my program, the ball's speed increases if the ball has hit the AI's and
   User's paddle a total of about 5-10 times. This increases the pressure for each player and makes it much easier to score for either player, and in a way also adds
   to the fun factor.

5) The game ends once the AI or User gets a score of 5 points. Upon getting 5 points, it displays an overlay that says "You win!" if the User wins, or "You lose..."
   if the AI wins. This contributes to the fun factor because it gives the user an end point to achieve. They must get 5 in order to win. If this game over functionality
   was not implemented, the user could play against the AI forever, which gets boring. Once the game is over, the user must exit the program and run it again in order to
   play.


Technicalities
==============
- The AI is the left paddle, and the User is the right paddle.
- The user is the only paddle that can be controlled, using the up arrow key to move up, and the down arrow key to move down.
