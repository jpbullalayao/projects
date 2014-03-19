#include "ProjectileManager.h"
#include "InputHandler.h"
#include "Tank.h"
#include "World.h"

#include "Ogre.h"
#include "OgreSceneManager.h"
#include "OgreAxisAlignedBox.h"

ProjectileManager::ProjectileManager(Ogre::SceneManager *sceneManager, InputHandler *input, World *world) : mSceneManager(sceneManager), mInputHandler(input), mWorld(world)
{
	comeDown = false;
	shotProjectile = false;
	spawnedProjectile = false;
}


ProjectileManager::~ProjectileManager(void)
{
}

/* Spawn projectile when user wants to shoot it */
void
ProjectileManager::spawnProjectile(void)
{
	/* If there is no current projectile that was shot */
	if (!shotProjectile) {
		Ogre::Entity *projectile = SceneManager()->createEntity("Cube.mesh");
		
		/* Projectile is child of tank, so that it spawns wherever the tank is */
		mProjectileNode = mTank->mMainNode->createChildSceneNode();
		mProjectileNode->attachObject(projectile);

		mProjectileNode->scale(2,2,2);

		// Debugging purposes 
		mProjectileNode->showBoundingBox(true);
		
		mAABB = &projectile->getWorldBoundingBox();
	}
}

void
ProjectileManager::shootProjectile(float time)
{
	const float PROJECTILE_SPEED = 75;

	/* User just shot the projectile */
	shotProjectile = true;

	/* Projectile going up */
	if (mProjectileNode->getPosition().y < 10 && comeDown == false)
	{
		mProjectileNode->translate(0, time * PROJECTILE_SPEED, 0, Ogre::Node::TS_LOCAL);
		mProjectileNode->translate(0, 0, time * 8 * PROJECTILE_SPEED, Ogre::Node::TS_LOCAL);
	}

	/* Projectile hit peak */
	else if (mProjectileNode->getPosition().y >= 10 && comeDown == false)
	{
		comeDown = true;
	}

	/* Projectile going down */
	else if (comeDown == true && mProjectileNode->getPosition().y > 0)
	{
		mProjectileNode->translate(0, -time * PROJECTILE_SPEED, 0, Ogre::Node::TS_LOCAL);
		mProjectileNode->translate(0, 0, time * 8 * PROJECTILE_SPEED, Ogre::Node::TS_LOCAL);
	}

	/* Projectile hit the ground */
	if (mProjectileNode->getPosition().y <= 0 && comeDown == true)
	{
		comeDown = false;
		shotProjectile = false;
		despawnProjectile();
	}
}

/* Make projectile disappear */
void
ProjectileManager::despawnProjectile()
{
	mProjectileNode->detachAllObjects();
}

/* Initialize iterator to head of AI tank list */
void
ProjectileManager::setIterator(void)
{
	iterator = mWorld->tanks->head;
}

void
ProjectileManager::Think(float time)
{
	/* Shoot projectile if 'space' key is pressed. Also continue translating projectile when space isn't pressed. */
	if (mInputHandler->IsKeyDown(OIS::KC_SPACE) || shotProjectile == true)
	{
		spawnProjectile();
		shootProjectile(time);

		if (iterator != NULL && iterator->eAABB != NULL) 
		{
			/* User hit an AI tank */
			if (mAABB->intersects(*iterator->eAABB))
			{
				mTank->destroyTank(iterator);
				despawnProjectile();

				/* Set AI tank's bounding box to NULL so that user tank can move through the spot that the AI was killed at */
				iterator->eAABB = &iterator->eAABB->BOX_NULL;
			}
			iterator = iterator->next;
		}
		else 
		{
			/* Reset iterator back to head in order to infinitely iterate through tank list */
			setIterator();
		}
	}
}