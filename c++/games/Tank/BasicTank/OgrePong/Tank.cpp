#include "Tank.h"
#include "Camera.h"
#include "World.h"

#include "Ogre.h"
#include "OgreAxisAlignedBox.h"
#include "OgreSceneManager.h"
#include "OgreSceneNode.h"

#include <stdio.h>
#include <stdlib.h>

Tank::Tank(Ogre::SceneManager *sceneManager, World *world) : mSceneManager(sceneManager), mWorld(world)
{
	mMainNode = SceneManager()->getRootSceneNode()->createChildSceneNode();

	/* Create 1 user tank */
	createUserTank();

	/* Generate random seed to be used for spawn points */
	//srand ((unsigned)time(0));

	/* Create 12 enemy tanks */
	for (int i = 1; i < 13; i++) {
		createEnemyTank(i);
	}
}


Tank::~Tank(void)
{
}

/* Attach camera to the main user node */
void
Tank::attachCamera(void)
{
	mCameraNode = mMainNode->createChildSceneNode();
	mCameraNode->attachObject(mCamera->getCamera());
}

/* Creates user tank entity and node */
void
Tank::createUserTank(void)
{
	Ogre::Entity *tankEntity = SceneManager()->createEntity("coin.mesh");
	mTankNode = mMainNode->createChildSceneNode();
	mTankNode->attachObject(tankEntity);
	mTankNode->scale(5,5,5);

	/* DEBUGGING PURPOSES */
	mTankNode->showBoundingBox(true);

	mAABB = &tankEntity->getWorldBoundingBox();
}

/* Creates enemy tank entity and node */
void
Tank::createEnemyTank(int i)
{
	/* Generate random coordinates between -100 and 100 */
	//int random = rand() % 1;
	//int random = rand() % 13;
	//int x = (rand() % 201) - 100;
	//int z = (rand() % 201) - 100;

	Node *node = new Node();

	Ogre::SceneNode *eTankNode;
	Ogre::Entity *tank = SceneManager()->createEntity("coin.mesh");
	
	eTankNode = SceneManager()->getRootSceneNode()->createChildSceneNode();
	eTankNode->setPosition(mWorld->spawnPoints[i]);
	eTankNode->attachObject(tank);
	eTankNode->scale(5, 5, 5);

	node->eTankNode = eTankNode;
	node->eAABB = &tank->getWorldBoundingBox();
	node->destroyed = false;

	/* DEBUGGING PURPOSES */
	node->eTankNode->showBoundingBox(true);

	/* Push new tank node onto AI tank list */
	mWorld->Push(node);
}

void
Tank::destroyTank(Node *node)
{
	node->eTankNode->detachAllObjects();
	node->destroyed = true;
}

void
Tank::respawnEnemyTank(Node *node)
{
	Ogre::Entity *tankEntity = SceneManager()->createEntity("coin.mesh");
	node->eTankNode->attachObject(tankEntity);
	node->eAABB = &tankEntity->getWorldBoundingBox();
	node->destroyed = false;
}


