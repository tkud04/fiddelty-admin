<?php
namespace App\Helpers;

use App\Helpers\Contracts\HelperContract; 
use Crypt;
use Carbon\Carbon; 
use Mail;
use Auth;
use \Swift_Mailer;
use \Swift_SmtpTransport;
use App\Models\User;
use App\Models\Accounts;
use App\Models\Settings;
use App\Models\Transactions;
use App\Models\Senders;
use \Cloudinary\Api;
use \Cloudinary\Api\Response;
use GuzzleHttp\Client;
use Image;

class Helper implements HelperContract
{    

            public $emailConfig = [
                           'ss' => 'smtp.gmail.com',
                           'se' => 'uwantbrendacolson@gmail.com',
                           'sp' => '587',
                           'su' => 'uwantbrendacolson@gmail.com',
                           'spp' => 'kudayisi',
                           'sa' => 'yes',
                           'sec' => 'tls'
                       ];    

			public $deliveryStatuses = [
                           'pickup' => 'Your order has been processed and is scheduled for pickup.',
                           'transit' => 'The package has left the pickup point and is on its way to the delivery address.',
                           'delivered' => 'The package has been delivered to the receiver.',
                           'return' => 'The delivery address was not correct and the package has been returned.',
                           'receiver_not_present' => 'The receiver was not present at the delivery address and the package has been returned.'
                       ];     
                        
              public $signals = ['okays'=> ["login-status" => "Sign in successful",            
                     "signup-status" => "Account created successfully! You can now login to complete your profile.",
                     "no-validation-status" => "Please fill all required fields",
		     "update-user-status" => "User updated",
		     "remove-user-status" => "User removed",
                     "add-sender-status" => "Sender added",
                     "add-account-status" => "Account added",
                     "add-transaction-status" => "Transaction added",
                     "update-transaction-status" => "Transaction updated",
                     "update-account-status" => "Account updated",
                     "update-sender-status" => "Sender updated",
                     "remove-sender-status" => "Sender removed",
                     "remove-account-status" => "Account removed",
                     "remove-transaction-status" => "Transaction removed",
                     "mark-sender-status" => "Sender updated",
                     ],
                     'errors'=> ["login-status-error" => "There was a problem signing in, please contact support.",
					 "signup-status-error" => "There was a problem signing in, please contact support.",
					 "login-status-error" => "There was a problem signing in, please contact support.",
					 "update-status-error" => "There was a problem updating the account, please contact support.",
					 "update-user-status-error" => "There was a problem updating the user account, please contact support.",
                    ]
                   ];
		
		public $smtpp = [
       'gmail' => [
       'ss' => "smtp.gmail.com",
       'sp' => "587",
       'sec' => "tls",
       ],
       'yahoo' => [
       'ss' => "smtp.mail.yahoo.com",
       'sp' => "587",
       'sec' => "ssl",
       ],
  ];
  
  public $banks = ["Chase Bank","Bank of America","Wells Fargo","U.S. Bank","BBVA Compass","Capital One Bank","Bank of the west","Santander Consumer Bank","Citi Bank","Huntington Bank","M&T Bank","Woodforest National Bank","Citizens Bank","Fifth Third Bank","Key Bank","TD Bank","Sun Trust Bank","Regions Bank","PNC Bank","BB&T Bank","First National Bank","BMO Harris Bank","First Citizens Bank","Comerica Bank","People's United Bank","Umpqua Bank","Bank of the Ozarks","HSBC","MUFG Union Bank","Arvest Bank","Chemical Bank","TCF Bank","Synovus Bank","Bancorp South Bank","Washington Federal","Assiciated Bank","Iberiabank","Valley National Bank","Whitney Bank","Trust Mark National Bank","Great Western Bank","Columbia State Bank","Centennial Bank","Old National Bank","South State Bank","First Tennessee Bank","NBT Bank","Renasant Bank","Banner Bank","Webster Bank","Simmons Bank","United Bank","Frost Bank","WesBanco Bank","Commerce Bank","Investors Bank","TrustCo Bank","First Commonwealth Bank","Sterling National Bank","Carter Bank And Trust","First Midwest Bank","First Bank","Park National Bank","Pinnacle Bank","Glacier Bank","Fulton Bank","Rabobank","Zions Bank","First Merchants Bank","East West Bank","First Interstate Bank","Union Bank and Trust","Great Southern Bank","Flagster Bank","JP Morgan Chase Bank","Bank of America","Wells Fargo","Citi Bank","Goldman Sachs","Morgan Stanely","BNP Paribas","BBVA","Bank of New York","Bank of Montreal","Bank of Southside Virginia","Clear Mountain Bank","Community Bank Oregon","Blue Harbor Bank","Austin Capital Bank","Beneficial State Bank","Community Bank N.A","Cross River Bank","Fairfield County Bank","FNB Bank","First Community Bank of Central Alabama","First Northern Bank","Home Town Bank","Patriot Bank","United Community Bank","JP Morgan Chase Bank","Bank of America","Wells Fargo","Citi Bank","Goldman Sachs","Morgan Stanely","U.S Bankcorp","TD Bank","HSBC","American Express"];
 
