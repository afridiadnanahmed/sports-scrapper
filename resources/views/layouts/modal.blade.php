
<div id="deactivateModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Deactivate?</h4>
            </div>
            <div class="modal-body">
                <div class="form-data"></div>
                <p>Do you really want to deactivate this?</p>
            </div>
            <div class="modal-footer">
                <a type="button" class="btn btn-default" data-dismiss="modal">Cancel</a>
                <button  type="button" class="btn btn-primary" data-dismiss="modal" id="deactivateRef">Confirm</button>
            </div>
        </div>
    </div>
</div>


<div id="activateModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Reactivate?</h4>
            </div>
            <div class="modal-body">
                <div class="form-data"></div>
                <p>Do you really want to activate this?</p>
            </div>
            <div class="modal-footer">
                <a type="button" class="btn btn-default" data-dismiss="modal">Cancel</a>
                <button  type="button" class="btn btn-primary" data-dismiss="modal" id="activateRef">Confirm</button>
            </div>
        </div>
    </div>
</div>


<div id="deactivateServiceModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Deactivate?</h4>
            </div>
            <div class="modal-body">
                <div class="form-data"></div>
                <p>Do you really want to deactivate this?</p>
            </div>
            <div class="modal-footer">
                <a type="button" class="btn btn-default" data-dismiss="modal">Cancel</a>
                <button  type="button" class="btn btn-primary" data-dismiss="modal" id="deactServiceRef">Confirm</button>
            </div>
        </div>
    </div>
</div>


<div id="activateServiceModal" class="modal fade" role="dialog">

</div>



<div id="modelnyearModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Deactivate/Reactivate?</h4>
            </div>
            <div class="modal-body">
                <div class="form-data"></div>
                <p>Select the models and press save</p>
            </div>
            <form method="post" action="{{route('statusModelnyear')}}">
                <div style="width: 100%">
                    <table style="box-shadow: none; width: 100%">
                        <tbody id="checkBoxes"><tr></tr><!-- Dynamic content here --><tbody>
                    </table>
                </div>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="modal-footer">
                    <a type="button" class="btn btn-default" data-dismiss="modal">Cancel</a>
                    <button  type="submit" class="btn btn-primary"  id="save">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--ADD CAR REQUEST-->

<div id="addCarRequestModaldd" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Car Request</h4>
            </div>
            <div class="modal-body">
                <div class="form-data"></div>
                <p>Select the models and press save</p>
            </div>
            <form method="post" action="{{route('statusModelnyear')}}">
                <div style="width: 100%">
                    <table style="box-shadow: none; width: 100%">
                        <tbody id="checkBoxes"><tr></tr><!-- Dynamic content here --><tbody>
                    </table>
                </div>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="modal-footer">
                    <a type="button" class="btn btn-default" data-dismiss="modal">Cancel</a>
                    <button  type="submit" class="btn btn-primary"  id="save">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--END ADD CAR REQUEST-->
<script>
    $(document).ready(function(){
        
        $('#deactivateModal').on('show.bs.modal', function (e) {
            $('#deactivateRef').click(function(){
                var object_id = $(e.relatedTarget).data('id');
                var object_url = $(e.relatedTarget).data('url');
                var activate = $(e.relatedTarget).data('activate');
                var data = { 'id' : object_id, 'activate' : activate};
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type : 'post',
                    url : object_url, //Here you should run query to fetch records
                    data : data, //Here pass id via
                    success : function(url){
                        location.href = url;
                    }
                });
            });

        });
        
        $('#activateModal').on('show.bs.modal', function (e) {
            $('#activateRef').click(function(){
                var object_id = $(e.relatedTarget).data('id');
                var object_url = $(e.relatedTarget).data('url');
                var activate = $(e.relatedTarget).data('activate');
                var data = { 'id' : object_id, 'activate' : activate};
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type : 'post',
                    url : object_url, //Here you should run query to fetch records
                    data : data, //Here pass id via
                    success : function(url){
                        location.href = url;
                    }
                });
            });
        });
        
        $('#deactivateServiceModal').on('show.bs.modal', function (e) {
            $('#deactServiceRef').click(function(){
                var object_id = $(e.relatedTarget).data('id');
                var object_url = $(e.relatedTarget).data('url');
                var object_type = $(e.relatedTarget).data('type');
                var activated = $(e.relatedTarget).data('activated');
                var data = { 'id' : object_id, 'type' : object_type, 'activated' : activated};
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type : 'post',
                    url : object_url,
                    data : data,
                    success : function(url){
                        location.href = url;
                    }
                });
            });
        });
        
        $('#activateServiceModal').on('show.bs.modal', function (e) {
            $('#actServiceRef').click(function(){
                var object_id = $(e.relatedTarget).data('id');
                var object_url = $(e.relatedTarget).data('url');
                var object_type = $(e.relatedTarget).data('type');
                var activated = $(e.relatedTarget).data('activated');
                console.log(object_url);
                var data = { 'id' : object_id, 'type' : object_type, 'activated' : activated};
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type : 'post',
                    url : object_url,
                    data : data,
                    success : function(url){
                        //console.log(url);
                        location.href = url;
                    }
                });
            });
        });
        
        $('#modelnyearModal').on('show.bs.modal', function (e) {
            var object_id = $(e.relatedTarget).data('id');
            var data = { 'model_id' : object_id};
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type : 'get',
                url : '{{route('modelnyear')}}',
                data : data,
                success : function(result){
                    console.log(result);
                    $('#checkBoxes').empty();
                    $('#checkBoxes').append('<tr></tr>');
                    var c = 0;
                    $.each(result.data, function(key, value) {
                        if(c == 4) {
                            c = 0;
                            $('#checkBoxes').append('<tr></tr>');
                        }
                        if(value.activated){
                            $('#checkBoxes > tr:last').append('<td style="padding: 0 10px; text-align: center;"><input type="checkbox"  value='+value.modelnyear_id+' name=checkbox['+key+']/ checked><label style="margin: 0 0 0 10px;" for='+key+'>'+value.name+'</label></td>');
                        }else{
                            $('#checkBoxes > tr:last').append('<td style="padding: 0 10px; text-align: center;"><input type="checkbox"  value='+value.modelnyear_id+' name=checkbox['+key+']/><label style="margin: 0;" for='+key+'>'+value.name+'</label></td>');
                        }
                        c++;
                    });
                }
            });
        });
    });
</script>