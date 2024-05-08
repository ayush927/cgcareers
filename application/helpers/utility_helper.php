<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	function backdoor( $pageName ){
		if( ! isset($_SESSION['isLoggedIn']) ){
			redirect( $pageName );
		}
	}
	
	function beforeBackdoor($pageName){
		if( isset( $_SESSION['isLoggedIn'] ) ){
			redirect( $pageName );
		}
	}


	function make_thumb( $src, $dest, $desired_width , $desired_height = null) {

    /* read the source image */
    $source_image = imagecreatefromjpeg($src);
    $width = imagesx($source_image);
    $height = imagesy($source_image);

    /* find the "desired height" of this thumbnail, relative to the desired width  */
		if( $desired_height == null ){
			$desired_height = floor( $height * ($desired_width / $width) );
		}

		
    /* create a new, "virtual" image */
    $virtual_image = imagecreatetruecolor( $desired_width, $desired_height );
		
    /* copy source image at a resized size */
    imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
		
    /* create the physical thumbnail image to its destination */
    move_uploaded_file($virtual_image, $dest);
		pre( ['ok'] , 1 );

}

// $src="1494684586337H.jpg";
// $dest="new.jpg";
// $desired_width="200";
// make_thumb($src, $dest, $desired_width);
	// Reponse Code for reponsing
	function code($code){
    		$codes = [
				'200'=>'deleted' ,
				'201'=>'created' ,
				'203'=>'updated' ,
				'204'=>'no content' ,
				'205'=>'update remain\'s same' ,
				'206'=>'delete successfully' ,
				'301'=>'Validation Error', 
				'302'=>'Parameter Missing Or Invalid Argument' ,
				'303'=>'No Data Found' ,
				'401'=>'Invalid request' ,
				'402'=>'unsupported response type' ,
				'403'=>'invalid scope' ,
				'404'=>'temporarily unavailable' ,
				'405'=>'invalid grant' ,
				'406'=>'invalid credentials' ,
				'407'=>'invalid refresh' ,
				'408'=>'no data' ,
				'409'=>'invalid data' ,
				'410'=>'access denied' ,
				'411'=>'unauthorized' ,
				'412'=>'invalid client' ,
				'413'=>'forbidden',
				'414'=>'resource not found' ,
				'416'=>'not acceptable' ,
				'419'=>'resource exists' ,
				'420'=>'conflict' ,
				'425'=>'resource gone' ,
				'445'=>'payload too large' ,
				'455'=>'unsupported media type' ,
				'499'=>'too many requests' ,
				'500'=>'server error' ,
				'501'=>'unsupported grant type' ,
				'502'=>'not implemented' ,
				'503'=>'Image Not Uploaded'
    	];
    	return $codes[$code];
	}
	// Dynamic Select Query which having select where, join, order, groupby ects.
	
	function getQuery($option){
		$ci =& get_instance();
		return $ci->core_model->joinQuery($option); 
	}

	function insert($table , $data){
		$ci =& get_instance();
		$ci->core_model->insert_data($table , $data);
		return $ci->db->insert_id();
	}

	// LQ stands for Last Query which is return the Query which is last called
	function lQ($id = ''){
		$ci =& get_instance();
		echo $ci->db->last_query()."<br>";
		if($id != ''){
			die();
		}
	}

	// Update function is used for updating the row by where condition 
	function update($update){
		extract($update);
		$ci =& get_instance();
		if( !isset($where) ){
			$ci->core_model->update_data($table, $data);
		}
		else{
			$ci->core_model->update_data_by_where($table, $data, $where);
		}
	}

	function update_batch($update){
		extract($update);
		$ci =& get_instance();
		$ci->core_model->update_batch_by_where($table, $data, $where);
	}

	// Delete Function basically deletes a array by where condition
	function delete($data){
		extract($data);
		$ci =& get_instance();
		$ci->core_model->delete_data_by_where($table, $where);
	}

	// Pre is using beautify the print of an array
	function pre($data, $n = '')
	{
		echo '<pre>';print_r($data);echo '</pre>';if($n != ''){ die(); }
	}

	// View function is used to reduce code line $pageName which of the page will be called
	// $data is array which having value of what is passing through 
	function view($pageName, $data = array())
	{
		$ci =& get_instance();
		if(empty($data)){
			$ci->load->view($pageName);
		}
		else{
			$ci->load->view($pageName, $data);
	 	}
	}

	function adminData($id = ''){
		$ci =& get_instance();
		return getQuery([
			'where' => [
				'id' => $id != '' ? $id : $_SESSION['adminId']
			],
			'table' => 'users',
			'single' => true
		]);
	}

	function admin($id = '' , $columnName = ''){
		$ci =& get_instance();
		$id = getQuery([
			'where' => [
				'id' => $id != '' ? $id : (isset($_SESSION['adminId']) ?  $_SESSION['adminId'] : 1 )
			],
			'table' => 'admin',
			'single' => true
		]);
		if( $columnName != '' ){
			return $id[$columnName];
		}
		else{
			return $id;
		}
	}

	// Set A Variable In Session 
	function setVariable( $data ){
		$ci =& get_instance();
		foreach( $data as $key =>$value ){
			$ci->session->set_userdata( $key , $value );
		}
	}

	// Checking Post Array Having Value
	function isPost(){
		if(empty($_POST)){
			return false;
		}
		else{
			return true ;
		}
	}
	
	// Setting Multiple Flash Data

	function setFlashData($array, $redirect = ''){
		$ci =& get_instance();
		foreach($array as $key =>$value){
			$ci->session->set_flashdata($key , $value);
		}
		if($redirect != ''){
			redirect($redirect);
		}
	}

	// Check Variable in flashdata return if exist
	function checkVariable($key){
		$ci =& get_instance();
		if($ci->session->flashdata($key) != null){
			return $ci->session->flashdata($key);
		}
		else{
			return false;
		}
	}

	// Getting flashdata by key
	function getFlastData($key){
		$ci =& get_instance();
		return $ci->session->flashdata($key);
	}

	// Check Validation Error and Return array of errors 
	function validations($setRules){
		$ci =& get_instance();
		$error = array();
		foreach ($setRules as &$rules){
			$ci->form_validation->set_rules($rules[0], $rules[1], $rules[2]);
			if($ci->form_validation->run() == FALSE){
				 if(isset($error)){
				 	unset($error);
				 }
				$error = $ci->form_validation->error_array();
			}
		}
		return $error;
	}


	// Set data in this array if any error occur in form values
	function setData($key){
		if(isset($_SESSION['formData'][$key])){
			return $_SESSION['formData'][$key];
		}
		return "";
	}

	// Setting Error Of Form Field
	function setError($key){
		if(isset($_SESSION['formError'][$key])){
			return $_SESSION['formError'][$key];
		}
		return "";
	}

  // Set Message On Form Error
	function setMessage($variable)
  {
    if(isset($_SESSION['formError'][$variable])){
      return '<span class="error invalid-feedback">'.ucwords($_SESSION['formError'][$variable]).'</span>';
    }
    else{
      return '';
    }
  }

  // Return Any affected Row On Update
	function affected(){
		$ci =& get_instance();
		return $ci->db->affected_rows();
	}

	// Sending Message From Mobile Number
	// function sendMessage(int $number, string $sms){
	// 	$data = file_get_contents(API.'mobiles='.$number.'&sms='.$sms);
	// 	return json_decode($data);
	// }


	function paginate($item_per_page,$current_page,$total_records, $addPerPage = '', $addSearch = '',$page_url , $attribute = ''){
  	$total_pages = ceil( $total_records/$item_per_page );
		if($addPerPage != ''){
			if($addSearch != ''){
				$addSearch = "&search=".$addSearch;
			}
			$addSearch = "?perPage=".$addPerPage.$addSearch;
		}
		if( $attribute != '' ){
			$page_url."/".$attribute;
		}
    $pagination = '';
    if($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages){ //verify total pages and current page number
        $pagination .= '<ul class="pagination pagination-sm float-right" style="margin-top:10px;">';
        $right_links    = $current_page + 3; 
        if($current_page == 1){
          $right_links    = $current_page + 5; 
        }
        $previous       = $current_page - 3; //previous link 
        $next           = $current_page + 1; //next link
        $first_link     = true; //boolean var to decide our first link
        
        if($current_page > 1){
					$previous_link = ($previous==0)?1:$previous;
          $pagination .= '<li class="page-item"><a class="page-link" href="'.$page_url.'/1'.$addSearch.'" title="First">«</a></li>'; //first link
          $pagination .= '<li class="page-item" ><a class="page-link" href="'.$page_url.'/'.$previous_link.'" title="Previous"><</a></li>'; //previous link
              for($i = ($current_page-2); $i < $current_page; $i++){ //Create left-hand side links
                  if($i > 0){
                      $pagination .= '<li class="page-item" ><a class="page-link" href="'.$page_url.'/'.$i.$addSearch.'">'.$i.'</a></li>';
                  }
              }   
          $first_link = false; //set first link to false
        }
        
        if($first_link){ //if current active page is first link
            $pagination .= '<li class="page-item active"><a class="page-link" href="'.$page_url.'/'.$current_page.$addSearch.'">'.$current_page.'</a></li>';
        }elseif($current_page == $total_pages){ //if it's the last active link
            $pagination .= '<li class="page-item active"><a class="page-link" href="'.$page_url.'/'.$current_page.$addSearch.'">'.$current_page.'</a></li>';
        }else{ //regular current link
					$pagination .= '<li class="page-item active"><a class="page-link" href="'.$page_url.'/'.$current_page.$addSearch.'">'.$current_page.'</a></li>';
        }
                
        for( $i = $current_page+1; $i < $right_links ; $i++ ){ //create right-hand side links
            if($i<=$total_pages){
                $pagination .= '<li class="page-item" ><a class="page-link" href="'.$page_url.'/'.$i.$addSearch.'">'.$i.'</a></li>';
            }
        }
        if($current_page < $total_pages){ 
				  $next_link = ($i > $total_pages)? $total_pages : $i;
					$pagination .= '<li class="page-item" ><a class="page-link" href="'.$page_url.'/'.$next_link.$addSearch.'" >></a></li>'; //next link
					$pagination .= '<li class="page-item last"><a class="page-link" href="'.$page_url.'/'.$total_pages.$addSearch.'" title="Last">»</a></li>'; //last link
        }
        
        $pagination .= '</ul>'; 
    }
		else{
    	$pagination .=  '<ul class="pagination pagination-sm float-right" style="margin-top:10px;">';
    	$pagination .=  '<li class="page-item"><a class="page-link" href="'.$page_url.'/1'.$addSearch.'" title="First">«</a></li>';
    	$pagination .=  '<li class="page-item active"><a class="page-link" href="'.$page_url.'/'.$current_page.$addSearch.'">'.$current_page.'</a></li>';
    	$pagination .=  '<li class="page-item last"><a class="page-link" href="'.$page_url.'/'.$total_pages.$addSearch.'" title="Last">»</a></li>';
    	$pagination .=  '</ul>';
    }
    return $pagination; //return pagination links
	}

