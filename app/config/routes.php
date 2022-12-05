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
  | Please see the user guide for complete details:event-booking
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
  |		my-controller/my-method	-> my_controller/my_method
 */
$route['default_controller'] = 'front';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

/*
 *
 * Frontend
 *
 */

/* Front */

$route['front'] = "front/index";
$route['change-city'] = "front/change_city";
$route['dashboard'] = "front/dashboard";
$route['maintenance'] = "maintenance/index";
$route['event-details/(:any)/(:any)'] = "front/event_details/$1/$2";
$route['service-details/(:any)/(:any)'] = "front/service_details/$1/$2";
$route['day-slots/(:num)'] = "front/day_slots/$1";
$route['day-slots/(:num)/(:num)'] = "front/day_slots/$1/$2";
$route['event-book/(:num)'] = "front/event_book/$1";
$route['time-slots/(:num)'] = "front/time_slots/$1";

$route['time-slots-admin/(:num)'] = "front/time_slots_admin/$1";
$route['time-slots/(:num)/(:num)'] = "front/time_slots/$1/$2";
$route['invoice/(:num)'] = "front/invoice/$1";
$route['page/(:any)'] = "front/page/$1";

$route['discount_coupon'] = "front/discount_coupon";
$route['booking-oncash'] = "front/booking_oncash";
$route['booking-stripe'] = "front/booking_stripe";
$route['booking-paypal'] = "front/booking_paypal";
$route['booking-free'] = "front/booking_free";
$route['paypal_success'] = 'front/paypal_success';
$route['paypal_cancel'] = 'front/paypal_cancel';
$route['booking-2checkout'] = 'front/booking_two_checkout';
$route['2checkout-success'] = 'front/two_checkout_success';
$route['set_language/(:any)'] = 'front/set_language/$1';

$route['appointment'] = "front/appointment";
$route['event-booking'] = "front/my_event_booking";
$route['delete-appointment/(:num)'] = "front/delete_appointment";
$route['update-appointment/(:num)'] = 'front/update_appointment/$1';
$route['update-appointment/(:num)/(:any)'] = 'front/update_appointment/$1/$2';
$route['v/(:any)/(:num)'] = "front/profile_details/$1/$2";
$route['payment-history'] = 'front/payment_history';
$route['update-booking'] = 'front/update_booking';
$route['event-category/(:any)/(:num)'] = 'front/event_category/$1/$2';
$route['get-appointment-details/(:num)'] = 'front/get_appointment_details/$1';
$route['appointment-success/(:num)'] = 'front/appointment_success/$1';
$route['contact-action'] = 'front/contact_action';

/* Booking Method */
$route['event-booking-oncash'] = "front/event_booking_oncash";
$route['event-booking-stripe'] = "front/event_booking_stripe";
$route['event-booking-paypal'] = "front/event_booking_paypal";
$route['event-booking-free'] = "front/event_booking_free";
$route['event-booking-twocheckout'] = "front/event_booking_two_checkout";
$route['event_paypal_success'] = 'front/event_paypal_success';
$route['event_paypal_cancel'] = 'front/event_paypal_cancel';

/* Message */
$route['message'] = 'front/message';
$route['message/(:num)'] = 'front/message/$1';
$route['message-action'] = 'front/message_action';

/* Location search */
$route['city/(:any)'] = 'front';

$route['save-vendorreview'] = 'front/save_vendorreview';
$route['terms-condition'] = 'front/terms_condition';
$route['privacy-policy'] = 'front/privacy_policy';
$route['faqs'] = 'front/faqs';
$route['contact-us'] = 'front/contact_us';
$route['contact-us-front'] = 'front/contact_us_front';
$route['success'] = 'front/register_success';
$route['events'] = 'front/event_listing';
$route['services'] = 'front/service_listing';
$route['search'] = 'front/search';

/* No Script */
$route['no-script'] = 'front/no_script';

/* Content */

