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
	public final static String LOGIN_USER = "ss133a.mobile.camilus.LOGIN_USER";
	public final static String JOBS_MANAGER = "ss133a.mobile.camilus.JOBS_MANAGER";
	private View loginView;
	private Button btnLogin;
	private EditText txtUser, txtPassword;
	ProgressBar pbLogin;
	String response = "000";
	public static JobsManager jobsmanager;
	Context c;
	
	public Login(){
		
	}
	
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
		//check if onclick is login button
		if(v.getId()==R.id.btnLogin){
			//validation for empty user or password field
			if(txtUser.getText().toString().equals("")){
				Toast.makeText(getApplicationContext(), "Please enter username", Toast.LENGTH_SHORT).show();
				}else if(txtPassword.getText().toString().equals("")){
				Toast.makeText(getApplicationContext(), "Please enter password", Toast.LENGTH_SHORT).show();
			}else{
				//proceed to authenticate user with server
				pbLogin.setVisibility(View.VISIBLE);
				System.out.println("sq: "+"1. executing loginasynctask");
				new LoginAsyncTask().execute(txtUser.getText().toString(),txtPassword.getText().toString());
			}
		}
	}
	
	//function to handle successful login
	public void login() {
		System.out.println("sq: "+"10. inside login()");
		Intent loginIntent = new Intent(this, Main.class);
		String logindetails = txtUser.getText().toString();
		loginIntent.putExtra(LOGIN_USER, logindetails);
		startActivity(loginIntent);
		finish(); //to prevent user from going back to login activity
	}

	//class to perform async login authentication with server
	private class LoginAsyncTask extends AsyncTask<String, Integer, Double>{
		String username = "";
		@Override
		protected Double doInBackground(String... params) {
			// TODO Auto-generated method stub
			postData(params[0],params[1]);
			username = params[0];
			return null;
		}
 
		//function to execute after server's authentication response
		protected void onPostExecute(Double result){
			//handle successful authentication
			if(response.equals("302")){
				System.out.println("sq: "+"2. login authenticataion success!");
				jobsmanager = new JobsManager();
				jobsmanager.addHeaderChildren();
				System.out.println("sq: "+"3. checking job file");
				if(jobsmanager.checkFileExist(username)==false){
					System.out.println("sq: "+"4. download file");
					jobsmanager.downloadFile(username, Login.this);
				}
				else{
					pbLogin.setVisibility(View.GONE);
					login();
				}
			}else{
				//handle fail authentication
				pbLogin.setVisibility(View.GONE);
				Toast.makeText(getApplicationContext(), "Login fail!. Error code: "+response, Toast.LENGTH_LONG).show();
			}
			
		}
		
		protected void onProgressUpdate(Integer... progress){
			pbLogin.setProgress(progress[0]);
		}
 
		//function to handle data post to server
		protected void postData(String user, String password) {
			HttpClient httpclient = new DefaultHttpClient();
			HttpPost httppost = new HttpPost("http://www.efxmarket.com/mobile/auth.php");
 
			try {
				//add login data
				List<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>();
				nameValuePairs.add(new BasicNameValuePair("mUser", user));
				nameValuePairs.add(new BasicNameValuePair("mPass", password));
				httppost.setEntity(new UrlEncodedFormEntity(nameValuePairs));
 
				//execute httppost request and retrieve server's response
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