// 	function resize_image($file, $w, $h, $crop=FALSE) {
//     list($width, $height) = getimagesize($file);
//     $r = $width / $height;
//     if ($crop) {
//         if ($width > $height) {
//             $width = ceil($width-($width*abs($r-$w/$h)));
//         } else {
//             $height = ceil($height-($height*abs($r-$w/$h)));
//         }
//         $newwidth = $w;
//         $newheight = $h;
//     } else {
//         if ($w/$h > $r) {
//             $newwidth = $h*$r;
//             $newheight = $h;
//         } else {
//             $newheight = $w/$r;
//             $newwidth = $w;
//         }
//     }
//     $src = imagecreatefromjpeg($file);
//     $dst = imagecreatetruecolor($newwidth, $newheight);
//     imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

//     return $dst;
// }

// function resize_image($im_filename,$th_filename,$max_width,$max_height,$quality = 0.75)
// {
// // The original image must exist
// if(is_file($im_filename))
// {
//     // Let's create the directory if needed
//     $th_path = dirname($th_filename);
//     if(!is_dir($th_path))
//         mkdir($th_path, 0777, true);
//     // If the thumb does not aleady exists
//     if(!is_file($th_filename))
//     {
//         // Get Image size info
//         list($width_orig, $height_orig, $image_type) = @getimagesize($im_filename);
//         if(!$width_orig)
//             return 2;
//         switch($image_type)
//         {
//             case 1: $src_im = @imagecreatefromgif($im_filename);    break;
//             case 2: $src_im = @imagecreatefromjpeg($im_filename);   break;
//             case 3: $src_im = @imagecreatefrompng($im_filename);    break;
//         }
//         if(!$src_im)
//             return 3;

