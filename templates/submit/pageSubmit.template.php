<?php
/**
* @package Bidi Recycle Program
*/
use Includes\Base\BaseController;
use Includes\Base\CustomerOrder;
use Includes\Base\DBModel;
use Includes\StampsAPI\StampService;
use Includes\StampsAPI\Address;
use Includes\StampsAPI\Credentials;
use Includes\AuthorizeNet_API\AuthorizeNetService;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Includes\Base\Email;
require "../../vendor/autoload.php";
require_once( dirname (dirname(dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) ) ) . '/wp-load.php' );

if(isset($_POST)){
	var_dump($_POST);
	$SubmitModel = new DBModel();
	$StampService = new StampService();
	$CustomerOrderObj = new CustomerOrder();
	$AuthorizeService = new AuthorizeNetService();

	$customer_id = $_POST['current_user_id'];

	$from_firstname = $_POST['from_firstname'];
	$from_lastName = $_POST['from_lastName'];
	$customerFullName = $from_firstname . " " . $from_lastName;

	$from_email = $_POST['from_email'];
	$from_address = $_POST['from_address'];
	$from_phone_number = $_POST['from_phone_number'];
	$from_country = $_POST['from_country'];
	$from_postcode = $_POST['from_postcode'];
	$from_city = $_POST['from_city'];
	$from_state = $_POST['from_state'];
	$note = $_POST['note'];

	
	$store_locator = $_POST['store_locator'];
	$totalItemWeight = $_POST['bidistick_qty'];	
	
	// STAMPS START
	// $address = new Address(
	// 	$from_firstname,
	// 	$from_lastName,
	// 	$from_address,
	// 	$from_city,
	// 	$from_state,
	// 	$from_postcode,
	// 	$from_phone_number,
	// 	$from_email
	// );
	// $cleanseAddress = $StampService->cleanseAddress($address);
	// $cleansedAddress = $cleanseAddress['address'];
	
	// $rate = $StampService->getRates($cleansedAddress->ZIPCode,0,$totalItemWeight,'US-FC', 'Thick Envelope');

	// $generateShippingLabel = $StampService->generateShippingLabel($cleansedAddress, $rate);

	// $TrackingNumber = $generateShippingLabel['TrackingNumber'];
	// $StampsTxID = $generateShippingLabel['StampsTxID'];
	// $postageURL = $generateShippingLabel['URL'];

	// $rates = $generateShippingLabel['Rate'];
	// $ShipDate = $rates['ShipDate'];
	// $DeliveryDate = $rates['DeliveryDate'];
	// $MaxAmount = $rates['MaxAmount'];

	// var_dump($generateShippingLabel);

	// $postageURL = 'https://swsim.testing.stamps.com/Label/Label3.ashx/label-200.png?AQAAAJTVbiboDdgIkUByHv41U40bWYLYmjlab8LDSVd4nG1SO2zTUBQ9jh2aOmmdqCUIEGCIFD4t6vMn_jSl1LXd1sKJq-e0EKhoGRACgYhgY2BDgoq5LKBOnZsJxIaoxAjKlgUGBBMSU9fKvBCQKsGR7nLvufccnffcm5N4X1uNngVtZ7N8bHev-vPR5vba1srzOX_j5f17XGHmyFUA13vVnkDpQwscUMZp8FAxAFQgiFKvV30zLnUzh8BzWhEC0ji4muaSBCCGGl8JOtyBpgh4RWxN6dyTNbbxBylA3QYUVVNaH0V5-NrD-W_Trz6Pfa99bR-99eLE69tfOuWRt8WVxzdGf-zeyXYfeBLuZjayHPi0YuuaNYohoOVUIaTOMWmbVQNCMgZ-uCdOiEZ0WyOKMY3jPPZBY8wpZuACqxmIzBwsCNxFZD4JzBEG_xJNDOQxCSFzmG2MI19iuRVmAy9wHerHMvUbS7QeJ0ly0jI0IseX5dhdiMIwlud8Spsy9eS44csaAxIMzvrOsk8bUT0V0bRtEmIJhqEqosXmhqnqmj4BXnAJsQvgRUvRdV2p2Jpxvq8seU498MPQl72I1nzKdCXVMOW5MKJ-3WVtylRyCw4N_Wa8HDBmatHpZyUotqbuO0nYQ4rgT-2s6yr5bWVn3dYJURTFsu2KoRNTZxPNqGTBF_8_yYGXluLFWG5Qx70U1Ofl0hA7m2ff4t2e2cl1n470wz3LpBjO_Mv_Baveils=';

	// $TrackingNumber = '9400111899564074420365';

	// $postaggeLabelImageUrl = savePostageLabelImage($postageURL, $TrackingNumber);

	// echo $postaggeLabelImageUrl;
	// // STAMPS END
	
	// $count = count($product_order_id);
	
	// // Insert Return Information Data from the form to wp_bidi_return_information table
	// $insertReturnInformation = $SubmitModel->insertReturnInformation($total_prod_qty, $current_date, $return_status, $customer_id, $TrackingNumber);

	// // Get Latest inserted ID from wp_bidi_return_information table
	// $return_id = $insertReturnInformation[0];

	// // Insert Shipping Information
	// $insertShippingInformation = $SubmitModel->insertShippingInformation($TrackingNumber, $StampsTxID, $postageURL, $ShipDate, $DeliveryDate, $MaxAmount, $return_id);

	// $from_email = "murdoc21daddie@gmail.com";
	// clientEmail($from_email, $customerFullName, $TrackingNumber, $postaggeLabelImageUrl, $count, $product_name, $product_qty);
	// /** START SEND EMAIL TO ADMIN **/
	// adminEmail($TrackingNumber, $from_firstname, $from_lastName, $from_email, $from_phone_number, $from_address, $from_city, $from_postcode, $from_state, $totalQty);
}

