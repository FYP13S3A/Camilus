package com.example.camilus;


import java.io.IOException;
import java.util.ArrayList;
import java.util.List;

import org.apache.http.NameValuePair;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.ResponseHandler;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.BasicResponseHandler;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicNameValuePair;

import com.google.zxing.integration.android.IntentIntegrator;
import com.google.zxing.integration.android.IntentResult;

import android.os.AsyncTask;
import android.os.Bundle;
import android.app.Activity;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.view.Menu;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.EditText;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.Toast;

public class DeliveryActivity extends Activity implements OnClickListener{
	public static final int SIGNATURE_REQUEST = 1;  // The request code
	private Button btnScan, btnNext;
	private EditText txtParcelID, txtSName, txtRName, txtRAdd, txtItemNo, txtItemDetail;
	private ProgressDialog pdLoading;
	private RadioButton rdReceived, rdNotAtHome;
	private RadioGroup rdgDeliveryStatus; 
	IntentIntegrator scanIntegrator = new IntentIntegrator(this);
	String response = "000";
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_delivery);
		btnScan = (Button)findViewById(R.id.btnScan);
		btnNext = (Button)findViewById(R.id.btnNext);
		txtParcelID = (EditText)findViewById(R.id.txtParcelID);
		txtSName = (EditText)findViewById(R.id.txtSName);
		txtRName = (EditText)findViewById(R.id.txtRName);
		txtRAdd = (EditText)findViewById(R.id.txtRAdd);
		txtItemNo = (EditText)findViewById(R.id.txtItemNo);
		txtItemDetail = (EditText)findViewById(R.id.txtItemDet);
		rdReceived = (RadioButton)findViewById(R.id.rdReceived);
		rdNotAtHome = (RadioButton)findViewById(R.id.rdNotAtHome);
		rdgDeliveryStatus = (RadioGroup)findViewById(R.id.rdgDeliveryStatus);
		btnScan.setOnClickListener(this);
		btnNext.setOnClickListener(this);
		
		btnNext.setEnabled(false);
		rdReceived.setEnabled(false);
		rdNotAtHome.setEnabled(false);
	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.delivery, menu);
		return true;
	}

	@Override
	public void onClick(View v) {
		// TODO Auto-generated method stub
		if(v.getId()==R.id.btnScan){
			//scan
			scanIntegrator.initiateScan();
		}else if(v.getId()==R.id.btnNext){
			if(rdgDeliveryStatus.getCheckedRadioButtonId()==-1){
				AlertDialog.Builder builder = new AlertDialog.Builder(this);
				builder.setTitle("Delivery Confirmation Error");
				builder.setMessage("Please select a delivery status.")
				       .setCancelable(false)
				       .setPositiveButton("OK", new DialogInterface.OnClickListener() {
				           public void onClick(DialogInterface dialog, int id) {
				                //do things
				        	   dialog.cancel();
				           }
				       });
				AlertDialog alert = builder.create();
				alert.show();
			}
			else{
				Intent i = new Intent(this, SignatureActivity.class);
				i.putExtra("parcelid", txtParcelID.getText().toString());
				startActivityForResult(i, SIGNATURE_REQUEST);
			}
		}
	}
	
	public void onActivityResult(int requestCode, int resultCode, Intent intent) {
		if(requestCode == SIGNATURE_REQUEST){
			//capture signature
			if(resultCode==RESULT_OK){
				clearFields();
			}
		}
		else{
			//retrieve scan result
			IntentResult scanningResult = IntentIntegrator.parseActivityResult(requestCode, resultCode, intent);
			if (scanningResult != null) {
				//we have a result
				txtParcelID.setText(scanningResult.getContents());
				pdLoading = ProgressDialog.show(this, "", "Retrieving Data...");
				String deliveryDetails = "2:"+scanningResult.getContents();
				new MyAsyncTask().execute(deliveryDetails);
			}else{
				Toast.makeText(getApplicationContext(),"No scan data received!", Toast.LENGTH_SHORT).show();
		    }
		}
	}
	
	private class MyAsyncTask extends AsyncTask<String, Integer, Double>{
		 
		@Override
		protected Double doInBackground(String... params) {
			// TODO Auto-generated method stub
			postData(params[0]);
			return null;
		}
 
		protected void onPostExecute(Double result){
			pdLoading.dismiss();
			String[] responseArray = response.split(":");
			if(responseArray[0].equals("201")){
				Toast.makeText(getApplicationContext(),"Parcel found!", Toast.LENGTH_SHORT).show();
				txtSName.setText(responseArray[1]);
				txtRName.setText(responseArray[2]);
				txtRAdd.setText(responseArray[3]);
				txtItemNo.setText(responseArray[4]);
				txtItemDetail.setText(responseArray[5]);
				btnNext.setEnabled(true);
				rdReceived.setEnabled(true);
				rdNotAtHome.setEnabled(true);
			}else{
				Toast.makeText(getApplicationContext(),"No parcel found! Error code: "+response, Toast.LENGTH_SHORT).show();
			}
		}
		protected void onProgressUpdate(Integer... progress){
		}
 
		public void postData(String valueIWantToSend) {
			// Create a new HttpClient and Post Header
			HttpClient httpclient = new DefaultHttpClient();
			HttpPost httppost = new HttpPost("http://www.efxmarket.com/handler.php");
 
			try {
				// Add your data
				List<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>();
				nameValuePairs.add(new BasicNameValuePair("handlerData", valueIWantToSend));
				httppost.setEntity(new UrlEncodedFormEntity(nameValuePairs));
 
				// Execute HTTP Post Request
				ResponseHandler<String> responseHandler = new BasicResponseHandler();
				response = httpclient.execute(httppost, responseHandler);
				
			} catch (ClientProtocolException e) {
				// TODO Auto-generated catch block
			} catch (IOException e) {
				// TODO Auto-generated catch block
			}
		}
 
	}
	public void clearFields(){
		txtParcelID.setText("");
		txtSName.setText("");
		txtRName.setText("");
		txtRAdd.setText("");
		txtItemNo.setText("");
		txtItemDetail.setText("");
		btnNext.setEnabled(false);
		rdReceived.setChecked(false);
		rdNotAtHome.setChecked(false);
		rdReceived.setEnabled(false);
		rdNotAtHome.setEnabled(false);
	}
}
