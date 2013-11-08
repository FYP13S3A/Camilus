package ss133a.mobile.camilus;

import java.io.IOException;
import java.net.SocketTimeoutException;
import java.util.ArrayList;
import java.util.Calendar;
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

import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.AsyncTask;
import android.os.Bundle;
import android.app.Activity;
import android.app.AlarmManager;
import android.app.AlertDialog;
import android.app.PendingIntent;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.view.Menu;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ProgressBar;
import android.widget.Toast;

public class Login extends Activity  implements OnClickListener{
	private Button btnLogin;
	private EditText txtUser, txtPassword;
	ProgressDialog pdLoading;
	public static JobsManager jobsmanager;
	Context c = this;
	String response = "000";
	
	public Login(){
		
	}
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_login);
		btnLogin = (Button)findViewById(R.id.btnLogin);
		txtUser = (EditText)findViewById(R.id.txtUser);
		txtPassword = (EditText)findViewById(R.id.txtPassword);
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
		
		/*Handles login button*/
		if(v.getId()==R.id.btnLogin){
			/*validation for empty user or password field*/
			if(txtUser.getText().toString().equals("")){
				Toast.makeText(getApplicationContext(), "Please enter username", Toast.LENGTH_SHORT).show();
				}else if(txtPassword.getText().toString().equals("")){
				Toast.makeText(getApplicationContext(), "Please enter password", Toast.LENGTH_SHORT).show();
			}else{
				if(isConnectingToInternet(getApplicationContext())){
					/*proceed to authenticate user with server*/
					pdLoading = ProgressDialog.show(this, "Please Wait...", "Connecting to server...");
					new LoginAsyncTask().execute(txtUser.getText().toString(),txtPassword.getText().toString());
				}else{
					AlertDialog.Builder builder = new AlertDialog.Builder(c);
					builder.setTitle("Connection Error");
					builder.setMessage("Unable to connect to Internet.")
					       .setCancelable(false)
					       .setPositiveButton("OK", new DialogInterface.OnClickListener() {
					           public void onClick(DialogInterface dialog, int id) {
					        	   
					           }
					       });
					AlertDialog alert = builder.create();
					alert.show();
				}
				
			}
		}
	}
	
	
	/*function to handle successful login.
	 *forward user to Main.class.
	 **/
	public void login() {
		Intent loginIntent = new Intent(this, Main.class);
		startActivity(loginIntent);
		finish(); //to prevent user from going back to login activity
	}
	
	/*function to check phone's connectivity.*/
	public boolean isConnectingToInternet(Context context){
        ConnectivityManager connectivity = (ConnectivityManager) context.getSystemService(Context.CONNECTIVITY_SERVICE);
          if (connectivity != null) 
          {
              NetworkInfo[] info = connectivity.getAllNetworkInfo();
              if (info != null) 
                  for (int i = 0; i < info.length; i++) 
                      if (info[i].getState() == NetworkInfo.State.CONNECTED)
                      {
                          return true;
                      }
 
          }
          return false;
    }

	
	/*class to handle asynchronous login authentication with server
	 *Takes in 2 variables: username and password
	 *send request to: http://www.efxmarket.com/mobile/auth.php
	 *request method used: HTTPPOST
	 **/
	private class LoginAsyncTask extends AsyncTask<String, Integer, Double>{
		String username = "";
		@Override
		protected Double doInBackground(String... params) {
			// TODO Auto-generated method stub
			postData(params[0],params[1]);
			username = params[0];
			return null;
		}
 
		protected void onPostExecute(Double result){
			/*Handles successful authentication
			 *Creates a new object JobsManager to handle jobs for deliveryman.
			 *Check if job file for deliveryman exist.
			 * - True:call login function
			 * - False: Download job file*/
			if(response.equals("302")){
				jobsmanager = new JobsManager(username);
				jobsmanager.addHeaderChildren();
				if(jobsmanager.checkFileExist(c)==false){
					jobsmanager.downloadFile(Login.this, c);
				}
				else{
					String filedata = jobsmanager.readFile(c).trim();
					if(filedata.trim().equals("404")){ /*404 means no job found for deliveryman*/
						jobsmanager.removeFile(c);
					}else{
						/*sort data to respective containers*/
						jobsmanager.sortJobs(filedata);
						jobsmanager.prepareJobContainer();
					}
					pdLoading.dismiss();
					login();
				}
			}else{
				/*handles fail authentication*/
				pdLoading.dismiss();
				Toast.makeText(getApplicationContext(), "Login fail!. Error code: "+response, Toast.LENGTH_LONG).show();
			}
			
		}
		
		protected void onCancelled (){
			pdLoading.dismiss();
			AlertDialog.Builder builder = new AlertDialog.Builder(c);
			builder.setTitle("Login Error");
			builder.setMessage("Server is currently busy. Please try again later.")
			       .setCancelable(false)
			       .setPositiveButton("OK", new DialogInterface.OnClickListener() {
			           public void onClick(DialogInterface dialog, int id) {
			        	   
			           }
			       });
			AlertDialog alert = builder.create();
			alert.show();
		}
 
		protected void postData(String user, String password) {
			/*set timeout*/
			HttpParams httpParams = new BasicHttpParams();
			HttpConnectionParams.setConnectionTimeout(httpParams, 5000);
			HttpConnectionParams.setSoTimeout(httpParams, 5000);
			
			HttpClient httpclient = new DefaultHttpClient(httpParams);
			HttpPost httppost = new HttpPost("http://www.efxmarket.com/mobile/auth.php");
 
			try {
				/*adds login data*/
				List<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>();
				nameValuePairs.add(new BasicNameValuePair("mUser", user));
				nameValuePairs.add(new BasicNameValuePair("mPass", password));
				httppost.setEntity(new UrlEncodedFormEntity(nameValuePairs));
 
				/*executes HTTPPOST request and retrieve server's response*/
				ResponseHandler<String> responseHandler = new BasicResponseHandler();
				response = httpclient.execute(httppost, responseHandler);
				
			} catch (ConnectTimeoutException e){
                cancel(true);
			} catch (SocketTimeoutException e){
                cancel(true);
			} catch (ClientProtocolException e) {
				// TODO Auto-generated catch block
			}  catch (IOException e) {
				// TODO Auto-generated catch block
			}
		}
 
	}
}
