@include('common/header')
@include('common/navbar')

<div class="slim-mainpanel">
      <div class="container">
        <div class="slim-pageheader">
          <ol class="breadcrumb slim-breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <!--<li class="breadcrumb-item"><a href="#">Pages</a></li>-->
            <li class="breadcrumb-item active" aria-current="page">Profile Page</li>
          </ol>
          <h6 class="slim-pagetitle">Edit Profile</h6>
        </div><!-- slim-pageheader -->

        <div class="row row-sm mg-t-20">
        
        <div class="col-lg-6">
        <div class="section-wrapper">
          <form action="/profile/{{Auth::user()->id}}" method="POST" enctype="multipart/form-data">
           @csrf
           @method('PATCH')
              <label class="section-title">Edit Profile Info : </label>
              <p class="mg-b-20 mg-sm-b-40"></p>

              <div class="form-layout form-layout-4">
                <div class="row">
                  <label class="col-sm-4 form-control-label">Name: <span class="tx-danger">*</span></label>
                  <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                    <input type="text" name="name" class="form-control" placeholder="Enter Name" value="{{Auth::user()->name}}" required>
                  </div>
                </div><!-- row -->
                <div class="row mg-t-20">
                  <label class="col-sm-4 form-control-label">Email: <span class="tx-danger">*</span></label>
                  <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                    <input type="email" name="email" class="form-control" placeholder="Enter email address" value="{{Auth::user()->email}}" required>
                  </div>
                </div>
                <!--<div class="row mg-t-20">
                  <label class="col-sm-4 form-control-label">Password: <span class="tx-danger"></span></label>
                  <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                    <input type="password" name="password" class="form-control" placeholder="Enter password" >
                  </div>
                </div>
                <div class="row mg-t-20">
                  <label class="col-sm-4 form-control-label">Password Confirmation: <span class="tx-danger"></span></label>
                  <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" >
                  </div>
                </div>-->
               <div class="row mg-t-20">
                  <label class="col-sm-4 form-control-label">Profile Image <span class="tx-danger"></span></label>
                  <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                    <input type="file" name="avatar" class="form-control" >
                  </div>
                </div>
                <div class="row mg-t-20">
                  
                  <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                  <img style="object-fit: cover;" width="250px" height="200px" src="/storage/{{Auth::user()->avatar}}" alt="profile-avatar">
                  </div>
                </div>
                <!--<div class="row mg-t-20">
                  <label class="col-sm-4 form-control-label">Address: <span class="tx-danger">*</span></label>
                  <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                    <textarea rows="2" class="form-control" placeholder="Enter your address"></textarea>
                  </div>
                </div>-->
                <div class="form-layout-footer mg-t-30">
                  <input type="submit" name="submit" value="Submit Form" class="btn btn-primary bd-0">
                  <!--<input type="reset"  name="reset" value="Reset" class="btn btn-secondary bd-0">-->
                </div><!-- form-layout-footer -->
              </div><!-- form-layout -->
              </form>
            </div>

        </div>



        </div><!-- row -->

      </div><!-- container -->
    </div><!-- slim-mainpanel -->

@include('common/footer')