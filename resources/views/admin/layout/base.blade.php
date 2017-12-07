<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Title -->
    <title>@yield('title'){{ Setting::get('site_title', 'Your Cab') }}</title>

    <link rel="shortcut icon" type="image/png" href="{{ Setting::get('site_icon') }}">

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="{{asset('main/vendor/bootstrap4/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('main/vendor/themify-icons/themify-icons.css')}}">
    <link rel="stylesheet" href="{{asset('main/vendor/font-awesome/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('main/vendor/animate.css/animate.min.css')}}">
    <link rel="stylesheet" href="{{asset('main/vendor/jscrollpane/jquery.jscrollpane.css')}}">
    <link rel="stylesheet" href="{{asset('main/vendor/waves/waves.min.css')}}">
    <link rel="stylesheet" href="{{asset('main/vendor/switchery/dist/switchery.min.css')}}">
    <link rel="stylesheet" href="{{asset('main/vendor/DataTables/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('main/vendor/DataTables/Responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('main/vendor/DataTables/Buttons/css/buttons.dataTables.min.css')}}">
    <link rel="stylesheet" href="{{asset('main/vendor/DataTables/Buttons/css/buttons.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css">
    <link rel="stylesheet" href="{{ asset('main/vendor/dropify/dist/css/dropify.min.css') }}">
    <link rel="stylesheet" href="{{ asset('main/assets/css/core.css') }}">

    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
    <style type="text/css">
        .rating-outer span,
        .rating-symbol-background {
            color: #ffe000!important;
        }
        .rating-outer span,
        .rating-symbol-foreground {
            color: #ffe000!important;
        }
    </style>
    @yield('styles')