$route['login'] = 'content/login';
$route['upload-summernote-image'] = 'content/upload_summernote_image';
$route['logout'] = 'content/logout';
$route['profile'] = 'content/profile';
$route['register'] = 'content/register';
$route['login-action'] = 'content/login_action';
$route['profile-save'] = 'content/profile_save';
$route['register-save'] = 'content/register_save';
$route['vendor-register'] = 'content/vendor_register';
$route['vendor-register-save'] = 'content/vendor_register_save';
$route['forgot-password'] = 'content/forgot_password';
$route['forgot-password-action'] = 'content/forgot_password_action';
$route['reset-password/(:any)/(:any)'] = 'content/reset_password';
$route['reset-password-action'] = 'content/reset_password_action';
$route['change-password'] = 'content/update_password';
$route['update-password-action'] = 'content/update_password_action';
$route['check-vendor-email'] = 'content/check_vendor_email';
$route['check-vendor-phone'] = 'content/check_vendor_phone';
$route['check-customer-email'] = 'content/check_customer_email';
$route['check-customer-phone'] = 'content/check_customer_phone';
$route['verify-vendor/(:any)/(:any)'] = 'content/verify_vendor/$1/$2';
$route['vendor-verify-resend/(:num)'] = 'content/vendor_verify_resend/$1';
$route['verify-customer/(:any)/(:any)'] = 'content/verify_customer/$1/$2';
$route['customer-verify-resend/(:num)'] = 'content/customer_verify_resend/$1';

/*
 *
 * Admin Folder
 *
 */

/* Content */

$route['admin'] = 'admin/content/login';
$route['admin/login'] = 'admin/content/login';
$route['admin/logout'] = 'admin/content/logout';
$route['admin/profile'] = 'admin/content/profile';
$route['admin/login-action'] = 'admin/content/login_action';
$route['admin/profile-save'] = 'admin/content/profile_save';
$route['admin/forgot-password'] = 'admin/content/forgot_password';
$route['admin/forgot-password-action'] = 'admin/content/forgot_password_action';
$route['admin/reset-password/(:any)/(:any)'] = 'admin/content/reset_password_admin';
$route['admin/reset-password-action'] = 'admin/content/reset_password_admin_action';
$route['admin/update-password'] = 'admin/content/update_password';
$route['admin/update-password-action'] = 'admin/content/update_password_action';



/* Dashboard */
$route['admin/send-membership-reminder'] = 'admin/dashboard/send_membership_reminder';
$route['admin/dashboard'] = 'admin/dashboard/index';
$route['admin/contact-us'] = 'admin/dashboard/contact_us';
$route['admin/event-inquiry'] = 'admin/dashboard/event_inquiry';
$route['admin/send_reply'] = 'admin/dashboard/send_reply';
$route['admin/send_event_inquiry_reply'] = 'admin/dashboard/send_event_inquiry_reply';
$route['admin/payment-history'] = 'admin/appointment/payment_history';

$route['admin/mandatory-update'] = 'admin/dashboard/mandatory_update';

/* Customer */

$route['admin/customer'] = 'admin/customer/index';
$route['admin/add-customer'] = 'admin/customer/add_customer';
$route['admin/update-customer/(:num)'] = 'admin/customer/update_customer/$1';
$route['admin/save-customer'] = 'admin/customer/save_customer';
$route['admin/delete-customer/(:any)'] = 'admin/customer/delete_customer/$1';
$route['admin/customer-booking/(:any)'] = 'admin/customer/customer_booking/$1';


$route['admin/currency'] = 'admin/currency/index';
$route['admin/add-currency'] = 'admin/currency/add_currency';
$route['admin/update-currency/(:num)'] = 'admin/currency/update_currency/$1';
$route['admin/save-currency'] = 'admin/currency/save_currency';
$route['admin/delete-currency/(:any)'] = 'admin/currency/delete_currency/$1';

/* Staff */

$route['admin/staff'] = 'admin/staff/index';
$route['admin/add-staff'] = 'admin/staff/add_staff';
$route['admin/update-staff/(:num)'] = 'admin/staff/update_staff/$1';
$route['admin/save-staff'] = 'admin/staff/save_staff';
$route['admin/delete-staff/(:any)'] = 'admin/staff/delete_staff/$1';
$route['admin/staff-booking/(:any)'] = 'admin/staff/staff_booking/$1';


$route['vendor/staff'] = 'admin/staff/index';
$route['vendor/add-staff'] = 'admin/staff/add_staff';
$route['vendor/update-staff/(:num)'] = 'admin/staff/update_staff/$1';
$route['vendor/save-staff'] = 'admin/staff/save_staff';
$route['vendor/delete-staff/(:any)'] = 'admin/staff/delete_staff/$1';
$route['vendor/staff-booking/(:any)'] = 'admin/staff/staff_booking/$1';


