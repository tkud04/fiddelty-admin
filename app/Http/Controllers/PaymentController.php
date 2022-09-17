<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Helpers\Contracts\HelperContract; 
use Auth;
use Session; 
use Validator; 
use Carbon\Carbon; 
use Paystack; 
use App\Orders;

class PaymentController extends Controller {

	protected $helpers; //Helpers implementation
    
    public function __construct(HelperContract $h)
    {
    	$this->helpers = $h;                     
    }
    
    
    /**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
    public function postRedirectToGateway(Request $request)
    {
    	if(Auth::check())
		{
			$user = Auth::user();
		}
		else
        {
        	return redirect()->intended('/');
        }
		
		$req = $request->all();
       # dd($req);
        $type = json_decode($req['metadata']);
        //dd($type);
        
   
        $validator = Validator::make($req, [
                             'fname' => 'required|filled',
							 'amount' => 'required',
                             'lname' => 'required|filled',
                             'email' => 'required|email|filled',
                             'address' => 'required|filled',
                             'city' => 'required|filled',
                             'state' => 'required|not_in:none',
                             'phone' => 'required|filled',
                             'terms' => 'required|accepted',
         ]);
         
         if($validator->fails())
         {
             $messages = $validator->messages();
             return redirect()->back()->withInput()->with('errors',$messages);
             //dd($messages);
         }
         
         else
         {
			 if($req['amount'] < 1)
			 {
				 $err = "error";
				 session()->flash("no-cart-status",$err);
				 return redirect()->back();
			 }
			 else
			 {
			   //$paystack = new Paystack();
			   #dd($request);
			   $request->reference = Paystack::genTranxRef();
               $request->key = config('paystack.secretKey');
			 
			   try{
				 return Paystack::getAuthorizationUrl()->redirectNow(); 
			   }
			   catch(Exception $e)
			   {
				 $request->session()->flash("pay-card-status","error");
			     return redirect()->intended("checkout");
			   } 
			 }        
         }        
        
        
    }
    
    /**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function getPaymentCallback(Request $request)
    {
		if(Auth::check())
		{
			$user = Auth::user();
		}
		else
        {
        	return redirect()->intended('login?return=dashboard');
        }
		
        $paymentDetails = Paystack::getPaymentData();

        #dd($paymentDetails);       
        $paymentData = $paymentDetails['data'];
        $successLocation = "";
        $failureLocation = "";
        
        switch($paymentData['metadata']['type'])
        {
        	case 'checkout':
              $successLocation = "orders";
             $failureLocation = "checkout";           
            break; 
            
            case 'kloudpay':
              $successLocation = "transactions";
             $failureLocation = "deposit";
            break; 
       }
        //status, reference, metadata(order-id,items,amount,ssa), type
        if($paymentData['status'] == 'success')
        {
			#dd($paymentData);
        	$stt = $this->helpers->checkout($user,$paymentData);
			
			//send email to user
			$id = $paymentData['metadata']['custom_fields'][0]['value'];
			$o = $this->helpers->getOrder($id);
               #dd($o);
			   
               if($o != null || count($o) > 0)
               {		  
				   //dd($u);
               	//We have the user, notify the customer and admin
				$ret = $this->helpers->smtp;
				$ret['order'] = $o;
				$ret['user'] = $user;
				$ret['subject'] = "Your payment for order ".$o['payment_code']." has been confirmed!";
		        $ret['em'] = $user->email;
		        $this->helpers->sendEmailSMTP($ret,"emails.confirm-payment");
				
				#$ret = $this->helpers->smtp;
				$ret['order'] = $o;
				$ret['user'] =$user->email;
		        $ret['subject'] = "URGENT: Received payment for order ".$o['payment_code'];
		        $ret['em'] = $this->helpers->adminEmail;
		        //$this->helpers->sendEmailSMTP($ret,"emails.admin-payment-alert");
				$ret['em'] = $this->helpers->suEmail;
		        $this->helpers->sendEmailSMTP($ret,"emails.admin-payment-alert");
               }
			   
            $request->session()->flash("pay-card-status",$stt['status']);
			return redirect()->intended($successLocation);
        }
        else
        {
        	//Payment failed, redirect to orders
            $request->session()->flash("pay-card-status","error");
			return redirect()->intended($failureLocation);
        }
    }
    
    
}