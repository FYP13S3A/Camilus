package ss133a.mobile.camilus;

import java.io.BufferedInputStream;
import java.io.BufferedOutputStream;
import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.File;
import java.io.FileOutputStream;
import java.io.FileReader;
import java.io.FileWriter;
import java.io.IOException;
import java.io.PrintWriter;
import java.net.SocketTimeoutException;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.HashMap;
import java.util.List;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.conn.ConnectTimeoutException;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.params.BasicHttpParams;
import org.apache.http.params.HttpConnectionParams;
import org.apache.http.params.HttpParams;

import android.app.AlarmManager;
import android.app.AlertDialog;
import android.app.PendingIntent;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.AsyncTask;

public class JobsManager{
	HashMap<String, List<String>> hashmapExpandableListContainer;
	HashMap<String, String> hashmapJobsContainer;
	List<String> listJobHeader, listJobHeader2;
	List<List<String>> listJobChilds;
	Login login;
	Summary summary;
	String driver;
	Context context;
	
	public JobsManager(String driver){
		hashmapExpandableListContainer = new HashMap<String, List<String>>();
		hashmapJobsContainer = new HashMap<String, String>();
		listJobHeader = new ArrayList<String>(); //listjobheader is to create the 3 headers for storing of all the children
		listJobHeader2 = new ArrayList<String>(); //listjobheader2 is to create necessary header inside expandable list
		listJobChilds = new ArrayList<List<String>>();
		this.driver = driver;
	}
	
	public HashMap<String, List<String>> getHashmapExpandableListContainer(){
		return hashmapExpandableListContainer;
	}
	
	public void setHashmapExpandableListContainer(HashMap<String, List<String>> hashmapExpandableListContainer){
		this.hashmapExpandableListContainer = hashmapExpandableListContainer;
	}
	
	public HashMap<String, String> getHashmapJobsContainer(){
		return hashmapJobsContainer;
	}
	
	public void setHashmapJobsContainer(HashMap<String, String> hashmapJobsContainer){
		this.hashmapJobsContainer = hashmapJobsContainer;
	}
	
	public List<String> getListJobHeader(){
		return listJobHeader;
	}
	
	public void setListJobHeader(List<String> listJobHeader){
		this.listJobHeader = listJobHeader;
	}
	
	public List<String> getListJobHeader2(){
		return listJobHeader2;
	}
	
	public void setListJobHeader2(List<String> listJobHeader2){
		this.listJobHeader2 = listJobHeader2;
	}
	
	public List<List<String>> getListJobChilds(){
		return listJobChilds;
	}
	
	public void setListJobChilds(List<List<String>> listJobChilds){
		this.listJobChilds = listJobChilds;
	}
	
	public String getDriver(){
		return driver;
	}
	
	public void setDriver(String driver){
		this.driver = driver;
	}
	
	/*Function to add header nodes and create new ArrayList for store children nodes for expandable list*/
	public void addHeaderChildren(){
		listJobHeader.add("delivery"); //delivery header
		listJobHeader.add("appointment"); //appointment header
		listJobHeader.add("transfer"); //transfer header
		listJobChilds.add(new ArrayList<String>()); //store delivery children
		listJobChilds.add(new ArrayList<String>()); //store collection children
		listJobChilds.add(new ArrayList<String>()); //store transfer children
	}
	
	public int[] getHeadChildPos(String jobType, String childData){
		int[] pos = new int[2];
		for(int i=0;i<listJobHeader2.size();i++){
			if(listJobHeader2.get(i).equals(jobType)){
				pos[0] = i;
				break;
			}
		}
		List<String> child = getHashmapExpandableListContainer().get(listJobHeader2.get(pos[0]));
		for(int j=0;j<child.size();j++){
			if(child.get(j).contains(childData)){
				pos[1] = j;
			}
		}
		return pos;
	}
	
	/*Function to check if job file for deliveryman exist in phone.*/
	public boolean checkFileExist(Context c){
		String filePath = c.getFilesDir()+File.separator+driver+".txt";
		File file = new File(filePath);
		return file.exists();
	}
	
	/*Function to call AsynTask to retrieve job file from server.*/
	public void downloadFile(Login l, Context c){
		login = l;
		context = c;
		new RetrieveJobAsyncTask().execute(driver,"login");
	}
	
