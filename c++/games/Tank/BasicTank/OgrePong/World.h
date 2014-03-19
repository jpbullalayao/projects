#ifndef __World_h_
#define __World_h_

#include "Tank.h"
#include <time.h>

// Forward delcarations of Ogre classes.  Note the Ogre namespace!
namespace Ogre {
    class SceneNode;
    class SceneManager;
	class Vector3;
	class AxisAlignedBox;
}

// Forward delcarations of our own classes
class PongCamera;
class InputHandler;
class Tank;
class ProjectileManager;

class World
{
public:

	

	World(Ogre::SceneManager *sceneManager, InputHandler *input, Tank *tank);

    void Think(float mTime);
	void yawTank(InputHandler *mInputHandler, float mTime);
	void addCamera(PongCamera *c) { mCamera = c; }
	void addTank(Tank *t) {mTank = t; }
	void addProjectile(ProjectileManager *pm) { mProjectileManager = pm; }
	void setIterator();

	void Push(Tank::Node *node);

	/* Linked list structure that will contain AI tank nodes */
	struct LinkedList {
		Tank::Node *head;
	} mList;

	/* List of AI tanks */
	LinkedList *tanks;

	Ogre::SceneManager *SceneManager() { return mSceneManager; }

	Ogre::Vector3 *spawnPoints;

protected:

	Ogre::SceneManager *mSceneManager;
	InputHandler *mInputHandler;
	PongCamera *mCamera;
	Tank *mTank;
	ProjectileManager *mProjectileManager;
	
	Ogre::SceneNode *mFloorNode;

	/* Used to iterate through AI tank list to see if any user tank collides with another tank */
	Tank::Node *iterator;

	time_t start, end, diff;
};

#endif