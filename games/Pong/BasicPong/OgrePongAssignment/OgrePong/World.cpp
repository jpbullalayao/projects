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
#include "OgreStringConverter.h"

// IOS (Input system) header files
#include <ois/ois.h>

// Other input files for my project
#include "Camera.h"
#include "InputHandler.h"


World::World(Ogre::SceneManager *sceneManager, InputHandler *input)   : mSceneManager(sceneManager), mInputHandler(input)
{
	/* Necessary booleans for computing physics */
	start = true;
	hitTopWall = false;
	hitBottomWall = false;
	hitByAI = false;
	hitByUser = false;
	userScored = false;
	AIScored = false;

	ballSpeed = 35;

	// Global illumination for now.  Adding individual light sources will make you scene look more realistic
	mSceneManager->setAmbientLight(Ogre::ColourValue(1,1,1));
	
	createModels();

	// Yeah, this should be done automatically for all fonts referenced in an overlay file.
	//  But there is a bug in the OGRE code so we need to do it manually.
	Ogre::ResourceManager::ResourceMapIterator iter = Ogre::FontManager::getSingleton().getResourceIterator();
	while (iter.hasMoreElements()) 
	{ 
		iter.getNext()->load(); 
	}

	// Display scoreboard
	Scoreboard();
}

void
World::createModels(void) {

	// Create wall nodes
	Ogre::Entity *TopWall = mSceneManager->createEntity("Walls/Wall.mesh");
	Ogre::Entity *BottomWall = mSceneManager->createEntity("Walls/Wall.mesh");
	Ogre::Entity *MiddleWall = mSceneManager->createEntity("Walls/MiddleWall.mesh");

	// Create paddle nodes
	Ogre::Entity *LeftPaddle = mSceneManager->createEntity("Paddles/Paddle.mesh");
	Ogre::Entity *RightPaddle = mSceneManager->createEntity("Paddles/Paddle.mesh");

	// Create ball
	Ogre::Entity *Ball = mSceneManager->createEntity("Ball/Ball.mesh");

	// Walls
	mTopWallNode = mSceneManager->getRootSceneNode()->createChildSceneNode(Ogre::Vector3(0, 40, 0));
	mTopWallNode->attachObject(TopWall);
	mBottomWallNode = mSceneManager->getRootSceneNode()->createChildSceneNode(Ogre::Vector3(0, -40, 0));
	mBottomWallNode->attachObject(BottomWall);
	mMiddleWallNode = mSceneManager->getRootSceneNode()->createChildSceneNode(Ogre::Vector3(0, -4, 0));
	mMiddleWallNode->attachObject(MiddleWall);

	// Paddles
	mLeftPaddleNode = mSceneManager->getRootSceneNode()->createChildSceneNode(Ogre::Vector3(50, 0, 0));
	mLeftPaddleNode->attachObject(LeftPaddle);
	mRightPaddleNode = mSceneManager->getRootSceneNode()->createChildSceneNode(Ogre::Vector3(-50, 0, 0));
	mRightPaddleNode->attachObject(RightPaddle);

	// Ball
	mBallNode = mSceneManager->getRootSceneNode()->createChildSceneNode(Ogre::Vector3(0, 0, 0));
	mBallNode->attachObject(Ball);

	mTopWallNode->scale(10, 1, 0);			// Resizes wall
	mBottomWallNode->scale(10, 1, 0);		// Resizes wall
	mMiddleWallNode->scale(0.5, 12, 0);		// Resizes wall

	mLeftPaddleNode->scale(1, 1, 0);		// Resizes paddle
	mRightPaddleNode->scale(1, 1, 0);		// Resizes paddle
}

void
World::Scoreboard(void) {

	// Scoreboard overlays
	Ogre::OverlayManager& om = Ogre::OverlayManager::getSingleton();
	overly1 = om.getByName("Player1");
	overly1->show();

	overly2 = om.getByName("Player2");
	overly2->show();
}

