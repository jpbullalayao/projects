#include "Camera.h"
#include "OgreCamera.h"
#include "Tank.h"
#include "World.h"

PongCamera::PongCamera(Ogre::Camera *renderCamera, World *world) :
mRenderCamera(renderCamera), mWorld(world)
{
	mRenderCamera->setNearClipDistance(2);
}

Ogre::Camera *
PongCamera::getCamera()
{
	return mRenderCamera;
}

void
PongCamera::Think(float time)
{

	// Any code needed here to move the camera about per frame
	//  (use mRenderCamera to get the actual render camera, of course!)
}
