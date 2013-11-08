package ss133a.mobile.camilus;

import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.DialogInterface;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

public class Summary extends Fragment {
	private TextView txtUser;
	private Button btnRetrieveJobs;
	TextView txtAppt,txtDel,txtTrans,txtJobsLeft;
	private int del,appt, trans;
	JobsManager jm;
	ProgressDialog pdLoading;
	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        // Inflate the layout for this fragment
        View V = inflater.inflate(R.layout.activity_summary, container, false);
        jm = Main.jm;
        del = 0;
        appt = 0;
        trans = 0;
        txtUser = (TextView) V.findViewById(R.id.txtUsername);
        txtAppt = (TextView) V.findViewById(R.id.txtAppointment);
        txtDel = (TextView) V.findViewById(R.id.txtDeliveries);
        txtTrans = (TextView) V.findViewById(R.id.txtTransfer);
        txtJobsLeft = (TextView) V.findViewById(R.id.txtJobsLeft);
        btnRetrieveJobs = (Button) V.findViewById(R.id.btnRetrieveJob);
        
        /*populate data in summary*/
        txtUser.setText(jm.getDriver());
        txtJobsLeft.setText(jm.getHashmapJobsContainer().size()+"");
        del = (jm.getHashmapExpandableListContainer().get("delivery")==null?0:jm.getHashmapExpandableListContainer().get("delivery").size());
        txtDel.setText(del+"");
        appt = (jm.getHashmapExpandableListContainer().get("appointment")==null?0:jm.getHashmapExpandableListContainer().get("appointment").size());
        txtAppt.setText(appt+"");
        trans = (jm.getHashmapExpandableListContainer().get("transfer")==null?0:jm.getHashmapExpandableListContainer().get("transfer").size());
        txtTrans.setText(trans+"");
        
        
        btnRetrieveJobs.setOnClickListener(new OnClickListener(){
        	public void onClick(View v){
        		if(jm.checkFileExist(v.getContext())==false){
    				pdLoading = ProgressDialog.show(v.getContext(), "Please Wait...", "Retrieving latest job...");
        			jm.downloadFileForSummary(Summary.this, v.getContext());
				}else{
					AlertDialog.Builder builder = new AlertDialog.Builder(v.getContext());
    				builder.setTitle("Retrieve Job Error");
    				builder.setMessage("You can only retrieve latest jobs after you complete your current jobs.")
    				       .setCancelable(false)
    				       .setPositiveButton("OK", new DialogInterface.OnClickListener() {
    				           public void onClick(DialogInterface dialog, int id) {
    				        	   dialog.cancel();
    				           }
    				       });
    				AlertDialog alert = builder.create();
    				alert.show();
				}
        	}
        });
        	
        
        return V;
	}

}
