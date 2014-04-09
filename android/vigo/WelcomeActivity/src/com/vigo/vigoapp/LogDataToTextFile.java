package com.vigo.vigoapp;

/* Class:   LogData
 * Author:  Jourdan Bul-lalayao
 * Purpose: Used to log requested data by internal data user into a text file.
 */

import java.io.File;
import java.io.FileWriter;
import java.io.IOException;
import java.text.SimpleDateFormat;
import java.util.Date;

import android.os.Environment;

public class LogDataToTextFile {

	private byte[] bluetoothContentByteArray;
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

	private FileWriter writer;
	
	public LogDataToTextFile(byte[] bluetoothContentByteArray) {
		this.bluetoothContentByteArray = bluetoothContentByteArray;
		writer = null;
	}
	
	public void createFile() {
		Date date = new Date();
    	SimpleDateFormat sdf = new SimpleDateFormat("MM-dd-yyyy h-mm-ss a");
    	String fileName = sdf.format(date) + ".txt";
    	File root = Environment.getExternalStorageDirectory();
    	File file = new File(root, fileName);
    	
    	try {
			writer = new FileWriter(file, true);
		} catch (IOException e) {
			System.out.println("Unable to create file writer!");
			e.printStackTrace();
		}
	}
	
	public void log(RandomDataGenerator randGen) {
		
		createFile();
		
        // Try/catch statement necessary for FileWriter
        try {
            for (byte theByte : bluetoothContentByteArray) {
            	if (theByte == 0x30) {
            		state = randGen.getState();
            		writer.write("log_state\n");
            		writer.write(Integer.toString(state));
            	} else if (theByte == 0x10) {
            		prox1 = randGen.getProx1();
            		writer.write("log_prox_1\n");
            		writer.write(Integer.toString(prox1));
            	} else if (theByte == 0x11) {
            		prox2 = randGen.getProx2();
            		writer.write("log_prox_2\n");
            		writer.write(Integer.toString(prox2));
            	} else if (theByte == 0x12) {
            		prox3 = randGen.getProx3();
            		writer.write("log_prox_3\n");
            		writer.write(Integer.toString(prox3));
            	} else if (theByte == 0x20) {
            		ambient = randGen.getAmbient();
            		writer.write("log_ambient\n");
            		writer.write(Integer.toString(ambient));
            	} else if (theByte == 0x21) { // or 0x22
            		yaw = randGen.getYaw();
            		writer.write("log_yaw\n");
            		writer.write(Integer.toString(yaw));
            	} else if (theByte == 0x23) { // or 0x24
            		pitch = randGen.getPitch();
            		writer.write("log_pitch\n");
            		writer.write(Integer.toString(pitch));
            	} else if (theByte == 0x25) { // or 0x26
            		roll = randGen.getRoll();
            		writer.write("log_roll\n");
            		writer.write(Integer.toString(roll));
            	} else if (theByte == 0x31) {
            		totalBlinks = randGen.getTotalBlinks();
            		writer.write("log_totalblinks\n");
            		writer.write(Integer.toString(totalBlinks));
            	} else if (theByte == 0x32) {
            		avgBpm = randGen.getAvgBpm();
            		writer.write("log_avgbpm\n");
            		writer.write(Integer.toString(avgBpm));
            	} else if (theByte == 0x35) {
            		avgBlinkDuration = randGen.getAvgBlinkDuration();
            		writer.write("log_avgblinkduration\n");
            		writer.write(Integer.toString(avgBlinkDuration));
            	} else if (theByte == 0x37) {
            		recentBlinkDuration = randGen.getRecentBlinkDuration();
            		writer.write("log_recentBlinkDuration\n");
            		writer.write(Integer.toString(recentBlinkDuration));
            	} else if (theByte == 0x00) { // SUBSTITUTE LATER FOR LOG_AVG
            		avg = randGen.getAvg();
            		writer.write("log_avg\n");
            		writer.write(Integer.toString(avg));
            	} else if (theByte == 0x47) {
            		perClos = randGen.getPerClos();
            		writer.write("log_perclos\n");
            		writer.write(Integer.toString(perClos));
            	} else if (theByte == 0x50) {
            		idleTime = randGen.getIdleTime();
            		writer.write("log_idletime\n");
            		writer.write(Integer.toString(idleTime));
            	} else if (theByte == 0x51) {
            		headTilt = randGen.getHeadTilt();
            		writer.write("log_headtilt\n");
            		writer.write(Integer.toString(headTilt));
            	}
            	writer.write("\n\n");
            }
            writer.flush();
			writer.close();
	    } catch (IOException e) {
	        System.out.println("Unable to write to file!");
	        e.printStackTrace();
	    }
	}
}
