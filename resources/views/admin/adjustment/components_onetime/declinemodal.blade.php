
<div class="modal fade" id="modal-decline-{{$id}}" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Confirmation to decline</h4>
      </div>
      <div class="modal-body">
          <p>Are you sure you want to decline?</p>
      </div>
      <div class="modal-footer">
            {!! Form::open(['route' => 'admin.needsApproval.decline_adjustmentOneTime','autocomplete' => 'off']) !!}
            <input type="hidden" name="id" value="{{$id}}">
            <button type="submit" class="btn btn-success"> Decline</button>&nbsp;&nbsp;
            <button class="btn btn-danger" type="button"  data-dismiss="modal"><i calss="fa fa-times"></i> Cancel</button>
            {!! Form::close() !!}
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>
