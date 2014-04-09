package com.vigo.vigoapp;

import java.io.ByteArrayOutputStream;

import com.vigo.vigoapp.bluetooth.GlobalHandlerUtil;
import com.vigo.vigoapp.bluetooth.factory.BlueToothMsgSender;
import com.vigo.vigoapp.bluetooth.factory.RequestFactory;
import com.vigo.vigoapp.bluetooth.factory.ResponseProcessorFactory;
import com.vigo.vigoapp.bluetooth.request.PhoneToDeviceRequestDataRequest;
import com.vigo.vigoapp.bluetooth.response.DeviceToPhoneDataResponseProcessor;
import com.vigo.vigoapp.bluetooth.response.ResponseProcessor;

import android.os.Bundle;
import android.os.Environment;
import android.app.Activity;
import android.view.Menu;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.Toast; // debugging purposes (printing out to app)

public class RequestDataActivity extends VigoBaseActivity implements OnClickListener {

	private Button mLogButton;
	private ByteArrayOutputStream logProtocols;
	private byte[] contentArray;
	
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		
		// Displays this page in app after "request data" button is pressed in DemoActivity.java
		vigoSetContentView(this, R.layout.activity_request_data);
		
		//GlobalHandlerUtil.getInstant().getRequestDataHandler().setRequestDataActivity(this);
		mLogButton = (Button)findViewById(R.id.log_data_button);
		mLogButton.setOnClickListener(this);
		
		logProtocols = new ByteArrayOutputStream();
		contentArray = null;
	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.request_data, menu);
		return true;
	}
	
	
	// Jourdan - Created in order to add bluetooth protocols into an ArrayList for later use. Called in activity_request.data.xml
	public void onCheckboxClicked(View v) {
		switch (v.getId()) {
			case R.id.log_state_box:
				logProtocols.write(0x30);
				break;
			case R.id.log_prox_1_box:
				logProtocols.write(0x10);
				break;
			case R.id.log_prox_2_box:
				logProtocols.write(0x11);
				break;
			case R.id.log_prox_3_box:
				logProtocols.write(0x12);
				break;
			case R.id.log_ambient_box:
				logProtocols.write(0x20);
				break;
			case R.id.log_yaw_box:
				logProtocols.write(0x21); // or 0x22
				break;
			case R.id.log_pitch_box:
				logProtocols.write(0x23); // or 0x24
				break;
			case R.id.log_roll_box:
				logProtocols.write(0x25); // or 0x25
				break;
			case R.id.log_totalblinks_box:
				logProtocols.write(0x31);
				break;
			case R.id.log_avgbpm_box:
				logProtocols.write(0x32);
				break;
			case R.id.log_avgblinkduration_box:
				logProtocols.write(0x35);
				break;
			case R.id.log_recentblinkduration_box:
				logProtocols.write(0x37);
				break;
			case R.id.log_avg_box:
				// Add protocol
				break;
			case R.id.log_perclos_box:
				logProtocols.write(0x47);
				break;
			case R.id.log_idletime_box:
				logProtocols.write(0x50);
				break;
			case R.id.log_headtilt_box:
				logProtocols.write(0x51);
				break;
			default:
				break;
		}
	}
	
	public void sendProtocolsAsMessage() {
		
		// Write bytes into a byte[] array
		contentArray = logProtocols.toByteArray();
		
		PhoneToDeviceRequestDataRequest request = new PhoneToDeviceRequestDataRequest();
		
		// Sets message successfully
		request = RequestFactory.getInstant().getPhoneToDeviceRequestDataRequest(contentArray);
		
		//System.out.println(request.getMessage().getMessageContent().length); // MESSAGE IS THERE!!!!!
		
		/* BluetoothAdaptor not initialized, this line doesn't work at the moment? */
		BlueToothMsgSender.getInstant().sendMsg(request); // Start byte 02
		
		// HARDCODED PROTOCOL
		request.getMessage().setMessageType((byte) 0x05);

		ResponseProcessor processor = ResponseProcessorFactory.getInstant().getProcessor(request.getMessage());
		processor.processor();

		Toast.makeText(this, "Your data has been logged successfully.", Toast.LENGTH_SHORT).show();
	}

	@Override
	public void onClick(View v) {
		switch (v.getId()) {
			case R.id.log_data_button:
				// Send protocols to handler
				sendProtocolsAsMessage();
				break;
			default:
				break;
		}
		
	}

}
