package com.vigo.vigoapp;

import java.io.ByteArrayOutputStream;
import java.util.ArrayList;

import com.j256.ormlite.android.apptools.OpenHelperManager;
import com.vigo.vigoapp.model.DatabaseHelper;

import android.content.Context;
import android.graphics.Color;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.View;
import android.view.ViewGroup;
import android.view.View.OnClickListener;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.CompoundButton;
import android.widget.CompoundButton.OnCheckedChangeListener;
import android.widget.ListView;
import android.widget.Switch;
import android.widget.TextView;
import android.widget.Toast; // debugging purposes (printing out to app)
import android.widget.AdapterView.OnItemClickListener;

public class RequestDataActivity extends VigoBaseActivity implements OnClickListener {

	// Objects 
	private static RequestDataActivity instance;
	private MyAdapter adapter;
	private ArrayList<DataSelectionField> dataFields;
	private ByteArrayOutputStream logProtocols;
	
	// Buttons
	private Button mRequestButton;
	private Button mSelectAllButton;
	private Button mUnselectAllButton;
	private Switch mLogDataButton;
	
	// Bluetooth Data Structures 
	private byte[] contentArray;
	public byte[] lastRequestArray;
	public int lastRequestArrayLength;
	
	private int numSelected;
	
	private static boolean logData;
	
	private DatabaseHelper databaseHelper = null;
	
	private DatabaseHelper getHelper() {
		if (databaseHelper == null) {
			databaseHelper = OpenHelperManager.getHelper(this, DatabaseHelper.class);
		}

		return databaseHelper;
	}
	
	@Override
	protected void onDestroy() {
		super.onDestroy();

		if (databaseHelper != null) {
			OpenHelperManager.releaseHelper();
			databaseHelper = null;
		}
	}
	
	public boolean getLogDataStatus() {
		return RequestDataActivity.logData;
	}
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		
		// Displays this page in app after "request data" button is pressed in DemoActivity.java
		vigoSetContentView(this, R.layout.activity_request_data);
		
		instance = this;
		
		logData = false;

		mRequestButton = (Button)findViewById(R.id.request_data_button);
		mRequestButton.setOnClickListener(this);
		
		mSelectAllButton = (Button)findViewById(R.id.select_all_data_button);
		mSelectAllButton.setOnClickListener(this);
		
		mUnselectAllButton = (Button)findViewById(R.id.unselect_all_data_button);
		mUnselectAllButton.setOnClickListener(this);
		
		mLogDataButton = (Switch)findViewById(R.id.log_data_button);
		mLogDataButton.setOnCheckedChangeListener(new OnCheckedChangeListener() {
			@Override
			public void onCheckedChanged(CompoundButton buttonView,
					boolean isChecked) {
				if (isChecked) {
					Toast.makeText(RequestDataActivity.this, "Log Data Switch On", Toast.LENGTH_SHORT).show();
					logData = true;
				} else {
					Toast.makeText(RequestDataActivity.this, "Log Data Switch Off", Toast.LENGTH_SHORT).show();
					logData = false;
				}
				
			}
		});
		
		logProtocols = new ByteArrayOutputStream();
		contentArray = null;
		
		// Set up stuff for Data Selection
		dataFields = new ArrayList<DataSelectionField>();
		adapter = new MyAdapter(this, R.layout.data_selection_field, R.id.text, dataFields);
		ListView listView = (ListView) findViewById(R.id.listView);
		listView.setAdapter(adapter);

		// OnClickListener for data selection list
		listView.setOnItemClickListener(new OnItemClickListener() {
			public void onItemClick(AdapterView parent, View view, int position, long id) {
				DataSelectionField dataField = (DataSelectionField) parent.getItemAtPosition(position);
				
				if (dataField.isSelected()) {
					dataField.setSelected(false);
				} else {
					dataField.setSelected(true);
				}

				ListView listView = (ListView) findViewById(R.id.listView);

				// Update list view
				listView.setAdapter(adapter);
			}
		});
		