$route['vendor/view-event-booking-details/(:num)'] = 'admin/event/view_event_booking_details/$1';
$route['admin/view-event-booking-details/(:num)'] = 'admin/event/view_event_booking_details/$1';

$route['admin/change-booking-time/(:num)/(:num)'] = 'admin/appointment/change_booking_slot/$1/$2/';
$route['vendor/change-booking-time/(:num)/(:num)'] = 'admin/appointment/change_booking_slot/$1/$2/';

$route['admin/change-booking-time/(:num)/(:num)/(:num)'] = 'admin/appointment/change_booking_slot/$1/$2/$3';
$route['vendor/change-booking-time/(:num)/(:num)/(:num)'] = 'admin/appointment/change_booking_slot/$1/$2/$3';

$route['admin/change-customer-status/(:any)'] = 'admin/customer/change_customer_tatus/$1';
$route['admin/payout-request'] = 'admin/dashboard/payout_request';
$route['admin/payment_update/(:num)'] = 'admin/dashboard/payment_update/$1';

/* Event */
$route['admin/manage-event'] = 'admin/event/index';
$route['admin/event-booking'] = 'admin/event/event_booking';
$route['admin/event-payment'] = 'admin/event/event_payment';

$route['admin/add-event'] = 'admin/event/add_event';
$route['admin/update-event/(:num)'] = 'admin/event/update_event/$1';
$route['admin/save-event'] = 'admin/event/save_event';
$route['admin/delete-event/(:num)'] = 'admin/event/delete_event/$1';
$route['admin/delete-ticket-type'] = 'admin/event/delete_ticket_type/';
$route['vendor/delete-ticket-type'] = 'admin/event/delete_ticket_type/';

/* Services */

$route['admin/manage-service'] = 'admin/service/index';
$route['admin/add-service'] = 'admin/service/add_service';
$route['admin/update-service/(:num)'] = 'admin/service/update_service/$1';
$route['admin/save-service'] = 'admin/service/save_service';
$route['admin/delete-service/(:num)'] = 'admin/service/delete_service/$1';

$route['admin/service-booking-update'] = "admin/service/service_booking_update";
$route['vendor/service-booking-update'] = "admin/service/service_booking_update";

$route['admin/view-booking-details/(:any)'] = 'admin/service/view_booking_details/$1';
$route['vendor/view-booking-details/(:any)'] = 'admin/service/view_booking_details/$1';

/* Manage Service Addons */
$route['admin/manage-service-addons/(:num)'] = 'admin/service/addons/$1';
$route['admin/add-service-addons/(:num)'] = 'admin/service/add_service_addons/$1';
$route['admin/update-service-addons/(:num)/(:num)'] = 'admin/service/update_addons_service/$1/$2';
$route['admin/save-service-addons'] = 'admin/service/save_service_addons';
$route['admin/delete-service-addons/(:num)'] = 'admin/service/delete_service_addons/$1';

$route['admin/service-payment-details/(:num)'] = 'admin/service/service_payment_details/$1';
$route['vendor/service-payment-details/(:num)'] = 'admin/service/service_payment_details/$1';

/* Manage Vendor Service Addons */
$route['vendor/manage-service-addons/(:num)'] = 'admin/service/addons/$1';
$route['vendor/add-service-addons/(:num)'] = 'admin/service/add_service_addons/$1';
$route['vendor/update-service-addons/(:num)/(:num)'] = 'admin/service/update_addons_service/$1/$2';
$route['vendor/save-service-addons'] = 'admin/service/save_service_addons';
$route['vendor/delete-service-addons/(:num)'] = 'admin/service/delete_service_addons/$1';

/* FAQ FOR ADMIN */
$route['admin/add-faq'] = 'admin/faq/add_faq';
$route['admin/update-faq/(:num)'] = 'admin/faq/update_faq/$1';
$route['admin/manage-faq'] = 'admin/faq/index';
$route['admin/delete-faq/(:num)'] = 'admin/faq/delete_faq/$1';
$route['admin/save-faq'] = 'admin/faq/save_faq';
$route['admin/get-location/(:num)'] = 'content/get_location/$1';
$route['admin/delete-event-image'] = 'admin/event/delete_event_image';
$route['admin/delete-event-seo-image'] = 'admin/event/delete_event_seo_image';

