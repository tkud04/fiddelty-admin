@extends('layout')

@section('title',"Add Transaction")

@section('content')
			<div class="col-md-12">
			<form method="post" action="{{url('add-transaction')}}" id="add-sender-form" enctype="multipart/form-data">
				{!! csrf_field() !!}
				<input type="hidden" id="spp-s" value="show">
                <div class="block">
                    <div class="header">
                        <h2>Add new transaction</h2>
                    </div>
                    <div class="content controls">

						<div class="form-row">
                            <div class="col-md-3">From account:</div>
                            <div class="col-md-9">
							  <select class="form-control" id="at-from" name="from">
							    <option value="none">Select from account</option>
								<?php
								foreach($accounts as $a){
								//$ss = $product['status'] == $key ? " selected='selected'" : "";
								?>
								 <option value="{{$a['id']}}">{{$a['name']." - $".number_format($a['amount'],2)}}</option>
								<?php
								}
								?>
							  </select>
							</div>
                        </div>
						<div class="form-row" id="type-form-row">
                            <div class="col-md-3">Transaction type:</div>
                            <div class="col-md-9">
							  <select class="form-control" id="at-type" name="type">
							    <option value="none">Select transaction type</option>
								<?php
								 $types = ['same' => "To own account",'other' => "To other account"];
								foreach($types as $key => $value){
								//$ss = $product['status'] == $key ? " selected='selected'" : "";
								?>
								 <option value="{{$key}}">{{$value}}</option>
								<?php
								}
								?>
							  </select>
							</div>
                        </div>
                        <div id="at-same">
						<div class="form-row">
                                <div class="col-md-3">To account:</div>
								<div class="col-md-9">
							      <select class="form-control" name="to" id="at-to" style="margin-bottom: 5px;">
							        <option value="none">Select to account</option>
								    <?php
								     foreach($accounts as $a){
								    ?>
								    <option value="{{$a['id']}}">{{$a['name']." - $".number_format($a['amount'],2)}}</option>
								    <?php
								    }
								    ?>
							      </select>
								 </div>
								</div>
							  </div>
							  
							  <div id="at-other">
						<div class="form-row">
                                <div class="col-md-3">Destination bank:</div>
								<div class="col-md-9">
							      <select class="form-control" name="bank" id="at-bank" style="margin-bottom: 5px;">
							        <option value="none">Select bank</option>
								    <?php
								     foreach($banks as $key => $value){
								    ?>
								    <option value="{{$key}}">{{$value}}</option>
								    <?php
								    }
								    ?>
							      </select>
								 </div>
								</div>
						<div class="form-row" style="margin-bottom: 5px;">
							   <div class="col-md-3">Account number:</div>
							   <div class="col-md-9">
							      <input type="number" class="form-control" name="acnum" id="at-acnum" placeholder="Account number"/>
								 </div>
								</div>
								<div class="form-row" style="margin-bottom: 5px;">
							   <div class="col-md-3">Routing number:</div>
							   <div class="col-md-9">
							      <input type="number" class="form-control" name="rnum" id="at-rnum" placeholder="Routing number"/>
								 </div>
								</div>
							  </div>
							  
							  <div class="form-row">
							   <div class="col-md-3">Amount($):</div>
							   <div class="col-md-9">
							      <input type="number" class="form-control" name="amount" id="at-amount" placeholder="Enter amount"/>
								 </div>
								</div>
								   <?php
								 $statuses = ['pending' => "Pending",
								              'sent' => "Sent",
								              'received' => "Received",
								              'failed' => "Failed",
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