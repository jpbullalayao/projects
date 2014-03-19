#ifndef __AIManager_h_
#define __AIManager_h_

namespace Ogre {
	class SceneNode;
}

class World;

class AIManager 
{

public:
    // You may want to add parameters to the AI Manager constructor
	AIManager(World *world);
	~AIManager();

    // Go through all of the AIs, and call think on each one
    void Think(float time);

	void Control(float time, Ogre::SceneNode *mBallNode, Ogre::SceneNode *mLeftPaddleNode);

protected:
	World *mWorld;
	
	// Variables used in World.h/.cpp
	Ogre::SceneNode *mBallNode;
	Ogre::SceneNode *mLeftPaddleNode;
	float paddleSpeed;

	bool setGraphics;
	bool easyDifficulty;
	bool hardDifficulty;
	bool paused;
};

#endif