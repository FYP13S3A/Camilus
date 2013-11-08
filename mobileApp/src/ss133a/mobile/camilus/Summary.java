package ss133a.mobile.camilus;

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
	private TextView txtColl, txtCPE, txtCP, txtCPP, txtCR, txtCRP;
	private TextView txtDel, txtDPE, txtDP, txtDPP, txtDR, txtDRP;
	private TextView txtTrans,txtComplete;
	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        // Inflate the layout for this fragment
        View V = inflater.inflate(R.layout.activity_summary, container, false);
        Bundle argument = getArguments();
        String username = argument.getString("username", "N/A");
        txtUser = (TextView) V.findViewById(R.id.txtUsername);
        txtUser.setText(username);
        
        txtColl = (TextView) V.findViewById(R.id.txtCollection);
        txtCPE = (TextView) V.findViewById(R.id.txtCPEMail);
        txtCP = (TextView) V.findViewById(R.id.txtCPMail);
        txtCPP = (TextView) V.findViewById(R.id.txtCPPack);
        txtCR = (TextView) V.findViewById(R.id.txtCRMail);
        txtCRP = (TextView) V.findViewById(R.id.txtCRPack);
        
        txtDel = (TextView) V.findViewById(R.id.txtDeliveries);
        txtDPE = (TextView) V.findViewById(R.id.txtDPEMail);
        txtDP = (TextView) V.findViewById(R.id.txtDPMail);
        txtDPP = (TextView) V.findViewById(R.id.txtDPPack);
        txtDR = (TextView) V.findViewById(R.id.txtDRMail);
        txtDRP = (TextView) V.findViewById(R.id.txtDRPack);
        
        txtTrans = (TextView) V.findViewById(R.id.txtTransfer);
        txtComplete = (TextView) V.findViewById(R.id.txtCompletion);
        
        btnRetrieveJobs = (Button) V.findViewById(R.id.btnRetrieveJob);
        btnRetrieveJobs.setOnClickListener(new OnClickListener(){
        	public void onClick(View v){
        		Toast.makeText(v.getContext(), "button testing", Toast.LENGTH_LONG).show();
        	}
        });
        	
        
        return V;
	}

}
