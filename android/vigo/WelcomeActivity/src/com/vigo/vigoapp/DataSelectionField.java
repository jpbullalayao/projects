package com.vigo.vigoapp;

/* Class:   DataSelectionField
 * Author:  Jourdan Bul-lalayao
 * Purpose: Data structure used in ArrayList of RequestDataActivity. This is used for the nested Adapter class in
 *          RequestDataActivity. It is necessary in order to display the checkboxes for each data field, i.e.
 *          each data selection field.
 */
public class DataSelectionField {
	
	private String name;
	private double value;
	private int backgroundColor;
	private boolean selected;
	
	public DataSelectionField(String name, int backgroundColor, boolean selected) {
		this.name = name;
		this.backgroundColor = backgroundColor;
		this.selected = selected;
	}
	
	public String getName() {
		return name;
	}
	
	public void setValue(double value) {
		this.value = value;
	}
	
	public double getValue() {
		return value;
	}
	
	public int getBackgroundColor() {
		return backgroundColor;
	}
	
	public boolean isSelected() {
		return selected;
	}
	
	public void setSelected(boolean selected) {
		this.selected = selected;
	}
}
