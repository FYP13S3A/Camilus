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
import android.app.ProgressDialog;
import android.content.Context;
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
	Context c;
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
		
		c = getApplicationContext();
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
				/*proceed to authenticate user with server*/
				pdLoading = ProgressDialog.show(this, "Please Wait...", "Connecting to server...");
				new LoginAsyncTask().execute(txtUser.getText().toString(),txtPassword.getText().toString());
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
				if(jobsmanager.checkFileExist()==false){
					jobsmanager.downloadFile(Login.this);
				}
				else{
					String filedata = jobsmanager.readFile().trim();
					if(filedata.trim().equals("404")){ /*404 means no job found for deliveryman*/
						jobsmanager.removeFile();
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
		
		protected void onProgressUpdate(Integer... progress){
			//pbLogin.setProgress(progress[0]);
		}
 
		protected void postData(String user, String password) {
			HttpClient httpclient = new DefaultHttpClient();
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
				
			} catch (ClientProtocolException e) {
				// TODO Auto-generated catch block
			} catch (IOException e) {
				// TODO Auto-generated catch block
			}
		}
 
	}
}
