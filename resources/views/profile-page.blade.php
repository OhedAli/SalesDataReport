@include('common/header')
@include('common/navbar')

<div class="slim-mainpanel">
      <div class="container">
        <div class="slim-pageheader">
         
          <h6 class="slim-pagetitle">My Profile</h6>
           <ol class="breadcrumb slim-breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <!--<li class="breadcrumb-item"><a href="#">Pages</a></li>-->
            <li class="breadcrumb-item active" aria-current="page">Profile Page</li>
          </ol>
        </div><!-- slim-pageheader -->

        <div class="row row-sm">
          <div class="col-lg-8">
            <div class="card card-profile">
              <div class="card-body">
                <div class="media">
                  <img src="{{asset('public/images/uploads/avatars/'.Auth::user()->avatar) }}" alt="">
                  <div class="media-body">
                    <h3 class="card-profile-name">{{ Auth::user()->name}}</h3>
                    <p>San Francisco, California</p>

                    <p class="mg-b-0">
                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                    </p>


                  </div><!-- media-body -->
                </div><!-- media -->
              </div><!-- card-body -->
              
              <?php /* ?><div class="card-footer">
                <a href="" class="card-profile-direct">http://thmpxls.me/profile?id=katherine</a>
                <div>
                  <a href="">Edit Profile</a>
                  <!--<a href="">Profile Settings</a>-->
                </div>
              </div><?php */ ?><!-- card-footer -->
            </div><!-- card -->

            <!--<ul class="nav nav-activity-profile mg-t-20">
              <li class="nav-item"><a href="" class="nav-link"><i class="icon ion-ios-redo tx-purple"></i> Share an update</a></li>
              <li class="nav-item"><a href="" class="nav-link"><i class="icon ion-image tx-primary"></i> Publish photo</a></li>
              <li class="nav-item"><a href="" class="nav-link"><i class="icon ion-document-text tx-success"></i> Add an article</a></li>
            </ul>--><!-- nav -->


            <!--<div class="card card-experience mg-t-20">
              <div class="card-body">
                <div class="slim-card-title">Work Experience</div>
                <div class="media">
                  <div class="experience-logo">
                    <i class="icon ion-briefcase"></i>
                  </div>
                  <div class="media-body">
                    <h6 class="position-name">Front-End Engineer / Web Developer</h6>
                    <p class="position-company">Cebu Machine Intelligence, Inc.</p>
                    <p class="position-year">Nov 2012 - Present (5 years +) &nbsp;-&nbsp; <a href="">Edit</a></p>
                  </div>
                </div>
              </div>
              <div class="card-footer">
                <a href="">Show more<span class="d-none d-sm-inline"> experiences (4)</span> <i class="fa fa-angle-down"></i></a>
                <a href="">Add new</a>
              </div>
            </div>--><!-- card -->

        
          </div><!-- col-8 -->

          <div class="col-lg-4 mg-t-20 mg-lg-t-0">

            <!--<div class="card card-connection">
              <div class="row row-xs">
                <div class="col-4 tx-primary">129</div>
                <div class="col-8">people viewed your profile in the past 90 days</div>
              </div>
              <hr>
              <div class="row row-xs">
                <div class="col-4 tx-purple">845</div>
                <div class="col-8">
                  connections <br>
                  <a href="">Grow your network</a>
                </div>
              </div>
            </div>--><!-- card -->



            <div class="card pd-25">
              <div class="slim-card-title">Contact &amp; Personal Info</div>

              <div class="media-list mg-t-25">
            
                <div class="media mt-1">
                  <div><i class="icon ion-ios-telephone-outline tx-24 lh-0"></i></div>
                  <div class="media-body mg-l-15 mg-t-4">
                    <h6 class="tx-14 tx-gray-700">Phone Number</h6>
                    <span class="d-block">+1 234 5678 910</span>
                  </div><!-- media-body -->
                </div><!-- media -->
                <div class="media mg-t-25">
                  <div><i class="icon ion-ios-email-outline tx-24 lh-0"></i></div>
                  <div class="media-body mg-l-15 mg-t-4">
                    <h6 class="tx-14 tx-gray-700">Email Address</h6>
                    <span class="d-block">{{ Auth::user()->email}}</span>
                  </div><!-- media-body -->
                </div><!-- media -->
            
              </div><!-- media-list -->
            </div><!-- card -->
          </div><!-- col-4 -->
        </div><!-- row -->

      </div><!-- container -->
    </div><!-- slim-mainpanel -->

@include('common/footer')