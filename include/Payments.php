<?php

namespace Tbmt;

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;


use \Transaction as TransactionEntity;

class Payments {
  static public function executePayPalPayment($paymentId, $payerId) {
    $apiContext = self::getApiContext();

    $payment = null;
    try {
      $payment = Payment::get($paymentId, $apiContext);

      if ( !$payment )
        throw new \Exception('Could not retrieve payment from paypal');

      // ### Payment Execute
      // PaymentExecution object includes information necessary
      // to execute a PayPal account payment.
      // The payer_id is added to the request query parameters
      // when the user is redirected from paypal back to your site
      $execution = new PaymentExecution();
      $execution->setPayerId($payerId);

      // error_log(print_r($payment->toArray(), true));
      $payment->execute($execution, $apiContext);
      // error_log(print_r($payment->toArray(), true));

      return [$payment, null];
    } catch(\Exception $e) {
      return [$payment, $e];
    }
  }

  static public function getPaypal($paymentId) {
    $apiContext = self::getApiContext();
    return Payment::get($paymentId, $apiContext);
  }

  static public function createPayPal($invoiceNumber, \PropelPDO $con) {
    $i18n = Localizer::get('payment');

    // ### Payer
    // A resource representing a Payer that funds a payment
    // For direct credit card payments, set payment method
    // to 'credit_card' and add an array of funding instruments.
    $payer = new Payer();
    $payer->setPaymentMethod("paypal");

    // ### Itemized information
    // (Optional) Lets you specify item wise
    // information
    $item1 = new Item();
    $item1->setName($i18n['item_name'])
        ->setDescription($i18n['item_description'])
        ->setCurrency(TransactionEntity::$BASE_CURRENCY)
        ->setQuantity(1)
        ->setTax(0)
        ->setPrice(TransactionEntity::$MEMBER_FEE);

    $itemList = new ItemList();
    $itemList->setItems(array($item1));

    // ### Amount
    // Lets you specify a payment amount.
    // You can also specify additional details
    // such as shipping, tax.
    $amount = new Amount();
    $amount->setCurrency(TransactionEntity::$BASE_CURRENCY)
        ->setTotal(TransactionEntity::$MEMBER_FEE);

    // ### Transaction
    // A transaction defines the contract of a
    // payment - what is the payment for and who
    // is fulfilling it.
    $transaction = new Transaction();
    $transaction->setAmount($amount)
        ->setItemList($itemList)
        ->setDescription($i18n['transaction_description'])
        ->setInvoiceNumber($invoiceNumber);

    // ### Redirect urls
    // Set the urls that the buyer must be redirected to after
    // payment approval/ cancellation.
    $success = Router::toModule('account', 'index');
    $failure = Router::toModule('guide', 'index', ['purchase_failed' => true]);
    $redirectUrls = new RedirectUrls();
    $redirectUrls->setReturnUrl($success)
        ->setCancelUrl($failure);

    // ### Payment
    // A Payment Resource; create one using
    // the above types and intent set to sale 'sale'
    $payment = new Payment();
    $payment->setIntent("sale")
      ->setPayer($payer)
      ->setRedirectUrls($redirectUrls)
      ->setExperienceProfileId(self::getPaymentExperienceProfileId())
      ->setTransactions(array($transaction));

    // ### Create Payment
    // Create a payment by calling the payment->create() method
    // with a valid ApiContext (See bootstrap.php for more on `ApiContext`)
    // The return object contains the state.
    $apiContext = self::getApiContext();
    $payment->create($apiContext);
    return $payment;

  }

  static private $apiContext;

  static public function getApiContext() {
    if ( !self::$apiContext ) {
      self::$apiContext = new \PayPal\Rest\ApiContext(
        new \PayPal\Auth\OAuthTokenCredential(
            Config::get('paypal.clientid'),
            Config::get('paypal.clientsecret')
        )
      );
    }

    return self::$apiContext;
  }

