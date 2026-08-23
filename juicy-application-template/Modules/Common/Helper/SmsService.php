<?php

namespace Modules\Common\Helper;

use Exception;
use Illuminate\Support\Facades\Http;

class SmsService
 {
    var $host = 'api.rmlconnect.net' ;
    var $port = '2351';
    /*
    * Username that is to be used for submission
    */
    var $strUserName = 'LozWaMoz';
    /*
    * password that is to be used along with username 
    */
    var $strPassword= 'K1v[_2xV';
    /*
    * Sender Id to be used for submitting the message
    */
    var $strSender = 'LozWeMoz';
    /*
    * Message content that is to be transmitted 
    */
    var $strMessage;
    /*
    * Mobile No is to be transmitted. 
    */
    var $strMobile;

    /*
    * What type of the message that is to be sent 
    * <ul>
    * <li>0:means plain text</li> 
    * <li>1:means flash</li>
    * <li>2:means Unicode (Message content should be in Hex)</li>
    * <li>6:means Unicode Flash (Message content should be in Hex)</li>
    * </ul>
    */
    var $strMessageType=0;
    /*
    * Require DLR or not *
    <ul>
    * <li>0:means DLR is not Required</li>
    * <li>1:means DLR is Required</li>
    * </ul> 
    */
    var $strDlr=1;
    
    //Constructor..
    public function __construct ($message,$mobile){
        $this->strMessage=$message; //URL Encode The Message.. 
        $this->strMobile=$mobile;
    }

    
    public function Submit(){
        $this->strMessage=urlencode($this->strMessage);
        
        try{
            $apiURL = "http://".$this->host."/bulksms/bulksms";
            $parameters = ['username' => $this->strUserName,'password'=>$this->strPassword,'type'=>0,'dlr'=>1,'destination'=>$this->strMobile,'source'=>$this->strSender,'message'=>$this->strMessage];
            return $response = Http::get($apiURL, $parameters);
            $statusCode = $response->status();
            $responseBody = json_decode($response->getBody(), true);

        }catch(Exception $e){
        echo 'Message:' .$e->getMessage(); 
        }
        
    } 
}

?>