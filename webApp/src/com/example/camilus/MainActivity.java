package com.example.camilus;

import android.os.Bundle;
import android.app.Activity;
import android.app.TabActivity;
import android.content.Intent;
import android.content.res.Resources;
import android.view.Menu;
import android.widget.TabHost;
import android.widget.TabHost.TabSpec;
import android.widget.TextView;

public class MainActivity extends TabActivity {

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_main);
		
		Resources ressources = getResources(); 
		TabHost tabHost = getTabHost(); 
		
		// delivery tab
		Intent intentDelivery = new Intent().setClass(this, DeliveryActivity.class);
		TabSpec tabSpecDelivery = tabHost
		  .newTabSpec("Delivery")
		  .setIndicator("Delivery")
		  .setContent(intentDelivery);
		
		// collection tab
		Intent intentCollection = new Intent().setClass(this, CollectionActivity.class);
		TabSpec tabSpecCollection = tabHost
		  .newTabSpec("Collection")
		  .setIndicator("Collection")
		  .setContent(intentCollection);
		
		// add all tabs 
		tabHost.addTab(tabSpecDelivery);
		tabHost.addTab(tabSpecCollection);
	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.main, menu);
		return true;
	}

}
