// My header file.  This should go first!
#include "World.h"

// Ogre header files
#include "Ogre.h"
#include "OgreMath.h"
#include "OgreSceneManager.h"
#include "OgreSceneNode.h"
#include "OgreOverlayManager.h"
#include "OgreOverlay.h"
#include "OgreFontManager.h"
#include "OgreTextAreaOverlayElement.h"

// IOS (Input system) header files

#include <ois/ois.h>

// Other input files for my project
#include "Camera.h"
#include "InputHandler.h"


World::World(Ogre::SceneManager *sceneManager, InputHandler *input, Tank *tank)   : mSceneManager(sceneManager), mInputHandler(input), mTank(tank)
{

	// Global illumination for now.  Adding individual light sources will make you scene look more realistic
	mSceneManager->setAmbientLight(Ogre::ColourValue(1,1,1));

	Ogre::Entity *ent2 = SceneManager()->createEntity("Cube.mesh");
	mFloorNode = SceneManager()->getRootSceneNode()->createChildSceneNode(Ogre::Vector3(0, -2, 0));
	mFloorNode->attachObject(ent2);
	mFloorNode->scale(30, 0, 30);

	// Yeah, this should be done automatically for all fonts referenced in an overlay file.
	//  But there is a bug in the OGRE code so we need to do it manually.
	Ogre::FontManager::getSingleton().getByName("BlueHighwayBig")->load();

	// Now we will show the sample overlay.  Look in the file Content/Overlays/Example to
	// see how this overlay is defined
	/*Ogre::OverlayManager& om = Ogre::OverlayManager::getSingleton();
	Ogre::Overlay *overly = om.getByName("Sample");
	overly->show();
	*/

	tanks = new LinkedList();
	tanks->head = NULL;

	/* Initialize positions for 13 spawn points */
	spawnPoints = new Ogre::Vector3[13];
	spawnPoints[0] = Ogre::Vector3(0, 0, 0);
	spawnPoints[1] = Ogre::Vector3(0, 0, 50);
	spawnPoints[2] = Ogre::Vector3(0, 0, 100);
	spawnPoints[3] = Ogre::Vector3(75, 0, 150);
	spawnPoints[4] = Ogre::Vector3(50, 0, 0);
	spawnPoints[5] = Ogre::Vector3(100, 0, 0);
	spawnPoints[6] = Ogre::Vector3(150, 0, 75);
	spawnPoints[7] = Ogre::Vector3(0, 0, -50);
	spawnPoints[8] = Ogre::Vector3(0, 0, -100);
	spawnPoints[9] = Ogre::Vector3(-75, 0, -150);
	spawnPoints[10] = Ogre::Vector3(-50, 0, 0);
	spawnPoints[11] = Ogre::Vector3(-100, 0, 0);
	spawnPoints[12] = Ogre::Vector3(-150, 0, -75);

	start = time(NULL);
}

/* Rotate the tank left or right */
void
World::yawTank(InputHandler *mInputHandler, float mTime)
{
	const float RADIANS_PER_SECOND = 1;
	
	/* Turn left */
	if (mInputHandler->IsKeyDown(OIS::KC_LEFT))
	{
		mTank->mMainNode->yaw(Ogre::Radian(mTime * RADIANS_PER_SECOND));
	} 

	/* Turn right */
	else
	{
		mTank->mMainNode->yaw(-Ogre::Radian(mTime * RADIANS_PER_SECOND));
	}
}

/* Initialize iterator to head of AI tank list */
void
World::setIterator() 
{
	iterator = tanks->head;
}

/* Pushes a new AI tank created in Tank.cpp onto the AI tank list */
void
World::Push(Tank::Node *node)
{
	node->next = tanks->head;
	tanks->head = node;
}

void 
World::Think(float mTime)
{
	const float TANK_SPEED = 50;

	/* Set 'end' equal to the current time */
	end = time(NULL);
	diff = end - start;

	/* Every 5 seconds, go through this condition */
	if (diff > 5) {

		/* Reset iterator back to head in order to iterate through tank list */
		setIterator();

		/* Go through entire tank list and respawn all tanks that are destroyed */
		while (iterator != NULL)
		{
			if (iterator->destroyed)
			{
				mTank->respawnEnemyTank(iterator);
				start = time(NULL);
			}
			iterator = iterator->next;
		}

		setIterator();
	}

	/* Rotate tank left or right */
	if (mInputHandler->IsKeyDown(OIS::KC_LEFT) || mInputHandler->IsKeyDown(OIS::KC_RIGHT))
	{
		yawTank(mInputHandler, mTime);
	}
	
	/* Move tank forward */
	else if (mInputHandler->IsKeyDown(OIS::KC_UP))
	{

		if (iterator != NULL && iterator->eAABB != NULL)
		{
			/* User hasn't collided with anything yet */
			if (!mTank->mAABB->intersects(*iterator->eAABB))
			{
				mTank->mMainNode->translate(0, 0, mTime * TANK_SPEED, Ogre::Node::TS_LOCAL);
				iterator = iterator->next;
			}
			
			/* User collided with another tank */
			else 
			{
				/* Bump the tank back upon collision */
				mTank->mMainNode->translate(0, 0, -mTime * 100 * TANK_SPEED, Ogre::Node::TS_LOCAL);
			}
		}
		
		else 
		{
			/* Reset iterator back to head in order to infinitely iterate through tank list */
			setIterator();
		}
	}

	/* Move tank backward */
	else if (mInputHandler->IsKeyDown(OIS::KC_DOWN))
	{
		if (iterator != NULL && iterator->eAABB != NULL)
		{
			/* User hasn't collided with anything yet */
			if (!mTank->mAABB->intersects(*iterator->eAABB))
			{
				mTank->mMainNode->translate(0, 0, -mTime * TANK_SPEED, Ogre::Node::TS_LOCAL);
				iterator = iterator->next;
			}
			
			/* User collided with another tank */
			else
			{ 
				/* Bump the tank forward upon collision */
				mTank->mMainNode->translate(0, 0, mTime * 100 * TANK_SPEED, Ogre::Node::TS_LOCAL);
			}
		}
		
		else 
		{
			/* Reset iterator back to head in order to infinitely iterate through tank list */
			setIterator();
		}
	}
}




