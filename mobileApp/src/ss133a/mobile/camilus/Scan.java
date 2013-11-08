package ss133a.mobile.camilus;

import java.util.HashMap;
import java.util.List;

import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

public class Scan extends Fragment {

	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
	        // Inflate the layout for this fragment
	        View V = inflater.inflate(R.layout.activity_scan, container, false);
	        /*List<String> childR = Jobs.listDataHeader;
	        JobsExpandableAdapter jobAdapt = Jobs.jobAdapter;
	        childR.remove(0);
	        jobAdapt.notifyDataSetChanged();*/
	        return V;
	}

}