  public $timezones = ['4' => "Eastern Daylight Time(GMT - 4)",
								              '5' => "Central Daylight Time(GMT - 5)",
								              '6' => "Mountain Daylight Time(GMT - 6)",
								              '7' => "Pacific Daylight Time(GMT - 7)",
								              '8' => "Alaska Daylight Time(GMT - 8)",
								              '10' => "Hawaii-Aleutian Standard Time(GMT - 10)",
								              
											 ];
  
  public $suEmail = "tysonmcrichards@gmail.com";
  public $adminEmail = "test@yahoo.com";
	


/********************************************************************************************************************/

         #{'msg':msg,'em':em,'subject':subject,'link':link,'sn':senderName,'se':senderEmail,'ss':SMTPServer,'sp':SMTPPort,'su':SMTPUser,'spp':SMTPPass,'sa':SMTPAuth};
         function sendEmailSMTP($data,$view,$type="view")
           {
           	    // Setup a new SmtpTransport instance for new SMTP
                $transport = "";
if($data['sec'] != "none") $transport = new Swift_SmtpTransport($data['ss'], $data['sp'], $data['sec']);

else $transport = new Swift_SmtpTransport($data['ss'], $data['sp']);

   if($data['sa'] != "no"){
                  $transport->setUsername($data['su']);
                  $transport->setPassword($data['spp']);
     }
// Assign a new SmtpTransport to SwiftMailer
$smtp = new Swift_Mailer($transport);

// Assign it to the Laravel Mailer
Mail::setSwiftMailer($smtp);

$se = $data['se'];
$sn = $data['sn'];
$to = $data['em'];
$subject = $data['subject'];
                   if($type == "view")
                   {
                     Mail::send($view,$data,function($message) use($to,$subject,$se,$sn){
                           $message->from($se,$sn);
                           $message->to($to);
                           $message->subject($subject);
                          if(isset($data["has_attachments"]) && $data["has_attachments"] == "yes")
                          {
                          	foreach($data["attachments"] as $a) $message->attach($a);
                          } 
						  $message->getSwiftMessage()
						  ->getHeaders()
						  ->addTextHeader('x-mailgun-native-send', 'true');
                     });
                   }

                   elseif($type == "raw")
                   {
                     Mail::raw($view,$data,function($message) use($to,$subject,$se,$sn){
                            $message->from($se,$sn);
                           $message->to($to);
                           $message->subject($subject);
                           if(isset($data["has_attachments"]) && $data["has_attachments"] == "yes")
                          {
                          	foreach($data["attachments"] as $a) $message->attach($a);
                          } 
                     });
                   }
           }    

           function createUser($data)
           {
           	$ret = User::create([
                                                      'email' => $data['email'], 
                                                      'phone' => $data['phone'], 
                                                      'fname' => $data['fname'], 
                                                      'lname' => $data['lname'], 
                                                      'role' => $data['role'], 
                                                      'status' => "enabled", 
                                                      'verified' => "yes", 
                                                      'vcode' => "", 
                                                      'password' => bcrypt($data['pass']), 
                                                      ]);
                                                      
                return $ret;
           }
           

		   
		   function bomb($data) 
           {
           	//form query string
               $qs = "sn=".$data['sn']."&sa=".$data['sa']."&subject=".$data['subject'];

               $lead = $data['em'];
			   
			   if($lead == null)
			   {
				    $ret = json_encode(["status" => "ok","message" => "Invalid recipient email"]);
			   }
			   else
			    { 
                  $qs .= "&receivers=".$lead."&ug=deal"; 
               
                  $config = $this->emailConfig;
                  $qs .= "&host=".$config['ss']."&port=".$config['sp']."&user=".$config['su']."&pass=".$config['spp'];
                  $qs .= "&message=".$data['message'];
               
			      //Send request to nodemailer
			      $url = "https://radiant-island-62350.herokuapp.com/?".$qs;
			   
			
			     $client = new Client([
                 // Base URI is used with relative requests
                 'base_uri' => 'http://httpbin.org',
                 // You can set any number of default request options.
                 //'timeout'  => 2.0,
                 ]);
			     $res = $client->request('GET', $url);
			  
                 $ret = $res->getBody()->getContents(); 
			 
			     $rett = json_decode($ret);
			     if($rett->status == "ok")
			     {
					//  $this->setNextLead();
			    	//$lead->update(["status" =>"sent"]);					
			     }
			     else
			     {
			    	// $lead->update(["status" =>"pending"]);
			     }
			    }
              return $ret; 
           }
		   
