<?php
// Heading
$_['heading_title']                = 'Reward Points Extended';

// Text
$_['text_version']                 = 'Reward Points Extended v2.0';
$_['text_module']                  = 'Modules';
$_['text_success']                 = 'Success: You have modified module Reward Points Extended!';

// Entry
$_['entry_auto_checkout']          = 'Automatically Use Points:<br/><span class="help">If customers points should be auto-applied to orders on checkout/cart.</span>';
$_['entry_allow_discounting']      = 'Allow Discounting:<br/><span class="help">If a product can be purchased with reward points, by default they can discount a percentage of the price.</span>';
$_['entry_purchase_url']           = 'Purchable Points URL:<br/><span class="help">If points can be purchased on your website, enter the url for purchasing here. (leave blank to disable)</span>';

$_['text_allow_discounting']       = 'Allow Discounting (Default)';
$_['text_full_points']             = 'Require All Points';

$_['entry_cancelled_orders']       = 'Cancelled Orders:<br /><span class="help">Any orders reaching the following status should be considered cancelled, any rewarded points should be removed from customer accounts.</span>';
$_['entry_completed_orders']       = 'Completed Orders:<br /><span class="help">Any orders reaching the following status should be considered complete, any pending reward points should be processed onto the customer account.</span>';

//REWARD POINTS DISPLAY FORMATTING
$_['heading_currency']             = 'Display Settings';
$_['entry_currency_mode']          = 'Reward Points Mode:<br/><span class="help">How reward points display and behave.</span>';
$_['entry_hidden_zero']            = 'Hide On Zero:<span class="help">If a product does not reward points, hide the display when zero.</span>';
$_['entry_subtext_display']        = 'Subtext Point Earnings:<span class="help">Override the default display to use subtext instead.</span>';
$_['entry_pop_notification']       = 'P.O.P Notification:<span class="help">Display a subtext notification on product pages when a product can only be purchased with reward points.</span>';
$_['entry_currency_prefix']        = 'Points Prefix:<span class="help">Prefix the display of reward points (on product listings) with the following character/symbol.</span>';
$_['entry_currency_suffix']        = 'Points Suffix:<span class="help">Suffix the display of reward points (on product listings) with the following character/symbol.</span>';

$_['text_zero_display']            = 'Display Zero (Default)';
$_['text_zero_hide']               = 'Hide When Zero';
$_['text_display_attribute']       = 'Display As Attribute (Default)';
$_['text_display_subtext']         = 'Display As Subtext';
$_['text_integer']                 = 'As Integer (Default)';
$_['text_float']                   = 'As Float (Currency)';

//REWARD POINT BONUSES
$_['heading_bonuses']              = 'Reward Point Bonsuses';

$_['entry_registration_bonus']     = 'Account Registration:<span class="help">The number of bonus reward points a customer receives for registering an account.</span>';
$_['entry_newsletter_bonus']       = 'Newsletter Subscription:<span class="help">The number of bonus reward points a customer receives for subscribing to the newsletter.</span>';
$_['entry_newsletter_unsubscribe'] = 'Subscription Removal:<span class="help">Remove the reward points bonus if a customer un-subscribes from newsletter.</span>';
$_['entry_order_bonus']            = 'First Order:<span class="help">The number of bonus reward points a customer receives for placing their first order.</span>';
$_['entry_review_bonus']           = 'Product Reviews:<span class="help">The number of bonus reward points a customer receives for rewiewing a product.</span>';
$_['entry_review_limit']           = 'Product Review Limit:<span class="help">The total number of times a customer can receive a bonus for reviewing a product.</span>';
$_['entry_review_auto_approve']    = 'Review Approval:<span class="help">Automatically approve reviews above the following star rating.</span>';
$_['text_unlimited']               = 'Unlimited';


//EMAIL REMINDERS
$_['heading_email_reminder']       = 'Product Review Reminder E-Mailing';

$_['entry_email_reminder_enabled'] = 'Reminders Enabled:<span class="help">If e-mail reminders should be sent out or not.</span>';
$_['entry_email_status']           = 'Order Status Reminder:<span class="help">Select one or more order statuses that is acceptable for a review reminder to be dispatched.</span>';
$_['entry_email_date']             = 'Date Filter:<span class="help">Which date should be using in calculating what review reminder e-mails should be sent.</span>';
$_['entry_email_days']             = 'Days Delayed:<span class="help">The number of days from order date to wait before sending a review reminder e-mail.</span>';
$_['entry_email_subject']          = 'E-Mail Subject:<span class="help">The subject line of review reminder e-mails.</span>';
$_['entry_email_content']          = 'Message Content:<span class="help">The content of the e-mail message that you will be sending.</span>';
$_['entry_email_cron']             = 'Cron Job Status:<span class="help">Shows the last run time of the automated cron job that dispatches reminder e-mails.</span>';
$_['entry_email_test']             = 'Send A Test E-Mail:<span class="help">Enter your e-mail address to send yourself a test e-mail.</span>';

$_['text_email_variables']         = '<span class="help"><strong>Supported Variables</strong><br />
										{first_name} - The customers first name.<br />
										{last_name} - The customers last name.<br />
										{order_id} - Order ID/Number.<br />
										{review_bonus} - Bonus reward points for leaving a product review.<br />
										{review_limit} - Maximum times a customer can receive a bonus.<br /></span>';
$_['text_email_send_test']         = 'Send Test E-Mail';
$_['text_success_email']           = 'Success: You sent out %s pending review reminder e-mails!';
$_['text_success_email_test']      = 'Success: You sent a test e-mail to <i>%s</i>!';
$_['text_success_cron']            = '<span class="help"><font color="green"><strong>Active</strong></font><br />Last Run Dispatched %s E-Mails.<br /> Last Job Run Time: %s</span>';
$_['text_date_created']            = 'Date Created';
$_['text_date_modified']           = 'Date Modified';

$_['button_send_reminders']        = 'Send Review Reminders (%s)';

// Error
$_['error_permission']    = 'Warning: You do not have permission to modify module Reward Points Extended!';


?>