void
World::updateScoreboard(void) {
	
	// User scored
	if (mBallNode->getPosition().x >= 60) {

		Ogre::OverlayManager& om = Ogre::OverlayManager::getSingleton();
		Ogre::TextAreaOverlayElement *te = (Ogre::TextAreaOverlayElement *) om.getOverlayElement("Scoreboard/Panel2/Score2");
		
		// Set new score for user player
		int score = Ogre::StringConverter::parseInt(te->getCaption());
		score++;
		te->setCaption(Ogre::StringConverter::toString(score));

		// Reset necessary booleans
		hitTopWall = false;
		hitBottomWall = false;
		hitByUser = false;
		hitByAI = false;
		start = true;

		// Send ball back to middle
		mBallNode->setPosition(Ogre::Vector3(0, 0, 0));
	}

	// AI scored
	if (mBallNode->getPosition().x <= -60) {

		Ogre::OverlayManager& om = Ogre::OverlayManager::getSingleton();
		Ogre::TextAreaOverlayElement *te = (Ogre::TextAreaOverlayElement *) om.getOverlayElement("Scoreboard/Panel1/Score1");
		
		// Set new score for AI player
		int score = Ogre::StringConverter::parseInt(te->getCaption());
		score++;
		te->setCaption(Ogre::StringConverter::toString(score));

		// Reset necessary booleans
		hitTopWall = false;
		hitBottomWall = false;
		hitByUser = false;
		hitByAI = false;
		start = true;

		// Send ball back to middle
		mBallNode->setPosition(Ogre::Vector3(0, 0, 0));
	}
}

void
World::Physics(float time) {

	// Serve the ball
	if (start) {
		mBallNode->translate(time * ballSpeed, 0, 0);
		mBallNode->translate(0, time * ballSpeed, 0);
		hitByUser = true;	// Even though it wasn't hit by user... the direction is going towards the AI
	}

	/* Hits top wall */
	if (mBallNode->getPosition().y >= 38) {
		hitTopWall = true;
		hitBottomWall = false;
		start = false; // Don't want to use top if statement again
	}

	/* Hits bottom wall */
	else if (mBallNode->getPosition().y <= -38) {
		hitBottomWall = true;
		hitTopWall = false;
	}

	/* Translate ball southwest direction */
	if (hitTopWall && hitByUser) {
		mBallNode->translate(time * ballSpeed, 0, 0);
		mBallNode->translate(0, -time * ballSpeed, 0);
	}

	/* Translate ball southeast direction */
	if (hitTopWall && hitByAI) {
		mBallNode->translate(-time * ballSpeed, 0, 0);
		mBallNode->translate(0, -time * ballSpeed, 0);
	}

	/* Translate ball northwest direction */
	if (hitBottomWall && hitByUser) {
		mBallNode->translate(time * ballSpeed, 0, 0);
		mBallNode->translate(0, time * ballSpeed, 0);
	}

	/* Translate ball northeast direction */
	if (hitBottomWall && hitByAI) {
		mBallNode->translate(-time * ballSpeed, 0, 0);
		mBallNode->translate(0, time * ballSpeed, 0);
	}

	/* If in same x coordinate as left paddle */
	if (mBallNode->getPosition().x >= 50 && mBallNode->getPosition().x <= 51) {

		/* If it touches paddle model */
		if (mBallNode->getPosition().y >= mLeftPaddleNode->getPosition().y - 5 &&
			mBallNode->getPosition().y <= mLeftPaddleNode->getPosition().y + 5) {
			hitByAI = true;
			hitByUser = false;
		}
	}

	/* If in same x coordinate as right paddle */
	if (mBallNode->getPosition().x <= -50 && mBallNode->getPosition().x >= -51) {

		/* If it touches paddle model */
		if (mBallNode->getPosition().y >= mRightPaddleNode->getPosition().y - 5 &&
			mBallNode->getPosition().y <= mRightPaddleNode->getPosition().y + 5) {
			hitByUser = true;
			hitByAI = false;
		}
	}
}

Ogre::SceneNode *
World::getBallNode() {
	return mBallNode;
}

Ogre::SceneNode *
World::getLeftPaddleNode() {
	return mLeftPaddleNode;
}

float
World::getBallSpeed() {
	return ballSpeed;
}

void 
World::Think(float time)
{
	const float RADIANS_PER_SECOND = 0.5;
	
	Physics(time);

	// Move right paddle up
	if (mInputHandler->IsKeyDown(OIS::KC_UP))
	{
		if (mRightPaddleNode->getPosition().y <= 35.3) // Don't go past top wall
			mRightPaddleNode->translate(0, time * ballSpeed, 0);
	}

	// Move right paddle down
	else if (mInputHandler->IsKeyDown(OIS::KC_DOWN))
	{
		if (mRightPaddleNode->getPosition().y >= -36) // Don't go past bottom wall
			mRightPaddleNode->translate(0, -time * ballSpeed, 0);
	}

	updateScoreboard();
}


