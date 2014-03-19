#ifndef __MainListener_h_
#define __MainListener_h_

#include "Ogre.h"
#include "OgreFrameListener.h"


// Forward declaration of classes
namespace Ogre
{
	class RenderWindow;
}

class InputHandler;
class AIManager;
class World;
class PongCamera;
class ProjectileManager;

class MainListener : public Ogre::FrameListener
{
public:
	MainListener(Ogre::RenderWindow *mainWindow, InputHandler *inputHandler, AIManager *aiManager, World *world, PongCamera *cam, ProjectileManager *pm);

	bool frameStarted(const Ogre::FrameEvent &evt);


protected:
	InputHandler *mInputHandler;
	AIManager *mAIManager;
	World *mWorld;
    PongCamera *mPongCamera;
	ProjectileManager *mProjectileManager;
	Ogre::RenderWindow *mRenderWindow;
	int x;
};

#endif