<a href="#" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#modal-delete-holiday-{{$id}}"><i class="fa fa-close"></i></a>

 
<div class="modal fade" id="modal-delete-holiday-{{$id}}" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Delete Holiday</h4>
      </div>
      {!! Form::open(['route' => 'admin.deleteholiday','autocomplete' => 'off']) !!}
      <input type="hidden" name="holiday_id" value="{{$id}}">
      <div class="modal-body">
           <p>Are you sure to delete this holiday from default holiday?</p>
      </div>
      <div class="modal-footer">
            <button type="submit" class="btn btn-success"> Yes</button>&nbsp;&nbsp;
            <button class="btn btn-danger" type="button"  data-dismiss="modal"><i calss="fa fa-times"></i> No</button>
      </div>
      {!! Form::close() !!}
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>
