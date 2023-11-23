
<div class="modal fade" id="modal-decline-{{$id}}" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Confirmation to decline</h4>
      </div>
      <div class="modal-body">
          <p>Would you like to make an adjustment before returning the invoice to the "Needs Sent" queue?</p>
      </div>
      <div class="modal-footer">
            {!! Form::open(['route' => 'payroll.invoices.declinebyclient','autocomplete' => 'off']) !!}
            <input type="hidden" name="id" value="{{$id}}">
            <!-- <a href="{{route('payroll.adjustment.create')}}" class="btn btn-success"><i class="fa fa-refresh">&nbsp;&nbsp; To Adjustment</i></a> -->
            <button type="submit" class="btn btn-warning"><i calss="fa fa-check"></i> Decline</button>&nbsp;&nbsp;
            <button class="btn btn-danger" type="button"  data-dismiss="modal"><i calss="fa fa-close"></i> Cancel</button>
            {!! Form::close() !!}
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>