/** Save Postage Label as Image inside Plugin **/
function savePostageLabelImage($postageURL, $TrackingNumber){

	$BaseController = new BaseController();

	$folder = $BaseController->plugin_path . 'postage-labels';
	$output = $folder . '/' . $TrackingNumber . '_postage_label.png';
	file_put_contents($output, file_get_contents($postageURL));

	return $output;
}

/** START SEND EMAIL TO CUSTOMER **/
function clientEmail($from_email, $customerFullName, $TrackingNumber, $postaggeLabelImageUrl, $count, $product_name, $product_qty){
	
	// Instantiation and passing `true` enables exceptions
	$Email = new Email();
	$mail = new PHPMailer(true);
	// Site logo
	$logoFileUrl = plugin_dir_path( dirname( __FILE__, 2 ) ) . "assets/img/customerEmailHeader.png";

	try {
		// GET SENDER EMAIL SETTING
		$senderEmailSetting = $Email->senderEmailSetting();
		$senderEmail = $senderEmailSetting['senderEmail'];
		$senderPassword = $senderEmailSetting['senderPassword'];

		// RECEIVER EMAIL
		// $receiverEmail = 'murdoc21daddie@gmail.com';
		$receiverEmail = $from_email;

		// MAIL SETTINGS
		$mail->SMTPDebug = $senderEmailSetting['SMTPDebug'];
		$mail->isSMTP();    
	    $mail->Host       = $senderEmailSetting['Host'];
	    $mail->SMTPAuth   = $senderEmailSetting['SMTPAuth'];
	    $mail->Username   = $senderEmail;
	    $mail->Password   = $senderPassword;
	    $mail->SMTPSecure = $senderEmailSetting['SMTPSecure'];
	    $mail->Port       = $senderEmailSetting['Port'];

	    //Sender
	    $mail->setFrom($senderEmail, 'Bidi Vapor - Bidi Recycle');
	    // Receiver
	    $mail->addAddress($receiverEmail, $customerFullName);
	    // Embeded Header Image
	    $mail->addEmbeddedImage($logoFileUrl, 'bidi_logo');	    
	    // Attachments
	    $mail->addAttachment($postaggeLabelImageUrl);         // Add attachments
	    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

	    // Content
	    $mail->isHTML(true);
	    $mail->Subject = 'Thank You For Taking a Part in the Bidi Cares Movement';
	    $mail->Body = '<div style="width:50%;">
	    				<h1>Save Your Bidi. Save Our Planet.</h1>
	    				<h4>Bidi Cares movement appreciates your recycling efforts. We are giving you something in return! To claim this gift, follow the instructions below.</h4>
	    				<img src="cid:bidi_logo" alt="Bidi Cares" style="width:100%;">

						<p>Hi, '.$customerFullName.'!</p>
						</br>
						<p><b>Thank you for your interest in taking part in the Bidi Cares Movement.</b></p>
						</br>
						<p>As an environmental advocate, we want to lessen our product’s impact on the planet through Bidi Cares, our eco platform. Now that you joined our recycling activity, we are positive that we can make a positive impact together. </p>
						</br>
						</br>
						<p><i>Your FREE Bidi Stick on your next purchase is now a few steps away.</i></p>
						<p><b><u>To continue, please check your return label attached to this message. </u></b></p>
						<a href="'.$postageURL.'"><button>Click Here To Print Postage Label</button></a>
						</br>
						<p>Do your part through these simple steps:</p>
						<ol>
							<li>Print your return label and place this in a package together with your used Bidi Sticks. Do not include the Bidi Stick packaging on your shipment. Ship only Bidi Stick vape pens alone to avoid return label cost differences during the actual shipment.</li>
							<li>Send your shipment to our facility. </li>
							<li>The coupon code for your FREE Bidi Stick on your next purchase will be sent right after your items have arrived in our facility and have been validated by our staff. Upon shipment, you can track your recycled Bidi Sticks through the <a href="https://www.usps.com/">USPS Website.</a></li>
							<li>The coupon code will include the instructions on how to redeem your FREE Bidi Stick on your next purchase.</li>
						</ol>
						</br>
						<p><b>At the moment, we can only process 10 Bidi Sticks per transaction. However, you may have multiple recycling transactions, as long as you have enough ordered items from www.bidivapor.com.</b></a>
						</br>
						<p>If you are interested in our environmental program, visit our Bidi Cares website. For further questions, don’t hesitate to contact us at <a href="mailto:support@bidivapor.com">support@bidivapor.com</a> or go to our <a href="bidivapor.org/faq/"></a>FAQs page</p>
						</br>
						<p>Thank you, Bidi eco-warrior</p>
						</br>
						<p>Cheers,</p>
						</br>
						<p>Bidi Cares Team</p></br></br>
						';
	    $mail->Body .= '
							
								<div>
									<header style="padding:1em;background-color:#37b348;">
										<h2 style="color:#fff;">Thank You For Choosing Bidi Recycle</h2>
										<h3 style="color:#fff;">Recycle Tracking Number : ' . $TrackingNumber . '</h3>
										<a href="'.$postageURL.'"><button>Click Here To Print Postage Label</button></a>
									</header>
									<div style="padding:1em;background-color:#fdfdfd;border:1px solid #eeeeee;color:#717983;">
										<p>Your Recycle has been received and is now being processed.</br>Your Recycle details are shown below for your reference:</p>									
										<table style="border:1px solid #eeeeee;">
										  <thead>
										    <tr style="border:1px solid #eeeeee;">
										      <th style="padding:.5em;background-color: #4CAF50;color: white;">Product</th>
										      <th style="padding:.5em;background-color: #4CAF50;color: white;">Quantity</th>
										    </tr>
										  </thead>
										  <tbody>';
										  	for ($x = 0; $x < $count; $x++) {
										  		$mail->Body .= '
											    <tr style="border:1px solid #eeeeee;">
											      <td style="padding:1em;border:1px solid #eeeeee;">' . $product_name[$x] . '</td>
											      <td style="padding:1em;text-align:center;border:1px solid #eeeeee;">' . $product_qty[$x] . '</td>
											    </tr>';
											    }
		$mail->Body .='
										  </tbody>
										  <tfoot>
										    <tr style="border:1px solid #eeeeee;">
										      <th style="padding:.5em;background-color: #4CAF50;color: white;">Product</th>
										      <th style="padding:.5em;background-color: #4CAF50;color: white;">Quantity</th>
										    </tr>
										  </tfoot>
										</table>
									</div>
								</div>
								</br>
								<hr>
								<p>If you are interested in knowing more about the Bidi Cares program, you may visit our <a href="https://bidicares.quikfillrx.org/about-bidi-stick/">FAQ page</a> or through our <a href="https://bidicares.quikfillrx.org/contact/">Contact Page</a>.</p>
							</div>
							</br></br></br>
							<p>If you are interested in our environmental program, visit our <a href="https://bidicares.com/">Bidi Cares</a> website. For further questions, don’t hesitate to <a href="https://bidicares.com/contact/">contact us</a> or go to our <a href="https://bidicares.com/faqs/">FAQs page.</a></p>
							';

	    
							if($mail->send()){
								echo 'Message has been sent';
								return true;
							}
	    
	} catch (Exception $e) {
	    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
	}
	
}
/** END SEND EMAIL TO CUSTOMER **/

