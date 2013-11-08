package ss133a.mobile.camilus;

import java.util.concurrent.ExecutionException;

import android.content.Intent;
import android.os.Bundle;
import android.support.v4.app.FragmentActivity;
import android.support.v4.app.FragmentTabHost;
import android.view.Menu;
import android.widget.Toast;

public class Main extends FragmentActivity  {
	private FragmentTabHost mTabHost;
	public static JobsManager jm;
	//private TextView username;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_main);
		
		Intent intent = getIntent();
		String username = intent.getStringExtra(Login.LOGIN_MESSAGE);

		Bundle arguments = new Bundle();
		arguments.putString("username", username);
		
		mTabHost = (FragmentTabHost)findViewById(android.R.id.tabhost);
		mTabHost.setup(this, getSupportFragmentManager(), R.id.realtabcontent);
		
		mTabHost.addTab(mTabHost.newTabSpec("Summary").setIndicator("Summary"),
                Summary.class, arguments);
		mTabHost.addTab(mTabHost.newTabSpec("Jobs").setIndicator("Jobs"),
                Jobs.class, null);
		mTabHost.addTab(mTabHost.newTabSpec("Scan").setIndicator("Scan"),
                Scan.class, null);
		
		jm = new JobsManager();
		jm.downloadFile(username);
		jm.sortJobs(jm.readFile());
	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.main, menu);
		return true;
	}

}
