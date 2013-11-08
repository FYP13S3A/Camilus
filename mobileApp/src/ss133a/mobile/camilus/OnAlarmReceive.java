package ss133a.mobile.camilus;

import java.io.BufferedReader;
import java.io.File;
import java.io.FileReader;
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

import android.app.AlarmManager;
import android.app.AlertDialog;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.app.TaskStackBuilder;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.media.RingtoneManager;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.net.Uri;
import android.os.AsyncTask;
import android.support.v4.app.NotificationCompat;

public class OnAlarmReceive extends BroadcastReceiver {
	Context context;
	String driver;
	 
	@Override
	public void onReceive(Context context, Intent intent) {
		System.out.println("sqsq: receive alarm.");
		this.context = context;
		Intent i = intent;
		driver = i.getStringExtra("driver");
		if(isConnectingToInternet(context)){
			System.out.println("sqsq: internet connection check success");
			/*Starts to perform job update if there is Internet connectivity*/
			if(checkFileExist(context, driver)){
				System.out.println("sqsq: file found. Proceed");
				/*Only read job data if the file exist. To prevent IOException*/
				String data = readJobFromTempFile(context, driver).trim();
				if(!data.isEmpty()){
					System.out.println("sqsq: execute async task");
					/*Only perform job update if temp_file has jobs inside*/
					data = data.substring(0,data.length()-2);		
					new UpdateJobAsyncTask().execute(data);
				}else{
					System.out.println("sqsq: no job found");
				}
			}else{
				System.out.println("sqsq: no file found");
			}
		}else{
			System.out.println("sqsq: internet connection check fail, reassigning alarm");
			/*Reassign an AlarmManager to perform job update at a later time if there is no Internet connectivity*/
			setupJobUpdateAlarm(10, context, driver);
		}
	}
	
	public boolean checkFileExist(Context c, String driver){

		String filePath = c.getFilesDir()+File.separator+driver+"_temp.txt";
		File file = new File(filePath);
		return file.exists();
	}
	
	public String readJobFromTempFile(Context c, String driver){
		String filedata = "";
		String filePath = c.getFilesDir()+File.separator+driver+"_temp.txt";
		File file = new File(filePath);
		
		StringBuilder text = new StringBuilder();
		try {
		    BufferedReader br = new BufferedReader(new FileReader(file));
		    String line;
		    while ((line = br.readLine()) != null) {
		        text.append(line);
		        text.append('\n');
		    }
		}
		catch (IOException e) {
		   
		}
		filedata = text.toString();
		return filedata;
	}
	
	public void removeFile(Context c, String driver){
		String filePath = c.getFilesDir()+File.separator+driver+"_temp.txt";
		//delete file
		File file = new File(filePath);
		file.delete();
	}
	
	/*Function to setup an AlarmManager to perform job status update independently from application*/
	public void setupJobUpdateAlarm(int seconds, Context context, String driver) {
		// Creates an AlarmManager which calls OnAlarmReceive.class to handle job update
		AlarmManager alarmManager = (AlarmManager) context.getSystemService(android.content.Context.ALARM_SERVICE);
		Intent intent = new Intent(context, OnAlarmReceive.class);
		intent.putExtra("driver", driver);
		PendingIntent pendingIntent = PendingIntent.getBroadcast(
		   context, 168, intent,
		   PendingIntent.FLAG_CANCEL_CURRENT);
		 
		// Sets elapsed time for alarm to trigger
		Calendar cal = Calendar.getInstance();
		cal.add(Calendar.SECOND, seconds);
		 
		alarmManager.set(AlarmManager.RTC_WAKEUP, cal.getTimeInMillis(), pendingIntent);
	}
	
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
	
	private class UpdateJobAsyncTask extends AsyncTask<String, Integer, Double>{
		String response = "";
		@Override
		protected Double doInBackground(String... params) {
			// TODO Auto-generated method stub
			postData(params[0]);
			return null;
		}
 
		protected void onPostExecute(Double result){
			
			if(response.equals("1")){
				System.out.println("sqsq: job update success");
				Uri alarmSound = RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION);
				NotificationCompat.Builder mBuilder =
				        new NotificationCompat.Builder(context)
				        .setSmallIcon(R.drawable.ic_launcher)
				        .setContentTitle("Update Notice")
				        .setContentText("Jobs updated to server.")
				        .setAutoCancel(true)
				        .setSound(alarmSound);
				NotificationManager mNotificationManager =
					    (NotificationManager) context.getSystemService(Context.NOTIFICATION_SERVICE);
					// mId allows you to update the notification later on.				
				mNotificationManager.notify(1, mBuilder.build());
				removeFile(context, driver);
			}
			else{
				System.out.println("sqsq: job update fail");
				Uri alarmSound = RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION);
				NotificationCompat.Builder mBuilder =
				        new NotificationCompat.Builder(context)
				        .setSmallIcon(R.drawable.ic_launcher)
				        .setContentTitle("Update Notice")
				        .setContentText("Update fail. Please contact IT dept for assistance.")
				        .setAutoCancel(true)
				        .setSound(alarmSound);
				NotificationManager mNotificationManager =
					    (NotificationManager) context.getSystemService(Context.NOTIFICATION_SERVICE);
					// mId allows you to update the notification later on.				
				mNotificationManager.notify(1, mBuilder.build());
			}
		}


		protected void onCancelled (){
			setupJobUpdateAlarm(10, context, driver);
		}
 
		public void postData(String data) {
			HttpParams httpParams = new BasicHttpParams();
			HttpConnectionParams.setConnectionTimeout(httpParams, 10000);
			HttpConnectionParams.setSoTimeout(httpParams, 10000);
			
			HttpClient httpclient = new DefaultHttpClient(httpParams);
            HttpPost httppost = new HttpPost("http://www.efxmarket.com/mobile/update_job.php");
            
			try {
				// Add data
				List<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>();
				nameValuePairs.add(new BasicNameValuePair("mass","yes"));
				nameValuePairs.add(new BasicNameValuePair("massdata",data));
				httppost.setEntity(new UrlEncodedFormEntity(nameValuePairs));
 
				// Execute HTTP Post Request
				ResponseHandler<String> responseHandler = new BasicResponseHandler();
				response = httpclient.execute(httppost, responseHandler);
				
			}catch (ConnectTimeoutException e){
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
