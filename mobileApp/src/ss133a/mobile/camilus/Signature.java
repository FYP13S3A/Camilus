package ss133a.mobile.camilus;

import java.io.ByteArrayOutputStream;
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
import android.gesture.GestureOverlayView;
import android.graphics.Bitmap;
import android.text.format.DateFormat;
import android.util.Base64;
import android.view.Menu;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.Toast;

public class Signature extends Activity {
	private GestureOverlayView govSignaturePad;
	private Button btnConfirm, btnClear;
	private ProgressDialog pdLoading;
	private Context context = this;
	String manifestid, jobid, driverid;
	Intent intent;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_signature);
		
		govSignaturePad = (GestureOverlayView) findViewById(R.id.signaturePad);
		btnConfirm = (Button)findViewById(R.id.btnConfirm);
		btnClear = (Button)findViewById(R.id.btnClear);
		intent = getIntent();
		
		jobid = intent.getStringExtra(Delivery.DELIVERY_JOBID);
		manifestid = intent.getStringExtra(Delivery.DELIVERY_MANIFESTID);
		driverid = intent.getStringExtra(Delivery.DELIVERY_DRIVERID);
		
		
		btnClear.setOnClickListener(new OnClickListener(){
        	public void onClick(View v){
    			govSignaturePad.cancelClearAnimation();
    			govSignaturePad.clear(true);
        	}
        });
		
		btnConfirm.setOnClickListener(new OnClickListener(){
        	public void onClick(View v){
        		AlertDialog.Builder alertDialogBuilder = new AlertDialog.Builder(context);
    			
    			// set title
    			alertDialogBuilder.setTitle("Delivery Confirmation");
    			// set dialog message
    			alertDialogBuilder
    				.setMessage("Proceed to confirm delivery?")
    				.setCancelable(false)
    				.setPositiveButton("Yes",new DialogInterface.OnClickListener() {
    					public void onClick(DialogInterface dialog,int id) {
    						//Toast.makeText(context, "send confirmation testing", Toast.LENGTH_LONG).show();
    						String image = getImageBase64();
    						sendConfirmation(jobid, driverid,manifestid, image, "complete", DateFormat.format("yyyy-MM-dd  kk:mm:ss", System.currentTimeMillis()).toString());
    					}
    				  })
    				.setNegativeButton("No",new DialogInterface.OnClickListener() {
    					public void onClick(DialogInterface dialog,int id) {
    						// if this button is clicked, just close
    						// the dialog box and do nothing
    						dialog.cancel();
    					}
    				});
     
				// create alert dialog
				AlertDialog alertDialog = alertDialogBuilder.create();
 
				// show it
				alertDialog.show();
        	}
        });
	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.signature, menu);
		return true;
	}
	
	public void sendConfirmation(String jobId, String driverId, String manifestId, String imageString, String status, String time){
		pdLoading = ProgressDialog.show(this, "", "Confirming Delivery...");
		new DeliveryAsyncTask().execute(jobId, driverId, manifestId, imageString, status, time);
	}
	
	public String getImageBase64(){
		govSignaturePad.setDrawingCacheEnabled(true);
		Bitmap bm = Bitmap.createBitmap(govSignaturePad.getDrawingCache());
		ByteArrayOutputStream imageStream = new ByteArrayOutputStream();
		bm.compress(Bitmap.CompressFormat.PNG, 90, imageStream);
		byte [] imageByte = imageStream.toByteArray();
        String imageString = Base64.encodeToString(imageByte,Base64.DEFAULT);
		return imageString;
	}
	
	private class DeliveryAsyncTask extends AsyncTask<String, Integer, Double>{
		String response = "";
		@Override
		protected Double doInBackground(String... params) {
			// TODO Auto-generated method stub
			postData(params[0],params[1],params[2],params[3],params[4],params[5]);
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
				        	   //jm.removeJob(groupPos, childPos);
				        	   setResult(RESULT_OK,intent);
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
 
		public void postData(String jobId, String driverId, String manifestId, String imageString, String status, String time) {
			// Create a new HttpClient and Post Header
			HttpClient httpclient = new DefaultHttpClient();
            HttpPost httppost = new HttpPost("http://www.efxmarket.com/mobile/update_job.php");
            
			try {
				// Add your data
				List<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>();
				nameValuePairs.add(new BasicNameValuePair("jobId",jobId));
				nameValuePairs.add(new BasicNameValuePair("driverId",driverId));
				nameValuePairs.add(new BasicNameValuePair("image64",imageString));
				nameValuePairs.add(new BasicNameValuePair("imageName",manifestId));
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
