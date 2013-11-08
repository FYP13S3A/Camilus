package ss133a.mobile.camilus;

import java.io.ByteArrayOutputStream;
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
import android.gesture.GestureOverlayView;
import android.graphics.Bitmap;
import android.text.format.DateFormat;
import android.util.Base64;
import android.view.Menu;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;

public class Signature extends Activity {
	private GestureOverlayView govSignaturePad;
	private Button btnConfirm, btnClear;
	private ProgressDialog pdLoading;
	private Context context = this;
	String manifestid, jobid, driverid;
	Intent intent;
	JobsManager jm;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_signature);
		
		govSignaturePad = (GestureOverlayView) findViewById(R.id.signaturePad);
		btnConfirm = (Button)findViewById(R.id.btnConfirm);
		btnClear = (Button)findViewById(R.id.btnClear);
		intent = getIntent();
		jm = Main.jm;
		
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
    			
    			alertDialogBuilder.setTitle("Delivery Confirmation");
    			alertDialogBuilder
    				.setMessage("Proceed to confirm delivery?")
    				.setCancelable(false)
    				.setPositiveButton("Yes",new DialogInterface.OnClickListener() {
    					public void onClick(DialogInterface dialog,int id) {
    						final String image = getImageBase64();
    						if(jm.isConnectingToInternet(context)){
    							sendConfirmation("delivery", jobid, driverid,manifestid, image, "complete", DateFormat.format("yyyy-MM-dd  kk:mm:ss", System.currentTimeMillis()).toString());
    						}else{
		        				AlertDialog.Builder builder = new AlertDialog.Builder(context);
		    					builder.setTitle("Connection Error");
		    					builder.setMessage("Unable to connect to Internet. Job will be updated automatically to the server later.")
		    					       .setCancelable(false)
		    					       .setPositiveButton("OK", new DialogInterface.OnClickListener() {
		    					           public void onClick(DialogInterface dialog, int id) {
		    					        	   jm.addJobToTempFile("delivery"+"|"+jobid+"|"+driverid+"|"+"complete"+"|"+DateFormat.format("yyyy-MM-dd  kk:mm:ss", System.currentTimeMillis()).toString()+"|"+manifestid+"|"+image, context);
		    					        	   jm.setupJobUpdateAlarm(5, context);
		    					        	   setResult(RESULT_OK,intent);
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
        });
	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.signature, menu);
		return true;
	}
	
	public void sendConfirmation(String jobType, String jobId, String driverId, String manifestId, String imageString, String status, String time){
		pdLoading = ProgressDialog.show(this, "", "Confirming Delivery...");
		new DeliveryAsyncTask().execute(jobType, jobId, driverId, manifestId, imageString, status, time);
	}
	
	/*Function to grab signature from signature pad and saves to a Base64 String*/
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
		String param = "";
		@Override
		protected Double doInBackground(String... params) {
			// TODO Auto-generated method stub
			postData(params[0],params[1],params[2],params[3],params[4],params[5],params[6]);
			param = params[0]+"|"+params[1]+"|"+params[2]+"|"+params[5]+"|"+params[6]+"|"+params[3]+"|"+params[4];
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
			        	   setResult(RESULT_OK,intent);
			        	   finish();
			           }
			       });
			AlertDialog alert = builder.create();
			alert.show();
		}
 
		public void postData(String jobType, String jobId, String driverId, String manifestId, String imageString, String status, String time) {
			HttpParams httpParams = new BasicHttpParams();
			HttpConnectionParams.setConnectionTimeout(httpParams, 5000);
			HttpConnectionParams.setSoTimeout(httpParams, 5000);
			
			HttpClient httpclient = new DefaultHttpClient(httpParams);
            HttpPost httppost = new HttpPost("http://www.camilus.org/mobile/update_job.php");
            
			try {
				// Add data
				List<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>();
				nameValuePairs.add(new BasicNameValuePair("type",jobType));
				nameValuePairs.add(new BasicNameValuePair("jobId",jobId));
				nameValuePairs.add(new BasicNameValuePair("driverId",driverId));
				nameValuePairs.add(new BasicNameValuePair("image64",imageString));
				nameValuePairs.add(new BasicNameValuePair("imageName",manifestId));
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
