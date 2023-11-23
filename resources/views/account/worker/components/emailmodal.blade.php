<style>
    .client_email{
        background-color:ghostwhite;
        border-radius: 3px;
        padding: 5px; 
        margin: 5px;
        border: 1px solid gainsboro;
        float:left !important;
    } 
    #new_client_email{font-size: 18px;}
</style>
<div class="modal fade" id="modal-sendemail-{{$id}}" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Compile/Send Video Profile to Clients</h4>
      </div>
      {!! Form::open(['route' => 'account.worker.sendVideoProfile','autocomplete' => 'off']) !!}
      <div class="modal-body row">
           <div class="col-md-12 ClientsEmails">
              <div class="col-md-12 form-group">
                  <label>- Add Additional Emails:</label>
              </div>
              <div class="col-md-12 form-group">
                  &nbsp;&nbsp;<input id="new_client_email" type="email" placeholder="example@gmail.com" class="noucase" data-toggle="tooltip" data-placement="top" title="Add one or more emails." >
                  <button type="button" class="btn btn-warning add_client_email" ><i class="fa fa-plus"></i> Add</button>
                  <p class="validation_error_weekly" style="color: red;display: none">Valid email type required.</p>
              </div>
              <div class="col-md-12 form-group">
                  <label>- Emails List:</label>
              </div>
              <div class="col-md-12 form-group clients_emails"><?php $nm=0; ?> 
                  @foreach (explode(',',$client_emails) as $email)
                  @if($email)
                  <?php $nm++; ?> 
                  <div class="client_email {{$nm}}" id="{{$nm}}">{{$email}}
                    <input type="hidden" value="{{$email}}" name="client_email[{{$nm}}]" />
                      &nbsp;&nbsp;<a class="close_client_email" onclick="close_client_email({{$nm}})"><i class="fa fa-close"></i></a>
                  </div>
                  @endif
                  @endforeach 
              </div>
          </div> 
      </div>
      <div class="modal-footer">
            <input type="hidden" name="id" value="{{$id}}">
            <button type="submit" class="btn btn-success"> Send Email</button>&nbsp;&nbsp;
            <button class="btn btn-danger" type="button"  data-dismiss="modal"><i calss="fa fa-times"></i> Cancel</button>
      </div>
      {!! Form::close() !!}
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>
