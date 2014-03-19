#ifndef __Tank_h_
#define __Tank_h_

namespace Ogre {
	class SceneNode;
	class SceneManager;
	class AxisAlignedBox;
}

class PongCamera;
class World;

class Tank
{
public:
	Tank(Ogre::SceneManager *sceneManager, World* world);

	~Tank(void);

	/* Node used to hold scene node for AI tank, bounding box, and next tank */
	typedef struct Node {
		Ogre::SceneNode *eTankNode;
		const Ogre::AxisAlignedBox *eAABB;
		Node *next;
		bool destroyed;
	} mNode;

	void attachCamera(void);
	void createUserTank(void);
	void createEnemyTank(int i);
	void destroyTank(Node *node);
	void respawnEnemyTank(Node *node);

	/* Node for user tank */
	Ogre::SceneNode *mMainNode;
	const Ogre::AxisAlignedBox *mAABB;
	
	void addCamera(PongCamera *c) { mCamera = c; }
	Ogre::SceneManager *SceneManager() { return mSceneManager; }

protected:
	PongCamera *mCamera;
	World *mWorld;
	Ogre::SceneNode *mTankNode;
	Ogre::SceneNode *mCameraNode;
	Ogre::SceneManager *mSceneManager;
};

#endif
