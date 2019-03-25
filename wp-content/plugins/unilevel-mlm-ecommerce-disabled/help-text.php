<?php
$img_url = plugins_url()."/".MLM_PLUGIN_NAME."/css/help-16.png";
$pre_text='<a class="demo-tip-darkgray" title="' ;
$protext = 'href="javascript:;" style="cursor: help;"><img src="'.$img_url.'"></a>';

/***************** General Tab Settings Text************/

define("GENERAL_1",$pre_text.'The minimum number of people a member needs to sponsor into the network before he becomes entitled to earn commissions from his network."'.$protext);

define("GENERAL_2",$pre_text.'This defines the levels upto which the payment will be distributed for a successful sale in the network. Once defined, this value cannot be changed."'.$protext);

define("GENERAL_3",$pre_text.'A coupon is generated for each member of the network with his username. This coupon can be provided by the members to their referrals to get a discount on their purchases. This value sets the discount % that would be available to users who use these coupons to make purchases in the Shop."'.$protext);

define("GENERAL_4",$pre_text.'Commissions can be distributed on the product price or a specified distribution amount. If the amount that you would like to add for commission distribution is a standard % of the product price, specify the desired percentage value here."'.$protext);

define("GENERAL_5",$pre_text.'By default the affiliate URL for your members is setup to redirect to the registration page included in our plugin. In case you would like the affiliate URL to redirect to a different page, please specify the desired URL in this field."'.$protext);

define("GENERAL_6",$pre_text.'New users will get an option to join the network on the woocommerce checkout page."'.$protext);

define("GENERAL_7",$pre_text.'If you would like users to purchase a specific product(s) in order to become a part of the network, add those products to a separate category. This category is called the qualification category and the products are known as the qualification products. If you choose this option select the qualification category from the european below."'.$protext);

define("GENERAL_8",$pre_text.'Select the qualification category."'.$protext);

define("GENERAL_9",$pre_text.'Check this option if you do not want to show the qualification products on the Shop."'.$protext);

/***************** EOF General Tab Settings Text ************/

/***************** Payout Tab Settings Text ************/

define("PAYOUT_1",$pre_text.'These are the commission percentages that are used when a purchase is made by a member of the network."'.$protext);
define("PAYOUT_2",$pre_text.'These are the commission percentages that are used when a purchase is made by a general customer who is not a member of the network. It may be noted that the member who referred the sale will always be the Level 1 user. His sponsor will be Level 2 and so on for the other users in the network."'.$protext);

/***************** EOF Payout Tab Settings Text ************/

/***************** Deductions Tab Settings Text ************/

define("DEDUCTION_1",$pre_text.'You can configure the various withdrawal methods and the amount (fixed or percentage) that would be deductible if the member opts for a particular method of withdrawal."'.$protext);

define("DEDUCTION_2",$pre_text.'You can configure other deductions that would be deducted from a withdrawal. Typical examples could include a general service charge, withholding tax or any other amount that you like to deduct from the withdrawal amount."'.$protext);

/***************** EOF Deductions Tab Settings Text ************/

/***************** change sponsor Tab Settings Text ************/

define("CHECK_SPON",$pre_text.'Use the form below to change the sponsor for a particular user. First specify the user for whom a sponsor change needs to be done. Then specify the new sponsor for the user. Please note that a user cannot be moved anywhere in his own downline."'.$protext);

/***************** EOF change sponsor Tab Settings Text ************/

/***************** Email Settings Tab Text ************/
define("EMAIL_1",$pre_text.'When the payout routine is run this email template will be sent to all the members who have earned comissions and bonuses in a particular payout cycle."'.$protext);
define("EMAIL_2",$pre_text.'When a new member joins the network this email will be sent to all members in the upline of that user right upto the first user of the network."'.$protext);
define("EMAIL_3",$pre_text.'This is the email that is sent to the site admin notifying him when a member initiates a new withdrawal from the Beau Fairy Membership Financial Dashboard."'.$protext);
define("EMAIL_4",$pre_text.'This email is sent to an individual member once his withdrawal has been successfully processed by the admin."'.$protext);
define("PAYOUT_REC_EMAIL",$pre_text.'When the payout routine is run this email template will be sent to all the members who have earned comissions and bonuses in a particular payout cycle."'.$protext);
define("NETWORK_GROW_EMAIL",$pre_text.'When a new member joins the network this email will be sent to all members in the upline of that user right upto the first user of the network. "'.$protext);
define("WITHDRAWAL_INIT_EMAIL",$pre_text.'This is the email that is sent to the site admin notifying him when a member initiates a new withdrawal from the MLM Financial Dashboard. "'.$protext);
define("WITHDRAWAL_PROC_EMAIL",$pre_text.'This email is sent to an individual member once his withdrawal has been successfully processed by the admin. "'.$protext);

/***************** EOF Email Settings Tab Text ************/
