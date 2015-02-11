<?php

/**
 * This command fetch exchange rates from: AMD from CBA.am and others from webservicesx.com sources.
 * Results are storing in the database table named curencies.
 * For cronJob: php /home/lanlook/www/cmd.php GetRatesFromWebService
 * Local Examlpe: Z:\usr\local\php5\php cmd.php GetRatesFromWebService
 * @author armos Armen Bablanyan @2012
 *
 */
class GetRatesFromWebServiceCommand extends CConsoleCommand
{	
    public function run($args)
    {
        if(isset($args[0]) && $args[0]=='stop'){
        	Yii::log('Fetching exchange rates from remote Source is DISABLED', 'warning',  'application.commands.GetRatesFromWebService');
        	return;
        }
        
        echo "\nPlease wait...\n";
        
        //$amd_rate = $this->parse_rss_cba($args[0]);
        $amd_rate = $this->parse_soap_cba();

        
        $controller = new Controller("SiteConsole");
        return $controller->transact(function() use($amd_rate) {
        	try {

		        $c = new CDbCriteria();
		        $c->select = "t.curency_id, t.curency_code";
		        $c->condition = "lng_id = :lng AND curency_status=:stat";
		        $c->params = array(':lng'=>'en_us', ':stat'=>1);
		        $tmp_arr = Curencies::model()->findAll($c);
		        
		        $soapClient = new SoapClient("http://www.webservicex.net/CurrencyConvertor.asmx?WSDL");
		        
		        //========= FETCH INFORMATION ABOUT RATES FROM REMOTE SOURCE ==========//
				foreach ($tmp_arr as $curency){
					$cid = $curency["curency_id"];
					$currency_rate = 0;
					$currency_date = '';
					
					if ($curency["curency_code"]=='AMD'){

						if($amd_rate and is_array($amd_rate)) {
// 							$currency_date = date('Y-m-d H:i:s', strtotime($amd_rate[0]["rate_date"]));
							$currency_date = date('Y-m-d H:i:s');
							$currency_rate = round($amd_rate[1]["USD"], 3, PHP_ROUND_HALF_UP);
						} else
							continue;			
					}
					elseif($curency["curency_code"]=='USD'){
						$currency_date = date('Y-m-d H:i:s');
						$currency_rate = 1.000;
					}
					else
					{
						try {
			    			$res = $soapClient->ConversionRate(array("FromCurrency"=>"USD","ToCurrency"=>"{$curency["curency_code"]}"));
						} catch (SoapFault $fault) {
							Yii::log("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", 'info',  'application.commands.GetRatesFromWebService');
			    			continue;
						}
						$currency_date = date('Y-m-d H:i:s');
						$currency_rate = $res->ConversionRateResult;
					}
							
					if ((Curencies::model()->updateAll(array("curency_value"=>$currency_rate,'modified_date'=>$currency_date), "curency_id=:cid AND curency_status=:stat", array(':cid'=>$cid, ':stat'=>1)))!=(Curencies::model()->countBySql("SELECT COUNT(DISTINCT `lng_id`) FROM `curencies` WHERE `curency_status`=:stato", array(':stato'=>1))))
						throw new CException("Unacceptable count of updates.", 111);
				}
        	}catch (Exception $e) {
				echo "\nUpdate Error: ".$e->getMessage();
				return false;
			}
			return true;
		});		
    }
    
