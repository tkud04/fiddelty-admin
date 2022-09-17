@extends('layout')

@section('title',"Transactions")

@section('content')
			<div class="col-md-12">
				{!! csrf_field() !!}
                <div class="block">
                    <div class="header">
                        <h2>Your transactions list</h2>
                        <a class="pull-right btn btn-clean" href="{{url('add-transaction')}}">Add</a>
                    </div>
                    <div class="content">
                       <div id="DataTables_Table_2_wrapper" class="dataTables_wrapper" role="grid">
					     
                        <table cellpadding="0" cellspacing="0" width="100%" class="table table-bordered table-striped sortable">
                            <thead>
                                <tr>                                  
                                    <th>User</th>
                                    <th>Type</th>
                                    <th>From Account</th>
                                    <th>Details</th>
									<th>Amount</th>
									<th>Date</th>
                                    
                                    
                                </tr>
                            </thead>
                            <tbody>
							  <?php
							  
					  if(count($transactions) > 0)
					  {
						 foreach($transactions as $t)
						 {
							 $u = $t['user'];
							$type = $t['type'];
							$dt = $t['dt'];
							$from = $t['from'];
							$status = $t['status'];
							$tt = $type == "same" ? "OWN ACCOUNT" : "OTHER BANK";
							if($type == "same")
							{
								$to = $dt['to'];
                               $details = "<p>Account: <em>".$to['name']." - $".number_format($to['amount'],2)."</em></p>";
                            }
							else if($type == "other")
							{
						    	$details = "<p>Bank name: <em>".$dt['bank']."</em></p><p>Account number: <em>".$dt['acnum']."</em></p><p>Routing number: <em>".$dt['rnum']."</em></p>";
							}
							$ru = url('remove-transaction')."?s=".$t['id'];
							 
							
				    ?>
                      <tr>
					   
					   <td>{{ $u['fname']." ".$u['lname'] }}</td>
					  <td><h3 class="label label-info">{{ $tt }}</h3></td>
					  <td>
					   <em>{{$from['name']." - $".number_format($from['amount'],2)}}</em>
					  </td>
					  <td>{!! $details !!}</td>
					   <td>${{number_format($t['amount'],2)}}</td>
					   <td><b>{{strtoupper($t['status'])}}</b></td>
					    <td>{{$t['date']}}</td>
					   <td>
						<a class="btn btn-default btn-block btn-clean" href="{{$ru}}">Remove</a>
                       </td>
					 
					 </tr>
					<?php
						 }  
					  }
                    ?>				               
                            </tbody>
                        </table>                                        

                    </div>
                </div>  
            </div>				
           </div>
@stop