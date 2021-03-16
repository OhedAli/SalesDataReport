<div class="slim-navbar">
      <div class="container">
        <ul class="nav">
          <li class="nav-item with-sub">
            <a class="nav-link nav-base" href="{{Route('dashboard')}}" name="dashboard">
              <i class="icon ion-ios-home-outline"></i>
              <span>Dashboard</span>
            </a>
        
          </li>
          <li class="nav-item with-sub mega-dropdown">

            <a class="nav-link" href="#" name="saleslogs">

              <i class="icon ion-ios-filing-outline"></i>
              <span>Saleslogs</span>
            </a>
            <div class="sub-item">
              <ul>
                <li><a href="{{Route('saleslogs')}}">Sales</a></li>
                <li><a href="{{Route('wholesaleslogs')}}">WholeSales</a></li>
              </ul>
            </div><!-- sub-item -->
          </li>
          <li class="nav-item with-sub mega-dropdown">

            <a class="nav-link" href="#">

              <i class="icon ion-ios-filing-outline"></i>
              <span>Calls</span>
            </a>
            
          </li>
          <li class="nav-item with-sub mega-dropdown">

            <a class="nav-link" href="#">

              <i class="icon ion-ios-filing-outline"></i>
              <span>Otherlogs</span>
            </a>
            
          </li>
         
        
         
        </ul>
      </div><!-- container -->
    </div><!-- slim-navbar -->
