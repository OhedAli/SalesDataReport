
    <div class="slim-footer">
      <div class="container">
        <p>Copyright <?php echo date('Y'); ?> &copy; All Rights Reserved.</p>
        <p>Designed by: <a href="https://codeclouds.com" target="_blank" >CodeClouds</a></p>
      </div><!-- container -->
    </div><!-- slim-footer -->

    
    <script src="{{asset('app/lib/jquery/js/jquery.js')}}"></script>
    <script src="{{asset('app/lib/popper.js/js/popper.js')}}"></script>
    <script src="{{asset('app/lib/bootstrap/js/bootstrap.js')}}"></script>
    <script src="{{asset('app/lib/jquery.cookie/js/jquery.cookie.js')}}"></script>
    
    <script src="{{asset('app/js/slim.js')}}"></script>
    <script src="{{asset('js/custom.js')}}"></script>
    <script src="{{asset('app/lib/datatables/js/jquery.dataTables.js')}}"></script>
    <script src="{{asset('app/lib/datatables-responsive/js/dataTables.responsive.js')}}"></script>
    <script src="{{asset('app/lib/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('app/lib/fullcalendar/js/moment.js')}}"></script>
    <script src="{{asset('app/lib/fullcalendar/js/fullcalendar.js')}}"></script>
    <script src="{{asset('js/better-dom.min.js')}}"></script>
    <script src="{{asset('js/better-dateinput-polyfill.js')}}"></script>

    <script type="text/javascript">
        var page_name = "{{ Route::currentRouteName() }}";
        markActiveNav(page_name);
    </script>

    </body>
</html>