	public void downloadFileForSummary(Summary s, Context c){
		summary = s;
		context = c;
		new RetrieveJobAsyncTask().execute(driver,"summary");
	}
	
	/*Function to read contents inside job file and return a String value.*/
	public String readFile(Context c){
		String filedata = "";
		String filePath = c.getFilesDir()+File.separator+driver+".txt";
		File file = new File(filePath);
		
		StringBuilder text = new StringBuilder();
		try {
		    BufferedReader br = new BufferedReader(new FileReader(file));
		    String line;
		    while ((line = br.readLine()) != null) {
		        text.append(line);
		        text.append('\n');
		    }
		}
		catch (IOException e) {
		   
		}
		filedata = text.toString();
		return filedata;
	}
	
	/*Function to remove job file from phone.*/
	public void removeFile(Context c){
		String filePath = c.getFilesDir()+File.separator+driver+".txt";
		//delete file
		File file = new File(filePath);
		file.delete();
	}
	
	public void addJob(String job){
		String[] jobData = job.split("\\|");
		if(jobData[0].equals("transfer")){
			//key = manifest id, value = jobid|building|postalcode|jobType
			hashmapJobsContainer.put(jobData[2], jobData[1]+"|"+jobData[3]+"|"+jobData[4]+"|"+jobData[0]);
		}else if(jobData[0].equals("delivery")){
			//key = manifest id, value = jobid|ToAddress|ToPostalCode|SenderName|RecipientName|mailContents|jobType
			hashmapJobsContainer.put(jobData[2], jobData[1]+"|"+jobData[3]+"|"+jobData[4]+"|"+jobData[5]+"|"+jobData[6]+"|"+jobData[7]+"|"+jobData[0]);
		}else if(jobData[0].equals("appointment")){
			//key = manifest id, value = jobid|ApptAddress|ApptPostalCode|ApptName|mailContents|jobType
			hashmapJobsContainer.put(jobData[2], jobData[1]+"|"+jobData[3]+"|"+jobData[4]+"|"+jobData[5]+"|"+jobData[6]+"|"+jobData[0]);
		}
	}
	
	/*Function to sort jobs to respective children node container based on input variable 'file'
	 *Also call addJob function to add all jobs from input variable 'file' into hashmapJobsContainer*/
	public void sortJobs(String file){
		String[] jobs = file.split("\\*\\*");
		for(int i=0;i<jobs.length;i++){
			addJob(jobs[i]);
			String[] jobdetails = jobs[i].split("\\|");
			if(jobdetails[0].trim()!=""){
				switch (jobType.valueOf(jobdetails[0])){
					case delivery:
						listJobChilds.get(0).add(jobdetails[2]+" "+jobdetails[3]);
						break;
					case appointment:
						listJobChilds.get(1).add(jobdetails[2]+" "+jobdetails[3]);
						break;
					case transfer:
						listJobChilds.get(2).add(jobdetails[2]+" "+jobdetails[3]);
						break;
					default:
						break;
				}
			}
		}
	}
	
	/*Function to prepare hashmapExpandableListContainer for data population inside Jobs.class
	 *Adds headers with children nodes to hashmapExpandableListContainer
	 *Adds only headers that have children nodes to listJobHeader2
	 *Uses boolean variable 'prepareContainer' as a switch to prevent function from being called unnecessary during revisit of Activities*/
	public void prepareJobContainer(){
		for(int i=0;i<listJobHeader.size();i++){
			if(listJobChilds.get(i).size()>0){
				hashmapExpandableListContainer.put(listJobHeader.get(i),listJobChilds.get(i));
				listJobHeader2.add(listJobHeader.get(i));
			}
		}
	}
	
	/*Function to call various sub-functions to remove job from application as well as job file*/
	public void removeJob(int groupPos, int childPos, String manifestId, Context c){
		removeJobFromExpandableList(groupPos, childPos);
		removeJobFromJobContainer(manifestId);
		removeJobFromFile(c);
	}
	
	public void removeJobFromExpandableList(int groupPos, int childPos){
		JobsExpandableAdapter jobAdapt = Jobs.jobAdapter;
		List<String> child = getHashmapExpandableListContainer().get(getListJobHeader2().get(groupPos));
		child.remove(childPos);
		if(child.size()==0){
			getListJobHeader2().remove(groupPos);
		}
		if(jobAdapt!=null){
			jobAdapt.notifyDataSetChanged();
		}
	}
	
