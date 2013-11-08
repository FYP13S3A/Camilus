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
import android.widget.TextView;

public class Transfer extends Activity {
	private TextView txtTRManifestId, txtTRDestination;
	private Button btnTRUpdate;
	private Context context = this;
	private ProgressDialog pdLoading;
	Intent intent;
	String manifestid,jobId,driverId;
	int groupPos,childPos;
	JobsManager jm;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_transfer);
		
		txtTRManifestId = (TextView)findViewById(R.id.txtTRJobId);
		txtTRDestination = (TextView)findViewById(R.id.txtTRDestination);
		btnTRUpdate = (Button)findViewById(R.id.btnTRUpdate);
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
		txtTRManifestId.setText(jobId);
		txtTRDestination.setText(jobdata[1]);
		
		btnTRUpdate.setOnClickListener(new OnClickListener(){
        	public void onClick(View v){
        		AlertDialog.Builder alertDialogBuilder = new AlertDialog.Builder(context);
    			
    			alertDialogBuilder.setTitle("Transfer Confirmation");
    			alertDialogBuilder
    				.setMessage("Proceed to confirm transfer?")
    				.setCancelable(false)
    				.setPositiveButton("Yes",new DialogInterface.OnClickListener() {
    					public void onClick(DialogInterface dialog,int id) {
    						sendConfirmation("transfer",jobId, driverId,"complete", DateFormat.format("yyyy-MM-dd  kk:mm:ss", System.currentTimeMillis()).toString());
    					}
    				  })
    				.setNegativeButton("No",new DialogInterface.OnClickListener() {
    					public void onClick(DialogInterface dialog,int id) {
    						
    					}
    				});
				AlertDialog alertDialog = alertDialogBuilder.create();
				alertDialog.show();
        		
        	}
        });
	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.transfer, menu);
		return true;
	}
	
	private class TransferAsyncTask extends AsyncTask<String, Integer, Double>{
		String response = "";
		@Override
		protected Double doInBackground(String... params) {
			// TODO Auto-generated method stub
			postData(params[0],params[1],params[2],params[3],params[4]);
			return null;
		}
 
		protected void onPostExecute(Double result){
			pdLoading.dismiss();
			if(response.equals("1")){
				AlertDialog.Builder builder = new AlertDialog.Builder(context);
				builder.setTitle("Transfer Confirmation.");
				builder.setMessage("Transfer confirmation successful!")
				       .setCancelable(false)
				       .setPositiveButton("OK", new DialogInterface.OnClickListener() {
				           public void onClick(DialogInterface dialog, int id) {
				        	   jm.removeJob(groupPos, childPos, manifestid, context);
				        	   setResult(RESULT_OK,intent);
				        	   finish();
				           }
				       });
				AlertDialog alert = builder.create();
				alert.show();
			}
			else{
				AlertDialog.Builder builder = new AlertDialog.Builder(context);
				builder.setTitle("Trasnfer Confirmation.");
				builder.setMessage("Transfer confirmation fail! please try again.")
				       .setCancelable(false)
				       .setPositiveButton("OK", new DialogInterface.OnClickListener() {
				           public void onClick(DialogInterface dialog, int id) {
				                
				           }
				       });
				AlertDialog alert = builder.create();
				alert.show();
			}
		}
		protected void onProgressUpdate(Integer... progress){
		}
 
		public void postData(String jobType, String jobId, String driverId, String status, String time) {
			HttpClient httpclient = new DefaultHttpClient();
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
				
			} catch (ClientProtocolException e) {
				// TODO Auto-generated catch block
			} catch (IOException e) {
				// TODO Auto-generated catch block
			}
		}
 
	}
	
	public void sendConfirmation(String jobType, String jobId, String driverId, String status, String time){
		pdLoading = ProgressDialog.show(this, "", "Confirming Transfer...");
		new TransferAsyncTask().execute(jobType,jobId, driverId, status, time);
	}

}
