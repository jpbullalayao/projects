package com.db;

import java.sql.SQLException;

import android.content.Context;
import android.database.sqlite.SQLiteDatabase;

import com.db.DatabaseHelper;
import com.model.NBATeam;

public class DatabaseManager {

    static private DatabaseManager instance;

    static public void init(Context ctx) {
        if (null==instance) {
            instance = new DatabaseManager(ctx);
        }
    }

    static public DatabaseManager getInstance() {
        return instance;
    }

    private DatabaseHelper helper;
    private DatabaseManager(Context ctx) {
        helper = new DatabaseHelper(ctx);
    }

    private DatabaseHelper getHelper() {
        return helper;
    }
    
    public void addTeam(NBATeam team) {
        try {
            getHelper().getNBATeamDao().create(team);
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }
    
    public SQLiteDatabase getDatabase() {
    	return getHelper().getDatabase();
    }
}