	public void removeJobFromJobContainer(String manifestId){
		hashmapJobsContainer.remove(manifestId);
	}
	
	public void removeJobFromFile(Context c){
		String filePath = c.getFilesDir()+File.separator+driver+".txt";
		if(hashmapJobsContainer.size()==0){
			//delete file
			File file = new File(filePath);
			file.delete();
		}
		else{
			String filedata = "";
			for (String key: hashmapJobsContainer.keySet()) {
				String job = hashmapJobsContainer.get(key);
				String[] jobdata = job.split("\\|");
				
				switch (jobType.valueOf(jobdata[jobdata.length-1])){
				case delivery:
					filedata+=jobdata[6]+"|"+jobdata[0]+"|"+key+"|"+jobdata[1]+"|"+jobdata[2]+"|"+jobdata[3]+"|"+jobdata[4]+"|"+jobdata[5]+"**";
					break;
				case appointment:
					filedata+=jobdata[5]+"|"+jobdata[0]+"|"+key+"|"+jobdata[1]+"|"+jobdata[2]+"|"+jobdata[3]+"|"+jobdata[4]+"**";
					break;
				case transfer:
					filedata+=jobdata[3]+"|"+jobdata[0]+"|"+key+"|"+jobdata[1]+"|"+jobdata[2]+"**";
					break;
				default:
					break;
				}
			}
			filedata= filedata.substring(0, filedata.length()-2);
			try {
				FileWriter fstream = new FileWriter(filePath,false);
				BufferedWriter out = new BufferedWriter(fstream);
			    out.write(filedata);
			    out.close();
			} catch (IOException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
		}
	}
	
	public enum jobType{
	     delivery,appointment,transfer; 
	 }
	
	public boolean isConnectingToInternet(Context context){
        ConnectivityManager connectivity = (ConnectivityManager) context.getSystemService(Context.CONNECTIVITY_SERVICE);
          if (connectivity != null) 
          {
              NetworkInfo[] info = connectivity.getAllNetworkInfo();
              if (info != null) 
                  for (int i = 0; i < info.length; i++) 
                      if (info[i].getState() == NetworkInfo.State.CONNECTED)
                      {
                          return true;
                      }
 
          }
          return false;
    }
	
	/*Function to setup an AlarmManager to perform job status update independently from application*/
	public void setupJobUpdateAlarm(int seconds, Context context) {
		// Creates an AlarmManager which calls OnAlarmReceive.class to handle job update
		AlarmManager alarmManager = (AlarmManager) context.getSystemService(android.content.Context.ALARM_SERVICE);
		Intent intent = new Intent(context, OnAlarmReceive.class);
		intent.putExtra("driver", driver);
		PendingIntent pendingIntent = PendingIntent.getBroadcast(
		   context, 168, intent,
		   PendingIntent.FLAG_CANCEL_CURRENT);
		 
		// Sets elapsed time for alarm to trigger
		Calendar cal = Calendar.getInstance();
		cal.add(Calendar.SECOND, seconds);
		 
		alarmManager.set(AlarmManager.RTC_WAKEUP, cal.getTimeInMillis(), pendingIntent);
	}
	
	public void addJobToTempFile(String data, Context c){
		String filePath = c.getFilesDir()+File.separator+driver+"_temp.txt";
		try {
		    PrintWriter out = new PrintWriter(new BufferedWriter(new FileWriter(filePath, true)));
		    out.print(data+"**");
		    out.close();
		} catch (IOException e) {
		}
	}
	
	
	
	/*class to handle asynchronous download of job file from server
	 *Takes in 1 variable: driverId
	 *send request to: http://www.camilus.org/HUBVersion/checkjob.php
	 *request method used: HTTPGET
	 **/
	public class RetrieveJobAsyncTask extends AsyncTask<String, Integer, Double>{
		HttpEntity jobEntity;
		ProgressDialog pdialog;
		String username = "";
		String downloadClass = "";
		
		@Override
		protected Double doInBackground(String... params) {
			// TODO Auto-generated method stub
			postData(params[0]);
			username = params[0];
			downloadClass = params[1];
			return 0.0;
		}
 
		/*Function to execute after server's authentication response
		 *1) Grabs response from server and saves file to phone.
		 *2) Read content of file
		 *	 - if content = 404, remove file
		 *   - if content contains job data, sort data to respective containers*/
		protected void onPostExecute(Double result){
			if (jobEntity != null) {
				try {
					/*Grab response from server and save file to phone.*/
					BufferedInputStream bis = new BufferedInputStream(jobEntity.getContent());
					String filePath = context.getFilesDir()+File.separator+username+".txt";
					BufferedOutputStream bos = new BufferedOutputStream(new FileOutputStream(new File(filePath)));
					int inByte;
					while((inByte = bis.read()) != -1) bos.write(inByte);
					bis.close();
					bos.close();
				} catch (IllegalStateException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				} catch (IOException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
			}
			String filedata = readFile(context).trim();
			if(filedata.trim().equals("404")){ /*404 means no job found for deliveryman*/
				removeFile(context);
			}else{
				/*sort data to respective containers*/
				sortJobs(filedata);
				prepareJobContainer();
			}
			
			if(downloadClass.equals("login")){
				/*Calls login function to proceed to Main.class*/
				login.pdLoading.dismiss();
				login.login();
			}else if(downloadClass.equals("summary")){
				/*populate Summary fragment*/
				summary.txtJobsLeft.setText(getHashmapJobsContainer().size()+"");
				int del = (getHashmapExpandableListContainer().get("delivery")==null?0:getHashmapExpandableListContainer().get("delivery").size());
				summary.txtDel.setText(del+"");
				int appt = (getHashmapExpandableListContainer().get("appointment")==null?0:getHashmapExpandableListContainer().get("appointment").size());
		        summary.txtAppt.setText(appt+"");
		        int trans = (getHashmapExpandableListContainer().get("transfer")==null?0:getHashmapExpandableListContainer().get("transfer").size());
		        summary.txtTrans.setText(trans+"");
				summary.pdLoading.dismiss();
				if(getHashmapJobsContainer().size()==0){
					AlertDialog.Builder builder = new AlertDialog.Builder(context);
    				builder.setTitle("Job Retrieval Notice");
    				builder.setMessage("You do not have any new jobs at the moment.")
    				       .setCancelable(false)
    				       .setPositiveButton("OK", new DialogInterface.OnClickListener() {
    				           public void onClick(DialogInterface dialog, int id) {
    				        	   dialog.cancel();
    				           }
    				       });
    				AlertDialog alert = builder.create();
    				alert.show();
				}
			}
		}
 

		protected void onCancelled (){
			AlertDialog.Builder builder = new AlertDialog.Builder(context);
			if(downloadClass.equals("login")){
				login.pdLoading.dismiss();
				builder.setTitle("Login Error");
			}else if(downloadClass.equals("summary")){
				summary.pdLoading.dismiss();
				builder.setTitle("Job Retrieval Error");
			}
			builder.setMessage("Server is currently busy. Please try again later.")
			       .setCancelable(false)
			       .setPositiveButton("OK", new DialogInterface.OnClickListener() {
			           public void onClick(DialogInterface dialog, int id) {
			        	   
			           }
			       });
			AlertDialog alert = builder.create();
			alert.show();
		}
		
		protected void postData(String user) {
			HttpParams httpParams = new BasicHttpParams();
			HttpConnectionParams.setConnectionTimeout(httpParams, 5000);
			HttpConnectionParams.setSoTimeout(httpParams, 5000);
			
			HttpClient httpclient = new DefaultHttpClient(httpParams);
			HttpGet httpget = new HttpGet("http://www.camilus.org/HUBVersion/checkjob.php?id="+user);
			try {	 
				/*Executes HTTPGET request and retrieve server's response*/
				HttpResponse jobResponse = httpclient.execute(httpget);
				jobEntity = jobResponse.getEntity();
				
			} catch (ConnectTimeoutException e){
                cancel(true);
			} catch (SocketTimeoutException e){
                cancel(true);
			}catch (ClientProtocolException e) {
				// TODO Auto-generated catch block
			} catch (IOException e) {
				// TODO Auto-generated catch block
			}
		}
 
	}
}
