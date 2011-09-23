<?php

/*
medlinePlusApi
Ronaldo Barbachano
Sept-2011

MedlinePlus is a simple api with three methods. Currently the 'get_lab_test' method
does not function as expected.. probably due to some php-curl issues.. but does work via an
exec() command to return (a mostly useful response) I will be contacting medline shortly about this issue.
All methods support output in english and spanish, and extra parameters like titles etc..

Be sure to escape spaces for all search criteria fields lest face a server error.

All methods return raw xml.

Otherwise everything else works as explained on the developer reference page.
http://www.nlm.nih.gov/medlineplus/connect/service.html


*/


if(!class_exists('APIBaseClass'))	require('APIBaseClass.php');
class medlinePlusApi extends APIBaseClass{

// This api only supports XML
   public static $api_url = 'http://apps.nlm.nih.gov/medlineplus/services/mpconnect_service.cfm';

   public function __construct($url=NULL,$outputType=NULL)
        {
	        if($outputType == NULL) $this->output_type = 'xml';

                parent::new_request(($url?$url:self::$api_url));
		}
        public function setOutputType($type){
                if($type != $this->output_type)
                        $this->output_type = ($type != 'xml'?'json':'xml');
        }

        public function get_diagnosis($code,$type='ICD-9-CM',$critera=NULL,$language='en'){
        // also supports 'sp' for spanish ... returns a nice summary etc in xml with info and links
        
        	if($type == 'ICD-9-CM'){
        	// supports the ICD-9-CM ... but there are two other services that are undefined...
        		$request = "?mainSearchCriteria.v.cs=2.16.840.1.113883.6.103";
        	}elseif($type== 'SNOMED'){
        		$request = "?mainSearchCriteria.v.cs=2.16.840.1.113883.6.96";
        	}elseif($type == '2'){
        	// not sure what this is for.. undocumented..
        		$request = "?mainSearchCriteria.v.cs=2.16.840.1.113883.6.2";
        	}elseif($type == '42'){
        	// ditto
        		$request = "?mainSearchCriteria.v.cs=2.16.840.1.113883.6.42";
        	}
        	
        	$request .= "&mainSearchCriteria.v.c=$code";

        	if($critera !=NULL)$request .= "&mainSearchCriteria.v.dn=$critera";

        	if($language != 'en')$request .= "&informationRecipient.languageCode.c=sp";

        	
        	 return self::_request($request,'GET');
        
        }
        
        public function get_drug_info($id,$drug_name =NULL,$type='rxcui'){
        // only returns a link to the matching records.. far from ideal ....
        	if($type=='rxcui')
        		$request = '?mainSearchCriteria.v.cs=2.16.840.1.113883.6.88';
        	else
        	// FOR NDCS
        		$request = '?mainSearchCriteria.v.cs=2.16.840.1.113883.6.69';
        	if($drug_name != NULL)	
        		$request .= "&mainSearchCriteria.v.dn=$drug_name";
        	elseif($id)
        		$request .= "&mainSearchCriteria.v.c=$id";
        	 return self::_request($request,'GET');
        }
        
        public function get_lab_test($code,$title=NULL,$system='LOINC',$language='en'){
        
        	if($system == 'LOINC'){
        
        		$result = "?mainSearchCriteria.v.cs=2.16.840.1.113883.6.1";
        		
        	}else{
        		$result = "?mainSearchCriteria.v.cs=2.16.840.1.113883.11.79";
        	}
        
	        $result .= "&mainSearchCriteria.v.c=$code";
	        
	        if($title != NULL || trim($title) != ''){
	        	$result .= "&mainSearchCriteria.v.dn=$title";
	        }
	        
	        if($language != 'en'){
	        	$result .= "&informationRecipient.languageCode.c=sp";
	        }else{
	        	$result .= "&informationRecipient.languageCode.c=en";
	        
	        }
		// weird bug in this API that always returns a non LONIC result even if the url is appropriate...
			$execute = "curl -X GET 'http://apps.nlm.nih.gov/medlineplus/services/mpconnect_service.cfm$result' -s";
			exec($execute,$output);
			$output = implode($output,'');
			// this needs some serious work.. probably a php_curl problem...
			return $output;
	//		$xml_result = new SimpleXMLElement($output);
			
			}
}





