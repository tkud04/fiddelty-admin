@extends('layout')

@section('title',"Dashboard")

@section('content')
   <?php
								 $statuses = ['pending' => "Pending",
								              'sent' => "Sent",
								              'received' => "Received",
								              'failed' => "Failed",
											 ];
								?>
            <div class="col-md-2">
                
                <div class="block block-drop-shadow">
                    <div class="user bg-default bg-light-rtl">
                        <div class="info">                                                                                
                            <a href="#" class="informer informer-three">
                                <span>{{$user->fname}}</span>
									{{$user->lname}}
                            </a>                            
                            <a href="#" class="informer informer-four">
                                <span>{{strtoupper($user->role)}}</span>
                                Role
                            </a>                                                        
                            <img src="img/icon.png" class="img-circle img-thumbnail"/>
                        </div>
                    </div>
                    <div class="content list-group list-group-icons">
                        <a href="{{url('logout')}}" class="list-group-item"><span class="icon-off"></span>Logout<i class="icon-angle-right pull-right"></i></a>
                    </div>
                </div> 
               
                
            </div>
            
            <div class="col-md-5">
               <div class="block block-drop-shadow">                    
                    <div class="head bg-dot20">
                        <h2>Transactions</h2>
                        <div class="side pull-right">               
                            <ul class="buttons">                                
                                <li><a href="#"><span class="icon-cogs"></span></a></li>
                            </ul>
                        </div>
                        <div class="head-subtitle">Total transactions</div>                        
                        <div class="head-panel nm tac" style="line-height: 0px;">
                            <div class="knob">
                                <input type="text" data-fgColor="#3F97FE" data-min="0" data-max="100000000" data-width="100" data-height="100" value="0" data-readOnly="true" style="font: bold 20px Arial !important;"/>
                            </div>                              
                        </div>
                        <div class="head-panel nm">
                            <div class="hp-info hp-simple pull-left">
                                <span class="hp-main">Today's transactions</span>
                                <span class="hp-sm">{{0}}</span>                                
                            </div>
                            <div class="hp-info hp-simple pull-right">
                                <span class="hp-main">Total transactions this month</span>
                                <span class="hp-sm">{{0}}</span>                                
                            </div>                            
                        </div>
						<div class="head-panel nm" style="padding-top: 5px">
                            <div class="hp-info hp-simple pull-left">
                                <span class="hp-main">Total transactions</span>
                                <span class="hp-sm">{{0}}</span>                                
                            </div>
                            <div class="hp-info hp-simple pull-right">
                                <span class="hp-main">Total failed transactions</span>
                                <span class="hp-sm">{{0}}</span>                                
                            </div>                            
                        </div>                        
                    </div>                    
                    
                </div>  

                       <div class="block block-drop-shadow">                    
                        <div class="head bg-dot20">
                        <h2>Transactions</h2>
                        
                        <div class="head-subtitle">Transactions recorded on the system</div>                        
                        
                        <div class="head-panel nm">
						<br>
						  <?php
						   $transactionsCount = count($transactions);
						   
						  if($transactionsCount < 1)
						   {
						  ?>	  
						  <h4>No transactions added yet.</h4>
						  <a href="{{url('add-transaction')}}" class="btn btn-default btn-block btn-clean" style="margin-top: 5px;">Add one now</a> 
					      <?php
						   }
						  else
						  {
						    $ct = "transaction";
						    
						   
						   if($transactionsCount > 1)
						   {
							   $ct = "transactions";
							
						   }
						  ?>
							<h4>{{$transactionsCount}} {{$ct}} added.</h4>
							@if(count($sender) > 0) 
							<h5>Sent: {{$tc['sent']}}</h5>
							<h6>Pending: {{$tc['pending']}} </h6>
							<h6>Failed: {{$tc['failed']}} </h6>
							@endif
                            <a href="{{url('transactions')}}" class="btn btn-default btn-block btn-clean" style="margin-top: 5px;">View {{$ct}}</a> 
						  <?php						
						  }
                          ?>               
                        </div>                        
                    </div>                    
                                       
                    
                </div>  				

            </div> 
			<div class="col-md-5">
               <div class="block block-drop-shadow">                    
                        <div class="head bg-dot20">
                        <h2>General Settings</h2>
                        
                        <div class="head-subtitle">General site settings</div>                        
                        
                        <div class="head-panel nm">
						<br>
						  <?php
						   $timezone = "Not set";
						   if(count($tz) > 0)
						   {
							 $timezone = $timezones[$tz['value']];							 
						   } 
						   else
						   {
							   $tz = ['value' => "4"];
						   }
						  ?>
                            <div id="tz-div" style="margin-bottom: 15px;">
                            <div id = "tz-div-side1">							
							  <h5>Current timezone: <span id="tz-main">{{$timezone}}</span> <a class="btn btn-default btn-clean" id="tz-change" href="javascript:void(0)">Change</a></h5>
							</div>
							<div id = "tz-div-side2">
						     <form id="tz-form">
						   	   <input type="hidden" id="tk-tz" value="{{csrf_token()}}">
						       <div class="form-group">
							     <span class="control-label">Select timezone</span>
							     <select class="form-control" id="tz-timezone">
								 <option value="none">Select a timezone</option>
								 <?php
								   foreach($timezones as $key => $value)
								   {
									   $ss = $key == $tz['value'] ? " selected='selected'" : "";
								 ?>
								  <option value="{{$key}}"{{$ss}}>{{$value}}</option>
								 <?php
								   }
								 ?>
								 </select>
							     <span class="label label-danger" id="tz-timezone-error">Select a timezone</span>
							   </div>
							   <a class="btn btn-default btn-clean" id="tz-back" href="javascript:void(0)">Back</a>
						       <button type="submit" class="btn btn-default btn-clean">Submit</button>
						       <h4 id="tz-loading">Updating timezone.. <img src="img/loading.gif" class="img img-fluid" alt="Loading" width="50" height="50"></h4><br>
						     </form>
                            </div>  
                           </div>            
                        </div>                        
                    </div>                    
                                       
                    
                </div>
				
				
				                <div class="block block-drop-shadow">                    
                        <div class="head bg-dot20">
                        <h2>SMTP Senders</h2>
                        
                        <div class="head-subtitle">SMTP details used by the system to send emails</div>                        
                        
                        <div class="head-panel nm">
						<br>
						  <?php
						   $sendersCount = count($senders);
						   
						  if($sendersCount < 1)
						   {
						  ?>	  
						  <h4>No senders added yet.</h4>
						  <a href="{{url('add-sender')}}" class="btn btn-default btn-block btn-clean" style="margin-top: 5px;">Add one now</a> 
					      <?php
						   }
						  else
						  {
						    $ct = "sender";
						    
						   
						   if($sendersCount > 1)
						   {
							   $ct = "senders";
							
						   }
						  ?>
							<h4>{{$sendersCount}} {{$ct}} added.</h4>
							@if(count($sender) > 0) 
							<h5>Current Sender: {{$sender['sn']}} ({{$sender['se']}}).</h5>
							<h6>Last updated: {{$sender['updated']}} </h6>
							@endif
                            <a href="{{url('senders')}}" class="btn btn-default btn-block btn-clean" style="margin-top: 5px;">View {{$ct}}</a> 
						  <?php						
						  }
                          ?>               
                        </div>                        
                    </div>                    
                                       
                    
                </div> 
				 
		</div>
@stop