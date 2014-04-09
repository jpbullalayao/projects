package com.model;

import com.j256.ormlite.field.DatabaseField;
import com.j256.ormlite.table.DatabaseTable;

@DatabaseTable
public class NBATeam {
	@DatabaseField(generatedId=true)
    private int id;
	
	@DatabaseField
	private String name;
	
	@DatabaseField
	private String city;
	
	public void setId(int id) {
		this.id = id;
	}
	
	public int getId() {
		return id;
	}
	
	public void setName(String name) {
		this.name = name;
	}
	
	public String getName(String name) {
		return name;
	}
	
	public void setCity(String city) {
		this.city = city;
	}
	
	public String getCity(String city) {
		return city;
	}
}
