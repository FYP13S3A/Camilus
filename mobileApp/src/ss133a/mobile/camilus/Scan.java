package ss133a.mobile.camilus;

import java.util.HashMap;
import java.util.List;

import com.google.zxing.integration.android.IntentIntegrator;
import com.google.zxing.integration.android.IntentResult;

import android.app.ProgressDialog;
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
	private Button btnScan, btnView, btnDirection;
	private TextView txtSManifestId, txtSJobType, txtSAddress;
	IntentIntegrator scanIntegrator;
	View V;

	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
	        // Inflate the layout for this fragment
	        V = inflater.inflate(R.layout.activity_scan, container, false);
	        JobsManager jm = Main.jm;
	        
	        btnScan = (Button)V.findViewById(R.id.btnScan);
	        btnView = (Button)V.findViewById(R.id.btnView);
	        btnDirection = (Button)V.findViewById(R.id.btnDirection);
	        txtSManifestId = (TextView)V.findViewById(R.id.txtSManifestId);
	        txtSJobType = (TextView)V.findViewById(R.id.txtSJobType);
	        txtSAddress = (TextView)V.findViewById(R.id.txtSAddress);
	        scanIntegrator = new IntentIntegrator(getActivity());

	        btnScan.setOnClickListener(new OnClickListener(){
	        	public void onClick(View v){
	        		Intent intentScan = new Intent(BS_PACKAGE + ".SCAN");
	        		intentScan.addCategory(Intent.CATEGORY_DEFAULT);
	        	    startActivityForResult(intentScan, SCAN_REQUEST);
	        	}
	        });
	        return V;
	}
	
	@Override
	public void onActivityResult(int requestCode, int resultCode, Intent data) {
		IntentResult scanningResult = IntentIntegrator.parseActivityResult(requestCode, resultCode, data);
		if (scanningResult != null) {
			//we have a result
			Toast.makeText(V.getContext(),scanningResult.getContents(), Toast.LENGTH_SHORT).show();
			String scanResult = scanningResult.getContents();
		}else{
			Toast.makeText(V.getContext(),"No scan data received!", Toast.LENGTH_SHORT).show();
		}
		
	}

}
