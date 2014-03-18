#ifndef __World_h_
#define __World_h_

// Forward delcarations of Ogre classes.  Note the Ogre namespace!
namespace Ogre {
    class SceneNode;
    class SceneManager;
	class Overlay;
}

// Forward delcarations of our own classes
class PongCamera;
class InputHandler;


class World
{
public:
	
    World(Ogre::SceneManager *sceneManager, InputHandler *input);
    
    void Think(float time);
	void startMenu(void);
	void getStartMenuInput(void);
	void createModels(void);
	void serveBall(float time);
	void Physics(float time);
	void Scoreboard(void);
	void endGame(void);
	void DetachAllObjects(void);
	void updateScoreboard(void);
	
	
	// Get Methods
	Ogre::SceneNode * getBallNode();
	Ogre::SceneNode * getLeftPaddleNode();
	float getPaddleSpeed();
	bool getSetGraphics();
	bool getEasyDifficulty();
	bool getHardDifficulty();
	bool getPaused();

	void addCamera(PongCamera *c) { mCamera = c; }

	Ogre::SceneManager *SceneManager() { return mSceneManager; }
	
protected:

	Ogre::SceneManager *mSceneManager;

	InputHandler *mInputHandler;
	PongCamera *mCamera;

	// Here is where you keep all your world data.
	//  You probably want to use containers (arrays / lists / classes / etc) to ogranize them, 
	//    instead of a whole list of variables.  

	Ogre::SceneNode *mCoinNode;
	Ogre::SceneNode *mTopWallNode;
	Ogre::SceneNode *mBottomWallNode;
	Ogre::SceneNode *mMiddleWallNode;
	Ogre::SceneNode *mLeftPaddleNode;
	Ogre::SceneNode *mRightPaddleNode;
	Ogre::SceneNode *mBallNode;
	
	Ogre::Overlay *overly1;
	Ogre::Overlay *overly2;
	Ogre::Overlay *menuDifficultyOverly;
	Ogre::Overlay *menuEasyOverly;
	Ogre::Overlay *menuHardOverly;
	Ogre::Overlay *userWonOverly;
	Ogre::Overlay *AIWonOverly;

	float ballSpeed;
	float paddleSpeed;
	int numHits;
	int userScore;
	int AIScore;

	bool choseDifficulty;
	bool easyDifficulty;
	bool hardDifficulty;
	bool setGraphics;
	bool start;
	bool hitTopWall;
	bool hitBottomWall;
	bool hitByAI;
	bool hitByUser;
	bool userScored;
	bool AIScored;
	bool AIWon;
	bool userWon;
	bool paused;
	bool gameOver;

	// Will decide what angle to serve ball at
	int random;
};

#endif