package ss133a.mobile.camilus;


import java.io.IOException;
import java.net.SocketTimeoutException;
import java.util.ArrayList;
import java.util.List;

import org.apache.http.NameValuePair;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.ResponseHandler;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.conn.ConnectTimeoutException;
import org.apache.http.impl.client.BasicResponseHandler;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicNameValuePair;
import org.apache.http.params.BasicHttpParams;
import org.apache.http.params.HttpConnectionParams;
import org.apache.http.params.HttpParams;

import android.os.AsyncTask;
import android.os.Bundle;
import android.app.Activity;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.text.format.DateFormat;
import android.view.Menu;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.RadioGroup;
import android.widget.TextView;

public class Delivery extends Activity {
	private ProgressDialog pdLoading;
	public final static String DELIVERY_JOBID = "ss133a.mobile.camilus.DELIVERY_JOBID";
	public final static String DELIVERY_MANIFESTID = "ss133a.mobile.camilus.DELIVERY_MANIFESTID";
	public final static String DELIVERY_DRIVERID = "ss133a.mobile.camilus.DELIVERY_DRIVERID";
	public static final int SIGNATURE_REQUEST = 1;
	private TextView txtDSName,txtDRName,txtDAddress,txtDContent;
	private Button btnDUpdate;
	private RadioGroup rdgDeliveryStatus; 
	Intent intent;
	String manifestid,jobId,driverId;
	int groupPos,childPos;
	private Context context = this;
	JobsManager jm;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_delivery);
		
		txtDSName = (TextView)findViewById(R.id.txtDSName);
		txtDRName = (TextView)findViewById(R.id.txtDRName);
		txtDAddress = (TextView)findViewById(R.id.txtDAddress);
		txtDContent = (TextView)findViewById(R.id.txtDContent);
		btnDUpdate = (Button)findViewById(R.id.btnDUpdate);
		rdgDeliveryStatus = (RadioGroup)findViewById(R.id.rdgDeliveryStatus);
		jm = Main.jm;
		
		/*Receive data from caller's intent*/
		intent = getIntent();
		String job = intent.getStringExtra(Jobs.JOB_DATA);
		manifestid = intent.getStringExtra(Jobs.JOB_MANIFESTID);
		groupPos = intent.getIntExtra(Jobs.JOB_GROUP_POSN, 0);
		childPos = intent.getIntExtra(Jobs.JOB_CHILD_POSN, 0);
		String[] jobdata = job.split("\\|");
		jobId = jobdata[0];
		driverId = jm.getDriver();
		setTitle(manifestid);
		txtDSName.setText(jobdata[3]);
		txtDRName.setText(jobdata[4]);
		txtDAddress.setText(jobdata[1]);
		txtDContent.setText(jobdata[5]);
		
		btnDUpdate.setOnClickListener(new OnClickListener(){
        	public void onClick(View v){
        		/*To check if user selects a delivery status option*/
        		if(rdgDeliveryStatus.getCheckedRadioButtonId()==-1){
    				AlertDialog.Builder builder = new AlertDialog.Builder(v.getContext());
    				builder.setTitle("Delivery Confirmation Error");
    				builder.setMessage("Please select a delivery status.")
    				       .setCancelable(false)
    				       .setPositiveButton("OK", new DialogInterface.OnClickListener() {
    				           public void onClick(DialogInterface dialog, int id) {
    				           }
    				       });
    				AlertDialog alert = builder.create();
    				alert.show();
    			}else{
    				int option = rdgDeliveryStatus.getCheckedRadioButtonId();
    				if(option==R.id.rdACollected){
    					/*Prepare data to send to Signature.class*/
    	        		Intent sigIntent = new Intent(v.getContext(),Signature.class);
    	        		sigIntent.putExtra(DELIVERY_JOBID, jobId);
    	        		sigIntent.putExtra(DELIVERY_MANIFESTID, manifestid);
    	        		sigIntent.putExtra(DELIVERY_DRIVERID, driverId);
    	        		startActivityForResult(sigIntent, SIGNATURE_REQUEST);
    				}else{
    					/*Confirm status option with user*/
    					AlertDialog.Builder alertDialogBuilder = new AlertDialog.Builder(v.getContext());
    					alertDialogBuilder.setTitle("Delivery Confirmation");
    					alertDialogBuilder
    						.setMessage("You have selected 'Not At Home'. Proceed to confirm delivery status?")
    						.setCancelable(false)
    						.setPositiveButton("Yes",new DialogInterface.OnClickListener() {
    							public void onClick(DialogInterface dialog,int id) {
    								if(jm.isConnectingToInternet(context)){
    									sendConfirmation("delivery", jobId, driverId,"onhold", DateFormat.format("yyyy-MM-dd  kk:mm:ss", System.currentTimeMillis()).toString());
    								}else{
    			        				AlertDialog.Builder builder = new AlertDialog.Builder(context);
    			    					builder.setTitle("Connection Error");
    			    					builder.setMessage("Unable to connect to Internet. Job will be updated automatically to the server later.")
    			    					       .setCancelable(false)
    			    					       .setPositiveButton("OK", new DialogInterface.OnClickListener() {
    			    					           public void onClick(DialogInterface dialog, int id) {
    			    					        	   jm.addJobToTempFile("delivery"+"|"+jobId+"|"+driverId+"|"+"onhold"+"|"+DateFormat.format("yyyy-MM-dd  kk:mm:ss", System.currentTimeMillis()).toString(), context);
    			    					        	   jm.setupJobUpdateAlarm(5, context);
    			    					        	   jm.removeJob(groupPos, childPos, manifestid, context);
    			    					        	   finish();
    			    					           }
    			    					       });
    			    					AlertDialog alert = builder.create();
    			    					alert.show();
    			        			}
    							}
    						  })
    						.setNegativeButton("No",new DialogInterface.OnClickListener() {
    							public void onClick(DialogInterface dialog,int id) {
    								
    							}
    						});
						AlertDialog alertDialog = alertDialogBuilder.create();
						alertDialog.show();
    				}
    			}
        		
        	}
        });
	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.delivery, menu);
		return true;
	}
	
	public void onActivityResult(int requestCode, int resultCode, Intent intent) {
		if(requestCode == SIGNATURE_REQUEST){
			//capture signature
			if(resultCode==RESULT_OK){
	        	   jm.removeJob(groupPos, childPos, manifestid, context);
	        	   setResult(RESULT_OK,intent);
	        	   finish();
			}
		}
	}
	
	/*Function to call Delivery AsyncTask*/
	public void sendConfirmation(String jobType, String jobId, String driverId, String status, String time){
		pdLoading = ProgressDialog.show(this, "", "Confirming Delivery...");
		new DeliveryAsyncTask().execute(jobType, jobId, driverId, status, time);
	}
	
	/*class to handle asynchronous update of delivery job status to userver
	 *Takes in 5 variable: jobType, jobId, driverId, status, time
	 *send request to: http://www.efxmarket.com/mobile/update_job.php
	 *request method used: HTTPPOST
	 **/
	private class DeliveryAsyncTask extends AsyncTask<String, Integer, Double>{
		String response = "";
		String param = "";
		@Override
		protected Double doInBackground(String... params) {
			// TODO Auto-generated method stub
			postData(params[0],params[1],params[2],params[3],params[4]);
			param = params[0]+"|"+params[1]+"|"+params[2]+"|"+params[3]+"|"+params[4];
			return null;
		}
 
		protected void onPostExecute(Double result){
			pdLoading.dismiss();
			if(response.equals("1")){
				AlertDialog.Builder builder = new AlertDialog.Builder(context);
				builder.setTitle("Delivery Confirmation.");
				builder.setMessage("Delivery confirmation successful!")
				       .setCancelable(false)
				       .setPositiveButton("OK", new DialogInterface.OnClickListener() {
				           public void onClick(DialogInterface dialog, int id) {
				                /*Call removeJob to remove job data from application and file*/
				        	   jm.removeJob(groupPos, childPos, manifestid, context);
				        	   //setResult(RESULT_OK,intent);
				        	   finish();
				           }
				       });
				AlertDialog alert = builder.create();
				alert.show();
			}
			else{
				AlertDialog.Builder builder = new AlertDialog.Builder(context);
				builder.setTitle("Delivery Confirmation.");
				builder.setMessage("Delivery confirmation fail! please try again.")
				       .setCancelable(false)
				       .setPositiveButton("OK", new DialogInterface.OnClickListener() {
				           public void onClick(DialogInterface dialog, int id) {
				        	   
				           }
				       });
				AlertDialog alert = builder.create();
				alert.show();
			}
		}


		protected void onCancelled (){
			pdLoading.dismiss();
			AlertDialog.Builder builder = new AlertDialog.Builder(context);
			builder.setTitle("Notice");
			builder.setMessage("Server is currently busy. Job will be updated automatically to the server later.")
			       .setCancelable(false)
			       .setPositiveButton("OK", new DialogInterface.OnClickListener() {
			           public void onClick(DialogInterface dialog, int id) {
			        	   jm.addJobToTempFile(param, context);
			        	   jm.setupJobUpdateAlarm(5, context);
			        	   jm.removeJob(groupPos, childPos, manifestid, context);
			        	   finish();
			           }
			       });
			AlertDialog alert = builder.create();
			alert.show();
		}
 
		public void postData(String jobType, String jobId, String driverId, String status, String time) {
			HttpParams httpParams = new BasicHttpParams();
			HttpConnectionParams.setConnectionTimeout(httpParams, 5000);
			HttpConnectionParams.setSoTimeout(httpParams, 5000);
			
			HttpClient httpclient = new DefaultHttpClient(httpParams);
            HttpPost httppost = new HttpPost("http://www.efxmarket.com/mobile/update_job.php");
            
			try {
				// Add data
				List<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>();
				nameValuePairs.add(new BasicNameValuePair("type",jobType));
				nameValuePairs.add(new BasicNameValuePair("jobId",jobId));
				nameValuePairs.add(new BasicNameValuePair("driverId",driverId));
				nameValuePairs.add(new BasicNameValuePair("status",status));
				nameValuePairs.add(new BasicNameValuePair("time",time));
				httppost.setEntity(new UrlEncodedFormEntity(nameValuePairs));
 
				// Execute HTTPPost Request
				ResponseHandler<String> responseHandler = new BasicResponseHandler();
				response = httpclient.execute(httppost, responseHandler);
				
			} catch (ConnectTimeoutException e){
                cancel(true);
			} catch (SocketTimeoutException e){
                cancel(true);
			} catch (ClientProtocolException e) {
				// TODO Auto-generated catch block
			} catch (IOException e) {
				// TODO Auto-generated catch block
			}
		}
 
	}

}