/* Event Category */
$route['admin/event-category'] = 'admin/event/event_category';
$route['admin/add-category'] = 'admin/event/add_category';
$route['admin/update-category/(:num)'] = 'admin/event/update_category/$1';
$route['admin/save-category'] = 'admin/event/save_category';
$route['admin/check-event-category-title'] = 'admin/event/check_event_category_title';
$route['admin/delete-category/(:num)'] = 'admin/event/delete_category/$1';

/* Service Category */
$route['admin/service-category'] = 'admin/service/service_category';
$route['admin/add-service-category'] = 'admin/service/add_category';
$route['admin/update-service-category/(:num)'] = 'admin/service/update_category/$1';
$route['admin/save-service-category'] = 'admin/service/save_category';
$route['admin/check-service-category-title'] = 'admin/service/check_service_category_title';
$route['admin/delete-service-category/(:num)'] = 'admin/service/delete_service_category/$1';

/* Service Holiday */
$route['admin/holiday'] = 'admin/service/holiday';
$route['admin/add-holiday'] = 'admin/service/add_holiday';
$route['admin/update-holiday/(:num)'] = 'admin/service/update_holiday/$1';
$route['admin/save-holiday'] = 'admin/service/save_holiday';
$route['admin/delete-holiday/(:num)'] = 'admin/service/delete_holiday/$1';


/* Slider */
$route['admin/manage-slider'] = 'admin/slider/index';
$route['admin/add-slider'] = 'admin/slider/add_slider';
$route['admin/update-slider/(:num)'] = 'admin/slider/update_slider/$1';
$route['admin/save-slider'] = 'admin/slider/save_slider';
$route['admin/delete-slider/(:num)'] = 'admin/slider/delete_slider/$1';

/* Package */
$route['admin/manage-package'] = 'admin/package/index';
$route['admin/add-package'] = 'admin/package/add_package';
$route['admin/save-package'] = 'admin/package/save_package';
$route['admin/update-package/(:num)'] = 'admin/package/update_package/$1';
$route['admin/delete-package/(:num)'] = 'admin/package/delete_package/$1';
$route['admin/package-payment'] = 'admin/package/package_payment/$1';

/* Vendor */
$route['admin/vendor'] = 'admin/vendor/index';
$route['admin/unverified-vendor'] = 'admin/vendor/unverified_vendor';
$route['admin/delete-vendor/(:any)'] = 'admin/vendor/delete_vendor/$1';
$route['admin/change-vendor-status/(:any)'] = 'admin/vendor/change_vendor_tatus/$1';
$route['admin/vendor-payment'] = 'admin/vendor/vendor_payment';
$route['admin/send-vendor-payment/(:num)'] = 'admin/vendor/send_vendor_payment/$1';
$route['admin/add-vendor'] = 'admin/vendor/add_vendor';
$route['admin/update-vendor/(:num)'] = 'admin/vendor/update_vendor/$1';
$route['admin/save-vendor'] = 'admin/vendor/save_vendor';

/* Report */
$route['admin/report'] = 'admin/report/index';
$route['admin/event-booking-report'] = 'admin/report/event_booking';
$route['vendor/event-booking-report'] = 'admin/report/event_booking';
$route['admin/earnings-report'] = 'admin/report/earnings_report';
$route['admin/service-appointment-report'] = 'admin/report/service_appointment';
$route['vendor/service-appointment-report'] = 'admin/report/service_appointment';
$route['admin/customer-report'] = 'admin/report/customer_report';
$route['admin/appointment-report'] = 'admin/report/appointment_report';

/* Appointment */

$route['admin/manage-appointment'] = 'admin/appointment/index';
$route['admin/manage-appointment/(:num)'] = 'admin/appointment/index/$1';
$route['admin/manage-event-appointment/(:num)'] = 'admin/appointment/event_appointment/$1';
$route['admin/change-appointment-status/(:num)/(:any)'] = 'admin/appointment/change_appointment_status/$1/$2';

$route['admin/send-remainder'] = 'admin/appointment/send_remainder';



$route['vendor/manage-event-appointment/(:num)'] = 'admin/appointment/event_appointment/$1';
/* Site Setting */

