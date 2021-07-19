
    <div class="slim-footer">
      <div class="container">
        <p>Copyright <?php echo date('Y'); ?> &copy; All Rights Reserved.</p>
      </div><!-- container -->
    </div><!-- slim-footer -->

    
    <script src="{{asset('public/app/lib/jquery/js/jquery.js')}}"></script>
    <script src="{{asset('public/app/lib/popper.js/js/popper.js')}}"></script>
    <script src="{{asset('public/app/lib/bootstrap/js/bootstrap.js')}}"></script>
    <script src="{{asset('public/app/lib/jquery.cookie/js/jquery.cookie.js')}}"></script>
    
    <script src="{{asset('public/app/js/slim.js')}}"></script>
    <script src="{{asset('js/custom.js')}}"></script>
    <script src="{{asset('public/app/lib/datatables/js/jquery.dataTables.js')}}"></script>
    <script src="{{asset('public/app/lib/datatables-responsive/js/dataTables.responsive.js')}}"></script>
    <script src="{{asset('public/app/lib/datatables/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('public/app/lib/datatables/js/jszip.min.js')}}"></script>
    <script src="{{asset('public/app/lib/datatables/js/buttons.html5.min.js')}}"></script>
    <script src="{{asset('public/app/lib/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('public/app/lib/fullcalendar/js/moment.js')}}"></script>
    <script src="{{asset('public/app/lib/fullcalendar/js/fullcalendar.js')}}"></script>
    <script src="{{asset('js/better-dom.min.js')}}"></script>
    <script src="{{asset('js/better-dateinput-polyfill.js')}}"></script>


    <script type="text/javascript">
        var page_name = "{{ Route::currentRouteName() }}";
        markActiveNav(page_name);
    </script>

    </body>
</html>