  static public function getPaymentExperienceProfileId() {
    $apiContext = self::getApiContext();
    $list = \PayPal\Api\WebProfile::get_list($apiContext);
    if ( count($list) > 0 )
      return $list[0]->getId();

    // ### Create Web Profile
    // Use the /web-profiles resource to create seamless payment experience profiles. See the payment experience overview for further information about using the /payment resource to create the PayPal payment and pass the experience_profile_id.
    // Documentation available at https://developer.paypal.com/webapps/developer/docs/api/#create-a-web-experience-profile
    // Lets create an instance of FlowConfig and add
    // landing page type information
    $flowConfig = new \PayPal\Api\FlowConfig();
    // Type of PayPal page to be displayed when a user lands on the PayPal site for checkout. Allowed values: Billing or Login. When set to Billing, the Non-PayPal account landing page is used. When set to Login, the PayPal account login landing page is used.
    $flowConfig->setLandingPageType("Login");
    // The URL on the merchant site for transferring to after a bank transfer payment.
    // $flowConfig->setBankTxnPendingUrl("http://www.yeowza.com/");
    // When set to "commit", the buyer is shown an amount, and the button text will read "Pay Now" on the checkout page.
    $flowConfig->setUserAction(null);
    // Defines the HTTP method to use to redirect the user to a return URL. A valid value is `GET` or `POST`.
    // $flowConfig->setReturnUriHttpMethod("GET");
    //
    // Parameters for style and presentation.
    $presentation = new \PayPal\Api\Presentation();
    // A URL to logo image. Allowed vaues: .gif, .jpg, or .png.
    $presentation->setLogoImage("http://betterliving.social/assets/images/paypal_checkout_brand.jpg")
    //  A label that overrides the business name in the PayPal account on the PayPal pages.
      ->setBrandName(\Tbmt\Config::get('brand.name'))
    //  Locale of pages displayed by PayPal payment experience.
      ->setLocaleCode("US");
    // A label to use as hypertext for the return to merchant link.
      // ->setReturnUrlLabel("Return")
    // A label to use as the title for the note to seller field. Used only when `allow_note` is `1`.
      // ->setNoteToSellerLabel("Thanks!");
    // Parameters for input fields customization.
    $inputFields = new \PayPal\Api\InputFields();
    // Enables the buyer to enter a note to the merchant on the PayPal page during checkout.
    $inputFields->setAllowNote(false)
      // Determines whether or not PayPal displays shipping address fields on the experience pages. Allowed values: 0, 1, or 2. When set to 0, PayPal displays the shipping address on the PayPal pages. When set to 1, PayPal does not display shipping address fields whatsoever. When set to 2, if you do not pass the shipping address, PayPal obtains it from the buyer’s account profile. For digital goods, this field is required, and you must set it to 1.
      ->setNoShipping(1)
      // Determines whether or not the PayPal pages should display the shipping address and not the shipping address on file with PayPal for this buyer. Displaying the PayPal street address on file does not allow the buyer to edit that address. Allowed values: 0 or 1. When set to 0, the PayPal pages should not display the shipping address. When set to 1, the PayPal pages should display the shipping address.
      ->setAddressOverride(0);

    // #### Payment Web experience profile resource
    $webProfile = new \PayPal\Api\WebProfile();

    // Name of the web experience profile. Required. Must be unique
    $webProfile->setName("happiness-guide")
      // Parameters for flow configuration.
      ->setFlowConfig($flowConfig)
      // Parameters for style and presentation.
      ->setPresentation($presentation)
      // Parameters for input field customization.
      ->setInputFields($inputFields)
      // Indicates whether the profile persists for three hours or permanently. Set to `false` to persist the profile permanently. Set to `true` to persist the profile for three hours.
      ->setTemporary(false);

    $createProfileResponse = $webProfile->create($apiContext);
    return $createProfileResponse->getId();
  }
}

?>