$route['admin/setting/site'] = 'admin/sitesetting/index';
$route['admin/save-sitesetting'] = 'admin/sitesetting/save_sitesetting';

$route['admin/setting/email'] = 'admin/sitesetting/email_setting';
$route['admin/save-email-setting'] = 'admin/sitesetting/save_email_setting';

$route['admin/setting/display'] = 'admin/sitesetting/display_setting';
$route['admin/save-display-setting'] = 'admin/sitesetting/save_display_setting';

$route['admin/setting/business'] = 'admin/sitesetting/business_setting';

$route['admin/setting/currency'] = 'admin/sitesetting/currency_setting';
$route['admin/save-business-setting'] = 'admin/sitesetting/save_businesss_setting';

$route['admin/setting/payment'] = 'admin/sitesetting/payment_setting';
$route['admin/save-payment-setting'] = 'admin/sitesetting/save_payment_setting';

$route['admin/setting/vendor'] = 'admin/sitesetting/vendor_setting';
$route['admin/save-vendor-setting'] = 'admin/sitesetting/save_vendor_setting';

$route['admin/setting/display'] = 'admin/sitesetting/update_display_setting';
$route['admin/integrateon-webpage'] = 'admin/sitesetting/integrateon_webpage';


/* testimonial */
$route['admin/manage-testimonial'] = 'admin/city/index';
$route['admin/add-testimonial'] = 'admin/testimonial/add_testimonial';
$route['admin/update-testimonial/(:num)'] = 'admin/testimonial/update_testimonial/$1';
$route['admin/save-testimonial'] = 'admin/testimonial/save_testimonial';
$route['admin/check-testimonial-title'] = 'admin/testimonial/check_testimonial_title';
$route['admin/delete-testimonial/(:num)'] = 'admin/testimonial/delete_testimonial/$1';

/* City */

$route['admin/manage-city'] = 'admin/city/index';
$route['admin/default-city/(:num)'] = 'admin/city/default_city/$1';
$route['admin/add-city'] = 'admin/city/add_city';
$route['admin/update-city/(:num)'] = 'admin/city/update_city/$1';
$route['admin/save-city'] = 'admin/city/save_city';
$route['admin/check-city-title'] = 'admin/city/check_city_title';
$route['admin/delete-city/(:num)'] = 'admin/city/delete_city/$1';

/* Location */

$route['admin/manage-location'] = 'admin/location/index';
$route['admin/add-location'] = 'admin/location/add_location';
$route['admin/update-location/(:num)'] = 'admin/location/update_location/$1';
$route['admin/save-location'] = 'admin/location/save_location';
$route['admin/check-location-title'] = 'admin/location/check_location_title';
$route['admin/delete-location/(:num)'] = 'admin/location/delete_location/$1';

/* Message */
$route['admin/message'] = 'admin/message';
$route['admin/message/(:num)'] = 'admin/message/index/$1';
$route['admin/message-action'] = 'admin/message/message_action';
$route['admin/get-message'] = 'admin/message/get_message';
$route['admin/send-message'] = 'admin/message/send_message';
$route['admin/ajax-get-chats-messages'] = 'admin/message/ajax_get_chats_messages';

/* Discount Coupon Admin */
$route['admin/manage-coupon'] = 'admin/coupon/index';
$route['admin/add-coupon'] = 'admin/coupon/add_coupon';
$route['admin/update-coupon/(:num)'] = 'admin/coupon/update_coupon/$1';
$route['admin/save-coupon'] = 'admin/coupon/save_coupon';
$route['admin/delete-coupon/(:num)'] = 'admin/coupon/delete_coupon/$1';


/* language Setting */
$route['admin/manage-language'] = 'admin/language/index';
$route['admin/add-new-lang-word'] = 'admin/language/add_new_word';
$route['admin/add-language'] = 'admin/language/add_language';
$route['admin/language-translate/(:num)'] = 'admin/language/language_translate/$1';
$route['admin/update-language/(:num)'] = 'admin/language/update_language/$1';
$route['admin/save-language'] = 'admin/language/save_language';
$route['admin/save-translated-language/(:num)'] = 'admin/language/save_translated_language/$1';
$route['admin/delete-language/(:num)'] = 'admin/language/delete_language/$1';

