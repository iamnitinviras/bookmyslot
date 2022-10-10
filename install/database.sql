DROP TABLE IF EXISTS `app_admin`;
CREATE TABLE IF NOT EXISTS `app_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_image` varchar(100) NOT NULL DEFAULT '',
  `first_name` varchar(100) CHARACTER SET utf8 NOT NULL,
  `last_name` varchar(100) CHARACTER SET utf8 NOT NULL,
  `email` varchar(100) CHARACTER SET utf8 NOT NULL,
  `password` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL DEFAULT '',
  `address` text CHARACTER SET utf8,
  `status` enum('A','I','D','P') NOT NULL DEFAULT 'P' COMMENT 'A:Approved ,P:Pending ,I:Inactive,D:Deleted,C:Completed',
  `profile_status` enum('V','N','R') NOT NULL DEFAULT 'N' COMMENT 'V:Verify , N:Not Verify',
  `profile_cover_image` varchar(100) DEFAULT NULL,
  `type` enum('A','V','S') NOT NULL DEFAULT 'A' COMMENT 'A:Admin,V:Vendor',
  `my_wallet` decimal(18,2) NOT NULL DEFAULT '0.00',
  `my_earning` decimal(18,2) NOT NULL DEFAULT '0.00',
  `designation` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `company_name` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `website` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `package_id` int(11) NOT NULL DEFAULT '0',
  `membership_till` date DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `profile_text` text,
  `fb_link` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `twitter_link` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `google_link` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `instagram_link` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `default_password_changed` int(11) NOT NULL DEFAULT '0',
  `reset_password_check` int(11) NOT NULL DEFAULT '0',
  `reset_password_requested_on` datetime DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `cash_payment` decimal(18,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `app_appointment_payment`
--

DROP TABLE IF EXISTS `app_appointment_payment`;
CREATE TABLE IF NOT EXISTS `app_appointment_payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL DEFAULT '0',
  `vendor_id` int(11) NOT NULL DEFAULT '0',
  `event_id` int(11) NOT NULL DEFAULT '0',
  `booking_id` int(11) NOT NULL DEFAULT '0',
  `payment_id` varchar(255) NOT NULL,
  `customer_payment_id` varchar(255) NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `response_details` text NOT NULL,
  `payment_price` decimal(18,2) NOT NULL DEFAULT '0.00',
  `vendor_price` decimal(18,2) NOT NULL,
  `admin_price` decimal(18,2) NOT NULL,
  `failure_code` varchar(255) DEFAULT NULL,
  `failure_message` text,
  `payment_method` varchar(100) NOT NULL,
  `payment_status` enum('S','P','F') NOT NULL DEFAULT 'P',
  `transfer_status` enum('S','P','F') NOT NULL DEFAULT 'P' COMMENT 'S:Success, F:Fail, P:Pending',
  `created_on` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `event_id` (`event_id`),
  KEY `booking_id` (`booking_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `app_chat`
--

DROP TABLE IF EXISTS `app_chat`;
CREATE TABLE IF NOT EXISTS `app_chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `chat_id` int(11) NOT NULL DEFAULT '0',
  `from_id` int(11) NOT NULL DEFAULT '0',
  `to_id` int(11) NOT NULL DEFAULT '0',
  `message` text CHARACTER SET utf8 NOT NULL,
  `chat_type` enum('C','NC') NOT NULL DEFAULT 'NC' COMMENT 'C:Customer:NC:Not Customer',
  `msg_read` enum('Y','N') NOT NULL DEFAULT 'N',
  `created_on` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `app_chat_master`
--

DROP TABLE IF EXISTS `app_chat_master`;
CREATE TABLE IF NOT EXISTS `app_chat_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL DEFAULT '0',
  `vendor_id` int(11) NOT NULL DEFAULT '0',
  `created_on` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `app_city`
--

DROP TABLE IF EXISTS `app_city`;
CREATE TABLE IF NOT EXISTS `app_city` (
  `city_id` int(11) NOT NULL AUTO_INCREMENT,
  `city_title` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `city_status` enum('A','I') NOT NULL DEFAULT 'A' COMMENT 'A=active,I=inactive',
  `is_default` int(11) NOT NULL DEFAULT '0',
  `city_created_by` int(11) NOT NULL DEFAULT '0',
  `city_created_on` datetime DEFAULT NULL,
  `city_updated_on` datetime DEFAULT NULL,
  PRIMARY KEY (`city_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `app_contact_us`
--

DROP TABLE IF EXISTS `app_contact_us`;
CREATE TABLE IF NOT EXISTS `app_contact_us` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL DEFAULT '0',
  `admin_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(100) CHARACTER SET utf8 NOT NULL,
  `email` varchar(100) CHARACTER SET utf8 NOT NULL,
  `phone` varchar(50) NOT NULL,
  `subject` text CHARACTER SET utf8 NOT NULL,
  `message` text CHARACTER SET utf8 NOT NULL,
  `created_on` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `app_content`
--

DROP TABLE IF EXISTS `app_content`;
CREATE TABLE IF NOT EXISTS `app_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) CHARACTER SET utf8 NOT NULL,
  `description` text CHARACTER SET utf8 NOT NULL,
  `slug` varchar(100) CHARACTER SET utf8 NOT NULL,
  `status` enum('A','I') NOT NULL DEFAULT 'A',
  `created_on` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `app_coupon`
--

DROP TABLE IF EXISTS `app_coupon`;
CREATE TABLE IF NOT EXISTS `app_coupon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text CHARACTER SET utf8 NOT NULL,
  `valid_till` date NOT NULL,
  `event_id` varchar(255) NOT NULL,
  `code` varchar(255) CHARACTER SET utf8 NOT NULL,
  `discount_type` enum('P','A') NOT NULL COMMENT 'P=Percentage, A=Amount',
  `discount_value` decimal(18,2) NOT NULL,
  `status` enum('A','I','E') NOT NULL COMMENT 'A=Active,I=Inactive,E=Expire',
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `app_currency`
--

DROP TABLE IF EXISTS `app_currency`;
CREATE TABLE IF NOT EXISTS `app_currency` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) CHARACTER SET utf8 NOT NULL,
  `code` varchar(100) CHARACTER SET utf8 NOT NULL,
  `currency_code` varchar(100) CHARACTER SET utf8 NOT NULL,
  `stripe_supported` enum('Y','N') NOT NULL DEFAULT 'N',
  `paypal_supported` enum('Y','N') NOT NULL DEFAULT 'N',
  `2checkout_supported` enum('Y','N') NOT NULL DEFAULT 'N',
  `display_status` enum('A','I') NOT NULL DEFAULT 'I',
  `status` enum('A','I') NOT NULL DEFAULT 'A',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `app_currency`
--

INSERT INTO `app_currency` (`id`, `title`, `code`, `currency_code`, `stripe_supported`, `paypal_supported`, `2checkout_supported`, `display_status`, `status`) VALUES
(1, 'United States dollar', 'USD', '&#36;', 'Y', 'Y', 'Y', 'A', 'A');

-- --------------------------------------------------------

--
-- Table structure for table `app_customer`
--

DROP TABLE IF EXISTS `app_customer`;
CREATE TABLE IF NOT EXISTS `app_customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_image` varchar(100) NOT NULL,
  `first_name` varchar(100) CHARACTER SET utf8 NOT NULL,
  `last_name` varchar(100) CHARACTER SET utf8 NOT NULL,
  `email` varchar(100) CHARACTER SET utf8 NOT NULL,
  `password` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `status` enum('A','I','D','P') NOT NULL DEFAULT 'P' COMMENT 'A:Active,I:Inactive,D:Deleted,P:pending',
  `default_password_changed` int(11) NOT NULL DEFAULT '0',
  `reset_password_check` int(11) NOT NULL DEFAULT '0',
  `reset_password_requested_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL DEFAULT '0',
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `app_email_log`
--

DROP TABLE IF EXISTS `app_email_log`;
CREATE TABLE IF NOT EXISTS `app_email_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) CHARACTER SET utf8 NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `subject` text CHARACTER SET utf8 NOT NULL,
  `content` text CHARACTER SET utf8 NOT NULL,
  `status` enum('Sent','Not Sent') NOT NULL,
  `created_date` datetime DEFAULT NULL,
  `details` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `app_email_setting`
--

DROP TABLE IF EXISTS `app_email_setting`;
CREATE TABLE IF NOT EXISTS `app_email_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mail_type` enum('S','P') NOT NULL DEFAULT 'S',
  `smtp_host` varchar(255) CHARACTER SET utf8 NOT NULL,
  `smtp_username` varchar(255) CHARACTER SET utf8 NOT NULL,
  `smtp_password` varchar(255) CHARACTER SET utf8 NOT NULL,
  `smtp_port` int(11) NOT NULL,
  `smtp_secure` varchar(255) CHARACTER SET utf8 NOT NULL,
  `email_from` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `app_event`
--

DROP TABLE IF EXISTS `app_event`;
CREATE TABLE IF NOT EXISTS `app_event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL DEFAULT '0',
  `staff` varchar(255) NOT NULL,
  `type` enum('S','E') NOT NULL DEFAULT 'S' COMMENT '"S=service,E=event"',
  `title` varchar(100) CHARACTER SET utf8 NOT NULL,
  `event_slug` text CHARACTER SET utf8 NOT NULL,
  `description` text CHARACTER SET utf8 NOT NULL,
  `total_seat` int(11) DEFAULT NULL,
  `event_limit_type` enum('U','L') NOT NULL DEFAULT 'U' COMMENT '"u=unlimited,L=limited"',
  `days` text NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `padding_time` int(11) NOT NULL DEFAULT '0',
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `slot_time` int(11) NOT NULL DEFAULT '15',
  `monthly_allow` int(11) NOT NULL DEFAULT '1',
  `multiple_slotbooking_allow` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT 'Y=yes,N=no',
  `multiple_slotbooking_limit` int(11) NOT NULL DEFAULT '0',
  `city` varchar(100) NOT NULL,
  `location` varchar(100) NOT NULL DEFAULT '0',
  `is_display_address` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT 'Y=yes,N=no',
  `address` text,
  `address_map_link` text NOT NULL,
  `latitude` double(18,2) NOT NULL DEFAULT '0.00',
  `longitude` double(18,2) NOT NULL DEFAULT '0.00',
  `image` text NOT NULL,
  `thumb_image` varchar(100) CHARACTER SET utf8 NOT NULL,
  `payment_type` enum('F','P') NOT NULL DEFAULT 'F' COMMENT 'F:Free,P:Paid',
  `price` decimal(18,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(18,2) NOT NULL DEFAULT '0.00',
  `discounted_price` decimal(18,2) DEFAULT '0.00',
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  `status` enum('A','I','E','SS') NOT NULL COMMENT 'A:Active,I:Inactive,E:Expired,SS:Service Sespended',
  `seo_description` text CHARACTER SET utf8,
  `seo_keyword` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `faq` text CHARACTER SET utf8 NOT NULL,
  `seo_og_image` varchar(100) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '0',
  `created_on` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `app_event_book`
--

DROP TABLE IF EXISTS `app_event_book`;
CREATE TABLE IF NOT EXISTS `app_event_book` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL DEFAULT '0',
  `description` text CHARACTER SET utf8,
  `start_date` date NOT NULL,
  `start_time` time NOT NULL,
  `slot_time` int(11) NOT NULL DEFAULT '0',
  `event_id` int(11) NOT NULL DEFAULT '0',
  `staff_id` int(11) NOT NULL,
  `addons_id` varchar(255) NOT NULL,
  `type` enum('E','S') NOT NULL DEFAULT 'S' COMMENT '"E=event,S=service"',
  `event_booked_seat` int(11) DEFAULT NULL,
  `price` decimal(18,2) NOT NULL,
  `vendor_price` decimal(18,2) NOT NULL,
  `admin_price` decimal(18,2) NOT NULL,
  `invoice_file` varchar(100) DEFAULT NULL,
  `payment_status` enum('S','F','P','IN') NOT NULL DEFAULT 'P' COMMENT 'S:Success, F:Fail, P:Pending',
  `status` enum('A','P','R','D','C','IN') NOT NULL DEFAULT 'IN' COMMENT 'A:Approved ,P:Pending , R:Rejected ,D:Deleted,C:Completed',
  `created_on` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`),
  KEY `event_id` (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `app_event_category`
--

DROP TABLE IF EXISTS `app_event_category`;
CREATE TABLE IF NOT EXISTS `app_event_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `category_slug` text CHARACTER SET utf8 NOT NULL,
  `status` enum('A','I') NOT NULL DEFAULT 'A' COMMENT 'A=active,I=inactive',
  `type` enum('E','S') NOT NULL DEFAULT 'S',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `event_category_image` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `app_event_sponsor`
--

DROP TABLE IF EXISTS `app_event_sponsor`;
CREATE TABLE IF NOT EXISTS `app_event_sponsor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `sponsor_name` varchar(100) CHARACTER SET utf8 NOT NULL,
  `website_link` varchar(100) CHARACTER SET utf8 NOT NULL,
  `sponsor_image` varchar(100) CHARACTER SET utf8 NOT NULL,
  `updated_on` datetime DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `event_id` (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `app_event_ticket_type`
--

DROP TABLE IF EXISTS `app_event_ticket_type`;
CREATE TABLE IF NOT EXISTS `app_event_ticket_type` (
  `ticket_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `ticket_type_title` varchar(255) CHARACTER SET utf8 NOT NULL,
  `ticket_type_price` decimal(12,2) NOT NULL,
  `total_seat` int(11) NOT NULL,
  `available_ticket` int(11) NOT NULL DEFAULT '0',
  `status` enum('A','I','E','') NOT NULL DEFAULT 'A',
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_on` datetime DEFAULT NULL,
  PRIMARY KEY (`ticket_type_id`),
  KEY `event_id` (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `app_event_ticket_type_booking`
--

DROP TABLE IF EXISTS `app_event_ticket_type_booking`;
CREATE TABLE IF NOT EXISTS `app_event_ticket_type_booking` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `ticket_type_id` int(11) NOT NULL,
  `total_ticket` int(11) NOT NULL DEFAULT '0',
  `status` enum('A','P','R','D','C','IN') NOT NULL DEFAULT 'IN',
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `app_faq`
--

DROP TABLE IF EXISTS `app_faq`;
CREATE TABLE IF NOT EXISTS `app_faq` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) CHARACTER SET utf8 NOT NULL,
  `description` text CHARACTER SET utf8 NOT NULL,
  `status` enum('A','I') NOT NULL DEFAULT 'A',
  `created_on` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `app_language`
--

DROP TABLE IF EXISTS `app_language`;
CREATE TABLE IF NOT EXISTS `app_language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text CHARACTER SET utf8 NOT NULL,
  `db_field` text CHARACTER SET utf8 NOT NULL,
  `status` enum('A','I') NOT NULL DEFAULT 'A' COMMENT 'A=active,I=inactive',
  `created_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `app_language`
--

INSERT INTO `app_language` (`id`, `title`, `db_field`, `status`, `created_date`) VALUES
(1, 'english', 'english', 'A', '2018-07-13 17:17:42');

-- --------------------------------------------------------

--
-- Table structure for table `app_language_data`
--

DROP TABLE IF EXISTS `app_language_data`;
CREATE TABLE IF NOT EXISTS `app_language_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `default_text` longtext CHARACTER SET utf8 NOT NULL,
  `english` text CHARACTER SET utf8,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=720 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `app_language_data`
--

INSERT INTO `app_language_data` (`id`, `default_text`, `english`) VALUES
(1, 'login', 'Login'),
(2, 'logout', 'Logout'),
(3, 'return', 'Return'),
(4, 'now', 'Now'),
(5, 'required_message', 'This field is required.'),
(6, 'login_success', 'You have logged in successfully.'),
(7, 'login_failure', 'Please check your email or password and try again.'),
(8, 'forgot_password', 'Forgot Password'),
(9, 'forgot_mail_message', 'Need to reset your password?'),
(10, 'forgot_mail_content', 'We have received a request to reset your password. You can change your password by hitting the button below.'),
(11, 'reset_password', 'Reset Password'),
(12, 'forgot_success', 'Reset password link has been sent successfully.'),
(13, 'forgot_failure', 'Email not registered with system.'),
(14, 'reset_failure', 'Reset password link has been expired. Please try again.'),
(15, 'invalid_request', 'Invalid request. Please try again.'),
(16, 'reset_success', 'Your password has been changed successfully.'),
(17, 'update_password', 'Update Password'),
(18, 'current_password_failure', 'Your old password is invalid. Please try again.'),
(19, 'profile_success', 'Your profile has been updated successfully.'),
(20, 'logout_success', 'Logout successfully...'),
(21, 'login_required_for_book', 'You can not book slot. please login and try again.'),
(22, 'vendor_verify_failure', 'Account verification link has been expired. Please try again.'),
(23, 'already_vendor_verify', 'Account already verify.'),
(24, 'account_verify_success', 'Account verified successfully.'),
(25, 'vendor_not_verify', 'Account not verified. <br> <a href=\"{resend_url}\">Resend</a>  verification link'),
(26, 'account_verification_content', 'Your account has been created successfully.Please click on verification button to activate account.'),
(27, 'image', 'Image'),
(28, 'edit', 'Edit'),
(29, 'update', 'Update'),
(30, 'add', 'Add'),
(31, 'email', 'Email'),
(32, 'thank', 'Thank'),
(33, 'you', 'you'),
(34, 'password', 'Password'),
(35, 'first_name', 'First Name'),
(36, 'last_name', 'Last Name'),
(37, 'phone', 'Phone'),
(38, 'choose_file', 'Choose File'),
(39, 'submit', 'Submit'),
(40, 'subject', 'Subject'),
(41, 'purpose', 'Purpose'),
(42, 'description', 'Description'),
(43, 'appointment_date', 'Appointment Date'),
(44, 'created_date', 'Created Date'),
(45, 'action', 'Action'),
(46, 'info', 'Info'),
(47, 'password_length', 'Password must be 8 characters long.'),
(48, 'password_lowercase', 'Must contain at least one lowercase letter.'),
(49, 'password_uppercase', 'Must contain at least one uppercase letter.'),
(50, 'password_numeric', 'Must contain at least one numeric digit and one special character.'),
(51, 'confirm_password', 'Confirm Password'),
(52, 'Change_password', 'Change Password'),
(53, 'payment_history', 'Payment History'),
(55, 'profile_image', 'Profile Image'),
(56, 'upload_your_file', 'Upload your file'),
(57, 'manage', 'Manage'),
(58, 'event', 'Event'),
(59, 'appointment', 'Appointment'),
(60, 'delete', 'Delete'),
(61, 'confirm', 'Confirm'),
(62, 'close', 'Close'),
(63, 'total', 'Total'),
(64, 'customer', 'Customer'),
(66, 'active', 'Active'),
(67, 'inactive', 'Inactive'),
(68, 'title', 'Title'),
(69, 'select', 'Select'),
(70, 'delete_confirm', 'Are you sure you want to delete this record?'),
(71, 'home', 'Home'),
(72, 'new_account_mail', ' Your account has been created successfully.Your login credential are stated as below.'),
(73, 'admin_login', 'Admin Login'),
(74, 'rights_reserved_message', ' All Rights Reserved.'),
(75, 'protected_message', 'This is login protected. Please login now to view this.'),
(76, 'status', 'Status'),
(77, 'save', 'Save'),
(78, 'profile', 'Profile'),
(79, 'facebook_link', 'Facebook Link'),
(80, 'twitter_link', 'Twitter Link'),
(81, 'google_link', 'Google+ Link'),
(82, 'profile_text', 'Profile Text'),
(83, 'profile_under_review', 'Your account is under verification. You are not allowed view this.'),
(87, 'purchase', 'Purchase'),
(88, 'message', 'Message'),
(89, 'no_found', 'No Found'),
(90, 'my_amount', 'My Amount'),
(91, 'view_details', 'View Details'),
(92, 'appointment_details', 'Appointment Details'),
(93, 'profile_setting', 'Profile Setting'),
(94, 'dashboard', 'Dashboard'),
(97, 'site_setting', 'Site Setting'),
(98, 'email_setting', 'Email Setting'),
(99, 'delete_install', 'Please delete install directory.'),
(100, 'upcoming_appointment', 'Upcoming Appointment'),
(101, 'mandatory_update', 'Mandatory Update'),
(102, 'mandatory_message', 'Please follow below steps to getting started with service & event.'),
(103, 'mandatory_category', 'Please insert minimum one service category'),
(104, 'mandatory_payment', 'Please select payment method'),
(105, 'mandatory_city', 'Please insert minimum one city'),
(106, 'mandatory_location', 'Please insert minimum one location'),
(109, 'appointment_booking', 'Appointment Booking'),
(110, 'back_top', 'Back To Top'),
(111, 'customer_profile', 'Customer Profile'),
(112, 'register', 'Register'),
(113, 'package', 'Package'),
(114, 'vendor_register', 'Vendor Register'),
(115, 'vendor_registration', 'Vendor Registration'),
(116, 'account_registration', 'Account Registration'),
(117, 'register_mail_message', 'Your account has been created successfully. Please login to your credential.'),
(118, 'register_success', 'Your account has been created successfully.'),
(119, 'customer_login', 'Customer Login'),
(120, 'create_account', 'Create an account'),
(121, 'dont_have_account', 'Don`t have an account'),
(123, 'click_here', 'Click Here'),
(124, 'morning', 'Morning'),
(125, 'noon', 'Noon'),
(126, 'booking_insert', 'Congratulation!! Your booking request has been processed successfully. '),
(127, 'booking_update', 'Appointment has been updated successfully'),
(128, 'appointment_delete', 'Appointment has been deleted successfully.'),
(129, 'select_a_day', 'Select a Day'),
(130, 'today', 'Today'),
(131, 'available', 'Available'),
(132, 'unavailable', 'Unavailable'),
(133, 'today_unavailable', 'Today Unavailable'),
(134, 'select_a_time', 'Select a Time'),
(135, 'booking', 'Booking'),
(136, 'book', 'Book'),
(137, 'appointment_time', 'Appointment Time'),
(138, 'book_your_event', 'Book Your Event'),
(139, 'event_information', 'Event Information'),
(140, 'event_category_image', 'Category Image'),
(141, 'user_registration', 'Customer Registration'),
(142, 'event_time', '{slot_time} Minute'),
(143, 'rating', 'Rating'),
(144, 'on_cash', 'Cash'),
(145, 'debit_card', 'Cards (Credit/Debit)'),
(146, 'card_number', 'Credit/Debit Card Number'),
(147, 'expiry', 'Expiry (MM/YY)'),
(148, 'payment', 'Payment'),
(149, 'cvv', 'CVV'),
(150, 'name_on_card', 'Name on Card'),
(151, 'approved', 'Approved'),
(152, 'pending', 'Pending'),
(153, 'rejected', 'Rejected'),
(154, 'deleted', 'Deleted'),
(155, 'view_event', 'View Event'),
(156, 'event_details', 'Event Details'),
(157, 'booking_date', 'Booking Date'),
(158, 'details', 'Details'),
(159, 'profile_text_content', 'Welcome'),
(160, 'customer_name', 'Customer Name'),
(161, 'event_category_exist', 'You are not allowed to delete this category.'),
(163, 'slot_time', 'Slot Time'),
(164, 'in_min', 'In Min.'),
(165, 'minute', 'Minute'),
(166, 'change_status', 'Change Status'),
(167, 'appointment_status', 'Appointment has been {status}.'),
(168, 'appointment_action', 'Appointment Action'),
(169, 'completed', 'Completed'),
(170, 'success', 'Success'),
(171, 'failed', 'Failed'),
(173, 'customer_deleted', 'Customer has been deleted successfully.'),
(174, 'customer_status', 'Customer status has been changed successfully.'),
(175, 'customer_email', 'Customer Email'),
(176, 'event_update', 'Event has been updated successfully.'),
(177, 'event_insert', 'Event has been inserted successfully.'),
(178, 'event_delete', 'Event has been deleted successfully.'),
(179, 'event_category_update', 'Event Category has been updated successfully.'),
(180, 'event_category_insert', 'Event Category has been inserted successfully.'),
(181, 'event_category_delete', 'Event Category has been deleted successfully.'),
(182, 'event_book_Appointment', 'You Cannot delete. It is associated with booking.'),
(183, 'days', 'Days'),
(184, 'monday', 'Monday'),
(185, 'tuesday', 'Tuesday'),
(186, 'wednesday', 'Wednesday'),
(187, 'thursday', 'Thursday'),
(188, 'friday', 'Friday'),
(189, 'saturday', 'Saturday'),
(190, 'sunday', 'Sunday'),
(191, 'start_time', 'Start Time'),
(192, 'end_time', 'End Time'),
(194, 'per_allow', 'Per Customer Monthly Book'),
(195, 'total_booking', 'Total Booking'),
(196, 'select_city', 'Select City'),
(197, 'select_location', 'Select Location'),
(198, 'select_city_first', 'Select City First'),
(199, 'event_image', 'Event Image'),
(200, 'more', 'More'),
(201, 'event_image_preview', 'Event Image Preview'),
(202, 'price', 'Price'),
(203, 'free', 'Free'),
(204, 'paid', 'Paid'),
(205, 'manage_event_category', 'Manage Event Category'),
(206, 'add_event_category', 'Add Event Category'),
(207, 'event_category', 'Event Category'),
(208, 'category', 'Category'),
(210, 'site_setting_update', 'Site setting details updated successfully.'),
(211, 'smtp_update', 'Smtp details updated successfully.'),
(212, 'valid_image', 'Please select valid image(jpg, png, jpeg, gif extension only)'),
(213, 'valid_logo', 'Please check your image. It must be in minimum dimension of 241 x 61'),
(214, 'valid_banner', 'Please check your image. It must be in minimum dimension of 1900 x 500'),
(215, 'valid_logo_size', 'Size must be minimum of 241*61'),
(216, 'valid_banner_size', 'Size must be minimum of 1900*500'),
(217, 'something_wrong_group', 'Something wrong with this group'),
(218, 'something_wrong', 'Something wrong.'),
(219, 'smtp_host', 'Smtp Host'),
(220, 'smtp_secure', 'Smtp Secure'),
(221, 'username', 'Username'),
(222, 'port', 'Port'),
(223, 'from_name', 'From Name'),
(224, 'site_name', 'Site Name'),
(225, 'site_email', 'Site Email'),
(226, 'site_phone', 'Site Phone'),
(227, 'address', 'Address'),
(228, 'language', 'Language'),
(229, 'next', 'Next'),
(230, 'basic', 'Basic'),
(231, 'information', 'Information'),
(232, 'social', 'Social'),
(233, 'media', 'Media'),
(234, 'personal', 'Personal'),
(235, 'data', 'Data'),
(236, 'terms_conditions', 'Terms and Conditions'),
(237, 'previous', 'Previous'),
(238, 'facebook', 'Facebook'),
(239, 'google+', 'Google+'),
(240, 'twitter', 'Twitter'),
(241, 'instagram', 'Instagram'),
(242, 'linkdin', 'Linkdin'),
(243, 'link', 'Link'),
(244, 'company', 'Company'),
(245, 'english', 'English'),
(246, 'logo', 'Logo'),
(247, 'time_zone', 'TimeZone'),
(248, 'rating_review', 'Rating / Review'),
(249, 'review', 'Review'),
(250, 'submit_rating_review', 'Submit Rating / Review'),
(251, 'no_record_found', 'No Record Found'),
(252, 'payment_setting', 'Payment Setting'),
(253, 'payment_status', 'Payment Status'),
(254, 'update_payment_setting', 'Update Payment Setting'),
(255, 'stripe', 'Stripe'),
(256, 'yes', 'Yes'),
(257, 'no', 'No'),
(258, 'stripe_secret_key', 'Stripe Secret Key'),
(259, 'stripe_publish_key', 'Stripe Publish Key'),
(260, 'payment_setting_update', 'Payment setting has been updated successfully.'),
(261, 'master', 'Master'),
(262, 'city', 'City'),
(263, 'location', 'Location'),
(264, 'city_update', 'City has been updated successfully.'),
(265, 'city_insert', 'City has been inserted successfully.'),
(266, 'city_delete', 'City has been deleted successfully.'),
(267, 'location_update', 'Location has been updated successfully.'),
(268, 'location_insert', 'Location has been inserted successfully.'),
(269, 'location_delete', 'Location has been deleted successfully.'),
(270, 'vendor', 'Vendor'),
(271, 'vendor_list', 'Vendor List'),
(272, 'vendor_login', 'Vendor Login'),
(273, 'name', 'Name'),
(274, 'package_name', 'Package Name'),
(275, 'company_name', 'Company Name'),
(277, 'website', 'Website'),
(278, 'vendor_deleted', 'Vendor has been deleted successfully.'),
(279, 'vendor_status', 'Vendor status has been changed successfully.'),
(280, 'vendor_Payment', 'Payment Request'),
(281, 'event_creater', 'Event Creator'),
(282, 'transfer_status', 'Transfer Status'),
(283, 'send', 'Send'),
(284, 'vendor_payment_send', 'Vendor appointment payment sent successfully.'),
(286, 'payment_type', 'Payment Type'),
(288, 'card', 'Card'),
(289, 'package_update', 'Package has been updated successfully.'),
(290, 'package_insert', 'Package has been inserted successfully.'),
(291, 'package_delete', 'Package has been deleted successfully.'),
(292, 'package_list', 'Package List'),
(293, 'package_payment', 'Package Payment'),
(295, 'max_event', 'Max Event'),
(296, 'remaining_event', 'Remaining Event'),
(298, 'expired', 'Expired'),
(299, 'payment_method', 'Payment Method'),
(301, 'cancel', 'Cancel'),
(302, 'transaction_fail', 'Your transaction is failed.Please try again.'),
(303, 'transaction_success_event', 'Your payment has been processed successfully.'),
(304, 'invalid_card', 'Invalid card details.Please try again.'),
(306, 'select_payment', 'Select payment method first.'),
(307, 'gallery_image', 'Gallery Image'),
(308, 'gallery_update', 'Gallery Image has been updated successfully.'),
(309, 'gallery_insert', 'Gallery Image has been inserted successfully.'),
(310, 'gallery_delete', 'Gallery Image has been deleted successfully.'),
(311, 'report', 'Report'),
(312, 'manage_report', 'Manage Report'),
(313, 'vendor_report', 'Vendor Report'),
(314, 'customer_report', 'Customer Report'),
(315, 'select_month', 'Select Month'),
(316, 'select_year', 'Select Year'),
(317, 'January', 'January'),
(318, 'february', 'February'),
(319, 'march', 'March'),
(320, 'april', 'April'),
(321, 'may', 'May'),
(322, 'june', 'June'),
(323, 'july', 'July'),
(324, 'august', 'August'),
(325, 'september', 'September'),
(326, 'october', 'October'),
(327, 'november', 'November'),
(328, 'december', 'December'),
(329, 'monthly_new_customer', 'Monthly New Customer'),
(330, 'monthly_new_vendor', 'Monthly New Vendor'),
(331, 'monthly_appointment', 'Monthly Appointment'),
(332, 'new_customer', 'New Customer'),
(333, 'new_vendor', 'New Vendor'),
(334, 'date', 'Date'),
(335, 'appointment_report', 'Appointment Report'),
(336, 'select_vendor', 'Select Vendor'),
(337, 'month', 'Month'),
(338, 'invoice', 'Invoice'),
(339, 'download', 'Download'),
(340, 'author', 'Author'),
(341, 'your_rating', 'Your Rating'),
(342, 'rating_item', 'Rate Now'),
(343, 'full_name', 'Full Name'),
(344, 'member_join', 'Member Since'),
(345, 'fevicon_icon', 'Fevicon Icon'),
(346, 'vendor_mail_success', 'We have sent an email with a confirmation link to your email address. Please check your email to confirm your account.'),
(348, 'event_coupon', 'Discount Coupon'),
(349, 'language_setting', 'Language Setting'),
(350, 'manage_language', 'Manage Language'),
(351, 'language_translate', 'Language Translate'),
(352, 'language_add', 'Language has been added successfully.'),
(353, 'language_update', 'Language has been updated successfully.'),
(354, 'language_delete', 'Language has been deleted successfully.'),
(355, 'language_used', 'Language is already in use. You are not allowed to delete.'),
(356, 'translate_word', 'Translate Word'),
(357, 'translated_word', 'Translated Word'),
(358, 'translate', 'Translate'),
(359, 'words', 'Words'),
(360, 'coupon', 'Coupon'),
(361, 'code', 'Code'),
(362, 'added_by', 'Added By'),
(363, 'discount_type', 'Discount Type'),
(364, 'discount_value', 'Discount Value'),
(365, 'amount', 'Amount'),
(366, 'percentage', 'Percentage'),
(367, 'coupon_title', 'Coupon Title'),
(368, 'expiry_date', 'Expiry Date'),
(369, 'coupon_discount_on', 'Coupon Discount On'),
(370, 'coupon_update', 'Coupon has been updated successfully.'),
(371, 'coupon_insert', 'Coupon has been added successfully.'),
(372, 'coupon_delete', 'Coupon has been deleted successfully.'),
(373, 'paypal_merchant_email', 'PayPal Merchant Email'),
(374, 'paypal', 'PayPal'),
(375, 'payment_by', 'Payment By'),
(376, 'booking_note', 'Booking Note'),
(377, 'paypal_mode', 'PayPal Mode'),
(378, 'paypal_sendbox', 'PayPal Sandbox'),
(379, 'paypal_live', 'PayPal Live'),
(381, 'seo', 'SEO'),
(382, 'discount', 'Discount'),
(384, 'in', 'in'),
(385, 'from_date', 'From Date'),
(386, 'to_date', 'To Date'),
(388, 'seo_description', 'SEO Description'),
(389, 'seo_keyword', 'SEO Keyword'),
(390, 'seo_og_image', 'SEO og Image'),
(391, 'display_setting', 'Display Setting'),
(392, 'Display', 'Display'),
(393, 'enable', 'Enable'),
(394, 'module', 'Module'),
(395, 'searching', 'Searching'),
(396, 'records', 'Records'),
(397, 'per_page', 'Per Page'),
(398, 'select_language', 'Select Language'),
(399, 'is_display_address', 'Do You want to show event address on map'),
(401, 'invalid_coupon_code', 'Invalid coupon code.'),
(402, 'coupon_code_expired', 'Coupon code has been expired.'),
(403, 'coupon_code_not_associated_event', 'Given coupon code is not associated with this event.'),
(404, 'coupon_code_apply', 'coupon code applied'),
(405, 'google', 'Google'),
(406, 'map', 'Map'),
(407, 'key', 'Key'),
(408, 'header', 'Header'),
(409, 'color', 'Color'),
(411, 'footer', 'Footer'),
(412, 'search', 'Search'),
(413, 'business', 'Business'),
(414, 'comission', 'Commission'),
(415, 'minimum', 'Minimum'),
(417, 'payout', 'Payout'),
(418, 'setting', 'Setting'),
(419, 'business_setting_update', 'Business setting details updated successfully'),
(420, 'send_mail', 'Send Mail'),
(422, 'send_event_reminder', 'Do you want to send event reminder to user?'),
(423, 'reminder_event_booking', 'This is a reminder that you have an appointment with us. Please check below details for more.'),
(424, 'remainder_mail_success', 'Reminder email sent successfully.'),
(425, 'thank_you', 'Thank You'),
(427, 'remainder_mail_failure', 'Unable to send reminder email. Please try again.'),
(428, 'my_wallet_amount', 'My Wallet Amount'),
(429, 'payout_request', 'Payout Request'),
(430, 'payout_amount', 'Payout Amount'),
(431, 'payout_reference', 'PayPal - Email'),
(432, 'chosen_payment_gateway', 'Chosen Payment Gateway'),
(433, 'payment_gateway', 'Payment Gateway'),
(434, 'gateway_fee_note', 'Gateway fees deducted from the total amount.<br/>Example $100.00 - 2.9% + $0.30 = $96.70'),
(435, 'payout_request_success', 'Your payout request has been submitted.'),
(436, 'payout_request_error', 'Unable to create payout request.'),
(437, 'earnings', 'Earnings'),
(438, 'payout_notice', 'You can able to request payout once your earnings is greater or equal to '),
(439, 'payment_gateway_fee', 'Payment Gateway Fee'),
(440, 'reference_no', 'Reference No'),
(441, 'request_date', 'Request Date'),
(442, 'payout_minimum_amount', 'Your payout amount must be greater or equal to'),
(443, 'payout_mail_content', 'You have new payout request from vendor.'),
(444, 'vendor_name', 'Vendor Name'),
(445, 'payment_gateway_fee_in_percentage', 'Payment Gateway Fee(In Percentage)'),
(446, 'updated_payment_amount', 'Updated Payment Amount'),
(447, 'other_charges', 'Other Charge(In Dollar)'),
(448, 'payout_process', 'Payout has been processed successfully.'),
(449, 'payout_success_from_admin', 'Your payout has been processed.'),
(450, 'processed_date', 'Processed Date'),
(451, 'wallet_error', 'You do not have sufficient earnings to make payout request. '),
(452, 'commission_percentage', 'percent will charged on each paid appointment as a service charge.'),
(453, 'appointment_payment_history', 'Appointment Payments'),
(454, 'vendor_amount', 'Vendor Amount'),
(455, 'admin_amount', 'Admin Amount'),
(456, 'update_payment_status', 'Update Payment Status'),
(457, 'payment_received', 'Payment Received'),
(458, 'status_update', 'Status has been updated successfully.'),
(460, 'mandatory_commission', 'Please select commission for admin and minimum payout for vendor'),
(461, 'pick_city', 'Pick a City'),
(462, 'finds_awesome_events', 'To find awesome event\'s around you'),
(463, 'enter_your_city', 'Enter your city name'),
(464, 'top_cities', 'Top Cities'),
(465, 'search_restaurants_spa_events_city_location_vendor', 'Search restaurants, spa, events, city, location, vendor..'),
(466, 'popular', 'Popular'),
(467, 'whats_new', 'What\'s New'),
(468, 'price_high_to_low', 'Price (High to Low)'),
(469, 'price_low_to_high', 'Price (Low to High)'),
(470, 'categories', 'Categories'),
(471, 'no_location_found', 'No Location Found'),
(472, 'recommanded_searches', 'Recommended Searches'),
(473, 'events_in', 'Events In'),
(474, 'more_info', 'More Information'),
(475, 'go_to_profile_page', 'Go to profile page'),
(476, 'send_message', 'Send Message'),
(477, 'off', 'Off'),
(478, 'change_location', 'Change Location'),
(479, 'current', 'Current'),
(480, 'load_more', 'Load More'),
(481, 'booking_time_expired', 'Currently, there is not slot available for booking.'),
(482, 'price_after_discount', 'Price After Discount'),
(483, 'apply', 'Apply'),
(484, 'discard', 'Discard'),
(485, 'add_vendor', 'Add Vendor'),
(486, 'my_appointment', 'My Appointments'),
(488, 'instagram_link', 'Instagram Link'),
(489, 'login_required_for_review', 'You can not allow to give the review.Please login and try again.'),
(490, 'write_a', 'Write a'),
(492, 'quality', 'Quality'),
(494, 'space', 'Space'),
(495, 'service', 'Service'),
(497, 'review_title', 'Title of Review'),
(498, 'review_comment', 'Your Comment'),
(499, 'vendor_review_save', 'Your review saved successfully'),
(500, 'average', 'Average'),
(501, 'add_a_review', 'Add A Review'),
(502, 'update_a_review', 'Update A Review'),
(503, 'contact_vendor', 'Contact Vendor'),
(504, 'reviews_for', 'Reviews For'),
(512, 'type', 'Type'),
(515, 'already_created_account?', 'Already have account?'),
(516, 'account', 'Account'),
(519, 'login_required_for_send_message', 'You can not allow to send message.Please login and try again.'),
(520, 'login_required', 'Please login and try again.'),
(521, 'chats', 'Chats'),
(522, 'events', 'Events'),
(523, 'my_appointment', 'My Appointment'),
(524, 'no_gallery_found', 'There are no gallery Images'),
(525, 'profile_detail', 'Profile Detail'),
(527, 'more_images', 'More Images'),
(528, 'see_all', 'See All'),
(529, 'privacy_policy', 'Privacy Policy'),
(530, 'faqs', 'FAQs'),
(531, 'contact-us', 'Contact Us'),
(532, 'contact_detail_send', 'Your Contact Details has been send successfully'),
(533, 'new_contact_request', 'You have received new contact request'),
(534, 'contact_detail', 'Contact Detail'),
(535, 'field', 'Field'),
(536, 'appointment_booked', 'Your appointment has been booked successfully.'),
(537, 'new_appointment_booking', 'New appointment has been booked by customer with below details.'),
(538, 'new_appointment', 'New Appointment'),
(539, 'user_detail', 'User Detail'),
(540, 'text', 'Text'),
(541, 'contact-us-request', 'Contact Us Request'),
(542, 'faq_update', 'FAQ has been updated successfully.'),
(543, 'faq_insert', 'FAQ has been inserted successfully.'),
(544, 'faq_delete', 'FAQ has been deleted successfully.'),
(545, 'no_chat_available', 'There is no chat available yet'),
(546, 'no_review_available', 'There is no review available yet'),
(547, 'for', 'for'),
(548, 'contact-us-information', 'Feel free to send me inquiries about event, questions on booking and anything else you feel like contacting us about!'),
(549, 'event_inquiry', 'Inquiry'),
(550, 'display_datetime_form', 'Date Time Format'),
(551, 'not_allowed_booking', 'You are not allowed to book this slot. As its already booked.'),
(552, 'account_created', 'Your Account has been created successfully'),
(553, 'your_login_detail', 'Your login credentials given as below'),
(554, 'login_account_credential', 'Login Account Credentials'),
(555, 'vendor_insert', 'Vendor has been inserted successfully.'),
(556, 'vendor_update', 'Vendor has been updated successfully.'),
(557, 'content_management', 'Content Management'),
(558, 'content_delete', 'Page content has been deleted successfully.'),
(559, 'content_insert', 'Page content has been inserted successfully.'),
(560, 'content_update', 'Page content has been updated successfully.'),
(561, 'maintenance_mode', 'Maintenance Mode'),
(562, 'we_will_be_back_soon!', 'We will be back soon!'),
(563, 'Sorry_for_the_inconvenience_but_we_are_performing_some_maintenance_at_the_moment.', 'Sorry for the inconvenience but we are performing some maintenance at the moment.!'),
(564, 'if_you_need_to_you_can_always', 'If you need to you can always'),
(565, 'otherwise_we_will_be_back_online_shortly!', 'otherwise we will be back online shortly!'),
(566, 'profile_cover', 'Profile Cover'),
(567, 'valid_profile_cover_size', 'Size must be minimum of 1110*266.'),
(568, 'service_update', 'Service has been updated successfully.'),
(569, 'service_insert', 'Service has been inserted successfully.'),
(570, 'service_delete', 'Service has been deleted successfully.'),
(571, 'service_category_delete', 'Service category has been deleted successfully.'),
(572, 'service_category_update', 'Service Category has been updated successfully.'),
(573, 'service_category_insert', 'Service Category has been inserted successfully.'),
(574, 'vendor-service', 'Vendor Service'),
(575, 'ecommerce', 'eCommerce'),
(576, 'product', 'Product'),
(578, 'integrateon_webpage', 'Integrate on your website'),
(579, 'integration_info', 'To link into the booking tool on your website then paste the code below into the HTML editor for your website. Sounds complicated or you are unsure how to do? Contact us so we can help you. To test how it looks, click the button below '),
(580, 'list', 'List'),
(581, 'time', 'Time'),
(582, 'venue', 'Venue'),
(583, 'created_by', 'Created By'),
(585, 'quantity', 'Quantity'),
(586, 'tag', 'Tags'),
(587, 'total_seat', 'Total Seat'),
(588, 'seat_not_available', 'Sorry to inform you that your required tickets not available.'),
(589, 'users', 'Users'),
(590, 'service_list', 'Services List'),
(591, 'service_category', 'Service Category'),
(592, 'sponsor', 'Sponsor'),
(593, 'thumb_image_preview', 'Thumbnail image preview'),
(594, 'thumb', 'Thumbnail'),
(595, 'attendee', 'Attendee'),
(596, 'padding_time', 'Padding Time'),
(597, 'is_allow_multiple_slotbooking', 'Do You want to allow multiple booking on same slot?'),
(598, 'multiple_slotbooking_limit', 'Multiple Slot Booking Limit'),
(599, 'event_vendor_exist', 'You are not allowed to delete this vendor.'),
(600, 'event_city_exist', 'You are not allowed to delete this city.'),
(601, 'event_location_exist', 'You are not allowed to delete this location.'),
(602, 'view_website', 'View Website'),
(605, 'latest_services', 'Latest Services'),
(606, 'book_your_appointment', 'Book Your Appointment'),
(612, 'valid_profile_cover_size', 'Size must be minimum of 1110*266'),
(613, 'other_charge', 'Other Charge'),
(614, 'event_listing', 'Event Listing'),
(628, 'all', 'All'),
(629, 'unlimited', 'Unlimited'),
(630, 'limited', 'Limited'),
(631, 'ticket', 'Ticket'),
(632, 'search-result', 'Search result'),
(633, 'new_booking', 'New Booking'),
(634, 'thanks_booking', 'Thank you for booking with us.'),
(635, 'thanks_booking_text', 'Complete details about your booking and invoice of your payment is sent your billing address already. You can also login to your account to track booking(s) and invoice(s) at any time.'),
(636, 'seat', 'Seat'),
(638, 'customer_insert', 'Customer has been inserted successfully.'),
(639, 'customer_update', 'Customer has been updated successfully.'),
(640, 'view', 'View'),
(641, 'instructions', 'Instructions'),
(642, 'change', 'Change'),
(643, 'customer_booked_no_delete', 'You are not allowed to delete this user.'),
(644, 'register_success', 'Register Success'),
(645, 'thanks_welcome', 'Thank You and Welcome!'),
(646, 'thanks_text', 'Your new account is now ready to use. We have sent you an email with login details to access your new account.'),
(647, 'hours', 'Hours'),
(649, 'get', 'Get'),
(650, 'is_default', 'Mark as a default'),
(651, 'vendor_setting_updated', 'Vendor setting has been updated successfully.'),
(652, 'allow_city_location', 'Do you want to allow vendor to create city/location?'),
(653, 'allow_service_category', 'Do you want to allow vendor to create service category?'),
(654, 'allow_event_category', 'Do you want to allow vendor to create event category?'),
(655, 'discover_more_events', 'Discover More Services'),
(656, 'result', 'result'),
(657, 'sponsored_Ad', 'Sponsor'),
(659, 'organizer', 'Organizer'),
(660, 'available_seat', 'Available Seat'),
(661, 'add_ons', 'Add Ons'),
(662, 'service_add_ons_update', 'Service addons has been updated.'),
(663, 'service_add_ons_insert', 'Service addons has been added.'),
(664, 'service_add_ons_delete', 'Service addons has been deleted.'),
(665, 'things_to_do_around', 'Things to do in & around'),
(666, 'update_booking_time', 'Service booking has been updated.'),
(667, 'verification', 'Verification'),
(668, 'unverified', 'Unverified'),
(669, 'approve', 'Approve'),
(670, 'reject', 'Reject'),
(671, 'profile_verification_content', 'We would like to inform you that. Your profile has been '),
(672, 'new_event_booking', 'Thank you so much booking event ticket with us. We look forward to having you for '),
(673, 'new_event_booking_vendor', 'Congratulations!! New ticket has been booked for event. Please check below details.'),
(674, 'notification', 'Notification'),
(675, 'appointment_delete_notification_vendor', 'We would like to info you that customer has delete appointment. '),
(676, 'contact', 'Contact'),
(677, 'my_staff', 'My Staff'),
(678, 'staff', 'Staff'),
(679, 'staff_deleted', 'Staff member has been deleted successfully.'),
(680, 'staff_insert', 'Staff member has been inserted successfully.'),
(681, 'staff_update', 'Staff member has been updated successfully.'),
(682, 'designation', 'Designation'),
(683, 'appointment_booking_reminder', 'Appointment Booking Reminder'),
(684, 'get_direction', 'Get Direction'),
(685, 'wallet', 'Wallet'),
(686, 'withdrawable_balance ', 'Withdrawable Balance '),
(687, 'number_of_ticket', 'Number of ticket'),
(688, 'sold_out', 'Sold out'),
(689, 'booking_pending', 'Your booking request has been placed successfully and pending for approval. We will send you approval notification via email soon.'),
(690, 'reminder', 'Reminder'),
(691, 'booking_approve_reject', 'We would like to inform you that your request for booking has been {status}.'),
(692, 'fee', 'Fee'),
(693, 'process', 'Process'),
(694, 'payout_successful', 'Payout Successful'),
(695, 'on_hold', 'On Hold'),
(696, 'reply', 'Reply'),
(697, 'reply_send_success', 'Your reply has been sent successfully.'),
(698, 'appointment_time_update', 'We would to inform you that you appointment details are updated. Please check below information.'),
(699, 'staff_booked_no_delete', 'You are not allowed to delete this staff member.'),
(700, 'on_going', 'On Going'),
(701, 'currency', 'Currency'),
(702, 'past', 'Past'),
(703, 'upcoming', 'Upcoming'),
(704, 'testimonial', 'Testimonial'),
(705, 'testimonial_insert', 'Testimonial has been added.'),
(706, 'testimonial_update', 'Testimonial has been updated.'),
(707, 'testimonial_delete', 'Testimonial has been deleted.'),
(708, 'validity', 'Validity'),
(709, 'membership', 'Membership'),
(710, 'membership_not_select', 'It seems like you have not purchased membership.'),
(711, 'membership_expired', 'It seems like your membership is expired. Please purchase now to prevent service interruption.'),
(712, 'transaction_success', 'Your purchase request has been processed successfully.'),
(713, 'expire_date', 'Expire date'),
(714, 'service_suspended', 'Service Suspended'),
(715, 'last_seen', 'Last Seen'),
(716, 'ago', 'ago'),
(717, 'send_membership_reminder', 'Do you want to send membership reminder to vendor?'),
(718, 'membership_reminder_email_content', 'A friendly reminder that your membership is about to expire. Please update your membership to prevent service interruption.'),
(719, 'cash_payment_fee', 'Cash Payment Fee');

-- --------------------------------------------------------

--
-- Table structure for table `app_location`
--

DROP TABLE IF EXISTS `app_location`;
CREATE TABLE IF NOT EXISTS `app_location` (
  `loc_id` int(11) NOT NULL AUTO_INCREMENT,
  `loc_city_id` int(11) NOT NULL DEFAULT '0',
  `loc_title` varchar(100) CHARACTER SET utf8 NOT NULL,
  `loc_status` enum('A','I') NOT NULL DEFAULT 'A' COMMENT 'A=active,I=inactive',
  `loc_created_by` int(11) NOT NULL DEFAULT '0',
  `loc_created_on` datetime DEFAULT NULL,
  `loc_updated_on` datetime DEFAULT NULL,
  PRIMARY KEY (`loc_id`),
  KEY `loc_city` (`loc_city_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `app_membership_history`
--

DROP TABLE IF EXISTS `app_membership_history`;
CREATE TABLE IF NOT EXISTS `app_membership_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL DEFAULT '0',
  `package_id` int(11) NOT NULL DEFAULT '0',
  `membership_till` date DEFAULT NULL,
  `remaining_event` int(11) NOT NULL DEFAULT '0',
  `payment_method` varchar(100) NOT NULL,
  `payment_status` varchar(100) NOT NULL,
  `price` int(11) NOT NULL DEFAULT '0',
  `transaction_id` varchar(255) NOT NULL,
  `payment_id` varchar(255) NOT NULL,
  `customer_payment_id` varchar(255) NOT NULL,
  `failure_code` varchar(255) DEFAULT NULL,
  `failure_message` text,
  `status` enum('A','E') NOT NULL DEFAULT 'A' COMMENT 'A:Active,E:Expired',
  `created_on` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `app_package`
--

DROP TABLE IF EXISTS `app_package`;
CREATE TABLE IF NOT EXISTS `app_package` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) CHARACTER SET utf8 NOT NULL,
  `description` text CHARACTER SET utf8,
  `price` varchar(100) NOT NULL,
  `max_event` varchar(100) NOT NULL,
  `package_month` int(11) NOT NULL DEFAULT '1',
  `status` enum('A','I') NOT NULL DEFAULT 'A' COMMENT 'A:Active ,I:Inactive',
  `created_on` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `app_package`
--

INSERT INTO `app_package` (`id`, `title`, `description`, `price`, `max_event`, `package_month`, `status`, `created_on`) VALUES
(1, '1 Month Package', 'Package Description', '10', '0', 1, 'A', '2019-08-27 00:00:00'),
(2, '3 Month Package', 'Package Description', '15', '0', 3, 'A', '2019-08-27 00:00:00'),
(3, '12 Month Package', 'Package Description', '20', '0', 12, 'A', '2019-08-27 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `app_payment_request`
--

DROP TABLE IF EXISTS `app_payment_request`;
CREATE TABLE IF NOT EXISTS `app_payment_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_id` int(11) NOT NULL,
  `amount` decimal(18,2) NOT NULL,
  `updated_amount` decimal(18,2) DEFAULT NULL,
  `reference_no` varchar(255) DEFAULT NULL,
  `created_date` datetime NOT NULL,
  `processed_date` datetime DEFAULT NULL,
  `status` enum('P','H','F','S') NOT NULL COMMENT 'S=Success,P=Pending, F=Fail, H=Hold',
  `choose_payment_gateway` varchar(255) NOT NULL,
  `payment_gateway_ref` varchar(255) NOT NULL,
  `payment_gateway_fee` varchar(50) NOT NULL,
  `other_charge` decimal(18,2) NOT NULL,
  `cash_payment` decimal(18,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `vendor_id` (`vendor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `app_payment_setting`
--

DROP TABLE IF EXISTS `app_payment_setting`;
CREATE TABLE IF NOT EXISTS `app_payment_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stripe` enum('Y','N') NOT NULL DEFAULT 'N',
  `on_cash` enum('Y','N') NOT NULL DEFAULT 'N',
  `stripe_secret` varchar(255) DEFAULT NULL,
  `stripe_publish` varchar(255) DEFAULT NULL,
  `paypal` enum('Y','N') NOT NULL COMMENT 'Y=Yes,N=No',
  `paypal_sendbox_live` enum('S','L') NOT NULL COMMENT 'S=Sandbox,L=Live',
  `paypal_merchant_email` varchar(255) DEFAULT NULL,
  `2checkout` enum('Y','N') NOT NULL DEFAULT 'N',
  `2checkout_account_no` varchar(255) DEFAULT NULL,
  `2checkout_live_sandbox` enum('L','S') NOT NULL DEFAULT 'S' COMMENT 'L=Live,S=Sandbox',
  `2checkout_publishable_key` text,
  `2checkout_private_key` text,
  `created_on` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `app_rating`
--

DROP TABLE IF EXISTS `app_rating`;
CREATE TABLE IF NOT EXISTS `app_rating` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `event_id` int(11) NOT NULL DEFAULT '0',
  `appointment_id` int(11) NOT NULL DEFAULT '0',
  `rating` int(11) NOT NULL DEFAULT '0',
  `review` text CHARACTER SET utf8 NOT NULL,
  `created_on` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `app_service_addons`
--

DROP TABLE IF EXISTS `app_service_addons`;
CREATE TABLE IF NOT EXISTS `app_service_addons` (
  `add_on_id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` text CHARACTER SET utf8 NOT NULL,
  `details` text CHARACTER SET utf8 NOT NULL,
  `image` varchar(255) NOT NULL,
  `price` decimal(18,2) NOT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  PRIMARY KEY (`add_on_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `app_site_setting`
--

DROP TABLE IF EXISTS `app_site_setting`;
CREATE TABLE IF NOT EXISTS `app_site_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_logo` varchar(25) DEFAULT NULL,
  `banner_image` varchar(255) NOT NULL DEFAULT '',
  `company_name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `company_email1` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `company_email2` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `company_phone1` varchar(25) CHARACTER SET utf8 DEFAULT NULL,
  `company_phone2` varchar(25) CHARACTER SET utf8 DEFAULT NULL,
  `company_address1` text CHARACTER SET utf8,
  `company_address2` text CHARACTER SET utf8,
  `google_link` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `fb_link` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `twitter_link` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `insta_link` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `linkdin_link` varchar(255) CHARACTER SET utf8 DEFAULT '',
  `language` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT 'english',
  `home_page` int(11) DEFAULT '1',
  `community_banner` varchar(100) DEFAULT NULL,
  `time_zone` varchar(255) DEFAULT NULL,
  `time_format` varchar(255) NOT NULL DEFAULT 'm-d-Y H:i',
  `fevicon_icon` varchar(100) DEFAULT NULL,
  `commission_percentage` int(11) NOT NULL DEFAULT '10',
  `minimum_vendor_payout` double(18,2) NOT NULL DEFAULT '50.00',
  `is_display_vendor` enum('Y','N') NOT NULL DEFAULT 'Y' COMMENT 'Y=yes,N=no',
  `is_display_category` enum('Y','N') NOT NULL DEFAULT 'Y' COMMENT 'Y=yes,N=no',
  `is_display_location` enum('Y','N') NOT NULL DEFAULT 'Y' COMMENT 'Y=yes,N=no',
  `is_display_language` enum('Y','N') NOT NULL DEFAULT 'Y' COMMENT 'Y=yes,N=no',
  `is_display_searchbar` enum('Y','N') NOT NULL DEFAULT 'Y' COMMENT 'Y=yes,N=no',
  `is_maintenance_mode` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT 'Y=yes,N=no',
  `display_record_per_page` int(11) NOT NULL DEFAULT '12',
  `header_color_code` varchar(100) DEFAULT '#4b6499',
  `footer_color_code` varchar(100) DEFAULT '#4b6499',
  `footer_text` text CHARACTER SET utf8,
  `google_map_key` varchar(100) DEFAULT NULL,
  `google_location_search_key` varchar(100) DEFAULT NULL,
  `enable_service` enum('Y','N') NOT NULL DEFAULT 'Y',
  `enable_event` enum('Y','N') NOT NULL DEFAULT 'Y',
  `currency_id` int(11) NOT NULL DEFAULT '1',
  `currency_position` enum('R','L') NOT NULL DEFAULT 'L',
  `enable_testimonial` enum('Y','N') NOT NULL DEFAULT 'N',
  `enable_membership` enum('Y','N') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `app_slider`
--

DROP TABLE IF EXISTS `app_slider`;
CREATE TABLE IF NOT EXISTS `app_slider` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_by` int(11) NOT NULL,
  `image` varchar(100) NOT NULL,
  `status` enum('A','I','D') NOT NULL DEFAULT 'A',
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `app_testimonial`
--

DROP TABLE IF EXISTS `app_testimonial`;
CREATE TABLE IF NOT EXISTS `app_testimonial` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `image` varchar(255) NOT NULL,
  `details` text CHARACTER SET utf8 NOT NULL,
  `status` enum('A','I') NOT NULL DEFAULT 'A',
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `app_vendor_review`
--

DROP TABLE IF EXISTS `app_vendor_review`;
CREATE TABLE IF NOT EXISTS `app_vendor_review` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL DEFAULT '0',
  `vendor_id` int(11) NOT NULL DEFAULT '0',
  `event_id` int(11) NOT NULL DEFAULT '0',
  `appointment_id` int(11) NOT NULL DEFAULT '0',
  `quality_rating` int(11) NOT NULL DEFAULT '0',
  `location_rating` int(11) NOT NULL DEFAULT '0',
  `space_rating` int(11) NOT NULL DEFAULT '0',
  `service_rating` int(11) NOT NULL DEFAULT '0',
  `price_rating` int(11) NOT NULL DEFAULT '0',
  `review_comment` text CHARACTER SET utf8,
  `created_on` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`),
  KEY `vendor_id` (`vendor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `app_vendor_setting`
--

DROP TABLE IF EXISTS `app_vendor_setting`;
CREATE TABLE IF NOT EXISTS `app_vendor_setting` (
  `id` int(11) NOT NULL,
  `allow_city_location` enum('Y','N') NOT NULL DEFAULT 'Y',
  `allow_service_category` enum('Y','N') NOT NULL DEFAULT 'Y',
  `allow_event_category` enum('Y','N') NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `app_vendor_setting`
--

INSERT INTO `app_vendor_setting` (`id`, `allow_city_location`, `allow_service_category`, `allow_event_category`) VALUES
(1, 'N', 'N', 'N');

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `app_appointment_payment`
--
ALTER TABLE `app_appointment_payment`
  ADD CONSTRAINT `app_appointment_payment_event_id` FOREIGN KEY (`event_id`) REFERENCES `app_event` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `app_event_book_id` FOREIGN KEY (`booking_id`) REFERENCES `app_event_book` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `app_event`
--
ALTER TABLE `app_event`
  ADD CONSTRAINT `app_event_vendor` FOREIGN KEY (`created_by`) REFERENCES `app_admin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `app_event_book`
--
ALTER TABLE `app_event_book`
  ADD CONSTRAINT `app_event_book_customer_id` FOREIGN KEY (`customer_id`) REFERENCES `app_customer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `app_event_book_event_id` FOREIGN KEY (`event_id`) REFERENCES `app_event` (`id`);

--
-- Constraints for table `app_event_sponsor`
--
ALTER TABLE `app_event_sponsor`
  ADD CONSTRAINT `app_event_sponsor_event_id` FOREIGN KEY (`event_id`) REFERENCES `app_event` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `app_event_ticket_type`
--
ALTER TABLE `app_event_ticket_type`
  ADD CONSTRAINT `event_ticket_type_id` FOREIGN KEY (`event_id`) REFERENCES `app_event` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `app_location`
--
ALTER TABLE `app_location`
  ADD CONSTRAINT `app_location_city_id` FOREIGN KEY (`loc_city_id`) REFERENCES `app_city` (`city_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `app_payment_request`
--
ALTER TABLE `app_payment_request` ADD CONSTRAINT `app_payment_request_vendor_id` FOREIGN KEY (`vendor_id`) REFERENCES `app_admin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- custom update
INSERT INTO `app_payment_setting` (`id`,`stripe`, `on_cash`, `stripe_secret`, `stripe_publish`, `paypal`, `paypal_sendbox_live`, `paypal_merchant_email`, `created_on`) VALUES (NULL,'N', 'N', NULL, NULL, 'N', 'S', NULL, '2018-08-01 00:00:00');

INSERT INTO `app_admin` (`id`,`first_name`, `last_name`, `email`, `password`,`status`, `profile_status`, `type`, `company_name`, `created_on`,`cash_payment`)
VALUES (NULL,'admin_first_name', 'admin_last_name', 'admin_email', 'admin_password','A', 'V', 'A', 'admin_company_name','admin_created_at','0');
INSERT INTO `app_email_setting` (`id`,`mail_type`,`email_from`,`smtp_host`, `smtp_username`, `smtp_password`, `smtp_port`, `smtp_secure`)
VALUES (NULL,'email_mail_type','email_email_from','email_smtp_host', 'email_smtp_username', 'email_smtp_password', 'email_smtp_port', 'email_smtp_secure');
INSERT INTO `app_site_setting` (`id`,`company_logo`, `company_name`, `company_email1`, `time_zone`)
VALUES (NULL,'site_setting_company_logo','site_setting_company_name', 'site_setting_company_email','Asia/Kolkata');

-- 31-10-2019
ALTER TABLE `app_site_setting` ADD `slot_display_days` INT(11) NOT NULL DEFAULT '30' AFTER `enable_membership`;
INSERT INTO `app_language_data` (`id`, `default_text`, `english`) VALUES (NULL, 'latest_events', 'Latest Events');
INSERT INTO `app_language_data` (`id`, `default_text`, `english`) VALUES (NULL, 'resend_verification_link', 'Resend Verification Link');
INSERT INTO `app_language_data` (`id`, `default_text`, `english`) VALUES (NULL, 'holiday', 'holiday');
UPDATE `app_language_data` SET `english` = 'Account not verified.' WHERE `default_text` ="vendor_not_verify";
INSERT INTO `app_language_data` (`id`, `default_text`, `english`) VALUES (NULL, 'slot_display_days', 'Display Booking Slot for Next X Days');
INSERT INTO `app_language_data` (`id`, `default_text`, `english`) VALUES (NULL, 'holiday', 'Holiday');
INSERT INTO `app_language_data` (`id`, `default_text`, `english`) VALUES (NULL, 'record_insert', 'Record has been inserted successfully.');
INSERT INTO `app_language_data` (`id`, `default_text`, `english`) VALUES (NULL, 'record_update', 'Record has been updated successfully.');
INSERT INTO `app_language_data` (`id`, `default_text`, `english`) VALUES (NULL, 'record_delete', 'Record has been deleted successfully.');

CREATE TABLE IF NOT EXISTS `app_holidays` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 NOT NULL,
  `holiday_date` date DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` date DEFAULT NULL,
  `status` enum('A','I') NOT NULL DEFAULT 'A',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
  
COMMIT;