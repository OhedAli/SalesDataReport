@include('common/header')
@include('common/navbar')
<div class="slim-mainpanel">
      <div class="container pd-t-50">
        <div class="row">
          <div class="col-lg-12">
            <h3 class="tx-inverse mg-b-15">Welcome back, Logan!</h3>
            <p class="mg-b-40">Please see the chart.</p>

            <h6 class="slim-card-title mg-b-15">Your Lead Summary</h6>
            <div class="row no-gutters">
              <div class="col-sm-4">
                <div class="card card-earning-summary">
                  <h6>Today's Lead</h6>
                  <h1>950</h1>
                </div><!-- card -->
              </div><!-- col-6 -->
              <div class="col-sm-4">
                <div class="card card-earning-summary mg-sm-l--1 bd-t-0 bd-sm-t">
                  <h6>This Week's Lead</h6>
                  <h1>12,420</h1>
                </div><!-- card -->
              </div><!-- col-6 -->
              <div class="col-sm-4">
                <div class="card card-earning-summary mg-sm-l--1 bd-t-0 bd-sm-t">
                  <h6>This Week's Lead</h6>
                  <h1>12,420</h1>
                </div><!-- card -->
              </div><!-- col-6 -->
            </div><!-- row -->
          </div><!-- col-6 -->
         
        </div><!-- row -->

      

        <div class="card card-table mg-t-20 mg-sm-t-30">
          <div class="card-header">
            <h6 class="slim-card-title">Product Purchases</h6>
            <nav class="nav">
              <a href="" class="nav-link active">Today</a>
              <a href="" class="nav-link">This Week</a>
              <a href="" class="nav-link">This Month</a>
            </nav>
          </div><!-- card-header -->
          <div class="table-responsive">
            <table class="table mg-b-0 tx-13">
              <thead>
                <tr class="tx-10">
                  <th class="wd-10p pd-y-5 tx-center">Item</th>
                  <th class="pd-y-5">Item Details</th>
                  <th class="pd-y-5 tx-right">Sold</th>
                  <th class="pd-y-5 tx-center">Location</th>
                  <th class="pd-y-5">Gain</th>
                  <th class="pd-y-5 tx-right">Added</th>
                  <th class="pd-y-5 tx-right">Updated</th>
                  <th class="pd-y-5 tx-center">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="tx-center">
                    <img src="http://via.placeholder.com/800x533" class="wd-55" alt="Image">
                  </td>
                  <td>
                    <a href="" class="tx-inverse tx-medium d-block">The Dothraki Shoes</a>
                    <span class="tx-12 d-block"><span class="square-8 bg-danger mg-r-5 rounded-circle"></span> 20 remaining</span>
                  </td>
                  <td class="valign-middle tx-right">3,345</td>
                  <td class="valign-middle tx-center"><span class="flag-icon flag-icon-ph tx-16"></span></td>
                  <td class="valign-middle"><span class="tx-success"><i class="icon ion-android-arrow-up mg-r-5"></i>33.34%</span> from last week</td>
                  <td class="valign-middle tx-right">10/21/2017</td>
                  <td class="valign-middle tx-right">an hour ago</td>
                  <td class="valign-middle tx-center">
                    <a href="" class="tx-gray-600 tx-24"><i class="icon ion-android-more-horizontal"></i></a>
                  </td>
                </tr>
                <tr>
                  <td class="tx-center">
                    <img src="http://via.placeholder.com/800x533" class="wd-55" alt="Image">
                  </td>
                  <td>
                    <a href="" class="tx-inverse tx-medium d-block">Westeros Sneaker</a>
                    <span class="tx-12 d-block"><span class="square-8 bg-success mg-r-5 rounded-circle"></span> In stock</span>
                  </td>
                  <td class="valign-middle tx-right">720</td>
                  <td class="valign-middle tx-center"><span class="flag-icon flag-icon-ca tx-16"></span></td>
                  <td class="valign-middle"><span class="tx-danger"><i class="icon ion-android-arrow-down mg-r-5"></i>21.20%</span> from last week</td>
                  <td class="valign-middle tx-right">10/20/2017</td>
                  <td class="valign-middle tx-right">3 hours ago</td>
                  <td class="valign-middle tx-center">
                    <a href="" class="tx-gray-600 tx-24"><i class="icon ion-android-more-horizontal"></i></a>
                  </td>
                </tr>
                <tr>
                  <td class="tx-center">
                    <img src="http://via.placeholder.com/800x533" class="wd-55" alt="Image">
                  </td>
                  <td>
                    <a href="" class="tx-inverse tx-medium d-block">Selonian Hand Bag</a>
                    <span class="tx-12 d-block"><span class="square-8 bg-success mg-r-5 rounded-circle"></span> In stock</span>
                  </td>
                  <td class="valign-middle tx-right">1,445</td>
                  <td class="valign-middle tx-center"><span class="flag-icon flag-icon-us tx-16"></span></td>
                  <td class="valign-middle"><span class="tx-success"><i class="icon ion-android-arrow-up mg-r-5"></i>23.34%</span> from last week</td>
                  <td class="valign-middle tx-right">10/19/2017</td>
                  <td class="valign-middle tx-right">5 hours ago</td>
                  <td class="valign-middle tx-center">
                    <a href="" class="tx-gray-600 tx-24"><i class="icon ion-android-more-horizontal"></i></a>
                  </td>
                </tr>
                <tr>
                  <td class="tx-center">
                    <img src="http://via.placeholder.com/800x533" class="wd-55" alt="Image">
                  </td>
                  <td>
                    <a href="" class="tx-inverse tx-medium d-block">Kel Dor Sunglass</a>
                    <span class="tx-12 d-block"><span class="square-8 bg-warning mg-r-5 rounded-circle"></span> 45 remaining</span>
                  </td>
                  <td class="valign-middle tx-right">2,500</td>
                  <td class="valign-middle tx-center"><span class="flag-icon flag-icon-dk tx-16"></span></td>
                  <td class="valign-middle"><span class="tx-success"><i class="icon ion-android-arrow-up mg-r-5"></i>28.78%</span> from last week</td>
                  <td class="valign-middle tx-right">10/17/2017</td>
                  <td class="valign-middle tx-right">1 day ago</td>
                  <td class="valign-middle tx-center">
                    <a href="" class="tx-gray-600 tx-24"><i class="icon ion-android-more-horizontal"></i></a>
                  </td>
                </tr>
                <tr>
                  <td class="tx-center">
                    <img src="http://via.placeholder.com/800x533" class="wd-55" alt="Image">
                  </td>
                  <td>
                    <a href="" class="tx-inverse tx-medium d-block">Kubaz Sunglass</a>
                    <span class="tx-12 d-block"><span class="square-8 bg-success mg-r-5 rounded-circle"></span> In stock</span>
                  </td>
                  <td class="valign-middle tx-right">223</td>
                  <td class="valign-middle tx-center"><span class="flag-icon flag-icon-au tx-16"></span></td>
                  <td class="valign-middle"><span class="tx-danger"><i class="icon ion-android-arrow-down mg-r-5"></i>18.18%</span> from last week</td>
                  <td class="valign-middle tx-right">10/16/2017</td>
                  <td class="valign-middle tx-right">a week ago</td>
                  <td class="valign-middle tx-center">
                    <a href="" class="tx-gray-600 tx-24"><i class="icon ion-android-more-horizontal"></i></a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div><!-- table-responsive -->
          <div class="card-footer tx-12 pd-y-15 bg-transparent">
            <a href=""><i class="fa fa-angle-down mg-r-5"></i>View All Products</a>
          </div><!-- card-footer -->
        </div><!-- card -->
      </div><!-- container -->
    </div><!-- slim-mainpanel -->

    @include('common/footer')


