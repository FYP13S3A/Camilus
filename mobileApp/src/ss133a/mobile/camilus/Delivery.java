package ss133a.mobile.camilus;


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
import android.widget.Toast;

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
        		if(rdgDeliveryStatus.getCheckedRadioButtonId()==-1){
    				AlertDialog.Builder builder = new AlertDialog.Builder(v.getContext());
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
    			}else{
    				int option = rdgDeliveryStatus.getCheckedRadioButtonId();
    				if(option==R.id.rdACollected){
    	        		Intent sigIntent = new Intent(v.getContext(),Signature.class);
    	        		sigIntent.putExtra(DELIVERY_JOBID, jobId);
    	        		sigIntent.putExtra(DELIVERY_MANIFESTID, manifestid);
    	        		sigIntent.putExtra(DELIVERY_DRIVERID, driverId);
    	        		startActivityForResult(sigIntent, SIGNATURE_REQUEST);
    				}else{
    					AlertDialog.Builder alertDialogBuilder = new AlertDialog.Builder(v.getContext());
    					alertDialogBuilder.setTitle("Delivery Confirmation");
    					alertDialogBuilder
    						.setMessage("You have selected 'Not At Home'. Proceed to confirm delivery status?")
    						.setCancelable(false)
    						.setPositiveButton("Yes",new DialogInterface.OnClickListener() {
    							public void onClick(DialogInterface dialog,int id) {
    								sendConfirmation(jobId, driverId,"on-hold", DateFormat.format("yyyy-MM-dd  kk:mm:ss", System.currentTimeMillis()).toString());
    							}
    						  })
    						.setNegativeButton("No",new DialogInterface.OnClickListener() {
    							public void onClick(DialogInterface dialog,int id) {
    								dialog.cancel();
    							}
    						});
						// create alert dialog
						AlertDialog alertDialog = alertDialogBuilder.create();
		 
						// show it
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
	        	   jm.removeJob(groupPos, childPos, manifestid);
	        	   finish();
			}
		}
	}
	
	public void sendConfirmation(String jobId, String driverId, String status, String time){
		pdLoading = ProgressDialog.show(this, "", "Confirming Delivery...");
		new DeliveryAsyncTask().execute(jobId, driverId, status, time);
	}
	private class DeliveryAsyncTask extends AsyncTask<String, Integer, Double>{
		String response = "";
		@Override
		protected Double doInBackground(String... params) {
			// TODO Auto-generated method stub
			postData(params[0],params[1],params[2],params[3]);
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
				                //do things
				        	   jm.removeJob(groupPos, childPos, manifestid);
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
				                //do things
				        	   dialog.cancel();
				           }
				       });
				AlertDialog alert = builder.create();
				alert.show();
			}
		}
		protected void onProgressUpdate(Integer... progress){
		}
 
		public void postData(String jobId, String driverId, String status, String time) {
			// Create a new HttpClient and Post Header
			HttpClient httpclient = new DefaultHttpClient();
            HttpPost httppost = new HttpPost("http://www.efxmarket.com/mobile/update_job.php");
            
			try {
				// Add your data
				List<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>();
				nameValuePairs.add(new BasicNameValuePair("jobId",jobId));
				nameValuePairs.add(new BasicNameValuePair("driverId",driverId));
				nameValuePairs.add(new BasicNameValuePair("status",status));
				nameValuePairs.add(new BasicNameValuePair("time",time));
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

}
