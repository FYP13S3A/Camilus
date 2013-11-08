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
import java.io.Serializable;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.concurrent.ExecutionException;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.DefaultHttpClient;

import android.app.ProgressDialog;
import android.content.Context;
import android.os.AsyncTask;
import android.os.Parcel;
import android.os.Parcelable;
import android.view.View;

public class JobsManager{
	HashMap<String, List<String>> hashmapExpandableListContainer;
	HashMap<String, String> hashmapJobsContainer;
	List<String> listJobHeader, listJobHeader2;
	List<List<String>> listJobChilds;
	boolean prepareContainer;
	Login login;
	String driver;
	
	public JobsManager(String driver){
		hashmapExpandableListContainer = new HashMap<String, List<String>>();
		hashmapJobsContainer = new HashMap<String, String>();
		listJobHeader = new ArrayList<String>(); //listjobheader is to create the 3 headers for storing of all the children
		listJobHeader2 = new ArrayList<String>(); //listjobheader2 is to create necessary header inside expandable list
		listJobChilds = new ArrayList<List<String>>();
		prepareContainer= false;
		this.driver = driver;
	}
	
	public JobsManager(HashMap<String, List<String>> hashmapExpandableListContainer, HashMap<String, String> hashmapJobsContainer,List<String> listJobHeader,
			List<String> listJobHeader2, List<List<String>> listJobChilds, boolean prepareContainer){
		this.hashmapExpandableListContainer = hashmapExpandableListContainer;
		this.hashmapJobsContainer = hashmapJobsContainer;
		this.listJobHeader = listJobHeader;
		this.listJobHeader2 = listJobHeader2;
		this.listJobChilds = listJobChilds;
		this.prepareContainer = prepareContainer;
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
	
	public boolean getPrepareContainer(){
		return prepareContainer;
	}
	
	public void setPoolContainer(boolean boolContainer){
		this.prepareContainer = boolContainer;
	}
	
	public String getDriver(){
		return driver;
	}
	
	public void setDriver(String driver){
		this.driver = driver;
	}
	
	public void addHeaderChildren(){
		listJobHeader.add("delivery"); //delivery header
		listJobHeader.add("appointment"); //appointment header
		listJobHeader.add("transfer"); //transfer header
		listJobChilds.add(new ArrayList<String>()); //store delivery children
		listJobChilds.add(new ArrayList<String>()); //store collection children
		listJobChilds.add(new ArrayList<String>()); //store transfer children
	}
	
	public boolean checkFileExist(){
		String filePath = File.separator+"storage"+File.separator+"sdcard0"+File.separator+driver+".txt";
		File file = new File(filePath);
		return file.exists();
	}
	
	public void downloadFile(Login l){
		System.out.println("sq: "+"5. executing jm.downloadfile, "+driver);
		login = l;
		new RetrieveJobAsyncTask().execute(driver);
	}
	
	public String readFile(){
		System.out.println("sq: "+"12. read file");
		String filedata = "";
		String filePath = File.separator+"storage"+File.separator+"sdcard0"+File.separator+driver+".txt";
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

		System.out.println("sq: "+"12. read file finish");
		return filedata;
	}
	
	public void removeFile(){
		String filePath = File.separator+"storage"+File.separator+"sdcard0"+File.separator+driver+".txt";
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
	
	public void sortJobs(String file){
		System.out.println("sq: "+"13. sort file");
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
		System.out.println("sq: "+"13. sort file finish");
	}
	
	public void prepareJobContainer(){
		if(prepareContainer==false){
			for(int i=0;i<listJobHeader.size();i++){
				if(listJobChilds.get(i).size()>0){
					hashmapExpandableListContainer.put(listJobHeader.get(i),listJobChilds.get(i));
					listJobHeader2.add(listJobHeader.get(i));
				}
			}
			prepareContainer=true;
		}
	}
	
	public void removeJob(int groupPos, int childPos, String manifestId){
		removeJobFromExpandableList(groupPos, childPos);
		removeJobFromJobContainer(manifestId);
		removeJobFromFile();
	}
	
	public void removeJobFromExpandableList(int groupPos, int childPos){
		JobsExpandableAdapter jobAdapt = Jobs.jobAdapter;
		List<String> child = getHashmapExpandableListContainer().get(getListJobHeader2().get(groupPos));
		child.remove(childPos);
		if(child.size()==0){
			getListJobHeader2().remove(groupPos);
		}
		jobAdapt.notifyDataSetChanged();
	}
	
	public void removeJobFromJobContainer(String manifestId){
		hashmapJobsContainer.remove(manifestId);
	}
	
	public void removeJobFromFile(){
		String filePath = File.separator+"storage"+File.separator+"sdcard0"+File.separator+driver+".txt";
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
	
	public class RetrieveJobAsyncTask extends AsyncTask<String, Integer, Double>{
		HttpEntity jobEntity;
		ProgressDialog pdialog;
		String username = "";
		
		@Override
		protected Double doInBackground(String... params) {
			// TODO Auto-generated method stub
			System.out.println("sq: "+"6. executing jm.retrievejob.postdata");
			postData(params[0]);
			username = params[0];
			return 0.0;
		}
 
		//function to execute after server's authentication response
		protected void onPostExecute(Double result){
			System.out.println("sq: "+"7. executing jm.retrievejob.postexecute");
			if (jobEntity != null) {
				try {
					BufferedInputStream bis = new BufferedInputStream(jobEntity.getContent());
					String filePath = File.separator+"storage"+File.separator+"sdcard0"+File.separator+username+".txt";
					BufferedOutputStream bos = new BufferedOutputStream(new FileOutputStream(new File(filePath)));
					int inByte;
					while((inByte = bis.read()) != -1) bos.write(inByte);
					bis.close();
					bos.close();
					//pdialog.dismiss();
				} catch (IllegalStateException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				} catch (IOException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
			}else{
			}
			System.out.println("sq: "+"8. finish jm.retrievejob.postexecute");
			login.pbLogin.setVisibility(View.GONE);
			System.out.println("sq: "+"9. going to main page");
			login.login();
		}
		
		/*protected void onProgressUpdate(Integer... progress){
			//pbLogin.setProgress(progress[0]);
		}*/
 
		//function to handle data post to server
		protected void postData(String user) {
			System.out.println("sq: "+"6. executing jm.retrievejob.postdata.A, "+user);
			HttpClient httpclient = new DefaultHttpClient();
			System.out.println("sq: "+"6. executing jm.retrievejob.postdata.B");
			HttpGet httpget = new HttpGet("http://www.efxmarket.com/HUBVersion/checkjob.php?id="+user);
			try {	 
				//execute httpget request and retrieve server's response
				HttpResponse jobResponse = httpclient.execute(httpget);
				System.out.println("sq: "+"6. executing jm.retrievejob.postdata.C");
				jobEntity = jobResponse.getEntity();
				System.out.println("sq: "+"6. executing jm.retrievejob.postdata.D");
				
			} catch (ClientProtocolException e) {
				// TODO Auto-generated catch block
			} catch (IOException e) {
				// TODO Auto-generated catch block
			}
			System.out.println("sq: "+"6. executing jm.retrievejob.postdata.E");
		}
 
	}
}
