package ss133a.mobile.camilus;

import android.os.Bundle;
import android.app.Activity;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.view.Menu;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

public class Delivery extends Activity {
	public final static String DELIVERY_JOBID = "ss133a.mobile.camilus.DELIVERY_JOBID";
	public final static String DELIVERY_MANIFESTID = "ss133a.mobile.camilus.DELIVERY_MANIFESTID";
	public final static String DELIVERY_DRIVERID = "ss133a.mobile.camilus.DELIVERY_DRIVERID";
	public static final int SIGNATURE_REQUEST = 1;
	private TextView txtDSName,txtDRName,txtDAddress,txtDContent;
	private Button btnDUpdate;
	Intent intent;
	String manifestid,jobId,driverId;
	int groupPos,childPos;
	JobsManager jm;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_delivery);
		
		txtDSName = (TextView)findViewById(R.id.txtDSName);
		txtDRName = (TextView)findViewById(R.id.txtDRName);
		txtDAddress = (TextView)findViewById(R.id.txtDAddress);
		txtDContent = (TextView)findViewById(R.id.txtDContent);
		btnDUpdate = (Button)findViewById(R.id.btnDUpdate);
		jm = Main.jm;
		
		intent = getIntent();
		String job = intent.getStringExtra(Jobs.JOB_DATA);
		manifestid = intent.getStringExtra(Jobs.JOB_MANIFESTID);
		groupPos = intent.getIntExtra(Jobs.JOB_GROUP_POSN, 0);
		childPos = intent.getIntExtra(Jobs.JOB_CHILD_POSN, 0);
		String[] jobdata = job.split("\\|");
		jobId = jobdata[0];
		driverId = jm.getDriver();
		setTitle(manifestid);
		txtDSName.setText(jobdata[3]);
		txtDRName.setText(jobdata[4]);
		txtDAddress.setText(jobdata[1]);
		txtDContent.setText(jobdata[5]);
		
		btnDUpdate.setOnClickListener(new OnClickListener(){
        	public void onClick(View v){
        		Intent sigIntent = new Intent(v.getContext(),Signature.class);
        		sigIntent.putExtra(DELIVERY_JOBID, jobId);
        		sigIntent.putExtra(DELIVERY_MANIFESTID, manifestid);
        		sigIntent.putExtra(DELIVERY_DRIVERID, driverId);
        		startActivityForResult(sigIntent, SIGNATURE_REQUEST);
        	}
        });
	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.delivery, menu);
		return true;
	}
	
	public void onActivityResult(int requestCode, int resultCode, Intent intent) {
		if(requestCode == SIGNATURE_REQUEST){
			//capture signature
			if(resultCode==RESULT_OK){
	        	   jm.removeJob(groupPos, childPos);
	        	   finish();
			}
		}
	}

}
