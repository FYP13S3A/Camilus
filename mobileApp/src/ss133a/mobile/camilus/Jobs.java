package ss133a.mobile.camilus;

import java.util.HashMap;
import java.util.List;

import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ExpandableListView;
import android.widget.Toast;
import android.widget.ExpandableListView.OnChildClickListener;
import android.widget.ExpandableListView.OnGroupClickListener;
import android.widget.ExpandableListView.OnGroupCollapseListener;
import android.widget.ExpandableListView.OnGroupExpandListener;

public class Jobs extends Fragment {
	
	JobsExpandableAdapter jobAdapter;
    ExpandableListView expListView;
    List<String> listDataHeader;
    HashMap<String, List<String>> listJobContainer;

	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        // Inflate the layout for this fragment
        final View V = inflater.inflate(R.layout.activity_jobs, container, false);
        
        expListView = (ExpandableListView) V.findViewById(R.id.joblist);
        
        //retrieve jobsmanager residing on Main.class
        JobsManager jmanager = Main.jm;
        
        //prepare job container for espandable list population
        jmanager.prepareJobContainer();
        listJobContainer = jmanager.getHashmapDataContainer();
        listDataHeader = jmanager.getListJobHeader2();
        jobAdapter = new JobsExpandableAdapter(V.getContext(), listDataHeader, listJobContainer);
        expListView.setAdapter(jobAdapter);
        
        expListView.setOnGroupClickListener(new OnGroupClickListener() {
            @Override
            public boolean onGroupClick(ExpandableListView parent, View v,
                    int groupPosition, long id) {
                return false;
            }
        });
        
        expListView.setOnGroupExpandListener(new OnGroupExpandListener() {
        	 
            @Override
            public void onGroupExpand(int groupPosition) {
            }
        });
        
        expListView.setOnGroupCollapseListener(new OnGroupCollapseListener() {
        	 
            @Override
            public void onGroupCollapse(int groupPosition) {
            }
        });
        
        expListView.setOnChildClickListener(new OnChildClickListener() {
        	 
            @Override
            public boolean onChildClick(ExpandableListView parent, View v,
                    int groupPosition, int childPosition, long id) {
                // TODO Auto-generated method stub
                Toast.makeText(
                        V.getContext(),
                        listDataHeader.get(groupPosition)
                                + " : "
                                + listJobContainer.get(
                                        listDataHeader.get(groupPosition)).get(
                                        childPosition), Toast.LENGTH_SHORT)
                        .show();
                return false;
            }
        });
        
        return V;
	}

}
