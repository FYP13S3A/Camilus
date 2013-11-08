package ss133a.mobile.camilus;

import java.io.BufferedInputStream;
import java.io.BufferedOutputStream;
import java.io.BufferedReader;
import java.io.File;
import java.io.FileOutputStream;
import java.io.FileReader;
import java.io.IOException;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.DefaultHttpClient;

import android.os.AsyncTask;

public class JobsManager {
	HashMap<String, List<String>> hashmapDataContainer;
	List<String> listJobHeader, listJobHeader2;
	List<List<String>> listJobChilds;
	boolean prepareContainer;
	
	public JobsManager(){
		hashmapDataContainer = new HashMap<String, List<String>>();
		listJobHeader = new ArrayList<String>();
		listJobHeader2 = new ArrayList<String>();
		listJobChilds = new ArrayList<List<String>>();
		prepareContainer = false;
		listJobHeader.add("delivery"); //delivery header
		listJobHeader.add("collection"); //collection header
		listJobHeader.add("transfer"); //transfer header
		listJobChilds.add(new ArrayList<String>()); //store delivery children
		listJobChilds.add(new ArrayList<String>()); //store collection children
		listJobChilds.add(new ArrayList<String>()); //store transfer children
	}
	
	public HashMap<String, List<String>> getDataContainer(){
		return hashmapDataContainer;
	}
	
	public List<String> getJobHeader(){
		return listJobHeader2;
	}
	
	public List<List<String>> getJobChilds(){
		return listJobChilds;
	}
	
	public JobsManager(HashMap<String, List<String>> hashmapDataContainer,List<String> listJobHeader, List<List<String>> listJobChilds){
		this.listJobHeader = listJobHeader;
		this.hashmapDataContainer = hashmapDataContainer;
		this.listJobChilds = listJobChilds;
	}
	
	public void downloadFile(String username){
		String filePath = File.separator+"storage"+File.separator+"sdcard0"+File.separator+"job.txt";
		File file = new File(filePath);
		if(file.exists()==false){
			new RetrieveJobAsyncTask().execute(username);
		}
	}
	
	public String readFile(){
		String filedata = "";
		String filePath = File.separator+"storage"+File.separator+"sdcard0"+File.separator+"job.txt";
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
	
	public void sortJobs(String file){
		String[] jobs = file.split("\\*\\*");
		for(int i=0;i<jobs.length;i++){
			System.out.println("sqsqsqsq: "+i+": "+jobs[i]);
		}
		for(int i=0;i<jobs.length;i++){
			String[] jobdetails = jobs[i].split("\\|");
			if(jobdetails[0].trim()!=""){
				System.out.println("sqsq:"+jobdetails[0]);
				switch (jobType.valueOf(jobdetails[0])){
					case delivery:
						listJobChilds.get(0).add(jobdetails[5]);
						break;
					case collection:
						listJobChilds.get(1).add(jobdetails[5]);
						break;
					case transfer:
						listJobChilds.get(2).add(jobdetails[5]);
						break;
					default:
						break;
				}
			}
		}
	}
	
	public void prepareJobContainer(){
		if(prepareContainer==false){
			System.out.println("quanquan: "+listJobHeader.size());
			for(int i=0;i<listJobHeader.size();i++){
				if(listJobChilds.get(i).size()>0){
					System.out.println(listJobHeader.get(i)+" quanquan:"+listJobChilds.get(i).size());
					hashmapDataContainer.put(listJobHeader.get(i),listJobChilds.get(i));
					listJobHeader2.add(listJobHeader.get(i));
				}
			}
			prepareContainer=true;
		}
	}
	
	public enum jobType{
	     delivery,collection,transfer; 
	 }
	
	public class RetrieveJobAsyncTask extends AsyncTask<String, Integer, Double>{
		HttpEntity jobEntity;
		@Override
		protected Double doInBackground(String... params) {
			// TODO Auto-generated method stub
			postData(params[0]);
			return null;
		}
 
		//function to execute after server's authentication response
		protected void onPostExecute(Double result){
			//pbLogin.setVisibility(View.GONE);
			if (jobEntity != null) {
				try {
					BufferedInputStream bis = new BufferedInputStream(jobEntity.getContent());
					//String filePath = getFilesDir()+File.separator+"jobs.txt";
					String filePath = File.separator+"storage"+File.separator+"sdcard0"+File.separator+"job.txt";
					//Toast.makeText(getApplicationContext(), filePath, Toast.LENGTH_LONG).show();
					BufferedOutputStream bos = new BufferedOutputStream(new FileOutputStream(new File(filePath)));
					int inByte;
					while((inByte = bis.read()) != -1) bos.write(inByte);
					bis.close();
					bos.close();
					//pbLogin.setVisibility(View.GONE);
					//login(loginView);
				} catch (IllegalStateException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				} catch (IOException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
			}else{
				//Toast.makeText(getApplicationContext(), "unable to retrieve file", Toast.LENGTH_LONG).show();
			}
			
		}
		
		protected void onProgressUpdate(Integer... progress){
			//pbLogin.setProgress(progress[0]);
		}
 
		//function to handle data post to server
		protected void postData(String user) {
			HttpClient httpclient = new DefaultHttpClient();
			//HttpPost httppost = new HttpPost("http://www.efxmarket.com/mobile/checkjob.php");
			HttpGet httpget = new HttpGet("http://www.efxmarket.com/HUBVersion/checkjob.php?id="+user);
			try {	 
				//execute httpget request and retrieve server's response
				HttpResponse jobResponse = httpclient.execute(httpget);
				jobEntity = jobResponse.getEntity();
				
			} catch (ClientProtocolException e) {
				// TODO Auto-generated catch block
			} catch (IOException e) {
				// TODO Auto-generated catch block
			}
		}
 
	}

}