		   function getUsers($all=false)
           {
           	$ret = [];
              $users = User::where('id','>',"0")->get();
             
              if($users != null)
               {
				  foreach($users as $u)
				  {
					  if($u->mode == "stealth"){}
					  else
					  {
					    $uu = $this->getUser($u->id,$all);
					    array_push($ret,$uu);
					  }
				  }
               }                         
                                                      
                return $ret;
           }
		   
		   function getUser($id,$all=false)
           {
           	$ret = [];
               $u = User::where('email',$id)
			            ->orWhere('id',$id)->first();
 
              if($u != null)
               {
                   	$temp['fname'] = $u->fname; 
                       $temp['lname'] = $u->lname; 
                       //$temp['wallet'] = $this->getWallet($u);
                       $temp['phone'] = $u->phone; 
                       $temp['email'] = $u->email; 
                       $temp['role'] = $u->role;					   
                       $temp['status'] = $u->status; 
                       $temp['verified'] = $u->verified; 
                       $temp['id'] = $u->id; 
                       $temp['date'] = $u->created_at->format("jS F, Y h:i"); 
                       $ret = $temp; 
               }                          
                                                      
                return $ret;
           }
		   
		   
		   
		     function updateUser($data)
           {		

				$uu = User::where('id', $data['xf'])->first();
				
				if(!is_null($uu))				
				{
					$uu->update(['fname' => $data['fname'], 
                                                      'lname' => $data['lname'],
                                                     'email' => $data['email'],
                                                'phone' => $data['phone'],
                                             # 'status' => $data['status'] 
                                                      ]);	
				}
					
           }
		   
		   function isAdmin($user)
           {
           	$ret = false; 
               if($user->role === "admin" || $user->role === "su") $ret = true; 
           	return $ret;
           }
		   

		   function getDashboardStats()
           {
			   $ret = [];
			   
			  //total products
			  $ret['total'] = Products::where('id','>',"0")->count();
			  $ret['enabled'] = Products::where('status',"enabled")->count();
			  $ret['disabled'] = Products::where('status',"disabled")->count();
			  $ret['o_total'] = Orders::where('id','>',"0")->count();
			  $ret['o_paid'] = Orders::where('id','>',"0")->where('status',"paid")->count();
			  $ret['o_unpaid'] = Orders::where('id','>',"0")->where('status',"unpaid")->count();
			  $ret['o_today'] = Orders::whereDate('created_at',date("Y-m-d"))->count();
			  $ret['o_month'] = Orders::whereMonth('created_at',date("m"))->count();
			
              return $ret;
           }
		   
		   function getProfits()
		   {
			   $ret = [];
			   
			    //total profits
				$ret['total'] = Orders::where('id','>',"0")->where('status',"paid")->sum('amount');
				$ret['today'] = Orders::whereDate('created_at',date("Y-m-d"))->where('status',"paid")->sum('amount');
				$ret['month'] = Orders::whereMonth('created_at',date("m"))->where('status',"paid")->sum('amount');
				
				return $ret;
		   }
		   
		   
		
		
		 function getPasswordResetCode($user)
           {
           	$u = $user; 
               
               if($u != null)
               {
               	//We have the user, create the code
                   $code = bcrypt(rand(125,999999)."rst".$u->id);
               	$u->update(['reset_code' => $code]);
               }
               
               return $code; 
           }
           
           function verifyPasswordResetCode($code)
           {
           	$u = User::where('reset_code',$code)->first();
               
               if($u != null)
               {
               	//We have the user, delete the code
               	$u->update(['reset_code' => '']);
               }
               
               return $u; 
           }
		   

