@extends('admin.layouts.backend_layout')
<!--header nav-->
 

@section('content')


<div class="col-sm-8 col-sm-offset-2">
	<div class="col-sm-12 table-header">
		<div class="row">
			<div class="col-sm-6">
				<h2 style="color:#fff;">Template</h2>
			</div>
			<div class="col-sm-6 text-right pd-btn">
			<a href="{{url('templates/create')}}"><button type="button" class="btn btn-primary">Create Template</button></a>
			</div>
		</div>
	</div>

<div class="span7">   
<div class="widget stacked widget-table action-table">
    				
   </div>

				
				 <!-- /widget-header -->
				
				<div class="widget-content">
					<form>
					 <div class="">
					  <table class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>Template</th>
								<th>Descriptions</th>
								<th>Companies</th>
								<th>action</th>								
							</tr>
						</thead>
						<tbody>	
						@foreach($template as $template_val)
							<tr>  							<td>{{$template_val->temp_name}}</td>
								<td>{{$template_val->temp_description}}</td>
								<td class="td-actions">
								</td>
								<td>
									<a href="{{url('/templates/'.$template_val->id.'/edit')}}" class="btn btn-default"> Edit</a>
									<a href="delete_templates/{{ $template_val->id }}" class="btn btn-danger">Delete</a>
								</td>
							</tr>	
							@endforeach
							</tbody>
						</table>
						</div>
					</form>
				</div> 
			
			</div> 
            </div>
    @endsection