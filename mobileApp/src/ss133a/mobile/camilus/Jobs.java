package ss133a.mobile.camilus;

import java.util.ArrayList;
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
    HashMap<String, List<String>> listDataChild;

	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        // Inflate the layout for this fragment
        final View V = inflater.inflate(R.layout.activity_jobs, container, false);
        
        expListView = (ExpandableListView) V.findViewById(R.id.joblist);
        JobsManager jmanager = Main.jm;
        jmanager.prepareJobContainer();
        listDataChild = jmanager.getDataContainer();
        listDataHeader = jmanager.getJobHeader();
        //jobAdapter = new JobsExpandableAdapter(V.getContext(), jmanager.getJobHeader(), jmanager.getDataContainer());
        //prepareListData();
        jobAdapter = new JobsExpandableAdapter(V.getContext(), listDataHeader, listDataChild);
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
                                + listDataChild.get(
                                        listDataHeader.get(groupPosition)).get(
                                        childPosition), Toast.LENGTH_SHORT)
                        .show();
                return false;
            }
        });
        
        return V;
	}
	
	private void prepareListData() {
        listDataHeader = new ArrayList<String>();
        listDataChild = new HashMap<String, List<String>>();
 
        // Adding child data
        listDataHeader.add("Collection - Priority Express Mail");
        listDataHeader.add("Collection - Priority Mail");
        listDataHeader.add("Collection - Priority Package");
        listDataHeader.add("Collection - Registered Mail");
        listDataHeader.add("Collection - Registered Package");
        
        listDataHeader.add("Delivery - Priority Express Mail");
        listDataHeader.add("Delivery - Priority Mail");
        listDataHeader.add("Delivery - Priority Package");
        listDataHeader.add("Delivery - Registered Mail");
        listDataHeader.add("Delivery - Registered Package");
 
        // Adding child data
        List<String> collectionsPEM = new ArrayList<String>();
        collectionsPEM.add("Address 1");
        collectionsPEM.add("Address 2");
        
        List<String> collectionsPM = new ArrayList<String>();
        collectionsPM.add("Address 3");
        collectionsPM.add("Address 4");
        collectionsPM.add("Address 5");
        
        List<String> collectionsPP = new ArrayList<String>();
        collectionsPP.add("Address 6");
        collectionsPP.add("Address 7");
        
        List<String> collectionsRM = new ArrayList<String>();
        collectionsRM.add("Address 8");
        collectionsRM.add("Address 9");
        
        List<String> collectionsRP = new ArrayList<String>();
        collectionsRP.add("237 Tampines Street 21");
        collectionsRP.add("519 Bedok North Ave 2");
        
        List<String> deliveriesPEM = new ArrayList<String>();
        deliveriesPEM.add("Address 1");
        deliveriesPEM.add("Address 2");
        
        List<String> deliveriesPM = new ArrayList<String>();
        deliveriesPM.add("Address 3");
        deliveriesPM.add("Address 4");
        deliveriesPM.add("Address 5");
        
        List<String> deliveriesPP = new ArrayList<String>();
        deliveriesPP.add("Address 6");
        deliveriesPP.add("Address 7");
        
        List<String> deliveriesRM = new ArrayList<String>();
        deliveriesRM.add("Address 8");
        deliveriesRM.add("Address 9");
        
        List<String> deliveriesRP = new ArrayList<String>();
        deliveriesRP.add("111 Tampines Street 11");
        deliveriesRP.add("749 Tampines Street 79");
        deliveriesRP.add("201A Tampines Street 21");
 
        listDataChild.put(listDataHeader.get(0), collectionsPEM); // Header, Child data
        listDataChild.put(listDataHeader.get(1), collectionsPM);
        listDataChild.put(listDataHeader.get(2), collectionsPP);
        listDataChild.put(listDataHeader.get(3), collectionsRM);
        listDataChild.put(listDataHeader.get(4), collectionsRP);
        listDataChild.put(listDataHeader.get(5), deliveriesPEM);
        listDataChild.put(listDataHeader.get(6), deliveriesPM);
        listDataChild.put(listDataHeader.get(7), deliveriesPP);
        listDataChild.put(listDataHeader.get(8), deliveriesRM);
        listDataChild.put(listDataHeader.get(9), deliveriesRP);

       // Toast.makeText(V.getContext(), "jobs added", Toast.LENGTH_LONG).show();
    }

}