/* Content Management */
$route['admin/manage-content'] = 'admin/page_content/index';
$route['admin/add-content'] = 'admin/page_content/add_content';
$route['admin/update-content/(:num)'] = 'admin/page_content/update_content/$1';
$route['admin/save-content'] = 'admin/page_content/save_content';
$route['admin/delete-content/(:num)'] = 'admin/page_content/delete_content/$1';
$route['admin/check-page-title'] = 'admin/page_content/check_page_title';


/* Discount Coupon vendor */
$route['vendor/manage-coupon'] = 'admin/coupon/index';
$route['vendor/add-coupon'] = 'admin/coupon/add_coupon';
$route['vendor/update-coupon/(:num)'] = 'admin/coupon/update_coupon/$1';
$route['vendor/save-coupon'] = 'admin/coupon/save_coupon';
$route['vendor/delete-coupon/(:num)'] = 'admin/coupon/delete_coupon/$1';

/*
 *
 * Vendor Folder
 *
 */

/* Vendor Content */
$route['vendor'] = 'vendor/content/login';
$route['vendor/login'] = 'vendor/content/login';
$route['vendor/logout'] = 'vendor/content/logout';
$route['vendor/profile'] = 'vendor/content/profile';
$route['vendor/login-action'] = 'vendor/content/login_action';
$route['vendor/profile-save'] = 'vendor/content/profile_save';
$route['vendor/forgot-password'] = 'vendor/content/forgot_password';
$route['vendor/forgot-password-action'] = 'vendor/content/forgot_password_action';
$route['vendor/reset-password/(:any)/(:any)'] = 'vendor/content/reset_password/$1/$2';
$route['vendor/reset-password-action'] = 'vendor/content/reset_password_action';
$route['vendor/update-password'] = 'vendor/content/update_password';
$route['vendor/update-password-action'] = 'vendor/content/update_password_action';

/* Dashboard */
$route['vendor/dashboard'] = 'vendor/dashboard/index';
$route['vendor/contact-us'] = 'vendor/dashboard/contact_us';
$route['vendor/vendor_event_inquiry_reply'] = 'vendor/dashboard/vendor_event_inquiry_reply';
$route['vendor/payment_status_update/(:num)'] = 'vendor/dashboard/payment_status_update/$1';
$route['admin/payment_status_update/(:num)'] = 'admin/dashboard/payment_status_update/$1';
$route['vendor/appointment-payments'] = 'admin/service/appointment_payment';

/* Payment Request Vendor */
$route['vendor/payout-request'] = 'vendor/dashboard/wallet';
$route['vendor/wallet'] = 'vendor/dashboard/wallet';

$route['vendor/payment-request-save'] = 'vendor/dashboard/payment_request_save';

/* Event */

$route['vendor/manage-event'] = 'admin/event/index';
$route['vendor/add-event'] = 'admin/event/add_event';
$route['vendor/update-event/(:num)'] = 'admin/event/update_event/$1';
$route['vendor/save-event'] = 'admin/event/save_event';
$route['vendor/delete-event/(:num)'] = 'admin/event/delete_event/$1';
$route['vendor/get-location/(:num)'] = 'content/get_location/$1';
$route['vendor/delete-event-image'] = 'admin/event/delete_event_image';
$route['vendor/delete-event-seo-image'] = 'admin/event/delete_event_seo_image';
$route['vendor/event-category'] = 'admin/event/event_category';
$route['vendor/add-category'] = 'admin/event/add_category';
$route['vendor/update-category/(:num)'] = 'admin/event/update_category/$1';
$route['vendor/save-category'] = 'admin/event/save_category';
$route['vendor/check-event-category-title'] = 'admin/event/check_event_category_title';
$route['vendor/delete-category/(:num)'] = 'admin/event/delete_category/$1';
$route['vendor/event-booking'] = 'admin/event/event_booking';
$route['vendor/event-payment'] = 'admin/event/event_payment';

/* service */

/* Vendor Services */
$route['vendor/manage-service'] = 'admin/service/index';
$route['vendor/add-service'] = 'admin/service/add_service';
$route['vendor/update-service/(:num)'] = 'admin/service/update_service/$1';
$route['vendor/save-service'] = 'admin/service/save_service';
$route['vendor/delete-service/(:num)'] = 'admin/service/delete_service/$1';