//         $aspect_ratio = (float) $height_orig / $width_orig;

//         $thumb_height = $max_height;
//         $thumb_width = round($thumb_height / $aspect_ratio);
//         if($thumb_width > $max_width)
//         {
//             $thumb_width    = $max_width;
//             $thumb_height   = round($thumb_width * $aspect_ratio);
//         }

//         $width = $thumb_width;
//         $height = $thumb_height;

//         $dst_img = @imagecreatetruecolor($width, $height);
//         if(!$dst_img)
//             return 4;
//         $success = @imagecopyresampled($dst_img,$src_im,0,0,0,0,$width,$height,$width_orig,$height_orig);
//         if(!$success)
//             return 4;
//         switch ($image_type) 
//         {
//             case 1: $success = @imagegif($dst_img,$th_filename); break;
//             case 2: $success = @imagejpeg($dst_img,$th_filename,intval($quality*100));  break;
//             case 3: $success = @imagepng($dst_img,$th_filename,intval($quality*9)); break;
//         }
//         if(!$success)
//             return 4;
//     }
//     return 0;
// }
// return 1;
// }

	function resize_image( $file_path  , $width , $height, $new_file_path ) {
		$CI =& get_instance();
			// Set your config up
			$config['image_library']    = "gd2";      
			$config['source_image']     = $file_path;      
			// $config['create_thumb']     = TRUE;      
			// $config['maintain_ratio']   = TRUE;     
			$config['new_image'] = $new_file_path; 
			$config['master_dim'] = 'auto';
			$config['width'] = $width;      
			$config['height'] = $height;  
			$config['thumb_marker']=FALSE;
			$CI->load->library('image_lib');
			$CI->image_lib->initialize($config);
			// Do your manipulation
			
			if(!$CI->image_lib->resize())
			{
				$CI->image_lib->display_errors();  
			} 
			$CI->image_lib->clear();
			
			if( isset($imgdata['Orientation'] ) ){
				$imgdata=exif_read_data($new_file_path, 'IFD0');
				
				$config=array();
		
				$config['image_library'] = 'gd2';
				$config['source_image'] = $new_file_path;

				switch($imgdata['Orientation']) {
						case 3:
								$config['rotation_angle']='180';
								break;
						case 6:
								$config['rotation_angle']='270';
								break;
						case 8:
								$config['rotation_angle']='90';
								break;
				}
		
				$CI->image_lib->initialize($config); 
				$CI->image_lib->rotate();
			}
			// die;

	}


	function check_profile($user , $exception_list)
	{
		$ci =& get_instance();
		// pre( $exception_list , 1 );
			list($width, $height, $type, $attr) = getimagesize(base_url().$user['profile_photo']);
			$minSize   = 200 * 1000;
			$filesize = get_headers(base_url().$user['profile_photo'] , 1);
			// pre($filesize);
			$size = $filesize['content-length'];
			// echo $width." ".$height;
			$exception_flag = 'incomplete';
			if( in_array( $user["email"] , $exception_list ) ){
				$exception_flag = 'completed';
			}
			// echo $exception_flag ; die;
			$flag = 'completed';
			if( $width > 200  || $height > 200 )
			{
					$flag = 'incomplete';
					$errorArray['image'] = 'Height & width has to be 200*200 pixels';
			}
			if( $size < $minSize )
			{
					$flag = 'incomplete';
					$errorArray['Image size'] = 'Size of image must be 200kb or less';
			}
			$sp_profile_data = getQuery( [ 'where' => [ 'email' => $user['email'] ] , 'table'  => 'sp_profile_detail' , 'single' => true ] );
			if( !empty( $sp_profile_data ) ){
						if( $user['iam']  == 'sp' || $user['iam']  == 'reseller' ){
							// echo strlen( $sp_profile_data['content3'] );
							// pre( $sp_profile_data , 1 );
							if( str_word_count($sp_profile_data['about_us']) < 150  || str_word_count($sp_profile_data['about_us'])  > 200 ){
									$flag = 'incomplete';
									$errorArray['About Us'] = 'word count should be between 150 to 200';
								}
								if( str_word_count( $sp_profile_data['key_services'] )  != '' && str_word_count( $sp_profile_data['key_services'] ) > 50  ){
									$flag = 'incomplete';
									$errorArray['Key Services'] = 'word count should be not empty or not greater than 50';
								}
								if( str_word_count( $sp_profile_data['content1'] ) < 30  ){
									$flag = 'incomplete';
									$errorArray['Education Profile'] = 'word count should be between not empty to not less than 30';
								}
								if( str_word_count( $sp_profile_data['content2'] ) < 50  ){
									$flag = 'incomplete';
									$errorArray['Vision'] = 'word count should be between not empty to not less than 50';
								}
								if( str_word_count( $sp_profile_data['content3'] ) < 50  ){
									$flag = 'incomplete';
									$errorArray['Mission'] = 'word count should be between not empty to not less than 50';
								}
						}
						else{
							$errorArray['Mission'] = 'word count should be between not empty to not less than 80';
							$errorArray['Vision'] = 'word count should be between not empty to not less than 80';
							$errorArray['Education Profile'] = 'word count should be between not empty to not less than 75';
							$errorArray['Key Services'] = 'word count should be not empty or not greater than 50';
							$errorArray['About Us'] = 'word count should be between 150 to 200Kb';
							$flag = 'incomplete';
						}
			}
			else{
				$errorArray['Mission'] = 'word count should be between not empty to not less than 80';
				$errorArray['Vision'] = 'word count should be between not empty to not less than 80';
				$errorArray['Education Profile'] = 'word count should be between not empty to not less than 75';
				$errorArray['Key Services'] = 'word count should be not empty or not greater than 50';
				$errorArray['About Us'] = 'word count should be between 150 to 200Kb';
				$flag = 'incomplete';
			}
			if( $flag == 'incomplete' ){
					$_SESSION['profileError'] = $errorArray;
			}
			else{
				if( isset( $_SESSION['profileError'] ) ){
					unset( $_SESSION['profileError'] );
				}
				update( [ 'table' => 'user_details' , 'data' => [ 'profile_flag' => $flag."-".$exception_flag ] , 'where' => [ 'id' => $user['id'] ] ]);
			}
			update( [ 'table' => 'user_details' , 'data' => [ 'profile_flag' => $flag."-".$exception_flag  ] , 'where' => [ 'id' => $user['id'] ] ] );
			// lQ(1);
	}

	function prepare_seo( $service = null , $location = null ){
		
		if( $service == 'all-services' && $location = null ){
			return [
				'title' =>	'Best career and wellness counselors Near me- Respicite',
				'description' =>	"Connect with  Top Career and Wellness Counselors for instant appointments, live chat, and comprehensive career guidance all in one place!",
				'keywords' => "career counselor near me, best career counselor in bhopal near me,near me career counselor in bhopal, career counsellor",
				'google-code' => 'G-MBPM9J392X',
			];
		}
		elseif( in_array( $service , [ 'career-counselling' , 'overseas-services' , 'parenting-counselling' ] ) ){
			if( $location != null ){
				$seo = [
					'career-counselling' => [
						'title' => 'Best career counselor in '.ucwords($location).' Near me- Respicite',
						'description' => 'Connect with '.ucwords($location).'\'s Top Career Counselors for instant appointments, live chat, and comprehensive career guidance all in one place!',
						'keywords' => 'career counselor near me, best career counselor in '.ucwords($location).' near me, near me career counselor in '.ucwords($location).', career counsellor',
						'google-code' => 'G-MBPM9J392X',
					],
					'overseas-services' => [
						'title' => 'Best Study Abroad Consultants & Abroad Education Consultant in '.ucwords($location).' near me - Respicite',
						'description' => 'Find expert study abroad consultants & Abroad Education Consultant in '.ucwords($location).' for personalized guidance and support in pursuing your global education goals.',
						'keywords' => 'Abroad Education Consultant in '.ucwords($location).' near me, study abroad consultants in '.ucwords($location).' near me, study abroad consultants '.ucwords($location),
						'google-code' => 'G-MBPM9J392X',
					],
					'parenting-counselling' => [
						'title' => 'Nearby Parenting Coach in '.ucwords($location).' near me - Respicite',
						'description' => 'Find nearby parenting coaches for personalized advice and solutions to navigate the challenges of parenthood',
						'keywords' => 'parents coach near me in '.ucwords($location).', parents coach, parents, parents coach in '.ucwords($location).', near me parents coach',
						'google-code' => 'G-MBPM9J392X',
					]
				];
				return $seo[$service];
			}
			else{
				$seo = [
					'career-counselling' => [
						'title' => 'Best career counsellor  - Respicite',
						'description' => 'Connect with Top Career Counsellors for instant appointments, live chat, and comprehensive career guidance all in one place!',
						'keywords' => 'career counsellor near me, best career counsellor near me,near me career counsellor, career counsellor',
						'google-code' => 'G-MBPM9J392X',
					],
					'parenting-counselling' => [
						'title' => 'Nearby Parenting Coach - Respicite',
						'description' => 'Find nearby parenting coaches for personalized advice and solutions to navigate the challenges of parenthood',
						'keywords' => 'parents coach near me, parents coach, parents, parents coach, near me parents coach',
						'google-code' => 'G-MBPM9J392X',
					],
					'overseas-services' => [
						'title' => 'Best Study Abroad Consultants & Abroad Education Consultant near me - Respicite',
						'description' => 'Find expert study abroad consultants & Abroad Education Consultant for personalized guidance and support in pursuing your global education goals',
						'keywords' => 'Abroad Education Consultant near me, study abroad consultants near me, study abroad consultants',
						'google-code' => 'G-MBPM9J392X',
					]
				];
				return $seo[$service];
			}
		}
		else{
			return [
				'title' =>	'Best career and wellness counselors in '.ucwords($location).' Near me- Respicite',
				'description' =>	'Connect with '.ucwords($location).'\'s Top Career and Wellness Counselors for instant appointments, live chat, and comprehensive career guidance all in one place!',
				'keywords' => 'career counselor near me, best career counselor in '.ucwords($location).' near me,near me career counselor in '.ucwords($location).', career counsellor',
				'google-code' => 'G-MBPM9J392X',
			];
		}

	}


	function email_template($template){
		extract( $template );
		$htmlContent = "<img src='".base_url()."uploads/respicite-logo-small.png' alt='Respicite Logo' width='117'>
				<h3> <strong>".$heading."</strong> </h3>
				<h1>".$heading2."</h1>
				<br/>
				<p style='font-size:15px'>".$para1."<p>
				<br>
				<br/>
				<a href='".base_url()."' style='padding: 10px 24px 10px 22px;text-align: center;font-size: 16px;font-weight: bold;line-height: 21px;border-radius: 21px;text-decoration: none;background:#dcc643;color:#8c432a;text-decoration:none' rel='nofollow' target='_blank'>Login To Respicite</a>
				<br>
				<br>
				<p>
						We look forward for your presence.
						<br>
						<br>
						Thank You<br>
						Team, Respicite
				</p>
		";
		return $htmlContent;
	}



	function getProfileLink($fullName)
	{
		$profile_link=strtolower(str_replace(' ' , '-' , trim($fullName)));
		$searchList = getQuery( [ 'select' => 'count(id) as total' , 'table' => 'user_details' , 'like' => [ [ 'profile_link' , $profile_link ] ] , 'single' => true ] );
		// lQ(1);
		// pre( $searchList , 1 );
		if( $searchList['total'] > 0 ){
			if( !empty( $searchList ) ){
					// pre( $searchList , 1 );
					$profile_link = $profile_link.'-'.$searchList['total'];
			}
		}
		return $profile_link;
	}



	function rand_que_group( $solution , $partName , $subPart , $asmt_tbl ){
		if( $solution == 'UCE' ){
				if( $subPart != NULL ){
						$where = [ 'asmt_variant_3' => 1 , 'q_group !=' => 'All','part' => $subPart ];
				}
				else{
						$where = [ 'asmt_variant_3' => 1 , 'q_group !=' => 'All' ];
				}
				$groupList = getQuery( [ 'where' => $where , 'select' => 'q_group' , 'table' => $asmt_tbl , 'group_by' => [ 'q_group' ] , 'order' => [ 'q_group' => 'ASC' ] ] );
				return [ 1 , 'All' ];
		}
	}


	function submitForm($title = 'Modal title' , $segment , $cluster , $stream )
	{
		// pre( $segment , 1 );
		$segment_op = '<option value="">Select</option>';
		if( !empty( $segment ) ){
			foreach ($segment as $key => $value) {
				$segment_op .= '<option value="'.$value['mkt_sgmt'].'">'.ucwords(str_replace('_' , ' ' , $value['mkt_sgmt'])).'</option>';
			}
		}
		else{
			$segment_op = '<option value="">Select</option>';
		}
		$stream_op = '<option value="">Select</option>';
		// $stream_ch_op = '<option value="">Select</option>';
		if( !empty( $stream ) ){
			foreach ($stream as $key => $value) {
				$stream_op .= '<option value="'.$value['11th_12th'].'">'.ucwords(str_replace('_' , ' ' , $value['11th_12th'])).'</option>';
				// $stream_ch_op .= '<option id="'.$value['11th_12th'].'" value="'.$value['11th_12th'].'">'.ucwords(str_replace('_' , ' ' , $value['11th_12th'])).'</option>';
			}
		}
		else{
			$stream_op = '<option value="">Select</option>';
			// $stream_ch_op = '<option value="">Select</option>';
		}
		$cluster_op = '<option value="">Select</option>';
		if( !empty( $cluster ) ){
			foreach ($cluster as $key => $value) {
				$cluster_op .= '<option value="'.$value['Cluster'].'">'.ucwords(str_replace('_' , ' ' , $value['Cluster'])).'</option>';
			}
		}
		else{
			$cluster_op = '<option value="">Select</option>';
		}
		// pre( $segment_op , 1 );
		return '<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">'.$title.'</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
				<form id="variantForm">
					<div class="form-group mb-0">
						<label class="form-label">Education Level</label>
						<select class="form-control" id="mkt_sgmt" onchange=\'getFilter("mkt_sgmt" , "class" , this)\' required name="mkt_sgmt">
							'.$segment_op.'
						</select>
					</div>
					<div class="form-group mb-0">
						<label class="form-label">Class</label>
						<select class="form-control" id="class" onchange=\'getFilter("class" , "report_nature" , this)\' required name="class">
							<option>Select</option>
						</select>
					</div>
					<div class="form-group mb-0">
						<label class="form-label">What are you looking for ? <i data-toggle="modal" data-target="#reportNature" class="reportNature fa fa-info-circle d-none" aria-hidden="true"></i>
						</label>
						<select class="form-control" onchange=\'getFilter("report_nature" , "stream_action" , this)\' id="report_nature" required name="report_nature">
						<option>Select</option>
						</select>
					</div>
					<div class="form-group mb-0 cluster d-none">
						<label class="form-label"> Please choose a career cluster <i data-toggle="modal" data-target="#clusterDetail" class="fa fa-info-circle" aria-hidden="true"></i> </label>
						<select class="form-control"  onchange=\'getProfession()\' id="cluster" required name="cluster">
						'.$cluster_op.'
						</select>
					</div>
					<div class="form-group mb-0 profession d-none" >
						<label class="form-label"> Please choose profession within career cluster </label>
						<select class="form-control" id="profession" onchange=\'getFilter("stream_action" , "report_type" , this)\' required name="profession">
							<option>Select</option>
						</select>
					</div>
					<div class="form-group mb-0 stream_action">
						<label class="form-label">Please provide further details
						 ? </label>
						<select class="form-control" onchange=\'getFilter("stream_action" , "report_type" , this)\' id="stream_action" required name="stream_action">
							<option>Select</option>
						</select>
					</div>
					<div class="form-group mb-0 currentStream d-none">
						<label class="form-label"> Please choose your current stream </label>
						<select class="form-control" onchange=\'checkChangeStream()\' id="currentStream" required name="currentStream">
							'.$stream_op.'
						</select>
					</div>
					<div class="form-group mb-0 changeStream d-none">
						<label class="form-label d-block"> Please select for change stream  </label>
						<select class="form-control select2-multiple" id="changeStream" onchange=\'getFilter("stream_action" , "report_type" , this)\' required name="changeStream[]" multiple>
							'.$stream_op.'
						</select>
					</div>
					<div class="form-group mb-0">
						<label class="form-label">What type of report are you looking for ? <i data-toggle="modal" data-target="#reportType" class="reportType fa fa-info-circle d-none" aria-hidden="true"></i></label>
						<select class="form-control" onchange=\'getFilter("report_type" , "price" , this)\' id="report_type" required name="report_type">
							<option>Select</option>
						</select>
					</div>
					<div class="form-group mb-0">
						<label class="form-label">Price</label>
						<input type="text" class="form-control" name="price" id="price" readonly value="0">
						<input type="hidden" class="form-control" name="variantId" id="variantId">
					</div>
				</div>
				<div class="modal-footer">
				</div>
			</div>
		</div>
	</div>';
	}

	function reportNature($title = 'Report Nature')
	{
		// pre( $segment_op , 1 );
		return '<div class="modal fade" id="reportNature" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title">'.$title.'</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body" id="report_nature_div">
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
									</div>
								</div>
							</div>
						</div>';
	}
	
	function reportType($title = 'Report Type')
	{
		// pre( $segment_op , 1 );
		return '<div class="modal fade" id="reportType" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title">'.$title.'</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body" id="report_type_div">
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
									</div>
								</div>
							</div>
						</div>';
	}
	
	function clusterDetail($title = 'Cluster details')
	{
		// pre( $segment_op , 1 );
		return '<div class="modal fade" id="clusterDetail" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">'.$title.'</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
					We have divided all professions into 16 industry clusters. Choose your preferred career cluster
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>';
	}

	function getFilter( $search , $value , $select_column )
	{
		
		$next_search = [
			'class' => [ 'report_nature' , 'stream_action' , 'stream_action!='  , 'report_type' ],
			'report_nature' => [  'stream_action!=' , 'stream_action' , 'report_type' ],
			'stream_action' => [  'report_type' ]
		];

		if( !isset($_SESSION['variant_filter_arr'])){
			$_SESSION['variant_filter_arr'] = [];
			$_SESSION['variant_filter_arr']['status'] = 'active';
		}
		elseif( $search == 'mkt_sgmt' ){
			$_SESSION['variant_filter_arr'] = [];
			$_SESSION['variant_filter_arr']['status'] = 'active';
		}
		if( $value != '' ){
			if( !isset( $_SESSION['variant_filter_arr'][$search] ) ){
				$_SESSION['variant_filter_arr'][$search] = $value;
			}
			else{
					$_SESSION['variant_filter_arr'][$search] = $value;
			}
		}
		if( isset($next_search[$search]) ){
			foreach ($next_search[$search] as $key => $v) {
				if( isset( $_SESSION['variant_filter_arr'][$v] ) ){
					unset($_SESSION['variant_filter_arr'][$v]);
				} 
			}
		}
		
		if( $select_column != 'price'  ){
			// pre( $_SESSION['variant_filter_arr'] , 1 );
			if( $select_column == 'stream_action' ){
				if( $select_column == 'stream_action' && $value != 'na' ){
					$_SESSION['variant_filter_arr'][$select_column."!="] = 'na';
				}
			}
			if( $search == 'stream_action' && $value == 'na' ){
				if( isset( $_SESSION['variant_filter_arr'][$search."!="] ) ){
					unset( $_SESSION['variant_filter_arr'][$search."!="] );
				}
			}
			// pre( [ $_SESSION['variant_filter_arr'] , $search , $value ] );
			if( $search == 'stream_action' && $value == '' ){
				if( isset( $_SESSION['variant_filter_arr'][$search."!="] ) ){
					unset($_SESSION['variant_filter_arr'][$search."!="]);
				}
			}
			$data = [ 'list' => getQuery( [ 'where' => $_SESSION['variant_filter_arr'] , 'table' => 'solution_variant_new' , 'group_by' => [ $select_column ] ] ) ];
			// lQ(1);
			return $data;
		}
		else{
			$priceData = getQuery( [ 'select' => 'id, mrp_reseller' , 'where' => $_SESSION['variant_filter_arr'] , 'table' => 'solution_variant_new' , 'single' => true ] );
			// lQ(1);
			return [ 'list' => $priceData , 'price' => true ];
		}
	}


	function emailTemplate( $tempateType )
	{
		extract( $tempateType );
		if($type == 'assessment' ){
			$template = "<img src='".base_url()."uploads/respicite-logo-small.png' alt='Respicite Logo' width='117'>
					<h3> <strong>Please verify your Email Id</strong> </h3>
					<h1> <strong>Welcome from Respicite</strong> </h1>
					<p style='font-size:15px '>
						Dear ".$email."
						<br/> 
						<br/>
						You had visited our website Assessment Page and registered for ".$solutionName." 
						<br>
						Please complete your registration with us by using the following  
						<br>
						<br> 
						<a style='padding: 13px 20px;text-align: center;font-size: 16px;font-weight: bold;line-height: 15px;border-radius: 21px;text-decoration: none;background:#dcc643;color:#8c432a;text-decoration:none' rel='nofollow' target='_blank'> One Time Password - ".$otp."</a>
						<br/>
						<br/> 
						<br/> 
						<br/> 
						Team Respicite <br/> <mailto: info@respicite.com>
						<a href='https://respicite.com'>https://respicite.com</a>
						<br>
					</p>
				";
				return $template;
		}
	}

	function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = substr(str_shuffle($characters), 0, $length);
		
		return $randomString;
	}

	