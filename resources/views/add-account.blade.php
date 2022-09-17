@extends('layout')

@section('title',"Add Account")

@section('content')
			<div class="col-md-12">
			<form method="post" action="{{url('add-account')}}" id="add-sender-form" enctype="multipart/form-data">
				{!! csrf_field() !!}
				<input type="hidden" id="spp-s" value="show">
                <div class="block">
                    <div class="header">
                        <h2>Add new account</h2>
                    </div>
                    <div class="content controls">
                             <div class="form-row" style="margin-bottom: 5px;">
							   <div class="col-md-3">Account name:</div>
							   <div class="col-md-9">
							      <input type="text" class="form-control" name="name" id="ac-acname" placeholder="Account name"/>
								 </div>
								</div>
								<div class="form-row" style="margin-bottom: 5px;">
							   <div class="col-md-3">Account type:</div>
							   <div class="col-md-9">
							      <input type="text" class="form-control" name="type" id="ac-actype" placeholder="Account type"/>
								 </div>
								</div>
								
							  
							  <div class="form-row">
							   <div class="col-md-3">Opening Amount($):</div>
							   <div class="col-md-9">
							      <input type="number" class="form-control" name="amount" id="at-amount" placeholder="Enter amount"/>
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
								    ?>
								    <option value="{{$key}}">{{$value}}</option>
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
							    <button type="submit" id="at-submit" class="btn btn-default btn-block btn-clean">Submit</button>
							  </center>
							</div>
                            <div class="col-md-4"></div>							
                        </div>
                                              
                    </div>
                </div>  
            </form>				
            </div>
@stop