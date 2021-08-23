@include('common/header')

 
    <div class="slim-mainpanel">
      <div class="container">
      <div class="card mb-4">
            <div class="card-body">
                <form method="POST" action="{{route('user.store')}}" enctype="multipart/form-data">
                    @csrf

                <div class="row my-3">
                    <div class="col">
                    <div class="form-group1">
                        <label for="name">Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" placeholder="Enter name">
                        @error('name')
                        <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                </div>
                <div class="col input-group1">
                             <label for="use type">User Type</label>
                            <select class="custom-select @error('type') is-invalid @enderror" id="type" name="type">
                                <option value="">Select user type</option>
                                <option value="admin">Admin</option>
                                <option value="salesman">Sales Man</option>
                            </select>
                             @error('type')
                                <small class="text-danger">{{$message}}</small>
                             @enderror
                        </div>                
                </div>
                    
                    <div class="row my-3">
                        <div class="col input-group1">
                            <label for="email">Email Address</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" placeholder="Enter email">
                        @error('email')
                        <small class="text-danger">{{$message}}</small>
                        @enderror
                        </div>                       
                    </div>

                    <div class="row my-3">
                        <div class="col">
                            <label for="password">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" placeholder="Enter password">
                            @error('password')
                            <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="col">
                            <label for="password_confirmation">Confirm Password</label>
                            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" id="password_confirmation" placeholder="Enter confirm password">
                            @error('password_confirmation')
                            <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="row my-3">
                        <div class="col">
                            <div class="input-group-prepend">
                                <button class="btn btn-outline-primary" id="generate" type="button">Generate</button>
                                <button class="btn btn-outline-info" id="showncopy" type="button">Show & Copy</button>
                            </div>
                           </div>
                           
                    </div>

                    <div class="row my-3">
                        <div class="col-6">
                            <input type="password" id="random-text" class="form-control" placeholder="Generate Random Password" aria-label="" aria-describedby="basic-addon1">
                        </div>
                    </div>



                    <div class="form-group">
                        <label for="profile_img">Profile Image</label>
                        <input style="border:none;width: auto;" type="file" name="avatar" id="profile_img" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-outline-primary">Add</button>
                </form>

            </div>
        </div>
      </div><!-- container -->
    </div><!-- slim-mainpanel -->


    @include('common/footer')
    <script>
    $(document).on("click", "button#generate", function() {
        var randomstring = Math.random().toString(36).slice(-10);
        $("input#random-text").val(randomstring);
    });

    $(document).on("click", "button#showncopy", function() {
        $("input#random-text").attr("type", "text")
        var randomText = $("input#random-text").val();
        $("input#password, input#password_confirmation").val(randomText);
        copyTextToClipboard();
    });

    function copyTextToClipboard() {
        var copyText = document.querySelector("input#random-text");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        document.execCommand("copy");
        console.log("Copied the text: " + copyText.value);
    }
</script>
   
  