$route['vendor/add-service'] = 'admin/service/add_service';
$route['vendor/update-service/(:num)'] = 'admin/service/update_service/$1';
$route['vendor/save-service'] = 'admin/service/save_service';
$route['vendor/delete-service/(:num)'] = 'admin/service/delete_service/$1';
$route['vendor/delete-event-image'] = 'admin/event/delete_event_image';
$route['vendor/delete-event-seo-image'] = 'admin/event/delete_event_seo_image';
$route['vendor/service-category'] = 'admin/service/service_category';
$route['vendor/add-service-category'] = 'admin/service/add_category';
$route['vendor/update-service-category/(:num)'] = 'admin/service/update_category/$1';
$route['vendor/save-service-category'] = 'admin/service/save_category';
$route['vendor/delete-service-category/(:num)'] = 'admin/service/delete_service_category/$1';

/* Vendor Service Holiday */
$route['vendor/holiday'] = 'admin/service/holiday';
$route['vendor/add-holiday'] = 'admin/service/add_holiday';
$route['vendor/update-holiday/(:num)'] = 'admin/service/update_holiday/$1';
$route['vendor/save-holiday'] = 'admin/service/save_holiday';
$route['vendor/delete-holiday/(:num)'] = 'admin/service/delete_holiday/$1';

/* Appointment */

$route['vendor/manage-appointment'] = 'admin/appointment/index';
$route['vendor/manage-appointment/(:num)'] = 'admin/appointment/index/$1';
$route['vendor/change-appointment-status/(:num)/(:any)'] = 'admin/appointment/change_appointment_status/$1/$2';
$route['admin/change-event-booking-status/(:num)/(:any)'] = 'admin/appointment/change_event_booking_status/$1/$2';
$route['vendor/change-event-booking-status/(:num)/(:any)'] = 'admin/appointment/change_event_booking_status/$1/$2';
$route['vendor/send-remainder'] = 'admin/appointment/send_remainder';

/* City */

$route['vendor/city'] = 'admin/city/index';
$route['vendor/manage-city'] = 'admin/city/index';
$route['vendor/add-city'] = 'admin/city/add_city';
$route['vendor/update-city/(:num)'] = 'admin/city/update_city/$1';
$route['vendor/save-city'] = 'admin/city/save_city';
$route['vendor/check-city-title'] = 'admin/city/check_city_title';
$route['vendor/delete-city/(:num)'] = 'admin/city/delete_city/$1';

/* Location */

$route['vendor/location'] = 'admin/location/index';
$route['vendor/add-location'] = 'admin/location/add_location';
$route['vendor/update-location/(:num)'] = 'admin/location/update_location/$1';
$route['vendor/save-location'] = 'admin/location/save_location';
$route['vendor/check-location-title'] = 'admin/location/check_location_title';
$route['vendor/delete-location/(:num)'] = 'admin/location/delete_location/$1';

/* Membership */

$route['vendor/membership'] = 'vendor/membership/index';
$route['vendor/membership-purchase'] = 'vendor/membership/membership_purchase';
$route['vendor/get-membership-details/(:num)'] = 'vendor/membership/membership_purchase_details/$1';
$route['vendor/purchase-details/(:num)'] = 'vendor/membership/purchase_details/$1';
$route['vendor/check-package-price/(:num)'] = 'vendor/membership/check_package_price/$1';
$route['vendor/package-purchase'] = 'vendor/membership/package_purchase';
$route['vendor/membership_paypal_success'] = 'vendor/membership/membership_paypal_success';
$route['vendor/membership_paypal_cancel'] = 'vendor/membership/membership_paypal_cancel';



/* Slider */
$route['vendor/manage-slider'] = 'admin/slider/index';
$route['vendor/add-slider'] = 'admin/slider/add_slider';
$route['vendor/update-slider/(:num)'] = 'admin/slider/update_slider/$1';
$route['vendor/save-slider'] = 'admin/slider/save_slider';
$route['vendor/delete-slider/(:num)'] = 'admin/slider/delete_slider/$1';

/* Message */
$route['vendor/message'] = 'admin/message';
$route['vendor/message/(:num)'] = 'admin/message/index/$1';
$route['vendor/message-action'] = 'admin/message/message_action';
$route['vendor/get-message'] = 'admin/message/get_message';
$route['vendor/send-message'] = 'admin/message/send_message';
$route['vendor/ajax-get-chats-messages'] = 'admin/message/ajax_get_chats_messages';

