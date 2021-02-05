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
          <h6 class="slim-pagetitle">My Profile</h6>
        </div><!-- slim-pageheader -->

        <div class="row row-sm">
          <div class="col-lg-8">
            <div class="card card-profile">
              <div class="card-body">
                <div class="media">
                  <img src="http://via.placeholder.com/500x500" alt="">
                  <div class="media-body">
                    <h3 class="card-profile-name">{{ Auth::user()->name}}</h3>
                    <p class="card-profile-position">Executive Director at <a href="">ThemePixels, Inc.</a></p>
                    <p>San Francisco, California</p>

                    <p class="mg-b-0">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. </p>


                  </div><!-- media-body -->
                </div><!-- media -->
              </div><!-- card-body -->
              <div class="card-footer">
                <a href="" class="card-profile-direct">http://thmpxls.me/profile?id=katherine</a>
                <div>
                  <a href="">Edit Profile</a>
                  <!--<a href="">Profile Settings</a>-->
                </div>
              </div><!-- card-footer -->
            </div><!-- card -->

            <!--<ul class="nav nav-activity-profile mg-t-20">
              <li class="nav-item"><a href="" class="nav-link"><i class="icon ion-ios-redo tx-purple"></i> Share an update</a></li>
              <li class="nav-item"><a href="" class="nav-link"><i class="icon ion-image tx-primary"></i> Publish photo</a></li>
              <li class="nav-item"><a href="" class="nav-link"><i class="icon ion-document-text tx-success"></i> Add an article</a></li>
            </ul>--><!-- nav -->


            <div class="card card-experience mg-t-20">
              <div class="card-body">
                <div class="slim-card-title">Work Experience</div>
                <div class="media">
                  <div class="experience-logo">
                    <i class="icon ion-briefcase"></i>
                  </div><!-- experience-logo -->
                  <div class="media-body">
                    <h6 class="position-name">Front-End Engineer / Web Developer</h6>
                    <p class="position-company">Cebu Machine Intelligence, Inc.</p>
                    <p class="position-year">Nov 2012 - Present (5 years +) &nbsp;-&nbsp; <a href="">Edit</a></p>
                  </div><!-- media-body -->
                </div><!-- media -->
              </div><!-- card-body -->
              <!--<div class="card-footer">
                <a href="">Show more<span class="d-none d-sm-inline"> experiences (4)</span> <i class="fa fa-angle-down"></i></a>
                <a href="">Add new</a>
              </div>--><!-- card-footer -->
            </div><!-- card -->

            <div class="card card-recommendation mg-t-20">
              <!--<div class="card-body pd-25">
                <div class="slim-card-title">Recommendations</div>
                <div class="media align-items-center mg-y-25">
                  <img src="http://via.placeholder.com/500x500" class="wd-40 rounded-circle" alt="">
                  <div class="media-body mg-l-15">
                    <h6 class="tx-14 mg-b-2"><a href="">Rolando Paloso</a></h6>
                    <p class="mg-b-0">Head Architect</p>
                  </div>
                  <span class="tx-12">Nov 20, 2017</span>
                </div>

                <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.</p>
                <p class="mg-b-0">Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo.</p>
              </div>--><!-- card-body -->

              
            </div>
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



            <div class="card pd-25 mg-t-20">
              <div class="slim-card-title">Contact &amp; Personal Info</div>

              <div class="media-list mg-t-25">
                <div class="media">
                  <div><i class="icon ion-link tx-24 lh-0"></i></div>
                  <div class="media-body mg-l-15 mg-t-4">
                    <h6 class="tx-14 tx-gray-700">Websites</h6>
                    <a href="" class="d-block">http://themepixels.me</a>
                    <a href="" class="d-block">http://themeforest.net</a>
                  </div><!-- media-body -->
                </div><!-- media -->
                <div class="media mg-t-25">
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
                <div class="media mg-t-25">
                  <div><i class="icon ion-social-twitter tx-18 lh-0"></i></div>
                  <div class="media-body mg-l-15 mg-t-2">
                    <h6 class="tx-14 tx-gray-700">Twitter</h6>
                    <a href="#" class="d-block">@themepixels</a>
                  </div><!-- media-body -->
                </div><!-- media -->
              </div><!-- media-list -->
            </div><!-- card -->
          </div><!-- col-4 -->
        </div><!-- row -->

      </div><!-- container -->
    </div><!-- slim-mainpanel -->

@include('common/footer')