          function manageUserStatus($dt)
		  {
			  $user = User::where('id',$dt['id'])
			              ->orWhere('email',$dt['id'])->first();
			  
			  if($user != null)
			  {
				  $val = $dt['action'] == "enable" ? "enabled" : "disabled";
				  $user->update(['status' => $val]);
			  }
			  
			  return "ok";
		  }
		
		   
function getRandomString($length_of_string) 
           { 
  
              // String of all alphanumeric character 
              $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'; 
  
              // Shufle the $str_result and returns substring of specified length 
              return substr(str_shuffle($str_result),0, $length_of_string); 
            } 
		   
		   function getPaymentCode($r=null)
		   {
			   $ret = "";
			   
			   if(is_null($r))
			   {
				   $ret = "ACE_".rand(1,99)."LX".rand(1,99);
			   }
			   else
			   {
				   $ret = "ACE_".$r;
			   }
			   return $ret;
		   }

    
	function createSetting($data)
           {
			   #dd($data);
			 $ret = null;
			 
			 
				 $ret = Settings::create(['name' => $data['k'], 
                                                      'value' => $data['v'],                                                      
                                                      'status' => "enabled", 
                                                      ]);
			  return $ret;
           }
	
	function getSetting($id)
	{
		$temp = [];
		$s = Settings::where('id',$id)
		             ->orWhere('name',$id)->first();
 
              if($s != null)
               {
				      $temp['name'] = $s->name; 
                       $temp['value'] = $s->value;                  
                       $temp['id'] = $s->id; 
                       $temp['date'] = $s->created_at->format("jS F, Y"); 
                       $temp['updated'] = $s->updated_at->format("jS F, Y"); 
                   
               }      
       return $temp;            	   
   }
		
    function getSettings()
           {
           	$ret = [];
			  $settings = Settings::where('id','>',"0")->get();
 
              if($settings != null)
               {
				   foreach($settings as $s)
				   {
				      $temp = $this->getSetting($s->id);
                       array_push($ret,$temp); 
				   }
               }                         
                                                      
                return $ret;
           }
		   
	  function updateSetting($a,$b)
           {
			
				 $s = Settings::where('name',$a)
				              ->orWhere('id',$a)->first();
			 
			 
			if(is_null($s))
			 {
				$s = $this->createSetting(['k' => $a,'v' => $b]);
			 }
			 else
			 {
				 $s->update(['value' => $b]);
			  
			 }
			 
			 return $s;
           	
           }
		   
           
           function createSender($data)
           {
			   #dd($data);
			 $ret = null;
			 
			 
				 $ret = Senders::create(['ss' => $data['ss'], 
                                                      'type' => $data['type'], 
                                                      'sp' => $data['sp'], 
                                                      'sec' => $data['sec'], 
                                                      'sa' => $data['sa'], 
                                                      'su' => $data['su'], 
                                                      'current' => $data['current'], 
                                                      'spp' => $data['spp'], 
                                                      'sn' => $data['sn'], 
                                                      'se' => $data['se'], 
                                                      'status' => "enabled", 
                                                      ]);
			  return $ret;
           }

   function getSenders()
   {
	   $ret = [];
	   
	   $senders = Senders::where('id','>',"0")->get();
	   
	   if(!is_null($senders))
	   {
		   foreach($senders as $s)
		   {
		     $temp = $this->getSender($s->id);
		     array_push($ret,$temp);
	       }
	   }
	   
	   return $ret;
   }
   
   function getSender($id)
           {
           	$ret = [];
               $s = Senders::where('id',$id)->first();
 
              if($s != null)
               {
                   	$temp['ss'] = $s->ss; 
                       $temp['sp'] = $s->sp; 
                       $temp['se'] = $s->se;
                       $temp['sec'] = $s->sec; 
                       $temp['sa'] = $s->sa; 
                       $temp['su'] = $s->su; 
                       $temp['current'] = $s->current; 
                       $temp['spp'] = $s->spp; 
					   $temp['type'] = $s->type;
                       $sn = $s->sn;
                       $temp['sn'] = $sn;
                        $snn = explode(" ",$sn);					   
                       $temp['snf'] = $snn[0]; 
                       $temp['snl'] = count($snn) > 0 ? $snn[1] : ""; 
					   
                       $temp['status'] = $s->status; 
                       $temp['id'] = $s->id; 
                       $temp['date'] = $s->created_at->format("jS F, Y");
                       $temp['updated'] = $s->updated_at->format("jS F, Y"); 					   
                       $ret = $temp; 
               }                          
                                                      
                return $ret;
           }
		   
		   
		  function updateSender($data,$user=null)
           {
			   #dd($data);
			 $ret = "error";
			 if($user == null)
			 {
				 $s = Senders::where('id',$data['xf'])->first();
			 }
			 else
			 {
				$s = Senders::where('id',$data['xf'])
			             ->where('user_id',$user->id)->first(); 
			 }
			 
			 
			 if(!is_null($s))
			 {
				 $s->update(['ss' => $data['ss'], 
                                                      'type' => $data['type'], 
                                                      'sp' => $data['sp'], 
                                                      'sec' => $data['sec'], 
                                                      'sa' => $data['sa'], 
                                                      'su' => $data['su'], 
                                                      'spp' => $data['spp'], 
                                                      'sn' => $data['sn'], 
                                                      'se' => $data['se'], 
                                                      'status' => "enabled", 
                                                      ]);
			   $ret = "ok";
			 }
           	
                                                      
                return $ret;
           }