/** START SEND EMAIL TO ADMIN **/
function adminEmail($TrackingNumber, $from_firstname, $from_lastName, $from_email, $from_phone_number, $from_address, $from_city, $from_postcode, $from_state, $totalQty){
	$Email = new Email();
	$mailAdmin = new PHPMailer(true);
	// Site logo
	$logoFileUrl = plugin_dir_path( dirname( __FILE__, 2 ) ) . "assets/img/adminHeader.jpg";
	try {		

		// GET SENDER EMAIL SETTING
		$senderEmailSetting = $Email->senderEmailSetting();
		$senderEmail = $senderEmailSetting['senderEmail'];
		$senderPassword = $senderEmailSetting['senderPassword'];

		// RECEIVER EMAIL
		// $receiverEmail = 'murdoc21daddie@gmail.com';
		$receiverEmail = $senderEmailSetting['receiverEmail'];
		
		// MAIL SETTINGS
		$mailAdmin->SMTPDebug = $senderEmailSetting['SMTPDebug'];
		$mailAdmin->isSMTP();	    
	    $mailAdmin->Host       = $senderEmailSetting['Host'];
	    $mailAdmin->SMTPAuth   = $senderEmailSetting['SMTPAuth'];
	    $mailAdmin->Username   = $senderEmail;
	    $mailAdmin->Password   = $senderPassword;
	    $mailAdmin->SMTPSecure = $senderEmailSetting['SMTPSecure'];
	    $mailAdmin->Port       = $senderEmailSetting['Port'];

	    $customerFullName = $from_firstname . " " . $from_lastName;
	    //Sender
	    $mailAdmin->setFrom($senderEmail, 'Bidi Vapor - Bidi Recycle');
	    // Receiver
	    $mailAdmin->addAddress($receiverEmail, $blogname);

	    // Email Header Image
	    $mailAdmin->addEmbeddedImage($logoFileUrl, 'bidi_logo');	    
	    // Attachments
	    // $mailAdmin->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
	    // $mailAdmin->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

	    // Content
	    $mailAdmin->isHTML(true);
	    $mailAdmin->Subject = 'BIDI Recycling Request by '. $customerFullName;
	    $mailAdmin->Body = '<div style="width:50%;">
	    				<img src="cid:bidi_logo" alt="Bidi Cares" style="width:100%;">

						<p>Dear, '.$blogname.'</p>
						</br>
						<p>Greetings!</p>
						</br>
						<p>Through the Bidi Cares Program, we encourage our customers to give back 10 used Bidi Sticks in exchange for a free one. This is part of our mission to make Bidi more recyclable and save the environment from the dangers of nicotine and improper battery disposal.</p>
						</br>
						<h3>'.$customerFullName.' has sent his/her Bidi Sticks with a Tracking Number : '.$TrackingNumber.' to our facility for recycling, together with the return label.</h3>
						</br></br>
						<p>The complete details of this shipment are found below:</p>
						</br>
						<h3 style="text-align:center;">Bidi Cares Recycling</h3>
						<p>First Name: '.$from_firstname.'</p>
						<p>Last Name: '.$from_lastName.'</p>
						<p>Email Address: '.$from_email.'</p>
						<p>Phone: '.$from_phone_number.'</p>
						<p>Street Address: '.$from_address.'</p>
						<p>City: '.$from_city.'</p>
						<p>Zip Code: '.$from_postcode.'</p>
						<p>US State: '.$from_state.'</p>
						<p>Quantity of Bidi Stick you want to recycle: '.$total_prod_qty.'</p>
						</br>
						<h4>Please approve and validate recycled items. Once approved, please complete recycle process by pressing the complete button. This will automatically send them a coupon email.</h4>
						</br></br>
						<p>Cheers,</p>
						</br>
						<p>Bidi Cares Team</p>
						';

	    $mailAdmin->send();
	    echo 'Message has been sent';
	} catch (Exception $e) {
	    echo "Message could not be sent. Mailer Error: {$mailAdmin->ErrorInfo}";
	}
}
/** START SEND EMAIL TO ADMIN **/