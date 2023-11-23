<a href="#" class="btn btn-success btn-xs" data-toggle="modal" data-target="#modal-approve-pto-{{$id}}"><i class="fa fa-times"></i> Approve</a>

 
<div class="modal fade" id="modal-approve-pto-{{$id}}" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Approve Hours</h4>
      </div>
      <div class="modal-body">
          <p>Are you sure you want to approve hours?</p>
      </div>
      <div class="modal-footer">
            {!! Form::open(['route' => 'admin.approvePTO','autocomplete' => 'off']) !!}
            <input type="hidden" name="pto_id" value="{{$id}}">
            <button type="submit" class="btn btn-success"> OK</button>&nbsp;&nbsp;
            <button class="btn btn-danger" type="button"  data-dismiss="modal"><i calss="fa fa-times"></i> Cancel</button>
            {!! Form::close() !!}
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>
