@extends('layout')

@section('title',"Edit Account")

@section('content')
			<div class="col-md-12">
			<form method="post" action="{{url('account')}}" id="add-sender-form" enctype="multipart/form-data">
				{!! csrf_field() !!}
				<input type="hidden" id="spp-s" value="show">
				<input type="hidden" name="s" value="{{$account['id']}}">
                <div class="block">
                    <div class="header">
                        <h2>Add new account</h2>
                    </div>
                    <div class="content controls">
					<?php
					   $name = $account['name'];
					   $type = $account['type'];
					   $amount = $account['amount'];
					   $status = $account['status'];
					   $date = $account['date'];
					   $tz = $account['tz'];
					  ?>
                             <div class="form-row" style="margin-bottom: 5px;">
							   <div class="col-md-3">Account name:</div>
							   <div class="col-md-9">
							      <input type="text" class="form-control" name="name" id="ac-acname" value="{{$name}}" placeholder="Account name"/>
								 </div>
								</div> 
								
								<div class="col-md-3">Account type:</div>
							   <div class="col-md-9">
							      <input type="text" class="form-control" name="type" id="ac-actype" value="{{$type}}" placeholder="Account type"/>
								 </div>
								</div>
								
							  
							  <div class="form-row">
							   <div class="col-md-3">Opening Amount($):</div>
							   <div class="col-md-9">
							      <input type="number" class="form-control" name="amount" id="at-amount" value="{{$amount}}" placeholder="Enter amount"/>
								 </div>
								</div>
								<div class="form-row">
							   <div class="col-md-3">Date:</div>
							   <div class="col-md-9">
							      <input type="date" class="form-control" name="date" value="{{$date->format('yy-m-d')}}" id="at-date" placeholder="Enter date"/>
								 </div>
								</div>
								     <?php
								 $statuses = ['active' => "Active",
								              'dormant' => "Dormant"
								              
											 ];
								?>
								
								<div class="form-row">
							   <div class="col-md-3">Status:</div>
							   <div class="col-md-9">
							     <select class="form-control" name="status" id="at-status" style="margin-bottom: 5px;">
							        <option value="none">Select status</option>
								    <?php
								     foreach($statuses as $key => $value){
										 $ss = $status == $key ? " selected='selected'" : "";
								    ?>
								    <option value="{{$key}}"{{$ss}}>{{$value}}</option>
								    <?php
								    }
								    ?>
							      </select>
								 </div>
								</div>
							
						
                        
						<div class="form-row">
                            <div class="col-md-4"></div>
                            <div class="col-md-4">
							  <center>
							  <a href="{{url('accounts')}}" class="btn btn-default btn-clean">Back</a>
							    <button type="submit" id="at-submit" class="btn btn-default btn-clean">Submit</button>
							  </center>
							</div>
                            <div class="col-md-4"></div>							
                        </div>
                                              
                    </div>
                </div>  
            </form>				
            </div>
@stop