@extends('layout')

@section('title',"Accounts")

@section('content')
			<div class="col-md-12">
				{!! csrf_field() !!}
                <div class="block">
                    <div class="header">
                        <h2>Your accounts list</h2>
                        <a class="pull-right btn btn-clean" href="{{url('add-account')}}">Add</a>
                    </div>
                    <div class="content">
                       <div id="DataTables_Table_2_wrapper" class="dataTables_wrapper" role="grid">
					     
                        <table cellpadding="0" cellspacing="0" width="100%" class="table table-bordered table-striped sortable">
                            <thead>
                                <tr>                                  
                                    <th>User</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Status</th>
									<th>Date</th>
                                    
                                    
                                </tr>
                            </thead>
                            <tbody>
							  <?php
							  
					  if(count($accounts) > 0)
					  {
						 foreach($accounts as $a)
						 {
							 $u = $a['user'];
							$name = $a['name'];
							$amt = $a['amount'];
							$type = $a['type'];
							$status = $a['status'];
							$tz = $a['tz'];
							$ttz = is_null($tz) || $tz == "" ? "Unspecified" : $timezones[$tz];
							$eu = url('account')."?s=".$a['id'];
							$ru = url('remove-account')."?s=".$a['id'];
							 
							
				    ?>
                      <tr>
					   
					   <td>{{ $u['fname']." ".$u['lname'] }}</td>
					  <td>
					   <em>{{$name}}</em>
					  </td><td>
					   <em>{{$type}}</em>
					  </td>
					  <td>${{number_format($amt,2)}}</td>
					   
					   <td><b>{{strtoupper($status)}}</b></td>
					    <td>{{$a['date']->format("jS F, Y")}}</td>
					   <td>
					   <a class="btn btn-default btn-block btn-clean" href="{{$eu}}">Edit</a>
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