/* Report */
$route['vendor/appointment-report'] = 'admin/report/appointment_report';

/* integrateon webpage */
$route['vendor/integrateon-webpage'] = 'admin/sitesetting/integrateon_webpage';

/* Package */
$route['admin/manage-package'] = 'admin/package/index';
$route['admin/add-package'] = 'admin/package/add_package';
$route['admin/save-package'] = 'admin/package/save_package';
$route['admin/update-package/(:num)'] = 'admin/package/update_package/$1';
$route['admin/delete-package/(:num)'] = 'admin/package/delete_package/$1';
$route['admin/package-payment'] = 'admin/package/package_payment/$1';


$route['get-service-slidepanel-details/(:num)'] = 'content/get_service_slidepanel_details/$1';
$route['get-event-slidepanel-details/(:num)'] = 'content/get_event_slidepanel_details/$1';
$route['appointment-payment-details/(:num)'] = 'content/appointment_payment_details/$1';
$route['event-payment-details/(:num)'] = 'content/event_payment_details/$1';
$route['payout-request-details/(:num)'] = 'content/payout_request_details/$1';

/* Staff Module */
$route['staff'] = 'staff/content/login';
$route['staff/login'] = 'staff/content/login';
$route['staff/logout'] = 'staff/content/logout';
$route['staff/profile'] = 'staff/content/profile';
$route['staff/login-action'] = 'staff/content/login_action';
$route['staff/profile-save'] = 'staff/content/profile_save';
$route['staff/forgot-password'] = 'staff/content/forgot_password';
$route['staff/forgot-password-action'] = 'staff/content/forgot_password_action';
$route['staff/reset-password/(:any)/(:any)'] = 'staff/content/reset_password/$1/$2';
$route['staff/reset-password-action'] = 'staff/content/reset_password_action';
$route['staff/update-password'] = 'staff/content/update_password';
$route['staff/update-password-action'] = 'staff/content/update_password_action';

/* Dashboard */
$route['staff/dashboard'] = 'staff/dashboard/index';
$route['staff/contact-us'] = 'staff/dashboard/contact_us';
$route['staff/appointment'] = 'staff/dashboard/appointment';
$route['staff/change-appointment/(:num)/(:any)'] = 'staff/dashboard/change_appointment/$1/$2';
$route['staff/send-remainder'] = 'staff/dashboard/send_remainder';
$route['staff/view-booking-details/(:any)'] = 'staff/dashboard/view_booking_details/$1';



/* Embed Integrate On Your Website */
$route['something-wrong'] = 'embed/something_wrong';
$route['eservices/(:any)'] = 'embed/services/$1';
$route['eservices-details/(:any)/(:any)/(:num)'] = 'embed/services_details/$1/$2/$3';
$route['eslots/(:any)/(:num)'] = 'embed/day_slots/$1/$2';
$route['eslots/(:any)/(:num)/(:num)'] = 'embed/day_slots/$1/$2/$3';
$route['etime-slots/(:num)'] = "embed/time_slots/$1";
$route['ebooking-oncash'] = "embed/booking_oncash";
$route['ebooking-stripe'] = "embed/booking_stripe";
$route['ebooking-paypal'] = "embed/booking_paypal";
$route['ebooking-free'] = "embed/booking_free";
$route['epaypal_success'] = 'embed/paypal_success';
$route['epaypal_cancel'] = 'embed/paypal_cancel';
$route['eappointment-success/(:num)/(:any)'] = 'embed/appointment_success/$1/$2';


$route['eevents/(:any)'] = 'embed/events/$1';
$route['eevent-details/(:any)/(:any)/(:num)'] = 'embed/event_details/$1/$2/$3';
$route['eevent-booking-oncash'] = "embed/event_booking_oncash";
$route['eevent-booking-stripe'] = "embed/event_booking_stripe";
$route['eevent-booking-paypal'] = "embed/event_booking_paypal";
$route['eevent-booking-free'] = "embed/event_booking_free";
$route['eevent_paypal_success'] = 'embed/event_paypal_success';
$route['eevent_paypal_cancel'] = 'embed/event_paypal_cancel';

$route['econtact-action'] = 'embed/contact_action';