</head>
<body class="fixed-sidebar fixed-header content-appear skin-default">

    <div class="wrapper">
        <div class="preloader"></div>
        <div class="site-overlay"></div>

        @include('admin.include.nav')

        @include('admin.include.header')

        <div class="site-content">

            @include('common.notify')

            @yield('content')

            @include('admin.include.footer')

        </div>
    </div>

    <!-- Vendor JS -->
    <script type="text/javascript" src="{{asset('main/vendor/jquery/jquery-1.12.3.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('main/vendor/tether/js/tether.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('main/vendor/bootstrap4/js/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('main/vendor/detectmobilebrowser/detectmobilebrowser.js')}}"></script>
    <script type="text/javascript" src="{{asset('main/vendor/jscrollpane/jquery.mousewheel.js')}}"></script>
    <script type="text/javascript" src="{{asset('main/vendor/jscrollpane/mwheelIntent.js')}}"></script>
    <script type="text/javascript" src="{{asset('main/vendor/jscrollpane/jquery.jscrollpane.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('main/vendor/jquery-fullscreen-plugin/jquery.fullscreen')}}-min.js"></script>
    <script type="text/javascript" src="{{asset('main/vendor/waves/waves.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('main/vendor/DataTables/js/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('main/vendor/DataTables/js/dataTables.bootstrap4.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('main/vendor/DataTables/Responsive/js/dataTables.responsi')}}ve.min.js"></script>
    <script type="text/javascript" src="{{asset('main/vendor/DataTables/Responsive/js/responsive.bootstra')}}p4.min.js"></script>
    <script type="text/javascript" src="{{asset('main/vendor/DataTables/Buttons/js/dataTables.buttons')}}.min.js"></script>
    <script type="text/javascript" src="{{asset('main/vendor/DataTables/Buttons/js/buttons.bootstrap4')}}.min.js"></script>
    <script type="text/javascript" src="{{asset('main/vendor/DataTables/JSZip/jszip.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('main/vendor/DataTables/pdfmake/build/pdfmake.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('main/vendor/DataTables/pdfmake/build/vfs_fonts.js')}}"></script>
    <script type="text/javascript" src="{{asset('main/vendor/DataTables/Buttons/js/buttons.html5.min.js')}}"></script>

    <script type="text/javascript" src="{{asset('main/vendor/DataTables/Buttons/js/buttons.print.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('main/vendor/DataTables/Buttons/js/buttons.colVis.min.js')}}"></script>

    <script type="text/javascript" src="{{asset('main/vendor/switchery/dist/switchery.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('main/vendor/dropify/dist/js/dropify.min.js')}}"></script>

    <!-- Neptune JS -->
    <script type="text/javascript" src="{{asset('main/assets/js/app.js')}}"></script>
    <script type="text/javascript" src="{{asset('main/assets/js/demo.js')}}"></script>
    <script type="text/javascript" src="{{asset('main/assets/js/tables-datatable.js')}}"></script>
    <script type="text/javascript" src="{{asset('main/assets/js/forms-upload.js')}}"></script>


    @yield('scripts')

    <script type="text/javascript" src="{{asset('asset/js/rating.js')}}"></script>    
    <script type="text/javascript">
         var token='<?php echo csrf_token(); ?>';
        $('.rating').rating();
        $('#table-3 tfoot').hide();
        $('.buttons-copy').hide();
        $(document).ready(function(){    
            var example4 = $('#example4').DataTable({
                dom: 'lBfrtip',
                 buttons: [
                     'csv', 'excel', 'pdf'
                 ]
                
            });
            var example = $('#example').DataTable();           
            $(document).on('change','.isCheckedAll',function(){
                var cells = example.cells( ).nodes();
                $( cells ).find(':checkbox').prop('checked', $(this).is(':checked'));
            });
            var example1 = $('#example1').DataTable();           
            $(document).on('change','.isCheckedAll',function(){
                var cells = example1.cells( ).nodes();
                $( cells ).find(':checkbox').prop('checked', $(this).is(':checked'));
            });
            var example2 = $('#example2').DataTable();           
            $(document).on('change','.isCheckedAll',function(){
                var cells = example2.cells( ).nodes();
                $( cells ).find(':checkbox').prop('checked', $(this).is(':checked'));
            });
            $('.buttons-copy').hide();
            $(document).on('click','.btnSubmitData',function(){
                $.ajax({
                    url:'http://clientstagingdev.com/yourcab/public/admin/post_messages',
                    data:$('.users').serializeArray(),
                    type:'post',
                    dataType:'json',
                    success:function(response){
                        if(response.status=='false'){
                           $('.response').html(response.message); 
                           $('.response').show(); 
                        }else{
                            $('.response').hide(); 
                           $('#myModal').modal('show');
                            $('#ids').val(response.message);
                            $('#type').val(response.type);
                        }
                    }                    
                });
            });
           
            rowArrayUser=[];
            rowArray=[];
            rowArrayUsersList=[];
            rowArrayDriver=[];
            rowArrayDriverList=[];
             $(document).on('click','.isCheckedAll',function(){
                if($(this).is(':checked')){
                    var tab=$(this).attr('data-tab');
                    if(tab=='user'){
                         var search=$('#usearch').val();
                       $.ajax({
                            url:'http://clientstagingdev.com/yourcab/public/admin/get_all_user_data',
                            data:{
                                type:'user',
                                _token:token,
                                search:search
                            },
                            type:'post',   
                            dataType:'json',
                            success:function(response){
                              $('#newusers').val(response.users);    
                              rowArrayUser = response.users.split(',');  
                            }                    
                       });  
                    }else if(tab=='driver'){
                        var search=$('#dsearch').val();
                         $.ajax({
                            url:'http://clientstagingdev.com/yourcab/public/admin/get_all_user_data',
                            data:{
                                type:'driver',
                                _token:token,
                                search:search
                            },
                            type:'post',  
                            dataType:'json',
                            success:function(response){
                              $('#newdrivers').val(response.users);   
                              rowArrayDriverList = response.users.split(',');  
                            }                    
                       });                   
                    }else{
                        var search=$('#allsearch').val();
                        $.ajax({
                            url:'http://clientstagingdev.com/yourcab/public/admin/get_all_user_data',
                            data:{
                                type:'all',
                                _token:token,
                                search:search
                            },
                            type:'post',  
                            dataType:'json',
                            success:function(response){
                              $('#nU').val(response.users);  
                              rowArrayUsersList = response.users.split(','); 
                              $('#nD').val(response.drivers); 
                              rowArrayUsersList = response.users.split(','); 
                            }                    
                       });    

                    }                    
                }else{
                    $('#newusers').val('');   
                    $('#newdrivers').val(''); 
                    $('#nU').val('');  
                    $('#nD').val(''); 
                    rowArrayUser=[];
                    rowArray=[];
                    rowArrayUsersList=[];
                    rowArrayDriver=[];
                    rowArrayDriverList=[];
                }
                
            });
            $(document).on('keyup','#example2_filter input',function(){
                var test=$(this).val();
                $('#allsearch').val(test);

            });
            $(document).on('keyup','#example_filter input',function(){
                var test=$(this).val();
                $('#usearch').val(test);

            });
            $(document).on('keyup','#example1_filter input',function(){
                var test=$(this).val();
                $('#dsearch').val(test);
            });
            
            
            
            
            
            
            
            $(document).on('click','.isChecked',function(){
                var type=$(this).attr('data-id');                
                if(type=='u'){
                   var test=$(this).val();                      
                   if ($(this).is(':checked'))
                    {
                      rowArrayUser.push(test);                      
                    }else{                       
                      removeA(rowArrayUser, test);                      
                    }
                    $('#newusers').val(rowArrayUser);  
                }else if(type=='d'){
                   var test=$(this).val();  
                   if ($(this).is(':checked'))
                    {
                      rowArrayDriverList.push(test);
                    }else{
                      removeA(rowArrayDriverList, test);     
                    } 
                    $('#newdrivers').val(rowArrayDriverList);    
                }else{
                    if(type=='aU'){
                       var test=$(this).val();  
                       if ($(this).is(':checked'))
                        {
                          rowArrayUsersList.push(test);
                        }else{
                          removeA(rowArrayUsersList, test);  
                        }
                        $('#nU').val(rowArrayUsersList);   
                    }else{
                       var test=$(this).val();  
                       if ($(this).is(':checked'))
                       {
                        rowArrayDriver.push(test);
                       }else{
                        removeA(rowArrayDriver, test);     
                       } 
                       $('#nD').val(rowArrayDriver);   
                    }                 
                   
                }              
                
            });
            //driver
            $(document).on('click','.btnSubmitDataDriver',function(){
                $.ajax({
                    url:'http://clientstagingdev.com/yourcab/public/admin/post_messages',
                    data:$('.drivers').serializeArray(),
                    type:'post',
                    dataType:'json',
                    success:function(response){
                        if(response.status=='false'){                           
                           $('.response').html(response.message); 
                             $('.response').show(); 
                        }else{
                            $('.response').hide(); 
                           $('#myModal').modal('show');
                            $('#ids').val(response.message);
                            $('#type').val(response.type);
                        }
                    }                    
                });
            });            
            //all
            function removeA(arr) {
                var what, a = arguments,
                    L = a.length,
                    ax;
                while (L > 1 && arr.length) {
                    what = a[--L];
                    while ((ax = arr.indexOf(what)) !== -1) {
                        arr.splice(ax, 1);
                    }
                }
                return arr;
            }
            $(document).on('click','.btnSubmitDataAll',function(){
                $.ajax({
                    url:'http://clientstagingdev.com/yourcab/public/admin/post_messages',
                    data:$('.all').serializeArray(),
                    type:'post',
                    dataType:'json',
                    success:function(response){
                        if(response.status=='false'){
                           $('.response').html(response.message); 
                            $('.response').show(); 
                        }else{
                            $('.response').hide(); 
                            $('#myModal').modal('show');
                            $('#ids').val(response.message);
                            $('#dids').val(response.dids);
                            $('#type').val(response.type);
                        }
                    }                    
                });
            });
            $(document).on('click','.sendMessageToparticularDriver',function(){
                var dId=$(this).attr('data-id');
                var token='<?php echo csrf_token(); ?>';
                var row = [];
                row.push(dId);
                $.ajax({
                    url:'http://clientstagingdev.com/yourcab/public/admin/post_messages',
                    data:{type:'drivers',drivers:row,_token:token},
                    type:'post',
                    dataType:'json',
                    success:function(response){
                        if(response.status=='false'){
                           $('.response').html(response.message); 
                        }else{
                            $('#textArea').val('');
                            $('#ids').val('');
                            $('#dids').val('');
                            $('#myModal').modal('show');
                            $('#ids').val(response.message);
                            $('#dids').val(response.dids);
                            $('#type').val(response.type);
                        }
                    }                    
                });
            });
            $(document).on('click','.select',function(){
                $('.select').removeClass('active');
                $('.response').hide();
                $(this).addClass('active');
                var id=$(this).attr('href');
                if(id=='#users'){
                  $('#newdrivers').val('');   
                  $('#nU').val(''); 
                  $('#nD').val('')
                }else if(id=='#drivers'){
                  $('#newusers').val(''); 
                  $('#nU').val(''); 
                  $('#nD').val('');                    
                }else{
                   $('#newdrivers').val('');  
                   $('#newusers').val(''); 
                }
                $('.isChecked').attr('checked',false);
                $('.isCheckedAll').attr('checked',false);
            });
            $(document).on('click','.viewMsg',function(){
             var id=$(this).attr('data-id'); 
                var token='<?php echo csrf_token(); ?>';
             $.ajax({
                    url:'http://clientstagingdev.com/yourcab/public/admin/get_contact_message',
                    data:{
                        _token:token,
                        id:id
                    },
                    type:'post',
                    dataType:'json',
                    success:function(response){
                        if(response.status=='true'){
                            $('#recipient-name').val(response.message.users.first_name+' '+response.message.users.last_name);
                            $('#uname').val(response.message.users.first_name+' '+response.message.users.last_name);
                            $('#uemail').val(response.message.users.email);
                            $('#title').val(response.message.title);
                            $('#message-text').val(response.message.message);                            
                            $('#myModalView').modal('show');                            
                        }                       
                    }                    
               });     
                
            });
            $(document).on('click','#sndMsgBtn',function(){
                var msg=$('#textArea').val();
                if(msg==''){
                  $('#textArea').addClass('errorMsg'); 
                  return false;
                }else{
                    $('#textArea').removeClass('errorMsg'); 
                   $.ajax({
                        url:'http://clientstagingdev.com/yourcab/public/admin/do_post_messages',
                        data:$('#sndMessage').serializeArray(),
                        type:'post',
                        dataType:'json',
                        success:function(response){
                            if(response.status=='true'){
                                $('.isChecked').attr('checked',false);
                              $('.responseDiv').html('Your message has been sent.');
                                $('.responseDiv').show();
                              setTimeout(function(){
                                  $('.responseDiv').html(''); 
                                  $('.responseDiv').hide();
                                  $('#textArea').val('');
                                  $('#ids').val('');
                                  $('#dids').val('');
                                  $('#myModal').modal('hide'); 
                              },2000);                               
                            }
                        }                    
                   });                    
                }
            }); 
            $(document).on('click','.replybutton',function(){
                $('#myModalView').modal('hide');
                $('#textAreaContent').removeClass('errorMsg'); 
                var email=$(this).prev().prev().val();
                var name=$(this).prev().val();
                $('#replyEmail').val(email);
                $('#replyName').val(name); 
                $('#name').val(name);
                $('#email').val(email);
                setTimeout(function(){
                    $('#myModalReply').modal('show');                    
                },1000);                
            });
            $(document).on('click','#replyMsgBtn',function(){
                var msg=$('#textAreaContent').val();
                if(msg==''){
                  $('#textAreaContent').addClass('errorMsg'); 
                  return false;
                }else{
                    $('#textAreaContent').removeClass('errorMsg'); 
                   $.ajax({
                        url:'http://clientstagingdev.com/yourcab/public/admin/reply_messages',
                        data:$('#replyMessage').serializeArray(),
                        type:'post',
                        dataType:'json',
                        success:function(response){
                            if(response.status=='true'){
                                $('.responseDivReply').html(response.message);
                                $('.responseDivReply').show();
                              setTimeout(function(){
                                  $('.responseDivReply').html(''); 
                                  $('.responseDivReply').hide();
                                  $('#textAreaContent').val('');                                  
                                  $('#myModalReply').modal('hide'); 
                              },2000);                               
                            }
                        }                    
                   });                    
                }
            });
        });
        
        $("#example1 > tbody > tr").each(function(i,e){
           console.log(i,e); 
        });
        
    </script>
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        
      <form id="sndMessage">
          <div class="responseDiv" style="display:none;"></div>
          {{csrf_field()}}
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Enter Message</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <textarea id="textArea" name="textarea"></textarea>
              <input type="hidden" id="ids" name="users"/>
              <input type="hidden" id="dids" name="drivers"/>
              <input type="hidden" id="type" name="type"/>
          </div>
          <div class="modal-footer">
            <!--<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>-->
            <button id="sndMsgBtn" type="button" class="btn btn-primary">Send Message</button>
          </div>
       </form>
    </div>
  </div>
