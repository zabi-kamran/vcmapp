@extends('layout.app')
@section('title','Home')
@section('content')
 <!-- Main content -->
            <div class="content-wrapper">
                    <div class="breadcrumb-line">
                        <ul class="breadcrumb">
                            <li><a href="{{ url('home') }}"><i class="icon-home2 position-left"></i> Home</a></li>
                        </ul>

                        <ul class="breadcrumb-elements">
                        </ul>
                    </div>
                <!-- /page header -->


                <!-- Content area -->
                <div class="content">

                    <!-- Basic tables title -->
                    <h6 class="content-group text-semibold">
                   
                       
                    </h6>
                    <!-- /basic tables title -->


                    <!-- Basic table -->
                    <div class="panel panel-flat">
                        <div class="panel-heading">
                            <h5 class="panel-title">Here you can send text message to VCM</h5><br>
                            <div class="heading-elements">
                              <br><Br>
								
                            </div>
							<div class="form-group">
    <label for="exampleInputEmail1">Ward or LGA</label>
  
<select class="form-control">
<option>Select</option>
  <option>Giva</option>
  <option>Zaria</option>
</select>
  </div>
  <div class="form-group">
    <label for="exampleFormControlTextarea1">Type your text message here</label>
    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
  </div>
  <div class="form-group">
    <label for="exampleFormControlFile1">Add picture</label>
    <input type="file" class="form-control-file" id="exampleFormControlFile1">
  </div>
  <button type="submit" class="btn btn-primary">Send</button>
</form>
                        </div>
                        </div>
                    </div>
                    <!-- /basic table -->
                    
                </div>
                <!-- /content area -->

            </div>
            <!-- /main content -->
@endsection