package ss133a.mobile.camilus;

import android.app.IntentService;
import android.app.Notification;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.app.TaskStackBuilder;
import android.content.Context;
import android.content.Intent;
import android.support.v4.app.NotificationCompat;

public class BackgroundService extends IntentService{

	

	public BackgroundService() {
		super("BackgroundService");
		// TODO Auto-generated constructor stub
	}

	@Override
	protected void onHandleIntent(Intent intent) {
		// TODO Auto-generated method stub
		for(int i=0;i<10;i++){
			System.out.println("sq background: "+i);
			try {
				Thread.currentThread().sleep(1000);//sleep for 1000 ms(2000);
			} catch (InterruptedException e) {
				// TODO Auto-generated catch block
				System.out.println("sq background interrupted");
			}
			if(i==9){
				NotificationCompat.Builder mBuilder =
				        new NotificationCompat.Builder(this)
				        .setSmallIcon(R.drawable.ic_launcher)
				        .setContentTitle("My notification")
				        .setContentText("Hello World!")
				        .setAutoCancel(true);
				Intent resultIntent = new Intent(this, Login.class);
				TaskStackBuilder stackBuilder = TaskStackBuilder.create(this);
				stackBuilder.addParentStack(Login.class);
				stackBuilder.addNextIntent(resultIntent);
				PendingIntent resultPendingIntent =
				        stackBuilder.getPendingIntent(
				            0,
				            PendingIntent.FLAG_UPDATE_CURRENT
				        );
				mBuilder.setContentIntent(resultPendingIntent);
				
				NotificationManager mNotificationManager =
					    (NotificationManager) getSystemService(Context.NOTIFICATION_SERVICE);
					// mId allows you to update the notification later on.				
					mNotificationManager.notify(1, mBuilder.build());
			}
		}
		
	}

}
