<?php

/**
 * For Batch Management of products.
 * Usage: php cmd BatchProductManagement (string operation(add/remove), string 'path/text.txt', int count)
 * Example: Z:\usr\local\php5\php cmd.php BatchProductManagement add input.txt
 * @author armos Armen Bablanyan @2012
 *
 */
class BatchProductManagementCommand extends CConsoleCommand
{

	public function run($args)
    {
    	$operation = '';
    	$text = '';
    	$query = '';
        if(isset($args[0]) && $args[0]!='') {
        	if (strtolower($args[0]) == '--h' || strtolower($args[0]) == '--help')
        	{
        		echo "\nCommand Usage:\n\nBatchProductManagement Option=[add][remove] [path of text file] [quantity]\n\n[add]\t\tAdd new product from mentioned text file.\n\n[remove]\tRemove all products which ids are mentioned in text file.\n\n[quantity]\tNumber of products to be added for each category.\n";
        		return true;
        	} 
        	elseif(strtolower($args[0]) == 'add' || strtolower($args[0]) == 'remove')
        	{
        		$operation = $args[0];
        		$controller = new Controller('SiteConsole');
        		
        		switch ($operation) {
        			case 'add':
		        		if(isset($args[1]) && is_file($args[1])) {
							$f = fopen($args[1], 'r') or exit('Unable to open mentioned file.');
							while ( !feof($f) )
							{
								$text .= fgets($f);
							}
							fclose($f);
				        	$text_arr = explode('$=$', $text);

				        	$product_ids = array();
							
				        	foreach ($text_arr as $announce)
				        	{
				        		$announce_arr = explode('#=#', trim($announce));
				        		$categories = array();
				        		$images = array();
				        		
				        		
				        		if ( trim($announce_arr[0]) == 0 ) {
				        			//Get all ids of original (without children) categories: $categories
				        			$c = new CDbCriteria ();
				        			$c->select = 't.category_id';
									$c->condition = 't.category_id NOT IN (SELECT `parent_id` FROM `category_structure`) AND `category_status` > :stat';
									$c->params = array (':stat' => '1');
									$virginCategories = CategoryStructure::model ()->findAll ( $c );
		
									if(is_array($virginCategories) && count($virginCategories)>0) {
										foreach ($virginCategories as $cat) {
											$categories[] = $cat['category_id'];
										}
									}
				        		} elseif ( preg_match('/;/', trim($announce_arr[0])) ) {
				        			$categories = explode(';', trim($announce_arr[0]));
				        		} elseif (is_numeric(trim($announce_arr[0])) && trim($announce_arr[0])>0) {
				        			$categories = array(trim($announce_arr[0]));
				        		} else {
				        			echo "\nIncorrect file format:\nPlease check first parameter within file. Must be set as category ID.\n";
				        			return false;
				        		}
				        		
				        		$quantity = (isset($announce_arr[1]))?trim($announce_arr[1]):0;
				        		$exp_date = (isset($announce_arr[2]))?trim($announce_arr[2]):date('Y-m-d H:i:s');
				        		$status = (isset($announce_arr[3]))?trim($announce_arr[3]):2;
				        		
				        		if ( preg_match('/;/', trim($announce_arr[5])) )
				        		{
				        			$images = explode(';', trim($announce_arr[5]));
				        		} elseif (isset($announce_arr[5])) {
									$images = array(trim($announce_arr[5]));
				        		} else {
				        			echo "\nIncorrect file format:\nPlease check third parameter within file. Must be set as image file name.\n";
				        			return false;
				        		}
				        		
				        		foreach ($categories as $k=>$category_id) {
				        			//Do insert  in the table product_structure by $category_id and ...
				        			$new_prodStruct = new ProductStructure();
				        			$transaction = $new_prodStruct->model()->dbConnection->beginTransaction();
				        			
				        			try {
				        			
					        			$new_prodStruct->category_id = $category_id;
					        			$new_prodStruct->quantity = $quantity;
					        			$new_prodStruct->created_date = date('Y-m-d H:i:s');
					        			$new_prodStruct->expire_date = $exp_date;
					        			$new_prodStruct->product_status = $status;
					        			$new_prodStruct->save();
					        			
					        			$full_desc = explode('@#@', $announce_arr[4]);
					        			
					        			$c = new CDbCriteria ();
					        			$c->select = 'lng_id';
					        			$c->condition = 'lng_status=:stat';
					        			$c->params = array(':stat'=>2);
					        			$languages = Languages::model()->findAll( $c );
					        			
					        			if (is_array($languages) && count($languages)>0) {
					        				foreach ($languages as $key=>$lng) {
												//Insert data to table product_details by received bellow product_id											
					        					$new_prodDetails = new ProductDetails();
						        				$new_prodDetails->product_id = $new_prodStruct->product_id;
						        				$new_prodDetails->lng_id = $lng->lng_id;
						        				$new_prodDetails->product_name = (isset($full_desc[$key]))?$controller->multiSubString( $full_desc[$key], 20, 'l' ):'Tested';
						        				$new_prodDetails->full_description = (isset($full_desc[$key]))?$full_desc[$key]:'Tested';
						        				$new_prodDetails->seo_slag = (isset($full_desc[$key]))?$controller->multiSubString( $full_desc[$key], 30, 'l' ):'Tested';
						        				$new_prodDetails->page_title = (isset($full_desc[$key]))?$controller->multiSubString( $full_desc[$key], 20, 'l' ):'Tested';
						        				$new_prodDetails->keywords = $controller->makeKeyWords( $controller->multiSubString( $full_desc[$key], 70, 'l' ) );
						        				$new_prodDetails->description = (isset($full_desc[$key]))?$controller->multiSubString( $full_desc[$key], 70, 'l' ):'Tested';
						        				$new_prodDetails->modified_date = date('Y-m-d H:i:s');
						        				$new_prodDetails->user_id = 1;
						        					
						        				if(!$new_prodDetails->save())
						        					throw new CException('ProdDetails saving transaction failed: ');
					        				}
					        			} 
					        			
					        			foreach ($images as $image)
					        			{
					        				//Insert into table product_files with $product_id and $image
					        				
					        				if (is_file($image)) { 
					        					
					        					$new_imageFile = new ProductFiles();
					        					$new_imageFile->product_id = $new_prodStruct->product_id;
					        					$new_imageFile->file_name = CHtml::encode(trim($image));
					        					$new_imageFile->file_content = '';
					        					$new_imageFile->file_description = CHtml::encode(trim($image));
					        					$new_imageFile->file_status = 1;
					        					
					        					if(!$new_imageFile->save())
					        						throw new CException('ProdFiles saving transaction failed: ');
					        				} else {
					        					throw new CException("\nIncorrect image file:\n[{$image}] must be existing image file.\n");
					        				}
					        			}
					        			$transaction->commit();
					        			$product_ids[] = $new_prodStruct->product_id;
					        			
				        			} catch (Exception $e) {
									    $transaction->rollback();
									    echo $e->getMessage();
									    return false;
									}
				        		}
				        	}
				        	echo "\nSuccess Added:\n".implode(',', $product_ids);
				        	Yii::log($operation.':'.implode(',', $product_ids), 'info', 'application.BatchManagement');
		        		}
				        else 
				        {
				        	echo "\nInput parameters error:\nFile name argument (second parameter) must be path of an existing text file.\n";
				        	return false;
				        }
					break;
        			case 'remove':
        				if(isset($args[1]) && is_string($args[1])) {
        					$ids = explode(',', trim($args[1]));

        					return $controller->transact(function() use($ids) {
        						try {
	        						$del_files = ProductFiles::model()->deleteAllByAttributes(array('product_id'=>$ids));
	        							echo "\nNumber of rows deleted from table product_files: {$del_files}\n";
	        						$del_details = ProductDetails::model()->deleteAllByAttributes(array('product_id'=>$ids));
	        							echo "\nNumber of rows deleted from table product_details: {$del_details}\n";
	        						$del_stuct = ProductStructure::model()->deleteAllByAttributes(array('product_id'=>$ids));
	        							echo "\nNumber of rows deleted from table product_structure: {$del_stuct}\n";
        							return true;
        						} catch (CDbException $e) {
        							echo "\nDeletion Error: ".$e->getMessage();
        							return false;
        						}
        					});
        					
        					Yii::log($operation.':'.implode(',', $ids), 'info', 'application.BatchManagement');
        				}
        			break;	
        		}        
        	}
        	else 
        	{
        		echo "\nInput parameters error:\nOption argument (first parameter) must be [add] or [remove].\n";
        		return false;
        	}
        }
        
		
    }
}
