package com.example.camilus;

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
import android.util.Base64;
import android.view.Menu;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.Toast;

public class SignatureActivity extends Activity implements OnClickListener{
	String response = "000";
	private Context context = this;
	private GestureOverlayView govSignaturePad;
	private Button btnConfirm, btnClear;
	private ProgressDialog pdLoading;
	Intent i;
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_signature);
		govSignaturePad = (GestureOverlayView) findViewById(R.id.signaturePad);
		btnConfirm = (Button)findViewById(R.id.btnConfirm);
		btnConfirm.setOnClickListener(this);
		btnClear = (Button)findViewById(R.id.btnClear);
		btnClear.setOnClickListener(this);
		i = getIntent();
	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.signature, menu);
		return true;
	}
	
	public void sendConfirmation(){
		govSignaturePad.setDrawingCacheEnabled(true);
		Bitmap bm = Bitmap.createBitmap(govSignaturePad.getDrawingCache());
		ByteArrayOutputStream imageStream = new ByteArrayOutputStream();
		bm.compress(Bitmap.CompressFormat.PNG, 90, imageStream);
		byte [] imageByte = imageStream.toByteArray();
        String imageString = "3:"+Base64.encodeToString(imageByte,Base64.DEFAULT)+":"+i.getStringExtra("parcelid");
		pdLoading = ProgressDialog.show(this, "", "Updating Delivery Status...");
        new MyAsyncTask().execute(imageString);
	}

	@Override
	public void onClick(View v) {
		// TODO Auto-generated method stub
		if(v.getId()==R.id.btnConfirm){
			//alert box
			AlertDialog.Builder alertDialogBuilder = new AlertDialog.Builder(context);
			
			// set title
			alertDialogBuilder.setTitle("Delivery Confirmation");
			// set dialog message
			alertDialogBuilder
				.setMessage("Proceed to confirm delivery status?")
				.setCancelable(false)
				.setPositiveButton("Yes",new DialogInterface.OnClickListener() {
					public void onClick(DialogInterface dialog,int id) {
						sendConfirmation();
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
		}else if(v.getId()==R.id.btnClear){
			govSignaturePad.cancelClearAnimation();
			govSignaturePad.clear(true);
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
			if(responseArray[0].equals("301")){
				AlertDialog.Builder builder = new AlertDialog.Builder(context);
				builder.setTitle("Delivery Confirmation.");
				builder.setMessage("Update successful!")
				       .setCancelable(false)
				       .setPositiveButton("OK", new DialogInterface.OnClickListener() {
				           public void onClick(DialogInterface dialog, int id) {
				                //do things
				        	   i.putExtra("result", "301");
				        	   setResult(RESULT_OK,i);
				        	   finish();
				           }
				       });
				AlertDialog alert = builder.create();
				alert.show();
			}
			else{
				AlertDialog.Builder builder = new AlertDialog.Builder(context);
				builder.setTitle("Delivery Confirmation.");
				builder.setMessage("Update fail! please try again.")
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
 
		public void postData(String valueIWantToSend) {
			// Create a new HttpClient and Post Header
			HttpClient httpclient = new DefaultHttpClient();
            HttpPost httppost = new HttpPost("http://www.efxmarket.com/handler.php");
            
           
 
			try {
				// Add your data
				List<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>();
				 nameValuePairs.add(new BasicNameValuePair("handlerData",valueIWantToSend));
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