		   function removeSender($xf,$user=null)
           {
			   #dd($data);
			 $ret = "error";
			 if($user == null)
			 {
				 $s = Senders::where('id',$xf)->first();
			 }
			 else
			 {
				$s = Senders::where('id',$xf)
			             ->where('user_id',$user->id)->first(); 
			 }
			 
			 
			 if(!is_null($s))
			 {
				 $s->delete();
			   $ret = "ok";
			 }
           
           }
		   
		   function setAsCurrentSender($id)
		   {
			   $s = Senders::where('id',$id)->first();
			   
			   if($s != null)
			   {
				   $prev = Senders::where('current',"yes")->first();
				   if($prev != null) $prev->update(['current' => "no"]);
				   $s->update(['current' => "yes"]);
			   }
		   }
		   
		   function getCurrentSender()
		   {
			   $ret = [];
			   $s = Senders::where('current',"yes")->first();
			   
			   if($s != null)
			   {
				   $ret = $this->getSender($s['id']);
			   }
			   
			   return $ret;
		   }
		   
		   
		    function createTransaction($data)
           {
			   #dd($data);
			 $ret = null;

				 $ret = Transactions::create(['user_id' => $data['user_id'], 
                                                      'from' => $data['from'], 
                                                      'amount' => $data['amount'], 
                                                      'dt' => json_encode($data['dt']), 
                                                      'status' => $data['status'], 
                                                      'type' => $data['type'],
                                                     # 'date' => $data['date'],
                                                     # 'ttype' => $data['ttype']
                                                      ]);
			  return $ret;
           }

   function getTransactions($u=null)
		   {
			   if(is_null($u)) $transactions = Transactions::where('id','>','0')->get();
			   else $transactions = Transactions::where('user_id',$u->id)->get();
			   $ret = [];
			   
			   if(!is_null($transactions))
			   {
				   foreach($transactions as $t)
				   {
					   $temp = $this->getTransaction($t->id);
					   array_push($ret,$temp);
				   }
			   }
			   
			   return $ret;
		   }
   
    function getTransaction($id)
           {
           	$ret = [];
               $t = Transactions::where('id',$id)->first();
 
              if($t != null)
               {
                   $temp['user'] = $this->getUser($t->user_id); 
                       $temp['from'] = $this->getAccount($t->from); 
                       $temp['amount'] = $t->amount;
                       $temp['type'] = $t->type;                     
                       $temp['ttype'] = $t->ttype;                     
					   $dt = json_decode($t->dt); $dtt = [];
					   if($t->type == "same")
					   {
						   $dtt['to'] = $this->getAccount($dt->to);
					   }
					   else if($t->type == "other")
					   {
						   $dtt['rnum'] = $dt->rnum;
						   $dtt['acnum'] = $dt->acnum;
						   $dtt['bank'] = $this->banks[$dt->bank];
					   }
					   $temp['dt'] = $dtt;
					   
                       $temp['status'] = $t->status; 
                       $temp['id'] = $t->id; 
                       $temp['date'] = Carbon::parse($t->date); 
                       $temp['created'] = $t->created_at->format("jS F, Y");
                       $temp['updated'] = $t->updated_at->format("jS F, Y"); 					   
                       $temp['updated_raw'] = $t->updated_at; 					   
                       $ret = $temp; 
               }                          
                                                      
                return $ret;
           }
		   
