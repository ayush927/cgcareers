<?php
  defined('BASEPATH') OR exit('No direct script access allowed');
  /*
  | -------------------------------------------------------------------------
  | URI ROUTING
  | -------------------------------------------------------------------------
  | This file lets you re-map URI requests to specific controller functions.
  |
  | Typically there is a one-to-one relationship between a URL string
  | and its corresponding controller class/method. The segments in a
  | URL normally follow this pattern:
  |
  |	example.com/class/method/id/
  |
  | In some instances, however, you may want to remap this relationship
  | so that a different class/function is called than the one
  | corresponding to the URL.
  |
  | Please see the user guide for complete details:
  |
  |	https://codeigniter.com/user_guide/general/routing.html
  |
  | -------------------------------------------------------------------------
  | RESERVED ROUTES
  | -------------------------------------------------------------------------
  |
  | There are three reserved routes:
  |
  |	$route['default_controller'] = 'welcome';
  |
  | This route indicates which controller class should be loaded if the
  | URI contains no data. In the above example, the "welcome" class
  | would be loaded.
  |
  |	$route['404_override'] = 'errors/page_missing';
  |
  | This route will tell the Router which controller/method to use if those
  | provided in the URL cannot be matched to a valid route.
  |
  |	$route['translate_uri_dashes'] = FALSE;
  |
  | This is not exactly a route, but allows you to automatically route
  | controller and method names that contain dashes. '-' isn't a valid
  | class or method name character, so it requires translation.
  | When you set this option to TRUE, it will replace ALL dashes in the
  | controller and method URI segments.
  |
  | Examples:	my-controller/index	-> my_controller/index
  |	my-controller/my-method	-> my_controller/my_method
  */
  $route['default_controller'] = 'UserController';
  $route['404_override'] = '';
  $route['translate_uri_dashes'] = true;
  
  $route['best-counsellors-india'] = 'home/counsellors';
  $route['best-counsellors-india/(:any)'] = 'home/counsellors/$1';
  $route['best-counsellors-india/(:any)/(:any)'] = 'home/counsellors/$1/$2';
  // $route['counsellor-view/(:any)'] = 'home/counsellors-view/$1';
  // $route['best-career-counsellor/(:any)/(:any)/(:any)'] = 'home/counsellors/$1/$2/$3';
  $route['registration'] = 'UserController/registration';
  $route['counselors-view/(:any)'] = 'home/counselors-view/$1';
  $route['book-session'] = 'shared/calendars/Bookings/create';

  $route['service-providers'] = 'SpController/marketplace_new';
  $route['service-providers/(:any)'] = 'SpController/marketplace_new/$1';
  $route['service-provider/(:any)'] = 'SpController/view_sp_full_detail_public_updated/$1';

  $route['payment-gateway/configure'] = 'shared/payments/PaymentController/paymentType';
  $route['payment-gateway/addparameter'] = 'shared/payments/PaymentController/addPaymentParameter';
  $route['payment-gateway/saveparameter'] = 'shared/payments/PaymentController/savePaymentParameter';
  $route['payment-gateway/razorypay/keys/(:any)'] = 'shared/payments/RazorpayController/insertCreditial/$1';
  $route['payment-gateway/razorypay/keys'] = 'shared/payments/RazorpayController/insertCreditial';
  $route['payment-gateway/stripe/keys/(:any)'] = 'shared/payments/StripController/insertCreditial/$1';
  $route['payment-gateway/checkout'] = 'shared/payments/RazorpayController/checkout';
  $route['payment-gateway/status'] = 'shared/payments/RazorpayController/payment_status';

  $route['career-library/edit-source/(:any)'] = 'career-library/add-source/$1';
  $route['adminController/edit-cluster/(:any)'] = 'adminController/add-cluster/$1';
  $route['adminController/edit-calendly-config/(:any)'] = 'adminController/calendly-config/$1';

  $route['UserController/edit-booking-link/(:any)'] = 'UserController/add-booking-link/$1';
  $route['UserController/edit-booking-link/(:any)/(:any)'] = 'UserController/add-booking-link/$1/$2';
  $route['UserController/edit-booking-link/(:any)/(:any)/(:any)'] = 'UserController/add-booking-link/$1/$2/$3';

  $route['career-library/edit-career-path/(:any)'] = 'career-library/add-career-path/$1';
  $route['career-library/edit-profession/(:any)'] = 'career-library/add-profession/$1';
  $route['adminController/edit-service/(:any)'] = 'adminController/add-service/$1';
  $route['adminController/edit-details/(:any)/(:any)'] = 'adminController/add-details/$1/$2';
  $route['adminController/edit-counsellor-details/(:any)'] = 'adminController/add-counsellor-details/$1';
  // $route['marketplace'] = 'SpController/marketplace';shared/payments/RazorpayController/payment_status


  // Skill Development
  $route['skill-development/edit-domain/(:any)'] = 'skill-development/add-domain/$1';
  $route['skill-development/sub-edit-domain/(:any)'] = 'skill-development/add-sub-domain/$1';
  $route['skill-development/edit-level/(:any)'] = 'skill-development/add-level/$1';
  $route['skill-development/edit-nature/(:any)'] = 'skill-development/add-nature/$1';
  $route['skill-development/edit-certification/(:any)'] = 'skill-development/add-certification/$1';
  $route['skill-development/edit-discount/(:any)'] = 'skill-development/add-discount/$1';
  $route['skill-development/edit-language/(:any)'] = 'skill-development/add-language/$1';

  // UCE variation 3 Routes
  $route['assessment-variations/three/(:any)/(:any)'] = 'uce_version_3/view_question/$1/$2';
  $route['assessment-variations/three/get-question/(:any)/(:any)'] = 'uce_version_3/get-question/$1/$2';
  $route['assessment-variations/three/submit-answers/(:any)/(:any)'] = 'uce_version_3/submit-answers/$1/$2';
  
  
  // Assessment Variation Image 
  
  $route['AdminController/edit-variation-image/(:any)'] = 'AdminController/variation-report-image/$1';
  $route['UserController/edit-variation-image/(:any)'] = 'UserController/variation-report-image/$1';
  $route['SpController/edit-variation-image/(:any)'] = 'SpController/variation-report-image/$1';


  // 
  $route['check-status'] = 'home/check-status/';
  $route['check-status/(:any)'] = 'home/check-status/$1';
 
  $route['take-assessment/(:any)'] = 'purchase-assessment/take-assessment/$1';