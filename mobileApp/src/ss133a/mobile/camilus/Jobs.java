package ss133a.mobile.camilus;

import java.util.HashMap;
import java.util.List;

import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ExpandableListView;
import android.widget.ExpandableListView.OnChildClickListener;

public class Jobs extends Fragment {
	public final static String JOB_DATA = "ss133a.mobile.camilus.JOB_DATA";
	public final static String JOB_MANIFESTID = "ss133a.mobile.camilus.JOB_MANIFESTID";
	public final static String JOB_GROUP_POSN = "ss133a.mobile.camilus.JOB_GROUP_POSN";
	public final static String JOB_CHILD_POSN = "ss133a.mobile.camilus.JOB_CHILD_POSN";
	public static final int SIGNATURE_REQUEST = 1;
	public static JobsExpandableAdapter jobAdapter;
    ExpandableListView expListView;
    List<String> listDataHeader;
    HashMap<String, List<String>> listJobContainer;
    int groupPos, childPos;
    
    JobsManager jmanager;
    String jobManifest, jobType;
    Context c = this.getActivity();

	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        // Inflate the layout for this fragment
        final View V = inflater.inflate(R.layout.activity_jobs, container, false);
        
        expListView = (ExpandableListView) V.findViewById(R.id.joblist);
        jmanager = Main.jm;
        listJobContainer = jmanager.getHashmapExpandableListContainer();
        listDataHeader = jmanager.getListJobHeader2();
        jobAdapter = new JobsExpandableAdapter(V.getContext(), listDataHeader, listJobContainer);
        expListView.setAdapter(jobAdapter);
        
        expListView.setOnChildClickListener(new OnChildClickListener() {
        	 
            @Override
            public boolean onChildClick(ExpandableListView parent, View v,
                    int groupPosition, int childPosition, long id) {
                // TODO Auto-generated method stub
            	groupPos = groupPosition;
            	childPos = childPosition;
            	jobManifest = listJobContainer.get(listDataHeader.get(groupPosition)).get(childPosition).split(" ")[0];
            	jobType = listDataHeader.get(groupPosition);
            	
                AlertDialog.Builder ab=new AlertDialog.Builder(V.getContext());
                String items[] = {"View Job","Get Direction"};
                ab.setTitle("Choose an option");
                ab.setItems(items, new DialogInterface.OnClickListener() {
                	public void onClick(DialogInterface d, int choice) {
                		/*Handles View Job Choice*/
	                	if(choice == 0) {
	                		Class c = null;
	                		if(jobType.equals("transfer")){
	                			c = Transfer.class;
	                		}else if(jobType.equals("delivery")){
	                			c = Delivery.class;
	                		}else if(jobType.equals("appointment")){
	                			c = Appointment.class;
	                		}
	                		Intent jobIntent = new Intent(V.getContext(),c);
	                		String jobdata = jmanager.getHashmapJobsContainer().get(jobManifest);
	                		jobIntent.putExtra(JOB_DATA, jobdata);
	                		jobIntent.putExtra(JOB_MANIFESTID, jobManifest);
	                		jobIntent.putExtra(JOB_GROUP_POSN, groupPos);
	                		jobIntent.putExtra(JOB_CHILD_POSN, childPos);
	                		startActivity(jobIntent);
	                	}
	                	else if(choice == 1) {
	                		String jobdata = jmanager.getHashmapJobsContainer().get(jobManifest);
	                		String postalcode = jobdata.split("\\|")[2];
	                		String address = jobdata.split("\\|")[1];
	                		Intent mapIntent = new Intent(V.getContext(),GMap.class);
	                		mapIntent.putExtra("postalcode", postalcode);
	                		mapIntent.putExtra("address", address);
	                		startActivity(mapIntent);
	                	}
                	}
                });
                ab.show();
                return false;
            }
        });
        
        return V;
	}

}
