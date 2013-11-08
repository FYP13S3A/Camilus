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
	
	 
	@Override
	public void onReceive(Context context, Intent intent) {
		this.context = context;
		Intent i = intent;
		String data = i.getStringExtra("data");
		System.out.println("sqsqsq: "+data);
		if(isConnectingToInternet(context)){
			
			Uri alarmSound = RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION);
			NotificationCompat.Builder mBuilder =
			        new NotificationCompat.Builder(context)
			        .setSmallIcon(R.drawable.ic_launcher)
			        .setContentTitle("Update Notice")
			        .setContentText("starting.")
			        .setAutoCancel(true)
			        .setSound(alarmSound);
			NotificationManager mNotificationManager =
				    (NotificationManager) context.getSystemService(Context.NOTIFICATION_SERVICE);
				// mId allows you to update the notification later on.				
			mNotificationManager.notify(1, mBuilder.build());
			
			new UpdateJobAsyncTask().execute(data);
		}else{
			setupJobUpdateAlarm(10, context, data);
		}
	}
	
	public void setupJobUpdateAlarm(int seconds, Context context, String data) {
		AlarmManager alarmManager = (AlarmManager) context.getSystemService(android.content.Context.ALARM_SERVICE);
		Intent intent = new Intent(context, OnAlarmReceive.class);
		intent.putExtra("data", data);
		PendingIntent pendingIntent = PendingIntent.getBroadcast(
		   context, 0, intent,
		   PendingIntent.FLAG_UPDATE_CURRENT);
		 
		 
		// Getting current time and add the seconds in it
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
		String param = "";
		@Override
		protected Double doInBackground(String... params) {
			// TODO Auto-generated method stub
			postData(params[0]);
			param = params[0];
			return null;
		}
 
		protected void onPostExecute(Double result){
			
			if(response.equals("1")){
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
			}
			else{
				Uri alarmSound = RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION);
				NotificationCompat.Builder mBuilder =
				        new NotificationCompat.Builder(context)
				        .setSmallIcon(R.drawable.ic_launcher)
				        .setContentTitle("Update Notice")
				        .setContentText("error. "+response)
				        .setAutoCancel(true)
				        .setSound(alarmSound);
				NotificationManager mNotificationManager =
					    (NotificationManager) context.getSystemService(Context.NOTIFICATION_SERVICE);
					// mId allows you to update the notification later on.				
				mNotificationManager.notify(1, mBuilder.build());
			}
		}


		protected void onCancelled (){
			setupJobUpdateAlarm(10, context, param);
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
