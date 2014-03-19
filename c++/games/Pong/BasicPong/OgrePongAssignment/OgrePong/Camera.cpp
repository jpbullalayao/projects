#include "OgreCamera.h"
#include "Camera.h"
#include "World.h"
#include "OgreVector3.h"

PongCamera::PongCamera(Ogre::Camera *renderCamera, World *world) :
mRenderCamera(renderCamera), mWorld(world)
{
	mRenderCamera->setNearClipDistance(2);
}

void
PongCamera::Think(float time)
{

	// Any code needed here to move the camera about per frame
	//  (use mRenderCamera to get the actual render camera, of course!)
}
