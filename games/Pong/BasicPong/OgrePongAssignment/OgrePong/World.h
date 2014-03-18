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
	void createModels(void);
	void Physics(float time);
	void Scoreboard(void);
	void updateScoreboard(void);
	
	// Get Methods
	Ogre::SceneNode * getBallNode();
	Ogre::SceneNode * getLeftPaddleNode();
	float getBallSpeed();

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

	float ballSpeed;

	bool start;
	bool hitTopWall;
	bool hitBottomWall;
	bool hitByAI;
	bool hitByUser;
	bool userScored;
	bool AIScored;
};

#endif