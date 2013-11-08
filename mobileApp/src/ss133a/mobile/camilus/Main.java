package ss133a.mobile.camilus;

import android.content.Intent;
import android.os.Bundle;
import android.support.v4.app.FragmentActivity;
import android.support.v4.app.FragmentTabHost;
import android.view.Menu;

public class Main extends FragmentActivity  {
	private FragmentTabHost mTabHost;
	public static JobsManager jm;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_main);
		
		/*retrieve JobsManager from Login.class*/
		jm = Login.jobsmanager;
		
		/*initialize TabHost and add tabs*/
		mTabHost = (FragmentTabHost)findViewById(android.R.id.tabhost);
		mTabHost.setup(this, getSupportFragmentManager(), R.id.realtabcontent);
		
		mTabHost.addTab(mTabHost.newTabSpec("Summary").setIndicator("Summary"),
                Summary.class, null);
		mTabHost.addTab(mTabHost.newTabSpec("Jobs").setIndicator("Jobs"),
                Jobs.class, null);
		mTabHost.addTab(mTabHost.newTabSpec("Scan").setIndicator("Scan"),
                Scan.class, null);
	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.main, menu);
		return true;
	}
	
	/*Function to catch onActivityResult from Scan fragment's Scan activity and push it to Scan fragment*/
	@Override
	public void onActivityResult(int requestCode, int resultCode, Intent data) {
	   super.onActivityResult(requestCode, resultCode, data);
	}

}
