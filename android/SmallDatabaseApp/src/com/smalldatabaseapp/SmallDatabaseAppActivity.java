package com.smalldatabaseapp;

import java.io.File;
import java.io.FileInputStream;
import java.io.FileOutputStream;
import java.io.FileWriter;
import java.io.IOException;
import java.nio.channels.FileChannel;
import java.text.SimpleDateFormat;
import java.util.Date;

import com.db.DatabaseManager;
import com.model.NBATeam;

import android.os.Build;
import android.os.Bundle;
import android.os.Environment;
import android.app.Activity;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.view.Menu;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.EditText;

public class SmallDatabaseAppActivity extends Activity implements OnClickListener {

	private Button   mButton;
	private Button   mLogButton;
	private EditText mCity;
	private EditText mTeam;
	private String city;
	private String name;
	private FileWriter writer;
	private Cursor cursor;
	
	private SQLiteDatabase db;
    private static String DB_PATH;
    
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        DatabaseManager.init(this);
        setContentView(R.layout.activity_small_database_app);

        mButton = (Button) findViewById(R.id.button_add_team);
		mButton.setOnClickListener(this);
		
		mLogButton = (Button) findViewById(R.id.button_log_team);
		mLogButton.setOnClickListener(this);
		
		mCity = (EditText) findViewById(R.id.editText_city);
		mTeam = (EditText) findViewById(R.id.editText_name);
    }


    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.small_database_app, menu);
        return true;
    }

    public void addTeam() {
		city = mCity.getText().toString();
		name = mTeam.getText().toString();
		
		NBATeam team = new NBATeam();
		team.setCity(city);
		team.setName(name);
		
		DatabaseManager.getInstance().addTeam(team);
		
		/* Write DB to file */
		try {
			writeToSD();
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
    
	@Override
	public void onClick(View v) {
		switch (v.getId()) {
			case R.id.button_add_team:
				addTeam();
				break;
			case R.id.button_log_team:
				createFile();
				log();
				break;
		}
	}
	
	/* Write Database to file */
	private void writeToSD() throws IOException {
	    	
		if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.JELLY_BEAN_MR1) {
	        DB_PATH = this.getFilesDir().getAbsolutePath().replace("files", "databases") + File.separator;
	    }
	    else {
	        DB_PATH = this.getFilesDir().getPath() + this.getPackageName() + "/databases/";
	    }
	    	
	    File sd = Environment.getExternalStorageDirectory();
	
	    if (sd.canWrite()) {
	        String currentDBPath = Constants.DB_NAME;
	        String backupDBPath = "NBATeam.db";
	        File currentDB = new File(DB_PATH, currentDBPath);
	        File backupDB = new File(sd, backupDBPath);
	
	        if (currentDB.exists()) {
	            FileChannel src = new FileInputStream(currentDB).getChannel();
	            FileChannel dst = new FileOutputStream(backupDB).getChannel();
	            dst.transferFrom(src, 0, src.size());
	            src.close();
	            dst.close();
	        }
	    }
	}
	
	/* Create file that will contain contents of database */
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
	
	/* Log database contents into a text file */
	public void log() {

		/* Get readable SQLite database */
		db = DatabaseManager.getInstance().getDatabase();
		
		/* Get the "nbateam" table */
		cursor = db.rawQuery("SELECT * FROM " + "nbateam", null);
		
		/* Print out contents */
		try {
			while (cursor.moveToNext()) {
				writer.write(cursor.getString(0) + " " + cursor.getString(1) + "\n\n");
			}
			writer.flush();
			writer.close();
		} catch (IOException e) {
			e.printStackTrace();
		}
	}
}