<style>
    .errorMsg{
        border:2px solid red !important;
    }
</style>
</div>
    
<div class="modal fade" id="myModalReply" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabelReply" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">        
      <form id="replyMessage">
          <div class="responseDivReply" style="display:none;"></div>
          {{csrf_field()}}
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Reply Message</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
              <input type="text" class="form-control" disabled name="email" id="replyEmail"/>   <br>           
              <textarea id="textAreaContent" name="content"></textarea>
              <input type="hidden" id="name" name="name"/>
              <input type="hidden" id="email" name="email"/>
          </div>
          <div class="modal-footer">
            <!--<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>-->
            <button id="replyMsgBtn" type="button" class="btn btn-primary">Send Reply</button>
          </div>
       </form>
    </div>
  </div>

</div>
        
<div class="modal fade" id="myModalView" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabelReply" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">        
      <form id="">
          {{csrf_field()}}
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">View Message</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
              <div class="form-group">
                <label for="recipient-name" class="form-control-label">Name:</label>
                <input type="text" disabled name="name" class="form-control" id="recipient-name">
              </div>
              <div class="form-group">
                <label for="recipient-name" class="form-control-label">Title:</label>
                <input type="text" disabled id="title" class="form-control" >
              </div>
              <div class="form-group">
                <label for="message-text" class="form-control-label">Message:</label>
                <textarea rows="10" class="form-control" disabled id="message-text"></textarea>
              </div>              
              
          </div>
          <div class="modal-footer">  
              <input type="hidden" id="uemail" name="email"/>
              <input type="hidden" id="uname" name="name"/>
              <button type="button" class="btn btn-primary replybutton">Send Reply</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
       </form>
    </div>
  </div>

</div>
    
    <style>
    .errorMsg{
        border:2px solid red !important;
    }
        table.dataTable tr th.select-checkbox.selected::after {
    content: "✔";
    margin-top: -11px;
    margin-left: -4px;
    text-align: center;
    text-shadow: rgb(176, 190, 217) 1px 1px, rgb(176, 190, 217) -1px -1px, rgb(176, 190, 217) 1px -1px, rgb(176, 190, 217) -1px 1px;
}
</style>
    
</body>
</html>