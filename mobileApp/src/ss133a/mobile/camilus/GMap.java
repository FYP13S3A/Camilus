package ss133a.mobile.camilus;


import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;

import org.w3c.dom.Document;
import com.google.android.gms.common.ConnectionResult;
import com.google.android.gms.common.GooglePlayServicesClient;
import com.google.android.gms.common.GooglePlayServicesUtil;
import com.google.android.gms.location.LocationClient;
import com.google.android.gms.maps.CameraUpdateFactory;
import com.google.android.gms.maps.GoogleMap;
import com.google.android.gms.maps.MapFragment;
import com.google.android.gms.maps.model.BitmapDescriptorFactory;
import com.google.android.gms.maps.model.LatLng;
import com.google.android.gms.maps.model.LatLngBounds;
import com.google.android.gms.maps.model.MarkerOptions;
import com.google.android.gms.maps.model.Polyline;
import com.google.android.gms.maps.model.PolylineOptions;

import android.location.Location;
import android.os.AsyncTask;
import android.os.Bundle;
import android.app.Activity;
import android.app.AlertDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.IntentSender.SendIntentException;
import android.graphics.Color;
import android.view.Menu;

public class GMap extends Activity implements GooglePlayServicesClient.ConnectionCallbacks, GooglePlayServicesClient.OnConnectionFailedListener{
	public final static int CONNECTION_FAILURE_RESOLUTION_REQUEST = 9000;
	private GoogleMap googleMap;
	private LocationClient locationClient;
	private Location currentLocation;
	private String postalcode;
	private String address;
	Activity a = this;
	double currentLat;
	double currentLong;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_gmap);
		
		Intent intent = getIntent();
		postalcode = intent.getStringExtra("postalcode");
		address = intent.getStringExtra("address");
		
		try {
            initilizeMap();
 
        } catch (Exception e) {
            e.printStackTrace();
        }
		
		if(googleMap!=null){
			locationClient = new LocationClient(this, this, this);
			locationClient.connect();
		}
	}
	
	
	
	@Override
    protected void onResume() {
        super.onResume();
        initilizeMap();
    }
	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.gmap, menu);
		return true;
	}
	
	private void initilizeMap() {
        if (googleMap == null) {
            googleMap = ((MapFragment) getFragmentManager().findFragmentById(
                    R.id.map)).getMap();
            //googleMap.setMyLocationEnabled(true);
            //googleMap.getUiSettings().setRotateGesturesEnabled(false);
            
            // check if map is created successfully or not
            if (googleMap == null) {
            	AlertDialog.Builder builder = new AlertDialog.Builder(getApplicationContext());
				builder.setTitle("Error");
				builder.setMessage("Unable to initilize Google Map. Please try again later.")
				       .setCancelable(false)
				       .setPositiveButton("OK", new DialogInterface.OnClickListener() {
				           public void onClick(DialogInterface dialog, int id) {
				        	   finish();
				           }
				       });
				AlertDialog alert = builder.create();
				alert.show();
            }else{
                googleMap.getUiSettings().setCompassEnabled(false);
                googleMap.getUiSettings().setZoomControlsEnabled(false);
            }
        }
    }
	
	private boolean isGooglePlayServicesConnected(){
        int resultCode = GooglePlayServicesUtil.isGooglePlayServicesAvailable(this);
        if(ConnectionResult.SUCCESS == resultCode){
            return true;
        }else{
            return false;
        }
    }
	
	public void findDirections(double fromPositionDoubleLat, double fromPositionDoubleLong, String postal, String mode){
	    Map<String, String> map = new HashMap<String, String>();
	    map.put(GetDirectionsAsyncTask.USER_CURRENT_LAT, String.valueOf(fromPositionDoubleLat));
	    map.put(GetDirectionsAsyncTask.USER_CURRENT_LONG, String.valueOf(fromPositionDoubleLong));
	    map.put(GetDirectionsAsyncTask.DESTINATION_POSTAL, postal);
	    map.put(GetDirectionsAsyncTask.DIRECTIONS_MODE, mode);

	    GetDirectionsAsyncTask asyncTask = new GetDirectionsAsyncTask();
	    asyncTask.execute(map);
	}
	
	public void handleGetDirectionsResult(ArrayList<LatLng> directionPoints)
	{
		//draw direction start
	    Polyline newPolyline;
	    PolylineOptions rectLine = new PolylineOptions().width(3).color(Color.RED);

	    for(int i = 0 ; i < directionPoints.size() ; i++) 
	    {          
	        rectLine.add(directionPoints.get(i));
	    }
	    newPolyline = googleMap.addPolyline(rectLine);
	    //draw direction end
	    
	    //draw marker start
	    googleMap.addMarker(new MarkerOptions()
	    .position(directionPoints.get(0))
	    .title("Your Location")
	    .icon(BitmapDescriptorFactory.defaultMarker(BitmapDescriptorFactory.HUE_AZURE)));
	    googleMap.addMarker(new MarkerOptions()
		.position(directionPoints.get(directionPoints.size() - 1))
		.title("Destination")
		.snippet(address)
		.icon(BitmapDescriptorFactory.defaultMarker(BitmapDescriptorFactory.HUE_ROSE)));
	    //draw marker end
	    
	    //zoom in start
	    LatLngBounds.Builder builder = new LatLngBounds.Builder();
	    builder.include(directionPoints.get(0));
	    builder.include(directionPoints.get(directionPoints.size()-1));
	    LatLngBounds bounds = builder.build();
	    googleMap.animateCamera(CameraUpdateFactory.newLatLngBounds(bounds, 100));
	    //zoom in end
	}
	
	 
	
	
	
	public class GetDirectionsAsyncTask extends AsyncTask<Map<String, String>, Object, ArrayList<LatLng>> {

		public static final String USER_CURRENT_LAT = "user_current_lat";
		public static final String USER_CURRENT_LONG = "user_current_long";
		public static final String DIRECTIONS_MODE = "directions_mode";
		public static final String DESTINATION_POSTAL = "destination_postal";

		private Exception exception;

		@Override
		public void onPostExecute(ArrayList<LatLng> result) {

		    if (exception == null) {
		    	handleGetDirectionsResult(result);
		    }
		}

		@Override
		protected ArrayList<LatLng> doInBackground(Map<String, String>... params) {

		    Map<String, String> paramMap = params[0];
		    try{
		        LatLng fromPosition = new LatLng(Double.valueOf(paramMap.get(USER_CURRENT_LAT)) , Double.valueOf(paramMap.get(USER_CURRENT_LONG)));
		        String postal = String.valueOf(paramMap.get(DESTINATION_POSTAL));
		        GMapDirection md = new GMapDirection();
		        Document doc = md.getDocument(fromPosition, postal, getApplicationContext(), a);
		        ArrayList<LatLng> directionPoints = md.getDirection(doc);
		        return directionPoints;

		    }
		    catch (Exception e) {
		        exception = e;
		        return null;
		    }
		}

	}

	@Override
	public void onConnectionFailed(ConnectionResult connectionResult) {
		// TODO Auto-generated method stub
		if(connectionResult.hasResolution()){
			try {
				connectionResult.startResolutionForResult(this, CONNECTION_FAILURE_RESOLUTION_REQUEST);
			} catch (SendIntentException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
		}else{
			AlertDialog.Builder builder = new AlertDialog.Builder(getApplicationContext());
			builder.setTitle("Error");
			builder.setMessage("Unable to connect to Google Play Service. Please try again later.")
			       .setCancelable(false)
			       .setPositiveButton("OK", new DialogInterface.OnClickListener() {
			           public void onClick(DialogInterface dialog, int id) {
			        	   finish();
			           }
			       });
			AlertDialog alert = builder.create();
			alert.show();
		}
	}



	@Override
	public void onConnected(Bundle bundle) {
		// TODO Auto-generated method stub
		if(isGooglePlayServicesConnected()){
			currentLocation = locationClient.getLastLocation();
			findDirections(currentLocation.getLatitude(), currentLocation.getLongitude(), postalcode, "driving");
		}else{
			AlertDialog.Builder builder = new AlertDialog.Builder(getApplicationContext());
			builder.setTitle("Error");
			builder.setMessage("Unable to connect to Google Play Service. Please try again later.")
			       .setCancelable(false)
			       .setPositiveButton("OK", new DialogInterface.OnClickListener() {
			           public void onClick(DialogInterface dialog, int id) {
			        	   finish();
			           }
			       });
			AlertDialog alert = builder.create();
			alert.show();
		}
	}



	@Override
	public void onDisconnected() {
		// TODO Auto-generated method stub
		
	}
	
	@Override
    protected void onActivityResult(int requestCode, int resultCode, Intent intent){
		switch (requestCode) {
        case CONNECTION_FAILURE_RESOLUTION_REQUEST:
        	 switch (resultCode) {
        	 	case Activity.RESULT_OK:
        	 	
        	 	default:
        	 		AlertDialog.Builder builder = new AlertDialog.Builder(getApplicationContext());
        			builder.setTitle("Error");
        			builder.setMessage("Unable to connect to Google Play Service. Please try again later.")
        			       .setCancelable(false)
        			       .setPositiveButton("OK", new DialogInterface.OnClickListener() {
        			           public void onClick(DialogInterface dialog, int id) {
        			        	   finish();
        			           }
        			       });
        			AlertDialog alert = builder.create();
        			alert.show();
        	 }
        default:
        	AlertDialog.Builder builder = new AlertDialog.Builder(getApplicationContext());
			builder.setTitle("Error");
			builder.setMessage("Please try again later.")
			       .setCancelable(false)
			       .setPositiveButton("OK", new DialogInterface.OnClickListener() {
			           public void onClick(DialogInterface dialog, int id) {
			        	   finish();
			           }
			       });
			AlertDialog alert = builder.create();
			alert.show();
		}
	}
	
}
