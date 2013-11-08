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
		System.out.println("sq: "+"11. inside main.oncreate");
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_main);
		
		//get intent from login activity
		Intent intent = getIntent();
		String username = intent.getStringExtra(Login.LOGIN_USER);

		System.out.println("sq: "+"11. inside main.oncreate.A");
		//retrieve JobsManager from login intent
		jm = Login.jobsmanager;
		System.out.println("sq: "+"11. inside main.oncreate.B");
		
		//read job file and sort jobs
		jm.sortJobs(jm.readFile(username));
		System.out.println("sq: "+"11. inside main.oncreate.C");
		
		//create bundle to store username for summary fragment
		Bundle arguments = new Bundle();
		arguments.putString("username", username);
		
		//initialize tabhost and add tabs
		mTabHost = (FragmentTabHost)findViewById(android.R.id.tabhost);
		mTabHost.setup(this, getSupportFragmentManager(), R.id.realtabcontent);
		
		mTabHost.addTab(mTabHost.newTabSpec("Summary").setIndicator("Summary"),
                Summary.class, arguments);
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

}
