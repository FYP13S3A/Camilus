package ss133a.mobile.camilus;


import ss133a.mobile.camilus.JobsManager.jobType;

import com.google.zxing.integration.android.IntentIntegrator;
import com.google.zxing.integration.android.IntentResult;

import android.app.AlertDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

public class Scan extends Fragment {
	private static final String BS_PACKAGE = "com.google.zxing.client.android";
	private static final int SCAN_REQUEST = 0x0000c0de;
	private static final int VIEW_REQUEST = 888;
	private Button btnScan, btnView, btnDirection;
	private TextView txtSManifestId, txtSJobType, txtSAddress;
	IntentIntegrator scanIntegrator;
	JobsManager jm;
	String job;
	String[] jobdata;
	View V;

	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
	        // Inflate the layout for this fragment
	        V = inflater.inflate(R.layout.activity_scan, container, false);
	        jm = Main.jm;
	        
	        btnScan = (Button)V.findViewById(R.id.btnScan);
	        btnView = (Button)V.findViewById(R.id.btnView);
	        btnDirection = (Button)V.findViewById(R.id.btnDirection);
	        txtSManifestId = (TextView)V.findViewById(R.id.txtSManifestId);
	        txtSJobType = (TextView)V.findViewById(R.id.txtSJobType);
	        txtSAddress = (TextView)V.findViewById(R.id.txtSAddress);
	        scanIntegrator = new IntentIntegrator(getActivity());
	        
	        btnDirection.setEnabled(false);
	        btnView.setEnabled(false);

	        btnScan.setOnClickListener(new OnClickListener(){
	        	public void onClick(View v){
	        		/*Call Barcode Scanner*/
	        	    Intent intentScan = new Intent(BS_PACKAGE + ".SCAN");
	        	    intentScan.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
	        	    intentScan.addFlags(Intent.FLAG_ACTIVITY_CLEAR_WHEN_TASK_RESET);
	        	    startActivityForResult(intentScan, SCAN_REQUEST);
	        	}
	        });
	        
	        btnView.setOnClickListener(new OnClickListener(){
	        	public void onClick(View v){
	        		Class c = null;
	        		switch (jobType.valueOf(jobdata[jobdata.length-1])){
					case delivery:
						c = Delivery.class;
						break;
					case appointment:
						c = Appointment.class;
						break;
					case transfer:
						c = Transfer.class;
						break;
					default:
						break;
					}
	        		int[] pos = jm.getHeadChildPos(jobdata[jobdata.length-1], txtSManifestId.getText().toString());
	        		Intent jobIntent = new Intent(V.getContext(),c);
            		jobIntent.putExtra(Jobs.JOB_DATA, job);
            		jobIntent.putExtra(Jobs.JOB_MANIFESTID, txtSManifestId.getText().toString());
            		jobIntent.putExtra(Jobs.JOB_GROUP_POSN, pos[0]);
            		jobIntent.putExtra(Jobs.JOB_CHILD_POSN, pos[1]);
            		startActivityForResult(jobIntent,VIEW_REQUEST);
	        	}
	        });
	        return V;
	}
	
	/*Function to grab Barcode Scanner's result, populate respective TextViews and implement event listeners for respective buttons*/
	@Override
	public void onActivityResult(int requestCode, int resultCode, Intent data) {
		if(requestCode==SCAN_REQUEST){
			IntentResult scanningResult = IntentIntegrator.parseActivityResult(requestCode, resultCode, data);
			if (scanningResult != null) {
				if(scanningResult.getContents()!=null){
					String scanResult = scanningResult.getContents();
					if(jm.getHashmapJobsContainer().containsKey(scanResult)){
						job = jm.getHashmapJobsContainer().get(scanResult);
						jobdata = job.split("\\|");
						txtSManifestId.setText(scanResult);
						txtSJobType.setText(jobdata[jobdata.length-1]);
						txtSAddress.setText(jobdata[1]);
				        btnDirection.setEnabled(true);
				        btnView.setEnabled(true);
					}else{
						AlertDialog.Builder builder = new AlertDialog.Builder(V.getContext());
						builder.setTitle("Scan Result.");
						builder.setMessage("No job found!")
						       .setCancelable(false)
						       .setPositiveButton("OK", new DialogInterface.OnClickListener() {
						           public void onClick(DialogInterface dialog, int id) {
						                //do things
						        	   
						           }
						       });
						AlertDialog alert = builder.create();
						alert.show();
					}
				}
			}else{
				Toast.makeText(V.getContext(),"No scan data received!", Toast.LENGTH_SHORT).show();
			}
		}else if(requestCode==VIEW_REQUEST){
			/*To clear fields and disable buttons after user return from View Jobs*/
			txtSManifestId.setText("");
			txtSJobType.setText("");
			txtSAddress.setText("");
	        btnDirection.setEnabled(false);
	        btnView.setEnabled(false);
		}
		
	}

}