	/**
	 * Getting RSS from CENTRAL BANK OF ARMENIA and parse into array
	 * @param $timeout integer
	 * @return array of exchange rates in armenian drams
	 */
	protected function parse_rss_cba($timeout = 5 /*Timeout for service request*/ ){

		///	SET TIMEOUT FOR CONNECTION WITH cba.am	///
			$url = "http://www.cba.am/CBA_SITE/rss.xml?__locale=en";

			$ch = curl_init(); // get cURL handle

			$opts = array(CURLOPT_RETURNTRANSFER => true, 				// do not output to browser
	                               CURLOPT_URL => $url,            		// set URL
								// CURLOPT_NOBODY => true,       		// do a HEAD request only
								   CURLOPT_TIMEOUT => $timeout,			// set timeout
	                            // CURLOPT_PROXY => "IP:10.210.13.242",			// set proxy server and port
	                            // CURLOPT_PROXYUSERPWD => "user:pass"	// set details for proxy server
	                            );

	        curl_setopt_array($ch, $opts);
			$file = curl_exec($ch);

	        $retval = (curl_getinfo($ch, CURLINFO_HTTP_CODE) == 200); // check if HTTP OK

	        curl_close($ch); // close handle

	    if ($retval && strlen($file)>10){

			///	IF THERE IS EXISTING XML FILE PARSE XML BY DOM	///
			$xml = new DOMDocument();
			
			if(!$xml->loadXML($file)){
				Yii::log("CBA XML Error: File not loaded.", 'warning',  'application.commands.GetRatesFromWebService');
				return false;
			}
			$channel = $xml->getElementsByTagName('channel');
			if($channel->length>0){
				$item = $channel->item(1);
			}else{	
				Yii::log("CBA XML Error: Channel tag is missing.", 'warning',  'application.commands.GetRatesFromWebService');
				return false;
			}
			$title = $item->getElementsByTagName('category');
			if($title->length>0){
				$title = $title->item(0)->nodeValue;
			}else{
				Yii::log("CBA XML Error: Category tag is missing.", 'warning',  'application.commands.GetRatesFromWebService');
				return false;
			}
			$items = $item->getElementsByTagName('item');
			if($items->length>0){
				$item = $items->item(0);
			}else{
				Yii::log("CBA XML Error: Item tag is missing.", 'warning',  'application.commands.GetRatesFromWebService');
				return false;
			}
			$content = $item->getElementsByTagName('description');
			if($content->length>0){
				$content = $content->item(0)->nodeValue;
			}else{
				Yii::log("CBA XML Error: Description tag is missing.", 'warning',  'application.commands.GetRatesFromWebService');
				return false;
			}
			
			$first = explode('USD: 1 ',$content);
			$usd = explode('GBP: 1 ',$first[1]);
			$gbp = explode('EUR: 1 ',$usd[1]);
			$eur_rus = explode('RUB: 1 ',$gbp[1]);
			
			$dates = $item->getElementsByTagName('pubDate');
			if($dates->length>0){
				$date = $dates->item(0)->nodeValue;
			}else{
				Yii::log("CBA XML Error: pubDate tag is missing.", 'warning',  'application.commands.GetRatesFromWebService');
				return false;
			}
			
			///	INITIALIZED ARRAY CONTEINING ALL DATA OF EXCHANGE RATES ///
			$rates = array("0"=>array("Title"=>$title,"Date"=>$date),"1"=>array("USD"=>round($usd[0],3),"GBP"=>round($gbp[0],3),"EUR"=>round($eur_rus[0],3),"RUB"=>round($eur_rus[1],3)));
		}else
			$rates = array("0"=>array("Title"=>"Exchange Rates","Date"=>date('d.m.Y')),"1"=>array("USD"=>0,"GBP"=>0,"EUR"=>0,"RUB"=>0));
		// Yii::log("USD: ".$rates[1]["USD"], 'info',  'application.commands.GetRatesFromWebService');	
	return $rates;		
	}
	
	/**
	 * Call soap web service of CBA and get current exchange rates
	 * Returns array with rate and rate date
	 */
	public function parse_soap_cba()
	{
		$soapClient = new SoapClient("http://api.cba.am/exchangerates.asmx?WSDL");
		
		try {
	    	$res = $soapClient->ExchangeRatesLatestByISO(array("ISO"=>"USD"));
			}
		catch (SoapFault $fault) {
			Yii::log("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", 'info',  'application.commands.GetRatesFromWebService');
	    	continue;
			}
		$currency_rate = $res->ExchangeRatesLatestByISOResult;
		
		if ($currency_rate && is_object($currency_rate)){
			return array("0"=>array("rate_date"=>$currency_rate->CurrentDate), "1"=>array($currency_rate->Rates->ExchangeRate->ISO=>$currency_rate->Rates->ExchangeRate->Rate));
		}else {
			return false;
		}
	}
 
}