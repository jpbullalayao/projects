package com.vigo.vigoapp;

import java.sql.SQLException;
import java.util.ArrayList;
import java.util.Date;

import android.content.Context;
import android.graphics.Color;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.View.OnClickListener;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.GridView;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.j256.ormlite.android.apptools.OpenHelperManager;
import com.j256.ormlite.dao.Dao;
import com.jjoe64.graphview.GraphView;
import com.jjoe64.graphview.GraphViewSeries;
import com.jjoe64.graphview.LineGraphView;
import com.jjoe64.graphview.GraphView.GraphViewData;
import com.vigo.vigoapp.R;
import com.vigo.vigoapp.bluetooth.GlobalHandlerUtil;
import com.vigo.vigoapp.bluetooth.UIHandlerProtocol;
import com.vigo.vigoapp.bluetooth.factory.BlueToothMsgSender;
import com.vigo.vigoapp.bluetooth.factory.RequestFactory;
import com.vigo.vigoapp.model.NewDatabaseHelper;
import com.vigo.vigoapp.model.NewLogData;

public class DemoActivity extends VigoBaseActivity implements OnClickListener {
	
	private static DemoActivity instance;
	private GridAdapter adapter;
	
	private Button mRequestDataButton;
	private Button mStopDataButton;
	private Button mExportDB;
	private TextView textStatus;
	
	private ArrayList<DataSelectionField> dataFields;
	private byte[] contentArray;
	public byte[] lastRequestArray;
	public int lastRequestArrayLength;
	
	private RelativeLayout mBlinkGraphLayout;
	private RelativeLayout mAccelGraphLayout;
	private GraphView mBlinkGraphView;
	private GraphView mAccelGraphView;
	private GraphViewSeries mProxDataSeries;
	private GraphViewSeries mAccelXDataSeries;
	private GraphViewSeries mAccelYDataSeries;
	private GraphViewSeries mAccelZDataSeries;
	private double graphLastXValue = 0d;
	
	private Dao<NewLogData, Integer> newLogDataDao;
	private NewLogData newLogData;
	
	private NewDatabaseHelper newDatabaseHelper = null;
	
	private NewDatabaseHelper getHelper() {
		if (newDatabaseHelper == null) {
			newDatabaseHelper = OpenHelperManager.getHelper(this, NewDatabaseHelper.class);
		}
		
		return newDatabaseHelper;
	}
	
	@Override
	protected void onDestroy() {
		super.onDestroy();

		if (newDatabaseHelper != null) {
			OpenHelperManager.releaseHelper();
			newDatabaseHelper = null;
		}
	}

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		vigoSetContentView(this, R.layout.activity_demo);
		
		instance = this;
		
		// Database objects setup
		newLogDataDao = getHelper().getNewLogDataDao();
		newLogData = new NewLogData();
		
		// Bind Handler to Activity
		GlobalHandlerUtil.getInstant().getDemoHandler().setDemoActivity(this);
		
		// Blink Graph setup
		mProxDataSeries = new GraphViewSeries(new GraphViewData[] { });
		mProxDataSeries.getStyle().color = Color.BLUE;
		mBlinkGraphView = new LineGraphView(this, "Real-time Blink Data");
		mBlinkGraphView.addSeries(mProxDataSeries);
		mBlinkGraphView.setViewPort(1, 100);
		mBlinkGraphView.setScalable(true);
		mBlinkGraphView.setManualYAxisBounds(255.0d, 0.0d); // TODO: Change to 0-65535 when using Si1143 sensor
		mBlinkGraphView.getGraphViewStyle().setHorizontalLabelsColor(Color.BLACK);
		mBlinkGraphView.getGraphViewStyle().setVerticalLabelsColor(Color.BLACK);

		mBlinkGraphLayout = (RelativeLayout) findViewById(R.id.blink_graph_layout);
		mBlinkGraphLayout.addView(mBlinkGraphView);
		
		// Accelerometer Graph setup
		mAccelXDataSeries = new GraphViewSeries(new GraphViewData[] { });
		mAccelYDataSeries = new GraphViewSeries(new GraphViewData[] { });
		mAccelZDataSeries = new GraphViewSeries(new GraphViewData[] { });
		mAccelXDataSeries.getStyle().color = Color.GREEN;
		mAccelYDataSeries.getStyle().color = Color.RED;
		mAccelZDataSeries.getStyle().color = Color.YELLOW;
		
		mAccelGraphView = new LineGraphView(this, "Real-time Accelerometer Data");
		mAccelGraphView.addSeries(mAccelXDataSeries);
		mAccelGraphView.addSeries(mAccelYDataSeries);
		mAccelGraphView.addSeries(mAccelZDataSeries);
		mAccelGraphView.setViewPort(1, 100);
		mAccelGraphView.setScalable(true);
		mAccelGraphView.setManualYAxisBounds(65536.0d, 0d);
		mAccelGraphView.getGraphViewStyle().setHorizontalLabelsColor(Color.BLACK);
		mAccelGraphView.getGraphViewStyle().setVerticalLabelsColor(Color.BLACK);