		displayDataFields();
	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.request_data, menu);
		return true;
	}
	
	@Override
	public void onClick(View v) {
		switch (v.getId()) {
			case R.id.request_data_button:
				getProtocols(); // Get protocols associated with selected data
				finish(); // Dismiss this activity and go back to DemoActivity.java 
				break;
			case R.id.select_all_data_button:
				selectAll();
				break;
			case R.id.unselect_all_data_button:
				unselectAll();
				break;
			default:
				break;
		}
	}
	
	/* Function: 	 getProtocols()
	 * Purpose:  	 Determines which checkboxes were checked, and adds the corresponding bluetooth protocols to the logProtocols array stream
	 * Date written: 4/12/2014
	 */
	public void getProtocols() {
		numSelected = 0;
		
		for (DataSelectionField dataField : dataFields) {
			// If the user selected this dataField
			if (dataField.isSelected()) {
				if (dataField.getName() == "log_accel_x") {
					logProtocols.write(0x16);
				} else if (dataField.getName() == "log_accel_y") {
					logProtocols.write(0x17);
				} else if (dataField.getName() == "log_accel_z") {
					logProtocols.write(0x18);
				} else if (dataField.getName() == "log_gyro_x") {
					logProtocols.write(0x19);
				} else if (dataField.getName() == "log_gyro_y") {
					logProtocols.write(0x1A);
				} else if (dataField.getName() == "log_gyro_z") {
					logProtocols.write(0x1B);
				} else if (dataField.getName() == "log_state") {
					logProtocols.write(0x30);
				} else {
					logProtocols.write(0x10); // log_prox_1
				} 
				numSelected++;
			}
		}
		
		// Fill in rest of byte array with 0x00
		if (numSelected < 8) {
			for (int i = numSelected; i < 8; i++) {
				logProtocols.write(0x00);
			}
		}
		
		// Write bytes into a byte[] array
		contentArray = logProtocols.toByteArray();
	}

	/* Function:     displayDataFields()
	 * Purpose:      Sets up all the data selection fields through an ArrayList and Adapter class
	 * Date written: 4/12/2014
	 */
	public void displayDataFields() {
		dataFields = new ArrayList<DataSelectionField>();
		
		DataSelectionField dataField;
		
		dataField = new DataSelectionField("log_prox_1", Color.CYAN, false);
		dataFields.add(dataField);
		
		dataField = new DataSelectionField("log_accel_x", Color.GREEN, false);
		dataFields.add(dataField);
		
		dataField = new DataSelectionField("log_accel_y", Color.RED, false);
		dataFields.add(dataField);
		
		dataField = new DataSelectionField("log_accel_z", Color.YELLOW, false);
		dataFields.add(dataField);
		
		dataField = new DataSelectionField("log_gyro_x", Color.rgb(255, 102, 0), false);
		dataFields.add(dataField);
		
		dataField = new DataSelectionField("log_gyro_y", Color.MAGENTA, false);
		dataFields.add(dataField);
		
		dataField = new DataSelectionField("log_gyro_z", Color.LTGRAY, false);
		dataFields.add(dataField);
		
		dataField = new DataSelectionField("log_state", Color.GRAY, false);
		dataFields.add(dataField);
		
		// Update List View with data selection fields
		adapter = new MyAdapter(this, R.layout.data_selection_field, R.id.text, dataFields);
		ListView listView = (ListView) findViewById(R.id.listView);

		listView.setAdapter(adapter);
	}
	
	
	/* Function:     selectAll()
	 * Purpose:      Selects each checkbox in RequestDataActivity. In essence, the user wants to request all data to be visualized.
	 * Date written: 4/12/2014
	 */
	public void selectAll() {
		
		// Go through each data field, select each one
		for (DataSelectionField dataField : dataFields) {
			dataField.setSelected(true);
		}
		
		// Update List View to display checked data fields
		adapter = new MyAdapter(this, R.layout.data_selection_field, R.id.text, dataFields);
		ListView listView = (ListView) findViewById(R.id.listView);

		listView.setAdapter(adapter);
	}
	
	
	/* Function:     unselectAll()
	 * Purpose:      Unselects each checkbox in RequestDataActivity.
	 * Date written: 4/12/2014
	 */
	public void unselectAll() {
		
		// Go through each data field, unselect each one
		for (DataSelectionField dataField : dataFields) {
			dataField.setSelected(false);
		}
		
		// Update List View to display unchecked data fields
		adapter = new MyAdapter(this, R.layout.data_selection_field, R.id.text, dataFields);
		ListView listView = (ListView) findViewById(R.id.listView);

		listView.setAdapter(adapter);
	}
	
	public static RequestDataActivity getInstance() {
		return instance;
	}
	
	public byte[] getContentArray() {
		return contentArray;
	}
	
	
	/* Class:	     getDataFields
	 * Purpose:      Get method used to return "dataFields" object, called in DemoActivity in order to visualize data
	 * Data written: 4/13/2014
	 */
	public ArrayList<DataSelectionField> getDataFields() {
		return dataFields;
	}
	
	
	/* Class:        MyAdapter
	 * Purpose:      Adapter class used for Data Selection, specifically for "Select All" and "Unselect All" buttons
	 * Date written: 4/12/2014
	 */
	private class MyAdapter extends ArrayAdapter<DataSelectionField> {
		
		private ArrayList<DataSelectionField> dataFields;
		
		public MyAdapter(Context context, int resource, int textViewResourceId, ArrayList<DataSelectionField> dataFields) {
			super(context, resource, textViewResourceId, dataFields);
			this.dataFields = new ArrayList<DataSelectionField>();
			this.dataFields.addAll(dataFields);
		}
		
		private class ViewHolder {
			TextView text;
			CheckBox checkbox;
		}
		
		@Override
		public View getView(int position, View convertView, ViewGroup parent) {
			ViewHolder holder = null;
			
			final DataSelectionField dataField = dataFields.get(position);
			
			if (convertView == null) {
				LayoutInflater vi = (LayoutInflater) getSystemService(Context.LAYOUT_INFLATER_SERVICE);
				convertView = vi.inflate(R.layout.data_selection_field, null);

				holder = new ViewHolder();
				holder.text = (TextView) convertView.findViewById(R.id.text);
				holder.checkbox = (CheckBox) convertView.findViewById(R.id.checkBox);
				
				// OnClickListener for checkbox
				holder.checkbox.setOnClickListener(new OnClickListener() {

					@Override
					public void onClick(View v) {
						if (dataField.isSelected()) {
							dataField.setSelected(false);
						} else {
							dataField.setSelected(true);
						}
					}
					
				});
				
				convertView.setTag(holder);
			} else {
				holder = (ViewHolder) convertView.getTag();
			}

			holder.text.setText(dataField.getName());
			holder.checkbox.setChecked(dataField.isSelected());
			return convertView;
		}
	}
}
