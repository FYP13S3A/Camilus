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

import android.os.AsyncTask;
import android.os.Bundle;
import android.app.Activity;
import android.content.Intent;
import android.view.Menu;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ProgressBar;
import android.widget.Toast;

public class LoginActivity extends Activity implements OnClickListener{
	public final static String LOGIN_MESSAGE = "com.example.camilus.MESSAGE";
	private View loginView;
	private Button btnLogin;
	private EditText txtUser, txtPassword;
	private ProgressBar pbLogin;
	String response = "000";
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_login);
		btnLogin = (Button)findViewById(R.id.btnLogin);
		txtUser = (EditText)findViewById(R.id.txtUser);
		txtPassword = (EditText)findViewById(R.id.txtPassword);
		pbLogin=(ProgressBar)findViewById(R.id.pbLogin);
		pbLogin.setVisibility(View.GONE);
		loginView = this.findViewById(android.R.id.content);
		btnLogin.setOnClickListener(this);
	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.login, menu);
		return true;
	}

	@Override
	public void onClick(View v) {
		// TODO Auto-generated method stub
		if(v.getId()==R.id.btnLogin){
			if(txtUser.getText().toString().equals("")){
				Toast.makeText(getApplicationContext(), "Please enter username", Toast.LENGTH_SHORT).show();
				}else if(txtPassword.getText().toString().equals("")){
				Toast.makeText(getApplicationContext(), "Please enter password", Toast.LENGTH_SHORT).show();
			}else{
				pbLogin.setVisibility(View.VISIBLE);
				String loginDetails = "1:"+txtUser.getText().toString()+":"+txtPassword.getText().toString();
				//Toast.makeText(getApplicationContext(), loginDetails, Toast.LENGTH_SHORT).show();
				new MyAsyncTask().execute(loginDetails);
				/*if(response.equals("111")){
					Toast.makeText(getApplicationContext(), "Login successful!", Toast.LENGTH_LONG).show();
					login(this.findViewById(android.R.id.content));
				}else{
					Toast.makeText(getApplicationContext(), "Login fail!. Error code: "+response, Toast.LENGTH_LONG).show();
				}*/
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
			pbLogin.setVisibility(View.GONE);
			if(response.equals("101")){
				Toast.makeText(getApplicationContext(), "Login successful!", Toast.LENGTH_LONG).show();
				login(loginView);
			}else{
				Toast.makeText(getApplicationContext(), "Login fail!. Error code: "+response, Toast.LENGTH_LONG).show();
			}
			
		}
		protected void onProgressUpdate(Integer... progress){
			pbLogin.setProgress(progress[0]);
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
	
	public void login(View view) {
		Intent loginIntent = new Intent(this, MainActivity.class);
		String logindetails = txtUser.getText().toString();
		loginIntent.putExtra(LOGIN_MESSAGE, logindetails);
		startActivity(loginIntent);
		finish();
	}

}
