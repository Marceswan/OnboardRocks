<?php

namespace Onboard\Model;

class Model
{
    private $obpropapiurl, $obpropapikey;

    /**
     * When creating the model, the configs for api connection are needed
     * @param $config
     */
    function __construct($config)
    {
        $this->obpropapiurl = $config['api_url'];
	$this->obpropapikey = $config['api_key'];
	
    }
    
    private function curlOnboardAPI($url)
    {
	$curl = curl_init();
	
	curl_setopt_array($curl, array(
	CURLOPT_URL => $url,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "GET",
	CURLOPT_HTTPHEADER => array(
	  "accept: application/json",
	  "apikey: " . $this->obpropapikey
	),
	));
      
	$response = curl_exec($curl);
	$err = curl_error($curl);
	curl_close($curl);
    
	if ($err) {
	    return '{"status": { "code": 999, "msg": "cURL Error #:" . $err."}}';
	  } else {
	    return json_decode($response, true);
	  }
    }
    /**
     * Property Search by Address
     * Return summary of properties within a radius of an address
     * @param $address1, $address2, $radius
     * @return JSON
     */
    public function searchPropertyByAddress($address1, $address2, $radius, $page)
    {
	$address1 = urlencode($address1);
	$address2 = urlencode($address2);
  
	$url = $this->obpropapiurl . "property/snapshot?address1=" . $address1 . "&address2=" . $address2 . "&radius=" . $radius . "&page=" . $page . "&orderBy=distance%20asc";
    
	return $this->curlOnboardAPI($url);  
    }
    
    /**
     * Get Property Sales History by Onboard Property ID
     * Return all sales history records of a single property
     * @param $propertyid
     * @return JSON
     */
    public function getSalesHistory($propertyid)
    {
        $url = $this->obpropapiurl . "saleshistory/snapshot?id=" . $propertyid;
	    
	return $this->curlOnboardAPI($url); 
    }
    
    /**
     * Get Assessment by Onboard Property ID
     * Return county tax assessment record of a single property
     * @param $propertyid
     * @return JSON
     */
    public function getAssessment($propertyid)
    {
        $url = $this->obpropapiurl . "assessment/snapshot?id=" . $propertyid;
	    
	return $this->curlOnboardAPI($url);  
    }
    
    /**
     * Get AVM by Onboard Property ID
     * Return avm record of a single property
     * @param $propertyid
     * @return JSON
     */
    public function getAVM($propertyid)
    {
        $url = $this->obpropapiurl . "avm/snapshot?id=" . $propertyid;
	    
	return $this->curlOnboardAPI($url); 
    }
}
