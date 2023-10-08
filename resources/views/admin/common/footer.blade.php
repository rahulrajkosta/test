

    <footer class="footer footer-static footer-light navbar-border navbar-shadow">
      <div class="clearfix blue-grey lighten-2 text-sm-center mb-0 px-2"><span class="float-md-left d-block d-md-inline-block">{{date('Y')}}  &copy; Copyright <a class="text-bold-800 grey darken-2" href="#" target="_blank">MATRIX MY EMI LOCKER</a></span>
      </div>
    </footer>
    <!-- END: Footer-->


    <!-- BEGIN: Vendor JS-->
    <script src="{{url('/assets/antitheft/')}}/app-assets/vendors/js/vendors.min.js" type="text/javascript"></script>
    <script src="{{url('/assets/antitheft/')}}/app-assets/vendors/js/forms/toggle/switchery.min.js" type="text/javascript"></script>
    <script src="{{url('/assets/antitheft/')}}/app-assets/js/scripts/forms/switch.min.js" type="text/javascript"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="{{url('/assets/antitheft/')}}/app-assets/vendors/js/charts/chartist.min.js" type="text/javascript"></script>
    <script src="{{url('/assets/antitheft/')}}/app-assets/vendors/js/charts/chartist-plugin-tooltip.min.js" type="text/javascript"></script>
    <script src="{{url('/assets/antitheft/')}}/app-assets/vendors/js/timeline/horizontal-timeline.js" type="text/javascript"></script>
    <script src="{{url('/assets/antitheft/')}}/app-assets/vendors/js/extensions/dropzone.min.js" type="text/javascript"></script>
    <script src="{{url('/assets/antitheft/')}}/app-assets/vendors/js/forms/icheck/icheck.min.js" type="text/javascript"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="{{url('/assets/antitheft/')}}/app-assets/js/core/app-menu.min.js" type="text/javascript"></script>
    <script src="{{url('/assets/antitheft/')}}/app-assets/js/core/app.min.js" type="text/javascript"></script>
    <script src="{{url('/assets/antitheft/')}}/app-assets/js/scripts/customizer.min.js" type="text/javascript"></script>
    <script src="{{url('/assets/antitheft/')}}/app-assets/vendors/js/jquery.sharrre.js" type="text/javascript"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script src="{{url('/assets/antitheft/')}}/app-assets/js/scripts/pages/dashboard-ecommerce.min.js" type="text/javascript"></script>
    <!-- END: Page JS-->
    <script src="{{url('/assets/antitheft/')}}/app-assets/js/scripts/pages/chat-application.js" type="text/javascript"></script>

    <script src="{{url('/assets/antitheft/')}}/app-assets/vendors/js/charts/chart.min.js" type="text/javascript"></script>


    <script src="{{url('/assets/antitheft/')}}/app-assets/js/scripts/charts/chartjs/bar/column.js" type="text/javascript"></script>
    <script src="{{url('/assets/antitheft/')}}/app-assets/js/scripts/charts/chartjs/bar/bar.min.js" type="text/javascript"></script>
    <script src="{{url('/assets/antitheft/')}}/app-assets/js/scripts/charts/chartjs/bar/column-stacked.min.js" type="text/javascript"></script>
    <script src="{{url('/assets/antitheft/')}}/app-assets/js/scripts/charts/chartjs/line/line.min.js" type="text/javascript"></script>
    <script src="{{url('/assets/antitheft/')}}/app-assets/js/scripts/charts/chartjs/line/line-area.min.js" type="text/javascript"></script>
    <script src="{{url('/assets/antitheft/')}}/app-assets/js/scripts/charts/chartjs/pie-doughnut/pie-simple.min.js" type="text/javascript"></script>
    <script src="{{url('/assets/antitheft/')}}/app-assets/js/scripts/charts/chartjs/pie-doughnut/doughnut-simple.min.js" type="text/javascript"></script>
    <script src="{{url('/assets/antitheft/')}}/app-assets/js/scripts/charts/chartjs/polar-radar/polar.min.js" type="text/javascript"></script>
    <script src="{{url('/assets/antitheft/')}}/app-assets/js/scripts/charts/chartjs/polar-radar/radar.min.js" type="text/javascript"></script>

    
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  
  </body>