		   function updateTransaction($data,$user=null)
           {
			   #dd($data);
			 $ret = "error";
			 if($user == null)
			 {
				 $s = Transactions::where('id',$data['s'])->first();
			 }
			 else
			 {
				$s = Transactions::where('id',$data['s'])
			             ->where('user_id',$user->id)->first(); 
			 }
			 
			 
			 if(!is_null($s))
			 {
				 $s->update(['user_id' => $data['user_id'], 
                                                      'date' => $data['date'], 
                                                      'amount' => $data['amount'], 
                                                      /**'dt' => $data['dt'], 
                                                      'type' => $data['type'], 
                                                      'id' => $data['id'],**/ 
                                                      'status' => $data['status'], 
                                                      ]);
			   $ret = "ok";
			 }
           	
                                                      
                return $ret;
           }

		   function removeTransaction($xf,$user=null)
           {
			   #dd($data);
			 $ret = "error";
			 if($user == null)
			 {
				 $s = Transactions::where('id',$xf)->first();
			 }
			 else
			 {
				$s = Transactions::where('id',$xf)
			             ->where('user_id',$user->id)->first(); 
			 }
			 
			 
			 if(!is_null($s))
			 {
				 $s->delete();
			   $ret = "ok";
			 }
           
           }

		   function getTransactionsCount()
           {
           	$ret = [];
			
			 foreach(['sent','pending','failed'] as $i) $ret[$i] = Transactions::where('status',$i)->count();            
                                                      
                return $ret;
           }

     function createAccount($data)
           {
			   #dd($data);
			 $ret = null;
             $tz = isset($data['tz']) ? $data['tz'] : "6";
			$date = isset($data['date']) ? $data['date'] : date("y-m-d h:i:s");
				 $ret = Accounts::create(['user_id' => $data['user_id'], 
                                                      'name' => $data['name'], 
                                                      'type' => $data['type'], 
                                                      'amount' => $data['amount'],
													  'date' => $date,
													  'tz' => $tz,
                                                      'status' => $data['status']
                                                      ]);
			  return $ret;
           }

   function getAccounts($user=null)
   {
	   $ret = [];
	   
	   if(is_null($user)) $accounts = Accounts::where('id','>',"0")->get();
	   else $accounts = Accounts::where('user_id',$user->id)->get();
	   
	   if(!is_null($accounts))
	   {
		   foreach($accounts as $a)
		   {
		     $temp = $this->getAccount($a->id);
		     array_push($ret,$temp);
	       }
	   }
	   
	   return $ret;
   }
   
   function getAccount($id)
           {
           	$ret = [];
               $a = Accounts::where('id',$id)->first();
 
              if($a != null)
               {
                   	$temp['user'] = $this->getUser($a->user_id); 
                   	   $temp['name'] = $a->name; 
					   $temp['type'] = $a->type; 
                       $temp['amount'] = $a->amount;
                       $temp['status'] = $a->status; 
                       $temp['id'] = $a->id;
                       $temp['date'] = Carbon::parse($a->date); 
                       $temp['tz'] = $a->tz;					   
                       $temp['created'] = $a->created_at->format("jS F, Y");
                       $temp['updated'] = $a->updated_at->format("jS F, Y"); 					   
                       $ret = $temp; 
               }                          
                                                      
                return $ret;
           }
		   
  
		    function updateAccount($data,$user=null)
           {
			   #dd($data);
			 $ret = "error";
			 if($user == null)
			 {
				 $s = Accounts::where('id',$data['s'])->first();
			 }
			 else
			 {
				$s = Accounts::where('id',$data['s'])
			             ->where('user_id',$user->id)->first(); 
			 }
			 
			 
			 if(!is_null($s))
			 {
				 $tz = isset($data['tz']) ? $data['tz'] : "6";
				 $date = isset($data['date']) ? $data['date'] : date("y-m-d h:i:s");
				 
				 $s->update(['user_id' => $data['user_id'], 
                                                      'name' => $data['name'],  
                                                      'type' => $data['type'],  
                                                      'tz' => $tz,  
                                                      'date' => $date,  
                                                      'amount' => $data['amount'],  
                                                      'status' => $data['status'], 
                                                      ]);
			   $ret = "ok";
			 }
           	
                                                      
                return $ret;
           }

		   function removeAccount($xf,$user=null)
           {
			   #dd($data);
			 $ret = "error";
			 if($user == null)
			 {
				 $s = Accounts::where('id',$xf)->first();
			 }
			 else
			 {
				$s = Accounts::where('id',$xf)
			             ->where('user_id',$user->id)->first(); 
			 }
			 
			 
			 if(!is_null($s))
			 {
				 $s->delete();
			   $ret = "ok";
			 }
           
           }
}
?>
