package com.vigo.vigoapp;

import java.util.Random;

/* Class:   RandomDataGenerator
 * Author:  Jourdan Bul-lalayao
 * Purpose: Used to generate fake data for the data logging feature. This is so that we can test to ensure that data logging works, so that we can later implement it
 * 			with the real bluetooth data.
 */

public class RandomDataGenerator {

	private int state;
	private int prox1;
	private int prox2;
	private int prox3;
	private int ambient;
	private int yaw;
	private int pitch;
	private int roll;
	private int totalBlinks;
	private int avgBpm;
	private int avgBlinkDuration;
	private int recentBlinkDuration;
	private int avg;
	private int perClos;
	private int idleTime;
	private int headTilt;
	private Random rand;
	
	public RandomDataGenerator() {
		rand = new Random();
	}
	
	public void random() {
		int min = 0;
		int max = 0;
		int randNum = 0;
		
		/* log_state */
		min = 1;
		max = 3;
		randNum = rand.nextInt((max - min + 1)) + min;
		setState(randNum);
		
		/* log_prox_1 */
		min = 1;
		max = 10;
		randNum = rand.nextInt((max - min + 1)) + min;
		setProx1(randNum);
		
		/* log_prox_2 */
		randNum = rand.nextInt((max - min + 1)) + min;
		setProx2(randNum);
		
		/* log_prox_3 */
		randNum = rand.nextInt((max - min + 1)) + min;
		setProx3(randNum);
		
		/* log_yaw */
		min = 1;
		max = 180;
		randNum = rand.nextInt((max - min + 1)) + min;
		setYaw(randNum);
		
		/* log_pitch */
		randNum = rand.nextInt((max - min + 1)) + min;
		setPitch(randNum);
		
		/* log_roll */
		randNum = rand.nextInt((max - min + 1)) + min;
		setRoll(randNum);
		
		/* log_totalblinks */
		min = 1;
		max = 500;
		randNum = rand.nextInt((max - min + 1)) + min;
		setTotalBlinks(randNum);
		
		/* log_avgbpm */
		min = 1;
		max = 60;
		randNum = rand.nextInt((max - min + 1)) + min;
		setAvgBpm(randNum);
		
		/* log_avgblinkduration */
		min = 1;
		max = 30;
		randNum = rand.nextInt((max - min + 1)) + min;
		setAvgBlinkDuration(randNum);
		
		/* log_recentblinkduration */
		min = 1;
		max = 30;
		randNum = rand.nextInt((max - min + 1)) + min;
		setRecentBlinkDuration(randNum);
		
		/* log_avg */
		min = 1;
		max = 30;
		randNum = rand.nextInt((max - min + 1)) + min;
		setAvg(randNum);
		
		/* log_perclos */
		min = 1;
		max = 100;
		randNum = rand.nextInt((max - min + 1)) + min;
		setPerClos(randNum);
		
		/* log_idletime */
		min = 1;
		max = 1000;
		randNum = rand.nextInt((max - min + 1)) + min;
		setIdleTime(randNum);
		
		/* log_headtilt */
		min = 0;
		max = 90;
		randNum = rand.nextInt((max - min + 1)) + min;
		setHeadTilt(randNum);
	}
	
	public int getState() {
		return state;
	}
	public void setState(int state) {
		this.state = state;
	}
	public int getProx1() {
		return prox1;
	}
	public void setProx1(int prox1) {
		this.prox1 = prox1;
	}
	public int getProx2() {
		return prox2;
	}
	public void setProx2(int prox2) {
		this.prox2 = prox2;
	}
	public int getProx3() {
		return prox3;
	}
	public void setProx3(int prox3) {
		this.prox3 = prox3;
	}
	public int getAmbient() {
		return ambient;
	}
	public void setAmbient(int ambient) {
		this.ambient = ambient;
	}
	public int getYaw() {
		return yaw;
	}
	public void setYaw(int yaw) {
		this.yaw = yaw;
	}
	public int getPitch() {
		return pitch;
	}
	public void setPitch(int pitch) {
		this.pitch = pitch;
	}
	public int getRoll() {
		return roll;
	}
	public void setRoll(int roll) {
		this.roll = roll;
	}
	public int getTotalBlinks() {
		return totalBlinks;
	}
	public void setTotalBlinks(int totalBlinks) {
		this.totalBlinks = totalBlinks;
	}
	public int getAvgBpm() {
		return avgBpm;
	}
	public void setAvgBpm(int avgBpm) {
		this.avgBpm = avgBpm;
	}
	public int getAvgBlinkDuration() {
		return avgBlinkDuration;
	}
	public void setAvgBlinkDuration(int avgBlinkDuration) {
		this.avgBlinkDuration = avgBlinkDuration;
	}
	public int getRecentBlinkDuration() {
		return recentBlinkDuration;
	}
	public void setRecentBlinkDuration(int recentBlinkDuration) {
		this.recentBlinkDuration = recentBlinkDuration;
	}
	public int getAvg() {
		return avg;
	}
	public void setAvg(int avg) {
		this.avg = avg;
	}
	public int getPerClos() {
		return perClos;
	}
	public void setPerClos(int perClos) {
		this.perClos = perClos;
	}
	public int getIdleTime() {
		return idleTime;
	}
	public void setIdleTime(int idleTime) {
		this.idleTime = idleTime;
	}
	public int getHeadTilt() {
		return headTilt;
	}
	public void setHeadTilt(int headTilt) {
		this.headTilt = headTilt;
	}
}
