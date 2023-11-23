<a href="#" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#modal-decline-pto-{{$id}}"><i class="fa fa-times"></i> Decline</a>

<div class="modal fade" id="modal-decline-pto-{{$id}}" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Decline Hours</h4>
      </div>
      <div class="modal-body">
          <p>Are you sure you want to decline hours?</p>
      </div>
      <div class="modal-footer">
            {!! Form::open(['route' => 'admin.declinePTO','autocomplete' => 'off']) !!}
            <input type="hidden" name="pto_id"  value="{{$id}}">
              <button class="btn btn-success" type="submit"><i calss="fa 
              fa-times"></i> OK</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>&nbsp;&nbsp;
            {!! Form::close() !!}
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>
