#include "AIManager.h"
#include "World.h"

// Include Ogre classes
#include "Ogre.h"
#include "OgreSceneNode.h"

AIManager::AIManager(World *world) : mWorld(world)
{
	mBallNode = mWorld->getBallNode();
	mLeftPaddleNode = mWorld->getLeftPaddleNode();
	ballSpeed = mWorld->getBallSpeed();
}

AIManager::~AIManager()
{
    // Clean up after yourself ...
}

void 
AIManager::Think(float time)
{
	if (mBallNode->getPosition().x >= 0) {
		if (mBallNode->getPosition().y <= mLeftPaddleNode->getPosition().y) {
			// AI goes down
			if (mLeftPaddleNode->getPosition().y >= -36) { // Don't go past bottom wall
				mLeftPaddleNode->translate(0, -time * ballSpeed, 0);
			}
		} else {
			// AI goes up
			if (mLeftPaddleNode->getPosition().y <= 35.3) { // Don't go past top wall
				mLeftPaddleNode->translate(0, time * ballSpeed, 0);
			}
		}
	}
}