		mAccelGraphLayout = (RelativeLayout) findViewById(R.id.accelerometer_graph_layout);
		mAccelGraphLayout.addView(mAccelGraphView);
		
		// Button Setup
		mRequestDataButton = (Button)findViewById(R.id.request_data_button);
		mRequestDataButton.setOnClickListener(this);
		mStopDataButton = (Button)findViewById(R.id.stop_data_button);
		mStopDataButton.setOnClickListener(this);
		mExportDB = (Button)findViewById(R.id.export_data_button);
		mExportDB.setOnClickListener(this);
	}
	
	@Override
	public void onClick(View v) {
		switch (v.getId()) {
		
		// Start here with data logging
		case R.id.request_data_button:
			// Go to RequestDataActivity class
			launchActivity(RequestDataActivity.class);
			break;
		case R.id.stop_data_button:
			// Take off labels 
			removeLabels();
			
			// Stop requesting data
			contentArray = new byte[] {0x03,0x00,0x00,0x00,0x00,0x00,0x00,0x00};
			BlueToothMsgSender.getInstant().sendMsg(RequestFactory.getInstant().getPhoneToDeviceCommandRequest(contentArray)); // Start byte 01
			
			break;
		case R.id.export_data_button:
			// Go to DatabaseActivity class
			launchActivity(DatabaseActivity.class);
		}
	}
	
	public static DemoActivity getInstance() {
	    return instance;
	}
	
	@Override
	protected void onPause() {
		super.onPause();
	}

	@Override
	protected void onResume() {
		
		/* If != null, then we've already requested data to visualize */
		if (RequestDataActivity.getInstance() != null) {
			contentArray = RequestDataActivity.getInstance().getContentArray();
			BlueToothMsgSender.getInstant().sendMsg(RequestFactory.getInstant().getPhoneToDeviceRequestDataRequest(contentArray));
			
			lastRequestArray = contentArray;
			
			// Get actual length of lastRequestArray
			lastRequestArrayLength = 0;
			for (int i=0;i<lastRequestArray.length;i++) {
				if (lastRequestArray[i] != 0x00) {
					lastRequestArrayLength++;
				}
				else {
					break;
				}
			}
		}
		
		super.onResume();
	}	

	/* Function:	 removeLabels()
	 * Purpose:		 Remove visualized labels if user selects the "Stop Data" button.
	 * Date written: 4/13/2014
	 */
	public void removeLabels() {
		
		// Make an empty "labels" object, to pass into adapter
		ArrayList<DataSelectionField> labels = new ArrayList<DataSelectionField>();
		
		// Update adapter, pass in empty "labels" to say that there's nothing to visualize anymore
		adapter = new GridAdapter(this, R.layout.data_visualize_label, labels);
		GridView gridView = (GridView) findViewById(R.id.labels);
		gridView.setAdapter(adapter);
	}
	
	
	/* Function:	 getSelectedFields()
	 * Purpose:		 Return an object of data fields that user wants to visualize.
	 * Date written: 4/13/2014
	 */
	public ArrayList<DataSelectionField> getSelectedFields() {
		ArrayList<DataSelectionField> selectedFields = new ArrayList<DataSelectionField>();
		
		for (DataSelectionField dataField : dataFields) {
			if (dataField.isSelected()) {
				selectedFields.add(dataField);
			}
		}
		
		return selectedFields;
	}
	
	/* Function:     updateGraphView
	 * Purpose:      Updates the graph according to the values associated with each data that was requested for visualization.
	 * 				 Called in DemoHandler.java
	 * Date Written: ???
	 */
	public void updateGraphView(Object msgDataObj) {
		
		double[] msgDataArray = (double[])msgDataObj;
		
		if (RequestDataActivity.getInstance().getLogDataStatus() == true) {
			try {
				newLogData.setTimestamp(new Date());
				newLogData.setAccelx(new Double(msgDataArray[0]));
				newLogData.setAccely(new Double(msgDataArray[1]));
				newLogData.setAccelz(new Double(msgDataArray[2]));
				newLogData.setGyrox(new Double(msgDataArray[3]));
				newLogData.setGyroy(new Double(msgDataArray[4]));
				newLogData.setGyroz(new Double(msgDataArray[5]));
				newLogData.setProx1(new Double(msgDataArray[6]));
				newLogData.setState(new Double(msgDataArray[7]));
				
				newLogDataDao.create(newLogData);
			} catch (SQLException e) {
				e.printStackTrace();
			}
		}
		
		graphLastXValue += 1d;
		
		for (int i=0; i < lastRequestArrayLength; i++) {
			
			switch (lastRequestArray[i]) {
				case UIHandlerProtocol.idProx1:
				 	mProxDataSeries.appendData(new GraphViewData(graphLastXValue, msgDataArray[i]), true, 1000);
				 	break;
				case UIHandlerProtocol.idAccelX:
					mAccelXDataSeries.appendData(new GraphViewData(graphLastXValue, msgDataArray[i]), true, 1000);
				 	break;
				case UIHandlerProtocol.idAccelY:
					mAccelYDataSeries.appendData(new GraphViewData(graphLastXValue, msgDataArray[i]), true, 1000);
				 	break;
				case UIHandlerProtocol.idAccelZ:
					mAccelZDataSeries.appendData(new GraphViewData(graphLastXValue, msgDataArray[i]), true, 1000);
				 	break;
			}
		}
	}

	/* Function:     updateLabels
	 * Purpose:      Updates the numeric values of the labels while visualizing data. Called in DemoHandler.java.
	 * Date Written: 4/??/2014
	 */
	public void updateLabels(Object msgDataObj) {
		dataFields = RequestDataActivity.getInstance().getDataFields();
		ArrayList<DataSelectionField> selectedFields = getSelectedFields();
		
		double[] msgDataArray = (double[]) msgDataObj;
		for (int i = 0; i < lastRequestArrayLength; i++) {
			switch (lastRequestArray[i]) {
				case UIHandlerProtocol.idProx1:
					for (DataSelectionField dataField : selectedFields) {
						if (dataField.getName() == "log_prox_1") {
							dataField.setValue(msgDataArray[i]);
						}
					}
					break;
				case UIHandlerProtocol.idAccelX:
					for (DataSelectionField dataField : selectedFields) {
						if (dataField.getName() == "log_accel_x") {
							dataField.setValue(msgDataArray[i]);
						}
					}
					break;
				case UIHandlerProtocol.idAccelY:
					for (DataSelectionField dataField : selectedFields) {
						if (dataField.getName() == "log_accel_y") {
							dataField.setValue(msgDataArray[i]);
						}
					}
					break;
				case UIHandlerProtocol.idAccelZ:
					for (DataSelectionField dataField : selectedFields) {
						if (dataField.getName() == "log_accel_z") {
							dataField.setValue(msgDataArray[i]);
						}
					}
					break;
				case UIHandlerProtocol.idState:
					for (DataSelectionField dataField : selectedFields) {
						if (dataField.getName() == "log_state") {
							dataField.setValue(msgDataArray[i]);
						}
					}
					break;
				case UIHandlerProtocol.idGyroX:
					for (DataSelectionField dataField : selectedFields) {
						if (dataField.getName() == "log_gyro_x") {
							dataField.setValue(msgDataArray[i]);
						}
					}
					break;
				case UIHandlerProtocol.idGyroY:
					for (DataSelectionField dataField : selectedFields) {
						if (dataField.getName() == "log_gyro_y") {
							dataField.setValue(msgDataArray[i]);
						}
					}
					break;
				case UIHandlerProtocol.idGyroZ:
					for (DataSelectionField dataField : selectedFields) {
						if (dataField.getName() == "log_gyro_z") {
							dataField.setValue(msgDataArray[i]);
						}
					}
					break;
			}
		}
		
		adapter = new GridAdapter(this, R.layout.data_visualize_label, selectedFields);
		GridView gridView = (GridView) findViewById(R.id.labels);
		gridView.setAdapter(adapter);
	}
	
	public TextView getTextStatus() {
		return textStatus;
	}

	public void setTextStatus(TextView textStatus) {
		this.textStatus = textStatus;
	}

	
	/* Class:        GridAdapter
	 * Purpose:      Adapter class used for visualize labels. Only labels selected from RequestDataActivity will be visualized
	 * Date written: 4/12/2014
	 */
	private class GridAdapter extends ArrayAdapter<DataSelectionField> {
			
		private ArrayList<DataSelectionField> dataFields;
		
		public GridAdapter(Context context, int resource, ArrayList<DataSelectionField> dataFields) {
			super(context, resource, dataFields);
			this.dataFields = new ArrayList<DataSelectionField>();
			this.dataFields.addAll(dataFields);
		}
		
		private class ViewHolder {
			TextView text;
			TextView value;
		}
		
		@Override
		public View getView(int position, View convertView, ViewGroup parent) {
			ViewHolder holder = null;
			
			DataSelectionField dataField = dataFields.get(position);
			
			if (convertView == null) {
				LayoutInflater vi = (LayoutInflater) getSystemService(Context.LAYOUT_INFLATER_SERVICE);
				convertView = vi.inflate(R.layout.data_visualize_label, null);
				
				convertView.setBackgroundColor(dataField.getBackgroundColor());
				
				holder = new ViewHolder();
				holder.text = (TextView) convertView.findViewById(R.id.data_label);
				holder.value = (TextView) convertView.findViewById(R.id.data_value);
				convertView.setTag(holder);
			} else {
				holder = (ViewHolder) convertView.getTag();
			}
			
			holder.text.setText(dataField.getName());
			holder.value.setText(String.valueOf(dataField.getValue()));
			return convertView;
		}
	}
}
