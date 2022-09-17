<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Helpers\Contracts\HelperContract; 
use Auth;
use Session; 
use Validator; 
use Carbon\Carbon; 

class MainController extends Controller {

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
	public function getIndex()
    {
       $user = null;
	   
		if(Auth::check())
		{
			$user = Auth::user();
			if(!$this->helpers->isAdmin($user))
			{
				Auth::logout();
				 return redirect()->intended('/');
			}  
		}
		else
		{
			return redirect()->intended('login');
		}
		
		$signals = $this->helpers->signals;
		//$accounts = $this->helpers->getUsers();
		$accounts = [];
		$senders = $this->helpers->getSenders();
		$sender = $this->helpers->getCurrentSender();
		$transactions = $this->helpers->getTransactions();
		$tc = $this->helpers->getTransactionsCount();
		$stats = [];
		$tz = $this->helpers->getSetting('tz');
		$timezones = $this->helpers->timezones;
		#dd($tz);
		#dd($lowStockProducts);
    	return view('index',compact(['user','stats','sender','senders','transactions','tc','tz','timezones','signals']));
    }
    
    /**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function getSettings()
    {
       $user = null;

		if(Auth::check())
		{
			$user = Auth::user();
			if(!$this->helpers->isAdmin($user))
			{
				Auth::logout();
				 return redirect()->intended('/');
			}  
		}
		else
		{
			return redirect()->intended('login');
		}
		
		$signals = $this->helpers->signals;
		
		//$accounts = $this->helpers->getUsers();
		$accounts = [];
		$smtp = $this->helpers->getSetting('smtp');
		
		$sender = $this->helpers->getSender($smtp['value']);
		$senders = $this->helpers->getSenders();
		$settings = [
		   'smtp' => $smtp,
		   'd1' => $d1,
		   'd2' => $d2,
		   'bank' => $bank,
		   
        ];
		#dd($settings);
    	return view('settings',compact(['user','settings','senders','sender','banks','signals']));
    }
    
    /**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function getAddSetting(Request $request)
    {
       $user = null;
       $req = $request->all();
       $ret = ['status' => "ok",'data' => "nothing happened"];
		
		if(Auth::check())
		{
			$user = Auth::user();
			if($this->helpers->isAdmin($user))
			{
				
			
			$req = $request->all();
			
            $validator = Validator::make($req, [                            
                             'k' => 'required',
                             'v' => 'required',
            ]);
         
            if($validator->fails())
            {
               $ret = ['status' => "error",'message' => "validation"];
            }
         
            else
            {
              $s = $this->helpers->createSetting($req);
              $ret = ['status' => "ok",'data' => "settings updated"];
            }
          }
		}
		
        return json_encode($ret);	
    }
	
		/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function getSettingsTZ(Request $request)
    {
        return redirect()->intended('/');	
    }
	
	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
    public function postSettingsTZ(Request $request)
    {	
       $req = $request->all();
	   $rr = ['status' => "error",'message' => "nothing happened"];
		  # dd($req); 
        $validator = Validator::make($req, [
                             'dt' => 'required'
         ]);
         
         if($validator->fails())
         {
             $messages = $validator->messages();
             $rr['message'] = "validation";
             //dd($messages);
         }
         
         else
         {
			$dt = json_decode($req['dt']);
			foreach($dt as $key => $value)
			{
              $s = $this->helpers->updateSetting($key,$value);			
			}
			
			$tz = $this->helpers->timezones[$dt->tz];
			$dtt = ['tz' => $tz,'updated' => $s->updated_at->format("jS F, Y")];
			$rr = ['status' => "ok",'data' => $dtt];
         }

         return json_encode($rr);		 
    }
	
	   /**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function getTransactions(Request $request)
    {
       $user = null;
       $req = $request->all();
       
		
		if(Auth::check())
		{
			$user = Auth::user();
			if(!$this->helpers->isAdmin($user))
			{
				Auth::logout();
				 return redirect()->intended('/');
			} 
		}
		else
		{
			return redirect()->intended('login');
		}
		
		$u = isset($req['all']) ? null : $user;
		$transactions = $this->helpers->getTransactions($u);
		
		$signals = $this->helpers->signals;
		#dd($transactions);
    	return view('transactions',compact(['user','transactions','signals']));
    }
	
	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function getTransaction(Request $request)
    {
       $user = null;
       $req = $request->all();
       
		
		if(Auth::check())
		{
			$user = Auth::user();
			if(!$this->helpers->isAdmin($user))
			{
				Auth::logout();
				 return redirect()->intended('/');
			} 
		}
		else
		{
			return redirect()->intended('login');
		}
		
		$req = $request->all();
		#dd($req);
        $validator = Validator::make($req, [                          
                             's' => 'required'
         ]);
         
         if($validator->fails())
         {
             return redirect()->intended('transactions');
         }
		 else
		 {
			 $transaction = $this->helpers->getTransaction($req['s']);
			 $accounts = $this->helpers->getAccounts($user);
		     $banks = $this->helpers->banks;
		     $signals = $this->helpers->signals;
			#dd($transaction);
		     return view('transaction',compact(['user','transaction','banks','accounts','signals']));	
		 }
		
    }
	
	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
    public function postTransaction(Request $request)
    {
    	if(Auth::check())
		{
			$user = Auth::user();
			if(!$this->helpers->isAdmin($user))
			{
				Auth::logout();
				 return redirect()->intended('/');
			} 
		}
		else
        {
        	return redirect()->intended('login');
        }
        
        $req = $request->all();
		#dd($req);
        $validator = Validator::make($req, [                          
                             #'date' => 'required|not_in:none',
                             'status' => 'required|not_in:none',
                             's' => 'required',
                             'amount' => 'required|numeric'
         ]);
         
         if($validator->fails())
         {
             $messages = $validator->messages();
             return redirect()->back()->withInput()->with('errors',$messages);
             //dd($messages);
         }
         
         else
         {
         	$req['user_id'] = $user->id;
            $this->helpers->updateTransaction($req);
			session()->flash("update-transaction-status", "success");
			return redirect()->intended('transactions');
         } 	  
    }
	
	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function getAddTransaction(Request $request)
    {
       $user = null;
       $req = $request->all();
       
		
		if(Auth::check())
		{
			$user = Auth::user();
			if(!$this->helpers->isAdmin($user))
			{
				Auth::logout();
				 return redirect()->intended('/');
			} 
		}
		else
		{
			return redirect()->intended('login');
		}
		
		
		 $accounts = $this->helpers->getAccounts($user);
		 $banks = $this->helpers->banks;
		 $signals = $this->helpers->signals;
		 
		return view('add-transaction',compact(['user','banks','accounts','signals']));	
		
    }
	
	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
    public function postAddTransaction(Request $request)
    {
    	if(Auth::check())
		{
			$user = Auth::user();
			if(!$this->helpers->isAdmin($user))
			{
				Auth::logout();
				 return redirect()->intended('/');
			} 
		}
		else
        {
        	return redirect()->intended('login');
        }
        
        $req = $request->all();
		#dd($req);
        $validator = Validator::make($req, [                          
                             'from' => 'required|not_in:none',
                            # 'ttype' => 'required|not_in:none',
                             'type' => 'required|not_in:none',
                             'status' => 'required|not_in:none',
                             'amount' => 'required',
                             #'date' => 'required'
         ]);
         
         if($validator->fails())
         {
             $messages = $validator->messages();
             return redirect()->back()->withInput()->with('errors',$messages);
             //dd($messages);
         }
         
         else
         {
         	$dt = ['from' => $req['from'],
			       #'date' => $req['date'],
			       'type' => $req['type'],
				   #'ttype' => $req['ttype'],
				   'amount' => $req['amount'],
				   'status' => $req['status'],
				   'dt' => []];
             $dtt = [];
			 
			 if($req['type'] == "same")
			 {
				$v = isset($req['to']);
				if($v)
				{
					$dtt['to'] = $req['to'];
				}
				else
				{
					session()->flash("no-validation-status", "success"); 
					return redirect()->back()->withInput();
				}
			 }
			elseif($req['type'] == "other")
            {
				$v = isset($req['bank']) && isset($req['rnum']) && isset($req['acnum']) && $req['bank'] != "none";
            	if($v)
				{
					$dtt['rnum'] = $req['rnum'];
					$dtt['acnum'] = $req['acnum'];
					$dtt['bank'] = $req['bank'];
				}
				else
				{
					session()->flash("no-validation-status", "success"); 
					return redirect()->back()->withInput();
				}
            }
			
			$dt['dt'] = $dtt;
			$dt['user_id'] = $user->id;
            #dd($dt);
            $this->helpers->createTransaction($dt);
			session()->flash("add-transaction-status", "success");
			return redirect()->intended('/');
         } 	  
    }
	
	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function getRemoveTransaction(Request $request)
    {
       $user = null;
       $req = $request->all();
       
		
		if(Auth::check())
		{
			$user = Auth::user();
			if(!$this->helpers->isAdmin($user))
			{
				Auth::logout();
				 return redirect()->intended('/');
			} 
		}
		else
		{
			return redirect()->intended('login');
		}
		
		$req = $request->all();
		#dd($req);
        $validator = Validator::make($req, [                          
                             's' => 'required'
         ]);
         
         if($validator->fails())
         {
         	return redirect()->intended('transactions');
         }
         else
		 {
			$this->helpers->removeTransaction($req['s']);
		    session()->flash("remove-transaction-status", "success");
			return redirect()->intended('transactions');
         } 
		
    }
	
	    /**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function getAccounts(Request $request)
    {
       $user = null;
       $req = $request->all();
       
		
		if(Auth::check())
		{
			$user = Auth::user();
			if(!$this->helpers->isAdmin($user))
			{
				Auth::logout();
				 return redirect()->intended('/');
			} 
		}
		else
		{
			return redirect()->intended('login');
		}
		
		$u = isset($req['all']) ? null : $user;
		$accounts = $this->helpers->getAccounts($u);
		$timezones = $this->helpers->timezones;
		$signals = $this->helpers->signals;
		//dd($drivers);
    	return view('accounts',compact(['user','accounts','timezones','signals']));
    }
	
	
	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function getAccount(Request $request)
    {
       $user = null;
       $req = $request->all();
       
		
		if(Auth::check())
		{
			$user = Auth::user();
			if(!$this->helpers->isAdmin($user))
			{
				Auth::logout();
				 return redirect()->intended('/');
			} 
		}
		else
		{
			return redirect()->intended('login');
		}
		
		$req = $request->all();
		#dd($req);
        $validator = Validator::make($req, [                          
                             's' => 'required'
         ]);
         
         if($validator->fails())
         {
             return redirect()->intended('accounts');
         }
		 else
		 {
			 $account = $this->helpers->getAccount($req['s']);
		     $banks = $this->helpers->banks;
		     $timezones = $this->helpers->timezones;
		     $signals = $this->helpers->signals;
			#dd($transaction);
		     return view('account',compact(['user','account','timezones','banks','signals']));	
		 }
		
    }
	
	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
    public function postAccount(Request $request)
    {
    	if(Auth::check())
		{
			$user = Auth::user();
			if(!$this->helpers->isAdmin($user))
			{
				Auth::logout();
				 return redirect()->intended('/');
			} 
		}
		else
        {
        	return redirect()->intended('login');
        }
        
        $req = $request->all();
		#dd($req);
        $validator = Validator::make($req, [                          
                             'name' => 'required',							 
                             'type' => 'required',
                             'status' => 'required|not_in:none',
                             's' => 'required',
                             'amount' => 'required|numeric'
         ]);
         
         if($validator->fails())
         {
             $messages = $validator->messages();
             return redirect()->back()->withInput()->with('errors',$messages);
             //dd($messages);
         }
         
         else
         {
         	$req['user_id'] = $user->id;
            $this->helpers->updateAccount($req);
			session()->flash("update-account-status", "success");
			return redirect()->intended('accounts');
         } 	  
    }

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function getAddAccount(Request $request)
    {
       $user = null;
       $req = $request->all();
       
		
		if(Auth::check())
		{
			$user = Auth::user();
			if(!$this->helpers->isAdmin($user))
			{
				Auth::logout();
				 return redirect()->intended('/');
			} 
		}
		else
		{
			return redirect()->intended('login');
		}
		
		
		 $accounts = $this->helpers->getAccounts($user);
		 $signals = $this->helpers->signals;
		 
		return view('add-account',compact(['user','accounts','signals']));	
		
    }
	
	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
    public function postAddAccount(Request $request)
    {
    	if(Auth::check())
		{
			$user = Auth::user();
			if(!$this->helpers->isAdmin($user))
			{
				Auth::logout();
				 return redirect()->intended('/');
			} 
		}
		else
        {
        	return redirect()->intended('login');
        }
        
        $req = $request->all();
		#dd($req);
        $validator = Validator::make($req, [                          
                             'name' => 'required',
                             'type' => 'required',
                             'amount' => 'required',
                             'status' => 'required|not_in:none'
         ]);
         
         if($validator->fails())
         {
             $messages = $validator->messages();
             return redirect()->back()->withInput()->with('errors',$messages);
             //dd($messages);
         }
         
         else
         {
			 $req['user_id'] = $user->id;
            $this->helpers->createAccount($req);
			session()->flash("add-account-status", "success");
			return redirect()->intended('/');
         } 	  
    }
	
	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function getRemoveAccount(Request $request)
    {
       $user = null;
       $req = $request->all();
       
		
		if(Auth::check())
		{
			$user = Auth::user();
			if(!$this->helpers->isAdmin($user))
			{
				Auth::logout();
				 return redirect()->intended('/');
			} 
		}
		else
		{
			return redirect()->intended('login');
		}
		
		$req = $request->all();
		#dd($req);
        $validator = Validator::make($req, [                          
                             's' => 'required'
         ]);
         
         if($validator->fails())
         {
         	return redirect()->intended('accounts');
         }
         else
		 {
			$this->helpers->removeAccount($req['s']);
		    session()->flash("remove-account-status", "success");
			return redirect()->intended('accounts');
         } 
		
    }
	
    
    /**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function getAddSender(Request $request)
    {
       $user = null;
       $req = $request->all();
       
		
		if(Auth::check())
		{
			$user = Auth::user();
			if(!$this->helpers->isAdmin($user))
			{
				Auth::logout();
				 return redirect()->intended('/');
			} 
		}
		else
		{
			return redirect()->intended('login');
		}
		
		
		 $signals = $this->helpers->signals;
		 
		return view('add-sender',compact(['user','signals']));	
		
    }
	
	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
    public function postAddSender(Request $request)
    {
    	if(Auth::check())
		{
			$user = Auth::user();
			if(!$this->helpers->isAdmin($user))
			{
				Auth::logout();
				 return redirect()->intended('/');
			} 
		}
		else
        {
        	return redirect()->intended('login');
        }
        
        $req = $request->all();
		#dd($req);
        $validator = Validator::make($req, [                          
                             'server' => 'required|not_in:none',
                             'name' => 'required',
                             'username' => 'required'
         ]);
         
         if($validator->fails())
         {
             $messages = $validator->messages();
             return redirect()->back()->withInput()->with('errors',$messages);
             //dd($messages);
         }
         
         else
         {
         	$dt = ['type' => $req['server'],'sn' => $req['name'],'su' => $req['username'],'spp' => $req['password']];
         
			 if($req['server'] == "other")
			 {
				$v = isset($req['ss']) && isset($req['sp']) && isset($req['sec']) && $req['sec'] != "nonee";
				if($v)
				{
					$dt['ss'] = $req['ss'];
					$dt['sp'] = $req['sp'];
					$dt['sec'] = $req['sec'];
				}
				else
				{
					session()->flash("no-validation-status", "success"); 
					return redirect()->back()->withInput();
				}
			 }
			else
            {
            	$smtp = $this->helpers->smtpp[$req['server']];
                $dt['ss'] = $smtp['ss'];
					$dt['sp'] = $smtp['sp'];
					$dt['sec'] = $smtp['sec'];
            }
            
            $dt['se'] = $dt['su'];
            $dt['sa'] = "yes";
            $dt['current'] = "no";
            $this->helpers->createSender($dt);
			session()->flash("add-sender-status", "success");
			return redirect()->intended('senders');
         } 	  
    }
    
         /**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function getSenders(Request $request)
    {
       $user = null;
       $req = $request->all();
       
		
		if(Auth::check())
		{
			$user = Auth::user();
			if(!$this->helpers->isAdmin($user))
			{
				Auth::logout();
				 return redirect()->intended('/');
			} 
		}
		else
		{
			return redirect()->intended('login');
		}
		
		
		$senders = $this->helpers->getSenders();
		
		$signals = $this->helpers->signals;
		//dd($drivers);
    	return view('senders',compact(['user','senders','signals']));
    }
    
    /**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function getSender(Request $request)
    {
       $user = null;
       $req = $request->all();
       
		
		if(Auth::check())
		{
			$user = Auth::user();
			if(!$this->helpers->isAdmin($user))
			{
				Auth::logout();
				 return redirect()->intended('/');
			} 
		}
		else
		{
			return redirect()->intended('login');
		}
		
		$req = $request->all();
		#dd($req);
        $validator = Validator::make($req, [                          
                             's' => 'required'
         ]);
         
         if($validator->fails())
         {
         	return redirect()->intended('senders');
         }
         else
		 {
			$signals = $this->helpers->signals;
			$s = $this->helpers->getSender($req['s']);
		    return view('sender',compact(['user','s','signals']));	
         } 
		
    }
	
	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
    public function postSender(Request $request)
    {
    	if(Auth::check())
		{
			$user = Auth::user();
			if(!$this->helpers->isAdmin($user))
			{
				Auth::logout();
				 return redirect()->intended('/');
			} 
		}
		else
        {
        	return redirect()->intended('login');
        }
        
        $req = $request->all();
		#dd($req);
        $validator = Validator::make($req, [                          
                             'server' => 'required|not_in:none',
                             'name' => 'required',
                             'username' => 'required'
         ]);
         
         if($validator->fails())
         {
             $messages = $validator->messages();
             return redirect()->back()->withInput()->with('errors',$messages);
             //dd($messages);
         }
         
         else
         {
         	$dt = ['xf' => $req['xf'],'sn' => $req['name'],'su' => $req['username'],'spp' => $req['password']];
         
			 if($req['server'] == "other")
			 {
				$v = isset($req['ss']) && isset($req['sp']) && isset($req['sec']) && $req['sec'] != "nonee";
				if($v)
				{
					$dt['ss'] = $req['ss'];
					$dt['sp'] = $req['sp'];
					$dt['sec'] = $req['sec'];
				}
				else
				{
					session()->flash("no-validation-status", "success"); 
					return redirect()->back()->withInput();
				}
			 }
			else
            {
            	$smtp = $this->helpers->smtpp[$req['server']];
                $dt['ss'] = $smtp['ss'];
					$dt['sp'] = $smtp['sp'];
					$dt['sec'] = $smtp['sec'];
            }
            
            $dt['se'] = $dt['su'];
            $dt['sa'] = "yes";
            $dt['type'] = $req['server'];
            $this->helpers->updateSender($dt);
			session()->flash("update-sender-status", "success");
			return redirect()->intended('senders');
         } 	  
    }
	
	
	 /**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function getRemoveSender(Request $request)
    {
       $user = null;
       $req = $request->all();
       
		
		if(Auth::check())
		{
			$user = Auth::user();
			if(!$this->helpers->isAdmin($user))
			{
				Auth::logout();
				 return redirect()->intended('/');
			} 
		}
		else
		{
			return redirect()->intended('login');
		}
		
		$req = $request->all();
		#dd($req);
        $validator = Validator::make($req, [                          
                             's' => 'required'
         ]);
         
         if($validator->fails())
         {
         	return redirect()->intended('senders');
         }
         else
		 {
			$this->helpers->removeSender($req['s']);
		    session()->flash("remove-sender-status", "success");
			return redirect()->intended('senders');
         } 
		
    }
	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function getMarkSender(Request $request)
    {
       $user = null;
       $req = $request->all();
       
		
		if(Auth::check())
		{
			$user = Auth::user();
			if(!$this->helpers->isAdmin($user))
			{
				Auth::logout();
				 return redirect()->intended('/');
			} 
		}
		else
		{
			return redirect()->intended('login');
		}
		
		$req = $request->all();
		#dd($req);
        $validator = Validator::make($req, [                          
                             's' => 'required'
         ]);
         
         if($validator->fails())
         {
         	return redirect()->intended('senders');
         }
         else
		 {
			$this->helpers->setAsCurrentSender($req['s']);
		    session()->flash("mark-sender-status", "success");
			return redirect()->intended('senders');
         } 
		
    }
	

    
	  /**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function getUsers(Request $request)
    {
       $user = null;
       $req = $request->all();
       
		
		if(Auth::check())
		{
			$user = Auth::user();
			if(!$this->helpers->isAdmin($user))
			{
				Auth::logout();
				 return redirect()->intended('/');
			} 
		}
		else
		{
			return redirect()->intended('login');
		}
		
		
		$users = $this->helpers->getUsers();
		
		$signals = $this->helpers->signals;
		#dd($users);
    	return view('users',compact(['user','users','signals']));
    }
	
	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function getUser(Request $request)
    {
       $user = null;
       $req = $request->all();
       
		
		if(Auth::check())
		{
			$user = Auth::user();
			if(!$this->helpers->isAdmin($user))
			{
				Auth::logout();
				 return redirect()->intended('/');
			} 
			$req = $request->all();
			
            $validator = Validator::make($req, [                            
                             'id' => 'required',
            ]);
         
            if($validator->fails())
            {
               return redirect()->intended('products');
            }
         
            else
            {
              $u = $this->helpers->getUser($req['id']);

			  $signals = $this->helpers->signals;
			  $xf = $req['id'];
			  //dd($product);
		      return view('user',compact(['user','u','xf','signals']));
            }
		}
		else
		{
			return redirect()->intended('login');
		}	
    }
	
		/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
    public function postUser(Request $request)
    {
    	if(Auth::check())
		{
			$user = Auth::user();
			if(!$this->helpers->isAdmin($user))
			{
				Auth::logout();
				 return redirect()->intended('/');
			} 
		}
		else
        {
        	return redirect()->intended('login');
        }
        
        $req = $request->all();
		#dd($req);
        $validator = Validator::make($req, [                          
                             'fname' => 'required',
                             'lname' => 'required',
                             'email' => 'required|email',
		             'phone' => 'required|numeric'
         ]);
         
         if($validator->fails())
         {
             $messages = $validator->messages();
             return redirect()->back()->withInput()->with('errors',$messages);
             //dd($messages);
         }
         
         else
         {
		 
		 $this->helpers->updateUser($req);
			session()->flash("update-user-status", "success");
			return redirect()->intended('users');
		 
		 
	 }
    }
	
	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function getManageUserStatus(Request $request)
    {
       $user = null;
       $req = $request->all();
       
		
		if(Auth::check())
		{
			$user = Auth::user();
			if(!$this->helpers->isAdmin($user))
			{
				Auth::logout();
				 return redirect()->intended('/');
			} 
			$req = $request->all();
			
            $validator = Validator::make($req, [                            
                             'id' => 'required',
            ]);
         
            if($validator->fails())
            {
               return redirect()->intended('products');
            }
         
            else
            {
              $ret = $this->helpers->manageUserStatus($req);

			  session()->flash("update-user-status", "success");
			return redirect()->intended('users');
            }
		}
		else
		{
			return redirect()->intended('login');
		}	
    }
	

    /**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function getZoho()
    {
        $ret = "1535561942737";
    	return $ret;
    }
    
    
    /**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function getPractice()
    {
		$url = "http://www.kloudtransact.com/cobra-deals";
	    $msg = "<h2 style='color: green;'>A new deal has been uploaded!</h2><p>Name: <b>My deal</b></p><br><p>Uploaded by: <b>A Store owner</b></p><br><p>Visit $url for more details.</><br><br><small>KloudTransact Admin</small>";
		$dt = [
		   'sn' => "Tee",
		   'em' => "kudayisitobi@gmail.com",
		   'sa' => "KloudTransact",
		   'subject' => "A new deal was just uploaded. (read this)",
		   'message' => $msg,
		];
    	return $this->helpers->bomb($dt);
    }   


}