</html>
<script type="text/javascript">
  !(function (e, t, r) {
    console.log(e);
    new Chartist.Line(
        "#gradient-line-chart1",
        {
            labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
            series: [
                [100, 500, 125, 225, 175, 275, 220],
                [100, 275, 165, 280, 120, 226, 150],
            ],
        },
        {
            low: 100,
            fullWidth: !0,
            onlyInteger: !0,
            axisY: { low: 0, scaleMinSpace: 50 },
            axisX: { showGrid: !1 },
            lineSmooth: Chartist.Interpolation.simple({ divisor: 2 }),
            plugins: [Chartist.plugins.tooltip({ appendToBody: !0, pointClass: "ct-point" })],
        }
    )
        .on("created", function (e) {
            var t = e.svg.querySelector("defs") || e.svg.elem("defs");
            return (
                t
                    .elem("linearGradient", { id: "lineLinear1", x1: 0, y1: 0, x2: 1, y2: 0 })
                    .elem("stop", { offset: "0%", "stop-color": "rgba(168,120,244,0.1)" })
                    .parent()
                    .elem("stop", { offset: "10%", "stop-color": "rgba(168,120,244,1)" })
                    .parent()
                    .elem("stop", { offset: "80%", "stop-color": "rgba(255,108,147, 1)" })
                    .parent()
                    .elem("stop", { offset: "98%", "stop-color": "rgba(255,108,147, 0.1)" }),
                t
                    .elem("linearGradient", { id: "lineLinear2", x1: 0, y1: 0, x2: 1, y2: 0 })
                    .elem("stop", { offset: "0%", "stop-color": "rgba(0,230,175,0.1)" })
                    .parent()
                    .elem("stop", { offset: "10%", "stop-color": "rgba(0,230,175,1)" })
                    .parent()
                    .elem("stop", { offset: "80%", "stop-color": "rgba(255,161,69, 1)" })
                    .parent()
                    .elem("stop", { offset: "98%", "stop-color": "rgba(255,161,69, 0.1)" }),
                t
            );
        })
        .on("draw", function (e) {
            if ("point" === e.type) {
                var t = new Chartist.Svg("circle", { cx: e.x, cy: e.y, "ct:value": e.y, r: 10, class: 225 === e.value.y ? "ct-point ct-point-circle" : "ct-point ct-point-circle-transperent" });
                e.element.replace(t);
            }
            "line" === e.type && e.element.animate({ d: { begin: 1e3, dur: 1e3, from: e.path.clone().scale(1, 0).translate(0, e.chartRect.height()).stringify(), to: e.path.clone().stringify(), easing: Chartist.Svg.Easing.easeOutQuint } });
        });
})(window, document, jQuery);

</script>
<script type="text/javascript">
    $(document).ready(function() {
    $('.select2').select2();
});
</script>

<script type="text/javascript">
    $('#country_id').change( function(){
    var _token = '{{ csrf_token() }}';
    var country_id = $('#country_id').val();
    $.ajax({
      url: "{{ route('get_state') }}",
      type: "POST",
      data: {country_id:country_id},
      dataType:"HTML",
      headers:{'X-CSRF-TOKEN': _token},
      cache: false,
      success: function(resp){
       $('#state_id').html(resp);
     }
   });
  });

   $('#state_id').change( function(){
    var _token = '{{ csrf_token() }}';
    var state_id = $('#state_id').val();
    $.ajax({
      url: "{{ route('get_city') }}",
      type: "POST",
      data: {state_id:state_id},
      dataType:"HTML",
      headers:{'X-CSRF-TOKEN': _token},
      cache: false,
      success: function(resp){
       $('#city_id').html(resp);
     }
   });
  });
  $('document').ready(function(){
    $("#togglePassword").on("click",function(){
      $(this).toggleClass("fa-eye-slash");
      var dd=$(this).attr('class');
      //alert(dd);
      if($(this).attr('class')=="fa fa-eye fa-eye-slash"){
       $("#password").attr("type","text");
      }else{
        $("#password").attr("type","password");
      }
    });
    $(".togglePassword").on("click",function(){
      
      $(this).toggleClass("fa-eye-slash");
      var dd=$(this).attr('class');
      //alert(dd);
      if($(this).attr('class')=="fa fa-eye fa-eye-slash"){
       $(this).closest(".password").attr("type","text");
      }else{
        $(this).closest(".password").attr("type","password");
      }
    });
  })
</script>