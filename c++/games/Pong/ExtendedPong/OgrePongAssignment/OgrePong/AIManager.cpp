#include "AIManager.h"
#include "World.h"

// Include Ogre classes
#include "Ogre.h"
#include "OgreSceneNode.h"

AIManager::AIManager(World *world) : mWorld(world)
{
	paddleSpeed = mWorld->getPaddleSpeed();
}

AIManager::~AIManager()
{
    // Clean up after yourself ...
}

void
AIManager::Control(float time, Ogre::SceneNode *mBallNode, Ogre::SceneNode *mLeftPaddleNode)
{
	if (mBallNode->getPosition().y <= mLeftPaddleNode->getPosition().y) {
		// AI goes down
		if (mLeftPaddleNode->getPosition().y >= -36) { // Don't go past bottom wall
			mLeftPaddleNode->translate(0, -time * paddleSpeed, 0);
		}
	} else {
		// AI goes up
		if (mLeftPaddleNode->getPosition().y <= 35.3) { // Don't go past top wall
			mLeftPaddleNode->translate(0, time * paddleSpeed, 0);
		}
	}
}

void 
AIManager::Think(float time)
{
	mBallNode = mWorld->getBallNode();
	setGraphics = mWorld->getSetGraphics();
	mLeftPaddleNode = mWorld->getLeftPaddleNode();
	easyDifficulty = mWorld->getEasyDifficulty();
	hardDifficulty = mWorld->getHardDifficulty();
	paused = mWorld->getPaused();

	/* Don't let AIManager work until all the graphics have been set */
	if (setGraphics) {
		if (hardDifficulty) {
			if (mBallNode->getPosition().x >= 0) {
				Control(time, mBallNode, mLeftPaddleNode);
			}
		}
		else if (easyDifficulty) {
			if (mBallNode->getPosition().x >= 30) {
				Control(time, mBallNode, mLeftPaddleNode);
			}
		}
	}
}

