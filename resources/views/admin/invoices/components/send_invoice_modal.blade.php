
<div class="modal fade" id="modal-send-{{$id}}" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Confirmation to send invoice.</h4>
      </div>
      <div class="modal-body">
          <div class="modal-body">
                <p>Are you sure you want to send invoice?</p>
          </div>
          <div class="modal-footer">
                {!! Form::open(['route' => 'admin.invoices.send_client','autocomplete' => 'off']) !!}
                <input type="hidden" name="id" value="{{$id}}">
                <button type="submit" class="btn btn-success"><i class="fa fa-check"></i>&nbsp;&nbsp; Send </button>&nbsp;&nbsp;
                <button class="btn btn-danger" type="button"  data-dismiss="modal"><i calss="fa fa-times"></i> Cancel</button>
                {!! Form::close() !!}
          
                 
          </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div>
  </div>
</div>
